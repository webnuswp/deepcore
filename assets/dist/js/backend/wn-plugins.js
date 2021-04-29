"use strict";!function(o){o(document).ready(function(){var t=o(".wn-plugins"),c=o(".wn-admin-btn");t.on("click",'.wn-admin-btn[data-plugin-action="install"]',function(t){var n;t.preventDefault(),o(".wn-admin-btn").hasClass("installing")||(t={action:"deep_install_plugin",plugin:(t=(n=o(this)).attr("href").split("&"))[1].substr(t[1].lastIndexOf("=")+1,t[1].length),"tgmpa-install":"install-plugin","tgmpa-nonce":t[3].substr(t[3].lastIndexOf("=")+1,t[3].length),page:"install-required-plugins"},n.addClass("installing"),c.css("opacity","0.5"),n.css("opacity","1"),o.ajax({type:"GET",url:ajaxurl,data:t}).done(function(t){var a;n.closest(".wsw-install-deep-plus").length&&((a=location.href).includes("step")?(a=a.slice(0,-1),a+=2):a+="&step=2",location.href=a),c.css("opacity","1"),n.closest(".wn-plugin").length?n.closest(".row-actions").replaceWith('<div class="row-actions visible active"><span class="activate"><a href="#" class="button wn-admin-btn">Activated</a></span></div>'):n.removeClass("installing").attr("data-plugin-action","deactivate").attr("href",t.substr(t.lastIndexOf("webnusi")+6,t.length)).text("Deactivate").closest(".theme").addClass("active")}).fail(function(){alert("Something went wrong! Reload page and try again.")}))}),t.on("click",'.wn-admin-btn[data-plugin-action="update"]',function(t){var n;t.preventDefault(),o(".wn-admin-btn").hasClass("installing")||(t={action:"deep_update_plugin",plugin:(t=(n=o(this)).attr("href").split("&"))[1].substr(t[1].lastIndexOf("=")+1,t[1].length),"tgmpa-update":"update-plugin","tgmpa-nonce":t[3].substr(t[3].lastIndexOf("=")+1,t[3].length),page:"install-required-plugins"},n.addClass("installing"),c.css("opacity","0.5"),n.css("opacity","1"),o.ajax({type:"GET",url:ajaxurl,data:t}).done(function(t){var a;n.closest(".wsw-install-deep-plus").length&&((a=location.href).includes("step")?(a=a.slice(0,-1),a+=2):a+="&step=2",location.href=a),c.css("opacity","1"),n.closest(".row-actions").find(".update").remove(),n.closest(".wn-plugin").length?n.closest(".row-actions").replaceWith('<div class="row-actions visible active"><span class="activate"><a href="#" class="button wn-admin-btn">Activated</a></span></div>'):n.removeClass("installing").attr("data-plugin-action","deactivate").attr("href",t.substr(t.lastIndexOf("webnusi")+6,t.length)).text("Deactivate").closest(".theme").addClass("active")}).fail(function(){alert("Something went wrong! Reload page and try again.")}))}),t.on("click",'.wn-admin-btn[data-plugin-action="activate"]',function(t){var n;t.preventDefault(),o(".wn-admin-btn").hasClass("installing")||(t={action:"deep_activate_plugin",plugin:(t=(n=o(this)).attr("href").split("&"))[1].substr(t[1].lastIndexOf("=")+1,t[1].length),"tgmpa-activate":"activate-plugin","tgmpa-nonce":t[3].substr(t[3].lastIndexOf("=")+1,t[3].length)},n.addClass("installing"),c.css("opacity","0.5"),n.css("opacity","1"),o.ajax({type:"GET",url:ajaxurl,data:t,success:function(t){var a;n.closest(".wsw-install-deep-plus").length&&((a=location.href).includes("step")?(a=a.slice(0,-1),a+=2):a+="&step=2",location.href=a),c.css("opacity","1"),n.closest(".wn-plugin").length?n.closest(".row-actions").replaceWith('<div class="row-actions visible active"><span class="activate"><a href="#" class="button wn-admin-btn">Activated</a></span></div>'):n.removeClass("installing").attr("data-plugin-action","deactivate").attr("href",t).text("Deactivate").closest(".theme").addClass("active")}}))}),t.on("click",'.wn-admin-btn[data-plugin-action="deactivate"]',function(t){var a;t.preventDefault(),o(".wn-admin-btn").hasClass("installing")||(t={action:"deep_deactivate_plugin",plugin:(t=(a=o(this)).attr("href").split("&"))[1].substr(t[1].lastIndexOf("=")+1,t[1].length),"tgmpa-deactivate":"deactivate-plugin","tgmpa-nonce":t[3].substr(t[3].lastIndexOf("=")+1,t[3].length)},a.addClass("installing"),c.css("opacity","0.5"),a.css("opacity","1"),o.ajax({type:"GET",url:ajaxurl,data:t,success:function(t){c.css("opacity","1"),a.removeClass("installing").attr("data-plugin-action","activate").attr("href",t).text("Activate").closest(".theme").removeClass("active")}}))}),o(".whi-install-plugins").on("click",function(t){t.preventDefault();var a=o(this),t=a.parent().next(".wn-plugins"),i=[];t.find(".wn-plugin:not(:hidden)").each(function(t,a){var n=o(this).find(".wn-admin-btn"),e=n.attr("href"),s=n.data("plugin-action");null!=e&&"#"!=e&&i.push({elem:n[0],href:e,pluginAction:s})}),i.length?function a(n,e){if(!n.length)return void e.css({"pointer-events":"none"});if(o(".wn-admin-btn").hasClass("installing"))return;var s=o(n[0].elem);var t=n[0].pluginAction;var i=s.attr("href").split("&");var l;"install"==t?l={action:"deep_install_plugin",plugin:i[1].substr(i[1].lastIndexOf("=")+1,i[1].length),"tgmpa-install":"install-plugin","tgmpa-nonce":i[3].substr(i[3].lastIndexOf("=")+1,i[3].length),page:"install-required-plugins"}:"activate"==t?l={action:"deep_activate_plugin",plugin:i[1].substr(i[1].lastIndexOf("=")+1,i[1].length),"tgmpa-activate":"activate-plugin","tgmpa-nonce":i[3].substr(i[3].lastIndexOf("=")+1,i[3].length)}:"update"==t?l={action:"deep_update_plugin",plugin:i[1].substr(i[1].lastIndexOf("=")+1,i[1].length),"tgmpa-update":"update-plugin","tgmpa-nonce":i[3].substr(i[3].lastIndexOf("=")+1,i[3].length),page:"install-required-plugins"}:(n.shift(),a(n,e));s.addClass("installing");c.css("opacity","0.5");s.css("opacity","1");o.ajax({type:"GET",url:ajaxurl,data:l}).done(function(t){c.css("opacity","1"),s.closest(".wn-plugin").length?s.closest(".row-actions").replaceWith('<div class="row-actions visible active"><span class="activate"><a href="#" class="button wn-admin-btn">Activated</a></span></div>'):s.removeClass("installing").attr("data-plugin-action","deactivate").attr("href",t.substr(t.lastIndexOf("webnusi")+6,t.length)).text("Deactivate").closest(".theme").addClass("active"),n.shift(),a(n,e)}).fail(function(){alert("Something went wrong! Reload page and try again.")})}(i,a):a.css({"pointer-events":"none"})})})}(jQuery);