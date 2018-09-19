@extends('layouts.backend')

@section('title', 'Credit Purchases')

@section('css')
<link href="{{ asset('css/backend/dataTables.bootstrap.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="Status">Credit Purchases</li>
        </ol>
    </div>

    @include('flash::message')

    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-bar-chart"></i> Purchases</h3>
        <a href="{{ route('purchases.create') }}"><i class="fa fa-shopping-cart"></i> Buy Credit</a>
    </div>
    
    @if (count($purchases))        
        <table id="data_tables" class="table table-striped table-bordered" data-form="delete">
            <thead>
                <tr>
                    <th>Purchase Date</th>
                    <th>Reference</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Trans. Code</th>
                    <th>Client Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchases as $purchase)
                    <tr>
                        <td><span class="responsive">Purchase Date</span> {{ $purchase->created_at }}</td>
                        <td><span class="responsive">Reference</span> {{ $optimus->encode($purchase->id) }}</td>
                        <td><span class="responsive">Quantity</span> {{ number_format($purchase->quantity) }}</td>
                        <td><span class="responsive">Amount</span> {{ number_format($purchase->amount) }}</td>
                        <td><span class="responsive">Trans. Code</span> {{ $purchase->trans_code }}</td>
                        <td><span class="responsive">Client Name</span> {{ $purchase->user->name }}</td>
                        <?php
                            if ($purchase->status == 'Successful') $label = "label-success";
                            if ($purchase->status == 'Failed') $label = "label-danger";
                            if ($purchase->status == 'Processing') $label = "label-info";
                        ?>
                        <td><span class="responsive">Status</span> <span class="label {{ $label }}">{{ $purchase->status }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="clearfix10"></div>
        <p>No Purchases Available</p>
    @endif
    <!-- End Page Content -->  
</div>
@stop

@push('scripts')
    @include('backend.partials.delete-modal')
    <script src="{{ asset('js/backend/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('js/backend/dataTables.bootstrap.js') }}"></script>
@endpush
