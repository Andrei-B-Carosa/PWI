"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js"
import {trigger_select} from "../../../global/select.js"


export var dtOverTime = function (param=false) {

    const _page = $('.page-overtime-request');
    const _table = 'overtime_request';
    // const _tab = $(`.${_table}`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            'hris/employee/request/overtime/dt',
            {
                filter_year:$('select[name="filter_year"]').val(),
                filter_month:$('select[name="filter_month"]').val(),
                filter_status:$('input[name="filter_status"]:checked').val(),
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
                    data: "overtime_date", name: "overtime_date", title: "overtime_date",
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
                },
                {
                    data: "is_approved", name: "is_approved", title: "Status",
                    sortable:false,
                    searchable:false,
                    className:'',
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Approved"],
                            2: ["danger", "Disapproved"],
                            null:["secondary","Pending"]
                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "approver_level", name: "approver_level", title: "Approver Level",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                // {
                //     data: "approver_type", name: "approver_type", title: "Approver Type",
                //     sortable:false,
                //     searchable:false,
                //     visible:false,
                // },
                {
                    data: "approved_by", name: "approved_by", title: "Last Approver",
                    sortable:false,
                    searchable:false,
                    className:'',
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
                    data: "approver_remarks", name: "approver_remarks", title: "Remarks",
                    sortable:false,
                    searchable:false,
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }

                        return data;
                    }
                },
                {
                    data: "encrypted_id",
                    name: "encrypted_id",
                    title: "Action",
                    sortable:false,
                    className: "text-center",
                    responsivePriority: -1,
                    render: function (data, type, row) {

                        if(row.is_approved == 1 || row.is_approved === null){
                            return `
                                <a href="javascript:;" class="btn btn-icon btn-icon btn-info btn-sm hover-elevate-up history" data-id="${data}"
                                data-bs-toggle="tooltip" title="View Approval History" id="">
                                    <i class="ki-duotone ki-burger-menu-5 fs-2"></i>
                                </a>
                            `;
                        }

                        return `<div class="d-flex justify-content-center flex-shrink-0">
                            <a href="#" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="More Actions">
                                <i class="ki-duotone ki-pencil fs-2x">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
                                <div class="menu-item px-3 text-start">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                        More Actions
                                    </div>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="javascript:;" data-id="${data}" class="menu-link px-3 view-details">
                                        Edit Details
                                    </a>
                                </div>
                            </div>

                            <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
                             data-bs-toggle="tooltip" title="Delete this record">
                                <i class="ki-duotone ki-trash fs-2x">
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

            _page.off();

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

            $(`#${_table}_table`).on('click','.delete',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.confirm('question','Do you want to delete this overtime request ?',{
                    onConfirm: function() {
                        formData.append('id',id);
                        _request.post('/hris/employee/request/overtime/delete',formData)
                        .then((res) => {
                            Alert.toast(res.status,res.message);
                            console.log(res.payload ==0)
                            if(res.payload ==0){
                                dtOverTime().init();
                            }else{
                                $(`#${_table}_table`).DataTable().ajax.reload(null, false);
                            }
                        })
                        .catch((error) => {
                            Alert.alert('error', "Something went wrong. Try again later", false);
                        })
                        .finally((error) => {

                        });
                    }
                });


            })

            $(`#${_table}_table`).on('click','.history',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                formData.append('id',id);
                _request.post('/hris/employee/request/overtime/view_history',formData)
                .then((res) => {
                    if(res.status =='success'){
                        let payload = JSON.parse(window.atob(res.payload));
                        let html = '';

                        let drawerWidth = payload.length ==1 ? ``: `{default:'300px', 'lg': '500px'}`;
                        $('#kt_activities_request').attr("data-kt-drawer-width",drawerWidth);

                        const drawerElement = document.querySelector("#kt_activities_request");
                        const _drawer = KTDrawer.getInstance(drawerElement);
                        _drawer.update();

                        $.each(payload, function(index, entry) {

                            let icon = `<i class="ki-duotone ki-check fs-2 text-success"></i>`;
                            if(entry.is_approved =='rejected'){
                                icon =`
                                    <i class="ki-duotone ki-cross fs-2 text-danger">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>`;
                            }else if(entry.is_approved == 'pending'){
                                icon =`
                                    <i class="ki-duotone ki-information fs-2 text-info">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>`;
                            }

                            html+=`
                            <div class="timeline-item">
                            <div class="timeline-line"></div>
                            <div class="timeline-icon ${entry.is_approved=='rejected'?'bg-light-danger border-danger':
                                (entry.is_approved=='approved'?'bg-light-success border-success':'bg-light-info border-info')}">
                               ${icon}
                            </div>
                            <div class="timeline-content mt-n1">
                                <div class="pe-3 mb-5">
                                    <div class="fs-5 fw-semibold mb-2">
                                     ${entry.action}
                                    </div>
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div class="text-muted me-2 fs-7">${entry.recorded_at}</div>
                                        ${entry.is_final_approver ?`<a href="#" class="text-primary fw-bold me-1">Final Approver</a>`:``}
                                    </div>
                                </div>
                                ${
                                    entry.approver_remarks || entry.approver_remarks === null?`
                                        <div class="overflow-auto pb-5">
                                        <div class="d-flex align-items-center border border-dashed ${entry.is_approved == 'approved'?'border-success':'border-danger'}
                                                    rounded min-w-350px px-7 py-3 mb-5">
                                            ${entry.approver_remarks !== null ? `${'Remarks: '+entry.approver_remarks}`:`<span class="text-muted">No Remarks</span>`}
                                        </div>
                                    </div>
                                    `:``

                                }

                            </div>
                        </div>
                            `;
                        });

                        $('.approval-timeline').empty().append(html);
                        _drawer.toggle();
                    }
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {

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
