'use strict';
import { data_bs_components } from "../../../global.js";
import { Alert } from "../../../global/alert.js";
import { RequestHandler } from "../../../global/request.js";
// import { fvEmployeeDetails } from "../../fv_controller/201_employee/fv_employee_details.js";

export var EmployeeProfileController =  function (page,param) {

    const _page = $('.page-employee-profile');
    const _request = new RequestHandler;

    let tabLoaded = [];

    function loadActiveTab(tab=false){
        tab = (tab == false ? (localStorage.getItem("employee_details_tab") || '1') : tab);
        return new Promise(async (resolve, reject) => {
            if (tab) {
                let _formData = new FormData();
                _formData.append("emp_id", param);
                _formData.append("tab", tab);
                _request.post('/hris/employee/profile/form',_formData).then((res) => {
                    if(res.status == 'success'){
                        let view = window.atob(res.payload);
                        $(_page).find(`#form${tab}`).html(view);
                        resolve(tab);
                    }
                })
                .catch((error) => {
                    console.log(error)
                    Alert.alert('error',"Something went wrong. Try again later", false);
                })
                .finally(() => {
                    // fvEmployeeDetails(false,tab,param);
                    data_bs_components();
                    $(`#form${tab}`).find('select[data-control="select2"]').select2();
                });
            } else {
                resolve(false);
            }
        });
    }

    _page.on('click','a.tab',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        let _this = $(this);
        let tab = $(this).attr('data-tab');
        _this.attr('disabled',true);
        _page.find('button.cancel').click();

        localStorage.setItem("employee_details_tab",tab);
        if(tabLoaded.includes(tab)){
            _this.attr('disabled',false);
            return;
        }

        page_block.block();
        tabLoaded.push(tab);
        loadActiveTab(tab).then((res) => {
            if (res) {
                $(_this.attr('href')).find('.tab_title').text(_this.attr('data-tab-title'));
                setTimeout(() => {
                    page_block.release();
                    _this.attr('disabled',false);
                },500);
            }
        })
    });

    _page.on('click','button.edit',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        const form = $(this).closest('.card').find('form');
        form.find('input, select').prop('disabled', false);
        $(this).addClass('d-none');
        $(this).siblings('.cancel, .save').removeClass('d-none');
    });

    return {
        init: function () {

            page_block.block();
            loadActiveTab().then((tab) => {
                if(tab != false){
                    let _this = $('a[data-tab='+tab+']');
                    _this.addClass('active');
                    $(_this.attr('href')).find('.tab_title').text(_this.attr('data-tab-title'));
                    _page.find('button.edit').addClass('d-none');
                    $(`.tab${tab}`).addClass('show active');
                    tabLoaded.push(tab);
                }
            })

            setTimeout(() => {
                page_block.release();
            }, 300);

        }
    }

}
