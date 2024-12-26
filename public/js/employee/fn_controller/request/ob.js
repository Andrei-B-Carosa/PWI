'use strict';
import { data_bs_components } from "../../../global.js";
import {Alert} from "../../../global/alert.js"
import { get_employee, get_filter_month, get_filter_year, get_leave_type, trigger_select } from "../../../global/select.js";
import { dtObRequest } from "../../dt_controller/request/dt_ob.js";
import { fvObRequest } from "../../fv_controller/request/fv_ob.js";


export var OBRequestController = function (page,param) {

    dtObRequest().init();
    fvObRequest('#ob_request_table');

    get_filter_year('select[name="filter_year"]',3);
    get_filter_month('select[name="filter_month"]');
    get_employee('select[name="contact_person"]');

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
