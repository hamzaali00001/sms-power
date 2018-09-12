@extends('layouts.backend')

@section('title', 'Profile')

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="active">Profile</li>
        </ol>
    </div>

    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-user"></i> Profile</h3>
    </div>
            
    <div class="form-horizontal form-bordered">                                               
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="user_id">ID:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $optimus->encode($user->id) }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="role">Role:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $user->role->label }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="name">Name:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $user->name }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="email">Email:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $user->email }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="mobile">Mobile:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $user->mobile or 'N/A' }}</div>
            </div>
        </div>
        @if (!Auth::user()->hasRole('postpaid'))
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="credit">Credit:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ number_format($user->credit) }}</div>
            </div>
        </div>
        @endif
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="sms_cost">SMS Cost:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $user->sms_cost or env('SMS_COST') }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="is_suspended">Suspended:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $user->suspended ? 'Yes' : 'No' }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="timezone">Timezone:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $user->timezone or 'N/A' }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="last-login-ip">Last Login IP:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $user->last_login_ip or 'N/A' }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="last-login">Last Login Date:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $user->last_login }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="registration-date">Registration Date:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $user->created_at }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="verification_date">Verification Date:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $user->email_verified_at }}</div>
            </div>
        </div>
        <div class="form-actions fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-info"><i class="fa fa-edit"></i> Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>                    
    </div>
    <!-- End Page Content -->  
</div>
@stop
