@extends('layouts.master')

@section('css')
<!-- Table css -->
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Clock Out</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Clock Out</a></li>
    </ol>
</div>
@endsection
@php
$slug = get_user_role(auth()->user()->id);
$today = date('Y-m-d');
@endphp
@section('button')
@if ($slug == 'admin')
<a href="/attendance" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-arrow-right-thick mr-2"></i>Attendance Table</a>
@else
<a href="/empattendence" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-arrow-right-thick mr-2"></i>Attendance Table</a>
@endif
@endsection

@section('content')
@include('includes.flash')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-6">
                        <h5 class="card-title">{{ $attendance->employee->name }}</h5>

                        <h6 class="card-subtitle mb-2 text-muted">
                            {{ date('l, jS F Y', strtotime($attendance->attendance_date)) }}
                        </h6>
                    </div>
                    <div style="display: flex; justify-content: right; align-items: center;" class="col-6">
                        @can('permission', 'update_clock_time')
                        <a href="#" data-toggle="modal" onclick="updateClockTime('{{ $attendance->id }}')" data-target="#ajax_update_modal" data-id="{{ $attendance->id }}" class="btn btn-success btn-sm btn-flat"><i class='fa fa-edit mx-2'></i>Update Clock Time</a>
                        @endcan
                    </div>
                </div>
                <div class="table-rep-plugin">

                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="{{ $slug === 'admin' ? 'datatable' : 'datatable' }}" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th data-priority="1">Date</th>
                                    <th data-priority="2">Employee ID</th>
                                    <th data-priority="3">Name</th>
                                    <th data-priority="4">Attendance</th>
                                    <th data-priority="5">Clock In</th>
                                    <th data-priority="6">Clock Out</th>
                                    <th data-priority="6">Total Clock Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                <pre>
                                        @php
                                            $clockin = $attendance->attendance_time;
                                        @endphp
                                        @foreach ($clockouts as $clockout)
                                            <tr>
                                                <td>{{ date('d-M-Y', strtotime($clockout->attendance->attendance_date)) }}</td>
                                                <td>{{ $clockout->emp_id }}</td>
                                                <td>{{ $clockout->employee->name }}</td>
                                                <td>{{ $clockout->attendance->attendance_time }}
                                                    @if ($clockout->attendance->status == 1)
                                                        <span class="badge badge-primary badge-pill float-right">On Time</span>
                                                    @elseif ($clockout->attendance->status == 2)
                                                        <span class="badge badge-warning badge-pill float-right">Half Day</span>
                                                    @else
                                                        <span class="badge badge-danger badge-pill float-right">Late</span>
                                                        @endif
                                                </td>
            
                                                <td>{{ date('H:i:s', strtotime($clockin)) }} </td>
                                                @if (isset($clockout->clock_out))
                                            <td>{{ date('H:i:s', strtotime($clockout->clock_out)) }}
                                                                                            @if ($clockout->type == 1)
                                            <span class="badge badge-primary badge-pill float-right">official</span>
                                            @else
                                            <span class="badge badge-danger badge-pill float-right">general break</span>
                                            @endif
                                            </td>
                                        @else
                                            <td></td>
                                            @endif
                                            @if (isset($clockout->clock_in))
                                            @php
                                                $cout = strtotime($clockout->clock_out);
                                                $cin = strtotime($clockout->clock_in);
                                                $diff = round(($cin - $cout) / 60, 0);
                                                $timestam = $cin - $cout;
                                                //  $diff = $cin->diffminutes($cout);
                                            @endphp
                                                 <td class="{{ $clockout->status == 0 ? 'text-danger' : 'text-success' }} ">{{ gmdate('H:i:s', $timestam) }}
                                                    @if ($slug == 'admin' && $clockout->status == 0)
                                                <span class="badge badge-success badge-pill float-right"><a class="text-light update_clockin" data-id="{{ $clockout->id }}" data-reason="Reason: {{ $clockout->reason }}" ><i class="fa fa-edit"></i></a></span>
                                        @elseif($slug == 'admin' && $clockout->status == 1)
                                            <span class="badge badge-success badge-pill float-right"><a class="text-light update_clockin" data-id="{{ $clockout->id }}" data-reason="Reason: {{ $clockout->reason }}" ><i class="fa fa-edit"></i></a></span>
                                                                        <span class="badge badge-success badge-pill float-right"><a class="text-light view_clockout_reason" data-id="{{ $clockout->id }}" data-reason="Reason: {{ $clockout->reason }}" data-remarks="{{isset($clockout->remarks)?'Remarks:'.$clockout->remarks:'' }}" ><i class="fa fa-eye"></i></a></span>
                                        @else
                                            <span class="badge badge-success badge-pill float-right"><a class="text-light view_clockout_reason" data-id="{{ $clockout->id }}" data-reason="Reason: {{ $clockout->reason }}"  data-remarks="{{isset($clockout->remarks)?'Remarks:'.$clockout->remarks:'' }}" ><i class="fa fa-eye"></i></a></span>
                                            @endif
                                                                                        </td>
                                        @elseif($today > $attendance->attendance_date)
                                            @php
                                                $cout = date('H:i:s', strtotime($clockout->clock_out));
                                                $cout = strtotime($clockout->clock_out);
                                                $cin = strtotime($attendance->employee->schedules->first()->time_out);
                                                if ($cout < strtotime($attendance->employee->schedules->first()->time_out)) {
                                                    $cin = $cout;
                                                }
                                                $diff = round(($cin - $cout) / 60, 0);
                                                $timestam = $cin - $cout;
                                                //  $diff = $cin->diffminutes($cout);
                                            @endphp
                                                <td class="{{ $clockout->status == 0 ? 'text-danger' : 'text-success' }}">{{ gmdate('H:i:s', $timestam) }}
                                                    @if ($slug == 'admin' && $clockout->type == 1 && $clockout->status == 0)
                                                        <span class="badge badge-success badge-pill float-right"><a class="text-light update_clockin" data-id="{{ $clockout->id }}" data-reason="Reason: {{ $clockout->reason }}" ><i class="fa fa-edit"></i></a></span>
                                                    @elseif($slug == 'admin' && $clockout->status == 1)
                                                        <span class="badge badge-success badge-pill float-right"><a class="text-light update_clockin" data-id="{{ $clockout->id }}" data-reason="Reason: {{ $clockout->reason }}" ><i class="fa fa-edit"></i></a></span>
                                                                                                                <span class="badge badge-success badge-pill float-right"><a class="text-light view_clockout_reason" data-id="{{ $clockout->id }}" data-reason="Reason: {{ $clockout->reason }}" data-remarks="{{isset($clockout->remarks)?'Remarks:'.$clockout->remarks:'' }}" ><i class="fa fa-eye"></i></a></span>
                                                    @else
                                                        <span class="badge badge-success badge-pill float-right"><a class="text-light view_clockout_reason" data-id="{{ $clockout->id }}" data-reason="Reason: {{ $clockout->reason }}" data-remarks="{{isset($clockout->remarks)?'Remarks:'.$clockout->remarks:'' }}" ><i class="fa fa-eye"></i></a></span>
                                                        @endif                                                                                                                
                                                    </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    </tr>
                                                    @php
                                                        $clockin = $clockout->clock_in;
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                <td>{{ date('d-M-Y', strtotime($attendance->attendance_date)) }}</td>
                                                <td>{{ $attendance->emp_id }}</td>
                                                <td>{{ $attendance->employee->name }}</td>
                                                <td>{{ $attendance->attendance_time }}
                                                    @if ($attendance->status == 1)
                                                    <span class="badge badge-primary badge-pill float-right">On Time</span>
                                                @elseif ($attendance->status == 2)
                                                    <span class="badge badge-warning badge-pill float-right">Half Day</span>
                                                @else
                                                    <span class="badge badge-danger badge-pill float-right">Late</span>
                                                    @endif
                                                </td>
                                                @php
                                                    if (!isset($clockin) && $today > $attendance->attendance_date) {
                                                        $clockin = $attendance->employee->schedules->first()->time_out;
                                                    }
                                                @endphp
                                                @if (!isset($clockin) && $today == $attendance->attendance_date)
                                                    <td class="text-danger">On Break</td>
                                                @else
                                                    <td >{{ date('H:i:s', strtotime($clockin)) }}</td>
                                                @endif
                                                @if (isset($attendance->check->where('attendance_id', $attendance->id)->first()->leave_time))
                                                    <td>{{ date('H:i:s', strtotime($attendance->check->where('attendance_id', $attendance->id)->first()->leave_time)) }}
                                                <span class="badge badge-primary badge-pill float-right">Check out</span>
                                                </td>
                                                @else
                                                    @if (date('Y-m-d') == $attendance->attendance_date)
                                                    <td>Not Check Out Yet</td>
                                                @else
                                                    <td>Check Out Missing</td>
                                                @endif
                                            @endif
                                            @if (isset($attendance->clockout))
                                            @php
                                                $tclockout = 0;
                                                $totaltimestam = 0;
                                            @endphp
                                            @foreach ($attendance->clockout as $clockout)
                                            @php
                                                if ($clockout->status == 0) {
                                                    $cout = strtotime($clockout->clock_out);
                                                    if (isset($clockout->clock_in)) {
                                                        $cin = strtotime($clockout->clock_in);
                                                    } else {
                                                        if (date('Y-m-d') == $attendance->attendance_date) {
                                                            $cin = strtotime(now());
                                                        } else {
                                                            $cin = strtotime($attendance->employee->schedules->first()->time_out);
                                                        }
                                                    }
                                                    $timestam = $cin - $cout;
                                                    $diff = round(($cin - $cout) / 60, 0);
                                                    $tclockout += $diff;
                                                    $totaltimestam += $timestam;
                                                }
                                            @endphp
                                            @endforeach
                                            <td>{{ gmdate('H:i:s', $totaltimestam) }}
                                                <span class="badge badge-danger badge-pill float-right">Total Clock Out</span>
                                            </td>
                                            @else
                                                <td></td>
                                            @endif
                                                 </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @php
                                $workingtime = 0;

                                if (isset($attendance->check->attendance_time)) {
                                    if (isset($attendance->check->leave_time)) {
                                        $workingtime = strtotime($attendance->check->leave_time) - strtotime($attendance->check->attendance_time);
                                        $workingtime = $workingtime - $tclockout;
                                    } else {
                                        $workingtime = abs(strtotime(now()) - strtotime($attendance->attendance_time));
                                        $today = date('Y-m-d');
                                        if ($today > $attendance->attendance_date) {
                                            $workingtime = abs(strtotime($attendance->employee->schedules->first()->time_out) - strtotime($attendance->attendance_time));
                                        }
                                        if ($workingtime / 3600 > 8) {
                                            $workingtime = abs(strtotime($attendance->employee->schedules->first()->time_out) - strtotime($attendance->attendance_time));
                                        }
                                    }
                                    $workingtime = $workingtime - $tclockout * 60;
                                }
                            @endphp
                            <h3>Total Work Time:  <Span class="text-success">{{ gmdate('H:i:s', $workingtime) }}</Span></h3>
                        </div>
                    </div>
                    </section>
                </div>
                <script>
                    function updateClockTime(id) {
                        let url = '/update-in-out-time/' + id;
                        // console.log(url);
                        $.ajax({
                            url: url,
                            type: "Get",
                            dataType: 'json',
                            success: function(data) {
                                ajax_modal(data, "" + "{{ route('ajax_modal_contents', 'update_in_out_time') }}")
                            }

                        })
                    }
                </script>
                @include('includes.ajax_modal')
    @endsection