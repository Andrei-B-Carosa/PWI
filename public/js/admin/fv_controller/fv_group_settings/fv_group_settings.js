"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"
import { dtLeaveType } from "../../dt_controller/settings/dt_leave_settings/dt_leave.type.js";
import { dtGroupSettings } from "../../dt_controller/settings/dt_group_settings/dt_group_settings.js";

// export function fvGroupSettings(_table=false,param=false){

//     var init_fvGroupSettings = (function () {

//         var _handlefvGroupSettings = function(){
//             let fvGroupSettings;
//             let form = document.querySelector("#form_add_approver");
//             let modal_id = form.getAttribute('modal-id');
//             let modalContent = document.querySelector(`${modal_id} .modal-content`);

//             let blockUI = new KTBlockUI(modalContent, {
//                 message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
//             });

//             if (!form.hasAttribute('data-fv-initialized')) {
//                 fvGroupSettings = FormValidation.formValidation(form, {
//                     fields: {
//                         approver_level: {
//                             validators: {
//                                 notEmpty:{
//                                     message:'This field is required'
//                                 },
//                                 remote: {
//                                     url: '/hris/admin/settings/approver_settings/check_approver_level',
//                                     method: 'POST',
//                                     message: 'Leave already exist',
//                                     headers: {
//                                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                                     },
//                                     data: function() {
//                                         let data_id = $(modal_id).find('button.submit').attr('data-id');
//                                         // let section_id = form.querySelector('[name="section_id"]').value;
//                                         let department_id = form.querySelector('[name="department_id"]').value;
//                                         return {
//                                             id: data_id,
//                                             // section_id:section_id,
//                                             department_id:department_id
//                                         };
//                                     }
//                                 }
//                             }
//                         },
//                         is_final_approver: {
//                             validators: {
//                                 remote: {
//                                     url: '/hris/admin/settings/approver_settings/check_final_approver',
//                                     method: 'POST',
//                                     message: 'Leave already exist',
//                                     headers: {
//                                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                                     },
//                                     data: function() {
//                                         let data_id = $(modal_id).find('button.submit').attr('data-id');
//                                         // let section_id = form.querySelector('[name="section_id"]').value;
//                                         let department_id = form.querySelector('[name="department_id"]').value;
//                                         return {
//                                             id: data_id,
//                                             // section_id:section_id,
//                                             department_id:department_id,
//                                         };
//                                     }
//                                 }
//                             }
//                         },
//                         approver_id:{
//                             validators: {
//                                 notEmpty:{
//                                     message:'This field is required'
//                                 },
//                                 remote: {
//                                     url: '/hris/admin/settings/approver_settings/check_approver',
//                                     method: 'POST',
//                                     message: 'Leave already exist',
//                                     headers: {
//                                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                                     },
//                                     data: function() {
//                                         let data_id = $(modal_id).find('button.submit').attr('data-id');
//                                         // let section_id = form.querySelector('[name="section_id"]').value;
//                                         let department_id = form.querySelector('[name="department_id"]').value;
//                                         return {
//                                             id: data_id,
//                                             // section_id:section_id,
//                                             department_id:department_id
//                                         };
//                                     }
//                                 }
//                             }
//                         },
//                         company_id:fv_validator(),
//                         company_location_id:fv_validator(),
//                         is_active:fv_validator(),
//                         department_id:fv_validator(),
//                     },
//                     plugins: {
//                     trigger: new FormValidation.plugins.Trigger(),
//                     bootstrap: new FormValidation.plugins.Bootstrap5({
//                         rowSelector: ".fv-row",
//                         eleInvalidClass: "",
//                         eleValidClass: "",
//                     }),
//                     },
//                 })
//                 form.setAttribute('data-fv-initialized', 'true');
//             }

//             $(modal_id).on('click','.cancel',function(e){
//                 e.preventDefault()
//                 e.stopImmediatePropagation()
//                 Alert.confirm('question',"Close this form ?",{
//                     onConfirm: () => {
//                         modal_state(modal_id);
//                         fvGroupSettings.resetForm();
//                         form.reset();
//                         $(modal_id).find('#form_add_approver').attr('action','/hris/admin/settings/approver_settings/create');
//                         $(modal_id).find('.submit').attr('data-id','');
//                         $(modal_id).find('select[name="is_active"]').val(1).trigger('change');
//                         $(modal_id).find('select[name="company_id"]').val('').trigger('change').attr('disabled',false);
//                         $(modal_id).find('select[name="company_location_id"]').val('').trigger('change').attr('disabled',false);
//                         $(modal_id).find('select[name="department_id"]').val('').trigger('change').attr('disabled',false);
//                         $(modal_id).find('select[name="section_id"]').val('').trigger('change').attr('disabled',false);
//                         $(modal_id).find('select[name="approver_id"]').val('').trigger('change').attr('disabled',false);
//                         $(modal_id).find('select[name="approver_level"]').val('1').trigger('change');
//                         $(modal_id).find('input[name="is_final_approver"]').prop('checked', false).trigger('change');
//                         $(modal_id).find('input[name="is_required"]').prop('checked', false).trigger('change');

//                     }
//                 })
//             })

//             $(modal_id).on('click','.submit',function(e){
//                 e.preventDefault()
//                 e.stopImmediatePropagation()

//                 let _this = $(this);
//                 let url = form.getAttribute('action');
//                 fvGroupSettings && fvGroupSettings.validate().then(function (v) {
//                     if(v == "Valid"){
//                         let message = _handleAprroverConfiguration(form);
//                         Alert.confirm("question",message, {
//                             onConfirm: function() {
//                                 blockUI.block();
//                                 _this.attr("data-kt-indicator","on");
//                                 _this.attr("disabled",true);
//                                 let formData = new FormData(form);
//                                 formData.append('id',_this.attr('data-id') ?? '');
//                                 (new RequestHandler).post(url,formData).then((res) => {
//                                     Alert.toast(res.status,res.message);
//                                     if(res.status == 'success'){
//                                         fvGroupSettings.resetForm();
//                                         if($(_table).length && _table){
//                                                 $(_table).DataTable().ajax.reload();
//                                         }else{
//                                             dtGroupSettings().init()
//                                         }
//                                     }
//                                 })
//                                 .catch((error) => {
//                                     console.log(error)
//                                     Alert.alert('error',"Something went wrong. Try again later", false);
//                                 })
//                                 .finally(() => {
//                                     _this.attr("data-kt-indicator","off");
//                                     _this.attr("disabled",false);
//                                     blockUI.release();
//                                 });
//                             },
//                         });
//                     }
//                 })
//             })

//         }

//         var _handleAprroverConfiguration = function(form){
//             let department = $(form).find('[name="department_id"] option:selected').text();
//             let approver = $(form).find('[name="approver_id"] option:selected').text();

//             let approver_level = $(form).find('[name="approver_level"]').val();
//             let is_final_approver = $(form).find('[name="is_final_approver"]').prop('checked');
//             let is_required = $(form).find('[name="is_required"]').prop('checked');

//             let message = `
//             Set <b>${approver}</b> as an approver for <br><?>${department}?<br>
//             Level: <b>${approver_level}</b>.<br>
//             ${is_final_approver ? 'Final approver. <br>' : ''}
//             ${is_required ? 'Approval is required.' : 'Approval is optional.'}`;

//             return message;
//         }

//         return {
//             init: function () {
//                 _handlefvGroupSettings();
//             },
//         };

//     })();

//     KTUtil.onDOMContentLoaded(function () {
//         init_fvGroupSettings.init();
//     });

// }

export function fvGroupSettings(_table=false,form_id){

    var init_fvGroupSettings = (function () {

        var _handlefvGroupSettings = function(){
            let fvGroupSettings;
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

                    if (field.hasAttribute('data-required') && field.getAttribute('data-required') === 'false') {
                        return;
                    }

                    if (field.hasAttribute('remote-validation') && field.getAttribute('remote-validation') === 'true') {
                        validationRules[fieldName].validators.remote = {
                            url: '/hris/admin/settings/group_settings/validate_request',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: function () {
                                let data_id = document.querySelector(modal_id + ' button.submit').getAttribute('data-id');
                                return {
                                    id: data_id
                                };
                            }
                        };
                    }

                    validationRules[fieldName].validators.notEmpty = {message: 'This field is required'};
                });

                fvGroupSettings = FormValidation.formValidation(form, {
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
                        fvGroupSettings.resetForm();
                        form.reset();
                        $(modal_id).find('.submit').attr('data-id','');
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvGroupSettings && fvGroupSettings.validate().then(function (v) {
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
                                        fvGroupSettings.resetForm();
                                        if($(_table).length && _table){
                                             $(_table).DataTable().ajax.reload();
                                        }else{
                                            dtGroupSettings().init()
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
                _handlefvGroupSettings();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvGroupSettings.init();
    });

}

