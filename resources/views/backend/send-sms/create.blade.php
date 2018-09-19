@extends('layouts.backend')

@section('title', 'Send Message')

@section('content')
<div class="sms_container">
    <div class="sms_heading">
        <h3><i class="fa fa-send"></i> Send SMS</h3>
    </div>

     @include('flash::message')

    <!-- Start Page Content -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#bulk-sms-modal" aria-controls="bulk-sms" role="tab" data-toggle="tab"><i class="fa fa-table blue"></i> Bulk SMS </a></li>
        <li role="presentation"><a href="#single-sms-modal" aria-controls="single-sms" role="tab" data-toggle="tab"><i class="fa fa-file-o blue"></i> Single SMS</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="bulk-sms-modal">
            <form action="{{ route('bulk-sms') }}" class="form-horizontal form-bordered" id="bulk-sms" method="POST">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <input type="hidden" name="type" value="bulk">
                <input type="hidden" name="status" value="{{ config('smspower.msg_status.info') }}">
                @if (count($groups))
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="to">Recipients:</label>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <select class="select2 form-control" id="to" name="to" data-placeholder="Select Group">
                            <option></option>
                            @foreach ($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }} - ({{ $group->contacts->count() }} contacts)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if (count($senderids))
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="senderID">Sender ID:</label>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <select class="form-control select2" id="from" name="from" data-placeholder="Select Sender ID">
                            <option value="{{ env('SENDER_ID') }}">None</option>
                            @foreach ($senderids as $senderid)
                            <option value="{{ $senderid->name }}">{{ $senderid->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @else
                    <input type="hidden" name="from" value="{{ env('SENDER_ID') }}">
                @endif
                @if (count($templates))
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="template">Template:</label>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <select class="form-control select2" id="template-bulk" name="template" data-placeholder="Select Template">
                            <option value="0">None</option>
                            @foreach ($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="message">Message:</label>
                    <div class=" col-xs-12 col-sm-9 col-md-9">
                        <textarea class="form-control" rows="5" id="message-bulk" name="message" placeholder="Type your message here">{{ old('message') }}</textarea>
                        <div class="row text-small">
                            <div class="col-xs-12">
                                <span id="remaining"><strong class='blue'>160/160</strong> characters remaining</span>
                                <span id="messages" class="pull-right"><strong class='blue'>0</strong> message(s)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="schedule">Send Time:</label>
                    <div class="col-xs-12 col-sm-9 col-md-9">
                        <label class="radio-inline col-xs-block">
                            <input type="radio" id="sms_schedule_check" name="schedule" value="No" checked> Now (Instant)
                        </label>
                        <label class="radio-inline col-xs-block">
                            <input type="radio" id="sms_schedule_later_bulk" name="schedule" value="Yes"> Later (Schedule)
                        </label>
                        <div class="sms_later_bulk">
                            <div class="input-append date form_datetime">
                                <input size="16" class="form-control" type="text" name="send_time" value="{{ old('send_time') }}" readonly>
                                <span class="add-on"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit"><i class="fa fa-forward"></i> Send Bulk SMS</button>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="form-group">
                    <input type="text" class="form-control" id="name" name="name" value="TIP: First add Groups &amp; Contacts to send Bulk SMS." disabled>
                </div>
                @endif
            </form>
        </div>
        <div role="tabpanel" class="tab-pane" id="single-sms-modal">
            <form action="{{ route('single-sms') }}" class="form-horizontal form-bordered" id="single-sms" method="POST">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                <input type="hidden" name="type" value="single">
                <input type="hidden" name="status" value="{{ config('smspower.msg_status.info') }}">
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="to">Recipient:</label>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <input id="to" class="form-control" name="to" type="text" value="{{ old('to') }}">
                    </div>
                </div>
                @if (count($senderids))
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="from">Sender ID:</label>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <select class="form-control select2" id="from" name="from" data-placeholder="Select Sender ID">
                            <option value="{{ env('SENDER_ID') }}">None</option>
                            @foreach ($senderids as $senderid)
                            <option value="{{ $senderid->name }}">{{ $senderid->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @else
                    <input type="hidden" name="from" value="{{ env('SENDER_ID') }}">
                @endif
                @if (count($templates))
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="template">Template:</label>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <select class="form-control select2" id="template-single" name="template" data-placeholder="Select Template">
                            <option value="0">None</option>
                            @foreach ($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                <div class="form-group">

                    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="message">Message:</label>
                    <div class=" col-xs-12 col-sm-9 col-md-9">
                        <textarea class="form-control" rows="5" id="message-single" name="message" placeholder="Type your message here">{{ old('message') }}</textarea>
                        <div class="row text-small">
                            <div class="col-xs-12">
                                <span id="remaining-single"><strong class='blue'>160/160</strong> characters remaining</span>
                                <span id="messages-single" class="pull-right"><strong class='blue'>1</strong> message(s)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-xs-12 col-sm-3 col-md-3" for="schedule">Send Time:</label>
                    <div class="col-xs-12 col-sm-9 col-md-9">
                        <label class="radio-inline col-xs-block">
                            <input type="radio" id="sms_schedule_check" name="schedule" value="No" checked> Now (Instant)
                        </label>
                        <label class="radio-inline col-xs-block">
                            <input type="radio" id="sms_schedule_later_single" name="schedule" value="Yes"> Later (Schedule)
                        </label>
                        <div class="sms_later_single">
                            <div class="input-append date form_datetime">
                                <input size="16" class="form-control" type="text" name="send_time" value="{{ old('send_time') }}" readonly>
                                <span class="add-on"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit"><i class="fa fa-play"></i> Send Single SMS</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Page Content -->
</div>
@stop

@push('scripts')
<script>
    $("#single-sms").validate({
        errorElement: 'strong',
        errorClass: 'help-block',
        highlight: function(element, errorClass, validClass) {
            $(element).addClass(errorClass);
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).removeClass(errorClass);
            $(element).closest('.form-group').removeClass('has-error');
        },
        rules: {
            recipient: {
                required: true
            },
            message: {
                required: true
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
    $("#bulk-sms").validate({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        rules: {
            recipients: {
                required: true
            },
            message: {
                required: true,
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
<script type="text/javascript">
    $(".form_datetime").datetimepicker({
        format: "dd MM yyyy hh:ii",
        autoclose: true,
        minuteStep: 5,
        pickerPosition: "top-left",
        startDate: new Date()
    });
</script>
<script type="text/javascript">
    function message_bulk(that) {
        let counter = SmsCounter.count(that.val());
        $('#remaining').find('.blue').text(counter.remaining + "/" + counter.per_message);
        $('#messages').find('.blue').text(counter.messages);
    }
    function message_single(that) {
        let counter = SmsCounter.count(that.val());
        $('#remaining-single').find('.blue').text(counter.remaining + "/" + counter.per_message);
        $('#messages-single').find('.blue').text(counter.messages);
    }
    let templates = {!! $templates->toJson() !!}
    $('#template-bulk, #template-single').change(function() {
        let that = $(this);
        let textarea = that.closest('form').find('textarea');
        let index = that.prop('selectedIndex');
        if (!index) {
            textarea.val('');
            textarea.attr('id') === 'message-bulk' ? message_bulk($('#message-bulk')) : message_single($('#message-single'));
            return;
        }
        let message = templates[index-1]['message'];
        textarea.val(message);
        textarea.attr('id') === 'message-bulk' ? message_bulk($('#message-bulk')) : message_single($('#message-single'));
    });
    $('#message-bulk').keyup(function() {
        $('#template-bulk').val(0).trigger('change.select2');
        message_bulk($(this));
    });
    $('#message-single').keyup(function() {
        $('#template-single').val(0).trigger('change.select2');
        message_single($(this));
    });
</script>
@endpush
