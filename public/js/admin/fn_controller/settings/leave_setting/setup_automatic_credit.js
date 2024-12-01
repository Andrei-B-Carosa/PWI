'use strict';
import { fvLeaveSetup } from "../../../fv_controller/fv_leave_settings/fv_setup_automatic_credit.js";

export var AutomaticCreditController = function (page,param) {

    const _page = $('.page-automatic-credit-settings');

    fvLeaveSetup(false,param);

    return {
        init: function () {
            page_block.block();
            setTimeout(() => {
                page_block.release();
                $('.multi-select').select2({ closeOnSelect: false });
                $('#repeater').find('[data-repeater-item]').remove();
            }, 300);
        }
    }

}
