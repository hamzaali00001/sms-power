<?php

namespace App\Jobs;

use App\Models\FileUpload;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Propaganistas\LaravelPhone\PhoneNumber;

class UploadContacts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileUpload;
    protected $group;
    protected $invalidCount = [];
    protected $validContactsCount;
    protected $chunkSize = 100;

    /**
     * Create a new job instance.
     *
     * @param FileUpload $fileUpload
     * @param Group $group
     */
    public function __construct(FileUpload $fileUpload, Group $group)
    {
        $this->fileUpload = $fileUpload;

        $this->group = $group;

        for ($i = 0; $i < 9; $i++) {
            $this->invalidCount[$i] = 0;
        }

        $this->validContactsCount = 0;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '500000');

        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $filePath = $storagePath . $this->fileUpload->location;

        try {
            DB::beginTransaction();

            // Loading file as a spreadsheet
            $inputFileType = IOFactory::identify($filePath);
            $reader = IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);

            $invalidData = collect();
            $validContacts = collect();

            // Name of the columns
            $columnNames = [];

            // cell values in a row
            $rowData = [];

            // Message to send to the user
            $message = '';

            // Invalid Data Count
            $invalidDataCount = 0;

            foreach ($spreadsheet->getWorksheetIterator() as $index => $worksheet) {
                $rowIterator = $worksheet->getRowIterator();

                // Get column names
                foreach ($rowIterator->current()->getCellIterator() as $cell) {
                    if ($cell->getValue()) {
                        $columnNames[] = mb_strtolower($cell->getValue());
                    }
                }

                // Check if the mobile column exists
                if (!in_array('mobile', $columnNames)) {
                    $message = "Mobile Field column is missing in worksheet number {$index}</br>";
                    continue;
                }

                // Moving iterator to the next to pass the first row which has column names
                foreach ($rowIterator as $row) {
                    if ($rowIterator->key() <= 1) {
                        continue;
                    }

                    $iteration = 0;
                    foreach ($row->getCellIterator() as $cell) {
                        // Adding data with column names as key and cell data as value
                        $rowData += [$columnNames[$iteration++] => $cell->getValue()];

                        if ($iteration > count($columnNames) - 1) break;
                    }

                    // Validates data and populates valid and invalid arrays
                    $this->validateData($rowData, $validContacts, $invalidData);
                    $rowData = [];

                    if ($validContacts->count() > $this->chunkSize) {
                        $validContacts = $validContacts->unique('mobile');
                        DB::table('contacts')->insert($validContacts->toArray());

                        DB::commit();

                        $this->validContactsCount += $validContacts->count();
                        $validContacts = collect();
                    }

                    if ($invalidData->count() > $this->chunkSize) {
                        $invalidDataCount += $invalidData->count();

                        $this->addInvalidCount($invalidData);
                        $invalidData = collect();
                    }
                }

                // Removing Duplicates
                $validContacts = $validContacts->unique('mobile');

                // Database Seeding
                foreach ($validContacts->chunk($this->chunkSize) as $chunk) {
                    DB::table('contacts')->insert($chunk->toArray());
                }

                DB::commit();
            }

            $this->validContactsCount += $validContacts->count();

            $this->addInvalidCount($invalidData);

            if ($invalidDataCount == 0 && (trim($message) == true)) { // Informing user about successful upload
//                $this->fileUpload->user->notify(new \App\Notifications\FileUploaded());
                info('File uploaded successfully');
            } else {
                //Creating Error message
                $message = $this->createInvalidMessage($invalidData, $message);

                info($message);
                // Notifying user about all the files with failed uploads
//                $this->fileUpload->user->notify(new \App\Notifications\FileUploadedWithErrors($message));
            }
        } catch (\Exception $exception) {
            Log::error(
                "Error uploading file: {$exception->getMessage()} on {$exception->getFile()} : {$exception->getLine()}" .
                PHP_EOL .
                $exception->getTraceAsString()
            );

            DB::rollBack();

            Storage::delete($this->fileUpload->location);
        }
    }

    /**
     * Validates mobile number and add them into either validContacts or invalidData
     *
     * @param array $rowData
     * @param Collection $validContacts
     * @param Collection $invalidData
     * @return void
     */
    private function validateData($rowData, &$validContacts, &$invalidData)
    {
        $mobile = $rowData['mobile'];

        $group_contacts_mobile = $this->fileUpload->group->contacts->pluck('mobile')->toArray();

        try {
            $mobileFormatted = PhoneNumber::make($mobile, 'KE')->formatE164();

            // Check if exists in Users Group
            if (in_array($mobileFormatted, $group_contacts_mobile)) {
                $invalidData->push([
                    'type' => 'group_existing',
                    'mobile' => $mobile,
                    'message' => "The mobile {$mobile} exists already in this group!",
                ]);
            } // Check If type is mobile
            elseif (!PhoneNumber::make($mobileFormatted)->isOfType('mobile')) {
                $invalidData->push([
                    'type' => 'not_mobile',
                    'mobile' => $mobile,
                    'message' => "The mobile {$mobile} is not mobile!",
                ]);
            } // Check if country is KE
            elseif (!PhoneNumber::make($mobileFormatted)->isOfCountry('KE')) {
                $invalidData->push([
                    'type' => 'wrong_country',
                    'mobile' => $mobile,
                    'message' => "The mobile {$mobile} is not Kenyan!",
                ]);
            } // Contact Data is verified, push into validContacts
            else {
                $date = Carbon::now()->toDateTimeString();

                $validContacts->push([
                    'group_id' => $this->group->id,
                    'user_id' => $this->group->user_id,
                    'mobile' => $mobileFormatted,
                    'created_at' => $date,
                    'updated_at' => $date,
                    'name' => $rowData['name'],
                ]);
            }
        } catch (\Exception $e) {
            $invalidData->push([
                'type' => 'general',
                'mobile' => $mobile,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Creates Invalid Message by iterating through invalid data
     *
     * @param $validContacts
     * @param Collection $invalidData
     * @param string $message
     * @return string
     */
    private function createInvalidMessage($invalidData, $message)
    {
        if ($this->validContactsCount > 0) {
            $message .= "Your file has uploaded <b>{$this->validContactsCount} Contacts </b> with some errors!</br>";
        } else {
            $message .= "Your file did not save any contact! You have some errors!";
        }

        $invalidGroups = $invalidData->groupBy('type');

        foreach ($invalidGroups as $type => $items) {
            switch ($type) {
                case 'group_existing':
                    if ($this->invalidCount[0] > 0) $message .= "Your file contains {$this->invalidCount[0]} existing contacts in group! </br>";
                    break;
                case 'wrong_country':
                    if ($this->invalidCount[1] > 0) $message .= "Your file contains {$this->invalidCount[1]} contacts that are not from Kenya! </br>";
                    break;
                case 'not_mobile':
                    if ($this->invalidCount[2] > 0) $message .= "Your file contains {$this->invalidCount[2]} that are not mobile! </br>";
                    break;
                case 'duplicate_file':
                    if ($this->invalidCount[3] > 0) $message .= "Your file contains {$this->invalidCount[3]} duplicated contacts! </br>";
                    break;
                case 'missing_country_code_error':
                    if ($this->invalidCount[4] > 0) $message .= "Your file contains {$this->invalidCount[4]} contacts with missing country code! </br>";
                    break;
                case 'invalid_parameter_error':
                    if ($this->invalidCount[5] > 0) $message .= "Your file contains {$this->invalidCount[5]} contacts with invalid parameterd! </br>";
                    break;
                case 'number_format_error':
                    if ($this->invalidCount[6] > 0) $message .= "Your file contains {$this->invalidCount[6]} contacts with wrong number format! </br>";
                    break;
                case 'number_parser_error':
                    if ($this->invalidCount[7] > 0) $message .= "Your file contains {$this->invalidCount[7]} contacts which number could not be parsed! </br>";
                    break;
                case 'general':
                    if ($this->invalidCount[8] > 0) $message .= "Your file contains {$this->invalidCount[8]} wrong contacts! </br>";
                    break;
            }
        }

        return $message;
    }

    /**
     * Adds Invalid data count
     *
     * @param Collection $invalidData
     */
    private function addInvalidCount($invalidData)
    {
        $invalidGroups = $invalidData->groupBy('type');

        foreach ($invalidGroups as $type => $items) {
            switch ($type) {
                case 'group_existing':
                    $this->invalidCount[0] += $items->count();
                    break;
                case 'wrong_country':
                    $this->invalidCount[1] += $items->count();
                    break;
                case 'not_mobile':
                    $this->invalidCount[2] += $items->count();
                    break;
                case 'duplicate_file':
                    $this->invalidCount[3] += $items->count();
                    break;
                case 'missing_country_code_error':
                    $this->invalidCount[4] += $items->count();
                    break;
                case 'invalid_parameter_error':
                    $this->invalidCount[5] += $items->count();
                    break;
                case 'number_format_error':
                    $this->invalidCount[6] += $items->count();
                    break;
                case 'number_parser_error':
                    $this->invalidCount[7] += $items->count();
                    break;
                case 'general':
                    $this->invalidCount[8] += $items->count();
                    break;
            }
        }
    }
}
