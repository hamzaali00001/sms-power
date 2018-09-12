@extends('layouts.backend')

@section('title', 'Buy Credit')

@section('content')
<div class="sms_container">
    
    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-shopping-cart"></i> Buy Credit</h3>
        <div class="clearfix05"></div>
    </div>

    <form class="form-horizontal form-bordered">
        <div class="form-group group-title">
            <div class="col-md-12">
                <ul class="sms_list">
                    <li><i class="fa fa-circle-o"></i> Buy SMS credit instantly using <strong>MPESA.</strong> You can buy <strong>several times</strong> upto a maximum <strong>Total Cost</strong> of <strong>KES 70,000</strong> each time.</li>
                </ul>
            </div>
        </div>
    </form>

    <form action="{{ route('purchases.store') }}" class="form-horizontal form-bordered" id="buy_credit" method="POST">  
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="sms_required">SMS Required</label>
            <div class="col-xs-12 col-sm-6 col-md-4 {{ $errors->has('quantity') ? ' has-error' : '' }}">
                <input type="text" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}">
                @if ($errors->has('quantity'))
                    <span class="help-block"><strong>{{ $errors->first('quantity') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="total_cost">Total Cost</label>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="input-group">
                    <span class="input-group-addon" id="basic-addon3">Amount</span>
                    <input class="form-control" type="text" id="amount" name="amount" placeholder="0.00" value="{{ old('amount') }}" readonly>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="Mpesa">How to Pay</label>
            <div class="col-xs-12 col-sm-9 col-md-9">
                <ol>
                    <li>Go to <strong>M-PESA</strong>. Select <strong>Lipa na M-PESA</strong></li>
                    <li>Select <strong>Pay Bill</strong> option</li>
                    <li>Enter Business Number - <strong>961700</strong></li>
                    <li>Enter Account Number - <strong>{{ $optimus->encode(auth()->user()->id) }}</strong></li>
                    <li>Enter Amount (Total Cost) - <strong><span class="display-amount">0</span></strong></li>
                    <li>Enter <strong>M-PESA PIN</strong></li>
                    <li>Click <strong>OK</strong>. Wait for MPESA confirmation sms</li>
                    <li>Enter the <strong>M-PESA Transaction code</strong> below</strong></li>
                </ol>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-12 col-sm-3 col-md-3" for="trans_code">Transaction Code</label>
            <div class="col-xs-12 col-sm-6 col-md-4 {{ $errors->has('trans_code') ? ' has-error' : '' }}">
                <input type="text" class="form-control" id="trans_code" name="trans_code" value="{{ old('trans_code') }}">
                @if ($errors->has('trans_code'))
                    <span class="help-block"><strong>{{ $errors->first('trans_code') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-offset-3 col-sm-9 col-md-offset-3 col-md-9">
                        <button class="btn btn-info" type="submit"><i class="fa fa-shopping-cart"></i> Complete Transaction</button>
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
    $("#buy_credit").validate({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        rules: {
            quantity: {
                required: true
            },
            trans_code: {
                required: true,
                minlength: 10
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
