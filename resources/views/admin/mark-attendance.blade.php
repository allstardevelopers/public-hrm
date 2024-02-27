@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css"
        media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Attendance</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Attendance</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Mark Attendence</a></li>



        </ol>
    </div>
@endsection
@section('button')
    {{-- <a href="attendance/assign" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add New</a> --}}
@endsection

@section('content')
    @include('includes.flash')

    <style>
        textarea {
        width: 100%;
        height: 100px;
        background: none repeat scroll 0 0 rgba(0, 0, 0, 0.07);
        border-color: -moz-use-text-color #FFFFFF #FFFFFF -moz-use-text-color;
        border-image: none;
        border-radius: 6px 6px 6px 6px;
        border-style: none solid solid none;
        border-width: medium 1px 1px medium;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.12) inset;
        color: #555555;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 1em;
        line-height: 1.4em;
        padding: 5px 8px;
        transition: background-color 0.2s ease 0s;
    }


    textarea:focus {
        background: none repeat scroll 0 0 #FFFFFF;
        outline-width: 0;
    }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">

                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th data-priority="1">Date</th>
                                        <th data-priority="2">Employee ID</th>
                                        <th data-priority="2">Employee Name</th>
                                        <th data-priority="4">Attendance</th>
                                        <th data-priority="6">Clock In</th>
                                        <th data-priority="7">Clock Out</th>
                                        <th data-priority="7">Total Clock out</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <pre>
                                        @php
                                            $clockin= $attendance->attendance_time;
                                        @endphp
                                    @foreach ($clockouts as $clockout)
                                    <tr>
                                        <td>{{ date('d-M-Y', strtotime($clockout->attendance->attendance_date)) }}</td>
                                        <td>{{ $clockout->emp_id }}</td>
                                        <td>{{ $clockout->employee->where('id',$clockout->emp_id)->first()->name}}</td>
                                        <td>{{ $clockout->attendance->attendance_time }}
                                            @if ($clockout->attendance->status == 1)
                                                <span class="badge badge-primary badge-pill float-right">On Time</span>
                                            @elseif ($attendance->status == 2)
                                                <span class="badge badge-warning badge-pill float-right">Half Day</span>
                                            @else
                                                <span class="badge badge-danger badge-pill float-right">Late</span>
                                            @endif
                                        </td>
    
                                        <td>{{ date('H:i:s', strtotime($clockin))}} </td>
                                        @if  (isset($clockout->clock_out))
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
                                         @if  (isset($clockout->clock_in))
                                         @php
                                             $cout = strtotime($clockout->clock_out);
                                             $cin = strtotime($clockout->clock_in);
                                             $diff = round(($cin - $cout) / 60, 0);
                                            //  $diff = $cin->diffminutes($cout);

                                         @endphp
                                         <td class="{{ $clockout->status == 0 ? 'text-danger' : 'text-success' }}">{{ $diff }} Minutes
                                            <span class="badge badge-success badge-pill float-right"><a class="text-light view_clockout_reason" data-id="{{ $clockout->id }}" data-reason="Reason: {{ $clockout->reason }}" data-remarks="{{isset($clockout->remarks)?'Remarks:'.$clockout->remarks:'' }}" ><i class="fa fa-eye"></i></a></span> 
                                        </td>
                                         @else
                                         <td></td>
                                          @endif
                                    </tr>
                                    @php
                                        $clockin= $clockout->clock_in;
                                    @endphp
                                    
                                    @endforeach
    
    
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
        <div class="col-12">
            @if(!isset($attendance->check->leave_time))
            @if($attendance->state == 1)
            <div class="well" style="min-height: 70px !important;">
               <div class="col-md-4">
                 <h4>Clock-out Reason </h4>
                </div>
            <form id="clock_out" style="display:flex; align-items: baseline;">
                @csrf
                <div class="col-md-4">
                    <select onchange="setReason()" id="remarks" name="remarks" class="form-control">
                        <option value="short break"> General Break </option>
                        <option value="official"> Official Break </option>&gt;
                        <option value="shift over"> Shift Over </option>
                    </select>
                    <div style="display:none" id="reson-div">
                        <label for="reason">Type Reason</label>
                     <textarea class="with-border" placeholder="Type Reason" name="reason" id="reason" cols="7"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-flat">Clock Out</button>
            </form>
            </div>
            @else 
                <div class="well" style="min-height: 70px !important;">
                    <div class="col-md-4">
                      <h4>Clock In</h4>
                      <p>You clock out please click on clock in</p>
                     </div>
                 <form id="clock_in">
                  @csrf
                  <button type="submit" class="btn btn-primary btn-flat">Clock In</button>
                 </form>
            @endif
            @else 
             <div class="well" style="min-height: 70px !important;">
                <div class="col-md-4">
                    <h4>Shift Over</h4>
                    <p>You already Check Out</p>
                </div>
            
            @endif
            
            
        </div>
            <!-- End New Row  -->
        
    </div>
    </div> <!-- end row -->
    <script>
        $('#clock_out').submit(function(event) {
            console.log('check form submit')
            event.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: "{{ route('clock-out') }}",
                method: "POST",
                data: formData,
                success: function(resp) {
                    if(resp=='success')
                    {
                        console.log('function response')
                        Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Clock out',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            setTimeout(function(){// wait for 5 secs(2)
                               location.reload(); // then reload the page.(3)
                             }, 500)
                    }

                }
            });
        });
        $('#clock_in').submit(function(event) {
            console.log('check form submit')
            event.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url: "{{ route('clock-in') }}",
                method: "POST",
                data: formData,
                success: function(resp) {
                    if(resp=='success')
                    {
                           Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Clock in',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            setTimeout(function(){// wait for 5 secs(2)
                               location.reload(); // then reload the page.(3)
                             }, 500)
                    }
                }
            });
        });

        function setReason(){
            console.log('Mark Value')
            if ($('#remarks').val()=='official')
            {
                $('#reson-div').css('display', 'block');
                $('#reason').prop('required',true)

            }
            else {
                $('#reson-div').css('display', 'none')
                $('#reason').prop('required',false)
            }
        }
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
                addDisplayAllBtn: 'btn btn-secondary'
            });
        });
    </script>
@endsection
