'use strict';
import { data_bs_components } from "../../../../global.js";
import { Alert } from "../../../../global/alert.js";
import { RequestHandler } from "../../../../global/request.js";
import { dtEducationalBackground } from "../../../dt_controller/201_employee/employee_details/dt_educational_background.js";
import { fvAccountSecurity, fvEducationalBackground, fvEmployeeDetails, fvUploadDocument } from "../../../fv_controller/201_employee/fv_employee_details.js";

export var PersonalDataHandler =  function (page,param) {

    const _page = $('.page-employee-details');
    const _request = new RequestHandler;
    let tabLoaded = [];

    const _handlers = {
        1:(tab,param) => personalInfoHandler(tab,param),
        // 2:(tab,param) => employeeDetailsHandler(tab,param),
        3:(tab,param) => EducationalBackgroundHandler(tab,param),
        // 6: (tab,param) => DocumentAttachmentsHandler(tab,param),
        // 8 : (tab,param) =>AccountSecurityHandler(tab,param),
    };

    function loadActiveTab(tab=false){
        tab = (tab == false ? (localStorage.getItem("employee_details_tab") || '1') : tab);

        return new Promise(async (resolve, reject) => {
            if (tab) {
                let _formData = new FormData();
                _formData.append("emp_id", param);
                _formData.append("tab", tab);
                _request.post('/hris/admin/201_employee/employee_details/personal_data/tab',_formData).then((res) => {
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

    function personalInfoHandler(tab,param)
    {
        $(_page).find(`#form${tab}`).attr('action','/hris/admin/201_employee/employee_details/update');
        $(`#form${tab}`).find('select[data-control="select2"]').select2();
        fvEmployeeDetails(false,tab,param);
    }

    function EducationalBackgroundHandler(tab,param)
    {
        dtEducationalBackground(param).init();
        fvEducationalBackground(false,tab,param);

        $(_page).find(`#form_add_education`).attr('action','/hris/admin/201_employee/employee_details/personal_data/educational_background/update');
        $(`#form_add_education`).find('select[data-control="select2"]').select2();
    }

    function employeeDetailsHandler(tab,param)
    {
    $(_page).find(`#form${tab}`).attr('action','/hris/admin/201_employee/employee_details/update');
    $(`#form${tab}`).find('select[data-control="select2"]').select2();
    fvEmployeeDetails(false,tab,param);
    }

    function DocumentAttachmentsHandler(tab,param)
    {
    $(_page).find('select[name="file_type"]').select2({
    dropdownParent: '#modal_upload_document',
    width: '100%',
    });

    $(_page).find(`#form_upload_document`).attr('action','/hris/admin/201_employee/employee_details/update');

    $('#document_attachments_table').on('click','.delete',function(e){
    e.preventDefault();
    e.stopImmediatePropagation();

    let _this = $(this);
    Alert.confirm("question","Delete document?", {
        onConfirm: function() {
            let formData = new FormData();
            formData.append('id',_this.attr('data-id'));
            formData.append('tab',tab);
            _request.post('/hris/admin/201_employee/employee_details/delete',formData,true).then((res) => {
                Alert.toast(res.status,res.message);
                if(res.status == 'success'){
                    loadActiveTab(tab)
                }
            })
            .catch((error) => {
                console.log(error)
                Alert.alert('error',"Something went wrong. Try again later", false);
            })
            .finally(() => {
            });
        },
    });
    })

    fvUploadDocument(false,tab,param);
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

        _page.on('click','a.sub-tab',function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            let _this = $(this);
            let tab = _this.attr('data-tab');

            let card = document.querySelector(`#card${tab}`);
            let blockUI = KTBlockUI.getInstance(card) ?? new KTBlockUI(card, {
            message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
            });

            if (_this.data("processing")) {
                return;
            }

            localStorage.setItem("employee_details_tab",tab);
            if(tabLoaded.includes(tab)){
                _this.attr('disabled',false);
                _this.attr('processing',false);
                return;
            }

            _this.attr('disabled',true);
            _this.data("processing", true);


            if (blockUI.isBlocked() === false) {  blockUI.block(); }
            loadActiveTab(tab).then((res) => {
                if (res) {
                    setTimeout(() => {
                        _this.attr('disabled',false);
                        _this.data("processing", false);
                        blockUI.release();
                    },300);

                    $(_this.attr('href')).find('.tab_title').text(_this.attr('data-tab-title'));
                    tabLoaded.push(tab);
                }
            })
        });

    });

}

