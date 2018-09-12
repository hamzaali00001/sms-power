@extends('layouts.auth')

@section('title', 'Reset Password')

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
                <form class="form-horizontal form-bordered row" id="auth" action="{{ route('password.request') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-actions col-xs-12">
                        <h3><i class="fa fa-lock"></i> Reset Password</h3>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label" for="email">Enter your Email Address</label>
                            <input id="email" type="email" name="email" class="form-control" value="{{ $email or old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label" for="password">Enter Password</label>
                            <input id="password" type="password" name="password" class="form-control">
                            @if ($errors->has('password'))
                                <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="control-label" for="password-confirm">Confirm Password</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <div class="clearfix20"></div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
                                <button type="submit" class="btn btn-info"><i class="fa fa-lock"></i> Reset Password</button>
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
        $("#auth").validate({
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
                },
                password_confirmation: {
                    required: true,
                    minlength: 6
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