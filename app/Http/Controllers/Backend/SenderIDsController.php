<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\SenderIDs\CreateSenderIDRequest;
use App\Http\Requests\Backend\SenderIDs\UpdateSenderIDRequest;
use App\Http\Controllers\Controller;
use App\Models\SenderId;
use Jenssegers\Optimus\Optimus;

class SenderIDsController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('sender-ids');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $senderids = SenderId::with('user')->latest()->get();
        } else {
            $senderids = auth()->user()->senderids()->with('user')->latest()->get();
        }

        return view('backend.senderids.index', compact('senderids'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Jenssegers\Optimus\Optimus $request
     * @return \Illuminate\Http\Response
     */
    public function create(Optimus $optimus)
    {
        return view('backend.senderids.create', compact('optimus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Backend\SenderIDs\CreateSenderIDRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSenderIDRequest $request)
    {
        SenderId::create($request->all());

        flash()->success('The Sender ID has been created successfully.');

        return redirect()->route('senderids.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Models\SenderId $senderid
     * @return \Illuminate\Http\Response
     */
    public function edit(SenderId $senderid)
    {
        return view('backend.senderids.edit', compact('senderid'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Backend\SenderIDs\UpdateSenderIDRequest $request
     * @param  App\Models\SenderId $senderid
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSenderIDRequest $request, SenderId $senderid)
    {
        $senderid->update($request->all());

        flash()->success('The Sender ID has been updated successfully.');

        return redirect()->route('senderids.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\SenderId $senderid
     * @return \Illuminate\Http\Response
     */
    public function destroy(SenderId $senderid)
    {
        $senderid->delete();

        flash()->success('The Sender ID has been deleted successfully.');
        
        return redirect()->route('senderids.index');
    }
}
