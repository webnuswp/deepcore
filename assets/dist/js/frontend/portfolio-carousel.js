!function(t){t(".portfolio-carousel").owlCarousel({items:2,autoPlay:!0,dots:!1,nav:!0,margin:90,autoWidth:!0,stagePadding:30,autoHeight:!0,loop:!0,navText:["<span class='ol-pre'></span>","","",""]});var o=t("#latest-projects-2");o.owlCarousel({items:4,nav:!0,dots:!1}),t(".latest-projects-navigation .next").on("click",function(){o.find(".owl-next").trigger("click")}),t(".latest-projects-navigation .prev").on("click",function(){o.find(".owl-prev").trigger("click")}),t("#latest-projects").owlCarousel({items:8,nav:!1,dots:!0,autoPlay:!0,margin:10,loop:!1,responsiveClass:!0,responsive:{0:{items:1,nav:!1,dots:!0},768:{items:2,nav:!1,dots:!0},960:{items:4,nav:!1,dots:!0}}})}(jQuery);