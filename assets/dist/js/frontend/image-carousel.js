!function(e){function a(e,s){s(".w-image-carousel").owlCarousel({items:s(".w-image-carousel").data("items"),autoplay:!0,autoplayTimeout:2e3,nav:!1,dots:!1,navText:["",""],loop:!0}),s(".w-image-carousel-type2").owlCarousel({center:!0,loop:!0,autoplay:!0,items:1,nav:!0,dots:!0,autoplayHoverPause:!0,animateOut:"slideOutUp",animateIn:"slideInUp",mouseDrag:!1,navText:["<span class='ol-pre'></span>","<span class='ol-nxt'></span>","",""],responsiveClass:!0,responsive:{0:{items:1},600:{items:1},1e3:{items:1}}}),function(){s(".w-image-carousel-type2 .center").prev().addClass("after"),s(".w-image-carousel-type2 .center").next().addClass("before");var e=s(".w-image-carousel-type2 .owl-stage-outer .active img").height(),a=s(".w-image-carousel-type2 .owl-stage-outer").find(".owl-item").not(".cloned").length,a=parseInt(e,10)/parseInt(a,10);s(".w-image-carousel-type2 .owl-dots .owl-dot").css("height",a),s(".w-image-carousel-type2").on("changed.owl.carousel",function(e){var a,t,o;"position"==e.property.name&&(o=e.relatedTarget.current(),a=s(this).find(".owl-stage").children(),t="changed"==e.type,a.eq(e.relatedTarget.normalize(o+1)).toggleClass("before",t),a.eq(e.relatedTarget.normalize(o-1)).toggleClass("after",t),s(".w-image-carousel-type2 .center").prev().removeClass("after"),s(".w-image-carousel-type2 .center").next().removeClass("before"),e=a.eq(e.relatedTarget.normalize(o)).height(),o=s(".w-image-carousel-type2 .owl-stage-outer").find(".owl-item").not(".cloned").length,o=parseInt(e,10)/parseInt(o,10),s(".w-image-carousel-type2 .owl-dots .owl-dot").css("height",o))})}(),s(".w-image-carousel-type3,.w-image-carousel-type4").owlCarousel({center:!0,loop:!0,autoplay:!0,items:1,nav:!0,dots:!1,autoplayHoverPause:!0,animateOut:"slideOutUp",animateIn:"slideInUp",mouseDrag:!1,navText:["<span class='ol-pre'></span>","<span class='ol-nxt'></span>","",""],responsiveClass:!0,responsive:{0:{items:1},600:{items:1},1e3:{items:1}}})}e(window).on("elementor/frontend/init",function(){elementorFrontend.hooks.addAction("frontend/element_ready/image_carousel.default",a)})}(jQuery);