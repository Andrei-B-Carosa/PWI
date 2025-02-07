<?php

use App\Http\Controllers\AdminController\Approvals\ApplicationForLeave;
use App\Http\Controllers\AdminController\Approvals\OfficialBusiness;
use App\Http\Controllers\AdminController\Approvals\OvertimeRequisition;
use App\Http\Controllers\AdminController\Employee\AccountSecurity\AccountDetails;
use App\Http\Controllers\AdminController\Employee\AccountSecurity\Tab as AccountSecurityTab;
use App\Http\Controllers\AdminController\Employee\EmployeeDetails;
use App\Http\Controllers\AdminController\Employee\EmployeeMasterlist;
use App\Http\Controllers\AdminController\Employee\EmployeeRegistration;
use App\Http\Controllers\AdminController\Employee\EmploymentDetails\EmploymentDetails;
use App\Http\Controllers\AdminController\Employee\PersonalData\DocumentAttachments;
use App\Http\Controllers\AdminController\Employee\PersonalData\EducationalBackground;
use App\Http\Controllers\AdminController\Employee\PersonalData\FamilyBackground;
use App\Http\Controllers\AdminController\Employee\PersonalData\PersonalInformation;
use App\Http\Controllers\AdminController\Employee\PersonalData\References;
use App\Http\Controllers\AdminController\Employee\PersonalData\Tab as PersonalDataTab;
use App\Http\Controllers\AdminController\Employee\PersonalData\WorkExperience;
use App\Http\Controllers\AdminController\Settings\FileMaintenance\Classification;
use App\Http\Controllers\AdminController\Settings\FileMaintenance\Company;
use App\Http\Controllers\AdminController\Settings\FileMaintenance\CompanyLocation;
use App\Http\Controllers\AdminController\Settings\FileMaintenance\Department;
use App\Http\Controllers\AdminController\Settings\FileMaintenance\EmploymentType;
use App\Http\Controllers\AdminController\Settings\FileMaintenance\Position;
use App\Http\Controllers\AdminController\Settings\FileMaintenance\Section;
use App\Http\Controllers\AdminController\PageController as Page;
use App\Http\Controllers\AdminController\Settings\ApproverSettings;
use App\Http\Controllers\AdminController\Settings\GroupSettings\GroupApprover;
use App\Http\Controllers\AdminController\Settings\GroupSettings\GroupDetails;
use App\Http\Controllers\AdminController\Settings\GroupSettings\GroupMember;
use App\Http\Controllers\AdminController\Settings\GroupSettings\GroupSettings;
use App\Http\Controllers\AdminController\Settings\LeaveSettings\AutomaticCredit;
use App\Http\Controllers\AdminController\Settings\LeaveSettings\LeaveManagement;
use App\Http\Controllers\AdminController\Settings\LeaveSettings\LeaveType;
use App\Http\Controllers\AdminController\Settings\LeaveSettings\ManualCredit;
use App\Http\Controllers\AdminController\UserManagement\Permission;
use App\Http\Controllers\AdminController\UserManagement\RoleList;
use App\Models\HrisCompany;
use App\Services\Reusable\Select\CompanyOptions;
use App\Services\Reusable\Select\LeaveTypeOptions;
use App\Services\WebRoute;
use Illuminate\Support\Facades\Route;


Route::group(['prefix'=>'hris/admin'], function() {

    Route::middleware('prevent.verified.user')->get('/', function(){
        return view('login.admin');
    })->name('login.admin');

    Route::middleware(['auth','is.admin'])->controller(Page::class)->group(function () {

        Route::get('/', 'system_file');
        Route::post('/setup-page', 'setup_page');

        Route::get('/automatic_credit/{id}', 'system_file');
        Route::get('/manual_credit/{id}', 'system_file');

        Route::get('/employee_details/{id}', 'system_file');

        Route::get('/group_details/{id}', 'system_file');

        Route::get('/register_employee', function(){
            return view('admin.201_employee.employee_registration.index');
        })->name('admin.register_employee');

        $routes = (new WebRoute())->getWebRoutes(1);
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

    Route::group(['prefix'=>'settings'], function() {

        Route::group(['prefix'=>'leave_settings'], function() {
            Route::controller(LeaveType::class)->prefix('leave_type')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');

                Route::post('/info', 'info');
                Route::post('/validate_request', 'validate_request');
            });

            Route::controller(LeaveManagement::class)->prefix('leave_management')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');

                Route::post('/info', 'info');
                Route::post('/validate_request', 'validate_request');

            });

            Route::controller(AutomaticCredit::class)->prefix('automatic_credit')->group(function() {
                Route::post('/update', 'update');
                Route::post('/info', 'info');
            });

            Route::controller(ManualCredit::class)->prefix('manual_credit')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/create', 'create');
                Route::post('/update', 'update');
                Route::post('/info', 'info');
            });
        });

        Route::group(['prefix'=>'file_maintenance'], function() {
            Route::controller(Department::class)->prefix('department')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');

                Route::post('/info', 'info');
                Route::post('/validate_request', 'validate_request');
            });

            Route::controller(Position::class)->prefix('position')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');

                Route::post('/info', 'info');
                Route::post('/validate_request', 'validate_request');
            });

            Route::controller(Classification::class)->prefix('classification')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');

                Route::post('/info', 'info');
                Route::post('/validate_request', 'validate_request');
            });

            Route::controller(EmploymentType::class)->prefix('employment_type')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');

                Route::post('/info', 'info');
                Route::post('/validate_request', 'validate_request');
            });

            Route::controller(Section::class)->prefix('section')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');

                Route::post('/info', 'info');
                Route::post('/validate_request', 'validate_request');
            });

            Route::controller(Company::class)->prefix('company')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');

                Route::post('/info', 'info');
                Route::post('/validate_request', 'validate_request');
            });

            Route::controller(CompanyLocation::class)->prefix('company_location')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');

                Route::post('/info', 'info');
                Route::post('/validate_request', 'validate_request');
            });
        });

        Route::controller(GroupSettings::class)->prefix('group_settings')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');

            Route::post('/info', 'info');
            Route::post('/validate_request', 'validate_request');
        });

        Route::group(['prefix'=>'group_details'], function() {
            Route::controller(GroupApprover::class)->prefix('approver')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/info', 'info');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');

                Route::post('/check_final_approver', 'check_final_approver');
                Route::post('/check_approver', 'check_approver');
                Route::post('/check_approver_level', 'check_approver_level');
                Route::post('/emp_details', 'emp_details');
            });

            Route::controller(GroupMember::class)->prefix('member')->group(function() {
                Route::post('/dt', 'dt');
                Route::post('/update', 'update');
                Route::post('/info', 'info');
                Route::post('/delete', 'delete');

                Route::post('/employee_list', 'employee_list');
                Route::post('/emp_details', 'emp_details');
            });

        });

    });

    Route::group(['prefix'=>'201_employee'], function() {
        Route::controller(EmployeeMasterlist::class)->prefix('employee_masterlist')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/restore', 'restore');
            Route::post('/archive', 'archive');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');
            Route::post('/emp_details', 'emp_details');
        });

        Route::group(['prefix'=>'employee_details'], function() {

            Route::controller(EmployeeDetails::class)->group(function() {
                Route::post('/tab', 'tab');
                Route::post('/form', 'form');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');
            });

            Route::group(['prefix'=>'personal_data'], function() {

                Route::post('/tab', [PersonalDataTab::class, 'tab']);

                Route::post('/personal_information/update', [PersonalInformation::class, 'update']);
                Route::post('/family_background/update', [FamilyBackground::class, 'update']);

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

        Route::controller(EmployeeRegistration::class)->prefix('employee_registration')->group(function() {
            Route::post('/form', 'form');
            Route::post('/update', 'update');
        });

    });

    Route::group(['prefix'=>'approvals'], function() {
        Route::controller(OvertimeRequisition::class)->prefix('overtime_requisition')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/view_history','view_history');
            Route::post('/update', 'update');
        });

        Route::controller(OfficialBusiness::class)->prefix('official_business')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/view_history','view_history');

            Route::post('/update', 'update');
        });

        Route::controller(ApplicationForLeave::class)->prefix('application_for_leave')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/view_history','view_history');

            Route::post('/update', 'update');
        });
    });

    Route::group(['prefix'=>'user_management'], function() {
        Route::controller(RoleList::class)->prefix('role_list')->group(function() {
            Route::get('/list', 'list');
            Route::post('/update', 'update');
            Route::post('/update_system_file', 'update_system_file');
            Route::post('/update_file_layer', 'update_file_layer');

            Route::post('/delete', 'delete');

            Route::post('/employee_list', 'employee_list');
            Route::post('/user_list', 'user_list');
        });

        Route::controller(Permission::class)->prefix('permission')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');
        });
    });

    Route::group(['prefix'=>'select'], function() {
        Route::post('/get_company', [CompanyOptions::class, 'list']);
    });

});
