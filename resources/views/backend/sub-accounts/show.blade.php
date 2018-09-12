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
        <h3><i class="fa fa-cogs"></i> Profile</h3>
    </div>
            
    <div class="form-horizontal form-bordered">                                               
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="parent_acc">Parent:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $sub_account->parentAccount->name }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="user_id">User ID:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $optimus->encode($sub_account->id) }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="role">Role:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $sub_account->role->label }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="name">Name:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $sub_account->name }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="email">Email:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $sub_account->email }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="mobile">Mobile:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $sub_account->mobile or 'N/A' }}</div>
            </div>
        </div>
        @if (!Auth::user()->hasRole('postpaid'))
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="credit">Credit:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ number_format($sub_account->credit) }}</div>
            </div>
        </div>
        @endif
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="is_suspended">Suspended:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $sub_account->suspended ? 'Yes' : 'No' }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="timezone">Timezone:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $sub_account->timezone or 'N/A' }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="last-login-ip">Last Login IP:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $sub_account->last_login_ip or 'N/A' }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="last-login">Last Login Date:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $sub_account->last_login or 'N/A' }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="registration-date">Registration Date:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $sub_account->created_at }}</div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="verification_date">Verification Date:</label>
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="form-control-static">{{ $sub_account->email_verified_at }}</div>
            </div>
        </div>
        <div class="form-actions fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9">
                        <a href="{{ route('sub-accounts.edit', $sub_account) }}" class="btn btn-info"><i class="fa fa-edit"></i> Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>                    
    </div>
    <!-- End Page Content -->  
</div>
@stop
