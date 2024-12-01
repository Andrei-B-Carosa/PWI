<?php

use App\Http\Controllers\AccessController\EmployeeLogin as Login;
use App\Http\Controllers\EmployeeController\Approvals\ApplicationForLeave;
use App\Http\Controllers\EmployeeController\Approvals\OvertimeRequisition;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController\PageController as Page;
use App\Http\Controllers\EmployeeController\Request\Leave;
use App\Http\Controllers\EmployeeController\Request\OfficialBusiness;
use App\Http\Controllers\EmployeeController\Request\OverTime;
use App\Services\Reusable\Select\FilterYearOptions;
use App\Services\Reusable\Select\LeaveTypeOptions;
use App\Services\WebRoute;

Route::group(['prefix'=>'hris/employee'], function() {

    //EMPLOYEE PAGE
    Route::middleware('auth')->controller(Page::class)->group(function () {

        Route::get('/', 'system_file');
        Route::post('/setup-page', 'setup_page');

        $routes = (new WebRoute())->getWebRoutes(2);

        if ($routes) {
            foreach ($routes as $row) {
                if ($row->sub_menu) {
                    foreach ($row->file_layer as $layer) {
                        Route::get('/'.$layer->href,'system_file');
                    }
                }else{
                    Route::get('/'.$row->href,'system_file');
                }
            }
        }

    });

    Route::group(['prefix'=>'request'], function() {
        Route::controller(OverTime::class)->prefix('overtime')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');
        });

        Route::controller(Leave::class)->prefix('leave')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');
            Route::post('/check_leave_balance', 'check_leave_balance');

        });

        Route::controller(OfficialBusiness::class)->prefix('official_business')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');
        });
    });

    Route::group(['prefix'=>'approvals'], function() {
        Route::controller(OvertimeRequisition::class)->prefix('overtime_requisition')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');
        });

        Route::controller(ApplicationForLeave::class)->prefix('application_for_leave')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');
        });

    });

    Route::group(['prefix'=>'select'],function(){
        Route::post('/get_request_year', [FilterYearOptions::class, 'get_request_year']);
        Route::post('/leave_type', [LeaveTypeOptions::class, 'list']);
    });

});
