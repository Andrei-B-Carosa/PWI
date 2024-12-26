'use strict';
import { modal_state } from "../../global.js";
import {Alert} from "../../global/alert.js";
import {RequestHandler} from "../../global/request.js";
import { dtApprovalHistory } from "../dt_controller/dt_home.js";

export var HomeController = function (page, param) {

    const _page = $('.page-home');
    const scrollContainer = $(".member-list");
    const limit = 5;
    const _modal = document.querySelector("#modal_view_group .modal-content");

    let currentOffset = 0;
    let hasMore = true;
    let isFetching = false

    async function loadViewGroup(modal_id)
    {
        const _request = new RequestHandler;
        const _formData = new FormData;

        _request.get('/hris/employee/home/group_details')
        .then((res) => {

            let html_approver='',html_member='';
            let div_approver_list = $('.approver-list'), div_member_list = $('.member-lists'),span_group_name=$('.group-name');

            div_approver_list.empty();
            div_member_list.empty();

            if(res.status == 'success'){
                let payload = JSON.parse(window.atob(res.payload));

                if(payload.group_approver.length >0){
                    payload.group_approver.forEach((item, index) => {
                        html_approver +=`
                        <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px symbol-circle">
                                    <span class="symbol-label bg-light-primary text-primary fw-semibold">
                                        ${item.name[0]}
                                    </span>
                                    </div>
                                <div class="ms-6">
                                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-gray-900 text-hover-primary">
                                        ${item.name}
                                         ${item.is_final_approver ?`
                                            <span class="badge badge-success fs-8 fw-semibold ms-2">
                                            Final Approver
                                            </span>
                                        `:``}
                                    </a>
                                    <div class="fw-semibold text-muted">Approver level ${item.approver_level}</div>
                                </div>
                            </div>
                        </div>
                        `;
                    });
                }

                if(payload.group_member.length > 0){
                    payload.group_member.forEach((item, index) => {
                        html_member +=`
                        <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px symbol-circle">
                                    <span class="symbol-label bg-light-primary text-primary fw-semibold">
                                        ${item.name[0]}
                                    </span>
                                    </div>
                                <div class="ms-6">
                                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-gray-900 text-hover-primary">
                                        ${item.name}
                                    </a>
                                    <div class="fw-semibold text-muted">${item.position}</div>
                                </div>
                            </div>
                        </div>
                        `;
                    });
                    currentOffset += payload.group_member.length;
                }

                div_approver_list.append(html_approver);
                div_member_list.append(html_member);
                span_group_name.text(payload.group_details.name);
            }

            if(res.status =='error'){
                $('.group-details').empty().append(`
                    <div id="empty_state_wrapper">
                        <div class="card-px text-center pt-10 pb-15">
                            <h2 class="fs-2x fw-bold mb-0" id="empty_state_title">Oops !</h2>
                            <p class="text-gray-400 fs-4 fw-semibold py-7" id="empty_state_subtitle">
                                It seems that you are not part of any group yet, wait for your HR to assign you to a group
                            </p>

                        </div>
                        <div class="text-center pb-15 px-5">
                            <img src="${asset_url+'/media/illustrations/sketchy-1/16.png'}" alt="" class="mw-100 h-200px h-sm-325px">
                        </div>
                    </div>
                `);
            }
        })
        .catch((error) => {
            console.log(error);
            Alert.alert('error', "Something went wrong. Try again later", false);
        })
        .finally((error) => {
            //code here
        });

    }

    async function loadMoreGroupMembers(offset, limit)
    {
        if (isFetching || !hasMore) return;
        isFetching = true;

        const _request = new RequestHandler;
        const _formData = new FormData;

        _formData.append('offset',offset);
        _formData.append('limit',limit);
        _request.post('/hris/employee/home/get_group_members',_formData)
        .then((res) => {
            if(res.status == 'success'){
                let payload = JSON.parse(window.atob(res.payload));
                let html_member='';
                let div_member_list = $('.member-lists');
                if(payload.group_member.length > 0){

                    // No more employees to fetch
                    if (payload.group_member.length < limit) {
                        hasMore = false;
                    }else{
                        hasMore = true;
                    }

                    payload.group_member.forEach((item, index) => {
                        html_member +=`
                        <div class="d-flex flex-stack py-5 border-bottom border-gray-300 border-bottom-dashed">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-35px symbol-circle">
                                    <span class="symbol-label bg-light-primary text-primary fw-semibold">
                                        ${item.name[0]}
                                    </span>
                                    </div>
                                <div class="ms-6">
                                    <a href="#" class="d-flex align-items-center fs-5 fw-bold text-gray-900 text-hover-primary">
                                        ${item.name}
                                    </a>
                                    <div class="fw-semibold text-muted">${item.position}</div>
                                </div>
                            </div>
                        </div>
                        `;
                    });

                    div_member_list.append(html_member);
                    currentOffset += payload.group_member.length;

                }else{
                    hasMore = false;
                }
            }
        })
        .finally(()=>{
            isFetching = false;
        })
    }

    async function loadLeaveCredit()
    {
        const _request = new RequestHandler;
        const _formData = new FormData;

        _request.get('/hris/employee/home/leave_credit')
        .then((res) => {
            if(res.status == 'success'){
                let payload = JSON.parse(window.atob(res.payload)),total_leave = 0;
                let div_total_leave = $('.total-leave'), div_leave_breakdown = $('.leave-breakdown'),html ='';

                div_leave_breakdown.empty();
                div_total_leave.empty();

                if (payload.length  > 0) {
                    payload.forEach((item, index) => {
                        html +=`
                        <div class="d-flex align-items-center mb-6">
                            <div class="symbol symbol-45px w-40px me-5">
                                <span class="symbol-label bg-lighten">
                                    <i class="ki-duotone ki-element-11 fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                            </div>
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <div class="mb-1 pe-3 flex-grow-1">
                                    <a href="#" class="fs-5 text-gray-800 text-hover-primary fw-bold">${item.name} (${item.code})</a>
                                    <div class="text-gray-500 fw-semibold fs-7">Leave Credit</div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold fs-5 text-gray-800 pe-1">${item.balance}</div>
                                </div>
                            </div>
                        </div>
                        `;
                        total_leave = item.balance + total_leave;
                    });
                }else{
                    html=`
                    <div class="text-center">
                        <h4 class="fw-bolder text-gray-900 mb-2">
                            Oops!
                        </h4>
                        <div class="fw-semibold fs-6 text-gray-500 mb-7">
                            No Leave credit found wait for monthly allocation of the system.
                        </div>
                    </div>`;
                }

                div_leave_breakdown.append(html);
                div_total_leave.append(total_leave);
            }
        })
        .catch((error) => {
            console.log(error);
            Alert.alert('error', "Something went wrong. Try again later", false);
        })
        .finally((error) => {
            //code here
        });

    }

    //Event Handlers
    $(function() {
        $(_page).on('click','button.view-group',async function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            let _this = $(this);
            let modal_id = _this.attr('modal-id');

            await loadViewGroup(modal_id);
            modal_state(modal_id,'show');
        });

        scrollContainer.on("scroll", async function () {
            if(!hasMore){
                return;
            }
            var blockUI = KTBlockUI.getInstance(_modal) ?? new KTBlockUI(_modal, {
                message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });
            if (blockUI.isBlocked() !== true) {
                blockUI.block();
            }
            await loadMoreGroupMembers(currentOffset, limit);
            setTimeout(() => {
                blockUI.release();
            }, 300);
        });
    });

    $(async function () {
        page_block.block();

        await loadLeaveCredit();

        dtApprovalHistory().init();

        setTimeout(() => {
            page_block.release();
            KTComponents.init();
        }, 300);

    });
}
