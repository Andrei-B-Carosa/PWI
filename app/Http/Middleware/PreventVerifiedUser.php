<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventVerifiedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (($request->is('hris/employee/logout') || $request->is('hris/admin/logout')) && Auth::check()) {
            return $next($request);
        }

        if ((!$request->is('hris/employee/logout') || !$request->is('hris/admin/logout')) && Auth::check()) {
            if (session()->has('user_id')) {
                $role = session('user_role');
                $default = session('default');
                return redirect("hris/$role/$default");
            }
        }
        return $next($request);
    }
}
