@extends('layouts.master')

@section('css')
<!-- Table css -->
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Report</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0);">Report</a></li>


    </ol>
</div>
@endsection
@php
$slug = get_user_role(auth()->user()->id);
$today = date('Y-m-d');
$currentYear = \Carbon\Carbon::now()->year;
$startYear = 2023;
$i = 1;

@endphp
@section('button')
@if ($slug == 'admin')
<a href="/attendance/today" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-calendar-check mr-2"></i>Today
    Attendance</a>
@if (isset($emp_id))
<a href="/attendance" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-calendar-multiple mr-2"></i>Attendance</a>
@endif
@endif
@endsection

@section('content')
@include('includes.flash')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @if($slug=='employee'):
                        <input type="hidden" name="emp_id" id="emp_id" value="{{ encrypt($emp_id)}}">
                    @else 
                    <div class="col-md-4">
                        <div class="form-group">
                            {{-- <label for="employee_name" class="col-sm-6 control-label">Employee Name</label> --}}
                            <select class="form-control" id="emp_id" name="emp_id" required>
                                <option value="" selected>- Select Employee -</option>
                                @foreach ($employees as $employee)
                                @if (isset($emp_id))
                                <option value="{{ encrypt($employee->id)}}" {{ $emp_id == $employee->id ? 'selected' : '' }}>{{ $employee->name }}
                                </option>
                                @else
                                <option value="{{ encrypt($employee->id) }}">{{ $employee->name }}</option>
                                @endif
                                @endforeach
                            </select>
                            <p id="emp_id_error" class="error"></p>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-2">
                        <div class="form-group">
                            {{-- <label for="employee_name" class="col-sm-6 control-label">Month</label> --}}
                            <select class="form-control" id="month_id" name="month_id" required>
                                <option value="" selected>- Select Month -</option>
                                @for ($i = 1; $i <= 12; $i++) @if (isset($month_id)) <option value="{{ $i }}" {{ $month_id == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                    @else
                                    <option value="{{ $i }}">
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                    @endif
                                    @endfor
                            </select>
                            <p id="month_id_error" class="error"></p>

                        </div>
                    </div>
                    @if($slug=='employee'):
                    <input type="hidden" name="year" id="year" value="{{ $currentYear}}">
                    @else 
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
                    @endif
                    <div class="col-md-2 align-bottom">
                        {{-- <label hidden>Month</label> --}}
                        <button onclick="searchResult('{{$slug}}')" class="btn align-bottom btn-primary waves-effect waves-light">
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
<div class="row">
    <div class="col-4">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    @php
                    $profilePic = null;
                    $display = 'display: none;';
                    $employee = employee_details($emp_id);
                    if($employee->profile_pic !=null){
                    $profilePic = $employee->profile_pic;
                    $display = '';
                    }

                    @endphp
                    <div class="col-md-4" id="profilePicDiv" style="cursor: pointer;">
                        <form id="profilePicForm" action="/upload-profile-pic" method="post" enctype="multipart/form-data">
                            <!-- Clicking on this div will trigger the file input element -->
                            @csrf
                            @if($profilePic == null)
                            <span class="ti-face-smile hide_upload" style="font-size: 80px"></span>
                            @else
                            <img src="{{ asset('storage/assets/profile_pics/' . $profilePic) }}" alt="Profile Picture Preview" id="profilePicPreview" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                            @endif
                            <input type="file" name="profile_pic" id="profilePicInput" style="display: none;">

                            <input type="hidden" name="id" value="{{$emp_id}}" />
                        </form>
                    </div>
                    <div class="col-md-8 px-3">
                        <h3 class="text-primary">{{ App\Models\Employee::find($emp_id)->name }}</h3>
                        <p>{{ App\Models\Employee::find($emp_id)->position }}</p>
                    </div>
                    <span style="color: red;" id="file_error_msg"></span>
                </div>

                <!-- <button type="submit" onclick="uploadProfilePic()">Upload</button> -->


                <!-- <form id="profilePicForm" action="/upload-profile-pic" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="profile_pic" id="profilePicInput">
                    <img src="#" alt="Profile Picture Preview" id="profilePicPreview" style="display: none; max-width: 200px;">
                    <button type="submit">Upload</button>
                </form> -->
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @include('includes.employee_report_card')
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <span class="ti-write" style="font-size: 11px"></span> {{ App\Models\Employee::find($emp_id)->name }}
                Attendance Sheet
            </div>
            <div class="card-body">
                @include('includereport.attendance_table')
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <span class="ti-write" style="font-size: 11px"></span> {{ App\Models\Employee::find($emp_id)->name }}
                Leave Sheet
            </div>
            <div class="card-body">
                @include('includereport.leave_table')
            </div>
        </div>
    </div>
</div>
<script>
    function searchResult(slug) {
        let emp_id = $('#emp_id').val();
        let month_id = $('#month_id').val();
        let year = $('#year').val();
       
        if (emp_id == '') {
            $('#emp_id_error').text('Please select employee');
        } else {
            $('#emp_id_error').text('');
        }
        if (month_id == '') {
            $('#month_id_error').text('Please select month');
        } else {
            $('#month_id_error').text('');
        }
        if(slug=='employee'){
            path= "{{ route('employee.report', ['id' => ':emp_id', 'm' => ':month_id', 'y' => ':year']) }}"
        }
        else {
            path= "{{ route('employee.attendance.report', ['id' => ':emp_id', 'm' => ':month_id', 'y' => ':year']) }}"
        }

        if (emp_id != '', month_id != '') {
            let url =path;
            url = url.replace(':emp_id', emp_id);
            url = url.replace(':month_id', month_id);
            url = url.replace(':year', year);
            window.location.href = url;
        }
    }

    function updateEmpSetting(url, emp_id, columnName) {
        let newval = $('input[name="' + columnName + '"]').val()
        if (columnName == 'clockOut_time') {
            newval = newval * 60;
        }
        var formData = new FormData();
        let csrf_token = '{{ csrf_token() }}';
        formData.append('emp_id', emp_id);
        formData.append('setting_name', columnName);
        formData.append('setting_value', newval);
        formData.append('_token', csrf_token);
        post_ajax_call(url, formData)
    }
    document.getElementById("profilePicDiv").addEventListener("click", function() {
        document.getElementById("profilePicInput").click();
    });

    document.getElementById("profilePicInput").addEventListener("change", function(event) {
        const file = event.target.files[0];
        const profilePicPreview = document.getElementById("profilePicPreview");

        if (file) {
            // Check if the file is an image (e.g., jpg, jpeg, png, gif)
            if (!file.type.startsWith("image/")) {
                $("#file_error_msg").text("Please select an image file (jpg, jpeg, png, gif");
                return; // Stop further execution
            }

            const reader = new FileReader();

            reader.addEventListener("load", function(event) {
                profilePicPreview.src = event.target.result;
                profilePicPreview.style.display = "flex";
            });

            $("#file_error_msg").text("");
            $(".hide_upload").hide();
            uploadProfilePic();
            reader.readAsDataURL(file);
        } else {
            profilePicPreview.src = "#";
            profilePicPreview.style.display = "none";
        }
    });
</script>
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