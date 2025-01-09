"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../../global.js"
import {trigger_select} from "../../../../global/select.js"


export var dtApproverList = function (param) {

    const _table = 'approver_list';
    const _tab = $(`.${_table}`);
    const _page = $('.page-group-details');
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    const _request = new RequestHandler;

    function initTable(){

        dataTableHelper.initTable(
            'hris/admin/settings/group_details/approver/dt',
            {
                filter_status:false,
                group_id:param
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
                    data: "emp_name", name: "emp_name", title: "Employee",
                    sortable:false,
                    className:'',
                    render(data,type,row)
                    {
                        return `<div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <span>
                                        <div class="symbol-label fs-3 bg-light-primary text-primary">${data[0]}</div>
                                    </span>
                                </div>
                                <div class="">
                                    <span class="text-gray-800 d-block text-hover-primary mb-1">
                                        ${data}
                                    </span>
                                    <span class="badge badge-outline badge-primary ">${row.emp_no}</span>
                                    <div class="fs-7 text-muted fw-bolder">Approver Level ${row.approver_level}</div>
                                </div>
                            </div>`;
                    }
                },
                {
                    data: "approver_level", name: "approver_level", title: "Approver Level",
                    sortable:false,
                    searchable:false,
                    className:"text-muted text-center",
                    visible:false,

                },
                {
                    data: "emp_no", name: "emp_no", title: "Employee No.",
                    sortable:false,
                    visible:false,
                    searchable:false,

                },

                {
                    data: "is_required", name: "is_required", title: "Approval Required ?",
                    sortable:false,
                    searchable:false,
                    className:'text-center',
                    render(data,type,row){
                        let is_required = {
                            1: { icon: "ki-check-circle", color: "text-success" },
                            null: { icon: "ki-cross-circle", color: "text-danger" },
                        };
                        return`<i class="ki-duotone ${is_required[data].icon} ${is_required[data].color} fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>`;
                    }
                },
                {
                    data: "is_final_approver", name: "is_final_approver", title: "Final Approver ?",
                    sortable:false,
                    searchable:false,
                    className:'text-center',
                    render(data,type,row){
                        let is_required = {
                            1: { icon: "ki-check-circle", color: "text-success" },
                            null: { icon: "ki-cross-circle", color: "text-danger" },
                        };
                        return`<i class="ki-duotone ${is_required[data].icon} ${is_required[data].color} fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>`;
                    }
                },
                {
                    data: "date_joined", name: "date_joined", title: "Joined Date",
                    sortable:false,
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

            _tab.off('keyup', '.search').on('keyup','.search',function(e){
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
                let modal_id = '#modal_add_approver';
                let form = $('#form_add_approver');

                let formData = new FormData;

                formData.append('id',id);
                _request.post('/hris/admin/settings/group_details/approver/info',formData)
                .then((res) => {
                    if(res.status == 'success'){
                        let payload = JSON.parse(window.atob(res.payload));

                        form.find('select[name="is_active"]').val(payload.is_active).trigger('change');
                        form.find('select[name="approver_level"]').val(payload.approver_level).trigger('change');
                        $("input:checkbox[name='is_required']").attr("checked",payload.is_required??false);
                        $("input:checkbox[name='is_final_approver']").attr("checked",payload.is_final_approver??false);

                        trigger_select("select[name='approver_id']", payload.approver_id);
                        $("select[name='approver_id']").attr('disabled',true);
                        $(modal_id).find('button.submit').attr('data-id',id);
                    }
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
                formData.append('id',id);

                _request.post('/hris/admin/settings/group_details/approver/emp_details',formData)
                .then((res) => {
                    if(res.status =='success'){
                        let payload = JSON.parse(window.atob(res.payload));
                        Alert.confirm('question','Do you want to remove approver <br><b>'+payload.name+'</b> ?',{
                            onConfirm: function() {
                                _request.post('/hris/admin/settings/group_details/approver/delete',formData)
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
                    }
                })
            })

        })

    }

    return {
        init: function () {
            initTable();
        }
    }

}

export var dtGroupMember = function (param) {

    const _table = 'group_member';
    const _tab = $(`.${_table}`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            'hris/admin/settings/group_details/member/dt',
            {
                filter_status:false,
                group_id:param
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
                    data: "emp_name", name: "emp_name", title: "Employee",
                    sortable:false,
                    className:'',
                    render(data,type,row)
                    {
                        let status = {
                            1: ["success", "Active"],
                            2: ["danger", "Inactive"],
                        };
                        return `<div class="position-relative ps-6 pe-3 py-2">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-${status[row.is_active][0]}"></div>
                                    <span class="mb-1 text-dark text-hover-primary fw-bolder">
                                        ${data} <span class="badge badge-outline badge-primary ">${row.emp_no??'No Employee Number'}</span>
                                    </span>
                                    <div class="fs-7 text-muted fw-bolder">${row.emp_position}</div>
                                </div>`;
                    }
                },
                {
                    data: "emp_no", name: "emp_no", title: "Employee No.",
                    sortable:false,
                    visible:false,
                    searchable:false,
                },
                {
                    data: "emp_position", name: "emp_position", title: "Position",
                    sortable:false,
                    visible:false,
                    searchable:false,
                },
                {
                    data: "date_joined", name: "date_joined", title: "Joined Date",
                    sortable:false,
                    searchable:false,
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
                    data: "encrypted_id",
                    name: "encrypted_id",
                    title: "Action",
                    sortable:false,
                    className: "text-center",
                    responsivePriority: -1,
                    render: function (data, type, row) {
                        return `
                            <a href="javascript:;" class="btn btn-icon btn-sm btn-icon btn-light-danger btn-sm hover-elevate-up delete" data-id="${data}"
                             data-bs-toggle="tooltip" title="Remove Member">
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

            $(`#${_table}_table`).on('click','.delete',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;
                formData.append('id',id);
                formData.append('group_id',param);

                _request.post('/hris/admin/settings/group_details/member/emp_details',formData)
                .then((res) => {
                    if(res.status =='success'){
                        let payload = JSON.parse(window.atob(res.payload));
                        Alert.confirm('question','Remove member <b>'+payload.name +'</b> ?',{
                            onConfirm: function() {
                                formData.append('is_active',2);
                                _request.post('/hris/admin/settings/group_details/member/delete',formData)
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
                    }

                });


            })

            _tab.off('click', '.add-member').on('click','.add-member',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                dtEnrollGroupMember(param).init();

                setTimeout(() => {
                    modal_state('#modal_add_member','show');
                }, 100);
            });

            _tab.off('keyup', '.search').on('keyup','.search',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

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

        })

    }

    return {
        init: function () {
            initTable();
        }
    }

}

export var dtEnrollGroupMember = function (param) {
    const _table = 'employee_list';
    const _modal = $(`#modal_add_member`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            'hris/admin/settings/group_details/member/employee_list',
            {
                filter_status:false,
                group_id:param,
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
                    data: "emp_no", name: "emp_no", title: "Employee No.",
                    sortable:false,
                    visible:false
                },
                {
                    data: "emp_name", name: "emp_name", title: "Employee",
                    sortable:false,
                    className:'',
                    render(data,type,row)
                    {
                        let status = {
                            1: ["success", "Active"],
                            2: ["danger", "Inactive"],
                        };
                        return `<div class="position-relative ps-6 pe-3 py-2">
                                    <div class="position-absolute start-0 top-0 w-4px h-100 rounded-2 bg-${status[row.is_active][0]}"></div>
                                    <span class="mb-1 text-dark text-hover-primary fw-bolder">
                                        ${data} <br><span class="badge badge-outline badge-primary ">${row.emp_no??'No Employee Number'}</span>
                                    </span>
                                </div>`;
                    }
                },
                {
                    data: "emp_department", name: "emp_department", title: "Department",
                    sortable:false,
                    render(data,type,row)
                    {
                        return `${data}<br> <span class="text-muted fs-6">${row.emp_position}</span>`
                    }
                },
                {
                    data: "emp_position", name: "emp_position", title: "Position",
                    sortable:false,
                    visible:false,
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
                    data: "encrypted_id",
                    name: "encrypted_id",
                    title: "Action",
                    sortable:false,
                    className: "text-center",
                    responsivePriority: -1,
                    render: function (data, type, row) {
                        return `
                        <div class="form-check form-check-sm d-inline-block form-check-custom form-check-solid">
                                <input class="form-check-input enroll" type="checkbox" value="1" data-id="${data}"">
                            </div>`;
                    },
                },
            ],
            null,
        );


        $(`#${_table}_table`).ready(function() {

            _modal.off('keyup','.search').on('keyup','.search',function(e){
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

            $(`#${_table}_table`).on('click','.enroll',function(e){
                e.stopImmediatePropagation();
                // e.preventDefault();

                let _this = $(this);
                let id    =_this.attr('data-id');

                let formData = new FormData;
                formData.append('id',id);
                formData.append('group_id',param);
                formData.append('is_active',$(this).is(":checked")?1:2);
                _request.post('/hris/admin/settings/group_details/member/update',formData)
                .then((res) => {
                    Alert.toast(res.status,res.message);
                    if(res.status == 'success')
                    {
                        if($(`#${_table}_table`).length && _table){
                            $(`#${_table}_table`).DataTable().ajax.reload(null,false);
                        }else{
                            initTable();
                       }
                    }
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
                    dtGroupMember(param).init();
                });
            });

        })

    }

    return {
        init: function () {
            initTable();
        }
    }
}
