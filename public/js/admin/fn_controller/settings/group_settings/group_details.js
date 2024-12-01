'use strict';
import { modal_state } from "../../../../global.js";
import { Alert } from "../../../../global/alert.js";
import { RequestHandler } from "../../../../global/request.js";
import { get_company, get_company_location, get_department, get_employee, get_section } from "../../../../global/select.js";
import { dtEnrollGroupMember, dtGroupMember } from "../../../dt_controller/settings/dt_group_settings/dt_group_member.js";
import { fvGroupDetails } from "../../../fv_controller/fv_group_settings/fv_group_details.js";


export var GroupDetailsController = function (page,param) {

    const _page = $('.page-group-details');
    const _request = new RequestHandler();

    function loadApprovers()
    {
        page_block.block();

        let formData = new FormData;
        formData.append('group_id',param);
        _request.post('/hris/admin/settings/group_details/approver_list',formData)
        .then((res) => {
            if(res.status == 'success'){
                let payload = JSON.parse(window.atob(res.payload));
                let approver_list = $('.approver-list')
                let approvers_count = $('.approvers_count');
                approver_list.empty();
                approvers_count.empty().addClass('d-none');;
                if(payload.length >0){
                    let html ='';
                    $.each(payload, (key, value) => {
                        html += `<div class="d-flex align-items-center py-2 fs-6">
                        <span class="bullet bg-primary me-3"></span>
                           <span> ${value.name} <br>Level ${value.approver_level} ${value.is_final_approver ?' | Final Approver':''}</span>

                    </div>`;
                    });
                    approver_list.append(html);
                    approvers_count.append('('+payload.length+')').removeClass('d-none');
                }else{
                    approver_list.append(
                    `<div class="d-flex align-items-center py-2">
                        <span class="bullet bg-primary me-3"></span>
                           <em class="text-muted"> No Approver Found </em>
                    </div>`);
                }
            }
        })
        .catch((error) => {
            console.log(error)
            Alert.alert('error',"Something went wrong. Try again later", false);
        })
        .finally(() => {
            setTimeout(() => {
                page_block.release();
            }, 300);
            get_employee('select[name="approver_id"]');
        });
    }

    $(_page).ready(function() {

        _page.on('click','.open',function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            let modal_id = '#modal_add_member';

            dtEnrollGroupMember(param).init();
            modal_state(modal_id,'show');

        })

        _page.on('click','.close',function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            let modal_id = '#modal_add_member';
            modal_state(modal_id);
        })

    })

    return {
        init: function () {
            loadApprovers()
            dtGroupMember(param).init();
            fvGroupDetails(false,'approver',param);
        },
        initApprover :function() {
            loadApprovers()
        }
    }

}
