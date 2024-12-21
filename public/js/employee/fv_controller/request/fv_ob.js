"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"
import { dtObRequest } from "../../dt_controller/request/dt_ob.js";

export function fvObRequest(_table=false,param=false){

    var init_fvObRequest = (function () {
        const countUpInstances = {};
        var _handlefvObRequest = function(){
            let fvObRequest;
            let form = document.querySelector("#form_request_ob");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvObRequest = FormValidation.formValidation(form, {
                    fields: {
                        ob_filing_date: {
                            validators: {
                                ...fv_validator(),
                                callback: {
                                    message: 'The ob date must be within the current year',
                                    callback: function(input) {
                                        const inputDate = new Date(input.value);
                                        const currentYear = new Date().getFullYear();

                                        // Check if the input date's year matches the current year
                                        return inputDate.getFullYear() === currentYear;
                                    }
                                },
                                remote: {
                                    url: '/hris/employee/request/official_business/validate_request',
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function() {
                                        let data_id = $(modal_id).find('button.submit').attr('data-id');
                                        let obTimeOut = $('input[name="ob_time_out"]').val();
                                        let obTimeIn = $('input[name="ob_time_in"]').val();
                                        return {
                                            id: data_id,
                                            ob_time_out: obTimeOut,
                                            ob_time_in: obTimeIn
                                        };
                                    }
                                }
                            }
                        },
                        ob_time_out: fv_validator(),
                        ob_time_in: fv_validator(),
                        // contact_person: fv_validator(),
                        purpose: fv_validator(),
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
                        fvObRequest.resetForm();
                        form.reset();
                        $(modal_id).find('.submit').attr('data-id','');

                        let now = new Date();
                        let formattedDate = ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2) + '-' + now.getFullYear();
                        let formattedTime = now.toTimeString().slice(0, 5);

                        $('input[name="ob_filing_date"]').val(formattedDate);
                        $('input[name="ob_time_in"], input[name="ob_time_out"]').each(function() {
                            this._flatpickr.setDate(formattedTime);
                        });
                        $('select[name="contact_person"]').val('').trigger('change');

                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvObRequest && fvObRequest.validate().then(function (v) {
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
                                        fvObRequest.resetForm();
                                        _Handlewidgets();
                                        if(_this.attr('data-id')){ modal_state(modal_id); }
                                        if($(_table).length){
                                            _table ?$(_table).DataTable().ajax.reload(null, false) :'';
                                        }else{
                                            dtObRequest().init();
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

        function _Handlewidgets()
        {
            (new RequestHandler).get("/hris/employee/request/official_business/widgets")
                .then((res) => {
                    let payload = res.payload;

                    const countUpMapping = {
                        pending_requests: 'kt_countup_1',
                        approved_requests: 'kt_countup_2',
                        rejected_requests: 'kt_countup_3',
                        total_requests: 'kt_countup_4'
                    };

                    Object.entries(countUpMapping).forEach(([key, id]) => {
                        const value = payload[key];

                        // Check if instance already exists
                        if (countUpInstances[id]) {
                            // Update the existing instance
                            countUpInstances[id].update(value);
                        } else {
                            // Create a new instance and store it in the cache
                            const countUpInstance = new countUp.CountUp(id, value);
                            if (!countUpInstance.error) {
                                countUpInstance.start();
                                countUpInstances[id] = countUpInstance; // Cache the instance
                            } else {
                                console.error(`CountUp Error for ${id}:`, countUpInstance.error);
                            }
                        }
                    });
                })
                .catch((error) => {
                    console.error(error);

                })
                .finally(() => {
                });
        }

        return {
            init: function () {
                _handlefvObRequest();
                _Handlewidgets();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvObRequest.init();
    });

}
