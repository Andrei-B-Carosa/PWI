'use strict';
import { get_company } from "../../../global/select.js";
import { dtClassification } from "../../dt_controller/settings/dt_file_maintenance/dt_classification.js";
import { dtCompany } from "../../dt_controller/settings/dt_file_maintenance/dt_company.js";
import { dtCompanyLocation } from "../../dt_controller/settings/dt_file_maintenance/dt_company_location.js";
import { dtDepartment } from "../../dt_controller/settings/dt_file_maintenance/dt_department.js";
import { dtEmploymentType } from "../../dt_controller/settings/dt_file_maintenance/dt_employment_type.js";
import { dtPosition } from "../../dt_controller/settings/dt_file_maintenance/dt_position.js";
import { dtSection } from "../../dt_controller/settings/dt_file_maintenance/dt_section.js";
import { fvFileMaintenance } from "../../fv_controller/fv_leave_settings/fv_file_maintenance.js";


export var FileMaintenanceController = function (page,param) {

    const _page = $('.page-file-maintenance-settings');
    let tabLoaded = [];

    function loadActiveTab(tab=false){
        tab = (tab == false ? (localStorage.getItem("file_maintenance_tab") || 'department') : tab);

        const _tab = {
            "department": departmentTab,
            "position": positionTab,
            "classification": classificationTab,
            "employment_type": employmentTypeTab,
            "section": sectionTab,
            "company": companyTab,
            "company_location": companyLocationTab,

        };

        return new Promise((resolve, reject) => {
            if (_tab[tab]) {
                _tab[tab](tab).then(() =>
                    resolve(tab)
                )
                .catch(reject)
                .finally(()=>{
                    $(`#modal_add_${tab}`).find('select[name="is_active"]').select2()
                });
            } else {
                resolve(false);
            }
        });
    }

    function departmentTab(tab)
    {
        return new Promise((resolve, reject) => {
            try {
                $(_page).ready(function () {
                    dtDepartment().init();
                    fvFileMaintenance('#'+tab+'_table',tab);
                    resolve(true);
                });
            } catch (error) {
                resolve(false);
            }
        });
    }

    function positionTab(tab)
    {
        return new Promise((resolve, reject) => {
            try {
                dtPosition().init();
                fvFileMaintenance('#'+tab+'_table',tab);
                resolve(true);
            } catch (error) {
                resolve(false);
            }
        });
    }

    function classificationTab(tab)
    {
        return new Promise((resolve, reject) => {
            try {
                dtClassification().init();
                fvFileMaintenance('#'+tab+'_table',tab);
                resolve(true);
            } catch (error) {
                resolve(false);
            }
        });
    }

    function employmentTypeTab(tab)
    {
        return new Promise((resolve, reject) => {
            try {
                dtEmploymentType().init();
                fvFileMaintenance('#'+tab+'_table',tab);
                resolve(true);
            } catch (error) {
                resolve(false);
            }
        });
    }

    function sectionTab(tab)
    {
        return new Promise((resolve, reject) => {
            try {
                dtSection().init();
                fvFileMaintenance('#'+tab+'_table',tab);
                resolve(true);
            } catch (error) {
                resolve(false);
            }
        });
    }

    function companyTab(tab)
    {
        return new Promise((resolve, reject) => {
            try {
                dtCompany().init();
                fvFileMaintenance('#'+tab+'_table',tab);
                resolve(true);
            } catch (error) {
                resolve(false);
            }
        });
    }

    function companyLocationTab(tab)
    {
        return new Promise((resolve, reject) => {
            try {
                dtCompanyLocation().init();
                get_company('select[name="company_id"]');
                fvFileMaintenance('#'+tab+'_table',tab);
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

                localStorage.setItem("file_maintenance_tab",tab);
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
                    }
                })
            });

            setTimeout(() => {
                page_block.release();
            }, 300);

        }
    }

}
