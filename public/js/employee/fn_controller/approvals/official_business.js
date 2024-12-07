'use strict';
import { get_filter_month, get_filter_year, get_group } from "../../../global/select.js";
import { dtOfficialBusiness } from "../../dt_controller/approvals/dt_official_business.js";


export var OfficialBusinessController = function (page,param) {

    dtOfficialBusiness().init();

    get_filter_year('select[name="filter_year"]',3,false);
    get_filter_month('select[name="filter_month"]');
    get_group('select[name="filter_group"]');
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
