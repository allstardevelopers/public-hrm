@extends('layouts.master')

@section('css')
<!--Chartist Chart CSS -->
<link rel="stylesheet" href="{{ URL::asset('plugins/chartist/css/chartist.min.css') }}">
@endsection
@section('breadcrumb')
<div class="col-sm-6 text-left">
    <h4 class="page-title">Dashboard</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Welcome to Attendance Management System</li>
    </ol>
</div>
@endsection
@php
$slug = get_user_role(auth()->user()->id);
$currentYear = \Carbon\Carbon::now()->year;
@endphp
@section('content')
@include('includes.firbase_notification')
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <span class="ti-id-badge" style="font-size: 20px"></span>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Total <br> Employees</h5>
                    <h4 class="font-500">{{ $data[0] }} </h4>
                    <img src="{{ asset('assets/images/people.png') }}" alt="" width="76px">
                    {{-- <span class="ti-user" style="font-size: 71px"></span> --}}

                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="/employees" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-alarm-clock" style="font-size: 20px"></i>
                    </div>
                    <h6 class="font-16 text-uppercase mt-0 text-white-50">On Time <br> Percentage</h6>
                    <h4 class="font-500">{{ $data[3] }} %<i class="text-danger ml-2"></i></h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{ $data[3] }}/{{ count($data) }}</span>

                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="/attendance" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>

                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <i class=" ti-check-box " style="font-size: 20px"></i>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">On Time <br> Today</h5>
                    <h4 class="font-500">{{ $data[1] }} <i class=" text-success ml-2"></i></h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{ $data[1] }}/{{ count($data) }}</span>

                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="/attendance" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>

                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-alert" style="font-size: 20px"></i>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Late <br> Today</h5>
                    <h4 class="font-500">{{ $data[2] }}<i class=" text-success ml-2"></i></h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{ $data[2] }}/{{ count($data) }}</span>

                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="/latetime" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0">
                        <table id="{{ $slug === 'admin' ? 'today-Datatable' : 'datatable' }}" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Leave</th>
                                    <th>Last Month</th>
                                    <th>This Month</th>
                                    <th>This Week</th>
                                    <th>Today</th>
                                    <th>Clock Out</th>
                                    <th>Last Active</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($employees as $employee)
                                <tr>
                                    <td><span style="display:none;">{{ strtotime($employee->created_at) }}</span>{{ $employee->id }}
                                    </td>
                                    <td><a href="employee/attendance/report/{{encrypt($employee->id)}}/{{Date('m')}}/{{Date('Y')}}"> <i class="mdi mdi-link-variant"></i> {{ $employee->name }} </a></td>
                                    @php
                                    $leave= get_leave_balance($employee->id);
                                    $probation_comp= strtotime('+'. $employee->probation .'months',strtotime($employee->joining_date));
                                    $today= strtotime(date('d-m-Y'));
                                    @endphp
                                    @if($probation_comp>=$today)
                                    <td class="text-info">In probation</td>
                                    @else
                                    <td class="text-success">Balance: {{$leave['remaning']}} </td>
                                    @endif
                                    <td>{{ get_workingHour_monthly($employee->id, Date('m', strtotime('-1 month'))) }}
                                    </td>
                                    <td>{{ get_workingHour_monthly($employee->id, Date('m')) }}</td>
                                    <td>{{ get_thisweek_hour($employee->id) }}</td>
                                    <td>{{ get_daily_hour($employee->id, date('Y-m-d')) }}</td>
                                    @php
                                    $str= employee_status($employee->id);
                                    $break=get_clockouts($employee->id, date('Y-m-d'));
                                    @endphp
                                    <td><span class="{{($break['break_remainig'] < 0 ? 'text-danger':'text-success' )}}">{{ 'Spent:' . round($break['break_spent'], 0). ' Min' }} <br> {{ ($break['break_remainig'] < 0 ? 'Exc:' : 'Rem:') . round($break['break_remainig'], 0) . ' Min'; }}</span></td>
                                    <td><span class="label-tag">Last Seen:</span> <span class="last-seen">{{ last_seen_emploee($employee->id, date('Y-m-d')) }}</span><br> <span class="label-tag">Today</span> {!!$str!!}</td>
                                    @if ($employee->tracking == 1)
                                    <td class="text-center">
                                        <a class="text-success update_tracking" data-state="1" data-id="{{ $employee->id }}"><i class=" fa fa-toggle-on"></i></a></a>
                                    </td>
                                    @elseif ($employee->tracking == 0)
                                    <td class="text-center">
                                        <a class="text-primary update_tracking" data-state="0" data-id="{{ $employee->id }}"><i class=" fa fa-toggle-off"></i></a></a>
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
    </div> <!-- end col -->
</div>
<script>
    $('.update_tracking').on('click', function() {
        console.log($(this).data('state'));
        let id = $(this).data('id');
        let state = $(this).data('state');
        let title = '';
        if (state == 1) {
            title = 'Do you want to turn off tracking?'
        } else if (state == 0) {
            title = 'Do you want to turn on tracking?'
        }
        let url = '/employeeTracking/' + state + '/' + id;
        console.log(url);
        swalWithBootstrapButtons.fire({
            title: title,
            text: 'Tracking',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'No..',
            confirmButtonText: 'Yes..',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                get_ajax_call(url)
            } else if (result.dismiss === Swal.DismissReason.cancel) {

            }
        })
    })
</script>
<!-- end row -->

<div class="row">
<div class="col-xl-4">
    <div class="card">
        <div class="card-body no-padding" style="padding-top:0px; padding-bottom:0px;">

            <div class="bg-primary p-2 pt-3">
                <h4 class="mt-0 header-title text-white">In Probation</h4>

            </div>
            <div class="p-2 px-4">
                <ul class="list-unstyled rec-acti-list">
                    @foreach ($employees as $employee)
                    @php
                    $probation_comp= strtotime('+'. $employee->probation .'months',strtotime($employee->joining_date));
                    $today= strtotime(date('d-m-Y'));
                    @endphp
                    @if($probation_comp >= $today)
                    <li class=" row rec-acti-list-item">

                        <div class="col-md-1 d-flex align-items-center justify-content-center">
                            <span class="ti-alarm-clock" style="font-size: 30px"></span>
                        </div>
                        <div class="col-md-10">
                            <h6 class="mb-0">Employee Name: <a href="javascript:void(0)" class="text-dark">{{$employee->name}}</a></h6>
                            <p class="text-primary text-muted mb-1">Probation Complete on: <span class="text-danger"> {{date('d-m-Y',$probation_comp)}} </span></p>
                        </div>
                    </li>
                    @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@php
use Carbon\Carbon;
@endphp
<!-- ---------------Upcoming Birthdays--------------- -->
<div class="col-xl-4">
    <div class="card">
        <div class="card-body no-padding" style="padding-top:0px; padding-bottom:0px;">

            <div class="bg-primary p-2 pt-3">

                <h4 class="mt-0 header-title text-white">
                    <i class="fa fa-birthday-cake mx-2 events-icon" aria-hidden="true"></i>Upcoming Birthdays
                </h4>

            </div>
            <div class="p-2 px-4">
                <ul class="list-unstyled rec-acti-list">
                    @if(!empty(getUpcomingBirthdays()))
                    @foreach(getUpcomingBirthdays() as $birthdays)

                    <li class=" row rec-acti-list-item">

                        <div class="col-md-3 d-flex align-items-center justify-content-center">
                            @if($birthdays->profile_pic !=null)
                            <img src="{{ URL::asset('storage/assets/profile_pics/'. $birthdays->profile_pic) }}" class="mx-2 rounded-circle text-center" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;" alt="" />
                            @else
                            <img src="assets/images/profile1.png" class="mx-2 rounded-circle text-center" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;" alt="" />
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h6 class="mb-0"><a href="#" class="text-dark">{{$birthdays->name}}</a></h6>
                            <p class="text-primary text-muted mb-1">Date: <span class="text-danger">{{formatBirthday($birthdays->dob)}}</span></p>
                        </div>
                    </li>
                    @endforeach

                    @else
                    <li class=" row rec-acti-list-item">
                        <div class="col-md-12">
                            <p class="text-primary text-muted mb-1">No Birthdays In this Week</p>
                        </div>
                    </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ------------------------------------------------- -->

<!-- ---------------Upcoming Events--------------- -->
<div class="col-xl-4">
    <div class="card">
        <div class="card-body no-padding" style="padding-top:0px; padding-bottom:0px;">

            <div class="bg-blink p-2 pt-3">

                <h4 class="mt-0 header-title text-white">
                    <i class="fa fa-calendar mx-2" aria-hidden="true"></i></i>Upcoming Events
                </h4>

            </div>
            <div class="p-2 px-4">
                <ul class="list-unstyled rec-acti-list">

                    <div class="wrap">

                        @if(!empty(getUpcomingEvents()))
                        <div class="slider">
                            <div class="slider__row" id="upcoming_events">

                                <!-- upcoming_events -->

                                @foreach (getUpcomingEvents() as $upcoming_event)
                                <div class="row__item">
                                    <div class="event-card row rec-acti-list-item">
                                        <div class="col-md-3 d-flex align-items-center justify-content-center">
                                            <i class="fa fa-calendar" style="font-size: 50px;" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="event-title">{{$upcoming_event->title}}</div>
                                            <div class="event-date text-primary text-muted">
                                                Date: <span class="text-danger">{{Carbon::parse($upcoming_event->event_held_on)->format('l, d F')}}</span>
                                            </div>
                                            @if($upcoming_event->event_type)
                                            <span class="event-badge event-badge-closed">Office Will remain Close</span>
                                            @else
                                            <span class="event-badge event-badge-open">Office Will remain Open</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                        @else
                        <div class="row__item">
                            <li class=" row rec-acti-list-item text-center">
                                <div class="col-md-12">
                                    <p class="text-primary text-muted mb-1">No event available</p>
                                </div>
                            </li>
                        </div>
                        @endif
                    </div>

                </ul>
            </div>
        </div>
    </div>
</div>
</div>
<!-- end row -->


<!-- end row -->
@endsection

@section('script')
<!--Chartist Chart-->
<script src="{{ URL::asset('plugins/chartist/js/chartist.min.js') }}"></script>
<script src="{{ URL::asset('plugins/chartist/js/chartist-plugin-tooltip.min.js') }}"></script>
<!-- peity JS -->
<script src="{{ URL::asset('plugins/peity-chart/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('assets/pages/dashboard.js') }}"></script>


<!-- --------------Upcoming Events------------- -->
<script>
    var toTop = 0;

    function autoPlay() {
        timer = setTimeout(function() {
            var row = document.getElementById('upcoming_events');
            toTop = toTop - 128;
            if (toTop < -250) {
                toTop = 0;
                clearTimeout(timer);
                row.onmouseover = clearTimeout(timer);
            }
            row.style.top = toTop + 'px';
            autoPlay();
        }, 3000);
    }
    autoPlay();
</script>
@endsection