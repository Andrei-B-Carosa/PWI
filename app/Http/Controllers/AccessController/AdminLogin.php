<?php

namespace App\Http\Controllers\AccessController;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLogin extends Controller
{
    public function form()
    {
        return view('login.admin');
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
        $user_role = $user->user_roles;

        if(!$user_role)
        {
            Auth::logout();
            return response()->json([
                'status' => 'error',
                'message'=>'Account set-up is incomplete, try again later',
                'payload'=>csrf_token()
            ]);
        }

        if(!$user->employee->is_active || !$user->is_active || !$user_role->is_active)
        {
            Auth::logout();
            return response()->json([
                'status' => 'error',
                'message'=>'Account is Deactivated',
                'payload'=>csrf_token()
            ]);
        }

        if($user_role->role_id != 1)
        {
            Auth::logout();
            return response()->json([
                'status' => 'error',
                'message'=>'This login is for admin only',
                'payload'=>csrf_token()
            ]);
        }

        session([
            'user_id' => $user->id,
            'user_role' => 'admin',
            'default' => 'dashboard',
        ]);

        return response()->json([
            'status' => 'success',
            'message'=>'Login Success',
            'payload'=>'/hris/admin/dashboard'
        ]);
    }

    public function logout(Request $rq)
    {
        if(Auth::check())
        {
            Auth::logout();
            session()->flush();
            return redirect()->route('admin.form.login');
        }
    }
}
