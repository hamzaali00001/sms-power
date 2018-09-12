<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ScheduledMessage;
use App\Models\SentMessage;

class SendSMSController extends Controller
{
    /**
     * Show the form for sending messages.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = auth()->user()->groups()->withCount([
            'contacts' => function($query) {
                $query->active();
            }
        ])
        ->has('contacts', '>', 0)
        ->orderBy('name')
        ->get();

        $senderids = auth()->user()->senderids()->active()->orderBy('name')->get();
        $templates = auth()->user()->templates()->orderBy('name')->get();

        return view('backend.send-sms.create', compact(['groups', 'senderids', 'templates']));
    }

    /**
     * Send Single SMS.
     *
     * @return \Illuminate\Http\Response
     */
    public function singleSMS()
    {
        if (request('schedule') === 'No') {
            dispatch(new \App\Jobs\SendSMS);
        } elseif (request('schedule') === 'Yes') {
            dispatch(new \App\Jobs\ScheduleSMS);
        }

        return redirect()->back();
    }

    /**
     * Send Bulk SMS.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function bulkSMS(Request $request)
    {
        if (request('schedule') === 'No') {
            dispatch(new \App\Jobs\SendSMS);
        } elseif (request('schedule') === 'Yes') {
            dispatch(new \App\Jobs\ScheduleSMS);
        }

        return redirect()->back();
    }
}
