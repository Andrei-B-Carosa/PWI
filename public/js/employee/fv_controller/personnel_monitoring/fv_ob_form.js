"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"
import { dtObRequest } from "../../dt_controller/request/dt_ob.js";
import { dtOfficialBusinessForm } from "../../dt_controller/personnel_monitoring/dt_ob_form.js";

export function fvOfficialBusinessForm(_table=false,param=false){

    var init_fvOfficialBusinessForm = (function () {

        var _handlefvOfficialBusinessForm = function(){
            let fvOfficialBusinessForm;
            let form = document.querySelector("#form_request_ob");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvOfficialBusinessForm = FormValidation.formValidation(form, {
                    fields: {
                        actual_ob_time_out: fv_validator(),
                        // actual_ob_time_in: fv_validator(),
                        // guard_remarks: fv_validator(),
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
                        setTimeout(() => {
                            fvOfficialBusinessForm.resetForm();
                            form.reset();
                            $(modal_id).find('button.submit').attr('data-id','').removeClass('d-none');
                            $(modal_id).find('button.cancel').attr('data-id','').removeClass('btn-primary').addClass('btn-light');
                            $(modal_id).find('textarea[name="guard_remarks"]').attr('disabled',false);
                            $(modal_id).find('input[name="actual_ob_time_out"]').attr('disabled',false);
                            $(modal_id).find('input[name="actual_ob_time_in"]').attr('disabled',false);
                            _handleResetForm();
                        },200);
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvOfficialBusinessForm && fvOfficialBusinessForm.validate().then(function (v) {
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
                                        fvOfficialBusinessForm.resetForm();
                                        _handleResetForm();
                                        if(_this.attr('data-id')){ modal_state(modal_id); }
                                        if($(_table).length){
                                            _table ?$(_table).DataTable().ajax.reload(null, false) :'';
                                        }else{
                                            dtOfficialBusinessForm().init();
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
                });
            })
        }

        function _handleResetForm()
        {
            let now = new Date();
            let formattedDate = ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2) + '-' + now.getFullYear();
            let formattedTime = now.toTimeString().slice(0, 5);
            $('input[name="actual_ob_time_out"]').each(function() {
                this._flatpickr.setDate(formattedTime);
                $(this).attr('disabled',false);
            });
            $('textarea[name="guard_remarks"]').val('');
        }

        return {
            init: function () {
                _handlefvOfficialBusinessForm();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvOfficialBusinessForm.init();
    });

}
