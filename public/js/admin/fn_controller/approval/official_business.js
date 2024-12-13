'use strict';
import {Alert} from "../../../global/alert.js"
import { get_filter_month, get_filter_year } from "../../../global/select.js";
import { dtOfficialBusiness } from "../../dt_controller/approvals/dt_official_business.js";
import { dtOvertimeRequisition } from "../../dt_controller/approvals/dt_overtime_requisition.js";


export var OfficialBusinessController = function (page,param) {

    dtOfficialBusiness().init();

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
