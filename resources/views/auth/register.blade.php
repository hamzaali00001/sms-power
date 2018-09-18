@extends('layouts.auth')

@section('title', 'Register Account')

@section('content')
<div class="sms_container">
	<!-- Start Page Content -->
   	<div class="container-fluid">
	   	<div class="row">
	    	<div class="col-xs-12 col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">
                @include('flash::message')
	        	<form class="form-horizontal form-bordered row" id="register" action="{{ route('register') }}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="timezone" id="timezone">
                    <div class="form-actions col-xs-12">
                        <h3><i class="fa fa-edit"></i> Register Account</h3>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label" for="Name">Enter Full Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label" for="Email">Email Address:</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label" for="password">Enter Password:</label>
                            <input type="password" name="password" class="form-control" autocomplete="off">
                            @if ($errors->has('password'))
                                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                            <label class="control-label" for="mobile">Enter Mobile No:</label>
                            <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">
                            @if ($errors->has('mobile'))
                                <span class="help-block"><strong>{{ $errors->first('mobile') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="clearfix20"></div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
                                <button type="submit" class="btn btn-info"><i class="fa fa-edit"></i> Register Account</button>
                            </div>
                            <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 text-right">
                                <a href="{{ url('password/reset') }}" class="btn btn-grey"><i class="fa fa-question-circle"></i> Forgot Password?</a>
                            </div>
                        </div>
                    </div>
                </form>
	        </div>
	    </div>
   	</div>
    <!-- End Page Content -->  
</div>
@stop

@section('scripts')
    <script type="text/javascript">
        //guess user timezone
        $('#timezone').val(moment.tz.guess());

        $("#register").validate({
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                }, 
                password_confirmation: {
                    required: true,
                    minlength: 6
                },
                mobile: {
                    required: true,
                    minlength: 10
                }
            },
            errorPlacement: function(error, element) {
                if(element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    </script>
@endsection