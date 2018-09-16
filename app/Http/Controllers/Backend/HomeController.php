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
            $messages = ScheduledMessage::with('user')->latest()->limit(10)->get();
        } else {
            $messages = auth()->user()->scheduledMessages()->with('user')->latest()->limit(10)->get();
        }

        return view('backend.pages.home', compact('messages'));
    }
}
