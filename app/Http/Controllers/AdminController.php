<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Latetime;
use App\Models\Attendance;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        //Dashboard statistics 
        $user = User::find(auth()->user()->id);
        $totalEmp =  count(Employee::where('status', '1')->get());
        $AllAttendance = count(Attendance::whereAttendance_date(date("Y-m-d"))->get());
        $ontimeEmp = count(Attendance::whereAttendance_date(date("Y-m-d"))->whereStatus('1')->get());
        $latetimeEmp = count(Attendance::whereAttendance_date(date("Y-m-d"))->whereStatus('0')->get());

        if ($AllAttendance > 0) {
            $percentageOntime = str_split(($ontimeEmp / $AllAttendance) * 100, 4)[0];
        } else {
            $percentageOntime = 0;
        }

        $data = [$totalEmp, $ontimeEmp, $latetimeEmp, $percentageOntime];
        // UpcomingEvent
        return view('admin.index')->with(['employees' => Employee::where('status', '1')->get(), 'data' => $data, 'upcoming_events' => getUpcomingEvents()]);
    }
    public function searchindex()
    {
        echo 'httpd search index';
    }
    public function systemSetting()
    {
        return view('admin.setting');
    }
    public function insertToken()
    {
        $token = Token::where('token_id', $_GET['token_id'])->first();
        if (isset($token->token_id)) {
            echo 'Token already exist';
        } else {
            $token = new Token();
            $token->token_id = $_GET['token_id'];
            $token->user_id = auth()->user()->id;
            if ($token->save()) {
                echo 'Token inserted';
            }
        }
    }
}
