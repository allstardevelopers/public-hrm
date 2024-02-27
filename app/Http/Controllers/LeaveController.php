<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\FingerDevices;
use App\Helpers\FingerHelper;
use App\Models\Leave;
use App\Http\Requests\AttendanceEmp;
use App\Models\Attendance;
use App\Models\Check;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LeaveController extends Controller
{
    public function index()
    {
        return view('admin.leave')->with(['leaves' =>
        Leave::with('employee')
        ->whereHas('employee', function ($query) {
            $query->where('status', 1);
        })
        ->get(),
          'employees'=>Employee::where('status', '1')->get()]);
    }
    public function requestIndex()
    {
        return view('admin.leave')->with(['leaves' => Leave::where('emp_id', get_emp_id(auth()->user()->id))->get()]);
    }
    public function indexOvertime()
    {
        return view('admin.overtime')->with(['overtimes' => Overtime::all()]);
    }
    public function addLeave(Request $request)
    {
        // Leave Add By Admin---------------
        $leave = new Leave();
        $leave->uid =  auth()->user()->id;
        $leave->emp_id = $request->emp_id;
        $leave->leave_time = $request->check_out;
        if($request->leave_type==1)
        {
            $leave->leave_date = $request->leave_date;
        } else {
            $leave->leave_date = $request->leave_date;
        }
        $leave->type = $request->leave_type;
        $leave->leave_reason = $request->leave_reason;
        $leave->state = 1;
        if ($leave->save()) {
            echo 'success';
        }
    }

    public function leaveRequest(Request $request)
    {
        // type 1 for Half Leave and 2 for Full Day Leave
        // State 1 Approved By HR
        // 
        $leave = new Leave();
        $leave->uid =  auth()->user()->id;
        $leave->emp_id = get_emp_id(auth()->user()->id);
        $leave->leave_time = $request->check_out;
        if($request->leave_type==1)
        {
            $leave->leave_date = $request->leave_date;
        } else {
            $leave->leave_date = $request->leave_date;
        }
        $leave->type = $request->leave_type;
        $leave->leave_reason = $request->leave_reason;
        if ($leave->save()) {
            echo 'success';
        }
    }
    public function leaveAction(Request $request)
    {
        $leave = Leave::find($request->leave_id);
        $leave->uid =  auth()->user()->id;
        $leave->leave_reason = $request->leave_reason;
        if ($request->action == 'approve') {
            if ($leave->type == 1) {
                $attendance = Attendance::where('attendance_date', $leave->leave_date)->with('check')->first();
                if (isset($attendance->id)) {
                    $leave->leave_time = $request->check_out;
                    $check=Check::find($attendance->check->id);
                    $check->leave_time = date('Y-m-d H:i:s', strtotime("$leave->leave_date $request->check_out")); 
                    $leave->state = 1;
                    if ($leave->save()) {
                        // $check->save();
                        echo 'Approved';
                    }
                }
            } elseif ($leave->type == 2) {
                $leave->leave_date = $request->leave_date;
                $leave->state = 1;
                if ($leave->save()) {
                    echo 'Approved';
                }
            } elseif ($leave->type == 3) {
                // $leave->leave_date = $request->leave_date;
                $leave->state = 1;
                if ($leave->save()) {
                    echo 'Approved';
                }
            }
        } else {
            $leave->status = 0;
            $leave->state = 0;
            if ($leave->save()) {
                echo 'Dissapproved';
            }
        }
    }
    
    public static function overTimeDevice($att_dateTime, Employee $employee)
    {

        $attendance_time = new DateTime($att_dateTime);
        $checkout = new DateTime($employee->schedules->first()->time_out);
        $difference = $checkout->diff($attendance_time)->format('%H:%I:%S');

        $overtime = new Overtime();
        $overtime->emp_id = $employee->id;
        $overtime->duration = $difference;
        $overtime->overtime_date = date('Y-m-d', strtotime($att_dateTime));
        $overtime->save();
    }
    public function destroy(Leave $leave){
        $leave->delete();
        echo true;
    }
}
