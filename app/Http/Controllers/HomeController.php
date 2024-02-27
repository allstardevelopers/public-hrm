<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = User::find(auth()->user()->id);
        $slug = get_user_role(auth()->user()->id);
        if ($slug == 'admin') {
            return redirect()->route('admin');
        }
        if ($slug == 'employee') {
            $emp_id = get_emp_id(auth()->user()->id);
            $today = date('Y-m-d');
            $data = Attendance::where('attendance_date', $today)->with('employee')->where('emp_id', $emp_id)->first();
            $data= json_decode($data);
            if(isset($data->attendance_date))
            {
                return redirect()->route('dashboard');
            }
            else { 
                 $employee= Employee::find($emp_id);
                if($employee->tracking==0 && $employee->status==1)
                {
                    return redirect()->route('checkin');
                } elseif($employee->tracking==1){
                    return redirect()->route('checkinerror');
                } elseif($employee->status==0 || $employee->status==2 ){
                    return view('check-in.account-block');

                }
            }
        }
    }
    public function policies_view($policy)
    {
        $setting = Setting::where('setting_name', $policy)->first();
        // echo json_encode($setting);exit;
        if(isset($setting->setting_value))
        {
            $file_name=$setting->setting_value;
        } else {
            $file_name='test.pdf';
        }
        if($policy=='policy_file')
        {
            $page_title='Company Policies';
        } elseif($policy=='leave_policy_file') {

            $page_title='Leave Policies';
        }
        return view('admin.policies')->with(['page_title' =>$page_title ,'policy_file_name'=>$file_name]);
    }
    public function accept_policies(Request $request)
    {
        return redirect()->route('dashboard');
    }
}
