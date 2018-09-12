<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Show the form for changing password.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('auth.change-password');
    }

    /**
     * Update the password for the user.
     *
     * @param  App\Http\Requests\Backend\Users\ChangePasswordRequest $request
     * @param  App\Models\User $user
     * @return Response
     */
    public function update(ChangePasswordRequest $request)
    {
        // Check whether the current password and the filled in current password match
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            flash()->error('Your current password does not match with the password you provided.');
            return back();
        }

        // Check whether the current password and the new password are the same
        if (strcmp($request->current_password, $request->new_password) == 0){
            flash()->error('The new password cannot be the same as your current password.');
            return back();
        }

        // Change Password
        $user = auth()->user();
        $user->update(['password' => bcrypt($request->new_password)]);
        flash()->success('Your password has been changed successfully.');
        return back();
    }
}
