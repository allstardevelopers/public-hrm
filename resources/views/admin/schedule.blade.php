@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
        type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Schedules</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Schedule</a></li>
 

        </ol>
    </div>
@endsection
@section('button')
    <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="mdi mdi-plus mr-2"></i>Add</a>
@endsection

@section('content')
@include('includes.flash')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th data-priority="1">ID</th>
                                        <th data-priority="2">Shift</th>
                                        <th data-priority="3">Time In</th>
                                        <th data-priority="4">Time Out</th>
                                        <th data-priority="4">Break Time</th>
                                        <th data-priority="5">Action</th>
                                     

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schedules as $schedule)
                                        <tr>
                                            <td> {{ $schedule->id }} </td>
                                            <td> {{ $schedule->slug }} </td>
                                            <td> {{ date("g:i A", strtotime($schedule->time_in)) }} </td>
                                            <td> {{ date("g:i A", strtotime($schedule->time_out)) }} </td>
                                            <td><span>On Friday {{ isset($schedule->friday_break)?$schedule->friday_break:'75' }} min</span><br>
                                                <span>Daily {{ isset($schedule->otherday_break)?$schedule->otherday_break:'60' }} min</span><br>
                                                <span>Half Day {{ isset($schedule->halfday_break)?$schedule->halfday_break:'30' }} min</span>  </td>
                                            <td>

                                                <a href="#" data-toggle="modal" data-target="#ajax_update_modal" data-id="{{$schedule->id}}" class="btn btn-success btn-sm edit edit_schedule btn-flat"><i class='fa fa-edit'></i>
                                                    Edit</a>
                                                {{-- <a href="#delete{{ $schedule->slug }}" data-toggle="modal"
                                                    class="btn btn-danger btn-sm delete btn-flat"><i
                                                        class='fa fa-trash'></i> Delete</a> --}}

                                            </td>
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

    {{-- @foreach ($schedules as $schedule)
        @include('includes.edit_delete_schedule')
    @endforeach --}}
    @include('includes.ajax_modal')
    @include('includes.add_schedule')

@endsection
@section('script')
    <!-- Responsive-table-->
    <script src="{{ URL::asset('plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"></script>
    <script>
        $('.edit_schedule').on('click', function() {
            console.log($(this).data('id'));
            let id= $(this).data('id');
            let url= '/schedule_data/'+id;
            console.log(url);
            $.ajax({
                url: url,
                type: "Get",
                dataType: 'json',
                success: function(data) {
                    ajax_modal(data, "" + "{{ route('ajax_modal_contents','update_schedule') }}")
                }
        
            })
        })
    </script>
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
