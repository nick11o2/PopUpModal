define([

        "jquery", "Magento_Ui/js/modal/modal", "mage/url"
    ], function($,modal,url){
        'use strict';
        var ClearCartUrl = url.build('JsModalExercise/ClearCart/ClearCartAjax');
        var CheckoutUrl = 'checkout/';
        return function(config, element){
            var Clearcart = config + ClearCartUrl;
            var Checkout = config + CheckoutUrl;
            console.log(Checkout);
            var options = {
                responsive: true,
                innerScroll: true,
                title: 'Confirm',
                buttons: [{
                    text: 'Clear cart',
                    class: 'clear_cart',
                    click: function(){
                        $.ajax({
                            url: Clearcart,
                            type: 'POST',
                            dataType: "json",
                            success: function () {
                                console.log("success!");
                            },
                            complete: function() {
                                location.reload();
                            }
                        });
                        this.closeModal();
                    }
                }, {
                    text: 'Continue',
                    class: 'checkout',
                    click: function(){
                        window.location.href = Checkout;
                    }
                }]

            };

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

//standard widget

// $.widget('namespace.widgetname', {
//     //code
//      _create: function() {
//          this._cxkcjzkcjzck();
//      }
// });