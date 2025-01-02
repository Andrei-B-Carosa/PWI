'use strict';
import { data_bs_components } from "../../../global.js";
import {Alert} from "../../../global/alert.js"
import { RequestHandler } from "../../../global/request.js";
import { get_filter_month, get_filter_year, get_leave_type, trigger_select } from "../../../global/select.js";
import { dtLeaveRequest } from "../../dt_controller/request/dt_leave.js";
import { fvLeaveRequest } from "../../fv_controller/request/fv_leave.js";


export var LeaveRequestController = function (page,param) {

    dtLeaveRequest().init();
    fvLeaveRequest('#leave_request_table');

    get_filter_year('select[name="filter_year"]',2);
    get_filter_month('select[name="filter_month"]');
    get_leave_type('select[name="leave_type_id"]').then(() => {
        // trigger_select('select[name="leave_type_id"]','Vacation Leave (VL)')
    });

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
