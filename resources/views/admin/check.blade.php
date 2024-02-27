@extends('layouts.master')

@section('css')
<!-- Table css -->
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
<style>
    .days_name {
        color: #f8bb86;
    }

    .hr-row {
        margin-top: 0rem;
        margin-bottom: 0.2rem;
        border-top: 2px solid #0c2f52;
    }

    .months {
        font-size: x-small;
    }
</style>
@endsection

@php
$slug = get_user_role(auth()->user()->id);
$i=1;
$currentYear = \Carbon\Carbon::now()->year;
$startYear = 2023;

@endphp

@section('content')


<div class="card">

    <div class="card-body">

        @if($slug=='admin')
        <form method="POST" id="sort-attendance" action="{{ route('check') }}">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{-- <label for="employee_name" class="col-sm-6 control-label">Employee Name</label> --}}
                        <select class="form-control" id="emp_id" name="emp_id" required>
                            <option value="" selected>- Select Employee -</option>
                            @foreach ($all_employees as $employee)
                            @if(isset($emp_id))
                            <option value="{{ $employee->id }}" {{ $emp_id == $employee->id ? "selected" : "" }}>{{ $employee->name }}</option>
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
                            @for ($i = 1; $i <= 12; $i++) @if(isset($month_id)) <option value="{{$i}}" {{ $month_id == $i ? "selected" : "" }}>{{DateTime::createFromFormat('!m', $i)->format('F');}}</option>
                                @else
                                <option value="{{$i}}">{{DateTime::createFromFormat('!m', $i)->format('F');}}</option>
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
                            <option value="{{ $year }}" {{ $syear == $year ? 'selected' : '' }}>{{ $year }}
                            </option>
                            @else
                            <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>{{ $year }}
                            </option>
                            @endif
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-2 align-bottom">
                    <button type="submit" class="btn align-bottom btn-primary waves-effect waves-light">
                        Search
                    </button>

                    <a href="/check" class="btn align-bottom btn-primary waves-effect waves-light">
                        Reset
                    </a>
                </div>

            </div>
        </form>
        @endif


        <h5 class="card-header" style=" font-size: 24px; color: #0c2f52; font-family: Calibri; "> {{date('F Y')}}</h5>
        <div class="table-responsive">
            <table class="table table-responsive table-bordered table-sm">
                <thead class="bg-primary text-white">
                    <tr>

                        <th>Employee Name</th>
                        <th>Employee Position</th>
                        <th>Employee ID</th>
                        @php

                        $today = \Carbon\Carbon::parse($today);
                        $dates = [];
                        $dayname=[];

                        for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
                            $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
                            $dayname[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('l');
                            }

                            @endphp
                            @foreach ($dates as $date)
                            <th style="min-width: 60px; vertical-align: middle;">
                                <span class="months">{{ date('d F', strtotime($date)) }}</span>
                                <hr class="hr-row">
                                <span class="days_name">{{ \Carbon\Carbon::createFromDate($date)->format('D') }}</span>
                            </th>
                            @endforeach


                    </tr>
                </thead>

                <tbody>


                    <form action="{{ route('check_store') }}" method="post">
                        @csrf
                        @foreach ($employees as $employee)

                        <input type="hidden" name="emp_id" value="{{ $employee->id }}">

                        <tr>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->id }}</td>

                            @for ($i = 1; $i < $today->daysInMonth + 1; ++$i)
                                @php

                                $date_picker = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');

                                $check_attd = \App\Models\Attendance::query()
                                ->where('emp_id', $employee->id)
                                ->where('attendance_date', $date_picker)
                                ->with('check')
                                ->first();

                                $check_leave = \App\Models\Leave::query()
                                ->where('emp_id', $employee->id)
                                ->where('leave_date', $date_picker)
                                ->where('type', 2)
                                ->where('status', '!=', 0)
                                ->where('state', '!=', 0)
                                ->first();
                                $check_absent = \App\Models\Leave::query()
                                ->where('emp_id', $employee->id)
                                ->where('leave_date', $date_picker)
                                ->where('type', 3)
                                ->where('state', 0)
                                ->first();
                                $on_leave = \App\Models\Leave::query()
                                ->where('emp_id', $employee->id)
                                ->where('leave_date', $date_picker)
                                ->where('state', 1)
                                ->first();

                                $half_leave = \App\Models\Leave::query()
                                ->where('emp_id', $employee->id)
                                ->where('leave_date', $date_picker)
                                ->where('type', 1)
                                ->first();
                                //echo $date_picker;
                                $event_date = \Carbon\Carbon::parse($date_picker)->toDateString();
                                $is_event = checkEvents($event_date, 1);
                               // echo "<pre>";
                                 //   print_r($is_event);
                                @endphp
                                <td>

                                    @if($dayname[$i-1]=='Sunday' || $dayname[$i-1]=='Saturday')
                                    <span class="text-danger" style="font-weight: 600;">OFF</span>
                                    @elseif(isset($check_leave))
                                    <span class="text-danger">On Leave</span>
                                    @elseif(isset($half_leave))
                                    <span class="text-info">Half Leave</span>
                                    @elseif(isset($check_absent))
                                    <span class="text-danger">Absent</span>
                                    @elseif(isset($on_leave))
                                    @if($is_event)
                                    <span class="text-info">
                                        Public Holiday
                                    </span>
                                    @else
                                    <span class="text-danger">
                                        On Leave
                                    </span>
                                    @endif
                                    @else
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input checked-box" id="check_box" name="attd[{{ $date_picker }}][{{ $employee->id }}]" type="checkbox" @if (isset($check_attd)) checked @endif id="inlineCheckbox1" value="1" onclick="return false;"> in

                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input unchecked-box" id="check_box" name="leave[{{ $date_picker }}][{{ $employee->id }}]]" type="checkbox" @if (isset($check_attd->check->leave_time)) checked @endif id="inlineCheckbox2" value="1" onclick="return false;"> out
                                    </div>
                                    @endif

                                </td>

                                @endfor
                        </tr>
                        @endforeach

                    </form>


                </tbody>


            </table>
        </div>
    </div>
</div>
@endsection