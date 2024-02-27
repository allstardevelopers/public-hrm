@extends('layouts.master')

@section('css')
<!-- Table css -->
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection
@php
$slug = get_user_role(auth()->user()->id);
@endphp
@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Leave</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Leave</a></li>


    </ol>
</div>
@endsection
@section('button')
@if ($slug == 'employee')
<a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Request Leave</a>
@endif
@if ($slug == 'admin')
<a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add Leave</a>
@endif
@endsection

@section('content')
@include('includes.flash')
@if($slug=='employee')
@include('includes.employee_leave')
@endif
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead class="bg-primary text-white">
                                <tr>
                                    <th data-priority="2">Employee ID</th>
                                    <th data-priority="3">Name</th>
                                    <th data-priority="1">Date</th>
                                    <th data-priority="5">Leave</th>
                                    <th data-priority="6">Status</th>
                                    <th data-priority="6">Time In</th>
                                    <th data-priority="7">Time Out</th>
                                    <th data-priority="8">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($leaves as $leave)
                                @php
                                $event_date = \Carbon\Carbon::parse($leave->leave_date)->toDateString();
                                $is_event = checkEvents($event_date, 1);
                                @endphp
                                <tr>
                                    <td><span style="display:none;">{{strtotime($leave->created_at)}}</span>{{ $leave->emp_id }}</td>
                                    <td>{{ $leave->employee->name }}</td>
                                    <td>{{ $leave->leave_date }}
                                        @if(!$is_event)
                                        @if ($leave->type == 2)
                                        {{-- Type 2 For Live Request And Type one For Half Day Request/ Status 1 and state 0 maen Pending State 1 Approved status 0 means Canceled  --}}
                                        <span class="badge badge-primary badge-pill float-right">Leave</span>
                                        @elseif ($leave->type == 3 && $leave->state == 0)
                                        <span class="badge badge-danger badge-pill float-right">Absent</span>
                                        @elseif ($leave->type == 3 && $leave->state == 1)
                                        <span class="badge badge-primary badge-pill float-right">Leave</span>
                                        @else
                                        <span class="badge badge-info badge-pill float-right">Half Day</span>
                                        @endif
                                        @else
                                        <span class="badge badge-success badge-pill float-right">Public Holiday</span>
                                        @endif
                                    </td>
                                    <td>{{ $leave->leave_time }}
                                    </td>
                                    <td>

                                        @if ($leave->state == 1)
                                        <span class="badge badge-success badge-pill float-right">Approved</span>
                                        @elseif($leave->status == 0 && $leave->state == 0)
                                        <span class="badge badge-danger badge-pill float-right">Disapproved</span>
                                        @elseif($leave->status == 1 && $leave->state == 0)
                                        <span class="badge badge-warning badge-pill float-right">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $leave->employee->schedules->first()->time_in }}</td>
                                    <td>{{ $leave->employee->schedules->first()->time_out }}</td>
                                    @if ($slug == 'admin')
                                    <td>
                                        <a href="#" data-toggle="modal" data-target="#ajax_update_modal" data-id="{{ $leave->id }}" class="btn btn-success btn-sm update_leaverequest btn-flat"><i class='fa fa-edit'></i></a>
                                        <a class="btn btn-danger btn-sm text-light delete_leave" onclick="deleteSwal('{{ $leave->id }}', 'Do you want to delete?','{{ route('leave.delete', ['leave' => $leave->id]) }}', '0')"><i class="fa fa-trash"></i></a>
                                        <a class=" btn btn-info btn-sm text-light view_leave_reason" data-id="{{ $leave->id }}" data-reason="Reason: {{ $leave->leave_reason }}"><i class="fa fa-eye"></i></a>
                                    </td>
                                    @endif
                                    @if ($slug == 'employee')
                                    <td>
                                        <a class=" btn btn-info btn-sm text-light view_leave_reason" data-id="{{ $leave->id }}" data-reason="Reason: {{ $leave->leave_reason }}"><i class="fa fa-eye"></i></a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
</div>
@if($slug=='admin')
@include('includes.add_leave_by_admin')
@elseif($slug=='employee')
@include('includes.add_leaverequest')
@endif

@include('includes.ajax_modal')
@endsection