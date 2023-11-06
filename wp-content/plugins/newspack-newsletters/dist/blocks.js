(()=>{var e={4184:(e,t)=>{var n;
/*!
	Copyright (c) 2018 Jed Watson.
	Licensed under the MIT License (MIT), see
	http://jedwatson.github.io/classnames
*/!function(){"use strict";var a={}.hasOwnProperty;function l(){for(var e=[],t=0;t<arguments.length;t++){var n=arguments[t];if(n){var r=typeof n;if("string"===r||"number"===r)e.push(n);else if(Array.isArray(n)){if(n.length){var s=l.apply(null,n);s&&e.push(s)}}else if("object"===r){if(n.toString!==Object.prototype.toString&&!n.toString.toString().includes("[native code]")){e.push(n.toString());continue}for(var o in n)a.call(n,o)&&n[o]&&e.push(o)}}}return e.join(" ")}e.exports?(l.default=l,e.exports=l):void 0===(n=function(){return l}.apply(t,[]))||(e.exports=n)}()}},t={};function n(a){var l=t[a];if(void 0!==l)return l.exports;var r=t[a]={exports:{}};return e[a](r,r.exports,n),r.exports}n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var a in t)n.o(t,a)&&!n.o(e,a)&&Object.defineProperty(e,a,{enumerable:!0,get:t[a]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),n.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var a={};(()=>{"use strict";n.r(a),n.d(a,{blocks:()=>P});var e={};function t(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function l(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function r(e){for(var n=1;n<arguments.length;n++){var a=null!=arguments[n]?arguments[n]:{};n%2?l(Object(a),!0).forEach((function(n){t(e,n,a[n])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):l(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}n.r(e),n.d(e,{metadata:()=>S,name:()=>_,settings:()=>x});const s=window.wp.blocks,o=window.React,c=window.wp.primitives,i=(0,o.createElement)(c.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,o.createElement)(c.Path,{fillRule:"evenodd",d:"M6 5.5h12a.5.5 0 01.5.5v7H14a2 2 0 11-4 0H5.5V6a.5.5 0 01.5-.5zm-.5 9V18a.5.5 0 00.5.5h12a.5.5 0 00.5-.5v-3.5h-3.337a3.5 3.5 0 01-6.326 0H5.5zM4 13V6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2v-5z",clipRule:"evenodd"}));function u(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,a=new Array(t);n<t;n++)a[n]=e[n];return a}function p(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null!=n){var a,l,r=[],_n=!0,s=!1;try{for(n=n.call(e);!(_n=(a=n.next()).done)&&(r.push(a.value),!t||r.length!==t);_n=!0);}catch(o){s=!0,l=o}finally{try{_n||null==n.return||n.return()}finally{if(s)throw l}}return r}}(e,t)||function(e,t){if(e){if("string"==typeof e)return u(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?u(e,t):void 0}}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}const m=window.wp.element;var d=n(4184),w=n.n(d);const f=window.lodash,b=window.wp.apiFetch;var h=n.n(b);const g=window.wp.i18n,y=window.wp.components,k=window.wp.blockEditor,v=window.wp.data;var __=g.__,E=function(e){return"newspack-newsletters-list-checkbox-"+e},C=newspack_newsletters_blocks.settings_url,N=[{label:__("Initial","newspack-newsletters"),value:"initial"},{label:__("Success","newspack-newsletters"),value:"success"}];function O(e){var t="";if(e){var n=(0,v.select)("core/editor").getEditorSettings(),a=(0,k.getColorObjectByColorValue)(n.colors,e);a&&(t=a.slug)}return t}const S=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"name":"newspack-newsletters/subscribe","title":"Newsletter Subscription Form","category":"newspack","description":"Subscribe an email to a newsletter list.","textdomain":"newspack-newsletters","attributes":{"displayInputLabels":{"type":"boolean","default":false},"placeholder":{"type":"string","default":"Email Address"},"emailLabel":{"type":"string","default":"Email Address"},"displayNameField":{"type":"boolean","default":false},"displayLastNameField":{"type":"boolean","default":false},"namePlaceholder":{"type":"string","default":"Name"},"nameLabel":{"type":"string","default":"Name"},"lastNamePlaceholder":{"type":"string","default":"Last Name"},"lastNameLabel":{"type":"string","default":"Last Name"},"label":{"type":"string","default":"Sign up"},"displayDescription":{"type":"boolean","default":true},"lists":{"type":"array","default":[],"items":{"type":"string"}},"listsCheckboxes":{"type":"object","default":{}},"mailchimpDoubleOptIn":{"type":"boolean","default":false},"successMessage":{"type":"string","default":"Thank you for signing up!"},"className":{"type":"string"},"textColor":{"type":"string","default":"#ffffff"},"textColorName":{"type":"string","default":""},"backgroundColor":{"type":"string","default":"#dd3333"},"backgroundColorName":{"type":"string","default":""}},"supports":{"html":false,"align":true}}');for(var _=S.name,x={icon:{src:i,foreground:"#36f"},edit:function(e){var t=e.setAttributes,n=e.attributes,a=n.displayInputLabels,l=n.placeholder,s=n.emailLabel,o=n.displayNameField,c=n.displayLastNameField,i=n.namePlaceholder,u=n.nameLabel,d=n.lastNamePlaceholder,b=n.lastNameLabel,v=n.label,S=n.successMessage,_=n.lists,x=n.listsCheckboxes,P=n.displayDescription,j=n.mailchimpDoubleOptIn,T=n.textColor,L=n.backgroundColor,A=(0,k.useBlockProps)(),D=p((0,m.useState)(N[0].value),2),M=D[0],B=D[1],F=p((0,m.useState)(!1),2),I=F[0],R=F[1],H=p((0,m.useState)({}),2),V=H[0],z=H[1];(0,m.useEffect)((function(){R(!0),h()({path:"/newspack-newsletters/v1/lists_config"}).then(z).finally((function(){return R(!1)}))}),[]),(0,m.useEffect)((function(){var e=Object.keys(V);!e.length||_.length&&(0,f.intersection)(_,e).length||t({lists:[Object.keys(V)[0]]})}),[V]);var Y=function(e){return!x.hasOwnProperty(e)||x[e]},$=function(e){return function(){var n=r({},x);n[e]=!Y(e),t({listsCheckboxes:n})}};return(0,m.createElement)(m.Fragment,null,(0,m.createElement)(k.InspectorControls,null,(0,m.createElement)(y.PanelBody,{title:__("Form settings","newspack-newsletters")},(0,m.createElement)(y.ToggleControl,{label:__("Display input labels","newspack-newsletters"),checked:a,onChange:function(e){return t({displayInputLabels:e})}}),(0,m.createElement)(y.TextControl,{label:__("Email placeholder","newspack-newsletters"),value:l,onChange:function(e){return t({placeholder:e})}}),(0,m.createElement)(y.ToggleControl,{label:__("Display name field","newspack-newsletters"),checked:o,onChange:function(e){return t({displayNameField:e})}}),o&&(0,m.createElement)(m.Fragment,null,(0,m.createElement)(y.TextControl,{label:__("Name placeholder","newspack-newsletters"),value:i,onChange:function(e){return t({namePlaceholder:e})}}),(0,m.createElement)(y.ToggleControl,{label:__('Display "Last Name" field',"newspack-newsletters"),checked:c,onChange:function(e){return t({displayLastNameField:e})}}),c&&(0,m.createElement)(y.TextControl,{label:__('"Last Name" placeholder',"newspack-newsletters"),value:d,onChange:function(e){return t({lastNamePlaceholder:e})}})),_.length>1&&(0,m.createElement)(y.ToggleControl,{label:__("Display list description","newspack-newsletters"),checked:P,onChange:function(){return t({displayDescription:!P})}})),(0,m.createElement)(y.PanelBody,{title:__("Styles","newspack-newsletters"),className:"styles-container"},(0,m.createElement)("p",null,__("Make sure to pick a color that will contrast against the rest of your site's color scheme, to help this block stand out!","newspack-newsletters")),(0,m.createElement)(k.PanelColorSettings,{initialOpen:!0,colorSettings:[{value:T,onChange:function(e){t({textColorName:O(e)}),t({textColor:e})},label:__("Text color","newspack-newsletters")},{value:L,onChange:function(e){t({backgroundColorName:O(e)}),t({backgroundColor:e})},label:__("Background color","newspack-newsletters")}]})),(0,m.createElement)(y.PanelBody,{title:__("Subscription Lists","newspack-newsletters")},I&&(0,m.createElement)(y.Spinner,null),!I&&!Object.keys(V).length&&(0,m.createElement)("div",{style:{marginBottom:"1.5rem"}},(0,m.createElement)(y.Notice,{isDismissible:!1,status:"error"},__("You must enable lists for subscription.","newspack-newsletters"))),_.length<1&&(0,m.createElement)("div",{style:{marginBottom:"1.5rem"}},(0,m.createElement)(y.Notice,{isDismissible:!1,status:"error"},__("You must select at least one list.","newspack-newsletters"))),Object.keys(V).map((function(e){return(0,m.createElement)(y.ToggleControl,{key:e,label:V[e].title,checked:_.includes(e),onChange:function(){_.includes(e)?t({lists:_.filter((function(t){return t!==e}))}):t({lists:_.concat(e)})}})})),(0,m.createElement)("p",null,(0,m.createElement)("a",{href:C},__("Manage your subscription lists","newspack-newsletters")))),"mailchimp"===newspack_newsletters_blocks.provider&&(0,m.createElement)(y.PanelBody,{title:__("Mailchimp Settings","newspack-newsletters")},(0,m.createElement)(y.CheckboxControl,{label:__("Enable double opt-in","newspack-newsletters"),help:__('Whether the new contact will have its status as "pending" until email confirmation',"newspack-newsletters"),checked:j,onChange:function(e){return t({mailchimpDoubleOptIn:e})}})),newspack_newsletters_blocks.supports_recaptcha&&(0,m.createElement)(y.PanelBody,{title:__("Spam protection","newspack")},(0,m.createElement)("p",null,(0,g.sprintf)(__("reCAPTCHA v3 is currently %s.","newspack"),newspack_newsletters_blocks.has_recaptcha?__("enabled","newspack"):__("disabled","newspack"))),!newspack_newsletters_blocks.has_recaptcha&&(0,m.createElement)("p",null,__("It's highly recommended that you enable reCAPTCHA v3 protection to prevent spambots from using this form!","newspack")),(0,m.createElement)("p",null,(0,m.createElement)("a",{href:newspack_newsletters_blocks.recaptcha_url},__("Configure your reCAPTCHA settings.","newspack"))))),(0,m.createElement)("div",A,(0,m.createElement)("div",{className:"newspack-newsletters-subscribe__state-bar"},(0,m.createElement)("span",null,__("Edited State","newspack-newsletters")),(0,m.createElement)("div",null,N.map((function(e){return(0,m.createElement)(y.Button,{key:e.value,"data-is-active":M===e.value,onClick:function(){return B(e.value)}},e.label)})))),I?(0,m.createElement)(y.Spinner,null):(0,m.createElement)("div",{className:w()({"newspack-newsletters-subscribe":!0,"multiple-lists":_.length>1}),"data-status":"200"},"initial"===M&&(0,m.createElement)("form",{onSubmit:function(e){return e.preventDefault()}},_.length>1&&(0,m.createElement)("div",{className:"newspack-newsletters-lists"},(0,m.createElement)("ul",null,_.map((function(e){var t,n;return(0,m.createElement)("li",{key:e},(0,m.createElement)("span",{className:"list-checkbox"},(0,m.createElement)("input",{id:E(e),type:"checkbox",checked:Y(e),onChange:$(e)})),(0,m.createElement)("span",{className:"list-details"},(0,m.createElement)("label",{htmlFor:E(e)},(0,m.createElement)("span",{className:"list-title"},null===(t=V[e])||void 0===t?void 0:t.title),P&&(0,m.createElement)("span",{className:"list-description"},null===(n=V[e])||void 0===n?void 0:n.description))))})))),o&&(0,m.createElement)("div",{className:"newspack-newsletters-name-input"},(0,m.createElement)("div",{className:"newspack-newsletters-name-input-item"},(0,m.createElement)("label",null,a&&(0,m.createElement)(k.RichText,{onChange:function(e){return t({nameLabel:e})},placeholder:__("Name","newspack"),value:u,tagName:"span"})),(0,m.createElement)("input",{type:"text",placeholder:i})),c&&(0,m.createElement)("div",{className:"newspack-newsletters-name-input-item"},(0,m.createElement)("label",null,a&&(0,m.createElement)(k.RichText,{onChange:function(e){return t({lastNameLabel:e})},placeholder:__("Last Name","newspack"),value:b,tagName:"span"})),(0,m.createElement)("input",{type:"text",placeholder:d}))),(0,m.createElement)("div",{className:"newspack-newsletters-email-input"},(0,m.createElement)("label",null,a&&(0,m.createElement)(k.RichText,{onChange:function(e){return t({emailLabel:e})},placeholder:__("Email Address","newspack"),value:s,tagName:"span"})),(0,m.createElement)("input",{type:"email",placeholder:l}),(0,m.createElement)("div",{className:"submit-button",style:{backgroundColor:L,color:T}},(0,m.createElement)(k.RichText,{onChange:function(e){return t({label:e})},placeholder:__("Sign up","newspack"),value:v,tagName:"span"})))),"success"===M&&(0,m.createElement)("div",{className:"newspack-newsletters-subscribe__response"},(0,m.createElement)("div",{className:"newspack-newsletters-subscribe__icon"}),(0,m.createElement)("div",{className:"newspack-newsletters-subscribe__message"},(0,m.createElement)(k.RichText,{onChange:function(e){return t({successMessage:e})},placeholder:__("Success message","newspack-newsletters"),value:S,tagName:"p",className:"message status-200",allowedFormats:["core/bold","core/italic"]}))))))}},P=[e],j=function(e){if(e){var t=e.metadata,n=e.settings,a=e.name;(0,s.registerBlockType)(r({name:a},t),n)}},T=0,L=P;T<L.length;T++){j(L[T])}})();var l=window;for(var r in a)l[r]=a[r];a.__esModule&&Object.defineProperty(l,"__esModule",{value:!0})})();