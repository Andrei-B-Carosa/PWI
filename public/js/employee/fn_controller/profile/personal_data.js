'use strict';
import { data_bs_components } from "../../../global.js";
import { Alert } from "../../../global/alert.js";
import { RequestHandler } from "../../../global/request.js";
import { dtDocumentAttachments } from "../../dt_controller/profile/dt_document_attachments.js";
import { dtEducationalBackground } from "../../dt_controller/profile/dt_educational_background.js";
import { dtReferences } from "../../dt_controller/profile/dt_references.js";
import { dtWorkExperience } from "../../dt_controller/profile/dt_work_experience.js";

export var PersonalDataHandler =  function (page,param) {

    const _page = $('.page-profile');
    const _request = new RequestHandler;
    let tabLoaded = [];

    const _handlers = {
        1:(tab,param) => personalInfoHandler(tab,param),
        2:(tab,param) => FamilyBackgroundHandler(tab,param),
        3:(tab,param) => EducationalBackgroundHandler(tab,param),
        4:(tab,param) => WorkExperienceHandler(tab,param),
        5:(tab,param) => DocumentAttachmentsHandler(tab,param),
        6: (tab,param) => ReferencesHandler(tab,param),
    };

    function loadActiveTab(tab=false){
        tab = (tab == false ? (localStorage.getItem("employee_details_tab") || '1') : tab);
        return new Promise(async (resolve, reject) => {
            if (tab) {
                let _formData = new FormData();
                _formData.append("emp_id", param);
                _formData.append("tab", tab);
                _request.post('/hris/employee/profile/personal_data/tab',_formData).then((res) => {
                    if(res.status == 'success'){
                        let view = window.atob(res.payload);
                        $(_page).find(`#card${tab}`).html(view);

                        const handler = _handlers[tab];
                        if (handler) { handler(tab,param);  }

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

    function personalInfoHandler(tab,param)
    {
        // $(_page).find(`#form${tab}`).attr('action','/hris/employee/profile/personal_data/personal_information/update');
        // $(`#form${tab}`).find('select[data-control="select2"]').select2();
        // fvEmployeeDetails(false,tab,param);
        $('button.edit').remove();
    }

    function FamilyBackgroundHandler(tab,param)
    {
        // $(_page).find(`.form${tab}`).attr('action','/hris/employee/profile/personal_data/family_background/update');
        // $(`#form${tab}`).find('select[data-control="select2"]').select2();
        // fvFamilyBackground(false,tab,param);
        $('button.edit').remove();
    }

    function EducationalBackgroundHandler(tab,param)
    {
        dtEducationalBackground(param).init();
        $(`button[data-bs-target="#modal_add_education"]`).remove();

        // fvEducationalBackground('#educational_background_table',tab,param);
        // $(_page).find(`#form_add_education`).attr('action','/hris/admin/201_employee/employee_details/personal_data/educational_background/update');
        // $(`#form_add_education`).find('select[data-control="select2"]').select2();
    }

    function WorkExperienceHandler(tab,param)
    {
        dtWorkExperience(param).init();
        $(`button[data-bs-target="#modal_add_work_experience"]`).remove();
        // fvWorkExperience('#work_experience_table',tab,param);
        // $(_page).find(`#form_add_work_experience`).attr('action','/hris/admin/201_employee/employee_details/personal_data/work_experience/update');
        // $(`#form_add_work_experience`).find('select[data-control="select2"]').select2();
    }

    function DocumentAttachmentsHandler(tab,param)
    {
        dtDocumentAttachments(param).init();
        $(`button[data-bs-target="#modal_add_documents"]`).remove();

        // fvDocumentAttachments('#document_table',tab,param);
        // $(_page).find(`#form_add_documents`).attr('action','/hris/admin/201_employee/employee_details/personal_data/document_attachment/update');
        // $(`#form_add_documents`).find('select[data-control="select2"]').select2();

    }

    function ReferencesHandler(tab,param)
    {
        dtReferences(param).init();
        $(`button[data-bs-target="#modal_add_references"]`).remove();

        // fvReferences('#references_table',tab,param);
        // $(_page).find(`#form_add_references`).attr('action','/hris/admin/201_employee/employee_details/personal_data/references/update');
        // $(`#form_add_references`).find('select[data-control="select2"]').select2();
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

            if (_this.data("processing")) { return; }
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
                    localStorage.setItem("employee_details_tab",tab);
                    tabLoaded.push(tab);
                }
            })
        });

    });

}

