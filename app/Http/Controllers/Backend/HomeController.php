<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ScheduledMessage;

class HomeController extends Controller
{
    /**
     * Show the dashboard.
     *
     * @return Response
     */
    public function __invoke()
    {
        if (auth()->user()->hasRole('admin')) {
            $messages = ScheduledMessage::with('user')->limit(10)->latest()->get();
        } else {
            $messages = auth()->user()->scheduledMessages()->with('user')->limit(10)->latest()->get();
        }

        return view('backend.pages.home', compact('messages'));
    }
}
