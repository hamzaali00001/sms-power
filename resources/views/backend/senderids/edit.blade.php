@extends('layouts.backend')

@section('title', 'Edit Sender ID')

@section('content')
<div class="sms_container">
    
    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-asterisk"></i> Edit Sender ID</h3>
        <div class="clearfix05"></div>
    </div>

    <form class="form-horizontal form-bordered">
        <div class="form-group group-title">
            <div class="col-md-12">
                <ul class="sms_list">
                    <li><i class="fa fa-circle-o"></i>Update the Sender ID Status by setting it to either <strong>Active</strong> if it has been registered by the telcos or to <strong>Rejected</strong> if it has been declined.</li>
                </ul>
            </div>
        </div>
    </form>
            
    <form action="{{ route('senderids.update', $senderid) }}" class="form-horizontal form-bordered" id="sender_id" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="name">Sender ID Name:</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $senderid->name) }}">
                @if ($errors->has('name'))
                    <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="status">Sender ID Status:</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <select class="form-control select2" id="status" name="status" data-placeholder="Select Status">
                    <option value=""></option>
                    <option value="Active">Active</option>
                    <option value="Rejected">Rejected</option>
                </select>
                @if ($errors->has('status'))
                    <span class="help-block"><strong>{{ $errors->first('status') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9">
                        <button class="btn btn-info" type="submit"><i class="fa fa-save"></i> Update Sender ID</button>
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
    $("#sender_id").validate({
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
            status: {
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
