<?php

namespace App\Jobs;

use App\Models\FileUpload;
use App\Models\Group;
use Carbon\Carbon;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Propaganistas\LaravelPhone\PhoneNumber;
use Storage;

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
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $filePath = $storagePath.$this->fileUpload->location;

        try {
            DB::beginTransaction();

            $inputFileType = IOFactory::identify($filePath);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($filePath);

            /** @var Collection $invalid_data */
            $invalid_data = collect();

            /** @var Collection $contacts */
            $valid_contacts = collect();

            $data = [];

            $existing_group_contacts_db = $this->fileUpload->group->contacts;

            $existing_group_contacts_db_mobile = array();

            foreach($existing_group_contacts_db as $existing_contacts){
                array_push($existing_group_contacts_db_mobile, $existing_contacts->getOriginal()['mobile']);
            }

            $allowed_columns = ['name', 'mobile'];

            /** @var Worksheet $worksheet */
            foreach ($spreadsheet->getWorksheetIterator() as $index=>$worksheet) {

                $worksheetData = $worksheet->toArray();

                //First time get header
                //if not contains correct columns through an exception
                if ($index == 0) {

                    $header = array_shift($worksheetData);

                    $header = array_map('mb_strtolower', $header);

                    $array_keys = array_flip($header);

                    $mached_col = array_intersect($allowed_columns, $header);

                    foreach ($allowed_columns as $key => $allow_field) {

                        if (!in_array($allow_field, $header)) {
                            throw new \Exception('Your file does not contain a '. $allow_field .' named column!');
                        }
                    }
                } else {
                    array_shift($worksheetData);
                }

                foreach ($worksheetData as $key => $field) {
                   array_push($data, array('name'=>$field[$array_keys['name']], 'mobile'=>$field[$array_keys['mobile']]));
                }
            }

            $date = Carbon::now()->toDateTimeString();

            $toBeInserted = [];

            $dataCount = count($data);

            foreach ($data as $index => $contactData) {

                $mobile = $contactData['mobile'];

                try{

                    $contactData['mobile'] = PhoneNumber::make($contactData['mobile'], 'KE')->formatE164();

                    //CHECK IF EXISTS IN USERS GROUP
                    if (in_array($contactData['mobile'], $existing_group_contacts_db_mobile)) {
                        $invalid_data->push([
                            'type' => 'group_existing',
                            'mobile' => $mobile,
                            'message' => "The mobile {$mobile} exists already in this group!",
                        ]);
                    }
                    //CHECK IF TYPE IS MOBILE
                    elseif (!PhoneNumber::make($contactData['mobile'])->isOfType('mobile'))
                    {
                        $invalid_data->push([
                            'type' => 'not_mobile',
                            'mobile' => $mobile,
                            'message' => "The mobile {$mobile} is not mobile!",
                        ]);
                    }
                    //CHECK IF TYPE IS MOBILE
                    elseif (!PhoneNumber::make($contactData['mobile'])->isOfCountry('KE'))
                    {
                        $invalid_data->push([
                            'type' => 'wrong_country',
                            'mobile' => $mobile,
                            'message' => "The mobile {$mobile} is not Kenyan!",
                        ]);
                    }
                    else
                    {
                        $contactData = array_merge(array_filter($contactData),[
                            'group_id' => $this->group->id,
                            'user_id' => $this->group->user_id,
                            'created_at' => $date,
                            'updated_at' => $date,
                        ]);

                        $valid_contacts->push($contactData);
                    }
                }
                catch (\Exception $exception) {
                    $invalid_data->push([
                        'type' => 'general',
                        'mobile' => $mobile,
                        'message' => $exception->getMessage(),
                    ]);
                }
            }
            
            //remove duplicate entry
            $valid_contacts = $valid_contacts->unique('mobile');

            foreach (collect($valid_contacts->toArray())->chunk(1000) as $chunk) {
                DB::table('contacts')->insert($chunk->toArray());
            }

            DB::commit();
            
            if ($invalid_data->count() == 0) {
                $this->fileUpload->user->notify(new \App\Notifications\FileUploaded());
            }
            else {

                $grouped = $invalid_data->groupBy('type');

                $message = null;

                if ($valid_contacts->count() > 0) {
                    $message = "Your file has uploaded <b>{$valid_contacts->count()} Contacts </b> with some errors!</br>";
                } else {
                    $message = "Your file did not save any contact! You have some errors!";
                }

                /** @var Collection $items */
                foreach ($grouped as $type=> $items) {
                    switch ($type) {
                        case 'group_existing':
                            if($items->count() > 0) $message .= "Your file contains {$items->count()} existing contacts in group! </br>";
                            break;
                        case 'wrong_country':
                            if($items->count() > 0) $message .= "Your file contains {$items->count()} contacts that are not from Kenya! </br>";
                            break;
                        case 'not_mobile':
                            if($items->count() > 0) $message .= "Your file contains {$items->count()} that are not mobile! </br>";
                            break;
                        case 'duplicate_file':
                            if($items->count() > 0) $message .= "Your file contains {$items->count()} duplicated contacts! </br>";
                            break;
                        case 'missing_country_code_error':
                            if($items->count() > 0) $message .= "Your file contains {$items->count()} contacts with missing country code! </br>";
                            break;
                        case 'invalid_parameter_error':
                            if($items->count() > 0) $message .= "Your file contains {$items->count()} contacts with invalid parameterd! </br>";
                            break;
                        case 'number_format_error':
                            if($items->count() > 0) $message .= "Your file contains {$items->count()} contacts with wrong number format! </br>";
                            break;
                        case 'number_parser_error':
                            if($items->count() > 0) $message .= "Your file contains {$items->count()} contacts which number could not be parsed! </br>";
                            break;
                        case 'general':
                            if($items->count() > 0) $message .= "Your file contains {$items->count()} wrong contacts! </br>";
                            break;
                    }
                }

                $this->fileUpload->user->notify(new \App\Notifications\FileUploadedWithErrors($message));
            }
        } catch (\Exception $exception) {

            Log::error("Error uploading file: ". $exception->getMessage() . ' on ' . $exception->getFile() . ':' . $exception->getLine() . PHP_EOL . $exception->getTraceAsString());

            DB::rollBack();

            Storage::delete($this->fileUpload->filename);
        }
    }
}
