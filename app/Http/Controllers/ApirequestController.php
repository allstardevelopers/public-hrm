<?php

namespace App\Http\Controllers;

use App\Jobs\SendFirebaseNotification;
use App\Models\AppResponse;
use App\Models\Attendance;
use App\Models\Check;
use App\Models\ClockOut;
use App\Models\Employee;
use App\Models\Latetime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApirequestController extends Controller
{
    public function index($id)
    {
        $employee = Employee::find($id);
        return response()->json($employee);
    }
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (isset($user)) {
            if (Hash::check($request->password, $user->password)) {
                $employee = Employee::where('user_id', $user->id)->first();
                $schedule = get_schedule_time($user->id);
                $response = [
                    'respString' => 'Login successful',
                    'time_out' => $schedule->time_out,
                    'respcode'  => '105',
                    'employee' => $employee,
                ];
                return response()->json($response);
            } else {
                $response = [
                    'respString' => 'Invalid username or password',
                    'respcode'  => '103',
                ];
                return response()->json($response);
            }
        } else {
            $response = [
                'respString' => 'Invalid username or password',
                'respcode'  => '103',
            ];
            return response()->json($response);
        }
    }
    public function checkin_status($id)
    {
        $today = date('Y-m-d');
        $attendance = Attendance::where('emp_id', $id)->where('attendance_date', $today)->with('check')->with('clockout')->first();
        if (!isset($attendance)) {
            $user_id = get_user_id($id);
            $data = get_schedule_time($user_id);
            $time1 = strtotime($data->time_in);
            $time2 =  strtotime('-5 minutes', strtotime(Date('H:i:s')));
            $difference = strtotime(Date('H:i:s')) - $time1;
            if ($time2 > $time1) {
                if (strtotime(Date('H:i:s')) > strtotime('120 minutes', strtotime($data->time_in))) {
                    $response = [
                        'respString' => 'Late Check In',
                        'messagestring' => 'Your attendance will be marked as half-day',
                        'respcode'  => '112',
                        'difference' => gmdate("H:i:s", $difference),
                        'clock' => date('H:i:s'),
                    ];
                } else {
                    $response = [
                        'respString' => 'Late Check In',
                        'messagestring' => 'why you are late?',
                        'respcode'  => '111',
                        'difference' => gmdate("H:i:s", $difference),
                        'clock' => date('H:i:s'),
                    ];
                }
            } else {
                $response = [
                    'respString' => 'On Time',
                    'respcode'  => '110',
                    'clock' => date('H:i:s'),
                ];
            }

            return response()->json($response);
        }
    }
    public function check_in(Request $request)
    {
        $today = date('Y-m-d');
        $attendance = Attendance::where('emp_id', $request->emp_id)->where('attendance_date', $today)->with('check')->with('clockout')->first();
        if (!isset($attendance)) {
            $user_id = get_user_id($request->emp_id);
            if ($request->status == '111' || $request->status == '112') {
                $schedule_time = get_schedule_time($user_id);
                $time1 = strtotime($schedule_time->time_in);
                $difference = strtotime(Date('H:i:s')) - $time1;
                if (strtotime(Date('H:i:s')) > strtotime('120 minutes', strtotime($schedule_time->time_in))) {
                    $attendance_status = 2;
                } else {
                    $attendance_status = 0;
                }
                $data = array(
                    'uid' => $user_id,
                    'emp_id' => $request->emp_id,
                    'attendance_time' => date('Y/m/d H:i:s'),
                    'attendance_date' => date('Y/m/d'),
                    'status' => $attendance_status,
                    'reason' => $request->late_reason,
                    'duration' => gmdate("H:i:s", $difference),
                );
                $attendece_id = $this->Store_checkIn($data);
                $Latetime = new Latetime();
                $Latetime->attendance_id = $attendece_id;
                $Latetime->emp_id = $data['emp_id'];
                $Latetime->duration = $data['duration'];
                $Latetime->latetime_date = $data['attendance_date'];
                $Latetime->reason =  $data['reason'];
                // return response()->json($Latetime);exit;
                if ($Latetime->save()) {
                    $response = [
                        'attendance_id' => $attendece_id,
                        'respString' => 'Working Time:',
                        'time_out' => $schedule_time->time_out,
                        'respcode'  => '105',
                        'clock' => get_worked_time($request->emp_id, $today),
                        'setting_obj' => get_application_setting($request->emp_id)
                    ];
                    $name = Employee::find($data['emp_id'])->name;
                    $http = $_SERVER['HTTP_HOST'];
                    $notification = array(
                        "body" => $name . ' checked in late ' . $http,
                        "title" => '' . 'Late Check-in' . ' ',
                        "icon" => '',
                        "click_action" => 'https://hrm.allstartechnologies.co.uk/latetime/',
                    );
                    dispatch(new SendFirebaseNotification($notification));
                    return response()->json($response);
                }
            } elseif ($request->status == '110') {
                $data = array(
                    'uid' => $user_id,
                    'emp_id' => $request->emp_id,
                    'attendance_time' => date('Y/m/d H:i:s'),
                    'attendance_date' => date('Y/m/d'),
                    'status' => 1
                );
                $attendece_id = $this->Store_checkIn($data);
                if (isset($attendece_id)) {
                    $schedule = get_schedule_time($user_id);
                    $response = [
                        'attendance_id' => $attendece_id,
                        'respString' => 'Working Time:',
                        'time_out' => $schedule->time_out,
                        'respcode'  => '105',
                        'clock' => get_worked_time($request->emp_id, $today),
                        'setting_obj' => get_application_setting($request->emp_id)
                    ];
                    return response()->json($response);
                }
            }
        }
    }
    public function checkAttendance($id)
    {
        $today = date('Y-m-d');
        $attendance = Attendance::where('emp_id', $id)->where('attendance_date', $today)->with('check')->with('clockout')->first();
        $user_id = get_user_id($id);
        $schedule = get_schedule_time($user_id);
        if (isset($attendance)) {
            if (!isset($attendance->check->leave_time)) {
                if ($attendance->state == 1) {

                    $response = [
                        'attendance_id' => $attendance->id,
                        'respString' => 'Working Time:',
                        'emp_name' => Employee::find($attendance->emp_id)->name,
                        'time_out' => $schedule->time_out,
                        'respcode'  => '105',
                        'clock' => get_worked_time($id, $today),
                        'messagebox' => get_messagebox_setting($id, $today),
                        'setting_obj' => get_application_setting($id)
                    ];
                    return response()->json($response);
                } else {
                    $response = [
                        'attendance_id' => $attendance->id,
                        'respString' => 'Clock out',
                        'emp_name' => Employee::find($id)->name,
                        'respcode'  => '102',
                        'clock' => get_worked_time($id, $today),
                        'messagebox' => get_messagebox_setting($id, $today),
                        'setting_obj' => get_application_setting($id)
                    ];
                    return response()->json($response);
                }
            } else {
                $response = [
                    'attendance_id' => $attendance->id,
                    'respString' => 'Checked out',
                    'emp_name' => Employee::find($id)->name,
                    'respcode'  => '103',
                    'clock' => get_worked_time($id, $today),
                    'setting_obj' => get_application_setting($id)
                ];
                return response()->json($response);
            }
        } else {
            $response = [
                'respString' => 'Checkin Missing',
                'respcode'  => '101',
                'emp_name' => Employee::find($id)->name,
                'clock' => date('H:i:s'),
                'setting_obj' => get_application_setting($id)
            ];
            return response()->json($response);
        }
    }
    public function clockout(Request $request)
    {
        $today = date('Y-m-d');
        $attendance = Attendance::with('check')->find($request->attendance_id);
        if (isset($request->out_type)) {
            if ($request->out_type == 'auto') {
                $setting_obj=get_application_setting($attendance->emp_id);
                $couttime=$setting_obj['clockTime']+15;
                $clock_out_time = date('Y-m-d H:i:s', strtotime('-'.$couttime.' seconds', strtotime(Date('H:i:s'))));
            } else {
                $clock_out_time = date('Y-m-d H:i:s');
            }
        } else {
            $clock_out_time = date('Y-m-d H:i:s');
        }
        $clockout = new ClockOut();
        $clockout->emp_id = $attendance->emp_id;
        $clockout->attendance_id = $attendance->id;
        $clockout->clock_out = $clock_out_time;
        $clockout->type = '0';
        $clockout->reason =  'short break';
        if (!isset($attendance->check->leave_time) && $attendance->state == '1') {
            if ($clockout->save()) {
                $attendance->state = '0';
                if ($attendance->save()) {
                    $response = [
                        'attendance_id' => $attendance->id,
                        'respString' => 'Clock out',
                        'emp_name' => Employee::find($attendance->emp_id)->name,
                        'respcode'  => '102',
                        'clock' => get_worked_time($attendance->emp_id, $today),
                        'messagebox' => get_messagebox_setting($attendance->emp_id, $today),
                        'setting_obj' => get_application_setting($attendance->emp_id)
                    ];
                    return response()->json($response);
                }
            }
        } else {
            $response = [
                'attendance_id' => $attendance->id,
                'respString' => 'Clock out',
                'emp_name' => Employee::find($attendance->emp_id)->name,
                'respcode'  => '102',
                'clock' => get_worked_time($attendance->emp_id, $today),
                'messagebox' => get_messagebox_setting($attendance->emp_id, $today),
                'setting_obj' => get_application_setting($attendance->emp_id)
            ];
            return response()->json($response);
        }
    }
    public function clockin(Request $request)
    {
        $today = date('Y-m-d');
        $attendance = Attendance::with('check')->find($request->attendance_id);
        $user_id = get_user_id($attendance->emp_id);
        $schedule = get_schedule_time($user_id);
        if (!isset($attendance->check->leave_time) && $attendance->state == 0) {
            $clockout = ClockOut::where('attendance_id', $attendance->id)->latest('created_at')->first();
            $clockout->clock_in = date('Y-m-d H:i:s');
            $clockout->reason = $request->reason;
            if ($clockout->save()) {
                $attendance->state = '1';
                if ($attendance->save()) {
                    $response = [
                        'attendance_id' => $attendance->id,
                        'respString' => 'Working Time:',
                        'emp_name' => Employee::find($attendance->emp_id)->name,
                        'time_out' => $schedule->time_out,
                        'respcode'  => '105',
                        'clock' => get_worked_time($attendance->emp_id, $today),
                        'messagebox' => get_messagebox_setting($attendance->emp_id, $today),
                        'setting_obj' => get_application_setting($attendance->emp_id)
                    ];
                    return response()->json($response);
                }
            }
        } else {
            $response = [
                'attendance_id' => $attendance->id,
                'respString' => 'Working Time:',
                'emp_name' => Employee::find($attendance->emp_id)->name,
                'time_out' => $schedule->time_out,
                'respcode'  => '105',
                'clock' => get_worked_time($attendance->emp_id, $today),
                'messagebox' => get_messagebox_setting($attendance->emp_id, $today),
                'setting_obj' => get_application_setting($attendance->emp_id)
            ];
            return response()->json($response);
        }
    }
    public function official_clock_in(Request $request)
    {
        $today = date('Y-m-d');
        $attendance = Attendance::with('check')->find($request->attendance_id);
        $user_id = get_user_id($attendance->emp_id);
        $schedule = get_schedule_time($user_id);
        if (!isset($attendance->check->leave_time) && $attendance->state == 0) {
            $clockout = ClockOut::where('attendance_id', $attendance->id)->latest('created_at')->first();
            $clockout->clock_in = date('Y-m-d H:i:s');
            $clockout->type = '1';
            $clockout->reason = $request->reason;
            if ($clockout->save()) {
                $attendance->state = '1';
                if ($attendance->save()) {
                    $response = [
                        'attendance_id' => $attendance->id,
                        'respString' => 'Working Time:',
                        'emp_name' => Employee::find($attendance->emp_id)->name,
                        'time_out' => $schedule->time_out,
                        'respcode'  => '105',
                        'clock' => get_worked_time($attendance->emp_id, $today),
                        'messagebox' => get_messagebox_setting($attendance->emp_id, $today),
                        'setting_obj' => get_application_setting($attendance->emp_id)
                    ];
                    $name = Employee::find($attendance->emp_id)->name;
                    $http = $_SERVER['HTTP_HOST'];
                    $notification = array(
                        "body" => $name . ' marked official break ' . $http,
                        "title" => '' . 'Official Break' . ' Initiated',
                        "icon" => '',
                        "click_action" => 'https://hrm.allstartechnologies.co.uk/clockrequest/',
                    );
                    dispatch(new SendFirebaseNotification($notification));
                    return response()->json($response);
                }
            }
        } else {
            $response = [
                'attendance_id' => $attendance->id,
                'respString' => 'Working Time:',
                'emp_name' => Employee::find($attendance->emp_id)->name,
                'time_out' => $schedule->time_out,
                'respcode'  => '105',
                'clock' => get_worked_time($attendance->emp_id, $today),
                'messagebox' => get_messagebox_setting($attendance->emp_id, $today),
                'setting_obj' => get_application_setting($attendance->emp_id)
            ];
            return response()->json($response);
        }
    }
    public function check_out(Request $request)
    {
        $today = date('Y-m-d');
        $attendance = Attendance::with('check')->find($request->attendance_id);
        if ($attendance->attendance_date == $today) {
            
            if (!isset($attendance->check->leave_time) && isset($attendance->check->attendance_time)) {
                $check = Check::find($attendance->check->id);
                $check->leave_time = now();
                if ($check->save()) {
                    $response = [
                        'attendance_id' => $attendance->id,
                        'respString' => 'Checked out',
                        'emp_name' => Employee::find($attendance->emp_id)->name,
                        'respcode'  => '103',
                        'clock' => get_worked_time($attendance->emp_id, $today),
                        'messagebox' => get_messagebox_setting($attendance->emp_id, $today),
                        'setting_obj' => get_application_setting($attendance->emp_id)
                    ];
                    return response()->json($response);
                }
            }
        }
    }
    public function update_response(Request $request)
    {
        $today = date('Y-m-d');
        $attendance = Attendance::where('emp_id', $request->emp_id)->where('attendance_date', $today)->with('check')->with('clockout')->first();
        $user_id = get_user_id($request->emp_id);
        $schedule = get_schedule_time($user_id);
        if (isset($attendance)) {
            if (!isset($attendance->check->leave_time)) {
                if ($attendance->state == 1) {
                    $appresponse = AppResponse::where('emp_id', $request->emp_id)->where('attendance_id', $attendance->id)->first();
                    if (!isset($appresponse->id)) {
                        $appresponse = new AppResponse();
                        $appresponse->emp_id = $request->emp_id;
                        $appresponse->attendance_id = $attendance->id;
                        $appresponse->host_name = $request->host_name;
                        $appresponse->ip_address = $request->ip_address;
                        $appresponse->response_update = date('Y-m-d H:i:s');
                    } else {
                        $appresponse->host_name = $request->host_name;
                        $appresponse->ip_address = $request->ip_address;
                        $appresponse->response_update = date('Y-m-d H:i:s');
                    }
                    $appresponse->save();
                    $response = [
                        'attendance_id' => $attendance->id,
                        'respString' => 'Working Time:',
                        'emp_name' => Employee::find($request->emp_id)->name,
                        'time_out' => $schedule->time_out,
                        'respcode'  => '105',
                        'clock' => get_worked_time($request->emp_id, $today),
                        'messagebox' => get_messagebox_setting($request->emp_id, $today),
                        'setting_obj' => get_application_setting($request->emp_id)
                    ];
                    return response()->json($appresponse);
                } else {
                    $response = [
                        'attendance_id' => $attendance->id,
                        'respString' => 'Clock out',
                        'emp_name' => Employee::find($request->emp_id)->name,
                        'respcode'  => '102',
                        'clock' => get_worked_time($request->emp_id, $today),
                        'messagebox' => get_messagebox_setting($request->emp_id, $today),
                        'setting_obj' => get_application_setting($request->emp_id)
                    ];
                    return response()->json($response);
                }
            } else {
                $response = [
                    'attendance_id' => $attendance->id,
                    'respString' => 'Checked out',
                    'emp_name' => Employee::find($request->emp_id)->name,
                    'respcode'  => '103',
                    'clock' => get_worked_time($request->emp_id, $today),
                    'messagebox' => get_messagebox_setting($request->emp_id, $today),
                    'setting_obj' => get_application_setting($request->emp_id)
                ];
                return response()->json($response);
            }
        } else {
            $response = [
                'respString' => 'Checkin Missing',
                'emp_name' => Employee::find($request->emp_id)->name,
                'respcode'  => '101',
                'clock' => date('H:i:s'),
                'setting_obj' => get_application_setting($request->emp_id)
            ];
            return response()->json($response);
        }
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
        $check = new Check();
        $check->attendance_id  = $attendence->id;
        $check->emp_id = $data['emp_id'];
        $check->attendance_time = $data['attendance_time'];
        $check->save();
        return $attendence->id;
    }
    // --------Screen Short Save Dummy Function-------------- 
    public function screenShort(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads');
            return response()->json(['path' => $path], 200);
        } else {
            return response()->json(['message' => 'File not found.'], 404);
        }
    }
}
