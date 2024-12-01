'use strict';
import { get_company, get_leave_credit_type, get_leave_type } from "../../../../global/select.js";
import { dtLeaveType } from "../../../dt_controller/settings/dt_leave_settings/dt_leave.type.js";
import { dtLeaveManagement } from "../../../dt_controller/settings/dt_leave_settings/dt_leave_management.js";
import { fvLeaveManagement, fvLeaveType } from "../../../fv_controller/fv_leave_settings/fv_leave_settings.js";


export var LeaveSettingController = function (page,param) {

    const _page = $('.page-leave-settings');
    let tabLoaded = [];

    function loadActiveTab(tab=false){
        tab = (tab == false ? (localStorage.getItem("leave_settings_tab") || 'leave-type') : tab);

        const _tab = {
            "leave-type": leaveTypeTab,
            "leave-management": leaveManagementTab,
        };

        return new Promise((resolve, reject) => {
            if (_tab[tab]) {
                _tab[tab](tab).then(() => resolve(tab)).catch(reject);
            } else {
                resolve(false);
            }
        });
    }

    function leaveTypeTab()
    {
        return new Promise((resolve, reject) => {
            try {
                dtLeaveType().init();
                fvLeaveType('#leave_type_table');
                get_company('select[name="company_id"]');
                resolve(true);
            } catch (error) {
                resolve(false);
            }
        });
    }


    function leaveManagementTab()
    {
        return new Promise((resolve, reject) => {
            try {
                dtLeaveManagement().init();
                get_leave_type('select[name="leave_type_id"]','manage_leave');
                get_leave_credit_type('select[name="credit_type"]');

                fvLeaveManagement('#leave_management_table');
                resolve(true);
            } catch (error) {
                resolve(false);
            }
        });
    }

    return {
        init: function () {

            page_block.block();
            loadActiveTab().then((tab) => {
                if(tab != false){
                    $('a[data-tab='+tab+']').addClass('active');
                    $(`.${tab}`).addClass('show active');
                    tabLoaded.push(tab);
                }
            })

            _page.on('click','a.tab',function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                let _this = $(this);
                let tab = $(this).attr('data-tab');
                _this.attr('disabled',true);

                localStorage.setItem("leave_settings_tab",tab);
                if(tabLoaded.includes(tab)){
                    _this.attr('disabled',false);
                    return;
                }

                page_block.block();
                tabLoaded.push(tab);
                loadActiveTab(tab).then((res) => {
                    if (res) {
                        setTimeout(() => {
                            page_block.release();
                            _this.attr('disabled',false);
                        },500);
                    }else{
                        // localStorage.setItem("tractor_trailer_tab",tab);
                    }
                })
            });

            setTimeout(() => {
                page_block.release();
                KTComponents.init();
            }, 300);

        }
    }

}
