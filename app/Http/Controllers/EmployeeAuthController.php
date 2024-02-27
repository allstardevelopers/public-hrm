<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Employee;

class EmployeeAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'pin_code' => $request->pin_code
        ];

        if (Auth::attempt($credentials)) {
            return redirect()->intended('admin');
        }

        return redirect()->back()->withInput($request->only('email'));
    }
}
