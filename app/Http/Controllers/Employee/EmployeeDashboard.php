<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Employee;
use App\Models\Latetime;
use App\Models\Attendance;
use App\Models\Check;
use App\Http\Requests\CustomHTTPRec;
use App\Jobs\SendFirebaseNotification;
use App\Models\clock_out;
use App\Models\ClockOut;
use App\Models\EmployeeSetting;
use Dflydev\DotAccessData\Data;
use Carbon\Carbon;


class EmployeeDashboard extends Controller
{
    public function index()
    {
        $team = getUsersInTeam(auth()->user()->id);
        $emp_id = get_emp_id(auth()->user()->id);

        $totalEmp =  count(Employee::all());
        $AllAttendance = count(Attendance::where('emp_id', $emp_id)->whereMonth('created_at', date('m'))->get());
        $ontimeEmp = count(Attendance::where('emp_id', $emp_id)->whereMonth('created_at', date('m'))->whereStatus('1')->get());
        $latetimeEmp = count(Attendance::where('emp_id', $emp_id)->whereMonth('created_at', date('m'))->whereStatus('0')->get());

        if ($AllAttendance > 0) {
            $percentageOntime = str_split(($ontimeEmp / $AllAttendance) * 100, 4)[0];
        } else {
            $percentageOntime = 0;
        }
        $data = [$AllAttendance, $ontimeEmp, $latetimeEmp, $percentageOntime];
        $today = date('Y-m-d');
        $clockout = '';
        $attendance = Attendance::where('emp_id', $emp_id)->where('attendance_date', $today)->with('check')->latest('created_at')->first();
        if(!$attendance){
            return redirect()->route('checkin');
        }
        if ($attendance->state == 0) {
            $clockouts = Attendance::where('id', $attendance->id)->with('clockout')->with('check')->get()->first();
        } else {
            $clockouts = Attendance::where('id', $attendance->id)->with('clockout')->with('check')->get()->first();
        }
        // Calculate Total  Clock out Time
        $tclockout = 0;
        foreach ($clockouts->clockout as $key => $clockout) {
            if ($clockout->status == 0) {
                $cout = strtotime($clockout->clock_out);
                if (isset($clockout->clock_in)) {
                    $cin = strtotime($clockout->clock_in);
                } else {
                    $cin = strtotime(now());
                }
                $diff = ($cin - $cout) / 60;
                $tclockout += $diff;
            }
        }
        $workingtime = (strtotime(now()) - strtotime($attendance->check->attendance_time)) / 60;
        $workingtime = ($workingtime - $tclockout) * 60;
        $time = gmdate("H:i:s", $workingtime);
        if (!isset($clockouts->check->leave_time) && $clockouts->state == 1) {
            $clockoutStatus = 1;
        } else {
            $clockoutStatus = 0;
        }
        $messsage= get_messagebox_setting($emp_id, $today);
        return view('employee.dashboard')->with(['data' => $data, 'tworktime' => $time,  'timestamp' => $workingtime, 'clockoutStatus' => $clockoutStatus, 'messageStr'=>$messsage['messageStr'], 'team' => $team]);
    }
    public function account_blocked() {
        return view('check-in.account-block');
    }
    public function check_in_message()
    {
        $emp_id = get_emp_id(auth()->user()->id);
        $today = date('Y-m-d');
        $attendance = Attendance::where('attendance_date', $today)->with('employee')->where('emp_id', $emp_id)->first();
        if (isset($attendance->attendance_date)) {
            return redirect()->route('dashboard');
        } else {
            return view('check-in.checkin-message');
        }
    }
    public function check_in_view()
    {
        $emp_id = get_emp_id(auth()->user()->id);
        $today = date('Y-m-d');
        $data = Attendance::where('attendance_date', $today)->with('employee')->where('emp_id', $emp_id)->first();
        if (isset($data->attendance_date)) {
            return redirect()->route('dashboard');
        } else {
            $employee= Employee::find($emp_id);
            if($employee->tracking==1 && $employee->status==1){
                return redirect()->route('checkinerror');
            } elseif ($employee->status==0 || $employee->status==2) {
                return redirect()->route('account-block');
            }
            $data = get_schedule_time(auth()->user()->id);
            $time1 = strtotime($data->time_in);
            $time2 =  strtotime('-5 minutes', strtotime(Date('H:i:s')));
            $difference = strtotime(Date('H:i:s')) - $time1;
            if ($time2 > $time1) {
                if (strtotime(Date('H:i:s')) > strtotime('150 minutes', strtotime($data->time_in))) {
                    $data->link = 'href=#addreview data-toggle=modal';
                    $data->message = 'Your attendance will be marked as half-day';
                    $data->attendance_status = '2';   //Attendance Status 2 Mark Employee Half Day 
                    $data->duration = gmdate("H:i:s", $difference);
                } else {
                    $data->link = 'href=#addreview data-toggle=modal';
                    $data->message = 'why you are late?';
                    $data->attendance_status = '0';  //Attendance Status 1 Mark Employee Late Attendance 
                    $data->duration = gmdate("H:i:s", $difference);
                }
            } else {
                $data->duration = 0;
                $data->message = '';
                $data->attendance_status = '1';  //Employee on Time
                $data->link = 'href=clickcheckin';
            }
            return view('check-in.check-in')->with(['data' => $data]);
        }
    }
    public function check_in()
    {
        $emp_data = get_schedule_time(auth()->user()->id);
        $data = array(
            'uid' => auth()->user()->id,
            'emp_id' => $emp_data->emp_id,
            'attendance_time' => date('Y/m/d H:i:s'),
            'attendance_date' => date('Y/m/d'),
            'status' => 1

        );
        $this->Store_checkIn($data);
        return redirect()->route('dashboard');
    }
    public function submit_late_reason()
    {
        $emp_data = get_schedule_time(auth()->user()->id);
        $data = array(
            'uid' => auth()->user()->id,
            'emp_id' => $emp_data->emp_id,
            'attendance_time' => date('Y/m/d H:i:s'),
            'attendance_date' => date('Y/m/d'),
            'status' => $_POST['attendance_status'],
            'reason' => $_POST['reason'],
            'duration' => $_POST['duration'],
        );
        $attendece_id = $this->Store_checkIn($data);
        $Latetime = new Latetime();
        $Latetime->attendance_id = $attendece_id;
        $Latetime->emp_id = $data['emp_id'];
        $Latetime->duration = $data['duration'];
        $Latetime->latetime_date = $data['attendance_date'];
        $Latetime->reason =  $data['reason'];
        $Latetime->save();
        $name = Employee::find($data['emp_id'])->name;
        $http = $_SERVER['HTTP_HOST'];
        $notification = array(
            "body" => $name . ' checked in late ' . $http,
            "title" => '' . 'Late Check-in' . ' ',
            "icon" => '',
            "click_action" => 'https://hrm.allstartechnologies.co.uk/latetime/',
        );
        dispatch(new SendFirebaseNotification($notification));
    }
    public function Store_checkIn($data)
    {
        // -----Insert Attendence Data---------
        $attendence = new Attendance;
        $attendence->uid = $data['uid'];
        $attendence->emp_id = $data['emp_id'];
        $attendence->state = 1;
        $attendence->attendance_time = $data['attendance_time'];
        $attendence->attendance_date = $data['attendance_date'];
        $attendence->status = $data['status'];
        $attendence->type = 1;
        $attendence->save();

        // -----Check In Data---------
        $check = new Check;
        $check->attendance_id  = $attendence->id;
        $check->emp_id = $data['emp_id'];
        $check->attendance_time = $data['attendance_time'];
        $check->save();
        return $attendence->id;
    }
    public  function check_out()
    {
        $data = get_schedule_time(auth()->user()->id);
        $emp_id = get_emp_id(auth()->user()->id);

        $time1 = strtotime($data->time_out);
        $time2 = strtotime(Date('H:i:s'));
        $today = date('Y-m-d');
        $attendance = Attendance::where('emp_id', $emp_id,)->where('attendance_date', $today)->latest('created_at')->first();
        $getSchedule = getScheduleByEmployee($attendance);

        $current_time = Carbon::now();
        $current_time_only = $current_time->format('H:i:s');
        $start_time = Carbon::createFromTimeString('10:30:00'); // 10:30 AM

        // Convert schedule end time to a Carbon instance
        $end_time = Carbon::createFromTimeString($getSchedule['shift_end_time']);

        // Subtract 60 minutes from $end_time
        $end_time->subMinutes(58);
        // end 
        // $difference = round(($time1 - $time2) / 3600, 0);

        $data = json_decode($attendance);
        if (isset($data->attendance_date)) {
            $check = Check::where('attendance_id', $attendance->id)->latest('created_at')->first();
            $clockout = ClockOut::where('attendance_id', $attendance->id)->latest('created_at')->first();
            if ($attendance->state == 0 && !isset($clockout->clock_in)) {
                $clockout->clock_in = date('Y-m-d H:i:s');
                $clockout->save();

                $attendance->state = '1';
                $attendance->save();
            }
            $check->leave_time = now();
            if ($check->save()) {

                // start 
                // Check if the current time is after 10:30 AM or before the schedule end time (which may be the next day)
                if ($current_time_only < $end_time->format('H:i:s')) {
                    // The current time is between 10:30 AM and the employee's schedule end time
                    $attendance->status = '3';
                    $attendance->save();
                }

                // end 

                echo 'success';
            }
        } else {
            echo 'record not found';
        }
    }
    public function mark_attendance()
    {
        $emp_id = get_emp_id(auth()->user()->id);
        $today = date('Y-m-d');
        $clockout = '';
        $attendance = Attendance::where('emp_id', $emp_id)->where('attendance_date', $today)->with('check')->latest('created_at')->first();
        if (isset($attendance)) {
            if ($attendance->state == 0) {
                $clockout = ClockOut::where('attendance_id', $attendance->id)->with('attendance')->get();
            } else {
                $clockout = ClockOut::where('attendance_id', $attendance->id)->with('attendance')->get();
            }
            return view('admin.mark-attendance')->with(['clockouts' => $clockout, 'attendance' => $attendance]);
        } else {
            return redirect()->route('checkin');
        }
    }
    public function clock_out()
    {

        // $data = $_POST;
        // print_r($_POST);
        // exit;
        // echo $_POST['remarks'];exit;

        if ($_POST['remarks'] == 'short break' || $_POST['remarks'] == 'official') {
            if ($_POST['remarks'] == 'short break') {
                $reason = $_POST['remarks'];
                $type = '0';
            } else {
                $reason = $_POST['reason'];
                $type = '1';
            }
            $emp_id = get_emp_id(auth()->user()->id);
            $today = date('Y-m-d');
            $attendance = Attendance::where('emp_id', $emp_id)->where('attendance_date', $today)->latest('created_at')->with('check')->first();
            if (!isset($attendance->check->leave_time) && $attendance->state == 1) {
                $clockout = new ClockOut;
                $clockout->emp_id = $emp_id;
                $clockout->attendance_id = $attendance->id;
                $clockout->clock_out = date('Y-m-d H:i:s');
                $clockout->type = $type;
                $clockout->reason =  $reason;
                if ($clockout->save()); {
                    $attendance->state = '0';
                    if ($attendance->save()) {
                        echo 'success';
                    }
                }
            } else {
                echo 'success';
            }
        } elseif ($_POST['remarks'] == 'shift over') {
            $this->check_out();
        }
    }
    public function clock_in()
    {
        $emp_id = get_emp_id(auth()->user()->id);
        $today = date('Y-m-d');
        $attendance = Attendance::where('emp_id', $emp_id)->where('attendance_date', $today)->latest('created_at')->first();
        if ($attendance->state == 0) {
            $clockout = ClockOut::where('attendance_id', $attendance->id)->latest('created_at')->first();
            $clockout->clock_in = date('Y-m-d H:i:s');
            if ($clockout->save()) {
                $attendance->state = '1';
                if ($attendance->save()) {
                    echo 'success';
                    if ($clockout->type == 1) {
                        // ------------Initiate Notification on Official Break----------------
                        $name = Employee::find($attendance->emp_id)->name;
                        $http = $_SERVER['HTTP_HOST'];
                        $notification = array(
                            "body" => $name . ' Mark official break ' . $http,
                            "title" => '' . 'Officail break' . ' Initiated',
                            "icon" => '',
                            "click_action" => 'https://hrm.allstartechnologies.co.uk/',
                        );
                        sendNotification($notification);
                    }
                }
            }
        } else {
            echo 'success';
        }
    }
}
