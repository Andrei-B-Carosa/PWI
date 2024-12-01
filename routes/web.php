<?php

use App\Http\Controllers\AccessController\AdminLogin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessController\EmployeeLogin as EmployeeLogin;

Route::get('/', function(){
    return redirect()->route('employee.form.login');
});

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
});



//ADMIN LOGIN & LOGOUT
// Route::group([
//     'prefix' => 'hris/admin-login',
//     'middleware' => 'prevent.verified.user',
//     'controller' => EmployeeLogin::class
// ], function () {
//     Route::get('/', 'form')->name('login.employee');
//     Route::post('/login', 'login');
//     Route::post('/logout', 'logout');
// });
