<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Schedule;
use App\Http\Requests\EmployeeRec;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{

    public function index()
    {

        return view('admin.employee')->with(['employees' => Employee::where('status', '1')->get(), 'schedules' => Schedule::all()]);
    }
    public function inactiveIndex()
    {
        $status = [0, 2];
        return view('admin.employee')->with(['employees' => Employee::whereIN('status', $status)->get(), 'schedules' => Schedule::all()]);
    }

    public function store(EmployeeRec $request)
    {
        // $request->validated();
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->pin_code = bcrypt($request->pin_code);
        $user->password = Hash::make($request->password);
        $user->save();
        // echo json_encode($user->hasRole('employee'));
        $role = Role::whereSlug('employee')->first();
        $user->roles()->attach($role);
        $employee = new Employee;
        $employee->name = $request->name;
        $employee->gender = $request->gender;
        $employee->dob = $request->dob;
        $employee->position = $request->position;
        $employee->joining_date = $request->joining_date;
        $employee->probation = $request->probation;
        $employee->email = $request->email;
        $employee->contact_no = $request->contact_no;
        $employee->emergency_no = $request->emergency_no;
        $employee->pin_code = bcrypt($request->pin_code);
        $employee->user_id = $user->id;
        if ($employee->save()) {
            if ($request->schedule) {
                $schedule = Schedule::whereSlug($request->schedule)->first();
                $employee->schedules()->attach($schedule);
            }
            echo 'success';
        }
        // $role = Role::whereSlug('employee')->first();
        // echo json_encode($role);

        // $employee->roles()->attach($role);
        flash()->success('Success', 'Employee Record has been created successfully !');

        // return redirect()->route('employees.index')->with('success');
    }


    public function update(EmployeeRec $request, Employee $employee)
    {
        $request->validated();
        $user = User::find($employee->user_id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->pin_code = bcrypt($request->pin_code);
        if (isset($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        $employee->name = $request->name;
        $employee->position = $request->position;
        $employee->gender = $request->gender;
        $employee->dob = $request->dob;
        $employee->joining_date = $request->joining_date;
        $employee->probation = $request->probation;
        $employee->email = $request->email;
        $employee->contact_no = $request->contact_no;
        $employee->emergency_no = $request->emergency_no;
        $employee->pin_code = bcrypt($request->pin_code);
        $employee->save();
        if ($request->schedule) {

            $employee->schedules()->detach();

            $schedule = Schedule::whereSlug($request->schedule)->first();

            $employee->schedules()->attach($schedule);
        }

        flash()->success('Success', 'Employee Record has been Updated successfully !');
        return redirect()->route('employees.index')->with('success');
    }
    public function destroy(Employee $employee)
    {
        $user = User::find($employee->user_id);
        $employee->delete();
        $user->delete();
        flash()->success('Success', 'Employee Record has been Deleted successfully !');
        return redirect()->route('employees.index')->with('success');
    }
    public function employeeTracking($state, $id)
    {
        $employee = Employee::find($id);
        if ($state == 0) {
            $employee->tracking = 1;
            if ($employee->save()) {
                echo 'true';
            }
        } elseif ($state == 1) {
            $employee->tracking = 0;
            if ($employee->save()) {
                echo 'true';
            }
        }
    }
    public function dactive_account(Request $request)
    {

        $employee = Employee::find($request->emp_id);
        $user = User::where('id', $employee->user_id)->first();

        if ($request->purpose == 2) {
            $user->status = 2;
            $employee->status = 2;
            $employee->tracking = 0;
            $employee->resign_date = $request->resign_date;
        } else {
            $user->status = 0;
            $employee->status = 0;
            $employee->tracking = 0;
        }
        if ($employee->save()) {
            $user->save();
            echo 'success';
        }
    }
    public function uploadProfilePic(Request $request)
    {
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $employee = Employee::find($request->id);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found.'], 404);
        }

        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->move(public_path('storage/assets/profile_pics'), $filename);
            $employee->profile_pic = $filename;
            if ($employee->save()) {
                return response()->json(['msg' => 'Profile picture uploaded successfully!', 'path' => $path, 'status' => 200]);
            } else {
                return response()->json(['error' => 'Profile picture upload failed: ', 'status' => 500], 500);
            }
        }

        return response()->json(['error' => 'Profile picture upload failed!', 'status' => 500], 500);
    }
    public function uploadProfilePic2(Request $request)
    {
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $id = $request->id;
        $employee = Employee::find($id);
        if ($request->hasFile('profile_pic')) {

            $file = $request->file('profile_pic');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/assets/user_uploads/profile', $filename);


            // Update the employee's profile picture path in the database.
            $employee->update(['profile_pic' => $path]);

            return response()->json(['success' => 'Profile picture uploaded successfully!', 'path' => $path]);
        }

        return response()->json(['error' => 'Profile picture upload failed!'], 500);
    }
}
