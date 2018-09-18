@extends('layouts.backend')

@section('title', 'Groups')

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="active">Groups</li>
        </ol>
    </div>

    @include('flash::message')

    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-address-book-o"></i> Groups</h3>
        <a data-target="#create" data-toggle="modal"><i class="fa fa-plus"></i> Add New</a>
    </div>
    
    @if (count($groups))        
        <table id="groups" class="table table-striped table-bordered" data-form="delete">
            <thead>
                <tr>
                    <th data-orderable="false">Group Name</th>
                    <th>Client Name</th>
                    <th>Client Mobile</th>
                    <th>Contacts</th>
                    <th>Add Contacts</th>
                    <th>Created On</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <td><span class="responsive">Group Name</span> {{ $group->name }}</td>
                        <td><span class="responsive">Client Name</span> {{ $group->user->name }}</td>
                        <td><span class="responsive">Client Mobile</span> {{ $group->user->mobile }}</td>
                        <td><span class="responsive">Contacts</span> {{ $group->contacts->count() }}</td>
                        <td><span class="responsive">Add Contacts</span> <a href="{{ route('groups.contacts.create', $group) }}"><i class="fa fa-plus-square"></i> Add Contacts</a></td>
                        <td><span class="responsive">Created On</span> {{ $group->created_at }}</td>
                        <td><span class="responsive">Actions</span> 
                            <a href="{{ route('groups.contacts.create', $group) }}" class="btn btn-primary btn-xs"><i class="fa fa-plus" data-placement="top" data-toggle="tooltip" title="Add Group Contacts"></i></a>
                            <a href="{{ route('groups.contacts.index', $group) }}" class="btn btn-success btn-xs" data-placement="top" data-toggle="tooltip" title="View Group Contacts"><i class="fa fa-search"></i></a>
                            <span data-toggle="modal" data-target="#edit" data-name="{{ $group->name }}" data-form-action="{{ route('groups.update', $group) }}">
                                <button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit Group Name"><i class="fa fa-pencil-square-o"></i></button>
                            </span>
                            <form action="{{ route('groups.destroy', $group) }}" class="form-delete" method="POST" style="display:inline">
                                <input type="hidden" name="_method" value="DELETE">
                                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-danger btn-xs" data-placement="top" data-toggle="tooltip" title="Delete Group"><i class="fa fa-trash-o"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="clearfix10"></div>
        <p>No Groups Available</p>
    @endif
    <!-- End Page Content -->  
</div>
@stop

@push('scripts')
    @include('backend.groups.create')
    @include('backend.groups.edit')
    @include('backend.partials.delete-modal')
    <script>
        $('#groups').DataTable({
            "order": [[ 4, "desc" ]]
        });

        $("#edit").on('shown.bs.modal', function(e) {
            let name = $(e.relatedTarget).data("name");

            let form = $('#edit-group');
            
            form.find('#name').val(name);
            form.attr('action', $(e.relatedTarget).data("form-action"));
        });

        function validateForm(formName) {
            let form = $('form[name=' + formName +']');
            let x = form.find('[name=name]')[0].value;
            if (x == "") {
                let element = form.find('.form-group')[0];
                element.classList.add("has-error");
                let span_lists = form.find('.help-bloc');
                for(i=0; i<span_lists.length; i++){
                    $(span_lists[i]).remove();
                }
                let after = form.find('.form-group input')[0];
                $('<span class="help-block"><strong>The group name is required.</strong></span>').insertAfter(after);
                return false;
            }
        }

        function removeErrors(formName) {
            let form = $('form[name=' + formName + ']');
            let x = form.find('[name=name]')[0].value;
            if (x) {
                let element = form.find('.form-group')[0];
                element.classList.remove("has-error");
                form.find('.help-block').remove();
            }
        }

        $(".cancel_form").click(function(){
            let form = $(this).closest('form');
            let element = form.find('.form-group')[0];
            element.classList.remove("has-error");
            form.find('.help-block').remove();
        });
    </script>
@endpush
