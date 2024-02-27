@extends('layouts.master')

@section('css')
<!-- Table css -->
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Attendance</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Attendance</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Today Attendance</a></li>


    </ol>
</div>
@endsection
@php
$slug = get_user_role(auth()->user()->id);
$today = date('Y-m-d'); 
$i=1;
$currentYear = \Carbon\Carbon::now()->year;
$startYear = 2023;
@endphp
@section('button')
<a href="/attendance" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-calendar-multiple mr-2"></i>Attendance</a>
@endsection

@section('content')
@include('includes.flash')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if ($slug == 'admin')
                        <form method="POST" id="sort-attendance" action="{{ route('employee.attendance.monthly') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{-- <label for="employee_name" class="col-sm-6 control-label">Employee Name</label> --}}
                                        <select class="form-control" id="emp_id" name="emp_id" required>
                                            <option value="" selected>- Select Employee -</option>
                                            @foreach ($employees as $employee)
                                                @if (isset($emp_id))
                                                    <option value="{{ $employee->id }}"
                                                        {{ $emp_id == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->name }}</option>
                                                @else
                                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{-- <label for="employee_name" class="col-sm-6 control-label">Month</label> --}}
                                        <select class="form-control" id="month_id" name="month_id" required>
                                            <option value="" selected>- Select Month -</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                @if (isset($month_id))
                                                    <option value="{{ $i }}"
                                                        {{ $month_id == $i ? 'selected' : '' }}>
                                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                                @else
                                                    <option value="{{ $i }}">
                                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        {{-- <label for="employee_name" class="col-sm-6 control-label">Month</label> --}}
                                        <select class="form-control" id="year" name="year" required>
                                            
                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                                @if (isset($syear))
                                                    <option value="{{ $year }}"
                                                        {{ $syear == $year ? 'selected' : '' }}>{{ $year }}
                                                    </option>
                                                @else
                                                    <option value="{{ $year }}"
                                                        {{ $currentYear == $year ? 'selected' : '' }}>{{ $year }}
                                                    </option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 align-bottom">
                                    {{-- <label hidden>Month</label> --}}
                                    <button type="submit" class="btn align-bottom btn-primary waves-effect waves-light">
                                        Search
                                    </button>
                                    <a href="/attendance" class="btn align-bottom btn-primary waves-effect waves-light">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    @endif
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" >
                        <table id="today-Datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                            <thead>
                                <tr>
                                    <th type="date-eu">Date</th>
                                    <th data-priority="10">Employee ID</th>
                                    <th data-priority="10">Name</th>
                                    {{-- <th data-priority="4">Schedule Time </th> --}}
                                    <th data-priority="10">Check In</th>
                                    <th data-priority="10">Check Out</th>
                                    <th data-priority="10">Break Spent</th>
                                    <th data-priority="10">Break Remaning</th>
                                    <th data-priority="10">Work Time</th>
                                    <th data-priority="10">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <pre>
                                @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{date('d-M-Y',strtotime($attendance->attendance_date) )}}</td>
                                    <td>{{ $attendance->emp_id }}</td>
                                    <td>{{ $attendance->employee->name }}</td>
                                    {{-- <td>{{ $attendance->schedules->time_in}} </td> --}}
                                    <td>{{ $attendance->attendance_time }}
                                        @if ($attendance->status == 1)
                                        <span class="badge badge-primary badge-pill float-right">On Time</span>
                                        @elseif ($attendance->status == 2)
                                            <span class="badge badge-warning badge-pill float-right">Half Day</span>
                                        @else
                                        <span class="badge badge-danger badge-pill float-right">Late</span>
                                        @endif
                                    </td>
                                    @if  (isset($attendance->check->leave_time))
                                    <td>{{ date('H:i:s', strtotime($attendance->check->leave_time)) }}</td>
                                    @else
                                       @if(date('Y-m-d')==$attendance->attendance_date)
                                         <td>Not Check Out Yet</td>                                
                                       @else
                                         <td>Check Out Missing</td>
                                       @endif
                                     @endif
                                    @if(isset($attendance->clockout))
                                    @php
                                        $tclockout= 0;
                                        $hours=0;
                                        $minutes=0;
                                    @endphp
                                    @foreach ($attendance->clockout as $clockout)
                                      @php
                                        if($clockout->status==0)
                                        {
                                            $cout = strtotime($clockout->clock_out);
                                             if(isset($clockout->clock_in))
                                             {
                                                $cin = strtotime($clockout->clock_in);
                                             }
                                             else {
                                                if(date('Y-m-d')==$attendance->attendance_date)
                                                {
                                                    $cin = strtotime(now());
                                                } else {
                                                    $cin = strtotime($clockout->clock_out. ' + 1 hours'); 
                                                }
                                                
                                             }
                                             $diff = round(($cin - $cout) / 60, 0);
                                             $tclockout+=$diff;
                                             $hours= floor($tclockout/60);
                                             $minutes=floor($tclockout%60);
                                        }
                                      @endphp
                                   @endforeach
                                   @php
                                        $totalbreak = $attendance->employee->schedules->first()->otherday_break ?? 60;
                                        $day = date('D', strtotime($attendance->attendance_date));
                                        if ($day == 'Fri') {
                                            $totalbreak = $attendance->employee->schedules->first()->friday_break ?? 75;
                                        }
                                        if ($attendance->status == 2) {
                                            $totalbreak = $attendance->employee->schedules->first()->halfday_break ?? 30;
                                        }
                                        $remaning = $totalbreak - $tclockout;

                                    @endphp
                                        @if($remaning>0)
                                            <td>{{ $hours != 0 ?  $hours." hours ". $minutes." min": $minutes." min" }} </td>
                                        @else
                                            <td class="text-danger">{{ $hours != 0 ?  $hours." hour ". $minutes." min": $minutes." min" }}</td>
                                        @endif
                                    @else
                                    <td></td>
                                    @endif
                                    @if($remaning>0)
                                        <td>{{$totalbreak-$tclockout}} min</td>
                                    @elseif($remaning<0)
                                        <td>{{$totalbreak-$tclockout}} min  <span class="badge badge-danger badge-pill float-right">Extra</span></td>
                                    @else
                                        <td>{{0}} min</td>
                                    @endif
                                    @php
                                    $workingtime=0;
                                    
                                    if(isset($attendance->check->attendance_time))
                                    {
                                        if(isset($attendance->check->leave_time)){
                                            $workingtime = (strtotime($attendance->check->leave_time) - strtotime($attendance->check->attendance_time)) ;
                                            $workingtime = ($workingtime - $tclockout) ;
                                        }
                                        else {
                                            $workingtime = abs((strtotime(now()) - strtotime($attendance->attendance_time)));
                                            if($today>$attendance->attendance_date)
                                            {
                                                $workingtime = abs(strtotime($attendance->employee->schedules->first()->time_out) - strtotime($attendance->attendance_time));
                                            }
                                            if($workingtime/3600 > 8)
                                            {
                                             $workingtime = abs(strtotime($attendance->employee->schedules->first()->time_out) - strtotime($attendance->attendance_time));
                                            }
                                        }
                                        $workingtime = ($workingtime - $tclockout*60);
                                    }
                                
                                    @endphp
                                    <td>{{gmdate("H:i:s", $workingtime)}}</td>
                                    @if ($slug == 'admin')
                                    <td class="text-center"><a href="{{route('clockindex', ['id'=>encrypt($attendance->id)])}}"><i class="fa fa-eye"></i></a></a></td>
                                    @else 
                                    <td class="text-center"><a href="{{route('employeeClock', ['id'=>encrypt($attendance->id)])}}"><i class="fa fa-eye"></i></a></a></td>
                                    @endif
                                </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection


@section('script')
<!-- Responsive-table-->
<script src="{{ URL::asset('plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"></script>

@endsection

@section('script')
<script>
    $(function() {
        $('.table-responsive').responsiveTable({
            addDisplayAllBtn: 'btn btn-secondary',
        });
    });
</script>
@endsection