@extends('layouts.auth')

@section('title', 'Verify Your Account')

@section('content')
<div class="sms_container">
	<!-- Start Page Content -->
   	<div class="container-fluid">
	   	<div class="row">
	    	<div class="col-xs-12 col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">
                <form class="form-horizontal form-bordered row">
                    <div class="form-actions col-xs-12">
                        <h3><i class="fa fa-check-square-o"></i> Verify Your Account</h3>
                    </div>
                    <div class="clearfix10"></div>
                    <div class="col-xs-12">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        <p>{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                        <p>{{ __('If you did not receive the email') }}, <a style="color: #00AFF1" href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.</p>
                    </div>
                    <div class="clearfix10"></div>
                </form>
	        </div>
	    </div>
   	</div>
    <!-- End Page Content -->  
</div>
@stop
