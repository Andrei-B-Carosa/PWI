"use strict";

import { DataTableHelper } from "../../../global/datatable.js";
import {Alert} from "../../../global/alert.js"
import {RequestHandler} from "../../../global/request.js"
import {modal_state,createBlockUI,data_bs_components} from "../../../global.js"
import {trigger_select} from "../../../global/select.js"


export var dtOfficialBusiness = function (param=false) {

    const _page = $('.page-official-business');
    const _table = 'official_business';
    // const _tab = $(`.${_table}`);
    const _request = new RequestHandler;
    const dataTableHelper = new DataTableHelper(`${_table}_table`,`${_table}_wrapper`);

    function initTable(){

        dataTableHelper.initTable(
            'hris/admin/approvals/official_business/dt',
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
                    data: "employee_full_name", name: "employee_full_name", title: "Employee",
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
                        `
                    }
                },
                {
                    data: "position_name", name: "position_name", title: "Position",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "ob_filing_date", name: "ob_filing_date", title: "Filing Date",
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "ob_time_out", name: "ob_time_out", title: "Time Out",
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "ob_time_in", name: "ob_time_in", title: "Time In",
                    sortable:false,
                    searchable:false,
                },
                {
                    data: "is_approved", name: "is_approved", title: "Status",
                    sortable:false,
                    searchable:false,
                    className:'text-center',
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
                    data: "purpose", name: "purpose", title: "Purpose",
                    sortable:false,
                    searchable:false,
                    render(data,type,row)
                    {
                        if(!data){
                            return '--';
                        }
                        return ` ${data??'--'}
                        <div class="d-flex flex-column gap-1">
                            <div class="fs-7 text-muted">Destination : ${row.destination ?? '--'}</div>
                        </div>`;
                    }
                },
                {
                    data: "destination", name: "destination", title: "Destination",
                    sortable:false,
                    searchable:false,
                    visible:false,
                },
                {
                    data: "approver", name: "approver", title: "Approver",
                    sortable:false,
                    searchable:false,
                },
                // {
                //     data: "approver_remarks", name: "approver_remarks", title: "Remarks",
                //     sortable:false,
                //     searchable:false,
                //     render(data,type,row)
                //     {
                //         if(!data){
                //             return '--';
                //         }

                //         return data;
                //     }
                // },
                // {
                //     data: "encrypted_id",
                //     name: "encrypted_id",
                //     title: "Action",
                //     sortable:false,
                //     className: "text-center",
                //     responsivePriority: -1,
                //     render: function (data, type, row) {
                //         return `<div class="d-flex justify-content-center flex-shrink-0">
                //             <a href="#" class="btn btn-icon btn-light-primary btn-sm me-1 hover-elevate-up"
                //             data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-bs-toggle="tooltip" title="More Actions">
                //                 <i class="ki-duotone ki-pencil fs-2x">
                //                     <span class="path1"></span>
                //                     <span class="path2"></span>
                //                     <span class="path3"></span>
                //                     <span class="path4"></span>
                //                 </i>
                //             </a>
                //             <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
                //                 <div class="menu-item px-3 text-start">
                //                     <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                //                         More Actions
                //                     </div>
                //                 </div>
                //                 <div class="menu-item px-3">
                //                     <a href="javascript:;" data-id="${data}" class="menu-link px-3 view-details">
                //                         Edit Details
                //                     </a>
                //                 </div>
                //             </div>

                //             <a href="javascript:;" class="btn btn-icon btn-icon btn-light-danger btn-sm me-1 hover-elevate-up delete" data-id="${data}"
                //              data-bs-toggle="tooltip" title="Delete this record">
                //                 <i class="ki-duotone ki-trash fs-2x">
                //                     <span class="path1"></span>
                //                     <span class="path2"></span>
                //                     <span class="path3"></span>
                //                     <span class="path4"></span>
                //                 </i>
                //             </a>
                //         </div>`;
                //     },
                // },
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

                Alert.confirm('question','Do you want to delete this overtime request ?',{
                    onConfirm: function() {
                        formData.append('id',id);
                        _request.post('/hris/employee/request/overtime/delete',formData)
                        .then((res) => {
                            Alert.toast(res.status,res.message);
                            console.log(res.payload ==0)
                            if(res.payload ==0){
                                dtOvertimeRequisition().init();
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
