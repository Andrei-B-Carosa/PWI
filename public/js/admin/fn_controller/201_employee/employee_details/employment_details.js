'use strict';
import { data_bs_components } from "../../../../global.js";
import { Alert } from "../../../../global/alert.js";
import { RequestHandler } from "../../../../global/request.js";
import {  fvEmployeeDetails as fvEmploymentDetails } from "../../../fv_controller/201_employee/fv_employee_details.js";

export var EmploymentDetailsDataHandler =  function (tab,param) {

    const _page = $('.page-employee-details');
    const _request = new RequestHandler;

    $(async function () {

        $(_page).find(`#form2`).attr('action','/hris/admin/201_employee/employee_details/employment_details/update');
        $(`#form2`).find('select[data-control="select2"]').select2();
        fvEmploymentDetails(false,2,param);

    });

}
