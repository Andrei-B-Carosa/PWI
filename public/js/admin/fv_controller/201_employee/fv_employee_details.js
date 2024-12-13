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

            $(card_id).on('click','button.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                fvEmployeeDetails.resetForm();
                form.reset();
                $(form).find('input, select').prop('disabled', true);

                $(this).siblings('.edit').removeClass('d-none');
                $(this).siblings('.save').addClass('d-none');
                $(this).addClass('d-none');
            })

            $(card_id).on('click','button.save',function(e){
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
                                        _this.siblings('.edit').removeClass('d-none');
                                        $(form).find('input, select').prop('disabled', true);
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

            $(card_id).on('click','button.edit',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                const form = $(this).closest('.card').find('form');
                form.find('input, select').prop('disabled', false);
                $(this).addClass('d-none');
                $(this).siblings('.cancel, .save').removeClass('d-none');
            });
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

export function fvUploadDocument(_table=false,form_id,param){

    var init_fvUploadDocument = (function () {

        var _handlefvUploadDocument = function(){
            let fvUploadDocument;
            let form = document.querySelector('#form_upload_document');

            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {

                fvUploadDocument = FormValidation.formValidation(form, {
                    fields: {
                        files: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                file: {
                                    extension: 'pdf',
                                    type: 'application/pdf',
                                    message: 'Please upload a valid PDF file'
                                },
                                fileSize: {
                                    maxSize: 20480 * 1024,
                                    message: 'The file is too large. Maximum size allowed is 20 MB.'
                                },
                            }
                        },
                        file_type:fv_validator(),
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

            $(modal_id).on('click','button.cancel',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                Alert.confirm('question',"Close this form ?",{
                    onConfirm: () => {
                        modal_state(modal_id);
                        fvUploadDocument.resetForm();
                        form.reset();
                        $(modal_id).find('.submit').attr('data-id','');
                    }
                })
            })

            $(modal_id).on('click','button.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvUploadDocument && fvUploadDocument.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',param);
                                formData.append('tab',form_id);
                                (new RequestHandler).post(url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvUploadDocument.resetForm();
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
                                    $(modal_id).find('.modal-body').prepend(`
                                        <div class="alert alert-dismissible bg-light-primary border border-primary d-flex flex-column flex-sm-row p-5 mb-10">
                                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                                <h5 class="mb-1">Refresh Page</h5>
                                                <span>To fetch all latest documents of the employee.</span>
                                            </div>
                                            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                                <i class="ki-duotone ki-cross fs-1 text-success"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                        </div>
                                    `);
                                });
                            },
                        });
                    }
                })
            })
        }

        return {
            init: function () {
                _handlefvUploadDocument();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvUploadDocument.init();
    });

}

export function fvAccountSecurity(_table=false,form_id,param){

    var init_fvEmailAddress = (function () {

        var _handlefvEmailAddress = function(){
            let fvEmailAddress;
            let form = document.querySelector('#kt_signin_change_email');

            let card_id = '#card8';
            let cardContent = document.querySelector(`${card_id}`);

            let blockUI = KTBlockUI.getInstance(cardContent) ??new KTBlockUI(cardContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {

                fvEmailAddress = FormValidation.formValidation(form, {
                    fields: {
                        emailaddress: {
                            validators: {
                                notEmpty: {
                                    message: 'The email address is required'
                                },
                                emailAddress: {
                                    message: 'The input is not a valid email address'
                                }
                            }
                        },
                        // confirmemailpassword: {
                        //     validators: {
                        //         notEmpty: {
                        //             message: 'The password is required'
                        //         },
                        //         stringLength: {
                        //             min: 8,
                        //             message: 'The password must be at least 8 characters long'
                        //         },
                        //         regexp: {
                        //              regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])/,
                        //             message: 'The password must contain uppercase, lowercase, a number, and a special character'
                        //         }
                        //     }
                        // }
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

            $(form).on('click','button.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvEmailAddress && fvEmailAddress.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("warning","Are you sure you want to update the email?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',param);
                                formData.append('tab',form_id);
                                formData.append('column','c_email');
                                (new RequestHandler).post(url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvEmailAddress.resetForm();
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
                                    $('#kt_signin_cancel').click();
                                });
                            },
                        });
                    }
                })
            })
        }

        var _handlefvPassword = function(){
            let fvEmailAddress;
            let form = document.querySelector('#kt_signin_change_password');

            let card_id = '#card8';
            let cardContent = document.querySelector(`${card_id}`);

            let blockUI = KTBlockUI.getInstance(cardContent) ??new KTBlockUI(cardContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {

                fvEmailAddress = FormValidation.formValidation(form, {
                    fields: {
                        newpassword: {
                            validators: {
                                notEmpty: {
                                    message: 'The password is required'
                                },
                                stringLength: {
                                    min: 8,
                                    message: 'The password must be at least 8 characters long',
                                },
                                regexp: {
                                    regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
                                    message: 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character',
                                },
                            }
                        },
                        confirmpassword: {
                            validators: {
                                notEmpty: {
                                    message: 'The password is required'
                                },
                                identical: {
                                    compare: function () {
                                        return document.querySelector('[name="newpassword"]').value;
                                    },
                                    message: 'The password and its confirm password must match',
                                },
                            }
                        }
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

            $(form).on('click','button.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvEmailAddress && fvEmailAddress.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("warning","Are you sure you want to update the password?", {
                            onConfirm: function() {
                                blockUI.block();
                                _this.attr("data-kt-indicator","on");
                                _this.attr("disabled",true);
                                let formData = new FormData(form);
                                formData.append('id',param);
                                formData.append('tab',form_id);
                                formData.append('column','password');
                                (new RequestHandler).post(url,formData,true).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        fvEmailAddress.resetForm();
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
                                    $('#kt_password_cancel').click();
                                });
                            },
                        });
                    }
                })
            })
        }

        return {
            init: function () {
                _handlefvEmailAddress();
                _handlefvPassword();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvEmailAddress.init();
    });

}
