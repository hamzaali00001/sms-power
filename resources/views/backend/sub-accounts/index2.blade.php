@extends('layouts.backend')

@section('title', 'Sub Accounts')

@section('css')
<link href="{{ asset('css/backend/dataTables.bootstrap.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="active">Sub Accounts</li>
        </ol>
    </div>
    
    @include('flash::message')
    
    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-cogs"></i> Sub Accounts</h3>
        <a href="{{ route('sub-accounts.create') }}"><i class="fa fa-plus"></i> Add User</a>
    </div>
            
    <table id="sub-accounts" class="table table-striped table-bordered" data-form="delete">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Credit</th>
                <th>Parent A/c</th>
                <th>Created On</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
    <!-- End Page Content -->
</div>
@stop

@push('scripts')
    @include('backend.partials.delete-modal')
    <script src="{{ asset('js/backend/dataTables.bootstrap.js') }}"></script>
    <script>
        $(function() {
            $('#sub-accounts').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                deferRender: true,
                ajax: {
                    url: '{{ route('sub-accounts.index') }}'
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'mobile', name: 'mobile' },
                    { data: 'credit', name: 'credit' },
                    { data: 'parent_id', name: 'parent_id' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });
        })
    </script>
@endpush
