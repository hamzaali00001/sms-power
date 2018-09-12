<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Contacts\CreateContactRequest;
use App\Http\Requests\Backend\Contacts\UpdateContactRequest;
use Propaganistas\LaravelPhone\PhoneNumber;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Group;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        if (auth()->user()->hasRole('admin')) {
            $contacts = Contact::with('user')->latest()->get();
        } else {
            $contacts = auth()->user()->contacts()->with('user')->latest()->get();
        }

        return view('backend.contacts.index', compact(['contacts', 'group']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function create(Group $group)
    {
        return view('backend.contacts.create', compact('group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Backend\Contacts\CreateContactRequest $request
     * @param  App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function store(CreateContactRequest $request, Group $group)
    {
        // Validate that the number is a mobile number
        if (!PhoneNumber::make($request->get('mobile'))->isOfType('mobile')) {
            
            flash()->error("$request->get('mobile') is not a valid mobile number.");

            return back();
        // Validate that the mobile number is of the given country - Kenya
        } elseif (!PhoneNumber::make($request->get('mobile'))->isOfCountry('KE')) {
            
            flash()->error("$request->get('mobile') is not a valid Kenyan mobile number.");

            return back();
        // Check that the mobile number does not exist in that group.
        } elseif (condition) {
            
            flash()->error("$request->get('mobile') already exists in this group.");

            return back();
        // Insert the contact into the database.
        } else {
            Contact::create([
                'user_id' => auth()->user()->id,
                'group_id' => $group->id,
                'name' => $request->get('name'),
                'mobile' => PhoneNumber::make($request->get('mobile'), 'KE')->formatE164()
            ]);

            flash()->success('The contact has been created successfully.');

            return redirect()->route('groups.contacts.index', $group);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Models\Group $group
     * @param  App\Models\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group, Contact $contact)
    {
        return view('backend.contacts.edit', compact('group', 'contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Backend\Contacts\UpdateContactRequest $request
     * @param  App\Models\Group $group
     * @param  App\Models\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContactRequest $request, Group $group, Contact $contact)
    {
        $contact->update([
            'user_id' => auth()->user()->id,
            'group_id' => $group->id,
            'name' => $request->get('name'),
            'mobile' => PhoneNumber::make($request->get('mobile'), 'KE')->formatE164()
        ]);

        flash()->success('The contact has been updated successfully.');

        return redirect()->route('groups.contacts.index', $group);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Group $group
     * @param  App\Models\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, Contact $contact)
    {
        $contact->delete();

        flash()->success('The contact has been deleted successfully.');
        
        return redirect()->route('groups.contacts.index', $group);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMultiple(Request $request)
    {
        //$group->contacts()->whereIn('id', $request->get('rowCheck'))->delete();
        Contact::whereIn('id', [$request->get('rowCheck')])->delete();

        flash()->success('The contacts have been deleted successfully.');

        return redirect()->back();
    }

    /**
     * Display sample contacts file.
     *
     * @return \Illuminate\Http\Response
     */
    public function sample()
    {
        return response()->download(public_path('files/sample-contacts.xls'));
    }
}