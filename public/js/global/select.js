'use strict';

import {RequestHandler} from './request.js';


export async function trigger_select(select, text) {
    return new Promise((resolve) => {
        $(select).each(function() {
            let val = null;
            $(this).find('option').each(function() {
                if ($(this).text() === text) {
                    val = $(this).val();
                    return false; // Break out of the loop
                }
            });
            $(this).val(val).trigger('change'); // Set the value and trigger change
        });
        resolve(); // Resolve the promise after processing
    });
}

export async function get_filter_year(_element,type=1,param=true) {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', type);

        (new RequestHandler).post("/hris/employee/select/get_request_year", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_filter_month(_element) {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        element.attr('data-select2-initialized',true);
        element.select2({
            data: Array.from({length: 12}, (_, i) => ({ id: i + 1, text: new Date(0, i).toLocaleString('en-US', { month: 'long' }) })),
            dropdownParent: modal.length ? '#'+modal.attr('id') : null,
            width: '100%',
        });

        element.attr('disabled', false);
        resolve(true);
    });
}

export async function get_leave_type(_element,type="options",param='') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', type);

        (new RequestHandler).post("/hris/select/leave_type", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_leave_credit_type(_element,type="options",param='') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', type);

        (new RequestHandler).post("/hris/select/leave_credit_type", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_leave_fiscal_year(_element,type="options",param='') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', type);

        (new RequestHandler).post("/hris/select/leave_fiscal_year", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_company(_element,param='') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/hris/admin/select/get_company", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_company_location(_element,param='') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/hris/select/company_location", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_department(_element,param='') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/hris/select/department",formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_section(_element,param='') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/hris/select/section", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}

export async function get_employee(_element,param='') {
    return new Promise((resolve, reject) => {
        let element = $(_element);
        let modal = element.closest('.modal');

        element.attr('disabled', true);

        let formData = new FormData();
        formData.append('id', param);
        formData.append('type', 'options');

        (new RequestHandler).post("/hris/select/employee", formData)
            .then((res) => {
                element.empty().append(res);
                element.attr('data-select2-initialized',true);
                element.select2({
                    dropdownParent: modal.length ? '#'+modal.attr('id') : null,
                    width: '100%',
                });
                resolve(true);
            })
            .catch((error) => {
                console.error(error);
                resolve(false);
            })
            .finally(() => {
                element.attr('disabled', false);
            });
    });
}


