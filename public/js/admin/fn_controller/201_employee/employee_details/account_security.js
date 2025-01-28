'use strict';
import { data_bs_components } from "../../../../global.js";
import { Alert } from "../../../../global/alert.js";
import { RequestHandler } from "../../../../global/request.js";
import { fvAccountSecurity } from "../../../fv_controller/201_employee/fv_employee_details.js";
import { EmploymentDetailsDataHandler } from "./employment_details.js";
import { PersonalDataHandler } from "./personal_data.js";

export var AccountSecurityController =  function (page,param) {

    const _page = $('.page-employee-details');
    const _request = new RequestHandler;
    let tabLoaded = [];

    const _handlers = {
        1:(tab,param) => AccountSecurityHandler(tab,param),
    };

    function loadActiveTab(tab=false){
        tab = (tab == false ? (localStorage.getItem("employee_details_tab") || '1') : tab);

        return new Promise(async (resolve, reject) => {
            if (tab) {
                let _formData = new FormData();
                _formData.append("emp_id", param);
                _formData.append("tab", tab);
                _request.post('/hris/admin/201_employee/employee_details/account_security/tab',_formData).then((res) => {
                    if(res.status == 'success'){
                        let view = window.atob(res.payload);
                        $(_page).find(`#card${tab}`).html(view);
                        resolve(tab);
                    }
                })
                .catch((error) => {
                    console.log(error)
                    Alert.alert('error',"Something went wrong. Try again later", false);
                })
                .finally(() => {
                    const handler = _handlers[tab];
                    if (handler) { handler(tab,param);  }
                    data_bs_components();
                    KTMenu.createInstances();
                });
            } else {
                resolve(false);
            }
        });
    }

    function AccountSecurityHandler(tab,param){
        var d = function () {
            e.classList.toggle("d-none"),
            s.classList.toggle("d-none"),
            n.classList.toggle("d-none");
        },
        c = function () {
            o.classList.toggle("d-none"),
            a.classList.toggle("d-none"),
            i.classList.toggle("d-none");
        };
        let s = document.getElementById("kt_signin_email_button");
        let e = document.getElementById("kt_signin_email");
        let n = document.getElementById("kt_signin_email_edit") ;
        let o = document.getElementById("kt_signin_password");
        let i = document.getElementById("kt_signin_password_edit");
        let a = document.getElementById("kt_signin_password_button");
        let r = document.getElementById("kt_signin_cancel");
        let l = document.getElementById("kt_password_cancel");

        s.querySelector("button").addEventListener("click", function () {
            d();
        });

        a.querySelector("button").addEventListener("click", function () {
            c();
        })

        r.addEventListener("click", function () {
            d();
            loadActiveTab(tab);
        });
        l.addEventListener("click", function () {
            c();
        });

        $(_page).find('#kt_signin_change_password, #kt_signin_change_email').attr('action','/hris/admin/201_employee/employee_details/account_security/update');
        fvAccountSecurity(false,3,param);
    }


    $(async function () {

        loadActiveTab().then((tab) => {
            if(tab != false){
                let _this = $('a[data-tab='+tab+']');
                _this.addClass('active');
                $(_this.attr('href')).find('.tab_title').text(_this.attr('data-tab-title'));
                $(`.tab${tab}`).addClass('show active');
                tabLoaded.push(tab);
            }
        })

        // _page.on('click','a.account-securi',function(e){
        //     e.preventDefault();
        //     e.stopImmediatePropagation();

        //     let _this = $(this);
        //     let tab = _this.attr('data-tab');

        //     let card = document.querySelector(`#card${tab}`);
        //     let blockUI = KTBlockUI.getInstance(card) ?? new KTBlockUI(card, {
        //     message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
        //     });

        //     if (_this.data("processing")) {
        //         return;
        //     }

        //     localStorage.setItem("account_security_tab",tab);
        //     if(tabLoaded.includes(tab)){
        //         _this.attr('disabled',false);
        //         _this.attr('processing',false);
        //         return;
        //     }

        //     _this.attr('disabled',true);
        //     _this.data("processing", true);


        //     if (blockUI.isBlocked() === false) {  blockUI.block(); }
        //     loadActiveTab(tab).then((res) => {
        //         if (res) {
        //             setTimeout(() => {
        //                 _this.attr('disabled',false);
        //                 _this.data("processing", false);
        //                 blockUI.release();
        //             },300);

        //             $(_this.attr('href')).find('.tab_title').text(_this.attr('data-tab-title'));
        //             tabLoaded.push(tab);
        //         }
        //     })
        // });

    });
}
