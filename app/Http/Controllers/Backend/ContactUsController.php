<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Pages\ContactUsRequest;
use App\Http\Controllers\Controller;
use App\Jobs\SendContactUsEmail;

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
        dispatch(new SendContactUsEmail());

        flash()->success('Thank you for contacting us. We will get back to you soon.');
        
        return back();
    }
}
