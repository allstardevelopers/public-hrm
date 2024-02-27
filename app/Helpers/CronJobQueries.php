<?php

use App\Jobs\SendFirebaseNotification;
use App\Models\AppResponse;
use App\Models\Attendance;
use App\Models\Check;
use App\Models\ClockOut;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\UpcomingEvent;
use Carbon\Carbon;

function prob_complete_notif()
{
    $employees = Employee::all();
    foreach ($employees as $employee) {
        $probation_comp = strtotime('+' . $employee->probation . 'months', strtotime($employee->joining_date));
        $today = strtotime(date('d-m-Y'));
        if ($probation_comp == $today) {
            $name = $employee->name;
            $notification = array(
                "body" => $name . ' have successfully completed his probation period ',
                "title" => '' . ' Probation Completed' . ' ',
                "icon" => '',
                "click_action" => 'https://hrm.allstartechnologies.co.uk/',
            );
            // sendNotification($notification);
            dispatch(new SendFirebaseNotification($notification));
        }
    }
}
// ___________________---Employee Tracking ------------------------------

function markAbsent()
{
    $yesterday = Carbon::yesterday();
    // Check if yesterday was not a weekend (Saturday or Sunday)
    if (!$yesterday->isWeekend()) {
        // Check if yesterday was not a holiday event
        $event = UpcomingEvent::whereDate('event_held_on', $yesterday)->where('event_type', '1')->first();
        if (!$event) {
            // Fetch employees who haven't checked in yesterday
            $attendance = Attendance::whereDate('attendance_date', $yesterday)
                ->pluck('emp_id')
                ->toArray();


            $employees = Employee::where('status', '1')->pluck('id')->toArray();
            $absentEmployees = array_diff($employees, $attendance);
            foreach ($absentEmployees as $emp_id) {
                $leaveRequested = Leave::where('emp_id', $emp_id)
                    ->whereDate('leave_date', $yesterday)
                    ->exists();
                if (!$leaveRequested) {
                    if ($emp_id != 141) {
                        Leave::create([
                            'uid' => 1,
                            'emp_id' => $emp_id,
                            'type' => '3',
                            'leave_time' => '00:00:00',
                            'leave_date' => $yesterday,
                            'leave_reason' => 'Auto-marked as absent - No check-in',
                        ]);
                    }
                }
            }
            // -----To Remove Leave From system if Employee Checked in -------------
            $leaves = Leave::whereDate('leave_date', $yesterday)
                ->where('type', 2)
                ->pluck('emp_id')
                ->toArray();
            $leaveapplied = array_intersect($leaves, $attendance);
            foreach ($leaveapplied as $emp_id) {
                $leave = Leave::where('emp_id', $emp_id)->delete();
            }
            return 'Absentees marked successfully.';
        }
    }
}
function markshortLeave()
{
    $yesterday = Carbon::yesterday();
    // $yesterday = Carbon::create(2024, 1, 8);
    if (!$yesterday->isWeekend()) {
        $event = UpcomingEvent::whereDate('event_held_on', $yesterday)->where('event_type', '1')->first();
        if (!$event) {
            $attendances = Attendance::whereDate('attendance_date', $yesterday)
                ->with('check')
                ->get();
            // pd($attendances);
            foreach ($attendances as $attendance) {
                $existingLeave = Leave::where('emp_id', $attendance->employee->id)
                    ->whereDate('leave_date', $yesterday)
                    ->where('type', '1') // Assuming '2' represents short leave, adjust as needed
                    ->first();
                if (!$existingLeave) {
                    $schedule = $attendance->employee->schedules->first();
                    $s_in = Carbon::parse($yesterday)->setTimeFromTimeString($schedule->time_in);
                    $s_out = Carbon::parse($yesterday)->setTimeFromTimeString($schedule->time_out);
                    $checkIn = Carbon::parse($attendance->check->attendance_time);
                    $checkOut = Carbon::parse($attendance->check->leave_time);
                    if ($checkIn->gt($s_in->addMinutes(120)) || $checkOut->lt($s_out->subMinutes(120))) {
                        if ($checkIn->gt($s_in->addMinutes(120))) {
                            if ($attendance->employee->id != 141) {
                                Leave::create([
                                    'uid' => 1,
                                    'emp_id' => $attendance->employee->id,
                                    'type' => '1',
                                    'leave_time' => $attendance->check->attendance_time,
                                    'leave_date' => $yesterday,
                                    'leave_reason' => 'Auto-marked as Half Day - Checked in at: ' . $attendance->check->attendance_time . '.',
                                ]);
                                $attendance->status = 2;
                                $attendance->save();
                            }
                        } elseif ($checkOut->lt($s_out->subMinutes(120))) {
                            if ($attendance->employee->id != 141) {
                                Leave::create([
                                    'uid' => 1,
                                    'emp_id' => $attendance->employee->id,
                                    'type' => '1',
                                    'leave_time' => $attendance->check->leave_time,
                                    'leave_date' => $yesterday,
                                    'leave_reason' => 'Auto-marked as Half Day - Early Checked out at: ' . $attendance->check->leave_time . '.',
                                ]);
                                $attendance->status = 3;
                                $attendance->save();
                            }
                        }
                    }
                } else {
                    $schedule = $attendance->employee->schedules->first();
                    $s_in = Carbon::parse($yesterday)->setTimeFromTimeString($schedule->time_in);
                    $s_out = Carbon::parse($yesterday)->setTimeFromTimeString($schedule->time_out);
                    $checkIn = Carbon::parse($attendance->check->attendance_time);
                    $checkOut = Carbon::parse($attendance->check->leave_time);
                    if ($checkIn->lte($s_in->addMinutes(120)) && $checkOut->gte($s_out->subMinutes(120))) {
                        $existingLeave->delete();
                    }
                }
            }
        }
    }
}
function employeeTracking()
{
    $today = date('Y-m-d');
    $attendances = Attendance::where('attendance_date', $today)->with('employee')->with('check')->with('clockout')->get();
    foreach ($attendances as $attendance) {
        $schedule = get_schedule_time($attendance->uid);
        $appresponse = AppResponse::where('emp_id', $attendance->emp_id)->where('attendance_id', $attendance->id)->first();
        // ------------Check Employee App last Response is not  greater then 2 Minutes --------------
        if (isset($appresponse->id)) {
            if (strtotime(Date('H:i:s')) > strtotime('2 minutes', strtotime($appresponse->response_update))) {
                // -------Check Weather employee Check out time + 30 minutes and not recived any response  
                if (strtotime(Date('H:i:s')) > strtotime('30 minutes', strtotime($schedule->time_out))) {
                    if (!isset($attendance->check->leave_time)) {
                        $check = Check::find($attendance->check->id);
                        if ($attendance->state == '0') {
                            $clockout = ClockOut::where('attendance_id', $attendance->id)->latest('created_at')->first();
                            $clockout->clock_in = $clockout->clock_out;
                            $clockout->save();
                            $attendance->state = '1';
                            $attendance->save();
                            $check->leave_time = $clockout->clock_out;
                        } else {
                            $check->leave_time = date('Y-m-d H:i:s', strtotime("$today $schedule->time_out"));
                        }
                        if ($check->save()) {
                            echo 'success';
                        }
                    }
                } else {
                    if (!isset($attendance->check->leave_time) && $attendance->state == '1' &&  $attendance->employee->tracking == 1) {
                        $clockout = new ClockOut();
                        $clockout->emp_id = $attendance->emp_id;
                        $clockout->attendance_id = $attendance->id;
                        $clockout->clock_out = date('Y-m-d H:i:s');
                        $clockout->type = '0';
                        $clockout->reason =  'auto break';
                        if ($clockout->save()); {
                            $attendance->state = '0';
                            $attendance->save();
                            echo $attendance->employee->name . ' clockout <br>';
                        }
                    } else {
                        if ($attendance->employee->tracking == 1) {
                            echo $attendance->employee->name . ' tracking <br>';
                        }
                        echo $attendance->employee->name . ' alredy clock out <br>';
                    }
                }
            } else {
                echo $attendance->employee->name . ' online <br>';
            }
        } elseif ($attendance->employee->tracking == 1) {
            if (strtotime(Date('H:i:s')) > strtotime('30 minutes', strtotime($schedule->time_out))) {
                if (!isset($attendance->check->leave_time)) {
                    $check = Check::find($attendance->check->id);
                    if ($attendance->state == '0') {
                        $clockout = ClockOut::where('attendance_id', $attendance->id)->latest('created_at')->first();
                        $clockout->clock_in = date('Y-m-d H:i:s');
                        $clockout->save();
                        $attendance->state = '1';
                        $attendance->save();
                    }
                    $check->leave_time = date('Y-m-d H:i:s', strtotime("$today $schedule->time_out"));
                    if ($check->save()) {
                        echo 'success';
                    }
                }
            } else {
                if (!isset($attendance->check->leave_time) && $attendance->state == '1') {
                    $clockout = new ClockOut();
                    $clockout->emp_id = $attendance->emp_id;
                    $clockout->attendance_id = $attendance->id;
                    $clockout->clock_out = date('Y-m-d H:i:s');
                    $clockout->type = '0';
                    $clockout->reason =  'auto break';
                    if ($clockout->save()); {
                        $attendance->state = '0';
                        $attendance->save();
                        echo $attendance->employee->name . ' clockout';
                    }
                }
            }
        }
    }
}
