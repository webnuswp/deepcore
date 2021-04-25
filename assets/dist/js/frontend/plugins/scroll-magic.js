!function(e,t){"function"==typeof define&&define.amd?define(t):"object"==typeof exports?module.exports=t():e.ScrollMagic=t()}(this,function(){"use strict";function D(){}D.version="2.0.5",window.addEventListener("mousewheel",function(){});var N="data-scrollmagic-pin-spacer";D.Controller=function(e){function t(){var e,t;v&&f&&(e=P.type.Array(f)?f:u.slice(0),f=!1,t=d,t=(d=l.scrollPos())-t,(p=0!=t?0<t?"FORWARD":i:p)===i&&e.reverse(),e.forEach(function(e){e.update(!0)}))}function n(){r=P.rAF(t)}var r,o,i="REVERSE",s="PAUSED",a=T.defaults,l=this,c=P.extend({},a,e),u=[],f=!1,d=0,p=s,h=!0,g=0,v=!0,m=function(){0<c.refreshInterval&&(o=window.setTimeout(E,c.refreshInterval))},w=function(){return c.vertical?P.get.scrollTop(c.container):P.get.scrollLeft(c.container)},y=function(){return c.vertical?P.get.height(c.container):P.get.width(c.container)},S=this._setScrollPos=function(e){c.vertical?h?window.scrollTo(P.get.scrollLeft(),e):c.container.scrollTop=e:h?window.scrollTo(e,P.get.scrollTop()):c.container.scrollLeft=e},b=function(e){"resize"==e.type&&(g=y(),p=s),!0!==f&&(f=!0,n())},E=function(){if(!h&&g!=y()){var t;try{t=new Event("resize",{bubbles:!1,cancelable:!1})}catch(e){(t=document.createEvent("Event")).initEvent("resize",!1,!1)}c.container.dispatchEvent(t)}u.forEach(function(e){e.refresh()}),m()};this._options=c;function x(e){return e.length<=1||(e=e.slice(0)).sort(function(e,t){return e.scrollOffset()>t.scrollOffset()?1:-1}),e}return this.addScene=function(e){if(P.type.Array(e))e.forEach(function(e){l.addScene(e)});else if(e instanceof D.Scene)if(e.controller()!==l)e.addTo(l);else if(u.indexOf(e)<0)for(var t in u.push(e),u=x(u),e.on("shift.controller_sort",function(){u=x(u)}),c.globalSceneOptions)e[t]&&e[t].call(e,c.globalSceneOptions[t]);return l},this.removeScene=function(e){var t;return P.type.Array(e)?e.forEach(function(e){l.removeScene(e)}):-1<(t=u.indexOf(e))&&(e.off("shift.controller_sort"),u.splice(t,1),e.remove()),l},this.updateScene=function(e,t){return P.type.Array(e)?e.forEach(function(e){l.updateScene(e,t)}):t?e.update(!0):!0!==f&&e instanceof D.Scene&&(-1==(f=f||[]).indexOf(e)&&f.push(e),f=x(f),n()),l},this.update=function(e){return b({type:"resize"}),e&&t(),l},this.scrollTo=function(e,t){if(P.type.Number(e))S.call(c.container,e,t);else if(e instanceof D.Scene)e.controller()===l&&l.scrollTo(e.scrollOffset(),t);else if(P.type.Function(e))S=e;else{var n=P.get.elements(e)[0];if(n){for(;n.parentNode.hasAttribute(N);)n=n.parentNode;var r=c.vertical?"top":"left",o=P.get.offset(c.container),e=P.get.offset(n);h||(o[r]-=l.scrollPos()),l.scrollTo(e[r]-o[r],t)}}return l},this.scrollPos=function(e){return arguments.length?(P.type.Function(e)&&(w=e),l):w.call(l)},this.info=function(e){var t={size:g,vertical:c.vertical,scrollPos:d,scrollDirection:p,container:c.container,isDocument:h};return arguments.length?void 0!==t[e]?t[e]:void 0:t},this.loglevel=function(){return l},this.enabled=function(e){return arguments.length?(v!=e&&(v=!!e,l.updateScene(u,!0)),l):v},this.destroy=function(e){window.clearTimeout(o);for(var t=u.length;t--;)u[t].destroy(e);return c.container.removeEventListener("resize",b),c.container.removeEventListener("scroll",b),P.cAF(r),null},function(){for(var e in c)a.hasOwnProperty(e)||delete c[e];if(c.container=P.get.elements(c.container)[0],!c.container)throw"ScrollMagic.Controller init failed.";(h=c.container===window||c.container===document.body||!document.body.contains(c.container))&&(c.container=window),g=y(),c.container.addEventListener("resize",b),c.container.addEventListener("scroll",b),c.refreshInterval=parseInt(c.refreshInterval)||a.refreshInterval,m()}(),l};var T={defaults:{container:window,vertical:!0,globalSceneOptions:{},loglevel:2,refreshInterval:100}};D.Controller.addOption=function(e,t){T.defaults[e]=t},D.Controller.extend=function(e){var t=this;D.Controller=function(){return t.apply(this,arguments),this.$super=P.extend({},this),e.apply(this,arguments)||this},P.extend(D.Controller,t),D.Controller.prototype=t.prototype,D.Controller.prototype.constructor=D.Controller},D.Scene=function(e){var n,a,s="BEFORE",l="DURING",c="AFTER",r=M.defaults,u=this,f=P.extend({},r,e),d=s,p=0,o={start:0,end:0},h=0,i=!0,g={};this.on=function(e,n){return P.type.Function(n)&&(e=e.trim().split(" ")).forEach(function(e){var t=e.split("."),e=t[0],t=t[1];"*"!=e&&(g[e]||(g[e]=[]),g[e].push({namespace:t||"",callback:n}))}),u},this.off=function(e,i){return e&&(e=e.trim().split(" ")).forEach(function(e){var t=e.split("."),e=t[0],o=t[1]||"";("*"===e?Object.keys(g):[e]).forEach(function(e){for(var t=g[e]||[],n=t.length;n--;){var r=t[n];!r||o!==r.namespace&&"*"!==o||i&&i!=r.callback||t.splice(n,1)}t.length||delete g[e]})}),u},this.trigger=function(e,t){var n,r;return e&&(e=e.trim().split("."),n=e[0],r=e[1],(e=g[n])&&e.forEach(function(e){r&&r!==e.namespace||e.callback.call(u,new D.Event(n,e.namespace,u,t))})),u},u.on("change.internal",function(e){"loglevel"!==e.what&&"tweenChanges"!==e.what&&("triggerElement"===e.what?y():"reverse"===e.what&&u.update())}).on("shift.internal",function(){t(),u.update()}),this.addTo=function(e){return e instanceof D.Controller&&a!=e&&(a&&a.removeScene(u),a=e,E(),w(!0),y(!0),t(),a.info("container").addEventListener("resize",S),e.addScene(u),u.trigger("add",{controller:a}),u.update()),u},this.enabled=function(e){return arguments.length?(i!=e&&(i=!!e,u.update(!0)),u):i},this.remove=function(){var e;return a&&(a.info("container").removeEventListener("resize",S),e=a,a=void 0,e.removeScene(u),u.trigger("remove")),u},this.destroy=function(e){return u.trigger("destroy",{reset:e}),u.remove(),u.off("*.*"),null},this.update=function(e){var t;return a&&(e?a.enabled()&&i?(t=a.info("scrollPos"),e=0<f.duration?(t-o.start)/(o.end-o.start):t>=o.start?1:0,u.trigger("update",{startPos:o.start,endPos:o.end,scrollPos:t}),u.progress(e)):v&&d===l&&L(!0):a.updateScene(u,!1)),u},this.refresh=function(){return w(),y(),u},this.progress=function(e){if(arguments.length){var t,n=!1,r=d,o=a?a.info("scrollDirection"):"PAUSED",i=f.reverse||p<=e;return 0===f.duration?(n=p!=e,d=0===(p=e<1&&i?0:1)?s:l):e<0&&d!==s&&i?(d=s,n=!(p=0)):0<=e&&e<1&&i?(p=e,d=l,n=!0):1<=e&&d!==c?(p=1,d=c,n=!0):d!==l||i||L(),n&&(t={progress:p,state:d,scrollDirection:o},n=function(e){u.trigger(e,t)},(o=d!=r)&&r!==l&&(n("enter"),n(r===s?"start":"end")),n("progress"),o&&d!==l&&(n(d===s?"start":"end"),n("leave"))),u}return p};var v,m,t=function(){o={start:h+f.offset},a&&f.triggerElement&&(o.start-=a.info("size")*f.triggerHook),o.end=o.start+f.duration},w=function(e){var t;!n||x(t="duration",n.call(u))&&!e&&(u.trigger("change",{what:t,newval:f[t]}),u.trigger("shift",{reason:t}))},y=function(e){var t=0,n=f.triggerElement;if(a&&n){for(var r=a.info(),o=P.get.offset(r.container),i=r.vertical?"top":"left";n.parentNode.hasAttribute(N);)n=n.parentNode;var s=P.get.offset(n);r.isDocument||(o[i]-=a.scrollPos()),t=s[i]-o[i]}i=t!=h;h=t,i&&!e&&u.trigger("shift",{reason:"triggerElementPosition"})},S=function(){0<f.triggerHook&&u.trigger("shift",{reason:"containerResize"})},b=P.extend(M.validate,{duration:function(t){var e;if(P.type.String(t)&&t.match(/^(\.|\d)*\d+%$/)&&(e=parseFloat(t)/100,t=function(){return a?a.info("size")*e:0}),P.type.Function(t)){n=t;try{t=parseFloat(n())}catch(e){t=-1}}if(t=parseFloat(t),!P.type.Number(t)||t<0)throw n=n&&void 0,0;return t}}),E=function(e){(e=arguments.length?[e]:Object.keys(b)).forEach(function(t){var n;if(b[t])try{n=b[t](f[t])}catch(e){n=r[t]}finally{f[t]=n}})},x=function(e,t){var n=!1,r=f[e];return f[e]!=t&&(f[e]=t,E(e),n=r!=f[e]),n},T=function(t){u[t]||(u[t]=function(e){return arguments.length?("duration"===t&&(n=void 0),x(t,e)&&(u.trigger("change",{what:t,newval:f[t]}),-1<M.shifts.indexOf(t)&&u.trigger("shift",{reason:t})),u):f[t]})};this.controller=function(){return a},this.state=function(){return d},this.scrollOffset=function(){return o.start},this.triggerPosition=function(){var e=f.offset;return a&&(e+=f.triggerElement?h:a.info("size")*u.triggerHook()),e},u.on("shift.internal",function(e){e="duration"===e.reason;(d===c&&e||d===l&&0===f.duration)&&L(),e&&A()}).on("progress.internal",function(){L()}).on("add.internal",function(){A()}).on("destroy.internal",function(e){u.removePin(e.reset)});function C(){a&&v&&d===l&&!a.info("isDocument")&&L()}function z(){a&&v&&d===l&&((m.relSize.width||m.relSize.autoFullWidth)&&P.get.width(window)!=P.get.width(m.spacer.parentNode)||m.relSize.height&&P.get.height(window)!=P.get.height(m.spacer.parentNode))&&A()}function F(e){a&&v&&d===l&&!a.info("isDocument")&&(e.preventDefault(),a._setScrollPos(a.info("scrollPos")-((e.wheelDelta||e[a.info("vertical")?"wheelDeltaY":"wheelDeltaX"])/3||30*-e.detail)))}var L=function(e){var t,n,r;v&&a&&(t=a.info(),r=m.spacer.firstChild,e||d!==l?(e={position:m.inFlow?"relative":"absolute",top:0,left:0},n=P.css(r,"position")!=e.position,m.pushFollowers?0<f.duration&&(d===c&&0===parseFloat(P.css(m.spacer,"padding-top"))||d===s&&0===parseFloat(P.css(m.spacer,"padding-bottom")))&&(n=!0):e[t.vertical?"top":"left"]=f.duration*p,P.css(r,e),n&&A()):("fixed"!=P.css(r,"position")&&(P.css(r,{position:"fixed"}),A()),n=P.get.offset(m.spacer,!0),r=f.reverse||0===f.duration?t.scrollPos-o.start:Math.round(p*f.duration*10)/10,n[t.vertical?"top":"left"]+=r,P.css(m.spacer.firstChild,{top:n.top,left:n.left})))},A=function(){var e,t,n,r,o;v&&a&&m.inFlow&&(e=d===l,t=a.info("vertical"),n=m.spacer.firstChild,r=P.isMarginCollapseType(P.css(m.spacer,"display")),o={},m.relSize.width||m.relSize.autoFullWidth?e?P.css(v,{width:P.get.width(m.spacer)}):P.css(v,{width:"100%"}):(o["min-width"]=P.get.width(t?v:n,!0,!0),o.width=e?o["min-width"]:"auto"),m.relSize.height?e?P.css(v,{height:P.get.height(m.spacer)-(m.pushFollowers?f.duration:0)}):P.css(v,{height:"100%"}):(o["min-height"]=P.get.height(t?n:v,!0,!r),o.height=e?o["min-height"]:"auto"),m.pushFollowers&&(o["padding"+(t?"Top":"Left")]=f.duration*p,o["padding"+(t?"Bottom":"Right")]=f.duration*(1-p)),P.css(m.spacer,o))};this.setPin=function(e,t){if(t=P.extend({},{pushFollowers:!0,spacerClass:"scrollmagic-pin-spacer"},t),!(e=P.get.elements(e)[0]))return u;if("fixed"===P.css(e,"position"))return u;if(v){if(v===e)return u;u.removePin()}var n=(v=e).parentNode.style.display,r=["top","left","bottom","right","margin","marginLeft","marginRight","marginTop","marginBottom"];v.parentNode.style.display="none";var o="absolute"!=P.css(v,"position"),i=P.css(v,r.concat(["display"])),s=P.css(v,["width","height"]);v.parentNode.style.display=n,!o&&t.pushFollowers&&(t.pushFollowers=!1);var a,e=v.parentNode.insertBefore(document.createElement("div"),v),n=P.extend(i,{position:o?"relative":"absolute",boxSizing:"content-box",mozBoxSizing:"content-box",webkitBoxSizing:"content-box"});return o||P.extend(n,P.css(v,["width","height"])),P.css(e,n),e.setAttribute(N,""),P.addClass(e,t.spacerClass),m={spacer:e,relSize:{width:"%"===s.width.slice(-1),height:"%"===s.height.slice(-1),autoFullWidth:"auto"===s.width&&o&&P.isMarginCollapseType(i.display)},pushFollowers:t.pushFollowers,inFlow:o},v.___origStyle||(v.___origStyle={},a=v.style,r.concat(["width","height","position","boxSizing","mozBoxSizing","webkitBoxSizing"]).forEach(function(e){v.___origStyle[e]=a[e]||""})),m.relSize.width&&P.css(e,{width:s.width}),m.relSize.height&&P.css(e,{height:s.height}),e.appendChild(v),P.css(v,{position:o?"relative":"absolute",margin:"auto",top:"auto",left:"auto",bottom:"auto",right:"auto"}),(m.relSize.width||m.relSize.autoFullWidth)&&P.css(v,{boxSizing:"border-box",mozBoxSizing:"border-box",webkitBoxSizing:"border-box"}),window.addEventListener("scroll",C),window.addEventListener("resize",C),window.addEventListener("resize",z),v.addEventListener("mousewheel",F),v.addEventListener("DOMMouseScroll",F),L(),u},this.removePin=function(e){var t;return v&&(d===l&&L(!0),!e&&a||((e=m.spacer.firstChild).hasAttribute(N)&&(t=m.spacer.style,margins={},["margin","marginLeft","marginRight","marginTop","marginBottom"].forEach(function(e){margins[e]=t[e]||""}),P.css(e,margins)),m.spacer.parentNode.insertBefore(e,m.spacer),m.spacer.parentNode.removeChild(m.spacer),v.parentNode.hasAttribute(N)||(P.css(v,v.___origStyle),delete v.___origStyle)),window.removeEventListener("scroll",C),window.removeEventListener("resize",C),window.removeEventListener("resize",z),v.removeEventListener("mousewheel",F),v.removeEventListener("DOMMouseScroll",F),v=void 0),u};var O,_=[];return u.on("destroy.internal",function(e){u.removeClassToggle(e.reset)}),this.setClassToggle=function(e,t){e=P.get.elements(e);return 0!==e.length&&P.type.String(t)&&(0<_.length&&u.removeClassToggle(),O=t,_=e,u.on("enter.internal_class leave.internal_class",function(e){var t="enter"===e.type?P.addClass:P.removeClass;_.forEach(function(e){t(e,O)})})),u},this.removeClassToggle=function(e){return e&&_.forEach(function(e){P.removeClass(e,O)}),u.off("start.internal_class end.internal_class"),O=void 0,_=[],u},function(){for(var e in f)r.hasOwnProperty(e)||delete f[e];for(var t in r)T(t);E()}(),u};var M={defaults:{duration:0,offset:0,triggerElement:void 0,triggerHook:.5,reverse:!0,loglevel:2},validate:{offset:function(e){if(e=parseFloat(e),!P.type.Number(e))throw 0;return e},triggerElement:function(e){if(e=e||void 0){var t=P.get.elements(e)[0];if(!t)throw 0;e=t}return e},triggerHook:function(e){var t={onCenter:.5,onEnter:1,onLeave:0};if(P.type.Number(e))e=Math.max(0,Math.min(parseFloat(e),1));else{if(!(e in t))throw 0;e=t[e]}return e},reverse:function(e){return!!e}},shifts:["duration","offset","triggerHook"]};D.Scene.addOption=function(e,t,n,r){e in M.defaults||(M.defaults[e]=t,M.validate[e]=n,r&&M.shifts.push(e))},D.Scene.extend=function(e){var t=this;D.Scene=function(){return t.apply(this,arguments),this.$super=P.extend({},this),e.apply(this,arguments)||this},P.extend(D.Scene,t),D.Scene.prototype=t.prototype,D.Scene.prototype.constructor=D.Scene},D.Event=function(e,t,n,r){for(var o in r=r||{})this[o]=r[o];return this.type=e,this.target=this.currentTarget=n,this.namespace=t||"",this.timeStamp=this.timestamp=Date.now(),this};var P=D._util=function(i){function s(e){return parseFloat(e)||0}function a(e){return e.currentStyle||i.getComputedStyle(e)}function r(e,t,n,r){if((t=t===document?i:t)===i)r=!1;else if(!f.DomElement(t))return 0;e=e.charAt(0).toUpperCase()+e.substr(1).toLowerCase();var o=(n?t["offset"+e]||t["outer"+e]:t["client"+e]||t["inner"+e])||0;return n&&r&&(t=a(t),o+="Height"===e?s(t.marginTop)+s(t.marginBottom):s(t.marginLeft)+s(t.marginRight)),o}function l(e){return e.replace(/^[^a-z]+([a-z])/g,"$1").replace(/-([a-z])/g,function(e){return e[1].toUpperCase()})}var e={};e.extend=function(e){for(e=e||{},u=1;u<arguments.length;u++)if(arguments[u])for(var t in arguments[u])arguments[u].hasOwnProperty(t)&&(e[t]=arguments[u][t]);return e},e.isMarginCollapseType=function(e){return-1<["block","flex","list-item","table","-webkit-box"].indexOf(e)};for(var o=0,t=["ms","moz","webkit","o"],n=i.requestAnimationFrame,c=i.cancelAnimationFrame,u=0;!n&&u<t.length;++u)n=i[t[u]+"RequestAnimationFrame"],c=i[t[u]+"CancelAnimationFrame"]||i[t[u]+"CancelRequestAnimationFrame"];c=c||function(e){i.clearTimeout(e)},e.rAF=(n=n||function(e){var t=(new Date).getTime(),n=Math.max(0,16-(t-o)),r=i.setTimeout(function(){e(t+n)},n);return o=t+n,r}).bind(i),e.cAF=c.bind(i);var f=e.type=function(e){return Object.prototype.toString.call(e).replace(/^\[object (.+)\]$/,"$1").toLowerCase()};f.String=function(e){return"string"===f(e)},f.Function=function(e){return"function"===f(e)},f.Array=function(e){return Array.isArray(e)},f.Number=function(e){return!f.Array(e)&&0<=e-parseFloat(e)+1},f.DomElement=function(e){return"object"==typeof HTMLElement?e instanceof HTMLElement:e&&"object"==typeof e&&null!==e&&1===e.nodeType&&"string"==typeof e.nodeName};var d=e.get={};return d.elements=function(e){var t=[];if(f.String(e))try{e=document.querySelectorAll(e)}catch(e){return t}if("nodelist"===f(e)||f.Array(e))for(var n=0,r=t.length=e.length;n<r;n++){var o=e[n];t[n]=f.DomElement(o)?o:d.elements(o)}else!f.DomElement(e)&&e!==document&&e!==i||(t=[e]);return t},d.scrollTop=function(e){return e&&"number"==typeof e.scrollTop?e.scrollTop:i.pageYOffset||0},d.scrollLeft=function(e){return e&&"number"==typeof e.scrollLeft?e.scrollLeft:i.pageXOffset||0},d.width=function(e,t,n){return r("width",e,t,n)},d.height=function(e,t,n){return r("height",e,t,n)},d.offset=function(e,t){var n={top:0,left:0};return e&&e.getBoundingClientRect&&(e=e.getBoundingClientRect(),n.top=e.top,n.left=e.left,t||(n.top+=d.scrollTop(),n.left+=d.scrollLeft())),n},e.addClass=function(e,t){t&&(e.classList?e.classList.add(t):e.className+=" "+t)},e.removeClass=function(e,t){t&&(e.classList?e.classList.remove(t):e.className=e.className.replace(RegExp("(^|\\b)"+t.split(" ").join("|")+"(\\b|$)","gi")," "))},e.css=function(e,t){if(f.String(t))return a(e)[l(t)];if(f.Array(t)){var n={},r=a(e);return t.forEach(function(e){n[e]=r[l(e)]}),n}for(var o in t){var i=t[o];i==parseFloat(i)&&(i+="px"),e.style[l(o)]=i}},e}(window||{});return D}),function(e,t){"function"==typeof define&&define.amd?define(["ScrollMagic","TweenMax","TimelineMax"],t):"object"==typeof exports?(require("gsap"),t(require("scrollmagic"),TweenMax,TimelineMax)):t(e.ScrollMagic||e.jQuery&&e.jQuery.ScrollMagic,e.TweenMax||e.TweenLite,e.TimelineMax||e.TimelineLite)}(this,function(e,a,l){"use strict";e.Scene.addOption("tweenChanges",!1,function(e){return!!e}),e.Scene.extend(function(){var o,i=this;i.on("progress.plugin_gsap",function(){s()}),i.on("destroy.plugin_gsap",function(e){i.removeTween(e.reset)});var s=function(){var e,t;o&&(e=i.progress(),t=i.state(),o.repeat&&-1===o.repeat()?"DURING"===t&&o.paused()?o.play():"DURING"===t||o.paused()||o.pause():e!=o.progress()&&(0===i.duration()?0<e?o.play():o.reverse():i.tweenChanges()&&o.tweenTo?o.tweenTo(e*o.duration()):o.progress(e).pause()))};i.setTween=function(e,t,n){var r;1<arguments.length&&(arguments.length<3&&(n=t,t=1),e=a.to(e,t,n));try{(r=l?new l({smoothChildTiming:!0}).add(e):e).pause()}catch(e){return i}return o&&i.removeTween(),o=r,e.repeat&&-1===e.repeat()&&(o.repeat(-1),o.yoyo(e.yoyo())),s(),i},i.removeTween=function(e){return o&&(e&&o.progress(0).pause(),o.kill(),o=void 0),i}})});