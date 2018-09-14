@extends('layouts.backend')

@section('title', 'Sender IDs')

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="Status">Sender IDs</li>
        </ol>
    </div>

    @include('flash::message')

    <form class="form-horizontal form-bordered">
        <div class="form-group group-title">
            <div class="col-md-12">
                <ul class="sms_list">
                    <li><i class="fa fa-circle-o"></i>A Sender ID is a custom branded name that helps you send personalised sms messages e.g <strong>SMSPOWER</strong> or <strong>JOHN-DOE</strong>.</li>
                    <li><i class="fa fa-circle-o"></i>Your Sender ID must be <strong>YOUR NAME</strong> or the <strong>LEGALLY Registered Business Name</strong> or <strong>Trading Name</strong> of your business.</li>
                    <li><i class="fa fa-circle-o"></i>If you don't have your own branded Sender ID, your sms messages will be sent using our default Sender ID - <strong>SMSPOWER</strong>.</li>
                    <li><i class="fa fa-circle-o"></i>The Sender ID cost &amp; setup fee is <strong>KES {{ number_format(env('SENDERID_COST')) }}</strong> one time cost. Registration period of the Sender ID with the telcos is <strong>3-14</strong> days.</li>
                </ul>
            </div>
        </div>
    </form>

    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-asterisk"></i> Sender IDs</h3>
        <a href="{{ route('senderids.create') }}"><i class="fa fa-shopping-cart"></i> Buy New</a>
    </div>
    
    @if (count($senderids))        
        <table id="sender-ids" class="table table-striped table-bordered" data-form="delete">
            <thead>
                <tr>
                    <th>Sender ID</th>
                    <th>Cost</th>
                    <th>Trans. Code</th>
                    <th>Client Name</th>
                    <th>Status</th>
                    <th>Created On</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($senderids as $senderid)
                    <tr>
                        <td><span class="responsive">Sender ID</span> {{ $senderid->name }}</td>
                        <td><span class="responsive">Cost</span> {{ number_format($senderid->cost) }}</td>
                        <td><span class="responsive">Trans. Code</span> {{ $senderid->trans_code ?? 'N/A' }}</td>
                        <td><span class="responsive">Client Name</span> {{ $senderid->user->name }}</td>
                        <?php
                            if ($senderid->status == 'Active') $label = "label-success";
                            if ($senderid->status == 'Rejected') $label = "label-danger";
                            if ($senderid->status == 'Processing') $label = "label-info";
                        ?>
                        <td><span class="responsive">Status</span> <span class="label {{ $label }}">{{ $senderid->status }}</span></td>
                        <td><span class="responsive">Created On</span> {{ $senderid->created_at }}</td>
                        <td><span class="responsive">Actions</span>
                            @if (auth()->user()->hasRole('admin') && $senderid->status != 'Active')
                            <a href="{{ route('senderids.edit', $senderid->slug) }}" class="btn btn-info btn-xs" data-placement="top" data-toggle="tooltip" title="Edit Sender ID"><i class="fa fa-pencil-square-o"></i></a>
                            @endif
                            @if ($senderid->user->role != 'admin')
                            <form action="{{ route('senderids.destroy', $senderid) }}" class="form-delete" method="POST" style="display:inline">
                                <input type="hidden" name="_method" value="DELETE">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-xs" data-placement="top" data-toggle="tooltip" title="Delete Sender ID"><i class="fa fa-trash-o"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="clearfix10"></div>
        <p>No Sender IDs Available</p>
    @endif
    <!-- End Page Content -->  
</div>
@stop

@push('scripts')
    @include('backend.partials.delete-modal')
    <script>
        $('#sender-ids').DataTable({
            "order": [[ 5, "desc" ]]
        });
    </script>
@endpush
