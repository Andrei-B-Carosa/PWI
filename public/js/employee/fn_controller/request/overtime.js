'use strict';
import {Alert} from "../../../global/alert.js"
import { get_filter_month, get_filter_year } from "../../../global/select.js";
import { dtOverTime } from "../../dt_controller/request/dt_overtime.js";
import { fvOverTime } from "../../fv_controller/request/fv_overtime.js";


export var OverTimeController = function (page,param) {

    dtOverTime().init();
    fvOverTime('#overtime_request_table');

    get_filter_year('select[name="filter_year"]',1);
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
