<style>
    textarea {
        width: 100%;
        height: 100px;
        background: none repeat scroll 0 0 #FFFFFF;
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

    .datepicker {
        border: 1px solid #ced4da;
        padding: 8px;
    }

    textarea:focus {
        background: none repeat scroll 0 0 #FFFFFF;
        outline-width: 0;
    }

</style>
<div class="table-rep-plugin">
    <div class="table-responsive mb-0" data-pattern="priority-columns">
        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
            style="border-collapse: collapse; border-spacing: 0; width: 100%;">

            <thead>
                <tr>
                    <th data-priority="2">Employee ID</th>
                    <th data-priority="3">Name</th>
                    <th data-priority="1">Date</th>
                    <th data-priority="5">Leave</th>
                    <th data-priority="6">Status</th>
                    <th data-priority="6">Time In</th>
                    <th data-priority="7">Time Out</th>
                    @if ($slug == 'admin')
                        <th data-priority="8">Action</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach ($leaves as $leave)
                    <tr>
                        <td><span style="display:none;">{{strtotime($leave->created_at)}}</span>{{ $leave->emp_id }}</td>
                        <td>{{ $leave->employee->name }}</td>
                        <td>{{ $leave->leave_date }}
                            @if ($leave->type == 2)
                            {{-- Type 2 For Live Request And Type one For Half Day Request/ Status 1 and state 0 maen Pending State 1 Approved status 0 means Canceled  --}}
                                <span class="badge badge-primary badge-pill float-right">Leave</span>
                            @elseif ($leave->type == 3 && $leave->state == 0) 
                                <span class="badge badge-danger badge-pill float-right">Absent</span>
                            @elseif ($leave->type == 3 && $leave->state == 1) 
                                <span class="badge badge-primary badge-pill float-right">Leave</span>
                            @else
                                <span class="badge badge-info badge-pill float-right">Half Day</span>
                            @endif
                        </td>
                        <td>{{ $leave->leave_time }}
                        </td>
                        <td>
                            @if ($leave->state == 1)
                            <span class="badge badge-success badge-pill float-right">Approved</span>
                            @elseif($leave->status == 0 && $leave->state == 0)
                            <span class="badge badge-danger badge-pill float-right">Disapproved</span>
                            @elseif($leave->status == 1 && $leave->state == 0)
                            <span class="badge badge-warning badge-pill float-right">Pending</span>
                            @endif
                        </td>
                        <td>{{ $leave->employee->schedules->first()->time_in }} </td>
                        <td>{{ $leave->employee->schedules->first()->time_out }}</td>
                        @if ($slug == 'admin')
                            <td>
                                <a href="#" data-toggle="modal" data-target="#ajax_update_modal"
                                    data-id="{{ $leave->id }}"
                                    class="btn btn-success btn-sm update_leaverequest btn-flat"><i
                                        class='fa fa-edit'></i></a>
                                <a class="btn-success btn-sm text-light view_leave_reason" data-id="{{ $leave->id }}"
                                    data-reason="Reason: {{ $leave->leave_reason }}"><i
                                        class="fa fa-eye"></i></a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@include('includes.ajax_modal')
