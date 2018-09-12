<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ScheduledMessage;

class ScheduledSMSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $messages = ScheduledMessage::with('user')->latest()->get();
        } else {
            $messages = auth()->user()->scheduledMessages()->with('user')->latest()->get();
        }

        return view('backend.scheduled-sms.index', compact('messages'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\ScheduledMessage $sent_sm
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScheduledMessage $scheduled_sm)
    {
        // Update the user's credit;
        if (!auth()->user()->hasRole('admin')) {
            auth()->user()->update([
               'credit' => auth()->user()->credit + $scheduled_sm->cost
            ]);
        }

        $scheduled_sm->delete();

        flash()->success('The scheduled message has been cancelled.');
        
        return redirect()->route('scheduled-sms.index');
    }
}