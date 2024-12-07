"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"
import { dtLeaveType } from "../../dt_controller/settings/dt_leave_settings/dt_leave.type.js";
import { dtGroupSettings } from "../../dt_controller/settings/dt_group_settings/dt_group_settings.js";
import { GroupDetailsController } from "../../fn_controller/settings/group_settings/group_details.js";
import { dtApproverList } from "../../dt_controller/settings/dt_group_settings/dt_group_details.js";

export function fvGroupDetails(_table=false,form_id,param=''){

    var init_fvGroupDetails = (function () {

        var _handlefvApproverDetails = function(){
            let fvApproverDetails;
            let form = document.querySelector('#form_add_'+form_id+'');
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

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

                    if (field.hasAttribute('remote-validation')) {
                        validationRules[fieldName].validators.remote = {
                            url: '/hris/admin/settings/group_details/approver/'+field.getAttribute('data-validate'),
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: function () {
                                let data_id = document.querySelector(modal_id + ' button.submit').getAttribute('data-id');
                                return {
                                    id: data_id,
                                    group_id:param
                                };
                            }
                        };
                    }

                    if (!field.hasAttribute('data-required')) {
                        validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
                    }
                });

                fvApproverDetails = FormValidation.formValidation(form, {
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

            $(modal_id).on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvApproverDetails.resetForm();
                        form.reset();
                        $(modal_id).find('.submit').attr('data-id','');
                        $(modal_id).find("select[name='approver_id']").attr('disabled',false).val(null).trigger('change');;
                        $(modal_id).find("select[name='approver_level']").val(null).trigger('change');
                        $(modal_id).find("select[name='is_active']").val(null).trigger('change');
                        $(modal_id).find('input[name="is_final_approver"]').attr('checked',false);
                        $(modal_id).find('input[name="is_required"]').attr('checked',false);
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvApproverDetails && fvApproverDetails.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',_this.attr('data-id') ?? '');
                                formData.append('group_id',param);
                                (new RequestHandler).post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    fvApproverDetails.resetForm();
                                    if($(_table).length && _table){
                                         $(_table).DataTable().ajax.reload(null,false);
                                    }else{
                                        dtApproverList(param).init();
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
                _handlefvApproverDetails();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvGroupDetails.init();
    });

}
