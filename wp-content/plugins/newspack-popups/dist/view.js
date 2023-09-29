(()=>{"use strict";var e={r:e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},t={};function n(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}function r(e,t){if(e){if("string"==typeof e)return n(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);return"Object"===r&&e.constructor&&(r=e.constructor.name),"Map"===r||"Set"===r?Array.from(e):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?n(e,t):void 0}}function o(e){return function(e){if(Array.isArray(e))return n(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||r(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function i(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function a(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function u(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?a(Object(n),!0).forEach((function(t){i(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):a(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}e.r(t);var c=function(e,t){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};return!(!newspackPopupsData||!newspackPopupsData[t])&&u(u(u({},newspackPopupsData[t]),n),{},{action:e})},s=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"np_prompt_interaction";"function"==typeof gtag&&e&&gtag("event",t,e)},p=function(e){var t=e.split("_");return parseInt(t[t.length-1])},l=function(e){var t=e.currentTarget.closest(".newspack-lightbox");t&&t.contains(e.currentTarget)&&(t.style.display="none"),e.preventDefault()},d=function(e,t){newspack_popups_view.debug&&(window.newspack_popups_debug=window.newspack_popups_debug||{},window.newspack_popups_debug[e]=t)};function f(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=n){var r,o,i=[],_n=!0,a=!1;try{for(n=n.call(e);!(_n=(r=n.next()).done)&&(i.push(r.value),!t||i.length!==t);_n=!0);}catch(u){a=!0,o=u}finally{try{_n||null==n.return||n.return()}finally{if(a)throw o}}return i}}(e,t)||r(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}window.newspackPopupsCriteria=window.newspackPopupsCriteria||{criteria:{}},window.newspackPopupsCriteria.criteria=window.newspackPopupsCriteria.criteria||{};function v(e){return e?window.newspackPopupsCriteria.criteria[e]:window.newspackPopupsCriteria.criteria}var w=864e5,g={day:w,week:7*w,month:30*w},y=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null;e||(e=window.location.search);var t=new URLSearchParams(e);if(t.get("view_as")){var n=t.get("view_as").split(";").reduce((function(e,t){var n=t.split(":");return 1===n.length?e[n[0]]=!0:e[n[0]]=n[1],e}),{});return n}return null},m=function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null;e||(e=window.location.search);var t=new URLSearchParams(e);return t.get("pid")?parseInt(t.get("pid")):null},b=function(e){var t,n=function(e,t){var n="undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!n){if(Array.isArray(e)||(n=r(e))||t&&e&&"number"==typeof e.length){n&&(e=n);var o=0,i=function(){};return{s:i,n:function(){return o>=e.length?{done:!0}:{done:!1,value:e[o++]}},e:function(e){throw e},f:i}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var a,u=!0,c=!1;return{s:function(){n=n.call(e)},n:function(){var e=n.next();return u=e.done,e},e:function(e){c=!0,a=e},f:function(){try{u||null==n.return||n.return()}finally{if(c)throw a}}}}(e);try{for(n.s();!(t=n.n()).done;){var o=t.value,i=v(o.criteria_id);if(i&&!i.matches(o))return!1}}catch(a){n.e(a)}finally{n.f()}return!0},h=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:null,n=y(t);if(null!=n&&n.segment)return n.segment;var r=[];for(var o in e)b(e[o].criteria)&&r.push({id:o,priority:e[o].priority});return r.length?(r.sort((function(e,t){return e.priority-t.priority})),r[0].id):null},_=function(e,t,n){var r=arguments.length>3&&void 0!==arguments[3]?arguments[3]:null,o=e.getAttribute("id"),i=!0,a=[],u={element:e};if(!0===r||!1===r)u.override=!0,r||(i=!1,a.push("Prompt suppressed by override."));else if(n){var c=e.getAttribute("data-frequency").split(","),s=f(c,4),l=s[0],v=s[1],w=s[2],y=s[3],m=n.store.get("pageviews");if(m[y]){var b=m[y].count||0;if(b<=parseInt(l)&&(a.push("Prompt displayed starting at pageview ".concat(parseInt(l)+1,". Reader has only ").concat(b," pageviews.")),i=!1),0<v){var h=Math.max(0,b-(parseInt(l)+1));0<h%(parseInt(v)+1)&&(a.push("Prompt displayed once every ".concat(parseInt(v)+1," pageviews.")),i=!1)}var _=p(o),k=(n.getActivities("prompt_seen")||[]).filter((function(e){var t;return(null===(t=e.data)||void 0===t?void 0:t.prompt_id)===_&&g[y]>Date.now()-e.timestamp}));0<parseInt(w)&&k.length>=parseInt(w)&&(a.push("Prompt already displayed the max of ".concat(w," times.")),i=!1)}var A=e.getAttribute("data-segments")?e.getAttribute("data-segments").split(","):null;A&&0>A.indexOf(t)&&(a.push("Reader does not match prompt’s assigned segments."),i=!1)}return u.displayed=i,0<a.length&&(u.suppression=a),d(o,u),i},k=function(e){var t=arguments.length>1&&void 0!==arguments[1]&&arguments[1],n=arguments.length>2&&void 0!==arguments[2]&&arguments[2],r=arguments.length>3&&void 0!==arguments[3]?arguments[3]:null;return e===m(r)||(!t||!n)&&null},A=function(e){var t,n=new IntersectionObserver((function(r){r.forEach((function(r){r.isIntersecting?t||(t=setTimeout((function(){e(),n.unobserve(r.target)}),250)):t&&(clearTimeout(t),t=!1)}))}),{threshold:.5});return n};var S,O=function(e,t){A((function(){return t.dispatchActivity("prompt_seen",{prompt_id:p(e.getAttribute("id"))})})).observe(e,{attributes:!0})},P=function(e){var t,n,r=Date.now(),o={day:{count:0,start:r},week:{count:0,start:r},month:{count:0,start:r}},i=e.store.get("pageviews")||{},a=u(u({},o),i);for(var c in document.body.classList.forEach((function(e){0===e.indexOf("page-id-")&&(n=parseInt(e.replace("page-id-","")))})),n&&parseInt(null===(t=newspack_popups_view)||void 0===t?void 0:t.donor_landing_page)===n&&e.store.set("is_donor",!0),a)g[c]<r-a[c].start&&(a[c].count=0,a[c].start=r),a[c].count++;return e.store.set("pageviews",a),a},E=function(e){e.forEach((function(e){(function(e){return new MutationObserver((function(t){t.forEach((function(t){"amp-access-hide"!==t.attributeName||"attributes"!==t.type||t.target.hasAttribute("amp-access-hide")||e()}))}))})((function(){var t=c("loaded",p(e.getAttribute("id")));s(t)})).observe(e,{attributes:!0})}))},I=function(e){"function"==typeof gtag&&(E(e),window.newspackRAS=window.newspackRAS||[],window.newspackRAS.push((function(e){e.on("activity",(function(e){var t=e.detail,n=t.action,r=t.data;if("prompt_seen"===n){var o=r.prompt_id,i=c("seen",o);s(i)}}))})),function(e){e.forEach((function(e){var t=e.querySelector(".newspack-lightbox__close"),n=o(e.querySelectorAll(".newspack-popup__content form"));if(t){var r=function(){var t=c("dismissed",p(e.getAttribute("id")));s(t)};t.addEventListener("click",r),n.forEach((function(e){e.addEventListener("submit",(function(){return t.removeEventListener("click",r)}))}))}}))}(e),function(e){e.forEach((function(e){var t=o(e.querySelectorAll(".newspack-inline-popup a, .newspack-popup__content a")),n=function(t){var n,r,o={};null!==(n=t.currentTarget)&&void 0!==n&&n.href&&"#"!==(null===(r=t.currentTarget)||void 0===r?void 0:r.href)&&(o.action_value=t.currentTarget.getAttribute("href"));var i=c("clicked",p(e.getAttribute("id")),o);s(i)};t.forEach((function(e){return e.addEventListener("click",n)}))}))}(e),function(e){e.forEach((function(e){var t=o(e.querySelectorAll(".newspack-popup__content form, .newspack-inline-popup form")),n=function(){var t=c("form_submission",p(e.getAttribute("id")));s(t)};t.forEach((function(e){return e.addEventListener("submit",n)}))}))}(e))};"undefined"!=typeof window&&(S=function(){var e=o(document.querySelectorAll(".newspack-popup-container"));!function(e){var t=function(){var t,n,r=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,i=(null===(t=newspack_popups_view)||void 0===t?void 0:t.segments)||{},a=h(i);d("matchingSegment",a),r&&P(r),e.forEach((function(e){var t=e.getAttribute("id"),i=e.classList.contains("newspack-lightbox"),u=k(p(t),i,n);o(e.querySelectorAll(".newspack-lightbox__close, button.newspack-lightbox-overlay")).forEach((function(e){e.addEventListener("click",l)}));var c=_(e,a,r,u);if(!n&&i&&c&&(n=!0),c){var s=function(){e.classList.remove("hidden"),r&&O(e,r)};if(i)if(e.getAttribute("data-scroll")){var d=document.getElementById("page-position-marker_".concat(t));d&&A(s).observe(d)}else{var f=e.getAttribute("data-delay")||0;setTimeout(s,f)}else s()}}))};newspack_popups_view.segments?(window.newspackRAS=window.newspackRAS||[],window.newspackRAS.push(t)):t()}(e),I(e)},"undefined"!=typeof document&&("complete"!==document.readyState&&"interactive"!==document.readyState?document.addEventListener("DOMContentLoaded",S):S()));var j=window;for(var L in t)j[L]=t[L];t.__esModule&&Object.defineProperty(j,"__esModule",{value:!0})})();