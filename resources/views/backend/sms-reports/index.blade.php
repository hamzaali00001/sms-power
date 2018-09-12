@extends('layouts.backend')

@section('title', 'SMS Reports')

@section('content')
<div class="sms_container">
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="Status">SMS Reports</li>
        </ol>
    </div>

    <!-- Start Page Content -->
    <div id='calendar'></div>
    <!-- End Page Content -->  
</div>
@stop

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev, today',
                    center: 'title',
                    right: 'month, next'
                },
                timeFormat: ' ',
                eventOrder: 'total,delivered,scheduled,failed',
                editable: true,
                eventLimit: true, // allow "more" link when too many events
            });
        });
    </script>
@endpush
