(()=>{var e,t={7487:(e,t,s)=>{"use strict";s(8209);var r=s(6087),n=s(7723),o=s(1727),i=s(216),a=s(6427),c=s(790);class p extends r.Component{render(){const{data:e,onChange:t}=this.props,{verification:s,underConstruction:r,urls:i}=e,{google:p,bing:l}=s,{facebook:u,linkedin:d,twitter:h,youtube:w,instagram:g,pinterest:x}=i;return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(o.X3,{title:(0,n.__)("Webmaster Tools","newspack"),description:(0,n.__)("Add verification meta tags to your site","newspack")}),(0,c.jsxs)(o.xA,{children:[(0,c.jsx)(o.xt,{label:"Google",onChange:e=>t({verification:{...s,google:e}}),value:p,help:(0,c.jsxs)(c.Fragment,{children:[(0,n.__)("Get your verification code in","newspack")+" ",(0,c.jsx)(a.ExternalLink,{href:"https://www.google.com/webmasters/verification/verification?tid=alternate",children:(0,n.__)("Google Search Console","newspack")})]})}),(0,c.jsx)(o.xt,{label:"Bing",onChange:e=>t({verification:{...s,bing:e}}),value:l,help:(0,c.jsxs)(c.Fragment,{children:[(0,n.__)("Get your verification code in","newspack")+" ",(0,c.jsx)(a.ExternalLink,{href:"https://www.bing.com/toolbox/webmaster/#/Dashboard/",children:(0,n.__)("Bing Webmaster Tool","newspack")})]})})]}),(0,c.jsx)(o.X3,{title:(0,n.__)("Social Accounts","newspack"),description:(0,n.__)("Let search engines know which social profiles are associated to your site","newspack")}),(0,c.jsxs)(o.xA,{columns:1,gutter:64,children:[(0,c.jsxs)(o.xA,{columns:3,rowGap:16,children:[(0,c.jsx)(o.xt,{label:(0,n.__)("Facebook Page","newspack"),onChange:e=>t({urls:{...i,facebook:e}}),value:u,placeholder:(0,n.__)("https://facebook.com/page","newspack")}),(0,c.jsx)(o.xt,{label:(0,n.__)("Twitter","newspack"),onChange:e=>t({urls:{...i,twitter:e}}),value:h,placeholder:(0,n.__)("username","newspack")}),(0,c.jsx)(o.xt,{label:"Instagram",onChange:e=>t({urls:{...i,instagram:e}}),value:g,placeholder:(0,n.__)("https://instagram.com/user","newspack")}),(0,c.jsx)(o.xt,{label:"LinkedIn",onChange:e=>t({urls:{...i,linkedin:e}}),value:d,placeholder:(0,n.__)("https://linkedin.com/user","newspack")}),(0,c.jsx)(o.xt,{label:"YouTube",onChange:e=>t({urls:{...i,youtube:e}}),value:w,placeholder:(0,n.__)("https://youtube.com/c/channel","newspack")}),(0,c.jsx)(o.xt,{label:"Pinterest",onChange:e=>t({urls:{...i,pinterest:e}}),value:x,placeholder:(0,n.__)("https://pinterest.com/user","newspack")})]}),(0,c.jsx)(o.Lz,{isMedium:!0,title:(0,n.__)("Under construction","newspack"),description:(0,n.__)("Discourage search engines from indexing this site.","newspack"),toggleChecked:r,toggleOnChange:e=>t({underConstruction:e})})]})]})}}p.defaultProps={data:{}};const l=(0,o.c_)(p);var u=s(4058),d=s.n(u),h=s(4124),w=s.n(h);const{HashRouter:g,Redirect:x,Route:b,Switch:f}=i.A,m=(e,t)=>{if(!(e=>null!==e&&"object"==typeof e&&Object.getPrototypeOf(e).isPrototypeOf(Object))(e))return e;const s={};for(const r in e)e.hasOwnProperty(r)&&(s[t(r)]=m(e[r],t));return s};class k extends r.Component{state={underConstruction:!1,urls:{facebook:"",twitter:"",instagram:"",youtube:"",linkedin:"",pinterest:""},verification:{bing:"",google:""}};onWizardReady=()=>this.fetch();fetch(){const{setError:e,wizardApiFetch:t}=this.props;return t({path:"/newspack/v1/wizard/newspack-seo-wizard/settings"}).then((e=>this.setState(this.sanitizeResponse(e)))).catch((t=>e(t)))}update(){const{setError:e,wizardApiFetch:t}=this.props;return t({path:"/newspack/v1/wizard/newspack-seo-wizard/settings",method:"POST",data:m(this.state,(e=>w()(e))),quiet:!0}).then((e=>this.setState(this.sanitizeResponse(e)))).catch((t=>e(t)))}sanitizeResponse=e=>m(e,(e=>d()(e)));render(){const{pluginRequirements:e}=this.props,t=(0,n.__)("SEO","newspack"),s=(0,n.__)("Configure basic SEO settings","newspack"),o=(0,n.__)("Save Settings","newspack"),i=(0,n.__)("Advanced Settings","newspack"),a={data:this.state,headerText:t,subHeaderText:s};return(0,c.jsx)(r.Fragment,{children:(0,c.jsx)(g,{hashType:"slash",children:(0,c.jsxs)(f,{children:[e,(0,c.jsx)(b,{exact:!0,path:"/",render:()=>(0,c.jsx)(l,{...a,buttonAction:()=>this.update(),buttonText:o,onChange:e=>this.setState(e),secondaryButtonText:i})}),(0,c.jsx)(x,{to:"/"})]})})})}}(0,r.render)((0,r.createElement)((0,o.Aq)(k,["wordpress-seo","jetpack"])),document.getElementById("newspack-seo-wizard"))},4058:(e,t,s)=>{var r=s(4792),n=s(5539)((function(e,t,s){return t=t.toLowerCase(),e+(s?r(t):t)}));e.exports=n},4792:(e,t,s)=>{var r=s(3222),n=s(5808);e.exports=function(e){return n(r(e).toLowerCase())}},4124:(e,t,s)=>{var r=s(5539)((function(e,t,s){return e+(s?"_":"")+t.toLowerCase()}));e.exports=r},1609:e=>{"use strict";e.exports=window.React},790:e=>{"use strict";e.exports=window.ReactJSXRuntime},6154:e=>{"use strict";e.exports=window.moment},1455:e=>{"use strict";e.exports=window.wp.apiFetch},6427:e=>{"use strict";e.exports=window.wp.components},7143:e=>{"use strict";e.exports=window.wp.data},6087:e=>{"use strict";e.exports=window.wp.element},2619:e=>{"use strict";e.exports=window.wp.hooks},8537:e=>{"use strict";e.exports=window.wp.htmlEntities},7723:e=>{"use strict";e.exports=window.wp.i18n},8558:e=>{"use strict";e.exports=window.wp.keycodes},5573:e=>{"use strict";e.exports=window.wp.primitives},3832:e=>{"use strict";e.exports=window.wp.url}},s={};function r(e){var n=s[e];if(void 0!==n)return n.exports;var o=s[e]={id:e,loaded:!1,exports:{}};return t[e].call(o.exports,o,o.exports,r),o.loaded=!0,o.exports}r.m=t,e=[],r.O=(t,s,n,o)=>{if(!s){var i=1/0;for(l=0;l<e.length;l++){for(var[s,n,o]=e[l],a=!0,c=0;c<s.length;c++)(!1&o||i>=o)&&Object.keys(r.O).every((e=>r.O[e](s[c])))?s.splice(c--,1):(a=!1,o<i&&(i=o));if(a){e.splice(l--,1);var p=n();void 0!==p&&(t=p)}}return t}o=o||0;for(var l=e.length;l>0&&e[l-1][2]>o;l--)e[l]=e[l-1];e[l]=[s,n,o]},r.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return r.d(t,{a:t}),t},r.d=(e,t)=>{for(var s in t)r.o(t,s)&&!r.o(e,s)&&Object.defineProperty(e,s,{enumerable:!0,get:t[s]})},r.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.nmd=e=>(e.paths=[],e.children||(e.children=[]),e),r.j=410,(()=>{var e;r.g.importScripts&&(e=r.g.location+"");var t=r.g.document;if(!e&&t&&(t.currentScript&&(e=t.currentScript.src),!e)){var s=t.getElementsByTagName("script");if(s.length)for(var n=s.length-1;n>-1&&(!e||!/^http(s?):/.test(e));)e=s[n--].src}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),r.p=e})(),(()=>{var e={410:0};r.O.j=t=>0===e[t];var t=(t,s)=>{var n,o,[i,a,c]=s,p=0;if(i.some((t=>0!==e[t]))){for(n in a)r.o(a,n)&&(r.m[n]=a[n]);if(c)var l=c(r)}for(t&&t(s);p<i.length;p++)o=i[p],r.o(e,o)&&e[o]&&e[o][0](),e[o]=0;return r.O(l)},s=globalThis.webpackChunknewspack=globalThis.webpackChunknewspack||[];s.forEach(t.bind(null,0)),s.push=t.bind(null,s.push.bind(s))})();var n=r.O(void 0,[223],(()=>r(7487)));n=r.O(n)})();