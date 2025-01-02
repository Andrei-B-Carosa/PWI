'use strict';

import { page_content } from './page.js';
import { HomeController } from './fn_controller/home.js';
import { OverTimeController } from './fn_controller/request/overtime.js';
import { LeaveRequestController } from './fn_controller/request/leave.js';
import { OBRequestController } from './fn_controller/request/ob.js';
import { OverTimeRequisitionController } from './fn_controller/approvals/overtime_requisition.js';
import { ApplicationForLeaveController } from './fn_controller/approvals/application_for_leave.js';
import { OfficialBusinessController } from './fn_controller/approvals/official_business.js';
import { EmployeeProfileController } from './fn_controller/profile/profile.js';

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
            $(`.navbar[data-page='${page}']`).addClass('here');
        }
    })
}

export async function load_page(page, param=null){
    try {
        const pageContent = await page_content(page, param);
        if (pageContent) {
            await page_handler(page, param);
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
    home: (page, param) => HomeController(page, param),
    overtime_request: (page, param) => OverTimeController(page, param).init(),
    leave_request: (page, param) => LeaveRequestController(page, param).init(),
    official_business_request: (page, param) => OBRequestController(page, param).init(),
    overtime_requisition: (page, param) => OverTimeRequisitionController(page, param).init(),
    application_for_leave:(page,param) =>ApplicationForLeaveController(page,param).init(),
    official_business: (page, param) => OfficialBusinessController(page, param).init(),
    profile:(page,param) =>EmployeeProfileController(page,param),
};

jQuery(document).ready(function() {

    init_page();

    $(".navbar").on("click", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        let page = $(this).data('page');
        let link = $(this).data('link');
        let title = $(this).find('.menu-title').text();
        let _this = $(this);

        load_page(page).then((res) => {
            if (res) {
                if(_this.hasClass('sub-menu')){
                    $('.navbar ,.menu-sub').removeClass('here');
                    _this.parent().parent().addClass('here');
                }else{
                    $('.navbar').removeClass('here');
                    _this.addClass('here');
                }

            }
        });
    });

})
