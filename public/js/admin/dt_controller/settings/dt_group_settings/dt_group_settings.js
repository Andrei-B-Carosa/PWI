"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../../global.js"
import {trigger_select} from "../../../../global/select.js"


export var dtGroupSettings = function (param) {

    const _page = $('.page-group-settings');
    const _table = 'group';
    const _tab = $(`.${_table}`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        // dataTableHelper.initTable(
        //     'hris/admin/settings/approver_settings/dt',
        //     {
        //         leave_setting_id:param,
        //         filter_classification :$('select[name="filter_classification"]').val(),
        //         filter_employment :$('select[name="filter_employment"]').val(),
        //     },
        //     [
        //         {
        //             data: "count",
        //             name: "count",
        //             title: "No.",
        //             responsivePriority: -3,
        //             searchable:false,
        //         },

        //         {
        //             data: "employee_name", name: "employee_name", title: "Employee",
        //             sortable:false,
        //             className:'',
        //             render(data,type,row)
        //             {
        //                 if(!data){
        //                     return '--';
        //                 }
        //                 return `<div class="d-flex align-items-center">
        //                         <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
        //                             <span>
        //                                 <div class="symbol-label fs-3 bg-light-primary text-primary">${data[0]}</div>
        //                             </span>
        //                         </div>
        //                         <div class="">
        //                             <span class="text-gray-800 d-block text-hover-primary mb-1">
        //                                 ${data}
        //                             </span>
        //                             <span class="badge badge-outline badge-primary ">${row.emp_no}</span>
        //                             <div class="fs-7 text-muted fw-bolder">Approver Level ${row.approver_level}</div>
        //                         </div>
        //                     </div>
        //                 `
        //             }
        //         },
        //         {
        //             data: "emp_no", name: "emp_no", title: "Employee No.",
        //             sortable:false,
        //             visible:false
        //         },
        //         {
        //             data: "approver_level", name: "approver_level", title: "Approver Level",
        //             sortable:false,
        //             searchable:false,
        //             visible:false
        //         },
        //         {
        //             data: "is_required", name: "is_required", title: "Is Approval Required",
        //             sortable:false,
        //             searchable:false,
        //             className:'text-center',
        //             render(data,type,row){
        //                 let is_required = {
        //                     1: { icon: "ki-check-circle", color: "text-success" },
        //                     null: { icon: "ki-cross-circle", color: "text-danger" },
        //                 };
        //                 return`<i class="ki-duotone ${is_required[data].icon} ${is_required[data].color} fs-3">
        //                             <span class="path1"></span>
        //                             <span class="path2"></span>
        //                         </i>`;
        //             }
        //         },
        //         {
        //             data: "is_final_approver", name: "is_final_approver", title: "Is Final Approver",
        //             sortable:false,
        //             searchable:false,
        //             className:'text-center',
        //             render(data,type,row){
        //                 let is_required = {
        //                     1: { icon: "ki-check-circle", color: "text-success" },
        //                     null: { icon: "ki-cross-circle", color: "text-danger" },
        //                 };
        //                 return`<i class="ki-duotone ${is_required[data].icon} ${is_required[data].color} fs-3">
        //                             <span class="path1"></span>
        //                             <span class="path2"></span>
        //                         </i>`;
        //             }
        //         },

        //         {
        //             data: "department_name", name: "department_name", title: "Assigned Department",
        //             sortable:false,
        //             searchable:false,
        //             render(data,type,row){
        //                 return`<div class="d-flex flex-column">
        //                             <span>${data}</span>
        //                             ${row.section_name ?`<span class="text-muted">${row.section_name}</span>`: ``}
        //                         </div>`;
        //             }
        //         },

        //         {
        //             data: "section_name", name: "section_name", title: "Section",
        //             sortable:false,
        //             searchable:false,
        //             className:'text-center',
        //             visible:false,
        //         },

        //         {
        //             data: "last_updated_date", name: "last_updated_date", title: "Last Updated Date",
        //             sortable:false,
        //             searchable:false,
        //             className:'text-center',
        //             visible:false,
        //         },

        //         {
        //             data: "last_updated_by", name: "last_updated_by", title: "Last Updated By",
        //             sortable:false,
        //             searchable:false,
        //             className:'',
        //             render(data,type,row)
        //             {
        //                 if(!data){
        //                     return '--';
        //                 }
        //                 return `<div class="d-flex align-items-center">
        //                         <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
        //                             <span>
        //                                 <div class="symbol-label fs-3 bg-light-info text-info">
        //                                     ${data[0]}
        //                                 </div>
        //                             </span>
        //                         </div>
        //                         <div class="d-flex flex-column">
        //                             <span class="text-gray-800 text-hover-primary mb-1">
        //                                 ${data}
        //                             </span>
        //                             <span class="text-muted fs-7">${row.last_updated_date}</span>
        //                         </div>
        //                     </div>
        //                 `
        //             }
        //         },
        //         {
        //             data: "encrypted_id",
        //             name: "encrypted_id",
        //             title: "Action",
        //             sortable:false,
        //             className: "text-center",
        //             responsivePriority: -1,
        //             render: function (data, type, row) {
        //                 return `<div class="d-flex justify-content-center flex-shrink-0">
        //                     <a href="#" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
        //                     data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="More Actions">
        //                         <i class="ki-duotone ki-pencil fs-2x">
        //                             <span class="path1"></span>
        //                             <span class="path2"></span>
        //                             <span class="path3"></span>
        //                             <span class="path4"></span>
        //                         </i>
        //                     </a>
        //                     <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
        //                         <div class="menu-item px-3 text-start">
        //                             <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
        //                                 More Actions
        //                             </div>
        //                         </div>
        //                         <div class="menu-item px-3">
        //                             <a href="javascript:;" data-id="${data}" class="menu-link px-3 view-details">
        //                                 View Details
        //                             </a>
        //                         </div>
        //                     </div>

        //                     <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
        //                      data-bs-toggle="tooltip" title="Delete this record">
        //                         <i class="ki-duotone ki-trash fs-2x">
        //                             <span class="path1"></span>
        //                             <span class="path2"></span>
        //                             <span class="path3"></span>
        //                             <span class="path4"></span>
        //                         </i>
        //                     </a>
        //                 </div>`;
        //             },
        //         },
        //     ],
        //     null,
        // );

        dataTableHelper.initTable(
            'hris/admin/settings/group_settings/dt',
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
                    data: "name", name: "name", title: "Group",
                    sortable:false,
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
                    data: "approvers_count", name: "approvers_count", title: "Total Approver",
                    searchable:false,
                    sortable:false,
                    className:'text-center text-muted',
                },
                {
                    data: "members_count", name: "members_count", title: "Total Member",
                    searchable:false,
                    className:'text-center text-muted',
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
                                    <span class="text-muted fs-8">Last Updated</span>
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
                                        Edit Details
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="group_details/${data}" class="menu-link px-3">
                                        View Group
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
                let modal_id = '#modal_add_group';
                let form = $('#form_add_group');

                let formData = new FormData;

                formData.append('id',id);
                _request.post('/hris/admin/settings/group_settings/info',formData)
                .then((res) => {
                    let payload = JSON.parse(window.atob(res.payload));
                    form.find('input[name="name"]').val(payload.name);
                    form.find('select[name="is_active"]').val(payload.is_active).trigger('change');
                    form.find('textarea[name="description"]').val(payload.description);
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

                Alert.confirm('question','Delete this record ?',{
                    onConfirm: function() {
                        formData.append('id',id);
                        _request.post('/hris/admin/settings/group_settings/delete',formData)
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
