@extends('layouts.backend')

@section('title', 'Scheduled SMS')

@section('css')
<link href="{{ asset('css/backend/dataTables.bootstrap.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="active">Scheduled SMS</li>
        </ol>
    </div>

    @include('flash::message')

    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-clock-o"></i> Scheduled SMS</h3>
    </div>

    @if (count($messages))   
        <table id="scheduled-sms" class="table table-striped table-bordered" data-form="delete">
            <thead>
                <tr>
                    <th>From (Sender)</th>
                    <th>Recipients</th>
                    <th>Cost</th>
                    <th>Status</th>
                    <th>Created On</th>
                    <th>Send Time</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($messages as $msg)
                    <tr>
                        <td><span class="responsive">From</span> {{ $msg->from }}</td>
                        <td><span class="responsive">Recipients</span> {{ $msg->recipients }}</td>
                        <td><span class="responsive">Cost</span> {{ $msg->cost }}</td>
                        <td><span class="responsive">Status</span> <span class="label label-info">{{ $msg->status }}</span></td>
                        <td><span class="responsive">Created On</span> {{ $msg->created_at }}</td>
                        <td><span class="responsive">Send Time</span> {{ $msg->send_time }}</td>
                        <td><span class="responsive">Actions</span> 
                            <span data-toggle="modal" data-target="#show" data-from="{{ $msg->from }}" data-recipients="{{ $msg->recipients }}" data-message="{{ $msg->message }}" data-cost="{{ $msg->cost }}" data-send_time="{{ $msg->send_time }}" data-status="{{ $msg->status }}"">
                                <button type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="View SMS"><i class="fa fa-search"></i></button>
                            </span>
                            <form action="{{ route('scheduled-sms.destroy', $msg) }}" class="form-delete" method="POST" style="display:inline">
                                <input type="hidden" name="_method" value="DELETE">
                                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-danger btn-xs" data-placement="top" data-toggle="tooltip" title="Cancel SMS"><i class="fa fa-trash-o"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="clearfix10"></div>
        <p>No Scheduled Messages Available</p>
    @endif
    <!-- End Page Content -->  
</div>
@stop

@push('scripts')
    @include('backend.scheduled-sms.show')
    @include('backend.partials.delete-modal')
    <script src="{{ asset('js/backend/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('js/backend/dataTables.bootstrap.js') }}"></script>
    <script>
        $('#scheduled-sms').DataTable({
            "order": [[ 4, "desc" ]]
        });

        $("#show").on('shown.bs.modal', function(e) {
            let from = $(e.relatedTarget).data("from")
            let recipients = $(e.relatedTarget).data("recipients")
            let message = $(e.relatedTarget).data("message")
            let cost = $(e.relatedTarget).data("cost")
            let send_time = $(e.relatedTarget).data("send_time")
            let status = $(e.relatedTarget).data("status")
            
            let form = $('#show-message')
            
            form.find('#from').val(from)
            form.find('#recipients').val(recipients)
            form.find('#message').val(message)
            form.find('#cost').val(cost)
            form.find('#send_time').val(send_time)
            form.find('#status').val(status)
        });
    </script>
@endpush
