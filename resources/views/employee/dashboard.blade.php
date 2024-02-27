@extends('layouts.master')

@section('css')
<!--Chartist Chart CSS -->
<link rel="stylesheet" href="{{ URL::asset('plugins/chartist/css/chartist.min.css') }}">
@endsection
@php
$slug = get_user_role(auth()->user()->id);

@endphp
@section('breadcrumb')
<div class="col-sm-6 text-left">
    <h4 class="page-title">Dashboard</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Welcome To All Star Technologies
            <!-- @can('permission', 'delete')
            <button>Delete</button>
            @endcan -->
        </li>
    </ol>

</div>
<div class="col-sm-6 text-right timeSlide">
    {{-- <a href="{{route('mark-attendance')}}" class="btn btn-success btn-sm btn-flat">Clock OUT</a>
    <a href="#checkout" class="btn btn-success btn-sm btn-flat checkOut-btn">Checkout</a> --}}
    <div class="clockin" style="display:none">
        <p id="playTime"> {{$tworktime}}</a></p>
    </div>
    <div class="clockout" style="display:none">
        <p id="stopTime"> {{ $tworktime }} <a href="">
            <a href="/mark-attendance"><i class="fa fa-play"></i></a></p>
    </div>
    <div class="text-danger" style="margin-top: -12px;">{{$messageStr}}</div>
</div>
@endsection


<!-- ---------------------Birthday Section----------------------- -->

<!-- <div class="container">
<div class="row" >
<div class="col-md-6">
 <img src="{{ asset('assets/images/video/birthday-cake-g.gif') }}" alt="user" class="mr-1">

 </div>
</div>   
</div> -->


<!-- ----------------------End Birthday Section---------------------- -->

@section('content')
@php

$emp_id = get_emp_id(auth()->user()->id);
$employee = employee_details($emp_id);
$employees_with_birthdayToday = getEmployeesWithBirthdayToday();
$isBirthdayToday = isItEmployeeBirthday($employee);
@endphp
@if($isBirthdayToday)
<div class="birthday-banner mb-4" id="firework">

    <div class="pyro">
        <div class="before"></div>
        <div class="after"></div>
    </div>

    <div class="row " style="padding: 20px 0px;">

        <div class="col-md-2 d-flex align-items-center justify-content-center">
            @if($employee->profile_pic !=null)

            <img src="{{ URL::asset('storage/assets/profile_pics/'. $employee->profile_pic) }}" class="mx-2 rounded-circle text-center" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;" alt="" />
            @else
            <img src="assets/images/profile1.png" class="mx-2 rounded-circle text-center mr-1" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;" alt="" />
            @endif
        </div>

        <div class="col-md-7">
            <span style="display: flex; justify-content: center;">
                <h2 class="birthday-head">Happy Birthday!</h2>

                <div class="col-md-2 d-flex align-items-center justify-content-center">
                    <img src="{{ asset('assets/images/video/birthday-cake-g.gif') }}" alt="user" class="mr-1" style="width: inherit !important;">
                </div>
            </span>
            <h3 class="text-white" style=" color:#fea702 !important;">{{$employee->name}}</h3>
            <p class="birthday-wish-text"> "May your birthday be full of magical and special moments
                to remember for a lifelong. May you have a lovely and cool birthday night Have a gorgeous birthday!"</p>

        </div>


        <div class="col-md-3 d-flex align-items-center justify-content-center">
            <img src="{{ asset('assets/images/air-balloons.png') }}" alt="user" class="mr-1 stick" style="width:250px;" id="balloons">

        </div>
    </div>
</div>
@else
@if(count($employees_with_birthdayToday)>0)
<div class="birthday-slider">
    @foreach($employees_with_birthdayToday as $birthdayGuy)
    <div class="birthday-banner mb-4 slide" id="firework">

        <div class="pyro">
            <div class="before"></div>
            <div class="after"></div>
        </div>

        <div class="row " style="padding: 20px 0px;">

            <div class="col-md-2 d-flex align-items-center justify-content-center">
                @if($birthdayGuy->profile_pic !=null)

                <img src="{{ URL::asset('storage/assets/profile_pics/'. $birthdayGuy->profile_pic) }}" class="mx-2 rounded-circle text-center" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;" alt="" />
                @else
                <img src="assets/images/profile1.png" class="mx-2 rounded-circle text-center mr-1" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;" alt="" />
                @endif
            </div>

            <div class="col-md-7">
                <span style="display: flex; justify-content: center;">
                    <h2 class="birthday-head">Happy Birthday!</h2>

                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                        <img src="{{ asset('assets/images/video/birthday-cake-g.gif') }}" alt="user" class="mr-1" style="width: inherit !important;">
                    </div>
                </span>
                <h3 class="text-white" style=" color:#fea702 !important;">{{$birthdayGuy->name}}</h3>
                <p class="birthday-wish-text"> "May your special day be a joyful reminder of the amazing person you are. Wishing you a year filled with accomplishments,
                    laughter, and cherished moments. Happy birthday to a valuable member of our team!"</p>

            </div>


            <div class="col-md-3 d-flex align-items-center justify-content-center">
                <img src="{{ asset('assets/images/air-balloons.png') }}" alt="user" class="mr-1 stick" style="width:250px;" id="balloons">

            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endif
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <span class="ti-id-badge" style="font-size: 20px"></span>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Total <br> Work Days</h5>
                    <h4 class="font-500">{{ $data[0] }} </h4>
                    <span class="ti-user" style="font-size: 71px"></span>

                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
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
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
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
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">On Time <br> This Month</h5>
                    <h4 class="font-500">{{ $data[1] }} <i class=" text-success ml-2"></i></h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{ $data[1] }}/{{ count($data) }}</span>

                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
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
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Late <br> This Month</h5>
                    <h4 class="font-500">{{ $data[2] }}<i class=" text-success ml-2"></i></h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{ $data[2] }}/{{ count($data) }}</span>

                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>

                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
@if($slug=='employee')
@include('includes.employee_leave')
@endif
<?php
$emp_id = get_emp_id(auth()->user()->id);
$employee = employee_details($emp_id);
$isBirthdayToday = isItEmployeeBirthday($employee);
?>
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title mb-5">Monthly Report</h4>
                <div class="row">
                    <div class="col-lg-7">
                        <div>
                            <div id="chart-with-area" class="ct-chart earning ct-golden-section"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="text-center">
                                    <p class="text-muted mb-4">This month</p>
                                    <h4>{{get_workingHour_monthly(get_emp_id(auth()->user()->id),Date('m'))}}</h4>
                                    <p class="text-muted mb-5">
                                    </p>
                                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{ $data[3] }}/{{ count($data) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <p class="text-muted mb-4">Last month</p>
                                    <h4>{{get_workingHour_monthly(get_emp_id(auth()->user()->id),Date('m', strtotime("-1 month")))}}</h4>
                                    <p class="text-muted mb-5">
                                    </p>
                                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">3/5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <!-- ---------------Upcoming Events--------------- -->
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <h4 class="mt-0 header-title mb-4">Working Time</h4>
                                </div>
                                <div class="wid-peity mb-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div>
                                                <p class="text-muted">Today</p>
                                                <h5 class="mb-4">{{get_daily_hour(get_emp_id(auth()->user()->id), date('Y-m-d'))}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <span class="peity-line" data-width="100%" data-peity='{ "fill": ["rgba(2, 164, 153,0.3)"],"stroke": ["rgba(2, 164, 153,0.8)"]}' data-height="60">6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wid-peity mb-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div>
                                                <p class="text-muted">This Week</p>
                                                <h5 class="mb-4">{{get_thisweek_hour(get_emp_id(auth()->user()->id))}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <span class="peity-line" data-width="100%" data-peity='{ "fill": ["rgba(2, 164, 153,0.3)"],"stroke": ["rgba(2, 164, 153,0.8)"]}' data-height="60">6,2,8,4,-3,8,1,-3,6,-5,9,2,-8,1,4,8,9,8,2,1</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div>
                                                <p class="text-muted">This Month</p>
                                                <h5>{{get_workingHour_monthly(get_emp_id(auth()->user()->id),Date('m'))}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <span class="peity-line" data-width="100%" data-peity='{ "fill": ["rgba(2, 164, 153,0.3)"],"stroke": ["rgba(2, 164, 153,0.8)"]}' data-height="60">6,2,8,4,3,8,1,3,6,5,9,2,8,1,4,8,9,8,2,1</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- end card -->

    @php
    use Carbon\Carbon;
    @endphp
    <div class="col-xl-4">


        <div class="card">
            <div class="card-body no-padding" style="padding-top:0px; padding-bottom:0px;">

                <div class="rounded p-2 pt-3 bg-blink">

                    <h4 class="mt-0 header-title text-white">
                        <i class="fa fa-calendar mx-2 events-icon" aria-hidden="true"></i></i>Upcoming Events
                    </h4>

                </div>
                <div class="p-2 px-4">
                    <ul class="list-unstyled rec-acti-list">

                        <div class="wrap h-auto">
                            @if(getUpcomingEvents())
                            <div class="slider h-auto">
                                <div class="slider__row" id="upcoming_events">
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

                    <h4 class="mt-0 header-title up-birthday-head">
                        <i class="fa fa-birthday-cake mx-2" aria-hidden="true"></i>Upcoming Birthdays
                    </h4>
                    </h4>

                    <ul class="list-unstyled rec-acti-list">

                        <div class="wrap h-auto">
                            @if(!empty(getUpcomingBirthdays()))
                            <div class="slider h-auto">
                                <div class="slider__row" id="upcoming_events">
                                    @foreach(getUpcomingBirthdays() as $birthdays)
                                    <div class="row__item">
                                        <li class=" row rec-acti-list-item">

                                            <div class="col-md-3 d-flex align-items-center justify-content-center">
                                                @if($birthdays->profile_pic !=null)

                                                <img src="{{ URL::asset('storage/assets/profile_pics/'. $birthdays->profile_pic) }}" class="mx-2 rounded-circle text-center" style="width: 60px; height: 60px; object-fit: cover;" alt="" />
                                                @else
                                                <img src="assets/images/profile1.png" class="mx-2 rounded-circle text-center" style="width: 60px; height: 60px; object-fit: cover;" alt="" />
                                                @endif
                                            </div>
                                            <div class="col-md-9">
                                                <h6 class="mb-0"><a href="#" class="text-dark">{{$birthdays->name}}</a></h6>
                                                <p class="text-primary text-muted mb-1">Date: <span class="text-danger">
                                                        {{formatBirthday($birthdays->dob)}}</span></p>
                                            </div>
                                        </li>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="row__item">
                                <li class=" row rec-acti-list-item text-center">
                                    <div class="col-md-12">
                                        <p class="text-primary text-muted mb-1">No Birthdays In this Week</p>
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


@can('permission', 'manage_team')
@include('includes.myteam')
@endcan
<a class="btn btn-primary btn-rounded dropdown-toggle" href="/download-zip" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" download>
    <i class="mdi mdi-briefcase-download"></i> Download Application
</a>
{{-- <a href="/download-zip" download> donload</a> --}}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('.checkOut-btn').on('click', function() {
        var token = $('meta[name="csrf-token"]').attr('content');
        console.log(token);
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to check out",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Check out!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('check-out') }}",
                    method: "GET",
                    data: 1,
                    success: function(resp) {
                        if (resp == 'success') {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Check out',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }

                    }
                });

            }
        })
    })

    function Timer(duration, display) {
        var timer = duration,
            hours, mins, seconds;
        setInterval(function() {
            hours = parseInt((timer / 3600) % 24, 10)
            minutes = parseInt((timer / 60) % 60, 10)
            seconds = parseInt(timer % 60, 10);
            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
            // display.text(hours +":"+minutes + ":" + seconds);
            document.getElementById("playTime").innerHTML = hours + ':' + minutes + ':' + seconds;
            ++timer;
        }, 1000);
    }
    jQuery(function($) {
        var twentyFourHours = {{$timestamp}};
        // ------Clock Status, 0 clockout, 1 clock in
        var clockoutStatus = {{$clockoutStatus}};
        if (clockoutStatus == 1) {
            $('.clockin').css('display', 'block')
            $('.clockout').css('display', 'none')
            Timer(twentyFourHours);
        } else {
            $('.clockout').css('display', 'block')
            $('.clockin').css('display', 'none')

        }
    });
</script>

@endsection