"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components, fv_validator} from "../../../../global.js"
import {trigger_select} from "../../../../global/select.js"


export var dtManualCredit = function (param) {

    const _page = $('.page-manual-credit-settings');
    const _table = 'manual_credit';
    // const _tab = $(`.${_table}`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            'hris/admin/settings/leave_settings/manual_credit/dt',
            {
                leave_setting_id:param,
                filter_classification :$('select[name="filter_classification"]').val(),
                filter_employment :$('select[name="filter_employment"]').val(),
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: "No.",
                    responsivePriority: -3,
                    searchable:false,
                },

                {
                    data: "employee_name", name: "employee_name", title: "Employee",
                    sortable:false,
                    className:'',
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }
                        return `<div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <span>
                                        <div class="symbol-label fs-3 bg-light-info text-info">${data[0]}</div>
                                    </span>
                                </div>
                                <div class="">
                                    <span class="text-gray-800 d-block text-hover-primary mb-1">
                                        ${data}
                                    </span>
                                    <span class="badge badge-outline badge-primary ">${row.emp_no}</span>
                                </div>
                            </div>
                        `
                    }
                },
                {
                    data: "date_employed", name: "date_employed", title: "Date Employed",
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "classification", name: "classification", title: "Classification",
                    sortable:false,
                    searchable:false,
                },

                {
                    data: "emp_no", name: "emp_no", title: "Employee No.",
                    sortable:false,
                    visible:false
                },
                {
                    data: "url", name: "url", title: "Action",
                    sortable:false,
                    searchable:false,
                    visible:false
                },
                {
                    data: "employment", name: "employment", title: "Employment Type",
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "manual_leave_credit", name: "manual_leave_credit", title: "Manual Leave Credit",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        return ` <input type="text" name="manual_leave_credit" class="form-control form-control-sm" data-action = "${row.url}" data-id="${row.encrypted_id}">`;
                    }
                },
                {
                    data: "available_leave", name: "available_leave", title: "Available Leave",
                    sortable:false,
                    searchable:false,
                    className:'text-center',
                },
                // {
                //     data: "used_leave", name: "used_leave", title: "Used Leave",
                //     sortable:false,
                //     searchable:false,
                // },
                {
                    data: "encrypted_id", name: "encrypted_id", title: "Encrypted ID",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
            ],
            null,
        );

        $(`#${_table}_table`).ready(function() {

            $(`#${_table}_table`).on('keyup','input[name="manual_leave_credit"]',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                let _this = $(this);
                if (!$.isNumeric(_this.val())) {
                    _this.val(null);
                    return;
                }
                _this.attr('disabled',true);

                let formData = new FormData;
                formData.append('leave_setting_id',param);
                formData.append('emp_id',_this.attr('data-id'));
                formData.append('leave_balance',_this.val());

                _request.post('/hris/admin/settings/leave_settings/manual_credit/'+_this.attr('data-action'),formData)
                .then((res) => {
                    Alert.toast(res.status,res.message);
                    if(res.payload ==0){
                        initTable();
                    }else{
                        $(`#${_table}_table`).DataTable().ajax.reload(null, false);
                    }
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    _this.attr('disabled',false);
                });
            })

            _page.on('change','select.filter_table',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

            _page.on('keyup','.search',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                let searchTerm = $(this).val();
                if ((e.key === 'Enter' || e.keyCode === 13) && searchTerm !== '') {
                    dataTableHelper.search(searchTerm);
                } else if (e.keyCode === 8 || e.key === 'Backspace') {
                    setTimeout(() => {
                        let updatedSearchTerm = $(this).val();
                        if (updatedSearchTerm === '') {
                            dataTableHelper.search('');
                        }
                    }, 0);
                }
            })

        })

    }

    return {
        init: function () {
            initTable();
        }
    }

}
