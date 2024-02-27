<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;

class CheckController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $monthNumber = $request->month_id;
            $year = $request->year;
            $date = Carbon::createFromDate($year, $monthNumber, 1);
            $today = $date->format('Y-m-d');

            return view('admin.check')->with(['employees' => Employee::where('id', $request->emp_id)->get(), 'all_employees' => Employee::orderBy('name', 'asc')->where('status', '1')->get(), 'today' => $today, 'emp_id' => $request->emp_id, 'month_id' => $request->month_id, 'syear' => $request->year]);
        } else {
            $today = today();
            return view('admin.check')->with(['employees' => Employee::where('status', '1')->get(), 'all_employees' => Employee::orderBy('name', 'asc')->where('status', '1')->get(), 'today' => $today]);
        }
    }
    public function employeeCheck()
    {
        $today = today();
        $emp_id = get_emp_id(auth()->user()->id);
        $employee[0] = Employee::find($emp_id);
        return view('admin.check')->with(['employees' => $employee, 'all_employees' => Employee::where('status', '1')->get(), 'today' => $today]);
    }

    public function CheckStore(Request $request)
    {
        if (isset($request->attd)) {
            foreach ($request->attd as $keys => $values) {
                foreach ($values as $key => $value) {
                    if ($employee = Employee::whereId(request('emp_id'))->first()) {
                        if (
                            !Attendance::whereAttendance_date($keys)
                                ->whereEmp_id($key)
                                ->whereType(0)
                                ->first()
                        ) {
                            $data = new Attendance();

                            $data->emp_id = $key;
                            $emp_req = Employee::whereId($data->emp_id)->first();
                            $data->attendance_time = date('H:i:s', strtotime($emp_req->schedules->first()->time_in));
                            $data->attendance_date = $keys;

                            // $emps = date('H:i:s', strtotime($employee->schedules->first()->time_in));
                            // if (!($emps >= $data->attendance_time)) {
                            //     $data->status = 0;

                            // }
                            $data->save();
                        }
                    }
                }
            }
        }
        if (isset($request->leave)) {
            foreach ($request->leave as $keys => $values) {
                foreach ($values as $key => $value) {
                    if ($employee = Employee::whereId(request('emp_id'))->first()) {
                        if (
                            !Leave::whereLeave_date($keys)
                                ->whereEmp_id($key)
                                ->whereType(1)
                                ->first()
                        ) {
                            $data = new Leave();
                            $data->emp_id = $key;
                            $emp_req = Employee::whereId($data->emp_id)->first();
                            $data->leave_time = $emp_req->schedules->first()->time_out;
                            $data->leave_date = $keys;
                            // if ($employee->schedules->first()->time_out <= $data->leave_time) {
                            //     $data->status = 1;

                            // }
                            // 
                            $data->save();
                        }
                    }
                }
            }
        }
        flash()->success('Success', 'You have successfully submited the attendance !');
        return back();
    }
    public function sheetReport()
    {

        return view('admin.sheet-report')->with(['employees' => Employee::all()]);
    }
    public function emp_sheetReport()
    {
        $emp_id = get_emp_id(auth()->user()->id);
        $employee[0] = Employee::find($emp_id);
        return view('admin.sheet-report')->with(['employees' => $employee]);
    }
}
