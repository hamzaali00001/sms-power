@extends('layouts.backend')

@section('title', 'Change Password')

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="active">Change Password</li>
        </ol>
    </div>

    @include('flash::message')
    
    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-lock"></i> Change Password</h3>
    </div>
            
    <form action="{{ route('change-password') }}" class="form-horizontal form-bordered" id="password" method="POST">
        {{ csrf_field() }}
        <div class="form-group group-title">
            <div class="col-md-12">
                <ul class="sms_list">
                    <li><i class="fa fa-circle-o"></i>The <strong>current password</strong> is the password that you logged in with.</li>
                    <li><i class="fa fa-circle-o"></i>The <strong>new password</strong> and <strong>confirm new password</strong> must be the same characters.</li>
                    <li><i class="fa fa-circle-o"></i>The <strong>new password</strong> and <strong>confirm new password</strong> must be different from the <strong>current password</strong>.</li>
                </ul>
            </div>
        </div>
        <div class="form-group {{ $errors->has('current_password') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="current_password">Current Password:</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="password" class="form-control" id="current_password" name="current_password" value="{{ old('current_password') }}" autocomplete="off">
                @if ($errors->has('current_password'))
                    <span class="error-block">
                        <strong>{{ $errors->first('current_password') }}</strong>
                    </span>
                @endif
            </div>
        </div> 
        <div class="form-group  {{ $errors->has('new_password') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="new_password">New Password:</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="password" class="form-control" id="new_password" name="new_password" value="{{ old('new_password') }}" autocomplete="off">
                @if ($errors->has('new_password'))
                    <span class="error-block">
                        <strong>{{ $errors->first('new_password') }}</strong>
                    </span>
                @endif
            </div>
        </div> 
        <div class="form-group {{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="new_password_confirmation">Confirm New Password:</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="password" class="form-control" id="new_password_confirmation" autocomplete="off" name="new_password_confirmation" value="{{ old('new_password_confirmation') }}">
                @if ($errors->has('new_password_confirmation'))
                    <span class="error-block">
                        <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                    </span>
                @endif
            </div>
        </div> 
        <div class="form-actions">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9">
                        <button class="btn btn-info" type="submit"><i class="fa fa-lock"></i> Change Password</button>
                    </div>
                </div>
            </div>
        </div>                    
    </form>
    <!-- End Page Content -->  
</div>
@stop

@push('scripts')
    <script>
        $("#password").validate({
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'error-block',
            rules: {
                current_password: {
                    required: true,
                    minlength: 6
                },
                new_password: {
                    required: true,
                    minlength: 6
                },
                new_password_confirmation: {
                    required: true,
                    minlength: 6,
                    equalTo: "#new_password"
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    </script>
@endpush
