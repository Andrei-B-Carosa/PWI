'use strict';

import { page_content } from './pg_content.js';
import { Alert } from '../global/alert.js';
import { ApproverController } from './fn_controller.js/fn_approver.js';


async function init_page() {
    let pathname = window.location.pathname;
    let page = pathname.split("/")[3];
    let id = false;
    let url = window.location.pathname;
    if(url.split('/')[4] !== null && typeof url.split('/')[4] !== 'undefined'){
        id =  pathname.split("/")[4];
    }
    load_page(page, id).then((res) => {
        if (res) {
            $(`.sidebar[data-page='${page}']`).addClass('active').attr('processing',false);
        }
    })
}

export async function load_page(page, param){
    try {
        await page_handler(page, param);
    } catch (error) {
        console.error('Error in load_page:', error);
        return false;
    }
}

export async function page_handler(page, param){
    const handler = _handlers[page];
    if (handler) {
        handler(page, param);
    } else {
        console.log("No handler found for this page");
    }
}

const _handlers = {
    ob_request: (page, param) => ApproverController(page, param),
};

$(document).ready(function(e){

    init_page();

    $(".sidebar").on("click", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        let _this = $(this);
        if (_this.data("processing")) {
            return; // Exit early if already in process
        }

        _this.data("processing", true);

        let page = _this.data('page');
        let link = _this.data('link');
        let title = _this.find('.menu-title').text();

        load_page(page)
        .then((res) => {
            if (res) {
                $('.sidebar').removeClass('active').attr('data-tab-initialized',false);
                _this.addClass('active');
                _this.attr('data-tab-initialized',true);
            }
        })
        .catch((err) => {
            Alert.alert("error","Error loading page:"+err);
        })
        .finally(() => {
            _this.data("processing", false);
        });
    });

})
