@extends('layouts.backend')

@section('title', 'Templates')

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="active">Templates</li>
        </ol>
    </div>

    @include('flash::message')
    
    <!-- Start Page Content -->
    <div class="sms_heading">
        <h3><i class="fa fa-file-text-o"></i> Templates</h3>
        <a data-target="#create" data-toggle="modal"><i class="fa fa-plus"></i> Add New</a>
        <a href="#delete" class="btn btn-danger" data-toggle="modal" id="sms_all"><i class="fa fa-trash-o"></i> Delete All</a>
    </div>
            
    @if (count($templates)) 
        <table id="templates" class="table table-striped table-bordered" data-form="delete">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Client Name</th>
                    <th>Client Email</th>
                    <th>Client Mobile</th>
                    <th>Created On</th>
                    <th>Updated On</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($templates as $template)
                    <tr>
                        <td><span class="responsive">Name</span> {{ $template->name }}</td>
                        <td><span class="responsive">Client Name</span> {{ $template->user->name }}</td>
                        <td><span class="responsive">Client Email</span> {{ $template->user->email }}</td>
                        <td><span class="responsive">Client Mobile</span> {{ $template->user->mobile }}</td>
                        <td><span class="responsive">Created On</span> {{ $template->created_at }}</td>
                        <td><span class="responsive">Updated On</span> {{ $template->updated_at }}</td>
                        <td><span class="responsive">Actions</span>
                            <span data-toggle="modal" data-target="#show" data-name="{{ $template->name }}" data-message="{{ $template->message }}" data-characters="{{ strlen( utf8_decode($template->message)) }}"">
                                <button type="button" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="View Template"><i class="fa fa-search"></i></button>
                            </span>
                            <span data-toggle="modal" data-target="#edit" data-name="{{ $template->name }}" data-message="{{ $template->message }}" data-form-action="{{ route('templates.update', $template) }}">
                                <button type="button" class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit Template"><i class="fa fa-pencil-square-o"></i></button>
                            </span>
                            <form action="{{ route('templates.destroy', $template) }}" class="form-delete" method="POST" style="display:inline">
                                <input type="hidden" name="_method" value="DELETE">
                                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-danger btn-xs" data-placement="top" data-toggle="tooltip" title="Delete Template"><i class="fa fa-trash-o"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="clearfix10"></div>
        <p>No Templates Available</p>
    @endif
    <!-- End Page Content -->  
</div>
@stop

@push('scripts')
    @include('backend.templates.create')
    @include('backend.templates.edit')
    @include('backend.templates.show')
    @include('backend.partials.delete-modal')
    <script>
        $('#templates').DataTable({
            "order": [[ 4, "desc" ]]
        });

        $("#show").on('shown.bs.modal', function(e) {
            let name = $(e.relatedTarget).data("name")
            let message = $(e.relatedTarget).data("message")
            let characters = $(e.relatedTarget).data("characters")
            
            let form = $('#show-template')
            
            form.find('#name').val(name)
            form.find('#message').val(message)
            form.find('#characters').val(characters)
        });

        $("#edit").on('shown.bs.modal', function(e) {
            let name = $(e.relatedTarget).data("name")
            let message = $(e.relatedTarget).data("message")

            let form = $('#edit-template')
            
            form.find('#name').val(name)
            form.find('#message').val(message)
            form.attr('action', $(e.relatedTarget).data("form-action"))
        });

        $("#addTemplate").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass);
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass);
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {     
                    error.insertAfter(element.next('span'))
                } else {
                    error.insertAfter(element);
                }
            }
        });
    </script>
@endpush
