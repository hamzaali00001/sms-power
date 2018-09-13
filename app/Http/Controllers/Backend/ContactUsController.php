<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Pages\ContactUsRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Backend\ContactUs;

class ContactUsController extends Controller
{
    /**
     * Display Contact us Page.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
    	return view('backend.pages.contact-us');
    }

    /**
     * Send the message.
     *
     * @param  App\Http\Requests\Backend\Pages\ContactUsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function sendEmail(ContactUsRequest $request)
    {
        $data = [
            'email'=>auth()->user()->email,
            'name' => auth()->user()->name,
            'phone' => auth()->user()->mobile,
            'subject' => request('subject'),
            'message' => request('message')
        ];

        Mail::to(env('ADMIN_EMAIL'))->send(new ContactUs($data));

        flash()->success('Thank you for contacting us. We will get back to you soon.');
        
        return back();
    }
}
