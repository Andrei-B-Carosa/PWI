'use strict';
import { get_filter_month, get_filter_year } from "../../../global/select.js";
import { dtApplicationForLeave } from "../../dt_controller/approvals/dt_application_for_leave.js";


export var ApplicationForLeaveController = function (page,param) {

    dtApplicationForLeave().init();

    get_filter_year('select[name="filter_year"]',2,false);
    get_filter_month('select[name="filter_month"]');

    return {
        init: function () {

            page_block.block();

            setTimeout(() => {
                page_block.release();
                KTComponents.init();
            }, 300);

        }
    }

}
