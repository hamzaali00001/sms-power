<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Users\CreateUserRequest;
use App\Http\Requests\Backend\Users\UpdateUserRequest;
use App\Http\Controllers\Controller;
use Jenssegers\Optimus\Optimus;
use App\Models\User;
use App\Models\Role;

class UsersController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('users')->except('show', 'edit', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Jenssegers\Optimus\Optimus $optimus
     * @return \Illuminate\Http\Response
     */
    public function index(Optimus $optimus)
    {
        $users = User::whereNull('parent_id')->with('role')->latest()->get();

        return view('backend.users.index', compact('users', 'optimus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::whereIn('id', [1, 2, 3])->get();
        $timezones = \DateTimeZone::listIdentifiers();

        return view('backend.users.create', compact('roles', 'timezones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Backend\Users\CreateUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        User::create($request->all());

        //$password = User::generatePassword();

        event(new UserCreated($user));

        flash()->success('The user account has been created successfully.');

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\User $user
     * @param  Jenssegers\Optimus\Optimus $optimus
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Optimus $optimus)
    {
        return view('backend.users.show', compact('user', 'optimus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::whereIn('id', [1, 2, 3])->get();
        $timezones = \DateTimeZone::listIdentifiers();

        return view('backend.users.edit', compact('user', 'roles', 'timezones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Backend\Users\UpdateUserRequest $request
     * @param  App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());

        flash()->success('The user account has been updated successfully.');

        return redirect()->route('users.show', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        flash()->success('The user account has been deleted successfully.');

        return redirect()->route('users.index');
    }
}
