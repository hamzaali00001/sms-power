@extends('layouts.backend')

@section('title', 'Sent SMS')

@section('css')
<link href="{{ asset('css/backend/dataTables.bootstrap.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="active">Sent SMS</li>
        </ol>
    </div>

    @include('flash::message')

    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-check-square-o"></i> Sent SMS</h3>
        <a href="{{ route('send-sms') }}"><i class="fa fa-send"></i> Send SMS</a>
        <a href="#delete" class="btn btn-danger" data-toggle="modal" id="sms_all"><i class="fa fa-trash-o"></i> Delete All</a>
    </div>

    @if (count($messages))   
        <table id="sent-sms" class="table table-striped table-bordered" data-form="delete">
            <thead>
                <tr>
                    <th data-orderable="false" class="text-center checkAll"><input id="checkAll" type="checkbox" value="all" name="rowCheck" data-index="1"><span class="visible-xs">Select All</span></th>
                    <th>From (Sender)</th>
                    <th>To (Recipient)</th>
                    <th>Status</th>
                    <th>Cost</th>
                    <th>Date/Time Sent</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($messages as $msg)
                    <tr>
                        <td class="text-center"><input class="rowCheck" type="checkbox" value="check1" name="rowCheck" data-index="1"></td>
                        <td><span class="responsive">From</span> {{ $msg->from }}</td>
                        <td><span class="responsive">To</span> {{ $msg->to }}</td>
                        <td><span class="responsive">Status</span> <span class="{{ $msg->status_label }}">{{ $msg->status }}</span></td>
                        <td><span class="responsive">Cost</span> {{ $msg->cost }}</td>
                        <td><span class="responsive">Date/Time Sent</span> {{ $msg->created_at }}</td>
                        <td><span class="responsive">Actions</span> 
                            <span data-toggle="modal" data-target="#show" data-from="{{ $msg->from }}" data-to="{{ $msg->to }}" data-message="{{ $msg->message }}" data-characters="{{ strlen( utf8_decode($msg->message)) }}" data-cost="{{ $msg->cost }}" data-time_sent="{{ $msg->created_at }}" data-status="{{ $msg->status }}"">
                                <button type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="View SMS"><i class="fa fa-search"></i></button>
                            </span>
                            <form action="{{ route('sent-sms.destroy', $msg) }}" class="form-delete" method="POST" style="display:inline">
                                <input type="hidden" name="_method" value="DELETE">
                                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-danger btn-xs" data-placement="top" data-toggle="tooltip" title="Delete SMS"><i class="fa fa-trash-o"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="clearfix10"></div>
        <p>No Sent Messages Available</p>
    @endif
    <!-- End Page Content -->  
</div>
@stop

@push('scripts')
    @include('backend.sent-sms.show')
    @include('backend.partials.delete-modal')
    <script src="{{ asset('js/backend/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('js/backend/dataTables.bootstrap.js') }}"></script>
    <script>
        $('#sent-sms').DataTable({
            "order": [[ 5, "desc" ]]
        });

        $("#show").on('shown.bs.modal', function(e) {
            let from = $(e.relatedTarget).data("from")
            let to = $(e.relatedTarget).data("to")
            let message = $(e.relatedTarget).data("message")
            let characters = $(e.relatedTarget).data("characters")
            let cost = $(e.relatedTarget).data("cost")
            let status = $(e.relatedTarget).data("status")
            let time_sent = $(e.relatedTarget).data("time_sent")
            
            let form = $('#show-message')
            
            form.find('#from').val(from)
            form.find('#to').val(to)
            form.find('#message').val(message)
            form.find('#characters').val(characters)
            form.find('#cost').val(cost)
            form.find('#status').val(status)
            form.find('#time_sent').val(time_sent)
        });
    </script>
@endpush
