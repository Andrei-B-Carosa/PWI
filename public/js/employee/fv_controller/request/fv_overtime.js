"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"
import { dtOverTime } from "../../dt_controller/request/dt_overtime.js";

export function fvOverTime(_table=false,param=false){

    var init_fvOvertime = (function () {
        const countUpInstances = {};
        var _handlefvOverTime = function(){
            let fvOverTime;
            let form = document.querySelector("#form_request_overtime");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvOverTime = FormValidation.formValidation(form, {
                    fields: {
                        overtime_date: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                callback: {
                                    message: 'The overtime date must be within the current year',
                                    callback: function(input) {
                                        const inputDate = new Date(input.value);
                                        const currentYear = new Date().getFullYear();

                                        // Check if the input date's year matches the current year
                                        return inputDate.getFullYear() === currentYear;
                                    }
                                },
                                remote: {
                                    url: '/hris/employee/request/overtime/validate_request',
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function() {
                                        let data_id = $(modal_id).find('button.submit').attr('data-id');
                                        let overtimeFrom = $('input[name="overtime_from"]').val();
                                        let overtimeTo = $('input[name="overtime_to"]').val();
                                        return {
                                            id: data_id,
                                            overtime_from: overtimeFrom,
                                            overtime_to: overtimeTo
                                        };
                                    }
                                }
                            }
                        },
                        overtime_from: fv_validator(),
                        overtime_to: {
                            validators: {
                                ...fv_validator(),
                                // callback: {
                                //     message: 'Overtime must be at least 1 hour',
                                //     callback: function(input) {
                                //         const overtimeFrom = form.querySelector('[name="overtime_from"]').value;
                                //         const overtimeTo = input.value;

                                //         if (!overtimeFrom || !overtimeTo) {
                                //             return true;
                                //         }

                                //         const startTime = new Date(`1970-01-01T${overtimeFrom}:00`);
                                //         const endTime = new Date(`1970-01-01T${overtimeTo}:00`);

                                //         return (endTime - startTime) >= 60 * 60 * 1000;
                                //     }
                                // }
                            }
                        },
                        reason: fv_validator(),
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
                        fvOverTime.resetForm();
                        form.reset();
                        $(modal_id).find('.submit').attr('data-id','');

                        let now = new Date();
                        let formattedDate = ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2) + '-' + now.getFullYear();
                        let formattedTime = now.toTimeString().slice(0, 5);

                        $('input[name="overtime_date"]').val(formattedDate);
                        $('input[name="overtime_from"], input[name="overtime_to"]').each(function() {
                            this._flatpickr.setDate(formattedTime);
                        });
                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvOverTime && fvOverTime.validate().then(function (v) {
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
                                        fvOverTime.resetForm();
                                        _Handlewidgets();
                                        if(_this.attr('data-id')){ modal_state(modal_id); }
                                        if($(_table).length){
                                            _table ?$(_table).DataTable().ajax.reload(null, false) :'';
                                        }else{
                                            dtOverTime().init();
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
            (new RequestHandler).get("/hris/employee/request/overtime/widgets")
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
                _handlefvOverTime();
                _Handlewidgets();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvOvertime.init();
    });

}
