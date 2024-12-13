'use strict';
import { modal_state } from "../../../global.js";
import { dtEmployeeMasterlist,dtEmployeeArchiveMasterlist } from "../../dt_controller/201_employee/dt_employee_masterlist.js";

export var EmployeeMasterlistController = function (page,param) {

    const _page = $('.page-employee-masterlist-settings');

    _page.ready(function() {

        _page.on('click','button.view-archive',function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            dtEmployeeArchiveMasterlist().init();

            setTimeout(() => {
                modal_state('#modal_archive_employee','show');
            }, 100);
        })
    })

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
