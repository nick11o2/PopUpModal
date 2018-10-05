
define([
        "jquery", "Magento_Ui/js/modal/modal", "mage/url"
    ], function($,modal,url){
        'use strict';
        var ClearCartUrl = url.build('JsModalExercise/ClearCart/ClearCartAjax');
        var CheckoutUrl = url.build('checkout/');
        var options = {
            responsive: true,
            innerScroll: true,
            title: 'Confirm',
            buttons: [{
                text: 'Clear cart',
                class: 'clear_cart',
                click: function(){
                    $.ajax({
                        url: ClearCartUrl,
                        type: 'POST',
                        dataType: "json",
                        success: function (response) {
                            console.log("success!");
                        }
                    });
                    this.closeModal();
                }
            }, {
                text: 'Continue',
                class: 'checkout',
                click: function(){
                    window.location.href = CheckoutUrl;
                }
            }]

        };
        return function(config, element){
            modal(options,$('#modal-content'));

            $(element).on('click', function(){
                $("#modal-content").modal("openModal");
            });
        };
    }

    // $.widget('tigren.modalopen', {
    //     _create: function() {
    //         this._openModal();
    //         console.log(this);
    //     },
    //
    //     _openModal: function() {
    //         var options = {
    //             responsive: true,
    //             innerScroll: true
    //         };
    //         modal(options, $('#modal-content'));
    //         this.element.on('click', function() {
    //             $("#modal-content").modal("openModal");
    //         });
    //     }
    // });
    // return $.tigren.modalopen;

);

//standard cua widget

// $.widget('namespace.widgetname', {
//     //code
//      _create: function() {
//          this._cxkcjzkcjzck();
//      }
// });