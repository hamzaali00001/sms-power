<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\SubAccounts\CreateSubAccountRequest;
use App\Http\Requests\Backend\SubAccounts\UpdateSubAccountRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Jenssegers\Optimus\Optimus;
use Yajra\Datatables\Datatables;
use App\Events\Users\UserCreated;
use App\Models\Role;
use App\Models\User;

class SubAccounts2Controller extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('sub-accounts');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Jenssegers\Optimus\Optimus $optimus
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Optimus $optimus)
    {
        if (auth()->user()->hasRole('admin')) {
            $users = User::whereNotNull('parent_id')->with('role')->latest()->get();
        } else {
            $users = User::where('parent_id', Auth::id())->with('role')->latest()->get();
        }

        if ($request->ajax()) {

            return Datatables::of($users)
                            ->addColumn('actions', function ($user) {
                                return view('backend.sub-accounts.actions', compact('user'))->render();
                            })
                            ->editColumn('id',function ($user) use($optimus){
                                return "<span class='responsive'>User ID</span>{$optimus->encode($user->id)}";
                            })
                            ->editColumn('name', function ($user){
                                return "<span class='responsive'>Name</span>{$user->name}";
                            })
                            ->editColumn('mobile', function ($user){
                                if ($user->mobile) {
                                    return "<span class='responsive'>Mobile</span>".$user->mobile;
                                } else {
                                    return "<span class='responsive'>Mobile</span>N/A";
                                }
                            })
                            ->editColumn('credit', '{{ number_format($credit) }}')
                            ->editColumn('parent_id', function ($user){
                                return "<span class='responsive'>Parent A/c</span>{$user->parentAccount->name}";
                            })
                            ->editColumn('created_at', function ($user){
                                return "<span class='responsive'>Name</span>{$user->created_at}";
                            })
                            ->escapeColumns([])
                            ->make(true);
        }

        return view('backend.sub-accounts.index2', compact('optimus', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Jenssegers\Optimus\Optimus$optimus
     * @return \Illuminate\Http\Response
     */
    public function create(Optimus $optimus)
    {
        $users = User::whereNull('parent_id')->get();
        $timezones = \DateTimeZone::listIdentifiers();

        return view('backend.sub-accounts.create', compact('optimus', 'users', 'timezones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateSubAccountRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateSubAccountRequest $request)
    {
        $user = User::create($request->all());

        // Send the sub account an email notifying him of the account created and to change password
        //event(new UserCreated($user)) or event(new SubAccountCreated($user));

        flash()->success('The sub account has been created successfully.');

        return redirect()->route('sub-accounts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\User $sub_account
     * @param  Jenssegers\Optimus\Optimus $optimus
     * @return \Illuminate\Http\Response
     */
    public function show(User $sub_account, Optimus $optimus)
    {
        return view('backend.sub-accounts.show', compact('sub_account', 'optimus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Models\User $sub_account
     * @param  Jenssegers\Optimus\Optimus $optimus
     * @return \Illuminate\Http\Response
     */
    public function edit(User $sub_account, Optimus $optimus)
    {
        $users = User::whereNull('parent_id')->get();
        $timezones = \DateTimeZone::listIdentifiers();

        return view('backend.sub-accounts.edit', compact('sub_account', 'optimus', 'users', 'timezones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateSubaccountRequest $request
     * @param  App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSubAccountRequest $request, User $sub_account)
    {
        $sub_account->update($request->all());

        flash()->success('The sub account has been updated successfully.');

        return redirect()->route('sub-accounts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\User $sub_account
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $sub_account)
    {
        $sub_account->delete();

        flash()->success('The sub account has been deleted successfully.');

        return redirect()->route('sub-accounts.index');
    }
}
