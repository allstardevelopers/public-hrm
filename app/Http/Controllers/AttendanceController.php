<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Employee;
use App\Models\Latetime;
use App\Models\Check;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AttendanceEmp;
use App\Models\Leave;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //show attendance 
    public function index()
    {
        $leaves = Leave::orderBy('leave_date', 'desc')
        ->whereMonth('leave_date', date('m'))
        ->whereYear('leave_date', date('Y'))
        ->with('employee')
        ->whereHas('employee', function ($query) {
            $query->where('status', 1);
        })->get();
        return view('admin.attendance')->with(['attendances' => Attendance::orderBy('attendance_date', 'desc')->whereMonth('created_at', date('m'))->with('employee')->with('check')->with('clockout')->get(), 'employees' => Employee::orderBy('name', 'asc')->where('status', '1')->get(), 'total_count' => count(Employee::where('status', '1')->get()) , 'leaves'=>$leaves]);
    }
    //Search Employee Monthly Attendance With 
    public function searchindex(Request $request)
    {

        return view('admin.attendance')->with(['attendances' => Attendance::orderBy('attendance_date', 'desc')->where('emp_id', $request->emp_id)->whereMonth('created_at', $request->month_id)->whereYear('created_at', $request->year)->with('employee')->with('check')->with('clockout')->get(), 'employees' => Employee::where('status', '1')->get(), 'emp_id' => $request->emp_id, 'month_id' => $request->month_id, 'syear' => $request->year]);
    }

    public function searchCheckAttendanceSheet(Request $request)
    {
        return view('admin.check')->with(['employees' => Employee::where('id', $request->emp_id)->whereMonth('created_at', $request->month_id)]);

        // return view('admin.check_attendance_search')->with(['attendances' => Attendance::orderBy('attendance_date', 'desc')->where('emp_id', $request->emp_id)->whereMonth('created_at', $request->month_id)->with('employee')->with('check')->with('clockout')->get(), 'employees' => Employee::where('status', '1')->get(), 'emp_id' => $request->emp_id, 'month_id' => $request->month_id]);
    }
    // Show Report Of Employees
    public function reportindex($id, $month, $year) 
    {
        $id = decrypt($id);
        $attendance = Attendance::where('emp_id', $id)->whereMonth('created_at', $month)->whereYear('created_at', $year)->with('employee')->with('check')->with('clockout')->get();
        $leave = Leave::where('emp_id', $id)->whereMonth('Leave_date', $month)->whereYear('Leave_date', $year)->get();
        return view('admin.employee_report')->with(['employees' => Employee::where('status', '1')->get(), 'attendances' => $attendance, 'leaves' => $leave, 'emp_id' => $id, 'month_id' => $month, 'syear' => $year]);
    }

    public function teamReportIndex($id, $month, $year)
    {
        $id = decrypt($id);
        $attendance = Attendance::where('emp_id', $id)->whereMonth('created_at', $month)->with('employee')->with('check')->with('clockout')->get();
        $leave = Leave::where('emp_id', $id)->whereMonth('Leave_date', $month)->get();
        $team = getUsersInTeam(auth()->user()->id);
        return view('employee.team_report')->with(['employees' => $team, 'attendances' => $attendance, 'leaves' => $leave, 'emp_id' => $id, 'month_id' => $month, 'syear' => $year]);
    }
    // Show Today attendance
    public function todayindex()
    {
        $today = date('Y-m-d');
        $employees = Employee::orderBy('name', 'asc')->where('status', '1')->get();
        $attendance = Attendance::where('attendance_date', $today)->orderBy('attendance_date', 'desc')->with('employee')->with('check')->with('clockout')->get();
        return view('admin.today-attendance')->with(['attendances' => Attendance::where('attendance_date', $today)->orderBy('attendance_date', 'desc')->with('employee')->with('check')->with('clockout')->get(), 'employees'=>$employees]);
    }
    //show an amployee attendence
    public function employeeindex($id)
    {
        return view('admin.attendance')->with(['attendances' => Attendance::where('emp_id', $id)->with('employee')->with('check')->with('clockout')->get()]);
    }
    //
    //show late times
    public function indexLatetime()
    {
        // return view('admin.latetime')->with(['latetimes' => Latetime::orderBy('id', 'asc')->get()]);
        return view('admin.latetime')->with([
            'latetimes' => Latetime::whereHas('attendance', function ($query) {
                $query->where('status', 0);
            })
                ->orderBy('id', 'asc')
                ->get()
        ]);
    }
    //emplyee personal attendence view.
    public function emp_attendence()
    {
        $emp_id = get_emp_id(auth()->user()->id);
        return view('admin.attendance')->with(['attendances' => Attendance::with('employee')->with('check')->where('emp_id', $emp_id)->get()]);
    }
    public function indexempLatetime()
    {
        // $emp_id = get_emp_id(auth()->user()->id);
        // return view('admin.latetime')->with(['latetimes' => Latetime::with('employee')->with('attendance')->where('emp_id', $emp_id)->get()]);
        return view('admin.latetime')->with([
            'latetimes' => Latetime::whereHas('attendance', function ($query) {
                $emp_id = get_emp_id(auth()->user()->id);
                $query->where('status', 0)->where('emp_id', $emp_id);
            })->orderBy('id', 'asc')->get()
        ]);
    }


    public static function lateTimeDevice($att_dateTime, Employee $employee)
    {
        $attendance_time = new DateTime($att_dateTime);
        $checkin = new DateTime($employee->schedules->first()->time_in);
        $difference = $checkin->diff($attendance_time)->format('%H:%I:%S');

        $latetime = new Latetime();
        $latetime->emp_id = $employee->id;
        $latetime->duration = $difference;
        $latetime->latetime_date = date('Y-m-d', strtotime($att_dateTime));
        $latetime->save();
    }
}
