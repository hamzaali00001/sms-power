<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Contacts\UploadContactsRequest;
use App\Http\Controllers\Controller;
use App\Jobs\UploadContacts;
use App\Models\FileUpload;
use App\Models\Group;

class UploadContactsController extends Controller
{
    /**
     * Upload the contacts file
     *
     * @param  App\Http\Requests\Backend\Contacts\UploadContactsRequest $request
     * @param  App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UploadContactsRequest $request, Group $group)
    {
        if ($request->filename->isValid()) {

            $fileUpload = FileUpload::create([
                'user_id' => auth()->user()->id,
                'group_id' => $group->id,
                'filename' => $request->filename->hashName(),
                'extension' => $request->filename->extension(),
                'filesize' => $request->filename->getClientSize(),
                'location' => $request->filename->store('contact-files')
            ]);

            flash()->success('Your file is currently being processed. You will be notified when done.');

            dispatch(new UploadContacts($fileUpload, $group))->onQueue('file-uploads');
        } else {
            flash()->error('There was a problem uploading your file. Please try again.');
        }

        return back();
    }
}
