<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index()
    {
        $teams  = Team::with('activeMembers')->get();
        return view('admin.users')->with(['users' => User::where('status', '1')->with('employee')->with('roles')->get(), 'teams' => $teams]);
    }
    public function store(UserRequest  $request)
    {
    }
    public function update(UserRequest $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        if (isset($request->password)) {
            $user->password = Hash::make($request->password);
        }
        if (!$user->roles()->whereSlug($request->role)->exists()) {
            $role = Role::whereSlug($request->role)->first();
            $user->roles()->attach($role);
        }
        $employee = Employee::where('user_id', $user->id)->first();
        if ($employee) {
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->save();
        }
        if ($user->save()) {
            flash()->success('Success', 'Users Record has been Updated successfully !');
            return redirect()->route('users.index')->with('success');
        }
    }
    function store_team(Request $request)
    {
        $team = new Team;
        $team->name = $request->name;
        // $team->slug = $request->slug;
        $team->slug = Str::slug($request->name, '_');
        $team->descrption = $request->descrption;
        if ($team->save()) {
            flash()->success('Success', 'Team created successfully!');
            return redirect()->route('users.index')->with('success');
        }
    }
    function show_team($id)
    {
        $team = Team::find($id);
        // dd(json_decode($team));
        $team_obj = Team::where('id', $id)->first();

        $user = User::has('employee')->get();
        return view('ajax.team-info')->with(['users' => $user, 'team' => $team, 'team_name'=> $team_obj->name]);
    }
    function update_team(Request $request)
    {
        $team = Team::find($request->team_id);
        foreach ($request->users as $user_id) {
            # code...
            $user = User::find($user_id);
            if (!$team->users->contains('id', $user_id)) {
                $team->users()->attach($user_id);
            }
        }

        flash()->success('Success', 'Team Updated successfully!');
        return redirect()->route('users.index')->with('success');
    }

    function update_manager(Request $request)
    {
        $user = User::find($request->user_id);
        $team = Team::find($request->team_id);
        if ($user && $team) {
            $user->teams()->updateExistingPivot($team->id, ['is_manager' => 1]);
            return response()->json(['message' => 'User updated as manager']);
        } else {
            return response()->json(['message' => 'User or team not found'], 404);
        }
    }
}
