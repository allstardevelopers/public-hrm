@extends('layouts.master')

@section('css')
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Employees</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Employees</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Employees List</a></li>

    </ol>
</div>
@endsection
@section('button')
<a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Employee</a>
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
                            <th data-priority="1">Employee ID</th>
                            <th data-priority="2">Name</th>
                            <th data-priority="3">position</th>
                            <th data-priority="4">Email</th>
                            <th data-priority="5">Schedule</th>
                            <th data-priority="6">Joining Date</th>
                            <th data-priority="7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                        <tr>
                            <td>AST-{{ $employee->id }}</td>
                            <td>{{ $employee->name }}</td>

                            <td>{{ $employee->position }} {!! isEmployeeResigned($employee) !!}</td>
                            <td>{{ $employee->email }}</td>
                            <td>
                                @if (isset($employee->schedules->first()->slug))
                                {{ $employee->schedules->first()->slug }}
                                @endif
                            </td>
                            @if (isset($employee->joining_date))
                            <td>{{ date('d-m-Y', strtotime($employee->joining_date)) }}</td>
                            @else
                            <td>{{ date('d-m-Y', strtotime($employee->created_at)) }}</td>
                            @endif
                            <td>
                                <a href="#edit{{ $employee->id }}" data-toggle="modal" class="btn btn-success btn-sm edit btn-flat"><i class='fa fa-edit'></i></a>
                                {{-- <a href="#delete{{$employee->id}}" data-toggle="modal" class="btn btn-danger btn-sm delete btn-flat"><i class='fa fa-trash'></i> Delete</a> --}}
                                <a href="#" data-toggle="modal" data-target="#ajax_update_modal" data-id="{{ $employee->id }}" class="btn btn-info btn-sm view_employeeDetails btn-flat"><i class='fa fa-eye'></i></a>
                                <a href="#" data-toggle="modal" data-target="#ajax_update_modal" data-id="{{ $employee->id }}" class="btn btn-danger btn-sm dactive_employee btn-flat"><i class='fa fa-minus-circle'></i></a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@foreach ($employees as $employee)
@include('includes.edit_delete_employee')
@endforeach
<script>
    $('.view_employeeDetails').on('click', function() {
        console.log($(this).data('id'));
        let id = $(this).data('id');
        let url = '/employee_data/' + id;
        console.log(url);
        $.ajax({
            url: url,
            type: "Get",
            dataType: 'json',
            success: function(data) {
                ajax_modal(data, "" + "{{ route('ajax_modal_contents', 'view_employee') }}")
            }
        })
    })
    $('.dactive_employee').on('click', function() {
        console.log($(this).data('id'));
        let id = $(this).data('id');
        let url = '/employee_data/' + id;
        console.log(url);
        $.ajax({
            url: url,
            type: "Get",
            dataType: 'json',
            success: function(data) {
                ajax_modal(data, "" + "{{ route('ajax_modal_contents', 'deactive_employee') }}")
            }
        })
    })
</script>
@include('includes.add_employee')
@include('includes.ajax_modal')
@endsection


@section('script')
<!-- Responsive-table-->
@endsection