@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Employees</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Upcoming Events</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Upcoming Events List</a></li>
    </ol>
</div>
@endsection
@section('button')
<a href="#addevent" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add New Event</a>
@endsection

@section('content')
@include('includes.flash')



<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                    <thead class="bg-primary text-white">
                        <tr>
                            <th data-priority="2">Title</th>
                            <th data-priority="3">Event Date</th>
                            <th data-priority="4">Descrption</th>
                            <th data-priority="5">Event Type</th>
                            <th data-priority="7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_upcoming_event as $upcoming_event)
                        <tr>
                            <td>{{ $upcoming_event->title }}</td>
                            <td>{{ date('d-m-Y', strtotime($upcoming_event->event_held_on)) }}</td>
                            <td>{{ $upcoming_event->descrption }}</td>
                            <td>{{ $upcoming_event->event_type == 0 ? 'Inhouse Celebration' : 'Holiday' }}</td>
                            <td>
                                <a href="#" class="btn btn-success btn-sm edit btn-flat" data-toggle="modal" data-target="#editevent" data-event-id="{{ $upcoming_event->id }}">
                                    <i class='fa fa-edit'></i>
                                </a>
                                <a href="javascript:void(0)" onclick="DeleteIt('{{$upcoming_event->id}}')" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i> Delete</a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('includes.edit_event')
<script>
    function DeleteIt(id) {
        alert(id);
        let url = '/delete-event/' + id;
        swalWithBootstrapButtons.fire({
            title: 'Delete',
            text: "Do you want to Delete?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Delete',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                get_ajax_call(url)
            } else if (result.dismiss === Swal.DismissReason.cancel) {}
        })
    }

    $('.edit').on('click', function() {
        var eventId = $(this).data('event-id');
        $('#id').val(eventId);
        $.ajax({
            url: '/edit-event/' + eventId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Populate the modal with the event data

                $('#title').val(data.title);
                $('#event_type').val(data.event_type);
                $('#event_held_on').val(data.event_held_on);
                $('#emp_id').val(data.emp_id);
                $('#descrption').val(data.descrption);
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
</script>
@include('includes.add_event')

@include('includes.ajax_modal')
@endsection


@section('script')
<!-- Responsive-table-->
@endsection