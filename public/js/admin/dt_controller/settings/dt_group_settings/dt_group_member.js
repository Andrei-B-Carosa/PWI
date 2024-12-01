"use strict";

import { DataTableHelper } from "../../../../global/datatable.js";
import {Alert} from "../../../../global/alert.js"
import {RequestHandler} from "../../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../../global.js"
import {trigger_select} from "../../../../global/select.js"


export var dtGroupMember = function (param) {

    const _page = $('.page-group-settings');
    const _table = 'group_members';
    const _tab = $(`.${_table}`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            'hris/admin/settings/group_details/member_list',
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
                        return `<div class="position-relative ps-6 pe-3 py-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Date Hired : ${row.date_employed}">
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
                    visible:false
                },
                {
                    data: "emp_position", name: "emp_position", title: "Position",
                    sortable:false,
                    visible:false,
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
                        return `
                            <a href="javascript:;" class="btn btn-icon btn-sm btn-icon btn-light-danger btn-sm hover-elevate-up remove" data-id="${data}"
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

            // _tab.on('change','select.filter_table',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()
            //     initTable();
            // });

            // $(`#${_table}_table`).on('click','.view-details',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()

            //     let _this = $(this);
            //     let url   =_this.attr('rq-url');
            //     let id    =_this.attr('data-id');
            //     let modal_id = '#modal_add_group';
            //     let form = $('#form_add_group');

            //     let formData = new FormData;

            //     formData.append('id',id);
            //     _request.post('/hris/admin/settings/group_details/info',formData)
            //     .then((res) => {
            //         let payload = JSON.parse(window.atob(res.payload));
            //         form.find('input[name="name"]').val(payload.name);
            //         form.find('select[name="is_active"]').val(payload.is_active).trigger('change');
            //         form.find('textarea[name="description"]').val(payload.description);
            //         $(modal_id).find('button.submit').attr('data-id',id);
            //     })
            //     .catch((error) => {
            //         console.log(error);
            //         Alert.alert('error', "Something went wrong. Try again later", false);
            //     })
            //     .finally((error) => {
            //         modal_state(modal_id,'show');
            //     });

            // });

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

            $(`#${_table}_table`).on('click','.remove',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                Alert.confirm('question','Remove this member ?',{
                    onConfirm: function() {
                        formData.append('id',id);
                        formData.append('group_id',param);
                        formData.append('is_active',2);
                        _request.post('/hris/admin/settings/group_details/update_member',formData)
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

export var dtEnrollGroupMember = function (param) {
    const _page = $('.page-group-settings');
    const _table = 'employee_list';
    const _tab = $(`.${_table}`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            'hris/admin/settings/group_details/employee_list',
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

            // _tab.on('change','select.filter_table',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()
            //     initTable();
            // })

            // _tab.on('keyup','.search',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()
            //     let searchTerm = $(this).val();
            //     if (e.key === 'Enter' || e.keyCode === 13) {
            //         dataTableHelper.search(searchTerm);
            //     } else if (e.keyCode === 8 || e.key === 'Backspace') {
            //         setTimeout(() => {
            //             let updatedSearchTerm = $(this).val();
            //             if (updatedSearchTerm === '') {
            //                 dataTableHelper.search('');
            //             }
            //         }, 0);
            //     }
            // })

            // $(`#${_table}_table`).on('click','.view-details',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()

            //     let _this = $(this);
            //     let url   =_this.attr('rq-url');
            //     let id    =_this.attr('data-id');
            //     let modal_id = '#modal_add_group';
            //     let form = $('#form_add_group');

            //     let formData = new FormData;

            //     formData.append('id',id);
            //     _request.post('/hris/admin/settings/group_details/info',formData)
            //     .then((res) => {
            //         let payload = JSON.parse(window.atob(res.payload));
            //         form.find('input[name="name"]').val(payload.name);
            //         form.find('select[name="is_active"]').val(payload.is_active).trigger('change');
            //         form.find('textarea[name="description"]').val(payload.description);
            //         $(modal_id).find('button.submit').attr('data-id',id);
            //     })
            //     .catch((error) => {
            //         console.log(error);
            //         Alert.alert('error', "Something went wrong. Try again later", false);
            //     })
            //     .finally((error) => {
            //         modal_state(modal_id,'show');
            //     });

            // })

            // $(`#${_table}_table`).on('click','.delete',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()

            //     let _this = $(this);
            //     let id    =_this.attr('data-id');
            //     let formData = new FormData;

            //     Alert.confirm('question','Delete this record ?',{
            //         onConfirm: function() {
            //             formData.append('id',id);
            //             _request.post('/hris/admin/settings/group_details/delete',formData)
            //             .then((res) => {
            //                 Alert.toast(res.status,res.message);
            //                 if(res.payload ==0){
            //                     initTable();
            //                 }else{
            //                     $(`#${_table}_table`).DataTable().ajax.reload(null, false);
            //                 }
            //             })
            //             .catch((error) => {
            //                 Alert.alert('error', "Something went wrong. Try again later", false);
            //             })
            //             .finally((error) => {

            //             });
            //         }
            //     });


            // })

            $(`#${_table}_table`).on('click','.enroll',function(e){
                e.preventDefault()
                e.stopImmediatePropagation()

                let _this = $(this);
                let id    =_this.attr('data-id');

                let formData = new FormData;
                formData.append('id',id);
                formData.append('group_id',param);
                formData.append('is_active',$(this).is(":checked")?1:2);
                _request.post('/hris/admin/settings/group_details/update_member',formData)
                .then((res) => {
                    Alert.toast(res.status,res.message);
                })
                .catch((error) => {
                    Alert.alert('error', "Something went wrong. Try again later", false);
                })
                .finally((error) => {
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
