@extends('layouts.auth')

@section('title', 'Forgot Password?')

@section('content')
<div class="sms_container">
	<!-- Start Page Content -->
   	<div class="container-fluid">
	   	<div class="row">
	    	<div class="col-xs-12 col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form class="form-horizontal form-bordered row" id="login" action="{{ route('password.email') }}" method="POST">
                    {{csrf_field()}}
                    <div class="form-actions col-xs-12">
                        <h3><i class="fa fa-question-circle"></i> Forgot Password?</h3>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="clearfix10"></div>
                            <p>Enter the email associated with your account and we will send you an email with instructions on how to reset your password.</p>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="clearfix20"></div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
                                <button type="submit" class="btn btn-info"><i class="fa fa-hand-o-right"></i> Send Password Reset Email</button>
                            </div>
                            <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 text-right">
                                <a href="{{ route('login') }}" class="btn btn-grey"><i class="fa fa-sign-in"></i> Account Login</a>
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
@endsection