<?php

namespace App\Http\Controllers\AccessController;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EmployeeLogin extends Controller
{
    public function form()
    {
        return view('login.employee');
    }

    public function login(LoginRequest $rq)
    {
        if(!Auth::attempt($rq->only('username','password')))
        {
            return response()->json([
                'status' => 'error',
                'message'=>'Incorrect username or password',
                'payload'=>csrf_token()
            ]);
        }

        $user = Auth::user();
        $user_role =$user->user_roles;
        if(!$user->employee->is_active || !$user->is_active || !$user_role->is_active)
        {
            Auth::logout();
            return response()->json([
                'status' => 'error',
                'message'=>'Account is Deactivated',
                'payload'=>csrf_token()
            ]);
        }

        session([
            'user_id' => $user->id,
            'user_role' => 'employee',
            'default' => 'home',
        ]);

        return response()->json([
            'status' => 'success',
            'message'=>'Login Success',
            'payload'=>'/hris/employee/home'
        ]);
    }

    public function logout(Request $rq)
    {
        if(Auth::check())
        {
            Auth::logout();
            session()->flush();
            return redirect()->route('employee.form.login');
        }
    }
}
