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
        $file = $request->filename;

        if ($file->isValid()) {

            $fileUpload = FileUpload::create([
                'user_id' => auth()->user()->id,
                'group_id' => $group->id,
                'filename' => $file->hashName(),
                'extension' => $file->extension(),
                'filesize' => $file->getClientSize(),
                'location' => $file->store('contact-files')
            ]);

            flash()->success('Your contacts are currently being processed.');

            dispatch(new UploadContacts($fileUpload, $group));
        }

        return redirect()->route('groups.contacts.index', $group);
    }
}
