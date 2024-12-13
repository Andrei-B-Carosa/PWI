'use strict';
import { data_bs_components } from "../../../global.js";
import { Alert } from "../../../global/alert.js";
import { RequestHandler } from "../../../global/request.js";
import { dtPermission } from "../../dt_controller/user_management/dt_user_management.js";
import { fvEmployeeDetails } from "../../fv_controller/201_employee/fv_employee_details.js";

export var PermissionController =  function (page,param) {

    const _page = $('.page-role-list');
    const _request = new RequestHandler;

    dtPermission().init();

    return {
        init: function () {

            page_block.block();

            setTimeout(() => {
                page_block.release();
            }, 300);

        }
    }

}
