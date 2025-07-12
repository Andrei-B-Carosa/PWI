<?php

use App\Http\Controllers\AccessController\EmployeeLogin as Login;
use App\Http\Controllers\EmployeeController\Approvals\ApplicationForLeave as ApproveLeave;
use App\Http\Controllers\EmployeeController\Approvals\OfficialBusiness as ApproveOB;
use App\Http\Controllers\EmployeeController\Approvals\OvertimeRequisition as  ApproveOt;
use App\Http\Controllers\EmployeeController\Home as HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController\PageController as Page;
use App\Http\Controllers\EmployeeController\PersonnelMonitoring\OfficialBusinessForm as OBForm;
use App\Http\Controllers\EmployeeController\Profile\AccountSecurity\AccountDetails;
use App\Http\Controllers\EmployeeController\Profile\AccountSecurity\Tab as AccountSecurityTab;
use App\Http\Controllers\EmployeeController\Profile\Action as ProfileAction;
use App\Http\Controllers\EmployeeController\Profile\EmploymentDetails;
use App\Http\Controllers\EmployeeController\Profile\PersonalData\DocumentAttachments;
use App\Http\Controllers\EmployeeController\Profile\PersonalData\EducationalBackground;
use App\Http\Controllers\EmployeeController\Profile\PersonalData\References;
use App\Http\Controllers\EmployeeController\Profile\PersonalData\Tab as PersonalDataTab;
use App\Http\Controllers\EmployeeController\Profile\PersonalData\WorkExperience;
use App\Http\Controllers\EmployeeController\Profile\Tab as ProfileTab;
use App\Http\Controllers\EmployeeController\Request\Leave;
use App\Http\Controllers\EmployeeController\Request\OfficialBusiness;
use App\Http\Controllers\EmployeeController\Request\OverTime;
use App\Services\Reusable\Select\FilterYearOptions;
use App\Services\Reusable\Select\LeaveTypeOptions;
use App\Services\WebRoute;

Route::group(['prefix'=>'hris/employee'], function() {

    //EMPLOYEE PAGE
    Route::middleware(['auth','is.employee'])->controller(Page::class)->group(function () {

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

    Route::controller(HomeController::class)->prefix('home')->group(function() {
        Route::get('/leave_credit', 'leave_credit');
        Route::post('/leave_credit_history', 'leave_credit_history');
        Route::post('/approval_history', 'approval_history');
        Route::get('/group_details', 'group_details');
        Route::post('/get_group_members', 'get_group_members');
    });

    Route::group(['prefix'=>'request'], function() {
        Route::controller(OverTime::class)->prefix('overtime')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');

            Route::get('/widgets','widgets');
            Route::post('/view_history','view_history');
        });

        Route::controller(Leave::class)->prefix('leave')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');
            Route::post('/validate_leave_type', 'validate_leave_type');

            Route::get('/widgets', 'widgets');
            Route::post('/view_history','view_history');

        });

        Route::controller(OfficialBusiness::class)->prefix('official_business')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');

            Route::get('/widgets', 'widgets');
            Route::post('/view_history','view_history');

        });
    });

    Route::group(['prefix'=>'approvals'], function() {
        Route::controller(ApproveOt::class)->prefix('overtime_requisition')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');

            Route::post('/emp_details', 'emp_details');
            Route::post('/view_history', 'view_history');
        });

        Route::controller(ApproveLeave::class)->prefix('application_for_leave')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');

            Route::post('/emp_details', 'emp_details');
            Route::post('/view_history', 'view_history');
        });

        Route::controller(ApproveOB::class)->prefix('official_business')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');

            Route::post('/emp_details', 'emp_details');
            Route::post('/view_history', 'view_history');
        });

    });

    Route::group(['prefix'=>'profile'], function() {
        Route::post('/form', [ProfileTab::class, 'form']);
        Route::post('/update', [ProfileAction::class, 'update']);

        Route::controller(PersonalDataTab::class)->prefix('personal_data')->group(function() {
            Route::post('/tab', 'tab');
            Route::post('/update', 'update');

            Route::controller(EducationalBackground::class)->prefix('educational_background')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');
                Route::post('/check_document', 'check_document');

                Route::post('/info', 'info');
            });

            Route::controller(WorkExperience::class)->prefix('work_experience')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');
                Route::post('/check_document', 'check_document');

                Route::post('/info', 'info');
            });

            Route::controller(DocumentAttachments::class)->prefix('document_attachment')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');
                Route::post('/download_document', 'download_document');
                Route::post('/view_document', 'view_document');
            });

            Route::controller(References::class)->prefix('references')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');

                Route::post('/info', 'info');
            });

        });

        Route::post('/employment_details/update', [EmploymentDetails::class, 'update']);

        Route::group(['prefix'=>'account_security'], function() {
            Route::post('/tab', [AccountSecurityTab::class, 'tab']);
            Route::post('/update', [AccountDetails::class, 'update']);
        });

    });

    Route::group(['prefix'=>'personnel_monitoring'], function() {
        Route::controller(OBForm::class)->prefix('official_business_form')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');
            Route::post('/view_history', 'view_history');
        });
    });

    Route::group(['prefix'=>'select'],function(){
        Route::post('/get_request_year', [FilterYearOptions::class, 'get_request_year']);
        Route::post('/leave_type', [LeaveTypeOptions::class, 'list']);
    });

});
