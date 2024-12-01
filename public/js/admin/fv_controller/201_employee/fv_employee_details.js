"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"
import { dtLeaveType } from "../../dt_controller/settings/dt_leave_settings/dt_leave.type.js";
import { dtLeaveManagement } from "../../dt_controller/settings/dt_leave_settings/dt_leave_management.js";

export function fvEmployeeDetails(_table=false,form_id,param){

    var init_fvEmployeeDetails = (function () {

        var _handlefvEmployeeDetails = function(){
            let fvEmployeeDetails;
            let form = document.querySelector('#form'+form_id+'');
            let card_id = form.getAttribute('card-id');
            let modalContent = document.querySelector(`${card_id}`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {

                const validationRules = {};
                const fields = form.querySelectorAll('input, select, textarea');

                fields.forEach(function (field) {
                    const fieldName = field.name;

                    validationRules[fieldName] = {
                        validators: {}
                    };

                    if (field.hasAttribute('data-required') && field.getAttribute('data-required') === 'false') {
                        return;
                    }

                    if (field.hasAttribute('remote-validation') && field.getAttribute('remote-validation') === 'true') {
                        validationRules[fieldName].validators.remote = {
                            url: '/hris/admin/settings/employee_details/' + form_id + '/validate_request',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: function () {
                                let data_id = document.querySelector(card_id + ' button.submit').getAttribute('data-id');
                                return {
                                    id: data_id
                                };
                            }
                        };
                    }

                    validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
                });

                fvEmployeeDetails = FormValidation.formValidation(form, {
                    fields: validationRules,
                    plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: "",
                    }),
                    },
                })
                form.setAttribute('data-fv-initialized', 'true');
            }

            $(card_id).on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                fvEmployeeDetails.resetForm();
                form.reset();
                $(form).find('input, select').prop('disabled', true);

                $(this).siblings('.edit').removeClass('d-none');
                $(this).siblings('.save').addClass('d-none');
                $(this).addClass('d-none');
            })

            $(card_id).on('click','.save',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvEmployeeDetails && fvEmployeeDetails.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('tab',form_id);
                                formData.append('id',param);
                                (new RequestHandler).post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvEmployeeDetails.resetForm();
                                        _this.addClass('d-none');
                                        _this.siblings('.cancel').addClass('d-none');
                                        _this.addClass('.edit').removeClass('d-none');
                                    }
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    _this.attr("data-kt-indicator","off");
                                    _this.attr("disabled",false);
                                    blockUI.release();
                                });
                            },
                        });
                    }
                })
            })
        }

        return {
            init: function () {
                _handlefvEmployeeDetails();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvEmployeeDetails.init();
    });

}
