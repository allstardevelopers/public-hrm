@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css"
        media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Requests</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Clock Out</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Requests</a></li>
        </ol>
    </div>
@endsection
@php
    $slug = get_user_role(auth()->user()->id);
    $today = date('Y-m-d');
@endphp
@section('button')
    @if ($slug == 'admin')
        <a href="/attendance" class="btn btn-primary btn-sm btn-flat"><i
                class="mdi mdi-arrow-right-thick mr-2"></i>Attendance Table</a>
    @else
        <a href="/empattendence" class="btn btn-primary btn-sm btn-flat"><i
                class="mdi mdi-arrow-right-thick mr-2"></i>Attendance Table</a>
    @endif
@endsection

@section('content')
    @include('includes.flash')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="{{ $slug === 'admin' ? 'datatable-buttons' : 'datatable' }}"
                                class="table table-striped table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                        @foreach ($clockouts as $clockout)
    <tr>
                                            <td><span style="display:none;">{{ strtotime($clockout->created_at) }}</span>{{ date('d-M-Y', strtotime($clockout->attendance->attendance_date)) }}</td>
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
        
                                            <td>{{ $clockout->attendance->attendance_time }} </td>
                                            @if (isset($clockout->clock_out))
                                        <td>{{ date('H:i:s', strtotime($clockout->clock_out)) }}
                                        @if ($clockout->type == 1)
                                        <span class="badge badge-primary badge-pill float-right">official</span>
                                    @else
                                        <span class="badge badge-danger badge-pill float-right">short break</span>
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
                                                                                        @if ($slug == 'admin' && $clockout->type == 1 && $clockout->status == 0)
                                            <span class="badge badge-success badge-pill float-right"><a class="text-light update_clockin" data-id="{{ $clockout->id }}" data-reason="Reason: {{ $clockout->reason }}" ><i class="fa fa-edit"></i></a></span>
                                            @endif
                                                                                    </td>
                                        @elseif($today > $clockout->attendance->attendance_date)
                                            @php
                                                $cout = date('H:i:s', strtotime($clockout->clock_out));
                                                $cout = strtotime($clockout->clock_out);
                                                $cin = strtotime($clockout->employee->schedules->first()->time_out);
                                                $diff = round(($cin - $cout) / 60, 0);
                                                $timestam = $cin - $cout;
                                                //  $diff = $cin->diffminutes($cout);
                                            @endphp
                                            <td class="{{ $clockout->status == 0 ? 'text-danger' : 'text-success' }}">{{ gmdate('H:i:s', $timestam) }}
                                                @if ($slug == 'admin' && $clockout->type == 1 && $clockout->status == 0)
                                            <span class="badge badge-success badge-pill float-right"><a class="text-light update_clockin" data-id="{{ $clockout->id }}" data-reason="Reason: {{ $clockout->reason }}" ><i class="fa fa-edit"></i></a></span>
                                            @endif
                                            </td>
                                        @else
                                            <td></td>
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
            <script>
                
                // getswlinput()
            </script>
@endsection
