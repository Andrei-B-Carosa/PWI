"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"
import { dtLeaveType } from "../../dt_controller/settings/dt_leave_settings/dt_leave.type.js";
import { dtLeaveManagement } from "../../dt_controller/settings/dt_leave_settings/dt_leave_management.js";

export function fvLeaveType(_table=false,param=false){

    var init_fvLeaveType = (function () {

        var _handlefvLeaveType = function(){
            let fvLeaveType;
            let form = document.querySelector("#form_add_leave_type");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvLeaveType = FormValidation.formValidation(form, {
                    fields: {
                        name: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                remote: {
                                    url: '/hris/admin/settings/leave_settings/leave_type/validate_request',
                                    method: 'POST',
                                    message: 'Leave already exist',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function() {
                                        let data_id = $(modal_id).find('button.submit').attr('data-id');
                                        return {
                                            id: data_id
                                        };
                                    }
                                }
                            }
                        },
                        code:fv_validator(),
                        company_id:fv_validator(),
                        gender_type:fv_validator(),
                    },
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
                        fvLeaveType.resetForm();
                        form.reset();
                        $(modal_id).find('.modal_title').text('New Type of Leave');
                        $(modal_id).find('.submit').attr('data-id','');
                        $(modal_id).find('select[name="is_active"]').val(1).trigger('change');

                        $(modal_id).find('select[name="company_id"]').val('').trigger('change');
                        $(modal_id).find('select[name="gender_type"]').val('3').trigger('change');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvLeaveType && fvLeaveType.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',_this.attr('data-id') ?? '');
                                (new RequestHandler).post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvLeaveType.resetForm();
                                        if($(_table).length && _table){
                                             $(_table).DataTable().ajax.reload();
                                        }else{
                                            dtLeaveType().init()
                                        }
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
                _handlefvLeaveType();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvLeaveType.init();
    });

}

export function fvLeaveManagement(_table=false,param=false){
    var init_fvLeaveManagement = (function () {

        var _handlefvLeaveManagement = function(){
            let fvLeaveManagement;
            let form = document.querySelector("#form_add_leave_management");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvLeaveManagement = FormValidation.formValidation(form, {
                    fields: {
                        leave_type_id: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                remote: {
                                    url: '/hris/admin/settings/leave_settings/leave_management/validate_request',
                                    method: 'POST',
                                    message: 'Leave already exist',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function() {
                                        let data_id = $(modal_id).find('button.submit').attr('data-id');
                                        return {
                                            id: data_id
                                        };
                                    }
                                }
                            }
                        },
                        credit_type:fv_validator(),
                        status:fv_validator(),
                    },
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
                        fvLeaveManagement.resetForm();
                        // form.reset();
                        $(modal_id).find('.modal_title').text('Manage Leave');
                        $(modal_id).find('.submit').attr('data-id','');

                        $(modal_id).find('select[name="leave_type_id"]').val('').trigger('change');
                        $(modal_id).find('select[name="status"]').val('').trigger('change');
                        $(modal_id).find('select[name="credit_type"]').val('').trigger('change');
                        $(modal_id).find('select[name="fiscal_year"]').val('').select2().parent().addClass('d-none');

                        $(modal_id).find('button.submit').attr('data-id','');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvLeaveManagement && fvLeaveManagement.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',_this.attr('data-id') ?? '');
                                (new RequestHandler).post(url,formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvLeaveManagement.resetForm();
                                        if($(_table).length && _table){
                                             $(_table).DataTable().ajax.reload();
                                        }else{
                                            dtLeaveManagement().init()
                                        }
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
                _handlefvLeaveManagement();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvLeaveManagement.init();
    });
}

