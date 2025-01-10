'use strict';
import { get_employee, get_filter_month, get_filter_year, get_group } from "../../../global/select.js";
import { dtOfficialBusinessForm } from "../../dt_controller/personnel_monitoring/dt_ob_form.js";
import { fvOfficialBusinessForm } from "../../fv_controller/personnel_monitoring/fv_ob_form.js";


export var OfficialBusinessFormController = function (page,param) {

    dtOfficialBusinessForm().init();
    fvOfficialBusinessForm();

    get_filter_year('select[name="filter_year"]',3,false);
    get_filter_month('select[name="filter_month"]');
    get_group('select[name="filter_group"]');
    get_employee('select[name="contact_person"]');

    $(async function () {

        page_block.block();

        setTimeout(() => {
            page_block.release();
            KTComponents.init();
        }, 300);

    });

}
