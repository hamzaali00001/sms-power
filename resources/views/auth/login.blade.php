@extends('layouts.auth')

@section('title', 'Account Login')

@section('content')
<div class="sms_container">
	<!-- Start Page Content -->
   	<div class="container-fluid">
	   	<div class="row">
	    	<div class="col-xs-12 col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">
	        	@include('flash::message')
                <form class="form-horizontal form-bordered row" id="login" action="{{ route('login') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-actions col-xs-12">
                        <h3><i class="fa fa-sign-in"></i> Account Login</h3>
                    </div>
                    @if ($errors->has('error_message'))
                        <span class="help-block"><strong>{{ $errors->first('error_message') }}</strong></span>
                    @endif
                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label" for="Email">Enter your Email:</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label" for="Password">Enter your Password:</label>
                            <input type="password" class="form-control" name="password" autocomplete="off">
                            @if ($errors->has('password'))
                                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="clearfix20"></div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
                                <button type="submit" class="btn btn-info"><i class="fa fa-sign-in"></i> Account Login</button>
                            </div>
                            <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 text-right">
                                &nbsp;
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
    <script type="text/javascript" >
        $("#login").validate({
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
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
