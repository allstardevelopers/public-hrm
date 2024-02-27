<?php

use App\Jobs\SendFirebaseNotification;
use App\Models\AppResponse;
use App\Models\Attendance;
use App\Models\ClockOut;
use App\Models\Employee;
use App\Models\Latetime;
use App\Models\Leave;
use App\Models\Token;
use App\Models\EmployeeSetting;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Carbon\Carbon;
use App\Models\UpcomingEvent;
use Illuminate\Support\Facades\DB;

$currentyear = \Carbon\Carbon::now()->year;
function get_user_role($id)
{
    $data = DB::table('users')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->select('users.id', 'users.name', 'roles.slug')
        ->where('users.id', '=', $id)
        ->first();
    return $data->slug;
}
function checktracking($emp_id)
{
    $employee = Employee::find($emp_id);
    // dd($employee);
    if ($employee->tracking == 1 && $employee->status == 1) {
        return redirect()->route('checkinerror');
        die;
    } elseif ($employee->status == 0 || $employee->status == 2) {
        return redirect()->route('account-block');
    }
}

function getScheduleByEmployee($attendance)
{
    $cin = $attendance->employee->schedules->first();

    $shift_end_time = Carbon::createFromTimestamp(strtotime($cin->time_out));
    $shift_start_time = Carbon::createFromTimestamp(strtotime($cin->time_in));

    // Format the date as per your requirements
    $shift_end_time = $shift_end_time->format('H:i:s');
    $shift_start_time = $shift_start_time->format('H:i:s');


    // echo "Formatted Time: " . $formattedTime . "<br>"; // Debugging: Print the formatted time

    return array('shift_start_time' => $shift_start_time, 'shift_end_time' => $shift_end_time);
}

function getLastRole($userId)
{
    $user = User::findOrFail($userId);
    $role = $user->roles->last();
    return $role;
}
function countCheckdIn()
{
    return count(Attendance::whereAttendance_date(date("Y-m-d"))->get());
}

// function getUsersInTeam($userId)
// {
//     $secondRole = getLastRole($userId);

//     if ($secondRole->slug != 'employee' || $secondRole->slug != 'admin') {
//         $managerId = $userId;
//         $manager = User::with(['teams' => function ($query) {
//             $query->where('is_manager', 1);
//         }])->find($managerId);

//         if ($manager) {
//             $managerTeams = $manager->teams;

//             // Let's assume there's only one manager team
//             if ($managerTeams->count() > 0) {
//                 $managerTeam = $managerTeams->first();

//                 $teamMembers = $managerTeam->members;
//                 return $teamMembers;
//                 // Now $teamMembers contains the team members of the manager's assigned team
//                 // You can loop through $teamMembers and access their attributes
//             }
//         } else {
//             // Manager not found
//             return false;
//         }
//     }
// }

function getUsersInTeam($userId)
{
    $secondRole = getLastRole($userId);

    if ($secondRole->slug != 'employee' || $secondRole->slug != 'admin') {
        $managerId = $userId;
        $manager = User::with(['teams' => function ($query) {
            $query->where('is_manager', 1);
        }])->find($managerId);

        if ($manager) {
            $managerTeams = $manager->teams;

            // Let's assume there's only one manager team
            if ($managerTeams->count() > 0) {
                $managerTeam = $managerTeams->first();

                // Modified query to filter users with status = 1
                $teamMembers = $managerTeam->members()->where('status', 1)->get();
                return $teamMembers;
                // Now $teamMembers contains the team members of the manager's assigned team
                // You can loop through $teamMembers and access their attributes
            }
        } else {
            // Manager not found
            return false;
        }
    }
}
function get_roles()
{
    $roles = Role::get();
    return $roles;
}

function get_permissions()
{
    $permission = Permission::get();
    return $permission;
}

function removeUndersquareCapitalize($permission_name)
{
    $permission = str_replace('_', ' ', $permission_name);

    return $permission;
}
function employee_details($emp_id)
{
    $employee = Employee::find($emp_id);
    // $employee = Employee::where('id', $emp_id)->where('status', 0)->first();
    return $employee;
}

function isEmployeeResigned($employee)
{
    return $employee['status'] != 1 ? ($employee['status'] == 0 ? '<span class="badge badge-success badge-pill float-right">Not Submitted <span>' : '<span class="badge badge-danger badge-pill float-right">Resigned<span>') : '';
}

function getEmplLeaves($emp_id, $leave_date)
{
    $leaves = Attendance::where(DB::raw('DATE(attendance_date)'), $leave_date)
        ->where('emp_id', $emp_id)
        ->get();

    return $leaves;
}
function pd($data, $d = true)
{
    echo '<pre>';
    print_r(json_decode($data));
    if ($d) {
        die;
    }
}

function isItEmployeeBirthday($employee)
{
    if ($employee->dob != null) {
        // Get the current date
        $currentDate = Carbon::now();
        // Replace "1994-04-15" with the actual date of birth
        $dateOfBirth = Carbon::createFromFormat('Y-m-d', $employee->dob);
        // Compare month and day of the dates (ignoring the year)
        $isBirthdayToday = ($currentDate->month == $dateOfBirth->month) && ($currentDate->day == $dateOfBirth->day);
        return $isBirthdayToday;
    }
    return 0;
}
function getEmployeesWithBirthdayToday()
{
    // Get the current date
    $currentDate = Carbon::now();
    $employeesWithBirthdayToday = [];
    $employees = Employee::where('status', '1')->get();

    foreach ($employees as $employee) {
        if ($employee->dob != null) {
            // Replace "1994-04-15" with the actual date of birth
            $dateOfBirth = Carbon::createFromFormat('Y-m-d', $employee->dob);
            // Compare month and day of the dates (ignoring the year)
            $isBirthdayToday = ($currentDate->month == $dateOfBirth->month) && ($currentDate->day == $dateOfBirth->day);
            if ($isBirthdayToday) {
                $employeesWithBirthdayToday[] = $employee;
            }
        }
    }

    return $employeesWithBirthdayToday;
}

function formatBirthday($day)
{
    // Get the current year
    $currentYear = date('Y');
    $day = Carbon::parse($day);
    // Calculate the birthday for the current year based on the provided day
    $birthday = Carbon::create($currentYear, $day->month, $day->day);

    // Format and return the dates
    $fullFormat = $birthday->format('l, d F'); // Thursday, 10 August
    $shortFormat = $birthday->format('d M Y'); // 10 August 2005
    return $fullFormat;
}


function getUpcomingEvents()
{
    $res = UpcomingEvent::where('status', '0')
        ->whereDate('event_held_on', '>=', Carbon::today()->toDateString())
        ->get();
    if (count($res) > 0) {
        return $res;
        die;
    }
    return false;
}

function checkEvents($event_date, $event_type)
{
    $res = UpcomingEvent::where('status', '0')->where('event_type', $event_type)
        ->where('event_held_on', $event_date)
        ->get();
    if (count($res) > 0) {
        return $res;
    }
    return false;
}

function getUpcomingBirthdays()
{
    $upcomingStart = Carbon::today();
    $upcomingEnd = Carbon::today()->addDays(7); // Check for birthdays in the next 30 days

    $upcomingBirthdays = Employee::whereMonth('dob', '>=', $upcomingStart->month)
        ->where('status', '1')
        ->whereDay('dob', '>=', $upcomingStart->day)
        ->whereMonth('dob', '<=', $upcomingEnd->month)
        ->whereDay('dob', '<=', $upcomingEnd->day)
        ->orderBy('dob', 'desc')
        ->get();
    if (count($upcomingBirthdays) > 0) {
        return $upcomingBirthdays;
        die;
    }
    return false;
}
function get_emp_id($id)
{
    $data = DB::table('users')
        ->join('employees', 'users.id', '=', 'employees.user_id')
        // ->select('users.id', 'users.name', 'roles.slug')
        ->where('users.id', '=', $id)
        ->first();
    return $data->id;
}

function get_user_id($id)
{
    $data = DB::table('employees')
        ->join('users', 'employees.user_id', '=', 'users.id')
        // ->select('users.id', 'users.name', 'roles.slug')
        ->where('employees.id', '=', $id)
        ->first();
    return $data->id;
}
function get_schedule_time($id)
{
    $data = DB::table('users')
        ->join('employees', 'users.id', '=', 'employees.user_id')
        ->join('schedule_employees', 'employees.id', '=', 'schedule_employees.emp_id')
        ->join('schedules', 'schedule_employees.schedule_id', '=', 'schedules.id')
        // ->select('users.id', 'users.name', 'roles.slug')
        ->where('users.id', '=', $id)
        ->first();
    return $data;
}
function sheet_report($id)
{
    $data = DB::table('attendances')
        ->join('checks', 'attendances.emp_id', '=', 'checks.emp_id')
        ->join('employees', 'attendances.emp_id', '=', 'employees.id')
        ->select('attendances.*', 'employees.*', 'checks.attendance_time', 'checks.leave_time')
        ->where('attendances.emp_id', '=', $id)
        ->get();
    return $data;
}
function get_worked_time($emp_id, $dateday)
{
    $attendance = Attendance::where('emp_id', $emp_id)->where('attendance_date', $dateday)->with('check')->latest('created_at')->first();
    $clockouts = Attendance::where('id', $attendance->id)->with('clockout')->with('check')->get()->first();
    $tclockout = 0;
    foreach ($clockouts->clockout as $key => $clockout) {
        $cout = strtotime($clockout->clock_out);
        if ($clockout->status == 0) {
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
    return $time;
}
function get_clockOut_time($emp_id, $dateday)
{
    $attendance = Attendance::where('emp_id', $emp_id)->where('attendance_date', $dateday)->with('check')->latest('created_at')->first();
    $clockouts = Attendance::where('id', $attendance->id)->with('clockout')->with('check')->get()->first();
    $tclockout = 0;
    foreach ($clockouts->clockout as $key => $clockout) {
        $cout = strtotime($clockout->clock_out);
        if ($clockout->status == 0 && $clockout->type == 0) {
            if (isset($clockout->clock_in)) {
                $cin = strtotime($clockout->clock_in);
            } else {
                $cin = strtotime(now());
                if (date('Y-m-d') == $attendance->attendance_date) {
                    $cin = strtotime(now());
                } else {
                    $cin = strtotime($attendance->employee->schedules->first()->time_out);
                    if ($cout < strtotime($attendance->employee->schedules->first()->time_out)) {
                        $cin = $cout;
                    }
                }
            }

            $diff = ($cin - $cout) / 60;
            $tclockout += $diff;
        }
    }
    return $tclockout;
}
// Working Hours Time stamp
function get_working_time($emp_id, $dateday)
{
    $today = date('Y-m-d');
    $attendance = Attendance::where('emp_id', $emp_id)->where('attendance_date', $dateday)->with('check')->latest('created_at')->first();
    $clockouts = Attendance::where('id', $attendance->id)->with('clockout')->with('check')->get()->first();
    $tclockout = 0;
    $workingtime = 0;
    foreach ($clockouts->clockout as $key => $clockout) {
        $cout = strtotime($clockout->clock_out);
        if ($clockout->status == 0) {
            if (isset($clockout->clock_in)) {
                $cin = strtotime($clockout->clock_in);
            } else {
                $cin = strtotime(now());
            }
            $diff = ($cin - $cout) / 60;
            $tclockout += $diff;
        }
    }
    if (isset($attendance->check->leave_time) && $today > $dateday) {
        $workingtime = (strtotime($attendance->check->leave_time) - strtotime($attendance->check->attendance_time)) / 60;
    } else if (!isset($attendance->check->leave_time) && $today > $dateday) {
        $workingtime = (strtotime($attendance->employee->schedules->first()->time_out) - strtotime($attendance->check->attendance_time)) / 60;
    } else if (!isset($attendance->check->leave_time) && $today == $dateday) {
        $workingtime = (strtotime(now()) - strtotime($attendance->check->attendance_time)) / 60;
    } else if (isset($attendance->check->leave_time) && $today == $dateday) {
        $workingtime = (strtotime($attendance->check->leave_time) - strtotime($attendance->check->attendance_time)) / 60;
    }
    $workingtime = ($workingtime - $tclockout) * 60;
    $time = gmdate("H:i:s", $workingtime);
    return $time;
}
// Work Time of employee on the base of Date Filter 
function get_workingHour_DateRange($id, $startDate, $endDate)
{
    $attendances = Attendance::orderBy('emp_id', 'desc')->where('emp_id', $id)->whereBetween('created_at', [$startDate, $endDate])->with('employee')->with('check')->with('clockout')->get();
    $totaltime = 0;
    $employee = [];
    $i = 0;
    foreach ($attendances as $attendance) {
        $totaltime += strtotime(get_worked_time($attendance->emp_id, $attendance->attendance_date));
        $employee[$i++] = array(
            'emp_id' => $attendance->emp_id,
            'emp_name' => $attendance->employee->name,
            'attendance_id' => $attendance->id,
            'attendance_date' => $attendance->attendance_date,
            'total_work_time' => get_worked_time($attendance->emp_id, $attendance->attendance_date)
        );
    }
    return $totaltime;
}
function get_workingHour_monthly($id, $month_id, $year = null)
{
    $year = ($year !== null) ? $year : \Carbon\Carbon::now()->year;
    $attendances = Attendance::where('emp_id', $id)->whereMonth('created_at', $month_id)->whereYear('created_at', $year)->get();
    $total_minutes = 0;
    foreach ($attendances as $attendance) {
        $dayhours = get_working_time($attendance->emp_id, $attendance->attendance_date);
        $daytime = explode(":", $dayhours);
        $total_minutes += $daytime[0] * 60;
        $total_minutes += $daytime[1];
    }
    $hours = floor($total_minutes / 60);
    $minutes = $total_minutes % 60;
    $format = sprintf('%d h %d m', $hours, $minutes);
    return $format;
}
function get_thisweek_hour($id)
{
    $attendances = Attendance::where('emp_id', $id)->where('created_at', '>', Carbon::now()->startOfWeek())->where('created_at', '<', Carbon::now()->endOfWeek())->get();
    $total_minutes = 0;
    foreach ($attendances as $attendance) {
        $dayhours = get_working_time($attendance->emp_id, $attendance->attendance_date);
        $daytime = explode(":", $dayhours);
        $total_minutes += $daytime[0] * 60;
        $total_minutes += $daytime[1];
    }
    $hours = floor($total_minutes / 60);
    $minutes = $total_minutes % 60;
    $format = sprintf('%d h %d m', $hours, $minutes);
    return $format;
}
function get_clockOutTime_monthly($emp_id, $month_id, $year = null)
{
    $year = ($year !== null) ? $year : \Carbon\Carbon::now()->year;
    $attendances = Attendance::where('emp_id', $emp_id)->whereMonth('created_at', $month_id)->whereYear('created_at', $year)->get();
    $total_minutes = 0;
    foreach ($attendances as $attendance) {
        $dailyclockout = get_clockOut_time($attendance->emp_id, $attendance->attendance_date);
        $total_minutes += $dailyclockout;
    }
    $hours = floor($total_minutes / 60);
    $minutes = $total_minutes % 60;
    $format = sprintf('%d h %d m', $hours, $minutes);
    return $format;
}
function lateCount($emp_id, $month_id, $year = null)
{
    $year = ($year !== null) ? $year : \Carbon\Carbon::now()->year;
    $latetime = Latetime::where(array('emp_id' => $emp_id, 'update_status' => 0))->whereMonth('created_at', $month_id)->whereYear('created_at', $year)->count();
    return $latetime;
}
function totalWorkDays($emp_id, $month_id, $year)
{
    $attendance = Attendance::where('emp_id', $emp_id)->whereMonth('created_at', $month_id)->whereYear('created_at', $year)->count();
    return $attendance;
}
function total_leavs_inMonth($emp_id, $month_id)
{
    $attendance = Leave::where('emp_id', $emp_id)->whereMonth('created_at', $month_id)->count();
}
function get_daily_hour($id, $dateday)
{
    $total_minutes = 0;
    $attendance = Attendance::where('emp_id', $id)->where('attendance_date', $dateday)->first();
    if (isset($attendance->id)) {
        $dayhours = get_working_time($id, $dateday);
        $daytime = explode(":", $dayhours);
        $total_minutes += $daytime[0] * 60;
        $total_minutes += $daytime[1];
    }
    $hours = floor($total_minutes / 60);
    $minutes = $total_minutes % 60;
    $format = sprintf('%d h %d m', $hours, $minutes);
    return $format;
}
function get_clockouts($emp_id, $dateday)
{
    $date = Carbon::parse($dateday);
    $user_id = get_user_id($emp_id);
    $schedule = get_schedule_time($user_id);
    $time_out = Carbon::parse($schedule->time_out);
    if ($date->dayOfWeek == Carbon::FRIDAY) {
        $breaktime = $schedule->friday_break ?? 75;
    } else {
        $breaktime = $schedule->otherday_break ?? 60;
    }
    $attendance = Attendance::where('emp_id', $emp_id)->where('attendance_date', $dateday)->first();
    if (isset($attendance->id)) {

        $clockout = get_clockOut_time($emp_id, $dateday);
        $remaning = $breaktime - $clockout;

        $break_spent = $clockout;
        $break_remainig = $remaning;
    }
    $clockouts = array(
        'break_spent' => $break_spent ?? 0,
        'break_remainig' => $break_remainig ?? $breaktime,
    );
    return $clockouts;
}
function last_seen_emploee($id, $dateday)
{
    $attendance = Attendance::where('emp_id', $id)->where('attendance_date', $dateday)->first();
    if (isset($attendance->id)) {
        $appresponse = AppResponse::where('attendance_id', $attendance->id)->first();
        if (isset($appresponse->id)) {
            $active = date("h:i a", strtoTime($appresponse->response_update));
            return 'Today at: ' . $active;
        } else {
            if ($attendance->check->leave_time) {
                $active = date("h:i a", strtoTime($attendance->check->leave_time));
                return 'Today at: ' . $active;
            } elseif ($attendance->check->attendance_time) {
                $active = date("h:i a", strtoTime($attendance->check->attendance_time));
                return 'Today at: ' . $active;
            } else {
                return 'Update App';
            }
        }
    } else {
        $appresponse = AppResponse::where('emp_id', $id)->orderBy('created_at', 'desc')->first();
        if (isset($appresponse->id)) {
            $active = date("M d, h:i a", strtoTime($appresponse->response_update));
            return $active;
        } else {
            $attendance = Attendance::where('emp_id', $id)->orderBy('attendance_date', 'desc')->with('check')->first();
            if (isset($attendance->id)) {
                if ($attendance->check->leave_time) {
                    $active = date("M d, h:i a", strtoTime($attendance->check->leave_time));
                } elseif ($attendance->check->attendance_time) {
                    $active = date("M d, h:i a", strtoTime($attendance->check->attendance_time));
                }
                return $active;
            } else {
                return 'No record';
            }
        }
    }
}
function employee_status($emp_id)
{
    $today = date('Y-m-d');
    $attendance = Attendance::where('emp_id', $emp_id)->where('attendance_date', $today)->with('check')->with('clockout')->first();
    $user_id = get_user_id($emp_id);
    $schedule = get_schedule_time($user_id);
    $currentTime = Carbon::now();
    $timeInCarbon = Carbon::parse($schedule->time_in);
    $twoHoursLater = $timeInCarbon->copy()->addHours(2);
    // dd($schedule);
    if (isset($attendance)) {
        if (!isset($attendance->check->leave_time)) {

            if ($attendance->state == 1) {
                return '<span class="badge badge-success badge-pill ">Clock in</span>';
            } else {

                return '<span class="badge badge-warning badge-pill ">On Break</span>';
            }
        } else {

            return '<span class="badge badge-info badge-pill">Checked out</span>';
        }
    } else {
        if ($currentTime->greaterThan($twoHoursLater)) {
            return '<span class="badge badge-danger badge-pill ">Absent</span>';
        } else {
            return '<span class="badge badge-danger badge-pill">Checkin Missing</span>';
        }
    }
}
// function get_leave_balance($emp_id, $year = null)
// {
//     $year = ($year !== null) ? $year : \Carbon\Carbon::now()->year;
//     $total = Leave::where('emp_id', $emp_id)->whereIn('type', [2, 3])->whereYear('created_at', $year)->count();
//     $approved = Leave::where('emp_id', $emp_id)->where('state', 1)->whereIn('type', [2, 3])->whereYear('created_at', $year)->count();
//     $half_leave = Leave::where('emp_id', $emp_id)->where('type', 1)->whereYear('created_at', $year)->count();
//     $cancelled = Leave::where('emp_id', $emp_id)->where('state', 0)->where('status', 0)->whereIn('type', [2, 3])->whereYear('created_at', $year)->count();
//     $pending = Leave::where('emp_id', $emp_id)->where('state', 0)->where('status', 1)->whereIn('type', [2, 3])->whereYear('created_at', $year)->count();
//     $leave = array(
//         'total' => $total,
//         'approved' => $approved,
//         'half' => $half_leave,
//         'canceled' =>  $cancelled,
//         'pending' =>  $pending,
//         'remaning' => 16 - ($total + round($half_leave / 2)),
//     );
//     return $leave;
// }

// function get_leave_balance($emp_id, $year = null)
// {
//     $year = ($year !== null) ? $year : \Carbon\Carbon::now()->year;


//     $total = Leave::where('emp_id', $emp_id)->whereIn('type', [2, 3])->whereYear('created_at', $year)->count();
//     $approved = Leave::where('emp_id', $emp_id)->where('state', 1)->whereIn('type', [2, 3])->whereYear('created_at', $year)->count();
//     $half_leave = Leave::where('emp_id', $emp_id)->where('type', 1)->whereYear('created_at', $year)->count();
//     $cancelled = Leave::where('emp_id', $emp_id)->where('state', 0)->where('status', 0)->whereIn('type', [2, 3])->whereYear('created_at', $year)->count();
//     $pending = Leave::where('emp_id', $emp_id)->where('state', 0)->where('status', 1)->whereIn('type', [2, 3])->whereYear('created_at', $year)->count();

//     // Calculate remaining leaves based on the policy
//     $remaining_full_leaves = 16 - $total - round($half_leave / 2);
//     $remaining_half_leaves = 2 - ($approved - round($pending / 2));

//     $leave = [
//         'total' => $total,
//         'approved' => $approved,
//         'half' => $half_leave,
//         'canceled' =>  $cancelled,
//         'pending' =>  $pending,
//         'remaning' => $remaining_full_leaves,
//         'remaining_half' => $remaining_half_leaves,
//     ];

//     return $leave;
// }
function get_leave_balance($emp_id, $year = null)
{
    $year = ($year !== null) ? $year : \Carbon\Carbon::now()->year;

    // Retrieve total leaves excluding holidays
    $total = Leave::where('leaves.emp_id', $emp_id)
        ->whereIn('leaves.type', [2, 3]) // Types 2 and 3 for full and half leaves
        ->whereYear('leaves.created_at', $year)
        ->leftJoin('upcoming_events', function ($join) {
            $join->on('leaves.leave_date', '=', 'upcoming_events.event_held_on')
                ->where('upcoming_events.event_type', 1); // Exclude holiday leaves
        })
        ->whereNull('upcoming_events.id')
        ->count();

    // Retrieve approved leaves excluding holidays
    $approved = Leave::where('leaves.emp_id', $emp_id)
        ->where('leaves.state', 1)
        ->whereIn('leaves.type', [2, 3]) // Types 2 and 3 for full and half leaves
        ->whereYear('leaves.created_at', $year)
        ->leftJoin('upcoming_events', function ($join) {
            $join->on('leaves.leave_date', '=', 'upcoming_events.event_held_on')
                ->where('upcoming_events.event_type', 1); // Exclude holiday leaves
        })
        ->whereNull('upcoming_events.id')
        ->count();

    // Retrieve half leaves excluding holidays
    $half_leave = Leave::where('leaves.emp_id', $emp_id)
        ->where('leaves.type', 1) // Type 1 for half leaves
        ->whereYear('leaves.created_at', $year)
        ->count();


    // Retrieve canceled leaves excluding holidays
    $cancelled = Leave::where('leaves.emp_id', $emp_id)
        ->where('leaves.state', 0)
        ->where('leaves.status', 0)
        ->whereIn('leaves.type', [2, 3]) // Types 2 and 3 for full and half leaves
        ->whereYear('leaves.created_at', $year)
        ->leftJoin('upcoming_events', function ($join) {
            $join->on('leaves.leave_date', '=', 'upcoming_events.event_held_on')
                ->where('upcoming_events.event_type', 1); // Exclude holiday leaves
        })
        ->whereNull('upcoming_events.id')
        ->count();

    // Retrieve pending leaves excluding holidays
    $pending = Leave::where('leaves.emp_id', $emp_id)
        ->where('leaves.state', 0)
        ->where('leaves.status', 1)
        ->whereIn('leaves.type', [2, 3]) // Types 2 and 3 for full and half leaves
        ->whereYear('leaves.created_at', $year)
        ->leftJoin('upcoming_events', function ($join) {
            $join->on('leaves.leave_date', '=', 'upcoming_events.event_held_on')
                ->where('upcoming_events.event_type', 1); // Exclude holiday leaves
        })
        ->whereNull('upcoming_events.id')
        ->count();

    // Calculate remaining leaves based on the policy
    // $remaining_full_leaves = 16 - $total - round($half_leave / 2);
    // $remaining_half_leaves = 2 - ($approved - round($pending / 2));
    // Calculate remaining leaves based on the policy
    // echo "Getting half_leave: " . ceil($half_leave);
    // die;
    // $remaining_full_leaves = 16 - $total - ceil($half_leave / 2);
    $remaining_full_leaves = 16 - $total;
    $remaining_half_leaves = 2 - ($approved - ceil($pending / 2));


    $leave = [
        'total' => $total,
        'approved' => $approved,
        'half' => $half_leave,
        'canceled' =>  $cancelled,
        'pending' =>  $pending,
        'remaning' => $remaining_full_leaves,
        'remaining_half' => $remaining_half_leaves,
    ];

    return $leave;
}

function update_response_table()
{
    echo 'nothing';
}

function count_clockout_requests($ddate)
{
    return count(ClockOut::where('type', '1')->where('status', '0')->wheredate('created_at', $ddate)->with('employee')->with('attendance')->get());
}
function count_pendingLeave_requests()
{
    return count(Leave::where('state', '0')->where('status', '1')->whereMonth('created_at', date('m'))->whereHas('employee', function ($query) {
        $query->where('status', 1);
    })->get());
}


//------------------ Donot Change It Desktop Application Setting------------------
//################################################################################ 
function get_application_setting($emp_id)
{
    $empSettings = get_employee_setting($emp_id);
    if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
        $setting_obj = array(
            'clockTime' => $empSettings['clockOut_time'],   // Clock Out Time 120 seconds (Standard)
            'autoupdate' => 30,   // Update Window on Every 30 second
            'popuptime' => 15,     //Popup Display Time on User Screen 15  
            'app_id' => '06042023',
        );
    } else {
        // -----Do Not Change Server Response
        $setting_obj = array(
            'clockTime' => $empSettings['clockOut_time'],   // Clock Out Time 120 seconds (Standard)
            'autoupdate' => 30,   // Update Window on Every 30 second
            'popuptime' => 15,     //Popup Display Time on User Screen 15  
            'app_id' => '06042023',
        );
    }
    return $setting_obj;
}
function get_messagebox_setting($emp_id, $dateday)
{
    $clockout = get_clockOut_time($emp_id, $dateday);
    $date = Carbon::parse($dateday);
    $user_id = get_user_id($emp_id);
    $schedule = get_schedule_time($user_id);
    $time_out = Carbon::parse($schedule->time_out);
    $messageStr = '';
    if ($date->dayOfWeek == Carbon::FRIDAY) {
        $breaktime = $schedule->friday_break ?? 75;
    } else {
        $breaktime = $schedule->otherday_break ?? 60;
    }
    $remaning = $breaktime - $clockout;
    if ($time_out->lte(now()->addMinutes(5))) {
        $messageStr = 'Break Spent:' . round($clockout, 0) . ' Min';
    } else {
        $messageStr = 'Break Spent:' . round($clockout, 0) . ' | ' . ($remaning < 0 ? 'Exceeded:' : 'Remaining:') . round($remaning, 0) . ' Min';
    }
    $message_box = array(
        'messageStr' => $messageStr,
        'fg' => 'red',
        'popuptime' => 15,
        'font' => '("Helvetica", 5)'
    );
    return $message_box;
}
function testCommand()
{
    return "success";
}

// ---------Employee Setting Attributes-------------------
function get_employee_setting($emp_id)
{
    $empSettings = EmployeeSetting::where('emp_id', $emp_id)->get();
    $clockOut_time = 120;
    foreach ($empSettings as $key) {
        if ($key->setting_name == 'clockOut_time') {
            $clockOut_time = $key->setting_value;
        }
    }
    $employeeSetting = array(
        'clockOut_time' => intval($clockOut_time),
    );
    return $employeeSetting;
}

// ------------------Generate Notification Function---------------------------
function sendNotification($notification)
{
    $url = 'https://fcm.googleapis.com/fcm/send';

    $headers = array(
        'Authorization: key=' . "AAAAxd68FSg:APA91bGM6cDrNwJQEp2gUcrAXGwFJ3YWYTB75JIgWEAnO7TNl68nhqhembfIiRGib4xqjUlB5ht2K3ONOH-Mi4EelZZzp8QchmAkrjPPi2w4lQl9H9imbrBUenJlTgiHi4VpLFccS2uZ",
        'Content-Type: application/json'
    );

    $tokens = Token::all();
    echo '<pre>';
    print_r(json_decode($tokens));
    exit;
    foreach ($tokens as $key => $token) {

        $fields = array(
            "to" => $token->token_id,
            "notification" => $notification,
        );
        $fields = json_encode($fields);
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        // $result = curl_exec($ch);
        echo curl_exec($ch);
        // if (curl_exec($ch) === false) {
        //     // echo 'Curl error: ' . curl_error($ch);
        // } else {
        //     $result = curl_exec($ch);
        //     echo 'notification send';
        // }
        curl_close($ch);
    }
}


function getEmployeeListRoleBase()
{
    // $user = User::find(auth()->user()->id);
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
