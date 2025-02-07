'use strict';
import { data_bs_components } from "../../../global.js";
import { Alert } from "../../../global/alert.js";
import { RequestHandler } from "../../../global/request.js";
// import { fvAccountSecurity, fvEmployeeDetails, fvUploadDocument } from "../../fv_controller/profile/fv_profile.js";
import { fvAccountSecurity } from "../../fv_controller/profile/fv_profile.js";

export var EmployeeProfileController =  function (page,param) {

    // const _page = $('.page-employee-profile');
    // const _request = new RequestHandler;
    // let tabLoaded = [];

    // const _handlers = {
    //     1:(tab,param) => personalInfoHandler(tab,param),
    //     2:(tab,param) => employeDetailsHandler(tab,param),
    //     6: (tab,param) => DocumentAttachmentsHandler(tab,param),
    //     8 : (tab,param) =>AccountSecurityHandler(tab,param),
    // };

    // async function loadActiveTab(tab=false){
    //     tab = (tab == false ? (localStorage.getItem("employee_profile_tab") || '1') : tab);

    //     let card = document.querySelector(`.tab-content`);
    //     let blockUI = KTBlockUI.getInstance(card) ?? new KTBlockUI(card, {
    //         message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
    //     });

    //     if (blockUI.isBlocked() === false) {  blockUI.block(); }
    //     return new Promise(async (resolve, reject) => {
    //         if (tab) {
    //             let _formData = new FormData();
    //             _formData.append("emp_id", param);
    //             _formData.append("tab", tab);
    //             _request.post('/hris/employee/profile/form',_formData).then((res) => {
    //                 if(res.status == 'success'){
    //                     let view = window.atob(res.payload);
    //                     $(_page).find(`#card${tab} > .card-body`).html(view);
    //                     resolve(tab);
    //                 }
    //             })
    //             .catch((error) => {
    //                 console.log(error)
    //                 Alert.alert('error',"Something went wrong. Try again later", false);
    //             })
    //             .finally(() => {
    //                 const handler = _handlers[tab];
    //                 if (handler) { handler(tab,param);  }
    //                 data_bs_components();
    //                 KTMenu.createInstances();
    //                 setTimeout(() => {blockUI.release(); }, 300);
    //             });
    //         } else {
    //             resolve(false);
    //         }
    //     });
    // }

    // function personalInfoHandler(tab,param)
    // {
    //     $(_page).find(`#form${tab}`).attr('action','/hris/employee/profile/update');
    //     $(`#card${tab}`).find('button.edit, button.cancel, button.save').remove();
    //     $(`#form${tab}`).find('select[data-control="select2"]').select2();
    //     // fvEmployeeDetails(false,tab,param);
    // }

    // function employeDetailsHandler(tab,param)
    // {
    //     $(_page).find(`#form${tab}`).attr('action','/hris/employee/profile/update');
    //     $(`#form${tab}`).find('select[data-control="select2"]').select2();
    //     $(`#card${tab}`).find('button.edit, button.cancel, button.save').remove();
    //     // fvEmployeeDetails(false,tab,param);
    // }

    // function DocumentAttachmentsHandler(tab,param)
    // {
    //     $(_page).find('select[name="file_type"]').select2({
    //         dropdownParent: '#modal_upload_document',
    //         width: '100%',
    //     });

    //     $(_page).find(`#form_upload_document`).attr('action','/hris/employee/profile/update');

    //     $('#document_attachments_table').on('click','.delete',function(e){
    //         e.preventDefault();
    //         e.stopImmediatePropagation();

    //         let _this = $(this);
    //         Alert.confirm("question","Delete document?", {
    //             onConfirm: function() {
    //                 let formData = new FormData();
    //                 formData.append('id',_this.attr('data-id'));
    //                 formData.append('tab',tab);
    //                 _request.post('/hris/employee/profile/delete',formData,true).then((res) => {
    //                     Alert.toast(res.status,res.message);
    //                     if(res.status == 'success'){
    //                         loadActiveTab(tab)
    //                     }
    //                 })
    //                 .catch((error) => {
    //                     console.log(error)
    //                     Alert.alert('error',"Something went wrong. Try again later", false);
    //                 })
    //                 .finally(() => {
    //                 });
    //             },
    //         });
    //     })
    //     $(`#card${tab}`).find('button.upload-document').remove();
    //     // fvUploadDocument(false,tab,param);
    // }

    // function AccountSecurityHandler(tab,param)
    // {
    //     var d = function () {
    //         e.classList.toggle("d-none"),
    //           s.classList.toggle("d-none"),
    //           n.classList.toggle("d-none");
    //       },
    //       c = function () {
    //         o.classList.toggle("d-none"),
    //           a.classList.toggle("d-none"),
    //           i.classList.toggle("d-none");
    //       };
    //     let s = document.getElementById("kt_signin_email_button");
    //     let e = document.getElementById("kt_signin_email");
    //     let n = document.getElementById("kt_signin_email_edit") ;
    //     let o = document.getElementById("kt_signin_password");
    //     let i = document.getElementById("kt_signin_password_edit");
    //     let a = document.getElementById("kt_signin_password_button");
    //     let r = document.getElementById("kt_signin_cancel");
    //     let l = document.getElementById("kt_password_cancel");

    //     s.querySelector("button").addEventListener("click", function () {
    //         d();
    //     });

    //     a.querySelector("button").addEventListener("click", function () {
    //         c();
    //     })

    //     r.addEventListener("click", function (e) {
    //         d();
    //         loadActiveTab(tab);
    //     });

    //     l.addEventListener("click", function () {
    //         c();
    //     });

    //     $(_page).find('#kt_signin_change_password, #kt_signin_change_email').attr('action','/hris/employee/profile/update');
    //     $(`#card${tab}`).find('#kt_signin_email_button').remove();
    //     fvAccountSecurity(false,tab);
    // }

    // $(async function () {

    //     await loadActiveTab().then((tab) => {
    //         if(tab){
    //             let _this = $('a[data-tab='+tab+']');
    //             _this.addClass('active');
    //             $(_this.attr('href')).find('.tab_title').text(_this.attr('data-tab-title'));
    //             $(`.tab${tab}`).addClass('show active');
    //             tabLoaded.push(tab);
    //         }
    //     })

    //     _page.on('click','a.profile-tab',function(e){
    //         e.preventDefault();
    //         e.stopImmediatePropagation();

    //         let _this = $(this);
    //         if (_this.data("processing")) {
    //             return; // Exit early if already in process
    //         }
    //         _this.data("processing", true);

    //         let tab = $(this).attr('data-tab');
    //         _this.attr('disabled',true);
    //         // _page.find('button.cancel:not(.modal button.cancel)').click();
    //         localStorage.setItem("employee_profile_tab",tab);
    //         if(tabLoaded.includes(tab)){
    //             _this.attr('disabled',false);
    //             return;
    //         }

    //         loadActiveTab(tab).then((res) => {
    //             if (res) {
    //                 $(_this.attr('href')).find('.tab_title').text(_this.attr('data-tab-title'));
    //                 setTimeout(() => {
    //                     _this.attr('disabled',false);
    //                     _this.data("processing", false);
    //                 },500);
    //                 tabLoaded.push(tab);
    //                 console.log(123)
    //             }
    //         })
    //     });
    // });


        const _page = $('.page-profile');
        const _request = new RequestHandler;

        const _handlers = {
            'personal_data':(tab,param) => PersonalDataHandler(tab,param),
            'employment_details':(tab,param) => EmploymentDetailsDataHandler(tab,param),
            'account_security': (tab,param)=> AccountSecurityController(tab,param),
        };

        async function loadActiveMainTab(tab=false)
        {
            tab = (tab == false ? (localStorage.getItem("employee_details_maintab") || 'personal_data') : tab);

            if (page_block.isBlocked() === false) {  page_block.block(); }
            return new Promise(async (resolve, reject) => {
                if (tab) {
                    let _formData = new FormData();
                    _formData.append("emp_id", param);
                    _formData.append("tab", tab);
                    _request.post('/hris/employee/profile/tab',_formData).then((res) => {
                        if(res.status == 'success'){
                            let view = window.atob(res.payload);
                            $(_page).find(`.main-content`).html(view);

                            const handler = _handlers[tab];
                            if (handler) { handler(tab,param);  }

                            let _this = $('.main-tab[data-tab='+tab+']');
                            $('.main-tab').removeClass('active');
                            _this.addClass('active');

                            page_block.release();
                            resolve(tab);
                        }
                    })
                    .catch((error) => {
                        console.log(error)
                        Alert.alert('error',"Something went wrong. Try again later", false);
                    })
                    .finally(() => {
                        data_bs_components();
                        KTMenu.createInstances();
                    });
                } else {
                    resolve(false);
                }
            });
        }

        _page.on('click','a.main-tab',function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            let _this = $(this);
            if (_this.data("processing") || _this.hasClass('active')) {
                return;
            }

            _this.data("processing", true);
            _this.attr('disabled',true);

            let tab = $(this).attr('data-tab');
            localStorage.setItem("employee_details_maintab",tab);

            loadActiveMainTab(tab)
            .then((res) => {
                if (res) {
                    // $(_this.attr('href')).find('.tab_title').text(_this.attr('data-tab-title'));
                    setTimeout(() => {
                        _this.attr('disabled',false);
                        _this.data("processing", false);
                    },500);

                }
            })
        });

        return {
            init: async function () {
                await loadActiveMainTab().then((tab) => {
                })

            }
        }

}
