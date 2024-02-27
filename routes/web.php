<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FingerDevicesControlller;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\EmployeeAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Employee\CheckinController;
use App\Http\Controllers\Employee\AjaxController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
})->name('welcome')->middleware('mobile');
Route::get('register', function () {
    return view('auth.register');
    // echo 'register log';
})->name('register');



Route::get('/mobile', function () {
    return view('mobile');
})->name('mobile.home');
Route::get('/laptop', function () {
    echo 'laptop user';
})->middleware('mobile');

Route::get('attended/{user_id}', '\App\Http\Controllers\AttendanceController@attended')->name('attended');
Route::get('attended-before/{user_id}', '\App\Http\Controllers\AttendanceController@attendedBefore')->name('attendedBefore');
Auth::routes(['register' => true, 'reset' => true]);

Route::group(['middleware' => ['auth', 'Role'], 'roles' => ['admin']], function () {
    Route::resource('/users', '\App\Http\Controllers\UsersController');
    Route::post('/store.team', '\App\Http\Controllers\UsersController@store_team')->name('store.team');
    Route::get('/show.team/{id}', '\App\Http\Controllers\UsersController@show_team')->name('show.team');
    Route::post('/update.team', '\App\Http\Controllers\UsersController@update_team')->name('update.team');
    Route::resource('/employees', '\App\Http\Controllers\EmployeeController');
    Route::get('inactive/', '\App\Http\Controllers\EmployeeController@inactiveIndex')->name('employees.inactive');
    Route::post('/employees/deactive/', '\App\Http\Controllers\EmployeeController@dactive_account')->name('deactive.employee');
    Route::get('/attendance', '\App\Http\Controllers\AttendanceController@index')->name('attendance');
    Route::get('/attendance/today', '\App\Http\Controllers\AttendanceController@todayindex')->name('attendance.today');
    Route::post('/employee/attendance/monthly', '\App\Http\Controllers\AttendanceController@searchindex')->name('employee.attendance.monthly');

    Route::get('/employee/attendance/monthly', function () {
        return redirect()->route('attendance');
    })->name('employee.attendance.monthly');
    Route::get('/employee/attendance/report/{id}/{m}/{y}', '\App\Http\Controllers\AttendanceController@reportindex')->name('employee.attendance.report');
    Route::get('/clockindex/{id}', '\App\Http\Controllers\ClockController@clockindex')->name('clockindex');
    Route::get('/clockrequest', '\App\Http\Controllers\ClockController@clockrequest')->name('clockrequest');

    Route::get('employee/mastersearch', '\App\Http\Controllers\AdminController@searchindex')->name('mastersearch');
    // ---------Employee Tracking Routes----------------
    Route::get('employeeTracking/{state}/{id}', '\App\Http\Controllers\EmployeeController@employeeTracking')->name('employeeTracking');


    // --------------Clocks Routes------------- 
    Route::get('/clocks', '\App\Http\Controllers\ClockController@index')->name('indexClocks');
    Route::get('/approveclockout/{id}', '\App\Http\Controllers\ClockController@approveClockout')->name('approveclockout');
    Route::get('/disapproveclockout/{id}', '\App\Http\Controllers\ClockController@disapproveClockout')->name('disapproveclockout');

    Route::get('/latetime', '\App\Http\Controllers\AttendanceController@indexLatetime')->name('indexLatetime');
    Route::get('/leave', '\App\Http\Controllers\LeaveController@index')->name('leave');
    Route::get('/overtime', '\App\Http\Controllers\LeaveController@indexOvertime')->name('indexOvertime');

    Route::post('/addleave', '\App\Http\Controllers\LeaveController@addLeave')->name('addleave');
    Route::post('/leaveAction', '\App\Http\Controllers\LeaveController@leaveAction')->name('leaveAction');

    Route::get('/admin', '\App\Http\Controllers\AdminController@index')->name('admin');
    Route::resource('/schedule', '\App\Http\Controllers\ScheduleController');

    Route::get('/leave/{leave}/delete', '\App\Http\Controllers\LeaveController@destroy')->name('leave.delete');

    // Route::post('store', '\App\Http\Controllers\ScheduleController@store')->name('schedule.store');
    // Route::get('schedules/$id', 'ScheduleController@show');



    Route::get('/check', '\App\Http\Controllers\CheckController@index')->name('check');
    Route::get('/sheet-report', '\App\Http\Controllers\CheckController@sheetReport')->name('sheet-report');
    Route::post('check-store', '\App\Http\Controllers\CheckController@CheckStore')->name('check_store');
    Route::post('/check', '\App\Http\Controllers\CheckController@index')->name('check');


    // Fingerprint Devices
    Route::resource('/finger_device', '\App\Http\Controllers\BiometricDeviceController');

    // ---- Insert Tokens----------------------------------
    Route::get('/insertTokens', '\App\Http\Controllers\AdminController@insertToken')->name('insertToken');
    Route::get('/setting', '\App\Http\Controllers\AdminController@systemSetting')->name('setting');
    Route::post('/upload_policy_file', '\App\Http\Controllers\AjaxController@upload_policy_file')->name('upload_policy_file');
    Route::get('/view_policies/{policy}', '\App\Http\Controllers\HomeController@policies_view')->name('view.policies');

    Route::post('/employee_setting', '\App\Http\Controllers\AjaxController@employee_setting')->name('employee_setting');


    Route::delete('finger_device/destroy', '\App\Http\Controllers\BiometricDeviceController@massDestroy')->name('finger_device.massDestroy');
    Route::get('finger_device/{fingerDevice}/employees/add', '\App\Http\Controllers\BiometricDeviceController@addEmployee')->name('finger_device.add.employee');
    Route::get('finger_device/{fingerDevice}/get/attendance', '\App\Http\Controllers\BiometricDeviceController@getAttendance')->name('finger_device.get.attendance');
    // Temp Clear Attendance route
    Route::get('finger_device/clear/attendance', function () {
        $midnight = \Carbon\Carbon::createFromTime(23, 50, 00);
        $diff = now()->diffInMinutes($midnight);
        dispatch(new ClearAttendanceJob())->delay(now()->addMinutes($diff));
        toast("Attendance Clearance Queue will run in 11:50 P.M}!", "success");

        return back();
    })->name('finger_device.clear.attendance');
    Route::post('/upload-profile-pic', '\App\Http\Controllers\EmployeeController@uploadProfilePic')->name('file.upload');
    Route::resource('/upcoming-events', '\App\Http\Controllers\EventController');
    // Route::resource('/update_event', '\App\Http\Controllers\EventController@update_event');
    Route::get('/delete-event/{id}', '\App\Http\Controllers\EventController@delete')->name('delete-event');
    Route::get('/edit-event/{eventId}', '\App\Http\Controllers\EventController@editEvent')->name('edit-event');
    Route::post('/store.permission', '\App\Http\Controllers\PermissionController@store')->name('store.permission');
    Route::post('/update.permission', '\App\Http\Controllers\PermissionController@update')->name('update.permission');
    Route::post('/delete-perm', '\App\Http\Controllers\PermissionController@delete')->name('delete-perm');
    Route::post('/update.manager', '\App\Http\Controllers\UsersController@update_manager')->name('update.manager');
    // -------Update Check In Reports----------------
    // update clock time routes 

    Route::get('/update-in-out-time/{id}', '\App\Http\Controllers\AjaxController@updateInOutTime')->name('updateInOutTime');
    Route::post('/update-clock-in', '\App\Http\Controllers\ClockController@updateCheckInOut')->name('update-clock-in');
});

// routes/web.php




// Route::group(['middleware' => ['auth', 'Role'], 'roles' => ['admin']], function () {
//     Route::post('/store.team', '\App\Http\Controllers\UsersController@store_team')->name('store.team');
// });

Route::group(['middleware' => ['auth', 'Role'], 'roles' => ['employee']], function () {
    // Route::get('/home', '\App\Http\Controllers\HomeController@index')->name('home');
    Route::get('/checkin', '\App\Http\Controllers\Employee\EmployeeDashboard@check_in_view')->name('checkin')->middleware('mobile');
    Route::get('/checkinerror', '\App\Http\Controllers\Employee\EmployeeDashboard@check_in_message')->name('checkinerror')->middleware('mobile');
    Route::get('/clickcheckin', '\App\Http\Controllers\Employee\EmployeeDashboard@check_in')->name('clickcheckin');
    Route::post('/reason', '\App\Http\Controllers\Employee\EmployeeDashboard@submit_late_reason')->name('reason');

    Route::post('/account-blocked', '\App\Http\Controllers\Employee\EmployeeDashboard@account_blocked')->name('account-blocked');

    Route::get('/dashboard', '\App\Http\Controllers\Employee\EmployeeDashboard@index')->name('dashboard');
    Route::get('/employee/attendance/team_report/{id}/{m}/{y}', '\App\Http\Controllers\AttendanceController@teamReportIndex')->name('employee.attendance.team_report');
    Route::get('/employee/report/{id}/{m}/{y}', '\App\Http\Controllers\AttendanceController@reportindex')->name('employee.report');

    // Route::get('/dashboard', '\App\Http\Controllers\Employee\EmployeeDashboard@index')->name('dashboard');
    Route::get('/employeecheck', '\App\Http\Controllers\CheckController@employeeCheck')->name('employeecheck');
    Route::get('/empattendence', '\App\Http\Controllers\AttendanceController@emp_attendence')->name('empattendence');
    Route::get('/attendence-report', '\App\Http\Controllers\CheckController@emp_sheetReport')->name('attendence-report');
    Route::get('/emplatetime', '\App\Http\Controllers\AttendanceController@indexempLatetime')->name('indexempLatetime');
    Route::get('/employeeClock/{id}', '\App\Http\Controllers\ClockController@clockindex')->name('employeeClock');

    Route::get('/check-out', '\App\Http\Controllers\Employee\EmployeeDashboard@check_out')->name('check-out');
    Route::get('/mark-attendance', '\App\Http\Controllers\Employee\EmployeeDashboard@mark_attendance')->name('mark-attendance');
    Route::post('/clock-out', '\App\Http\Controllers\Employee\EmployeeDashboard@clock_out')->name('clock-out');
    Route::post('/clock-in', '\App\Http\Controllers\Employee\EmployeeDashboard@clock_in')->name('clock-in');
    Route::get('/clock-in', '\App\Http\Controllers\Employee\EmployeeDashboard@clock_in')->name('clock-in');


    Route::get('/leave/request', '\App\Http\Controllers\LeaveController@requestIndex')->name('leaverequest');
    Route::post('/leaveRequest', '\App\Http\Controllers\LeaveController@leaveRequest')->name('leaveRequest');

    Route::get('/policies/{policy}', '\App\Http\Controllers\HomeController@policies_view')->name('policies');
    Route::post('/accept/policies', '\App\Http\Controllers\HomeController@accept_policies')->name('accept.policies');
});
Route::get('/home', '\App\Http\Controllers\HomeController@index')->name('home')->middleware('mobile');


// Route::group(['middleware' => ['auth']], function () {

//     Route::get('/home', 'HomeController@index')->name('home');

// ------------Ajax Modal Data---------------------
Route::get('/latetime_data/{id}', '\App\Http\Controllers\AjaxController@latetime_data')->name('latetime_data');
Route::get('/leave_data/{id}', '\App\Http\Controllers\AjaxController@leave_data')->name('leave_data');
Route::get('/schedule_data/{id}', '\App\Http\Controllers\AjaxController@schedule_data')->name('schedule_data');
Route::get('/employee_data/{id}', '\App\Http\Controllers\AjaxController@employee_data')->name('employee_data');
Route::get('/get_data/{id}/{view}', '\App\Http\Controllers\AjaxController@get_data')->name('get_data');


Route::post('/update_checkIn', '\App\Http\Controllers\AjaxController@Update_Check_In')->name('update_checkIn');
Route::get('/ajax_modal_content', function () {
    $data = $_GET['data'];
    // echo '<pre>';
    // print_r($data);exit;
    return view('ajax.update_check_in')->with(['data' => $data]);
})->name('ajax_modal_content');
Route::get('/ajax_modal_contents/{page}', function ($page) {
    $data = $_GET['data'];
    // echo '<pre>';
    // print_r($data);exit;
    return view('ajax.' . $page)->with(['data' => $data]);
})->name('ajax_modal_contents');


Route::get('/attendance/assign', function () {
    return view('attendance_leave_login');
})->name('attendance.login');

// Route::post('/attendance/assign', '\App\Http\Controllers\AttendanceController@assign')->name('attendance.assign');


// Route::get('/leave/assign', function () {
//     return view('attendance_leave_login');
// })->name('leave.login');

// Route::post('/leave/assign', '\App\Http\Controllers\LeaveController@assign')->name('leave.assign');


// Route::get('{any}', 'App\http\controllers\VeltrixController@index');

Route::get('/cronjob', '\App\Http\Controllers\Employee\CheckinController@cronjob')->name('cronjob');
Route::get('/cronStatus', '\App\Http\Controllers\Employee\CheckinController@cronStatus')->name('cronStatus');

Route::get('download-zip', function () {
    $fileName = 'Clock App 22012024.zip';
    return response()->download(public_path($fileName));
});
Route::get('download-pdf/{name}', function ($name) {
    $fileName = 'storage/assets/documents/' . $name;
    return response()->download(public_path($fileName));
});
