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
                            2: ["danger", "Rejected"],
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
                {
                    data: "encrypted_id",
                    name: "encrypted_id",
                    title: "Action",
                    sortable:false,
                    className: "text-center",
                    responsivePriority: -1,
                    render: function (data, type, row) {
                        return `
                            <a href="javascript:;" class="btn btn-icon btn-icon btn-info btn-sm hover-elevate-up history" data-id="${data}"
                            data-bs-toggle="tooltip" title="View Approval History" id="">
                                <i class="ki-duotone ki-burger-menu-5 fs-2"></i>
                            </a>
                        `;
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

            // $(`#${_table}_table`).on('click','.view-details',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()

            //     let _this = $(this);
            //     let id    =_this.attr('data-id');

            //     let modal_id = '#modal_request_overtime';
            //     let form = $('#form_request_overtime');

            //     let formData = new FormData;
            //     formData.append('id',id);
            //     _request.post('/hris/employee/request/overtime/info',formData)
            //     .then((res) => {
            //         let payload = JSON.parse(window.atob(res.payload));
            //         $('input[name="overtime_date"]').val(payload.overtime_date);
            //         $('input[name="overtime_from"]')[0]._flatpickr.setDate(payload.overtime_from, true);
            //         $('input[name="overtime_to"]')[0]._flatpickr.setDate(payload.overtime_to, true);
            //         $('textarea[name="reason"]').val(payload.reason);
            //         $(modal_id).find('button.submit').attr('data-id',id);
            //     })
            //     .catch((error) => {
            //         console.log(error);
            //         Alert.alert('error', "Something went wrong. Try again later", false);
            //     })
            //     .finally((error) => {
            //         $(modal_id).find('.modal_title').text('Edit Details');
            //         modal_state(modal_id,'show');
            //     });

            // })

            // $(`#${_table}_table`).on('click','.delete',function(e){
            //     e.preventDefault()
            //     e.stopImmediatePropagation()

            //     let _this = $(this);
            //     let id    =_this.attr('data-id');
            //     let formData = new FormData;

            //     Alert.confirm('question','Do you want to delete this overtime request ?',{
            //         onConfirm: function() {
            //             formData.append('id',id);
            //             _request.post('/hris/employee/request/overtime/delete',formData)
            //             .then((res) => {
            //                 Alert.toast(res.status,res.message);
            //                 console.log(res.payload ==0)
            //                 if(res.payload ==0){
            //                     dtOvertimeRequisition().init();
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

            $(`#${_table}_table`).on('click','.history',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();

                let _this = $(this);
                let id    =_this.attr('data-id');
                let formData = new FormData;

                formData.append('id',id);
                _request.post('/hris/admin/approvals/official_business/view_history',formData)
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
