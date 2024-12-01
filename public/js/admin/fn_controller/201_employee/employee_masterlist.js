'use strict';
import { dtEmployeeMasterlist } from "../../dt_controller/201_employee/dt_employee_masterlist.js";

export var EmployeeMasterlistController = function (page,param) {

    const _page = $('.page-employee-masterlist-settings');

    return {
        init: function () {

            page_block.block();

            dtEmployeeMasterlist().init();

            setTimeout(() => {
                page_block.release();
            }, 300);

        }
    }

}
