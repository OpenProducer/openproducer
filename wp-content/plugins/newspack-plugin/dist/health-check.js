(()=>{"use strict";var e,t={7783:(e,t,n)=>{n.r(t);var a=n(4942),s=(n(5674),n(9307)),r=n(5736),i=n(1383),c=n(9674);const o=r.__;class p extends s.Component{render(){const{unsupportedPlugins:e,missingPlugins:t,deactivateAllPlugins:n}=this.props;return(0,s.createElement)(i.rj,{columns:1,gutter:64},t.length?(0,s.createElement)(i.rj,{columns:1,gutter:16},(0,s.createElement)(i.qX,{noticeText:o("These plugins shoud be active:","newspack"),isWarning:!0}),(0,s.createElement)(i.xf,{plugins:t})):null,e.length?(0,s.createElement)(i.rj,{columns:1,gutter:16},(0,s.createElement)(i.qX,{noticeText:o("Newspack does not support these plugins:","newspack"),isError:!0}),e.map((e=>(0,s.createElement)(i.fM,{title:e.Name,key:e.Slug,description:e.Description,className:"newspack-card__is-unsupported"}))),(0,s.createElement)("div",{className:"newspack-buttons-card"},(0,s.createElement)(i.zx,{isPrimary:!0,onClick:n},o("Deactivate All","newspack")))):(0,s.createElement)(i.qX,{noticeText:o("No unsupported plugins found.","newspack"),isSuccess:!0}))}}const l=(0,i.a4)(p),u=r.__;class d extends s.Component{render(){const{configurationStatus:e,hasData:t}=this.props,{jetpack:n,sitekit:a}=e||{};return t&&(0,s.createElement)(s.Fragment,null,(0,s.createElement)(i.fM,{className:n?"newspack-card__is-supported":"newspack-card__is-unsupported",title:u("Jetpack","newspack"),description:u(n?"Jetpack is connected.":"Jetpack is not connected. ","newspack"),actionText:!n&&u("Connect","newspack"),handoff:"jetpack"}),(0,s.createElement)(i.fM,{className:a?"newspack-card__is-supported":"newspack-card__is-unsupported",title:u("Google Site Kit","newspack"),description:u(a?"Site Kit is connected.":"Site Kit is not connected. ","newspack"),actionText:!a&&u("Connect","newspack"),handoff:"google-site-kit"}))}}const h=(0,i.a4)(d),w=r.__,{HashRouter:g,Redirect:m,Route:k,Switch:f}=c.Z;class v extends s.Component{constructor(e){super(e),(0,a.Z)(this,"onWizardReady",(()=>{this.fetchHealthData()})),(0,a.Z)(this,"fetchHealthData",(()=>{const{wizardApiFetch:e,setError:t}=this.props;e({path:"/newspack/v1/wizard/newspack-health-check-wizard/"}).then((e=>this.setState({healthCheckData:e,hasData:!0}))).catch((e=>{t(e)}))})),(0,a.Z)(this,"deactivateAllPlugins",(()=>{const{wizardApiFetch:e,setError:t}=this.props;e({path:"/newspack/v1/wizard/newspack-health-check-wizard/unsupported_plugins",method:"delete"}).then((e=>this.setState({healthCheckData:e}))).catch((e=>{t(e)}))})),this.state={hasData:!1,healthCheckData:{unsupported_plugins:{},missing_plugins:{}}}}render(){const{hasData:e,healthCheckData:t}=this.state,{unsupported_plugins:n,missing_plugins:a,configuration_status:r}=t,i=[{label:w("Plugins","newspack"),path:"/",exact:!0},{label:w("Configuration"),path:"/configuration"}];return(0,s.createElement)(s.Fragment,null,(0,s.createElement)(g,{hashType:"slash"},(0,s.createElement)(f,null,(0,s.createElement)(k,{path:"/",exact:!0,render:()=>(0,s.createElement)(l,{headerText:w("Health Check","newspack"),subHeaderText:w("Verify and correct site health issues","newspack"),deactivateAllPlugins:this.deactivateAllPlugins,tabbedNavigation:i,missingPlugins:Object.keys(a),unsupportedPlugins:Object.keys(n).map((e=>({...n[e],Slug:e})))})}),(0,s.createElement)(k,{path:"/configuration",exact:!0,render:()=>(0,s.createElement)(h,{hasData:e,headerText:w("Health Check","newspack"),subHeaderText:w("Verify and correct site health issues","newspack"),tabbedNavigation:i,configurationStatus:r,missingPlugins:Object.keys(a)})}),(0,s.createElement)(m,{to:"/"}))))}}(0,s.render)((0,s.createElement)((0,i.uF)(v)),document.getElementById("newspack-health-check-wizard"))},9196:e=>{e.exports=window.React},2819:e=>{e.exports=window.lodash},6292:e=>{e.exports=window.moment},6989:e=>{e.exports=window.wp.apiFetch},5609:e=>{e.exports=window.wp.components},9818:e=>{e.exports=window.wp.data},9307:e=>{e.exports=window.wp.element},2694:e=>{e.exports=window.wp.hooks},2629:e=>{e.exports=window.wp.htmlEntities},5736:e=>{e.exports=window.wp.i18n},9630:e=>{e.exports=window.wp.keycodes},444:e=>{e.exports=window.wp.primitives},6483:e=>{e.exports=window.wp.url}},n={};function a(e){var s=n[e];if(void 0!==s)return s.exports;var r=n[e]={exports:{}};return t[e].call(r.exports,r,r.exports,a),r.exports}a.m=t,e=[],a.O=(t,n,s,r)=>{if(!n){var i=1/0;for(l=0;l<e.length;l++){n=e[l][0],s=e[l][1],r=e[l][2];for(var c=!0,o=0;o<n.length;o++)(!1&r||i>=r)&&Object.keys(a.O).every((e=>a.O[e](n[o])))?n.splice(o--,1):(c=!1,r<i&&(i=r));if(c){e.splice(l--,1);var p=s();void 0!==p&&(t=p)}}return t}r=r||0;for(var l=e.length;l>0&&e[l-1][2]>r;l--)e[l]=e[l-1];e[l]=[n,s,r]},a.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return a.d(t,{a:t}),t},a.d=(e,t)=>{for(var n in t)a.o(t,n)&&!a.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},a.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),a.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),a.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.j=619,(()=>{var e;a.g.importScripts&&(e=a.g.location+"");var t=a.g.document;if(!e&&t&&(t.currentScript&&(e=t.currentScript.src),!e)){var n=t.getElementsByTagName("script");n.length&&(e=n[n.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),a.p=e})(),(()=>{var e={619:0};a.O.j=t=>0===e[t];var t=(t,n)=>{var s,r,i=n[0],c=n[1],o=n[2],p=0;if(i.some((t=>0!==e[t]))){for(s in c)a.o(c,s)&&(a.m[s]=c[s]);if(o)var l=o(a)}for(t&&t(n);p<i.length;p++)r=i[p],a.o(e,r)&&e[r]&&e[r][0](),e[i[p]]=0;return a.O(l)},n=self.webpackChunkwebpack=self.webpackChunkwebpack||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var s=a.O(void 0,[351],(()=>a(7783)));s=a.O(s);var r=window;for(var i in s)r[i]=s[i];s.__esModule&&Object.defineProperty(r,"__esModule",{value:!0})})();