<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClockOut;
use App\Models\Overtime;
use App\Models\Check;
use App\Models\Latetime;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClockController extends Controller
{
    public function index()
    {
        return view('admin.clockout')->with(['clockouts' => ClockOut::with('employee')->with('attendance')->get()]);
    }
    public function clockindex($id)
    {
        $id = decrypt($id);
        $clockout = ClockOut::where('attendance_id', $id)->with('employee')->with('attendance')->get();
        $attendance = Attendance::with('employee')->with('clockout')->find($id);
        return view('admin.clockout')->with(['attendance' => $attendance, 'clockouts' => $clockout]);
    }
    public function clockrequest()
    {
        $clockout = ClockOut::where('type', '1')->where('status', '0')->with('employee')->with('attendance')->get();
        return view('admin.clockrequest')->with(['clockouts' => $clockout]);
    }
    public function approveClockout($id)
    {
        $clockout = ClockOut::find($id);
        $clockout->type = 1;
        $clockout->status = 1;
        if ($clockout->save()) {
            echo 'true';
        }
    }
    public function disapproveClockout(Request $request, $id)
    {
        $clockout = ClockOut::find($id);
        $clockout->type = 0;
        $clockout->status = 0;
        $clockout->remarks = $request->input('remarks');
        if ($clockout->save()) {
            echo 'true';
        }
    }

    public function updateCheckInOut(Request $request)
    {
        $attendance = Attendance::find($request->attendance_id);
        $attendance_date = date('Y-m-d', strtotime($attendance->attendance_date));

        $time_in = date('H:i:s', strtotime($request->time_in));
        // $time_out = date('H:i:s', strtotime($request->time_out));

        $dateAndTime = $attendance_date . ' ' . $request->time_in;
        $dateAndTimeOut = $attendance_date . ' ' . $request->time_out;


        $attendance->attendance_time = $time_in;
        $attendance->status = $request->status;

        $latetime = Latetime::where('attendance_id', $request->attendance_id)->first();
        if($latetime){
            $latetime->update_status = 1;
            $latetime->updated_by = auth()->user()->id;
        }

        $checks = Check::where('attendance_id', $request->attendance_id)->first();
        $checks->attendance_time = $dateAndTime;

        $today = date('Y-m-d');
        if ($attendance->attendance_date!=$today) {
            $checks->leave_time = $dateAndTimeOut;
        }
        if ($checks->save()) {
            if($latetime){
                $latetime->save();
            }
            $attendance->save();
            echo json_encode('success');
        }
        else {
            echo json_encode('failed');
        }
    }
}
