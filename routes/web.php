<?php

use App\Http\Controllers\AccessController\AdminLogin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessController\EmployeeLogin as EmployeeLogin;
use App\Http\Controllers\ApproverController\LeaveRequest;
use App\Http\Controllers\ApproverController\OfficialBusinessRequest;
use App\Http\Controllers\ApproverController\OvertimeRequest;
use App\Services\Reusable\PDFSignature;

Route::get('/', function(){
    return redirect()->route('employee.form.login');
});

// Route::get('/test-mail', function(){
//     return view('email.approver_notification');
// });

//EMPLOYEE LOGIN & LOGOUT

Route::group(['prefix'=>'hris'], function() {

    Route::group(['prefix' => 'employee','middleware' => 'prevent.verified.user','controller' => EmployeeLogin::class ], function () {
        Route::get('/login', 'form')->name('employee.form.login');
        Route::post('/login', 'login')->name('employee.login');
        Route::post('/logout', 'logout')->name('employee.logout');
    });

    Route::group(['prefix' => 'admin','middleware' => 'prevent.verified.user','controller' => AdminLogin::class ], function () {
        Route::get('/login', 'form')->name('admin.form.login');
        Route::post('/login', 'login')->name('admin.login');
        Route::post('/logout', 'logout')->name('admin.logout');
    });

    //this route is when approving thru email, instead of going thru login direct to approving
    //just need to check if president then in the url parameter get the approving pin, if not then required user to enter approving pin first
    Route::group(['prefix'=>'approver'], function() {
        Route::controller(OfficialBusinessRequest::class)->prefix('ob_request')->group(function() {
            Route::get('/{token}', 'index')->name('approver.ob_request');
            Route::post('/update', 'update');
            Route::post('/next-request', 'next_request');
            Route::post('/info', 'info');
        });

        Route::controller(OvertimeRequest::class)->prefix('ot_request')->group(function() {
            Route::get('/{token}', 'index')->name('approver.ot_request');
            Route::post('/update', 'update');
            Route::post('/next-request', 'next_request');
            Route::post('/info', 'info');
        });

        Route::controller(LeaveRequest::class)->prefix('leave_request')->group(function() {
            Route::get('/{token}', 'index')->name('approver.leave_request');
            Route::post('/update', 'update');
            Route::post('/next-request', 'next_request');
            Route::post('/info', 'info');
        });
    });
});




