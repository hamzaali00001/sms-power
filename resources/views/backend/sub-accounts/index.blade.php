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
            
    @if (count($users))
        <table id="sub-accounts" class="table table-striped table-bordered" data-form="delete">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Credit</th>
                    <th>Parent A/c</th>
                    <th>Created On</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td><span class="responsive">User ID</span> {{ $optimus->encode($user->id) }}</td>
                        <td><span class="responsive">Name</span> {{ $user->name }}</td>
                        <td><span class="responsive">Mobile</span> {{ $user->mobile ?? 'N/A' }}</td>
                        <td><span class="responsive">Credit</span> {{ number_format($user->credit) }}</td>
                        <td><span class="responsive">Parent A/c</span> {{ $user->parentAccount->name }}</td>
                        <td><span class="responsive">Created On</span> {{ $user->created_at }}</td>
                        <td><span class="responsive">Actions</span> 
                            <a href="{{ route('sub-accounts.show', $user) }}" class="btn btn-success btn-xs" data-placement="top" data-toggle="tooltip" title="View User"><i class="fa fa-search"></i></a>
                            <a href="{{ route('sub-accounts.edit', $user) }}" class="btn btn-info btn-xs" data-placement="top" data-toggle="tooltip" title="Edit User"><i class="fa fa-pencil-square-o"></i></a>
                            @if (!$user->hasRole('admin'))
                            <form action="{{ route('sub-accounts.destroy', $user) }}" class="form-delete" method="POST" style="display:inline">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <button type="submit" class="btn btn-danger btn-xs" data-placement="top" data-toggle="tooltip" title="Delete User"><i class="fa fa-trash-o"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="clearfix10"></div>
        <p>No Sub Accounts Available</p>
    @endif
    <!-- End Page Content -->
</div>
@stop

@push('scripts')
    @include('backend.partials.delete-modal')
    <script src="{{ asset('js/backend/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('js/backend/dataTables.bootstrap.js') }}"></script>
    <script>
        $('#sub-accounts').DataTable({
            "order": [[ 5, "desc" ]]
        });
    </script>
@endpush
