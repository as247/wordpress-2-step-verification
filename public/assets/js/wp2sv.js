!function(t){var n={};function o(e){if(n[e])return n[e].exports;var s=n[e]={i:e,l:!1,exports:{}};return t[e].call(s.exports,s,s.exports,o),s.l=!0,s.exports}o.m=t,o.c=n,o.d=function(t,n,e){o.o(t,n)||Object.defineProperty(t,n,{configurable:!1,enumerable:!0,get:e})},o.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return o.d(n,"a",n),n},o.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},o.p="/",o(o.s=48)}({48:function(t,n,o){t.exports=o(49)},49:function(t,n){var o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t};window.wp2sv=window.wp2sv||{},function(t,n){"use strict";var e,s=0;t.init=function(){n(document).on("click","[data-wp2sv-modal]",function(o){o.preventDefault(),t.openModal(n(this).data("wp2sv-modal"))}),n(document).on("click",".wp2sv-modal-close",function(n){n.preventDefault(),t.closeModal(this)})},t.openModal=function(t){(t=n(t).closest(".wp2sv-modal")).find(".wp2sv-modal-content").length||t.wrapInner('<div class="wp2sv-modal-content"></div>'),t.trigger("open"),n(document).trigger("wp2sv-modal-open"),t.find(".wp2sv-modal-close-icon").length||t.append('<div class="wp2sv-modal-close"><span class="wp2sv-modal-close-icon"></span></div>'),s++,t.wrap('<div class="wp2sv-modal-mask"><div class="wp2sv-modal-wrap"></div></div>'),t.closest(".wp2sv-modal-mask").css("z-index",9999+s),e=t},t.closeModal=function(t){(t=(t=n(t=t||e)).closest(".wp2sv-modal")).trigger("close"),n(document).trigger("wp2sv-modal-close"),t.unwrap().unwrap(),t.trigger("closed"),s--};var i=1;t.confirm=function(o,e,s,c){var r=n("#wp2sv-confirm");r.after(r=r.clone()),r.attr("id","wp2sv-confirm-"+i++).addClass("wp2sv-confirm-modal"),"function"==typeof e&&(s=e,e=o,o=""),"function"!=typeof s&&(s=function(){return!0}),o&&n(".wp2sv-h1",r).html(o),e&&n(".wp2sv-p",r).html(e),c&&n(".wp2sv-cancel-btn",r).addClass("hidden"),r.on("closed",function(){r.remove()});var a=new Promise(function(o,e){r.on("click",".wp2sv-confirm-btn",function(){!1!==(n(this).is("[data-btn-ok]")?o(!0):o(!1))&&t.closeModal(r)})});return s&&a.then(s),t.openModal(r),a},t.alert=function(n,o,e){"function"==typeof o&&(e=o,o=""),t.confirm(o,n,e,!0)},t.post=function(n,o){return t.ajax(n,o,"POST")},t.get=function(n,o){return t.ajax(n,o)},t.ajax=function(e,s,i){return"object"===(void 0===e?"undefined":o(e))?(i||(i=s),s=e):"object"===(void 0===s?"undefined":o(s))?s.action=e:s={action:e},i=i||"GET",(s=s||{}).action&&(s.wp2sv_action=s.action,s.wp2sv_nonce=t._nonce),s.action="wp2sv",n.ajax({type:i,dataType:"json",url:t.ajaxurl,data:s})},t.print=function(t){var o=n(t).html(),e=window.open("","backup codes","height=400,width=600");e.document.write("<html><head><title></title>"),e.document.write('<link rel="stylesheet" href="'+parent.wp2sv.url.assets+'css/print.css" type="text/css" />'),e.document.write('</head><body ><div class="page"><div class="subpage">'),e.document.write(o),e.document.write("</div></div></body></html>"),e.document.close(),e.focus(),e.onload=function(){}},t.toast=function(t,n){return this.show(t,n)},Object.assign(t.toast,{show:function(t,n){void 0===n&&(n="info"),this.el().find(".wp2sv-toast-message").html(t);var o=n?"wp2sv-toast wp2sv-toast-"+n:"wp2sv-toast";return this.el().find(">div").attr("class",o),this.el().show(),this},info:function(t){return this.show(t,"info")},error:function(t){return this.show(t,"error")},success:function(t){return this.show(t,"success")},warning:function(t){return this.show(t,"warning")},hide:function(){return this.el().hide(),this},el:function(){if(this.$el||(this.$el=n("#wp2sv-toast")),!this.$el.length){this.$el=n('<div id="wp2sv-toast" style="display: none">\n        <div class="wp2sv-toast">\n            <div class="wp2sv-toast-message">\n\n            </div>\n        </div>\n    </div>').appendTo(".wp2sv")}return this.$el},hideAfter:function(t){return setTimeout(this.hide,t),this}}),t.toast.hide=t.toast.hide.bind(t.toast),t.init()}(window.wp2sv,jQuery)}});