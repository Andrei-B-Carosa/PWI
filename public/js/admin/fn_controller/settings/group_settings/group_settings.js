'use strict';
import { Alert } from "../../../../global/alert.js";
import { RequestHandler } from "../../../../global/request.js";
import { get_company, get_company_location, get_department, get_employee, get_section } from "../../../../global/select.js";
import { dtGroupSettings } from "../../../dt_controller/settings/dt_group_settings/dt_group_settings.js";
import { fvGroupSettings } from "../../../fv_controller/fv_group_settings/fv_group_settings.js";


export var GroupSettingsController = function (page,param) {

    const _page = $('.page-group-settings');
    const _request = new RequestHandler();

    dtGroupSettings().init();
    // fvApproverSettings();
    fvGroupSettings('#group_table','group');


    return {
        init: function () {

            // getListOfGroups();
            // dtApproverSettings().init();
            // fvApproverSettings();

            // get_company('select[name="company_id"]');
            // get_company_location('select[name="company_location_id"]');
            // get_employee('select[name="approver_id"]');
            // get_section('select[name="section_id"]');
            // get_department('select[name="department_id"]');


        }
    }

}
