@extends('layouts.backend')

@section('title', 'Transactions')

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="Status">Transactions</li>
        </ol>
    </div>

    @include('flash::message')

    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-bar-chart"></i> Transactions</h3>
        <a href="{{ route('buy-credit') }}"><i class="fa fa-shopping-cart"></i> Buy Credit</a>
    </div>
    
    @if (count($transactions))        
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
                @foreach ($transactions as $transaction)
                    <tr>
                        <td><span class="responsive">Purchase Date</span> {{ $transaction->created_at }}</td>
                        <td><span class="responsive">Reference</span> {{ $optimus->encode($transaction->id) }}</td>
                        <td><span class="responsive">Quantity</span> {{ number_format($transaction->quantity) }}</td>
                        <td><span class="responsive">Amount</span> {{ number_format($transaction->amount) }}</td>
                        <td><span class="responsive">Trans. Code</span> {{ $transaction->trans_code }}</td>
                        <td><span class="responsive">Client Name</span> {{ $transaction->user->name }}</td>
                        <?php
                            if ($transaction->status == 'Successful') $label = "label-success";
                            if ($transaction->status == 'Failed') $label = "label-danger";
                            if ($transaction->status == 'Processing') $label = "label-info";
                        ?>
                        <td><span class="responsive">Status</span> <span class="label {{ $label }}">{{ $transaction->status }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="clearfix10"></div>
        <p>No Transactions Available</p>
    @endif
    <!-- End Page Content -->  
</div>
@stop

@push('scripts')
    @include('backend.partials.delete-modal')
@endpush
