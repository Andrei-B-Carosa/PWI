"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,fv_validator} from "../../../global.js"
import { dtLeaveRequest } from "../../dt_controller/request/dt_leave.js";
import { trigger_select } from "../../../global/select.js";

export function fvLeaveRequest(_table=false,param=false){

    var init_fvLeaveRequest = (function () {

        const countUpInstances = {};

        var _handlefvLeaveRequest = function(){
            let fvLeaveRequest;
            let form = document.querySelector("#form_request_overtime");
            let modal_id = form.getAttribute('modal-id');
            let modalContent = document.querySelector(`${modal_id} .modal-content`);

            let blockUI = new KTBlockUI(modalContent, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvLeaveRequest = FormValidation.formValidation(form, {
                    fields: {
                        leave_filing_date: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                callback: {
                                    message: 'The leave date must be within the current year',
                                    callback: function(input) {
                                        const inputDate = new Date(input.value);
                                        const currentYear = new Date().getFullYear();

                                        // Check if the input date's year matches the current year
                                        return inputDate.getFullYear() === currentYear;
                                    }
                                },
                                remote: {
                                    url: '/hris/employee/request/leave/validate_request',
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function() {
                                        let data_id = $(modal_id).find('button.submit').attr('data-id');
                                        let leaveFrom = $('input[name="leave_date_from"]').val();
                                        let leaveTo = $('input[name="leave_date_to"]').val();
                                        let leaveTypeId = $('select[name="leave_type_id"]').val();
                                        return {
                                            id: data_id,
                                            leave_type_id: leaveTypeId,
                                            leave_date_from: leaveFrom,
                                            leave_date_to: leaveTo
                                        };
                                    }
                                }
                            }
                        },
                        leave_date_from: {
                            validators: {
                                ...fv_validator(),
                                callback: {
                                    message: 'The start date cannot be a weekend (Saturday or Sunday)',
                                    callback: function (input) {
                                        const leaveDateFrom = input.value;

                                        // Check if the 'from' date is filled
                                        if (!leaveDateFrom) {
                                            return true; // Allow if 'leave_date_from' is empty (handled by other validators)
                                        }

                                        // Parse the 'from' date using the format (MM/DD/YYYY)
                                        const fromDate = new Date(leaveDateFrom);

                                        // Check if the 'from' date is a weekend (Saturday or Sunday)
                                        const isFromWeekend = fromDate.getDay() === 0 || fromDate.getDay() === 6;

                                        if (isFromWeekend) {
                                            return {
                                                valid: false,
                                                message: 'The start date cannot be a weekend (Saturday or Sunday)',
                                            };
                                        }

                                        return true;
                                    }
                                }
                            }
                        },
                        leave_date_to: {
                            validators: {
                                ...fv_validator(),
                                callback: {
                                    message: 'The end date cannot be earlier than the start date',
                                    callback: function (input) {
                                        const leaveDateFrom = form.querySelector('[name="leave_date_from"]').value;
                                        const leaveDateTo = input.value;

                                        // Check if both dates are filled
                                        if (!leaveDateFrom || !leaveDateTo) {
                                            return true; // Allow if any of the fields is empty (handled by other validators)
                                        }

                                        // Parse the dates using the format (MM/DD/YYYY)
                                        const fromDate = new Date(leaveDateFrom);
                                        const toDate = new Date(leaveDateTo);

                                        // Check if the 'to' date is a weekend (Saturday or Sunday)
                                        const isToWeekend = toDate.getDay() === 0 || toDate.getDay() === 6;

                                        if (isToWeekend) {
                                            return {
                                                valid: false,
                                                message: 'The end date cannot be a weekend (Saturday or Sunday)',
                                            };
                                        }

                                        // Check if 'to' date is not earlier than 'from' date
                                        if (toDate < fromDate) {
                                            return {
                                                valid: false,
                                                message: 'The end date cannot be earlier than the start date',
                                            };
                                        }

                                        return true;
                                    }
                                }
                            }
                        },
                        reason: fv_validator(),
                        is_excused: fv_validator(),
                        leave_type_id: {
                            validators: {
                                notEmpty:{
                                    message:'This field is required'
                                },
                                remote: {
                                    url: '/hris/employee/request/leave/check_leave_balance',
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    data: function() {
                                        let data_id = $(modal_id).find('button.submit').attr('data-id');
                                        let leaveFrom = $('input[name="leave_date_from"]').val();
                                        let leaveTo = $('input[name="leave_date_to"]').val();
                                        return {
                                            id: data_id,
                                            leave_date_from: leaveFrom,
                                            leave_date_to: leaveTo
                                        };
                                    }
                                }
                            }
                        },
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
                        fvLeaveRequest.resetForm();
                        form.reset();
                        $(modal_id).find('.submit').attr('data-id','');

                        let now = new Date();
                        let formattedDate = ('0' + (now.getMonth() + 1)).slice(-2) + '-' + ('0' + now.getDate()).slice(-2) + '-' + now.getFullYear();

                        // $('input[name="leave_filing_date"]').val(formattedDate).attr('disabled',false);
                        $('input[name="leave_date_from"]').val(formattedDate);
                        $('input[name="leave_date_to"]').val(formattedDate);
                        trigger_select('select[name="leave_type_id"]','Vacation Leave (VL)').then(() =>{
                            $('select[name="leave_type_id"]').attr('disabled',false);
                        });
                        $('select[name="is_excused"]').val('Yes').trigger('change');

                    }
                })
            })

            $(modal_id).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                fvLeaveRequest && fvLeaveRequest.validate().then(function (v) {
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
                                        fvLeaveRequest.resetForm();
                                        _Handlewidgets();
                                        if($(_table).length){
                                            _table ?$(_table).DataTable().ajax.reload(null, false) :'';
                                        }else{
                                            dtLeaveRequest().init();
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
            (new RequestHandler).get("/hris/employee/request/leave/widgets")
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
                _handlefvLeaveRequest();
                _Handlewidgets();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvLeaveRequest.init();
    });

}
