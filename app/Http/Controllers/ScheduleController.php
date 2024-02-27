<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Http\Requests\ScheduleEmp;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    public function index()
    {
        return view('admin.schedule')->with('schedules', Schedule::all());
    }
    public function store(ScheduleEmp $request)
    {
        $request->validated();
        $schedule = new schedule;
        $schedule->slug = $request->slug;
        $schedule->time_in = $request->time_in;
        $schedule->time_out = $request->time_out;
        $schedule->friday_break = $request->friday_break;
        $schedule->otherday_break = $request->otherday_break;
        $schedule->halfday_break = $request->halfday_break;
        $schedule->save();
        flash()->success('Success', 'Schedule has been created successfully !');
        // return redirect()->route('schedule.index');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request['time_in'] = str_split($request->time_in, 5)[0];
        $request['time_out'] = str_split($request->time_out, 5)[0];
        $schedule->slug = $request->slug;
        $schedule->time_in = $request->time_in;
        $schedule->time_out = $request->time_out;
        $schedule->friday_break = $request->friday_break;
        $schedule->otherday_break = $request->otherday_break;
        $schedule->halfday_break = $request->halfday_break;
        if($schedule->save()){
            // flash()->success('Success', 'Schedule has been Updated successfully !');
            // return redirect()->route('schedule.index');
            echo 'success';
        }   
    }
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        flash()->success('Success', 'Schedule has been deleted successfully !');
        return redirect()->route('schedule.index');
    }
}
