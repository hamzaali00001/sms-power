<?php

namespace App\Http\Requests\Backend\Contacts;

use Illuminate\Foundation\Http\FormRequest;

class UploadContactsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $mime_types = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
            'application/vnd.ms-excel.addin.macroenabled.12',
            'application/vnd.ms-excel.sheet.binary.macroenabled.12',
            'application/vnd.ms-excel.sheet.macroenabled.12',
            'application/vnd.oasis.opendocument.spreadsheet',
            'text/csv',
            'text/plain'
        ];

        return [
            'filename' => 'required|file|mimetypes:'.implode($mime_types,',').'|max:51200'
        ];
    }

    public function messages()
    {
        return [
            'filename.mimetypes' => 'Only the following file extensions are allowed: .xls, .xlsx, csv, .txt or odds',
            'filename.required' => 'Please Upload a file',
            'filename.file' => 'Your file is not successfully uploaded'
        ];
    }
}
