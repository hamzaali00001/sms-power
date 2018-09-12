@extends('layouts.backend')

@section('title', 'Edit Profile')

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="active">Edit Profile</li>
        </ol>
    </div>
    
    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-edit"></i> Edit Profile</h3>
    </div>
            
    <form action="{{ route('users.update', $user) }}" class="form-horizontal form-bordered" id="edit-user" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="name">Full Name:</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
                @if ($errors->has('name'))
                    <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="email">User Email:</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}">
                @if ($errors->has('email'))
                    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                @endif
            </div>
        </div> 
        <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="mobile">User Mobile:</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile Number" value="{{ old('mobile', $user->mobile) }}">
                @if ($errors->has('mobile'))
                    <span class="help-block"><strong>{{ $errors->first('mobile') }}</strong></span>
                @endif
            </div>
        </div>
        @if (Auth::user()->hasRole('admin'))
        <div class="form-group {{ $errors->has('credit') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="credit">User Credit:</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" class="form-control" id="credit" name="credit" value="{{ old('credit', $user->credit) }}">
                @if ($errors->has('credit'))
                    <span class="help-block"><strong>{{ $errors->first('credit') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-group {{ $errors->has('sms_cost') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="sms_cost">SMS Cost:</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" class="form-control" id="sms_cost" name="sms_cost" value="{{ old('sms_cost', $user->sms_cost) }}">
                @if ($errors->has('sms_cost'))
                    <span class="help-block"><strong>{{ $errors->first('sms_cost') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-group {{ $errors->has('suspended') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="suspend_user">Suspend User:</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <select class="form-control select2" name="suspended">
                    <option {{ old('suspended', $user->suspended)=="0" ? 'selected':'' }} value="0">No</option>
                    <option {{ old('suspended', $user->suspended)=="1" ? 'selected':'' }} value="1">Yes</option>
                </select>
                @if ($errors->has('suspended'))
                    <span class="help-block"><strong>{{ $errors->first('suspended') }}</strong></span>
                @endif
            </div>
        </div>
        @endif
        <div class="form-group {{ $errors->has('timezone') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="timezone">Timezone:</label>
            <div class="col-xs-12 col-sm-6 col-md-4 {{ $errors->has('timezone') ? ' has-error' : '' }}">
                <select class="form-control select2" id="timezone" name="timezone" data-placeholder="Select Timezone">
                    <option value=""></option>
                    @foreach ($timezones as $timezone)
                    <option value="{{ $user->timezone }}" {{ ($user->timezone == $timezone) ? 'selected' : ''}}>{{ $timezone }}</option>
                    @endforeach
                </select>
                @if ($errors->has('timezone'))
                    <span class="help-block"><strong>{{ $errors->first('timezone') }}</strong></span>
                @endif
            </div>
        </div>
        @if (Auth::user()->hasRole('admin'))
        <div class="form-group {{ $errors->has('role_id') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="role">User Role:</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <select class="form-control select2" name="role_id" id="role_id" data-placeholder="Select Role">
                    <option value=""></option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ ($user->role->id == $role->id) ? 'selected' : ''}}>{{ $role->label }}</option>
                    @endforeach
                </select>

                @if ($errors->has('role_id'))
                    <span class="help-block"><strong>{{ $errors->first('role_id') }}</strong></span>
                @endif
            </div>
        </div>
        @else
            <input type="hidden" name="role_id" value="{{ $user->role->id }}">
        @endif
        <div class="form-actions">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9">
                        <button class="btn btn-info" type="submit"><i class="fa fa-save"></i> Save Profile</button>
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
        $(document).ready(function() {
            $('#timezone').select2().val("{{ old('timezone', $user->timezone) }}")
            $('#role_id').select2().val("{{ old('role_id', $user->role_id) }}")
            $('#suspended').select2().val("{{ old('suspended', $user->suspended) }}")
        });

        $("#edit-user").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass);
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass);
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                mobile: {
                    required: true,
                    minlength: 10
                },
                credit: {
                    required: true
                },
                timezone: {
                    required: true
                },
                role_id: {
                    required: true
                }
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {     
                    error.insertAfter(element.next('span'))
                } else {
                    error.insertAfter(element);
                }
            }
        });

        $('.select2').on('change', function() {
            $(this).valid();
        });
    </script>
@endpush
