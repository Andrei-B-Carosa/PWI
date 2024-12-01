"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js"
import {trigger_select} from "../../../global/select.js"


export var dtOvertimeRequisition = function (param=false) {

    const _page = $('.page-ot-requisition');
    const _table = 'overtime_requisition';
    // const _tab = $(`.${_table}`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            'hris/employee/approvals/overtime_requisition/dt',
            {
                filter_year:$('select[name="filter_year"]').val(),
                filter_month:$('select[name="filter_month"]').val(),
                filter_status:$('input[name="filter_status"]:checked').val(),
            },
            [
                {
                    data: "count",
                    name: "count",
                    title: "#",
                    responsivePriority: -3,
                    searchable:false,
                },
                {
                    data: "requestor", name: "requestor", title: "Employee",
                    sortable:false,
                    className:'',
                    render(data,type,row)
                    {
                        return `<div class="d-flex align-items-center">
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 text-hover-primary mb-1">
                                        ${data}
                                    </span>
                                    <span class="text-muted fs-7">${row.position_name}</span>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    data: "position_name", name: "position_name", title: "Position Name",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "overtime_date", name: "overtime_date", title: "Filing Date",
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "overtime_from", name: "overtime_from", title: "From",
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "overtime_to", name: "overtime_to", title: "To",
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "reason", name: "reason", title: "Reason",
                    sortable:false,
                    searchable:false,
                    render:function(data,type,row){
                        return `<span class="cursor-pointer" data-bs-toggle="tooltip" title="${row.reason}">
                                    ${row.reason_short ?? data}
                             </span>`;
                    }
                },
                {
                    data: "reason_short", name: "reason_short", title: "Reason Short",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "is_approved", name: "is_approved", title: "Status",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "approver_level", name: "approver_level", title: "Approver Level",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "is_current_approver", name: "is_current_approver", title: "Is Current Approver",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "approver_type", name: "approver_type", title: "Approver Type",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "approved_by", name: "approved_by", title: "Approver",
                    sortable:false,
                    searchable:false,
                    className:'text-start',
                    render(data,type,row)
                    {
                        if(!data){
                            return '<span class="text-muted">Waiting for approval</span>';
                        }
                        return `<div class="d-flex align-items-center">
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 text-hover-primary mb-1">
                                        ${data}
                                    </span>
                                    <span class="text-muted fs-7">${row.approver_level}</span>
                                </div>
                            </div>
                        `
                    }
                },

                {
                    data: "approver_status", name: "approver_status", title: "Status",
                    sortable:false,
                    searchable:false,
                    className:'',
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Approved"],
                            2: ["danger", "Disapproved"],
                            null:["secondary","Pending"]
                        };
                        if(status[data]){
                            return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                        }else{
                            return `<span class="text-muted fs-6">${data}</span>`;
                        }
                    },
                },
                {
                    data: "approver_remarks", name: "approver_remarks", title: "Remarks",
                    sortable:false,
                    searchable:false,
                    render(data,type,row)
                    {
                        if(!data){
                            return '<span class="text-muted">--</span>';
                        }

                        return data;
                    }
                },
                {
                    data: "encrypted_id",
                    name: "encrypted_id",
                    title: "Actions",
                    sortable:false,
                    className: "text-end min-w-100px",
                    responsivePriority: -1,
                    render: function (data, type, row) {
                        if(row.is_approved == 2 || row.is_approved == 1 || row.is_current_approver === false){
                            return `
                                <a href="javascript:;" class="btn btn-icon btn-icon btn-info btn-sm hover-elevate-up history" data-id="${data}"
                                data-bs-toggle="tooltip" title="View Approval History">
                                    <i class="ki-duotone ki-burger-menu-5 fs-2"></i>
                                </a>
                            `;
                        }
                        return `
                            <a href="javascript:;" class="btn btn-icon btn-icon btn-success btn-sm hover-elevate-up approve" data-id="${data}"
                             data-bs-toggle="tooltip" title="Approve Request">
                                <i class="ki-duotone ki-check fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                            <a href="javascript:;" class="btn btn-icon btn-icon btn-danger btn-sm hover-elevate-up disapprove" data-id="${data}"
                             data-bs-toggle="tooltip" title="Disapprove Request">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                        </div>`;
                    },
                },
            ],
            null,
        );

        $(`#${_table}_table`).ready(function() {

            _page.on('click','button.filter',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

            _page.on('click','button.reset',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                $('select[name="filter_year"]').val('').trigger('change');
                $('select[name="filter_month"]').val('').trigger('change');
                $('input[name="filter_status"][value="all"]').prop('checked', true).trigger('change');
                initTable();
            })

            _page.on('keyup','.search',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                let searchTerm = $(this).val();
                if (e.key === 'Enter' || e.keyCode === 13) {
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

            $(`#${_table}_table`).on('click','.view-details',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');

                let modal_id = '#modal_request_overtime';
                let form = $('#form_request_overtime');

                let formData = new FormData;
                formData.append('id',id);
                _request.post('/hris/employee/request/overtime/info',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));
                    $('input[name="overtime_date"]').val(payload.overtime_date);
                    $('input[name="overtime_from"]')[0]._flatpickr.setDate(payload.overtime_from, true);
                    $('input[name="overtime_to"]')[0]._flatpickr.setDate(payload.overtime_to, true);
                    $('textarea[name="reason"]').val(payload.reason);
                    $(modal_id).find('button.submit').attr('data-id',id);
                })
                .catch((error) => {
                    console.log(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    modal_state(modal_id,'show');
                });

            })

            $(`#${_table}_table`).on('click','.approve',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.input('question','Do you want to approve this request ?',{
                    isRequired: false,
                    inputPlaceholder: "Put your reason",
                    onConfirm: function(approver_remarks='') {
                        formData.append('id',id);
                        formData.append('approver_remarks',approver_remarks);
                        formData.append('is_approved',1);
                        _request.post('/hris/employee/approvals/overtime_requisition/update',formData)
                        .then((res) => {
                            Alert.toast(res.status,res.message);
                            initTable();
                        })
                        .catch((error) => {
                            Alert.alert('error', "Something went wrong. Try again later", false);
                        })
                        .finally((error) => {

                        });
                    }
                });
            })


            $(`#${_table}_table`).on('click','.disapprove',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.input('question','Do you want to disapprove this request ?',{
                    isRequired: true,
                    inputPlaceholder: "Put your reason",
                    onConfirm: function(approver_remarks) {
                        formData.append('id',id);
                        formData.append('approver_remarks',approver_remarks);
                        formData.append('is_approved',2);
                        _request.post('/hris/employee/approvals/overtime_requisition/update',formData)
                        .then((res) => {
                            Alert.toast(res.status,res.message);
                            initTable();
                        })
                        .catch((error) => {
                            Alert.alert('error', "Something went wrong. Try again later", false);
                        })
                        .finally((error) => {

                        });
                    }
                });


            })

        })

    }

    return {
        init: function () {
            initTable();
        }
    }

}
