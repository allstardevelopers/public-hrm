@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css"
        media="screen">
@endsection
@php
    $slug = get_user_role(auth()->user()->id);
@endphp
@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Late Time</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Late Time</a></li>


        </ol>
    </div>
@endsection

@section('button')
    @if($slug=='admin')
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

                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="{{ $slug === "admin" ? "datatable-buttons" : "datatable" }}" class="table table-striped table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th data-priority="1" >Date</th>
                                        <th data-priority="2">Employee ID</th>
                                        <th data-priority="3">Name</th>
                                        <th data-priority="3">Check in</th>
                                        <th data-priority="4">Late Time Duration</th>
                                        <th data-priority="6">Schedule In</th>
                                        <th data-priority="7">Schedule Out</th>
                                        @if ($slug == 'admin')
                                            <th data-priority="8">Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latetimes as $latetime)
                                        <tr>
                                            <td><span style="display:none;">{{strtotime($latetime->created_at)}}</span>{{ date('d-M-Y', strtotime($latetime->latetime_date))}}</td>
                                            <td>{{ $latetime->emp_id }}</td>
                                            <td>{{ $latetime->employee->name }}</td>
                                            <td>{{ $latetime->attendance->attendance_time}}
                                                @if ($latetime->attendance->status == 1)
                                                <span class="badge badge-success badge-pill float-right">On Time</span>
                                                @elseif ($latetime->attendance->status == 2)
                                                    <span class="badge badge-warning badge-pill float-right">Half Day</span>
                                                @else
                                                <span class="badge badge-danger badge-pill float-right">Late</span>
                                                @endif
                                            </td>
                                            @php
                                                $duration= strtotime($latetime->attendance->attendance_time)-strtotime($latetime->employee->schedules->first()->time_in);
                                            @endphp
                                            @if($duration>0)
                                            <td>{{ gmdate("H:i:s", $duration)}}</td>
                                            @else
                                            <td>{{0}} 
                                                <span class="badge badge-success badge-pill float-right">Updated</span>
                                            </td>
                                            @endif
                                            <td>{{ $latetime->employee->schedules->first()->time_in }} </td>
                                            <td>{{ $latetime->employee->schedules->first()->time_out }}</td>
                                            @if ($slug == 'admin')
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#ajax_update_modal" data-id="{{ $latetime->id }}" class="btn btn-success btn-sm update_checkIn btn-flat"><i class='fa fa-edit mx-2'></i>Update</a>
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
    </div> <!-- end row -->
    <script>
        $('.update_checkIn').on('click', function() {
            console.log($(this).data('id'));
            let id= $(this).data('id');
            let url= '/latetime_data/'+id;
            console.log(url);
            $.ajax({
                url: url,
                type: "Get",
                dataType: 'json',
                success: function(data) {
                    ajax_modal(data, "" + "{{ route('ajax_modal_content') }}")
                }

            })
        })
    </script>
    @include('includes.ajax_modal')
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
{{-- script --}}
