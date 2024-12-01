'use strict';

import { dtManualCredit } from "../../../dt_controller/settings/dt_leave_settings/dt_manual_credit.js";

export var ManualCreditController = function (page,param) {

    const _page = $('.page-automatic-credit-settings');

    dtManualCredit(param).init();

    return {
        init: function () {
            page_block.block();
            setTimeout(() => {
                page_block.release();
            }, 300);
        }
    }

}
