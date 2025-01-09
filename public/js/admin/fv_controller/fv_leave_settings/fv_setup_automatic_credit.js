"use strict";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {fv_validator, fv_numeric} from "../../../global.js"

export function fvLeaveSetup(_table=false,param=false){

    var init_fvLeaveSetup = (function () {

        let rowIndex = 0;
        let fvLeaveCondition;

        var _handlefvLeaveSetup = function(){
            let fvLeaveSetup ,form = document.querySelector("#form_leave_setup");

            let card = form.getAttribute('card-class');
            let _div = document.querySelector(`${card}`);
            let blockUI = new KTBlockUI(_div, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading Condition...</div>',
            });

            if (!form.hasAttribute('data-fv-initialized')) {
                fvLeaveSetup = FormValidation.formValidation(form, {
                    fields: {
                        'classification_id[]':fv_validator(),
                        'employment_id[]':fv_validator(),
                        'location_id[]':fv_validator(),
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

            $(form).on('click','.submit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let url = form.getAttribute('action');
                let _form_id = 'form_leave_condition';
                fvLeaveSetup && fvLeaveSetup.validate().then(function (v) {
                    if(v == "Valid"){

                        _this.attr("data-kt-indicator","on");
                        _this.attr("disabled",true);
                        $(form).find('select').attr('disabled',true);

                        blockUI.block();

                        document.getElementById(_form_id).reset();
                        $("#"+_form_id).find('select.select2').val(null).trigger('change');
                        fvLeaveCondition.resetForm();
                        _handleResetRepeater();

                        let formData = new FormData(form);
                        formData.append('id',param);

                        let classifications = fvLeaveSetup.getElements('classification_id[]')
                        .map(select => Array.from(select.selectedOptions).map(option => option.value))
                        .flat();

                        let employment = fvLeaveSetup.getElements('employment_id[]')
                            .map(select => Array.from(select.selectedOptions).map(option => option.value))
                            .flat();

                        let location = fvLeaveSetup.getElements('location_id[]')
                            .map(select => Array.from(select.selectedOptions).map(option => option.value))
                            .flat();

                        formData.append('classifications',JSON.stringify(classifications));
                        formData.append('employment',JSON.stringify(employment));
                        formData.append('location',JSON.stringify(location));

                        (new RequestHandler).post(url,formData).then((res) => {
                            // Alert.toast(res.status,res.message);
                            if(res.status == 'success'){
                                let payload = JSON.parse(window.atob(res.payload));
                                if(payload){
                                    $(card).find('input[name="start_credit"]').val(payload.start_credit);
                                    $(card).find('select[name="fiscal_year"]').val(payload.fiscal_year).trigger('change');

                                    $(card).find('select[name="is_carry_over"]').val(payload.is_carry_over).trigger('change');
                                    $(card).find('select[name="carry_over_month"]').val(payload.carry_over_month).trigger('change');
                                    $(card).find('select[name="carry_over_day"]').val(payload.carry_over_day).trigger('change');

                                    // $(card).find('select[name="is_reset"]').val(payload.is_reset).trigger('change');
                                    $(card).find('select[name="reset_month"]').val(payload.reset_month).trigger('change');
                                    $(card).find('select[name="reset_day"]').val(payload.reset_day).trigger('change');

                                    let milestonesData = JSON.parse(payload.increment_milestones || '[]');
                                    let repeaterAddField = $('#repeater').find('[data-repeater-create]');
                                    milestonesData.forEach(function (item, index) {
                                        index >0 ?repeaterAddField.click(): '';

                                        let lastRepeaterItem = $('#repeater').find('[data-repeater-item]').last();
                                        lastRepeaterItem.find('input[name="milestones['+index +'][year]"]').val(item.year).attr('disabled',true);
                                        lastRepeaterItem.find('input[name="milestones['+index+'][credit]"]').val(item.credit).attr('disabled',true);
                                    });

                                    repeaterAddField.addClass('d-none');
                                    $('#repeater').find('[data-repeater-item]').each(function() {
                                        $(this).find('[data-repeater-delete]').addClass('d-none');
                                    });
                                    $(card).find('select[name="succeeding_year"]').val(payload.succeeding_year).trigger('change');
                                }

                                $(card).find('.edit').attr('disabled',false).removeClass('d-none');
                            }
                        })
                        .catch((error) => {
                            console.log(error)
                            Alert.alert('error',"Something went wrong. Try again later", false);
                        })
                        .finally(() => {
                            _this.attr("data-kt-indicator","off");
                            _this.attr("disabled",false);
                            $(form).find('select').attr('disabled',false);
                            blockUI.release();
                        });
                    }
                })
            })
        }

        var _handlefvLeaveCondition = function(){
            let form = document.querySelector("#form_leave_condition");
            let formSetupLeave = $("#form_leave_setup");
            let card = form.getAttribute('card-class');

            const _request = new RequestHandler;

            if (!form.hasAttribute('data-fv-initialized')) {

                $('#repeater').repeater({
                    initEmpty: false,
                    defaultValues: { 'text-input': 'foo' },
                    isFirstItemUndeletable: true,
                    show: function () {
                        const currentIndex = $(this).closest('[data-repeater-list]').find('[data-repeater-item]').index($(this));

                        const yearFieldName = `milestones[${currentIndex}][year]`;
                        const creditFieldName = `milestones[${currentIndex}][credit]`;

                        fvLeaveCondition.addField(yearFieldName,fv_numeric())
                        fvLeaveCondition.addField(creditFieldName,fv_numeric())
                        $(this).slideDown();
                    },

                    hide: function (deleteElement) {
                        $(this).find('[name]').each(function () {
                            const fieldName = $(this).attr('name');
                            if (fvLeaveCondition.getElements(fieldName)) {
                                fvLeaveCondition.removeField(fieldName);
                            }
                        });
                        $(this).slideUp(deleteElement, function () {
                            updateFieldNames();
                        });
                    }
                });

                fvLeaveCondition = FormValidation.formValidation(form, {
                    fields: {
                        'start_credit': {
                            validators: {
                                notEmpty: {
                                    message: 'Start Credit is required'
                                },
                                numeric: {
                                    message: 'Start Credit must be a number'
                                },
                                regexp: {
                                    // Regular expression for validating floating-point numbers
                                    regexp: /^[0-9]+(\.[0-9]+)?$/,
                                    message: 'Start Credit must be a valid number or float'
                                }
                            }
                        },
                        'fiscal_year':fv_validator(),
                        'is_carry_over':fv_validator(),
                        'succeeding_year':fv_validator(),
                        // 'is_reset':fv_validator(),
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

            $(card).on('click','.submit',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                let _this = $(this);
                let url = form.getAttribute('action');
                const _formData = new FormData(form);

                fvLeaveCondition && fvLeaveCondition.validate().then(function (v) {
                    if(v == "Valid"){
                        Alert.confirm("question","Submit this form?", {
                            onConfirm: function() {
                                //process setup data
                                let classifications = formSetupLeave.find('select[name="classification_id[]"] option:selected').map(function() {
                                    return $(this).val();
                                }).get();

                                let employment = formSetupLeave.find('select[name="employment_id[]"] option:selected').map(function() {
                                    return $(this).val();
                                }).get();

                                let location = formSetupLeave.find('select[name="location_id[]"] option:selected').map(function() {
                                    return $(this).val();
                                }).get();

                                let milestones = [];
                                form.querySelectorAll('[data-repeater-item]').forEach((item, index) => {
                                    const year = item.querySelector('.milestone-year').value;
                                    const credit = item.querySelector('.milestone-credit').value;
                                    milestones.push({ year, credit });
                                });

                                //append data to formData
                                _formData.append('id',param);
                                _formData.append('classifications',JSON.stringify(classifications));
                                _formData.append('employment',JSON.stringify(employment));
                                _formData.append('location',JSON.stringify(location));
                                _formData.append('milestones', JSON.stringify(milestones));

                                //process request
                                _request.post(url,_formData).then((res) => {
                                    Alert.toast(res.status,res.message);
                                    if(res.status == 'success'){
                                        $(card).find('.cancel').click();
                                    }
                                })
                                .catch((error) => {
                                    console.log(error)
                                    Alert.alert('error',"Something went wrong. Try again later", false);
                                })
                                .finally(() => {
                                    // _this.attr("data-kt-indicator","off");
                                    // _this.attr("disabled",false);
                                    // page_block.release();
                                });
                            }
                        });
                    }
                })
            })

            $(card).on('click','.edit',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);

                $(card).find('input, select').attr('disabled',false);
                $(card).find('.cancel, .submit').attr('disabled',false).removeClass('d-none');
                _this.addClass('d-none');

                formSetupLeave.find('.submit').attr('disabled',true).addClass('d-none');
                formSetupLeave.find('select').attr('disabled',true);

                let repeaterAddField = $('#repeater').find('[data-repeater-create]');
                repeaterAddField.removeClass('d-none');
                $('#repeater').find('[data-repeater-item]').each(function() {
                    $(this).find('[data-repeater-delete]').removeClass('d-none');
                });
            })

            $(card).on('click','.cancel',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);

                $(form).find('input, select').attr('disabled',true);

                $(card).find('.edit').attr('disabled',false).removeClass('d-none');
                $(card).find('.cancel, .submit').attr('disabled',true).addClass('d-none');

                formSetupLeave.find('.submit').attr('disabled',false).removeClass('d-none');
                formSetupLeave.find('select').attr('disabled',false);

                let repeaterAddField = $('#repeater').find('[data-repeater-create]');
                repeaterAddField.addClass('d-none');
                $('#repeater').find('[data-repeater-item]').each(function() {
                    $(this).find('[data-repeater-delete]').addClass('d-none');
                });
            })

            $(form).on('change','select[name="succeeding_year"]',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this).val();
                let _div = $('.succeeding_year');

                if(_this ==1){
                    fvLeaveCondition.addField('milestones[0][year]', fv_numeric());
                    fvLeaveCondition.addField('milestones[0][credit]', fv_numeric());
                    _div.removeClass('d-none');
                }else{
                    _div.addClass('d-none');
                    _handleResetRepeater();
                }
            })

            $(form).on('change','select[name="is_carry_over"]',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this).val();
                let _divCarryOver = $('.carry_over');
                let _divResetCredit = $('.reset_leave_credit');

                if(_this ==1){
                    fvLeaveCondition.addField('carry_over_month',fv_validator());
                    fvLeaveCondition.addField('carry_over_day',fv_validator());

                    if(fvLeaveCondition.getElements('reset_month') && fvLeaveCondition.getElements('reset_day')){
                        fvLeaveCondition.addField('reset_month',fv_validator())
                        fvLeaveCondition.addField('reset_day',fv_validator())
                    }
                    _divCarryOver.removeClass('d-none');
                    _divResetCredit.addClass('d-none');

                }else{
                    fvLeaveCondition.addField('reset_month',fv_validator())
                    fvLeaveCondition.addField('reset_day',fv_validator())

                    if(fvLeaveCondition.getElements('carry_over_month') && fvLeaveCondition.getElements('carry_over_day')){
                        fvLeaveCondition.removeField('carry_over_month')
                        fvLeaveCondition.removeField('carry_over_day')
                    }

                    _divCarryOver.addClass('d-none');
                    _divResetCredit.removeClass('d-none');
                }
            })

            function updateFieldNames() {
                $('#repeater').find('[data-repeater-item]').each(function (index) {
                    const $item = $(this);

                     // Skip items with display: none
                    if ($item.css('display') === 'none') {
                        return; // Continue to the next iteration
                    }

                    // Update each input's name attribute based on the current index
                    $item.find('[name]').each(function () {
                        const $input = $(this);
                        const oldName = $input.attr('name');
                        const newName = oldName.replace(/\[\d+\]/, `[${index}]`);

                        // Update the input name
                        $input.attr('name', newName);

                        // Update FormValidation.io
                        if (fvLeaveCondition.getElements(oldName)) {
                            fvLeaveCondition.removeField(oldName);
                        }
                        fvLeaveCondition.addField(newName, fv_numeric());
                    });
                });
            }
        }

        var _handleResetRepeater = function(){
            $('#repeater').find('[data-repeater-item]').each(function(index) {
                // Get input fields inside the current repeater item
                let currentIndex = index;
                $(this).find('[name]').each(function() {
                    const fieldName = $(this).attr('name');

                    // Remove the field from validation
                    if (fvLeaveCondition.getElements(fieldName)) {
                        fvLeaveCondition.removeField(fieldName);
                    }
                });

                if (currentIndex === 0) {
                    return true;  // Continue to the next iteration, effectively skipping the deletion
                }

                // Remove the current repeater item
                $(this).remove();
            });

            // Reset rowIndex to 0 (if you still need it elsewhere)
            rowIndex = 0;
        }

        return {
            init: function () {
                _handlefvLeaveSetup();
                _handlefvLeaveCondition();
            },
        };

    })();

    KTUtil.onDOMContentLoaded(function () {
        init_fvLeaveSetup.init();
    });

}
