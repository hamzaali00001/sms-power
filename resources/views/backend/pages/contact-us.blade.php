@extends('layouts.backend')

@section('title', 'Contact us')

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="active">Contact us</li>
        </ol>
    </div>

    @include('flash::message')
    
    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-envelope"></i> Contact us</h3>
        <div class="clearfix15"></div>
    </div>
            
    <form action="{{ route('contact-us') }}" class="form-horizontal form-bordered" id="contact_us" method="POST">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        <div class="form-group {{ $errors->has('subject') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="subject">Subject:</label>
            <div class="col-xs-12 col-sm-9 col-md-9">
                <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}">
                @if ($errors->has('subject'))
                    <span class="help-block"><strong>{{ $errors->first('subject') }}</strong></span>
                @endif
            </div>
        </div>                        
        <div class="form-group {{ $errors->has('message') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="message">Message:</label>
            <div class=" col-xs-12 col-sm-9 col-md-9">
                <textarea rows="10" class="form-control" name="message">{{ old('message') }}</textarea>
                @if ($errors->has('message'))
                    <span class="help-block"><strong>{{ $errors->first('message') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9">
                        <button class="btn btn-info" type="submit"><i class="fa fa-envelope-o"></i> Contact us</button>
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
    $("#contact_us").validate({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        rules: {
            subject: {
                required: true
            },
            message: {
                required: true
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
