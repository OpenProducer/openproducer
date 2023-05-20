!function(){"use strict";var e,t={9717:function(e,t,n){n.r(t),n(5674);var a=n(9307),i=n(5736),s=n(1383),r=n(9674);const o=i.__;class c extends a.Component{render(){return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(s.fM,{title:o("Google Analytics"),description:o("Configure and view site analytics"),actionText:o("View"),handoff:"google-site-kit",editLink:newspack_analytics_wizard_data.analyticsConnectionError?void 0:"admin.php?page=googlesitekit-module-analytics"}))}}var l=(0,s.a4)(c),d=n(4942);const u=i.__,m=[{value:"HIT",label:u("Hit","newspack")},{value:"SESSION",label:u("Session","newspack")},{value:"USER",label:u("User","newspack")},{value:"PRODUCT",label:u("Product","newspack")}];class p extends a.Component{constructor(){super(...arguments),(0,d.Z)(this,"state",{error:newspack_analytics_wizard_data.analyticsConnectionError,customDimensions:newspack_analytics_wizard_data.customDimensions,newDimensionName:"",newDimensionScope:m[0].value}),(0,d.Z)(this,"handleAPIError",(e=>{let{message:t}=e;this.setState({error:t})})),(0,d.Z)(this,"handleCustomDimensionCreation",(()=>{const{wizardApiFetch:e}=this.props,{customDimensions:t,newDimensionName:n,newDimensionScope:a}=this.state;e({path:"/newspack/v1/wizard/analytics/custom-dimensions",method:"POST",data:{name:n,scope:a}}).then((e=>{this.setState({customDimensions:[...t,e]})})).catch(this.handleAPIError)})),(0,d.Z)(this,"handleCustomDimensionSetting",(e=>t=>{const{wizardApiFetch:n}=this.props;n({path:`/newspack/v1/wizard/analytics/custom-dimensions/${e}`,method:"POST",data:{role:t}}).then((e=>{this.setState({customDimensions:e})})).catch(this.handleAPIError)}))}render(){const{error:e,customDimensions:t,newDimensionName:n,newDimensionScope:i}=this.state;return(0,a.createElement)("div",{className:"newspack__analytics-configuration"},(0,a.createElement)(s.M$,{title:u("User-defined custom dimensions","newspack"),description:u("Collect and analyze data that Google Analytics doesn't automatically track","newspack")}),e?(0,a.createElement)(s.qX,{noticeText:e,isError:!0,rawHTML:!0}):(0,a.createElement)(a.Fragment,null,(0,a.createElement)("table",null,(0,a.createElement)("thead",null,(0,a.createElement)("tr",null,[u("Name","newspack"),u("ID","newspack"),u("Role","newspack")].map(((e,t)=>(0,a.createElement)("th",{key:t},e))))),(0,a.createElement)("tbody",null,t.map((e=>(0,a.createElement)("tr",{key:e.id},(0,a.createElement)("td",null,e.name),(0,a.createElement)("td",null,(0,a.createElement)("code",null,e.id)),(0,a.createElement)("td",null,(0,a.createElement)(s.Yw,{options:newspack_analytics_wizard_data.customDimensionsOptions,value:e.role||"",onChange:this.handleCustomDimensionSetting(e.id),className:"newspack__analytics-configuration__select"}))))))),(0,a.createElement)(s.M$,{title:u("Create new custom dimension","newspack")}),(0,a.createElement)(s.rj,{columns:1,gutter:16},(0,a.createElement)(s.rj,{rowGap:16},(0,a.createElement)(s.w4,{value:n,onChange:e=>this.setState({newDimensionName:e}),label:u("Name","newspack")}),(0,a.createElement)(s.Yw,{value:i,onChange:e=>this.setState({newDimensionScope:e}),label:u("Scope","newspack"),options:m})),(0,a.createElement)("div",{className:"flex justify-end"},(0,a.createElement)(s.zx,{onClick:this.handleCustomDimensionCreation,disabled:0===n.length,variant:"primary"},u("Save","newspack"))))))}}var w=(0,s.a4)(p),h=n(2819),E=n(5609),v=n(1984),k=n(8184),g=n(4350),f=n(4184),b=n.n(f);const _=i.__,y=[{value:"click",label:_("Click","newspack")},{value:"submit",label:_("Submit","newspack")}],C={event_name:"",event_category:"",event_label:"",on:y[0].value,element:"",amp_element:"",non_interaction:!0,is_active:!0},S="/newspack/v1/wizard/analytics/ntg";class x extends a.Component{constructor(){super(...arguments),(0,d.Z)(this,"state",{error:newspack_analytics_wizard_data.analyticsConnectionError,customEvents:newspack_analytics_wizard_data.customEvents,editedEvent:C,editedEventId:null,ntgEventsStatus:{}}),(0,d.Z)(this,"handleAPIError",(e=>{let{message:t}=e;return this.setState({error:t})})),(0,d.Z)(this,"updateCustomEvents",(e=>{const{wizardApiFetch:t}=this.props;t({path:"/newspack/v1/wizard/analytics/custom-events",method:"POST",data:{events:e}}).then((e=>{let{events:t}=e;return this.setState({customEvents:t,editedEvent:C,editedEventId:null})})).catch(this.handleAPIError)})),(0,d.Z)(this,"handleCustomEventEdit",(()=>{const{customEvents:e,editedEvent:t,editedEventId:n}=this.state;"new"===n?this.updateCustomEvents([...e,t]):this.updateCustomEvents(e.map((e=>e.id===n?t:e)))})),(0,d.Z)(this,"updateEditedEvent",(e=>t=>this.setState((n=>{let{editedEvent:a}=n;return{editedEvent:{...a,[e]:t}}})))),(0,d.Z)(this,"setEditModal",(e=>()=>{const t=null!==e&&(0,h.find)(this.state.customEvents,["id",e]);this.setState({editedEventId:e,...t?{editedEvent:t}:{editedEvent:C}})}))}componentDidMount(){this.props.wizardApiFetch({path:S}).then((e=>this.setState({ntgEventsStatus:e})))}render(){const{error:e,customEvents:t,editedEvent:n,editedEventId:i}=this.state,{isLoading:r}=this.props,o="new"===i;return(0,a.createElement)("div",{className:"newspack__analytics-configuration"},(0,a.createElement)("div",{className:"newspack__analytics-configuration__header"},(0,a.createElement)(s.M$,{title:_("User-defined custom events","newspack"),description:_("Collect and analyze specific user interactions","newspack"),noMargin:!0}),(0,a.createElement)(s.zx,{onClick:this.setEditModal("new"),variant:"primary"},_("Add New Custom Event","newspack"))),(0,a.createElement)(s.qX,{rawHTML:!0,isInfo:!0,noticeText:`${_("This is an advanced feature, read more about it on our","newspack")} <a href="${g.Sp}/analytics">${_("support page","newspack")}</a>.`}),e?(0,a.createElement)(s.qX,{noticeText:e,isError:!0,rawHTML:!0}):(0,a.createElement)(a.Fragment,null,(0,a.createElement)("table",null,(0,a.createElement)("thead",null,(0,a.createElement)("tr",null,[_("Active","newspack"),_("Action","newspack"),_("Category","newspack"),_("Label","newspack"),_("Trigger","newspack"),_("Edit","newspack")].map(((e,t)=>(0,a.createElement)("th",{key:t},e))))),(0,a.createElement)("tbody",null,t.map((e=>(0,a.createElement)("tr",{key:e.id},(0,a.createElement)("td",null,(0,a.createElement)("span",{className:b()("newspack-checkbox-icon",e.is_active&&"newspack-checkbox-icon--checked")},e.is_active&&(0,a.createElement)(v.Z,{icon:k.Z}))),(0,a.createElement)("td",null,e.event_name),(0,a.createElement)("td",null,e.event_category),(0,a.createElement)("td",null,e.event_label),(0,a.createElement)("td",null,(0,a.createElement)("code",null,e.on)),(0,a.createElement)("td",null,(0,a.createElement)(s.zx,{variant:"link",onClick:this.setEditModal(e.id)},_("Edit","newspack")))))))),null!==i&&(0,a.createElement)(s.u_,{title:_(o?"Add New Custom Event":"Editing Custom Event","newspack"),onRequestClose:this.setEditModal(null)},(0,a.createElement)(a.Fragment,null,(0,a.createElement)(s.rj,{gutter:32,rowGap:16},(0,a.createElement)(s.w4,{disabled:r,value:n.event_name,onChange:this.updateEditedEvent("event_name"),label:_("Action","newspack"),required:!0}),(0,a.createElement)(s.w4,{disabled:r,value:n.event_category,onChange:this.updateEditedEvent("event_category"),label:_("Category","newspack"),required:!0}),(0,a.createElement)(s.w4,{disabled:r,value:n.event_label,onChange:this.updateEditedEvent("event_label"),label:_("Label","newspack")}),(0,a.createElement)(s.Yw,{disabled:r,value:n.on,onChange:this.updateEditedEvent("on"),label:_("Trigger","newspack"),options:y,required:!0}),(0,a.createElement)(s.w4,{disabled:r,value:n.element,onChange:this.updateEditedEvent("element"),label:_("Selector","newspack"),className:"code",required:!0}),(0,a.createElement)(s.w4,{disabled:r,value:n.amp_element,onChange:this.updateEditedEvent("amp_element"),label:_("AMP Selector","newspack"),className:"code"})),(0,a.createElement)(E.CheckboxControl,{disabled:r,checked:n.non_interaction,onChange:this.updateEditedEvent("non_interaction"),label:_("Non-interaction event","newspack")}),(0,a.createElement)(E.CheckboxControl,{disabled:r,checked:n.is_active,onChange:this.updateEditedEvent("is_active"),label:_("Active","newspack")}),(0,a.createElement)(s.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},!o&&(0,a.createElement)(s.zx,{isSecondary:!0,disabled:r,onClick:()=>this.updateCustomEvents(this.state.customEvents.filter((e=>{let{id:t}=e;return n.id!==t})))},_("Delete","newspack")),(0,a.createElement)(s.zx,{onClick:this.handleCustomEventEdit,disabled:(c=n,!Boolean(c.event_name&&c.event_category&&c.on&&c.element)||r),isPrimary:!0},_(o?"Save":"Update","newspack")))))),(0,a.createElement)(s.fM,{isMedium:!0,title:_("News Tagging Guide custom events","newspack"),description:()=>(0,a.createElement)(a.Fragment,null,_("Free tool that helps you make the most of Google Analytics by capturing better data.","newspack")," ",(0,a.createElement)(E.ExternalLink,{href:"https://newsinitiative.withgoogle.com/training/datatools/ntg",key:"info-link"},_("More info","newspack"))),toggle:!0,disabled:void 0===this.state.ntgEventsStatus.enabled,toggleChecked:this.state.ntgEventsStatus.enabled,toggleOnChange:()=>this.props.wizardApiFetch({path:S,method:this.state.ntgEventsStatus.enabled?"DELETE":"POST",quiet:!0}).then((e=>this.setState({ntgEventsStatus:e})))}));var c}}var T=(0,s.a4)(x);const z=i.__,{HashRouter:D,Redirect:A,Route:M,Switch:N}=r.Z,O=[{label:z("Plugins","newspack"),path:"/",exact:!0},{label:z("Custom Dimensions","newspack"),path:"/custom-dimensions"},{label:z("Custom Events","newspack"),path:"/custom-events"}];class P extends a.Component{render(){const{pluginRequirements:e,wizardApiFetch:t,isLoading:n}=this.props,i={headerText:z("Analytics","newspack"),subHeaderText:z("Track traffic and activity","newspack"),tabbedNavigation:O,wizardApiFetch:t,isLoading:n};return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(D,{hashType:"slash"},(0,a.createElement)(N,null,e,(0,a.createElement)(M,{path:"/custom-dimensions",exact:!0,render:()=>(0,a.createElement)(w,i)}),(0,a.createElement)(M,{path:"/custom-events",exact:!0,render:()=>(0,a.createElement)(T,i)}),(0,a.createElement)(M,{path:"/",exact:!0,render:()=>(0,a.createElement)(l,i)}),(0,a.createElement)(A,{to:"/"}))))}}(0,a.render)((0,a.createElement)((0,s.uF)(P,["google-site-kit"])),document.getElementById("newspack-analytics-wizard"))},9196:function(e){e.exports=window.React},2819:function(e){e.exports=window.lodash},6292:function(e){e.exports=window.moment},6989:function(e){e.exports=window.wp.apiFetch},5609:function(e){e.exports=window.wp.components},9818:function(e){e.exports=window.wp.data},9307:function(e){e.exports=window.wp.element},2694:function(e){e.exports=window.wp.hooks},2629:function(e){e.exports=window.wp.htmlEntities},5736:function(e){e.exports=window.wp.i18n},9630:function(e){e.exports=window.wp.keycodes},444:function(e){e.exports=window.wp.primitives},6483:function(e){e.exports=window.wp.url}},n={};function a(e){var i=n[e];if(void 0!==i)return i.exports;var s=n[e]={exports:{}};return t[e].call(s.exports,s,s.exports,a),s.exports}a.m=t,e=[],a.O=function(t,n,i,s){if(!n){var r=1/0;for(d=0;d<e.length;d++){n=e[d][0],i=e[d][1],s=e[d][2];for(var o=!0,c=0;c<n.length;c++)(!1&s||r>=s)&&Object.keys(a.O).every((function(e){return a.O[e](n[c])}))?n.splice(c--,1):(o=!1,s<r&&(r=s));if(o){e.splice(d--,1);var l=i();void 0!==l&&(t=l)}}return t}s=s||0;for(var d=e.length;d>0&&e[d-1][2]>s;d--)e[d]=e[d-1];e[d]=[n,i,s]},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,{a:t}),t},a.d=function(e,t){for(var n in t)a.o(t,n)&&!a.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},a.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.j=142,function(){var e;a.g.importScripts&&(e=a.g.location+"");var t=a.g.document;if(!e&&t&&(t.currentScript&&(e=t.currentScript.src),!e)){var n=t.getElementsByTagName("script");n.length&&(e=n[n.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),a.p=e}(),function(){var e={142:0};a.O.j=function(t){return 0===e[t]};var t=function(t,n){var i,s,r=n[0],o=n[1],c=n[2],l=0;if(r.some((function(t){return 0!==e[t]}))){for(i in o)a.o(o,i)&&(a.m[i]=o[i]);if(c)var d=c(a)}for(t&&t(n);l<r.length;l++)s=r[l],a.o(e,s)&&e[s]&&e[s][0](),e[r[l]]=0;return a.O(d)},n=self.webpackChunkwebpack=self.webpackChunkwebpack||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))}();var i=a.O(void 0,[351],(function(){return a(9717)}));i=a.O(i);var s=window;for(var r in i)s[r]=i[r];i.__esModule&&Object.defineProperty(s,"__esModule",{value:!0})}();