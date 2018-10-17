
    define([
        'jquery', 'owlcarousel'
    ], function($){
        'use strict';
        $.widget('tigren.productCarousel',{
           _create: function() {
                this.productCarousel();
               console.log("asdasd");
           },
           productCarousel: function() {
               var options = {
                   loop:true,
                   margin:10,
                   nav:true,
                   items: 4,
                   responsive:{
                       0:{
                           items:1
                       },
                       600:{
                           items:2
                       },
                       1000:{
                           items:4
                       }
                   },

               }
               $(".owl-carousel").owlCarousel(options);
           }
        });
        return $.tigren.productCarousel;
    });