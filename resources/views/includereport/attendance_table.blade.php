<div class="table-rep-plugin">
    <div class="table-responsive mb-0">
        <table id="{{ $slug === "admin" ? "datatable-buttons" : "datatable" }}" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Break Spent</th>
                    <th>Break Remaning</th>
                    <th>Work Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $attendance)
                <tr>
                    <td><span style="display:none;">{{strtotime($attendance->created_at)}}</span>{{date('d-M-Y',strtotime($attendance->attendance_date) )}}</td>
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
                    @if (isset($attendance->check->leave_time))
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
                    if($clockout->status==0 && $clockout->type == 0)
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
                    $cin = strtotime($attendance->employee->schedules->first()->time_out);
                    if($cout < strtotime($attendance->employee->schedules->first()->time_out))
                        {
                        $cin=$cout;
                        }
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
                        <td>{{ $hours != 0 ?  $hours." hour ". $minutes." min": $minutes." min" }} </td>
                        @else
                        <td class="text-danger">{{ $hours != 0 ?  $hours." hour ". $minutes." min": $minutes." min" }}</td>
                        @endif
                        @else
                        <td></td>
                        @endif
                        @if($remaning>0)
                        <td>{{$totalbreak-$tclockout}} min</td>
                        @elseif($remaning<0) <td>{{$totalbreak-$tclockout}} min <span class="badge badge-danger badge-pill float-right">Extra</span></td>
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
                            @can('permission', 'view')
                            <td class="text-center"><a href="{{route('employeeClock', ['id'=>encrypt($attendance->id)])}}"><i class="fa fa-eye"></i></a></a></td>
                            @endcan
                            @endif
                </tr>
                @endforeach


            </tbody>
        </table>
    </div>
</div>