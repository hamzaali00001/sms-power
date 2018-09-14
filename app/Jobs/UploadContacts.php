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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '-1');
        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $filePath = $storagePath . $this->fileUpload->location;

        try {
            DB::beginTransaction();

            // Loading file as a spreadsheet
            $inputFileType = IOFactory::identify($filePath);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($filePath);

            $invalidData = collect();
            $validContacts = collect();

            // Name of the columns
            $columnNames = [];

            // cell values in a row
            $rowData = [];

            // Message to send to the user
            $message = '';

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
                }

                // Removing Duplicates
                $validContacts = $validContacts->unique('mobile');

                // Database Seeding
                foreach ($validContacts->chunk(1000) as $chunk) {
                    DB::table('contacts')->insert($chunk->toArray());
                }

                DB::commit();
            }

            if ($invalidData->count() == 0 && (trim($message) == true)) { // Informing user about successful upload
//                $this->fileUpload->user->notify(new \App\Notifications\FileUploaded());
                info('File uploaded successfully');
            } else {
                //Creating Error message
                $message = $this->createInvalidMessage($validContacts, $invalidData, $message);

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
    private function createInvalidMessage($validContacts, $invalidData, $message)
    {
        if ($validContacts->count() > 0) {
            $message .= "Your file has uploaded <b>{$validContacts->count()} Contacts </b> with some errors!</br>";
        } else {
            $message .= "Your file did not save any contact! You have some errors!";
        }

        $invalidGroups = $invalidData->groupBy('type');

        foreach ($invalidGroups as $type => $items) {
            switch ($type) {
                case 'group_existing':
                    if ($items->count() > 0) $message .= "Your file contains {$items->count()} existing contacts in group! </br>";
                    break;
                case 'wrong_country':
                    if ($items->count() > 0) $message .= "Your file contains {$items->count()} contacts that are not from Kenya! </br>";
                    break;
                case 'not_mobile':
                    if ($items->count() > 0) $message .= "Your file contains {$items->count()} that are not mobile! </br>";
                    break;
                case 'duplicate_file':
                    if ($items->count() > 0) $message .= "Your file contains {$items->count()} duplicated contacts! </br>";
                    break;
                case 'missing_country_code_error':
                    if ($items->count() > 0) $message .= "Your file contains {$items->count()} contacts with missing country code! </br>";
                    break;
                case 'invalid_parameter_error':
                    if ($items->count() > 0) $message .= "Your file contains {$items->count()} contacts with invalid parameterd! </br>";
                    break;
                case 'number_format_error':
                    if ($items->count() > 0) $message .= "Your file contains {$items->count()} contacts with wrong number format! </br>";
                    break;
                case 'number_parser_error':
                    if ($items->count() > 0) $message .= "Your file contains {$items->count()} contacts which number could not be parsed! </br>";
                    break;
                case 'general':
                    if ($items->count() > 0) $message .= "Your file contains {$items->count()} wrong contacts! </br>";
                    break;
            }
        }

        return $message;
    }
}
