'use strict';
import { modal_state } from "../../global.js";
import {Alert} from "../../global/alert.js";
import {RequestHandler} from "../../global/request.js";

export var ApproverController = function (page, param) {

    const _modal = '#request_modal';
    const _div = '#kt_app_main';
    const _title = page
    .split('_') // Split the string at underscores
    .map((word, index) =>
        index === 0 ? word.toUpperCase() : word.charAt(0).toUpperCase() + word.slice(1)
    )
    .join(' ');

    async function ModalHandler(path = 'info')
    {
        const _request = new RequestHandler;
        const _formData = new FormData;

        _formData.append('param',param);
        _request.post('/hris/approver/'+page+'/'+path,_formData)
        .then((res) => {
            if(res.status == 'success'){
                let view = '', payload = res.payload;

                if(path == 'next-request'){
                    if(payload.token){
                        window.history.pushState(null, null,payload.token);
                    }
                    view =window.atob(payload.view);
                }else if(path == 'info'){
                    view =window.atob(payload);
                }
                $(_div).empty().append(view);
                console.log(view)

                modal_state(_modal,'show');
                ModalEvents();
            }else if(res.status =='error'){
                Alert.loading(res.message,{
                    didOpen:function(){
                        setTimeout(function() {
                            window.location.replace('/hris/employee/login');
                        }, 3500);
                    }
                });
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

    async function ModalEvents()
    {
        $(_modal).off();

        $(_modal).on('click','button.action',function(e){
            e.preventDefault();
            e.stopImmediatePropagation();

            modal_state(_modal,'hide');

            let _this = $(this);
            let dataAction = _this.attr('data-action');
            let isRequired = _this.attr('data-action')=='approve'?false:true;

            const _request = new RequestHandler;
            const _formData = new FormData;

            _formData.append('param',param);
            _formData.append('is_approved',dataAction=='approve'?1:2);
            _formData.append('id',_this.attr('data-id'));

            Alert.input('question','Do you want to '+dataAction+' this request ?',{
                isRequired: isRequired,
                inputPlaceholder: "Put your reason",
                onConfirm: function(approver_remarks) {
                    _formData.append('approver_remarks',approver_remarks);
                    _request.post('/hris/approver/'+page+'/update',_formData)
                    .then((res) => {
                        if(res.status == 'success')
                        {
                            Alert.toast('success',res.message);
                            Alert.confirm('question','Proceed to next '+_title+' ?', {
                                onConfirm: function() {
                                    ModalHandler('next-request');
                                },
                                onCancel: function() {
                                    // ModalHandler();
                                    Alert.loading('Redirecting you to login . . .',{
                                        didOpen:function(){
                                            setTimeout(function() {
                                                window.location.replace('/hris/employee/login');
                                            }, 3500);
                                        }
                                    });
                                }
                            })
                        }else{
                            Alert.alert(res.status,res.message, false);
                            ModalHandler();
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                        Alert.alert('error', "Something went wrong. Try again later", false);
                        ModalHandler();
                    })
                    .finally((error) => {
                        // modal_state(_modal,'show');
                    });
                },
                onCancel: function(approver_remarks) {
                    modal_state(_modal,'show');
                },

            });
        })
    }

    $(async function () {
        page_block.block();
        await ModalHandler();
        setTimeout(() => {
            page_block.release();
            KTComponents.init();
        }, 300);

    });
}
