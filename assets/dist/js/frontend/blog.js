!function(e){function a(e,l){!function(){var o=l(".wn-loadmore-ajax"),n=o.data("site-url"),t=o.data("current-page"),i=o.data("max-page-num");o.data("total"),o.data("post-pre-page"),o.data("no-more-post");""==t&&o.attr("data-current-page",t="1"),t<i&&l(".wn-loadmore-ajax a").on("click",function(e){var a;e.preventDefault(),t<i&&(t++,o.find("a").attr("href",n+"/page/"+t+"/"),o.attr("data-current-page",t),e=l(this).attr("href"),(a=l('<div class="wn-circle-side-wrap"><div data-loader="wn-circle-side"></div></div>')).appendTo(l(this)),l.get(e,function(e){e=l(".wn-blog-ajax",e);l(".wn-loadmore-ajax").before(e),a.remove(),l(0<"tline-box".length)&&(l(".blog-social-5").find(".more-less").children(".less").hide(),l(".blog-social-5").find(".linkedin, .email").hide(),l(".blog-social-5").find("a.more-less").on("click",function(e){e.preventDefault(),l(this).closest(".blog-social-5").find(".linkedin, .email").slideToggle("400"),l(this).closest(".blog-social-5").find(".more-less").children(".more").slideToggle(400),l(this).closest(".blog-social-5").find(".more-less").children(".less").slideToggle(400)}))}))})}()}e(window).on("elementor/frontend/init",function(){elementorFrontend.hooks.addAction("frontend/element_ready/blog.default",a)})}(jQuery);