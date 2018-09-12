<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Groups\CreateGroupRequest;
use App\Http\Requests\Backend\Groups\UpdateGroupRequest;
use App\Http\Controllers\Controller;
use App\Models\Group;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $groups = Group::with('user', 'contacts')->latest()->get();
        } else {
            $groups = auth()->user()->groups()->with('user', 'contacts')->latest()->get();
        }

        return view('backend.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Backend\Groups\CreateGroupRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGroupRequest $request)
    {
        Group::create($request->all());

        flash()->success('The group has been created successfully.');

        return redirect()->route('groups.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        return view('backend.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Backend\Groups\UpdateGroupRequest $request
     * @param  App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        $group->update($request->all());

        flash()->success('The group has been updated successfully.');

        return redirect()->route('groups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Group $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();

        flash()->success('The group has been deleted successfully.');
        
        return redirect()->route('groups.index');
    }
}
