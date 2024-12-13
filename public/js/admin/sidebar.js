'use strict';

import { page_content } from './pg_content.js';
import { DashboardController } from './fn_controller/dashboard.js';
import { LeaveSettingController } from './fn_controller/settings/leave_setting/leave_setting.js';
import { AutomaticCreditController } from './fn_controller/settings/leave_setting/setup_automatic_credit.js';
import { ManualCreditController } from './fn_controller/settings/leave_setting/setup_manual_credit.js';
import { FileMaintenanceController } from './fn_controller/settings/file_maintenance.js';
import { EmployeeMasterlistController } from './fn_controller/201_employee/employee_masterlist.js';
import { Alert } from '../global/alert.js';
import { EmployeeDetailsController } from './fn_controller/201_employee/employee_details.js';
import { OverTimeRequisitionController } from './fn_controller/approval/overtime_requisition.js';
import { GroupSettingsController } from './fn_controller/settings/group_settings/group_settings.js';
import { GroupDetailsController } from './fn_controller/settings/group_settings/group_details.js';
import { OfficialBusinessController } from './fn_controller/approval/official_business.js';
import { ApplicationForLeaveController } from './fn_controller/approval/application_for_leave.js';
import { RoleListController } from './fn_controller/user_management/role_list.js';
import { PermissionController } from './fn_controller/user_management/permission.js';


async function init_page() {
    let pathname = window.location.pathname;
    let page = pathname.split("/")[3];

    let param = false;
    let url = window.location.pathname;
    if(url.split('/')[4] !== null && typeof url.split('/')[4] !== 'undefined'){
        param =  pathname.split("/")[4];
    }
    load_page(page, param).then((res) => {
        if (res) {
            $(`.sidebar[data-page='${page}']`).addClass('active').attr('processing',false);
        }
    })
}

export async function load_page(page, param=null){
    try {
        const contentResult = await page_content(page, param);
        if (contentResult) {
            await page_handler(page, param);
            KTComponents.init();
            KTMenu.createInstances();
            return true;
        } else {
            return false;
        }
    } catch (error) {
        console.error('Error in load_page:', error);
        return false;
    }
}

export async function page_handler(page,param=null){
    const handler = _handlers[page];
    if (handler) {
        handler(page, param);
    } else {
        console.log("No handler found for this page");
    }
}

const _handlers = {
    dashboard: (page, param) => DashboardController(page, param).init(),
    leave_settings: (page, param) =>LeaveSettingController(page, param).init(),
    automatic_credit: (page, param) =>AutomaticCreditController(page, param).init(),
    manual_credit: (page, param) =>ManualCreditController(page, param).init(),
    file_maintenance: (page, param) =>FileMaintenanceController(page, param).init(),
    group_settings: (page, param) =>GroupSettingsController(page, param).init(),
    group_details: (page, param) =>GroupDetailsController(page, param).init(),
    employee_masterlist: (page, param) =>EmployeeMasterlistController(page, param).init(),
    employee_details: (page, param) =>EmployeeDetailsController(page, param).init(),
    role_list: (page, param) =>RoleListController(page, param).init(),
    permissions: (page, param) =>PermissionController(page, param).init(),
    overtime_requisition:(page,param)=>OverTimeRequisitionController(page, param).init(),
    official_business:(page,param)=>OfficialBusinessController(page, param).init(),
    application_for_leave:(page,param)=>ApplicationForLeaveController(page, param).init(),
};

$(document).ready(function(e){

    init_page();

    $(".sidebar").on("click", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        let _this = $(this);
        if (_this.data("processing")) {
            return; // Exit early if already in process
        }

        _this.data("processing", true);

        let page = _this.data('page');
        let link = _this.data('link');
        let title = _this.find('.menu-title').text();

        load_page(page)
        .then((res) => {
            if (res) {
                $('.sidebar').removeClass('active').attr('data-tab-initialized',false);
                _this.addClass('active');
                _this.attr('data-tab-initialized',true);
            }
        })
        .catch((err) => {
            Alert.alert("error","Error loading page:"+err);
        })
        .finally(() => {
            _this.data("processing", false);
        });
    });

})
