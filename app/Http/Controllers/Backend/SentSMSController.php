<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SentMessage;

class SentSMSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $messages = SentMessage::with('user')->latest()->get();
        } else {
            $messages = auth()->user()->sentMessages()->with('user')->latest()->get();
        }

        return view('backend.sent-sms.index', compact('messages'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\SentMessage $sent_sm
     * @return \Illuminate\Http\Response
     */
    public function destroy(SentMessage $sent_sm)
    {
        $sent_sm->delete();

        flash()->success('The message has been deleted successfully.');
        
        return redirect()->route('sent-sms.index');
    }
}