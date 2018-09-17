@extends('layouts.backend')

@section('title', 'Contacts')

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li><a href="{{ route('groups.index') }}">Groups</a></li>
            <li class="active">{{ $group->name }}</li>
        </ol>
    </div>
    @include('flash::message')
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                {{ $error }} <br>
            @endforeach
        </div>
    @endif

    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-at"></i> Contacts</h3>
        <a href="{{ route('groups.contacts.create', $group) }}" data-toggle="modal"><i class="fa fa-plus"></i> Add New</a>
        <a href="{{ route('delete-contacts', $group) }}" class="btn btn-danger" data-toggle="modal" id="sms_all"><i class="fa fa-trash-o"></i> Delete All</a>
    </div>
    
    @if (count($contacts))        
        <table id="contacts" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th data-orderable="false" class="text-center checkAll"><input id="checkAll" type="checkbox" value="all" name="rowCheck" data-index="1"> <span class="visible-xs">Select All</span></th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Active</th>
                    <th>Created On</th>
                    <th>Updated On</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td class="text-center"><input class="rowCheck" type="checkbox" value="{{ $contact->id }}" name="rowCheck" data-index="1"></td>
                        <td><span class="responsive">Name</span> {{ $contact->name }}</td>
                        <td><span class="responsive">Mobile</span> {{ str_repeat("*", strlen($contact->mobile)-4) . substr($contact->mobile, -4) }}</td>
                        <td><span class="responsive">Active</span> {{ $contact->active }}</td>
                        <td><span class="responsive">Created On</span> {{ $contact->created_at }}</td>
                        <td><span class="responsive">Updated On</span> {{ $contact->updated_at }}</td>
                        <td><span class="responsive">Actions</span>
                            <span data-toggle="modal" data-target="#edit" data-name="{{ $contact->name }}" data-mobile="{{ $contact->mobile }}" data-active="{{ $contact->active }}"data-form-action="{{ route('groups.contacts.update', [$group, $contact]) }}">
                                <button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit Contact"><i class="fa fa-pencil-square-o"></i></button>
                            </span>
                            <form action="{{ route('groups.contacts.destroy', [$group, $contact]) }}" class="form-delete" method="POST" style="display:inline">
                                <input type="hidden" name="_method" value="DELETE">
                                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-danger btn-xs" data-placement="top" data-toggle="tooltip" title="Delete Contact"><i class="fa fa-trash-o"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="clearfix10"></div>
        <p>No Contacts Available</p>
    @endif
    <!-- End Page Content -->
</div>
@stop

@push('scripts')
    @include('backend.contacts.edit')
    @include('backend.partials.delete-modal')
    <script>
        $('#contacts').DataTable({
            "order": [[ 4, "desc" ]]
        });

        $("#mobile").intlTelInput({
            autoPlaceholder: true,
            nationalMode: true,
            initialCountry: "ke",
            utilsScript: "../../../../js/backend/utils.js",
            placeholderNumberType: 'MOBILE',
            hiddenInput: "full_phone",
        });

        $("#edit-contact").submit(function() {
            $('#mobile').val($('#mobile').intlTelInput("getNumber"));
        });

        $("#edit").on('shown.bs.modal', function(e) {
            let name = $(e.relatedTarget).data("name");
            let mobile = $(e.relatedTarget).data("mobile");
            let active = $(e.relatedTarget).data("active");
            if (active === 'Yes') {
                active = 1;
            } else {
                active = 0;
            }
            $('#active').val(active).trigger('change.select2');

            let form = $('#edit-contact');

            form.find('#name').val(name);
            form.find('#mobile').val(mobile);
            console.log(active);
            form.attr('action', $(e.relatedTarget).data("form-action"));
        });
    </script>
@endpush
