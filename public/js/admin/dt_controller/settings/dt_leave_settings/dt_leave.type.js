"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../../global.js"
import {trigger_select} from "../../../../global/select.js"


export var dtLeaveType = function (param) {

    const _page = $('.page-leave-settings');
    const _table = 'leave_type';
    const _tab = $(`.leave-type`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            'hris/admin/settings/leave_settings/leave_type/dt',
            {
                filter_status:false,
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
                    data: "code", name: "code", title: "Leave Code",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                // {
                //     data: "company_name", name: "company_name", title: "Company",
                //     sortable:false,
                //     searchable:false,
                //     visible:false,
                // },
                {
                    data: "name", name: "name", title: "Type of Leave",
                    render: function (data, type, row) {
                        return `
                                <div class="d-flex flex-column">
                                    <a href="javascript:;" class="text-gray-800 text-hover-primary mb-1">${data} (${row.code})</a>
                                </div>
                            `;
                    },
                },
                {
                    data: "description", name: "description", title: "Description",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        if(!data){
                            return '--';
                        }
                        return data;
                    },
                },
                {
                    data: "gender_type", name: "gender_type", title: "Gender Type",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["primary", "Male"],
                            2: ["primary", "Female"],
                            3: ["primary", "All Gender"],
                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },
                {
                    data: "is_active", name: "is_active", title: "Status",
                    sortable:false,
                    searchable:false,
                    render: function (data, type, row) {
                        let status = {
                            1: ["success", "Active"],
                            2: ["danger", "Inactive"],
                        };
                        return `<span class="badge badge-${status[data][0]}">${status[data][1]}</span>`;
                    },
                },

                {
                    data: "last_updated_at", name: "last_updated_at", title: "Last Updated At",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "last_updated_by", name: "last_updated_by", title: "Last Updated By",
                    sortable:false,
                    searchable:false,
                    className:'',
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }
                        return `<div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <span>
                                        <div class="symbol-label fs-3 bg-light-info text-info">
                                            ${data[0]}
                                        </div>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 text-hover-primary mb-1">
                                        ${data}
                                    </span>
                                    <span class="text-muted fs-8">${row.last_updated_at}</span>
                                </div>
                            </div>
                        `
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
                                        View Details
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

            _tab.off();
            _tab.on('change','select.filter_table',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()
                initTable();
            })

            _tab.on('keyup','.search',function(e){
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
                let url   =_this.attr('rq-url');
                let id    =_this.attr('data-id');
                let modal_id = '#modal_add_leave_type';
                let form = $('#form_add_leave_type');

                let formData = new FormData;

                formData.append('id',id);
                _request.post('/hris/admin/settings/leave_settings/leave_type/info',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));
                    form.find('input[name="name"]').val(payload.name);
                    form.find('input[name="code"]').val(payload.code);

                    // trigger_select('select[name="company_id"]',payload.company_id);
                    // trigger_select('select[name="company_location_id"]',payload.company_location_id);

                    form.find('select[name="gender_type"]').val(payload.gender_type).trigger('change');
                    form.find('select[name="is_active"]').val(payload.is_active).trigger('change');
                    form.find('textarea[name="description"]').val(payload.description);

                    $(modal_id).find('button.submit').attr('data-id',id);
                })
                .catch((error) => {
                    console.log(error);
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    $(modal_id).find('.modal_title').text('Edit Details');
                    modal_state(modal_id,'show');
                });

            })

            $(`#${_table}_table`).on('click','.delete',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.confirm('question','Delete this record ?',{
                    onConfirm: function() {
                        formData.append('id',id);
                        _request.post('/hris/admin/settings/leave_settings/leave_type/delete',formData)
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
