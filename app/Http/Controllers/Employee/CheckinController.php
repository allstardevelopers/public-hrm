<?php

namespace App\Http\Controllers\Employee;


use App\Http\Controllers\Controller;
use App\Jobs\SendFirebaseNotification;
use App\Models\AppResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Latetime;
use App\Models\Attendance;
use App\Models\Check;
use App\Models\ClockOut;

class CheckinController extends Controller
{
    public function index()
    {
        // Check In 
        return view('check-in.check-in');
    }
    public function cronjob()
    {
        $name='Ahsan Danish';
        $http=$_SERVER['HTTP_HOST'];


        $notification = array(
            "body" => $name . ' checked in Late ' . $http,
            "title" => '' . 'Late Check-in' . ' ',
            "icon" => '',
            "click_action" => 'https://hrm.allstartechnologies.co.uk/clockrequest/',
        );
        // sendNotification($notification);
        dispatch(new SendFirebaseNotification($notification));
        echo 'send';
    }
    public function cronStatus()
    {
        $time = now();
        echo strtotime($time) . '<br>';
        echo  strtotime('30 seconds', strtotime($time));
        exit;
        $today = date('Y-m-d');
        $attendances = Attendance::where('attendance_date', $today)->with('employee')->with('check')->with('clockout')->get();
        foreach ($attendances as $attendance) {
            $schedule = get_schedule_time($attendance->uid);
            $appresponse = AppResponse::where('emp_id', $attendance->emp_id)->where('attendance_id', $attendance->id)->first();
            // ------------Check Employee App last Response is not  greater then 10 Minutes --------------
            if (isset($appresponse->id)) {
                if (strtotime(Date('H:i:s')) > strtotime('1 minutes', strtotime($appresponse->response_update))) {
                    // -------Check Weather employee Check out time + 30 minutes and not recived any response  
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
                            echo '<pre>';
                            print_r(json_decode($attendance));
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
                            echo $attendance->employee->name . ' already clock out <br>';
                        }
                    }
                } else {
                    echo $attendance->employee->name . ' online <br>';
                }
            } elseif ($attendance->employee->tracking == 1) {
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
