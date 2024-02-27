<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Check;
use App\Models\Employee;
use App\Models\EmployeeSetting;
use App\Models\Latetime;
use App\Models\Leave;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;


class AjaxController extends Controller
{
    public function latetime_data($id)
    {
        $latetime = Latetime::where('id', $id)->with('attendance')->with('employee')->first();
        return $latetime;
    }
    public function updateInOutTime($id)
    {
        $data['check'] = Check::where('attendance_id', $id)->first();
        $data['attendance'] = Attendance::find($id);
        
        $data['employee'] = Employee::find($data['check']->emp_id);
        $data['late_time'] = Latetime::where('attendance_id', $id)->first();

        return $data;
    }

    public function leave_data($id)
    {
        $leave = Leave::where('id', $id)->with('employee')->first();
        return $leave;
    }
    public function employee_data($id)
    {
        $employee = Employee::find($id);
        return $employee;
    }
    public function schedule_data($id)
    {
        $schedule = Schedule::find($id);
        return $schedule;
    }
    function get_data($id, $view)
    {
        // echo $view;exit;
        if ($view == 'update_user') {
            $user = User::with('roles')->find($id);
            $roles = Role::get();
            $data = array(
                'user' => $user,
                'roles' => $roles,
            );
            return $user;
        }
        if ($view = 'team_details') {
            $user = User::with('roles')->find($id);

            return 'success';
        }
    }
    public function ajax_modal_content($ajaxbody)
    {
        return view('ajax.update_check_in');
    }
    public function upload_policy_file(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf',
        ]);
        $file = $request->file('file');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $setting = Setting::where('setting_name', $request->setting_name)->first();
        if (!isset($setting->setting_name)) {

            $setting = new Setting;
            $setting->setting_name = $request->setting_name;
            $setting->setting_value = $filename;
            $setting->updated_by = auth()->user()->id;
            if ($setting->save()) {
                // $file->storeAs('public/assets/documents/', $filename);
                $file->move(public_path('storage/assets/documents'), $filename);
                return response()->json(['success' => true, 'filename' => $filename]);
            }
        } else {
            $setting->setting_name = $request->setting_name;
            $setting->setting_value = $filename;
            $setting->updated_by = auth()->user()->id;
            if ($setting->save()) {
                // $file->storeAs('public/assets/documents/', $filename);
                $file->move(public_path('storage/assets/documents'), $filename);
                return response()->json(['success' => true, 'filename' => $filename]);
            }
        }
    }
    function employee_setting(Request $request)
    {
        $empSetting = EmployeeSetting::where(['emp_id' => $request->emp_id, 'setting_name' => $request->setting_name])->first();
        if (!isset($empSetting->setting_name)) {
            $empSetting = new EmployeeSetting;
            $empSetting->emp_id = $request->emp_id;
            $empSetting->setting_name = $request->setting_name;
            $empSetting->setting_value = $request->setting_value;
            $empSetting->updated_by = auth()->user()->id;
            if ($empSetting->save()) {
                return response()->json(['success' => true]);
            }
        } else {
            $empSetting->setting_name = $request->setting_name;
            $empSetting->setting_value = $request->setting_value;
            $empSetting->updated_by = auth()->user()->id;
            if ($empSetting->save()) {
                return response()->json(['success' => true]);
            }
        }
    }
    public function Update_Check_In()
    {
        $latetime = Latetime::where('id', $_POST['late_id'])->first();
        $attendance = Attendance::find($latetime->attendance_id);
        $check = Check::where('attendance_id', $latetime->attendance_id)->first();
        $user_id = get_user_id($latetime->emp_id);
        $data = get_schedule_time($user_id);
        $attendance->attendance_time = $_POST['check_in'];
        $attendance->status = 1;
        $attendance->save();
        $check->attendance_time = date('Y-m-d H:i:s', strtotime($attendance->attendance_date . ' ' . $attendance->attendance_time));
        if ($check->save()) {
            $latetime->update_status = 1;
            $latetime->updated_by = auth()->user()->id;
            $latetime->save();
            echo 'success';
        }
    }
}
