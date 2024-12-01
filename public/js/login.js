"use strict";

import { Alert } from './global/alert.js';
import { RequestHandler } from './global/request.js';
import {fv_validator} from './global.js';

var fvLogin = (function () {
    var _handleLogin = function() {

        let fvLogin;
        let form = document.querySelector("#form");
        let submit = $('.submit');

        const _form = new FormData();
        const _request = new RequestHandler();

        if (!form.hasAttribute('data-fv-initialized')) {
            fvLogin = FormValidation.formValidation(form,{
                fields: {
                    username: fv_validator(),
                    password: fv_validator(),
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: "",
                    }),
                }
            });
        }

        submit.click((e) => {
            e.preventDefault();
            e.stopImmediatePropagation();

            fvLogin && fvLogin.validate().then((i) => {
                if (i == "Valid") {

                    // submit.attr("data-kt-indicator", "on").attr('disabled', true);
                    _form.append("username",form.querySelector('[name="username"]').value);
                    _form.append("password",form.querySelector('[name="password"]').value);

                    _request.post(form.getAttribute('action'),_form)
                    .then((res) => {
                        Alert.toast( res.status, res.message);
                        if(res.status == 'success'){
                            window.location.replace(res.payload);
                        }else{
                            $("#csrf-token").attr('content', res.payload);
                            submit.attr("data-kt-indicator", "off").attr('disabled', false);
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                        Alert.alert('error', "Something went wrong. Try again later", false);
                    })
                    .finally((error) => {
                        //code here
                    });
                }
            });
        });

        window.onload = function () {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        }

    }

    return {
        init: function () {
            _handleLogin();
        }
    };
})();

jQuery(document).ready(function() {
    fvLogin.init();
});
