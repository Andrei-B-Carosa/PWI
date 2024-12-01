<?php

use App\Services\Reusable\Select\CompanyLocationOptions;
use App\Services\Reusable\Select\DepartmentOptions;
use App\Services\Reusable\Select\EmployeeOptions;
use App\Services\Reusable\Select\LeaveCreditTypeOptions;
use App\Services\Reusable\Select\LeaveFiscalYearOptions;
use Illuminate\Support\Facades\Route;
use App\Services\Reusable\Select\LeaveTypeOptions;
use App\Services\Reusable\Select\SectionOptions;

Route::group(['prefix'=>'hris/select'], function() {

    Route::post('/leave_type', [LeaveTypeOptions::class, 'list']);
    Route::post('/leave_fiscal_year', [LeaveFiscalYearOptions::class, 'list']);
    Route::post('/leave_credit_type', [LeaveCreditTypeOptions::class, 'list']);
    Route::post('/company_location', [CompanyLocationOptions::class, 'list']);

    Route::post('/department', [DepartmentOptions::class, 'list']);
    Route::post('/section', [SectionOptions::class, 'list']);
    Route::post('/employee', [EmployeeOptions::class, 'list']);

});
