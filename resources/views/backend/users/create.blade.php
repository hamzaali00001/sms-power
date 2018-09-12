@extends('layouts.backend')

@section('title', 'Add User')

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="active">Add User</li>
        </ol>
    </div>

    @include('flash::message')
    
    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-plus-circle"></i> Add User</h3>
    </div>
            
    <form action="{{ route('users.store') }}" class="form-horizontal form-bordered" id="create-user" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="name">Full Name:</label>
            <div class="col-xs-12 col-sm-6 col-md-4 {{ $errors->has('name') ? ' has-error' : '' }}">
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                @if ($errors->has('name'))
                    <span class="error-block"><strong>{{ $errors->first('name') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="email">User Email:</label>
            <div class="col-xs-12 col-sm-6 col-md-4  {{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <span class="error-block"><strong>{{ $errors->first('email') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="mobile">Mobile No:</label>
            <div class="col-xs-12 col-sm-6 col-md-4  {{ $errors->has('mobile') ? ' has-error' : '' }}">
                <input type="text" id="mobile" class="form-control" name="mobile" value="{{ old('mobile') }}">
                @if ($errors->has('mobile'))
                    <span class="error-block"><strong>{{ $errors->first('mobile') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="credit">SMS Credit:</label>
            <div class="col-xs-12 col-sm-6 col-md-4  {{ $errors->has('credit') ? ' has-error' : '' }}">
                <input type="text" id="credit" class="form-control" name="credit" value="{{ old('credit') }}">
                @if ($errors->has('credit'))
                    <span class="error-block"><strong>{{ $errors->first('credit') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-group {{ $errors->has('timezone') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="timezone">Timezone:</label>
            <div class="col-xs-12 col-sm-6 col-md-4 {{ $errors->has('timezone') ? ' has-error' : '' }}">
                <select class="select_div form-control select2" id="timezone" name="timezone" data-placeholder="Select Timezone">
                    <option value=""></option>
                    @foreach ($timezones as $timezone)
                    <option value="{{ $timezone }}">{{ $timezone }}</option>
                    @endforeach
                </select>
                @if ($errors->has('timezone'))
                    <span class="error-block"><strong>{{ $errors->first('timezone') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-group {{ $errors->has('role') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="role">User Role:</label>
            <div class="col-xs-12 col-sm-6 col-md-4 {{ $errors->has('role_id') ? ' has-error' : '' }}">
                <select class="select_div form-control select2" id="role_id" name="role_id" data-placeholder="Select Role">
                    <option value=""></option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->label }}</option>
                    @endforeach
                </select>
                @if ($errors->has('role_id'))
                    <span class="error-block"><strong>{{ $errors->first('role_id') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9">
                        <button class="btn btn-info" type="submit"><i class="fa fa-save"></i> Add User</button>
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
        $('.select_div').click(function(){
            $('body').css("overflow-x", "hidden");
        });
            
        $('#role_id').select2().val("{{ old('role_id') }}")
        $('#timezone').select2().val("{{ old('timezone') }}")

        $("#create-user").validate({
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
                    required:true,
                    email:true
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
