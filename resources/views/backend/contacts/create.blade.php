@extends('layouts.backend')

@section('title', 'Add Contacts')

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li><a href="{{ route('groups.index') }}">Groups</a></li>
            <li><a href="{{ route('groups.contacts.index', $group) }}">{{ $group->name }}</a></li>
            <li class="active">Add Contacts</li>
        </ol>
    </div>

    @include('flash::message')

    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-at"></i> Add Contacts</h3>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <form class="form-horizontal form-bordered">
        <div class="form-group group-title">
            <div class="col-md-12">
                <ul class="sms_list">
                    <li><i class="fa fa-circle-o"></i>Upload <strong>CSV, EXCEL</strong> or <strong>ODD</strong> files ONLY</li>
                    <li><i class="fa fa-circle-o"></i>The <strong>MOBILE</strong> field is mandatory i.e. must be filled</li>
                    <li><i class="fa fa-circle-o"></i><strong>MOBILE NUMBERS</strong> format should be like <strong>254722334455</strong></li>
                    <li><i class="fa fa-circle-o"></i>Leave the <strong>FIRST ROW</strong> field names (<strong>NAME</strong> &amp; <strong>MOBILE</strong>) intact</li>
                    <li><i class="fa fa-circle-o"></i><a href="{{ route('groups.contacts.sample') }}"><strong>CLICK HERE TO DOWNLOAD A SAMPLE CONTACTS FILE</strong></a></li>
                </ul>
            </div>
        </div>
    </form>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#upload-contacts" aria-controls="upload-contacts" role="tab" data-toggle="tab"><i class="fa fa-table blue"></i> Upload File</a></li>
        <li role="presentation"><a href="#single-contact" aria-controls="single-contact" role="tab" data-toggle="tab"><i class="fa fa-file-o blue"></i> Single Contact</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="upload-contacts">
            <form action="{{ route('upload-file', $group) }}" class="form-horizontal form-bordered" id="upload-contacts" method="post" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <div class="form-group">
                    <div class="col-xs-12 col-sm-9 col-md-6">
                        <input type="file" class="filestyle" id="filename" name="filename" data-buttonText="Browse">
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-sm-12 col-md-12">
                                <button class="btn btn-info" type="submit"><i class="fa fa-upload"></i> Upload Contacts File</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div role="tabpanel" class="tab-pane" id="single-contact">
            <form action="{{ route('groups.contacts.store', $group) }}" class="form-horizontal form-bordered"
                  method="POST" id="create-contact">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <input type="hidden" name="group_id" value="{{ $group->id }}">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="name">Name:</label>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="mobile">Mobile:</label>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <input type="tel" id="mobile" name="mobile" class="form-control mobile" value="{{ old('mobile') }}">
                        <span id="valid-msg" class="hide">✓ Valid</span>
                        <span id="error-msg" class="hide">Invalid number</span>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit"><i class="fa fa-save"></i> Add Single Contact</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @if (count($errors))
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your file upload. Please try again.
            </div>
        @endif
    </div>
    <!-- End Page Content -->
</div>
@stop

@push('scripts')
    <script>
        $(document).ready(function () {
            var mobile = $("#mobile"),
                errorMsg = $("#error-msg"),
                validMsg = $("#valid-msg");

            var reset = function() {
                mobile.removeClass("error");
                errorMsg.addClass("hide");
                validMsg.addClass("hide");
            };

            mobile.intlTelInput({
                autoPlaceholder: true,
                nationalMode: true,
                initialCountry: "ke",
                utilsScript: "/js/backend/utils.js",
                placeholderNumberType: 'MOBILE',
                hiddenInput: "full_phone",
            });

            var validateData = function () {
                reset();
                if ($.trim(mobile.val()) && mobile.intlTelInput("isValidNumber")) {
                    validMsg.removeClass("hide");
                    return false
                }
                mobile.addClass("error");
                errorMsg.removeClass("hide");
                return true;
            };

            mobile.blur(validateData);

            // on keyup / change flag: reset
            mobile.on("keyup change", reset);

            $("#create-contact").submit(function(e) {
                if (validateData()) {
                    e.preventDefault();
                }
            });
        })
    </script>
@endpush
