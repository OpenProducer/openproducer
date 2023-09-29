(()=>{var e,t={8029:(e,t,a)=>{var n=a(5393),s=a(1700),i=n((function(e,t,a){return e+(a?" ":"")+s(t)}));e.exports=i},9021:(e,t,a)=>{"use strict";a.r(t);var n=a(4942),s=(a(5674),a(9307)),i=a(5736),r=a(1383),l=a(9674),o=a(5609),c=a(6989),d=a.n(c),p=a(2307);const u=i.__,h=(0,r.a4)((e=>{let{services:t,fetchAdvertisingData:a,toggleService:n}=e;const{google_ad_manager:i}=t,[l,c]=(0,s.useState)(!1),[h,g]=(0,s.useState)(""),[m,w]=(0,s.useState)(!1);let k=[];return i.enabled&&i.status.error?k=k.concat([i.status.error," "]):i?.created_targeting_keys?.length>0&&(k=k.concat([u("Created custom targeting keys:")+" ",i.created_targeting_keys.join(", ")+".  ",(0,s.createElement)(o.ExternalLink,{href:`https://admanager.google.com/${i.network_code}#inventory/custom_targeting/list`,key:"google-ad-manager-custom-targeting-link"},u("Visit your GAM dashboard"))])),i.enabled&&i.available&&!i.status.connected&&k.push((0,s.createElement)(r.zx,{key:"gam-connect-account",isLink:!0,onClick:()=>w(!0)},u("Click here to connect your account.","newspack"))),(0,s.createElement)(s.Fragment,null,(0,s.createElement)(r.fM,{title:u("Google Ad Manager"),description:u("Manage Google Ad Manager ad units and placements directly from the Newspack dashboard."),actionText:i&&i.enabled&&u("Configure"),toggle:!0,toggleChecked:i&&i.enabled,toggleOnChange:e=>{n("google_ad_manager",e).then((()=>{!0!==e||i.status.connected||i.status.network_code||w(!0)}))},titleLink:i?.enabled?"#/google_ad_manager":null,href:i?.enabled&&"#/google_ad_manager",notification:k.length?k:null,notificationLevel:i.created_targeting_keys?.length?"success":"error"}),(0,s.createElement)(r.Wy,{plugins:{broadstreet:{actionText:u("Configure"),href:"/wp-admin/admin.php?page=Broadstreet"}}}),m&&(0,s.createElement)(r.u_,{title:u("Google Ad Manager Setup","newspack-ads"),onRequestClose:()=>w(!1)},(0,s.createElement)(p.Z,{onUpdate:e=>g(e.networkCode),onSuccess:()=>{a(),w(!1)}}),(0,s.createElement)(r.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,s.createElement)(r.zx,{isSecondary:!0,disabled:l,onClick:()=>w(!1)},u("Cancel","newspack")),(0,s.createElement)(r.zx,{isPrimary:!0,disabled:l||!h,onClick:()=>(c(!0),void d()({path:"/newspack/v1/wizard/billboard/network_code/",method:"POST",data:{network_code:h,is_gam:!1},quiet:!0}).finally((()=>{a(),c(!1),w(!1)})))},u("Save","newspack")))))}));var g=a(4184),m=a.n(g),w=a(6968),k=a.n(w),v=a(9522),f=a(7462);const b=i.__,y=e=>[{label:b("Select a provider","newspack"),value:""},...e.map((e=>({label:e.name,value:e.id})))],E=e=>{let{label:t=b("Ad Unit","newspack"),providers:a=[],bidders:n={},value:l={},disabled:o=!1,onChange:c,...d}=e;const[p,u]=(0,s.useState)({}),h=a.find((e=>e?.id===(l.provider||"gam")))||a[0];return(0,s.useEffect)((()=>{const e={};Object.keys(n).forEach((t=>{const a=n[t],s=h?.units.find((e=>e.value===l.ad_unit)),r=l.ad_unit&&s&&(o=a.ad_sizes,s.sizes.some((e=>(o||[]).find((t=>t[0]===e[0]&&t[1]===e[1])))));var o;e[t]=l.ad_unit&&s&&!r?(0,i.sprintf)(b("%s does not support the selected ad unit sizes.","newspack"),a.name,""):null})),u(e)}),[a,l.ad_unit]),a.length?(0,s.createElement)(s.Fragment,null,(0,s.createElement)(r.rj,{columns:2,gutter:32},(0,s.createElement)(r.Yw,{label:b("Provider","newspack"),value:h?.id,options:y(a),onChange:e=>c({...l,provider:e}),disabled:o}),(0,s.createElement)(r.Yw,(0,f.Z)({label:t,value:l.ad_unit,options:(g=h,g?.units?[{label:b("Select an Ad Unit","newspack"),value:""},...g.units.map((e=>({label:e.name,value:e.value})))]:[]),onChange:e=>{c({...l,ad_unit:e})},disabled:o},d))),"gam"===h?.id&&Object.keys(n).map((e=>{const t=n[e],a=(0,i.sprintf)(b("%s Placement ID","newspack"),t.name);return(0,s.createElement)(r.w4,(0,f.Z)({key:e,value:l.bidders_ids?l.bidders_ids[e]:null,label:a,disabled:p[e]||o,onChange:t=>{c({...l,bidders_ids:{...l.bidders_ids,[e]:t}})}},d))})),"gam"===h?.id&&Object.keys(p).map((e=>p[e]?(0,s.createElement)(r.qX,{key:e,isWarning:!0},p[e]):null))):(0,s.createElement)(r.qX,{isWarning:!0,noticeText:b("There is no provider available.","newspack")});var g},_=i.__,A=(0,r.a4)((()=>{const[e,t]=(0,s.useState)(!1),[a,n]=(0,s.useState)(!1),[l,c]=(0,s.useState)(null),[p,u]=(0,s.useState)([]),[h,g]=(0,s.useState)(null),[w,f]=(0,s.useState)({}),[b,y]=(0,s.useState)({}),[A,C]=(0,s.useState)(null),S=async e=>{try{const t=await d()(e);f(t),c(null)}catch(e){c(e)}},x=e=>async t=>{await S({path:`/newspack-ads/v1/placements/${e}`,method:t?"POST":"DELETE"}),t&&g(e)},z=(e,t)=>a=>{const n=w[e]?.data;let s={...n,...a};t&&(s={...n,hooks:{...n.hooks,[t]:a}}),f({...w,[e]:{...w[e],data:s}})},T=e=>w[e].data?.enabled;(0,s.useEffect)((()=>{(async()=>{n(!0),await S({path:"/newspack-ads/v1/placements"});try{const e=await d()({path:"/newspack-ads/v1/providers"});u(e)}catch(e){c(e)}try{const e=await d()({path:"/newspack-ads/v1/bidders"});y(e)}catch(e){C(e)}t(!0),n(!1)})()}),[]),(0,s.useEffect)((()=>{!h&&e&&S({path:"/newspack-ads/v1/placements"})}),[h]);const O=h?w[h]:null;return(0,s.createElement)(s.Fragment,null,!a&&!p.length&&(0,s.createElement)(r.qX,{isWarning:!0,noticeText:_("There is no provider available.","newspack")}),(0,s.createElement)("div",{className:m()({"newspack-wizard-ads-placements":!0,"newspack-wizard-section__is-loading":a&&!Object.keys(w).length})},Object.keys(w).map((e=>(0,s.createElement)(r.fM,{key:e,isSmall:!0,disabled:a||!p.length,title:w[e].name,description:w[e].description,toggleOnChange:x(e),toggleChecked:T(e),actionText:T(e)?(0,s.createElement)(r.zx,{disabled:a||!p.length,onClick:()=>g(e),icon:v.Z,label:_("Placement settings","newspack"),tooltipPosition:"bottom center"}):null})))),h&&O&&(0,s.createElement)(r.u_,{title:(0,i.sprintf)(_("%s placement settings","newspack"),O.name),onRequestClose:()=>g(null)},l&&(0,s.createElement)(r.qX,{isError:!0,noticeText:l.message}),A&&(0,s.createElement)(r.qX,{isWarning:!0,noticeText:A.message}),T(h)&&O.hook_name&&(0,s.createElement)(E,{providers:p,bidders:b,value:O.data,disabled:a,onChange:z(h)}),O.hooks&&Object.keys(O.hooks).map((e=>{const t={hookKey:e,...O.hooks[e]};return(0,s.createElement)(r.Zb,{noBorder:!0,key:e},(0,s.createElement)(E,{label:t.name+" "+_("Ad Unit","newspack"),providers:p,bidders:b,value:O.data?.hooks?O.data.hooks[e]:{},disabled:a,onChange:z(h,e)}))})),O.supports?.indexOf("stick_to_top")>-1&&(0,s.createElement)(o.ToggleControl,{label:_("Stick to Top","newspack"),checked:!!O.data?.stick_to_top,onChange:e=>{f(k()({...w},[h,"data","stick_to_top"],e))}}),(0,s.createElement)(r.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,s.createElement)(r.zx,{isSecondary:!0,disabled:a,onClick:()=>{g(null)}},_("Cancel","newspack")),(0,s.createElement)(r.zx,{isPrimary:!0,disabled:a,onClick:async()=>{await(async e=>{n(!0),await S({path:`/newspack-ads/v1/placements/${e}`,method:"POST",data:w[e].data}),n(!1)})(h),g(null)}},_("Save","newspack")))))})),C=i.__,S=[{key:"viewability_threshold",type:"int",description:C("Viewability Threshold","newspack"),help:C("The percentage of the ad slot which must be visible in the viewport in order to be considered eligible for being refreshed. It's recommended you do not lower this below 50 or you risk third-party viewability tracking platforms flagging your ad impressions as not having been viewed before refreshing.","newspack")},{key:"refresh_interval",type:"int",description:C("Refresh Interval","newspack"),help:C("The number of seconds that must pass between an ad crossing the viewability threshold and the the ad refreshing. The plugin enforces a minimum of 30 in order to avoid your site being flagged for abusing ad refreshes by advertisers. This value may however be overridden via the avc_refresh_interval_value filter hook.","newspack")},{key:"maximum_refreshes",type:"int",description:C("Maximum Refreshes","newspack"),help:C("The number of times each ad slot is allowed to be refreshed. If this is set to 4 then an ad slot could have a total of 5 impressions by combining the initial loading of the ad with the 4 times it can refresh.","newspack")},{key:"advertiser_ids",type:"string",description:C("Excluded Advertiser IDs","newspack"),help:C("Prevent ad refreshes for specific advertiser IDs in the format of a comma separated list (e.g., 125,594,293). If an ad slot ever displays an ad creative from one of the listed advertiser IDs then that ad slot will stop refreshing for the remainder of the page view. AdSense does not allow their ads to be auto-refreshed. When Newspack detects that AdSense is the advertiser for any given impression, a refresh will not take place.","newspack")},{key:"line_item_ids",type:"string",description:C("Line Items IDs to Exclude","newspack"),help:C("Prevent ad refreshs for specific line item IDs. (Comma Seperated List)","newspack")},{key:"sizes_to_exclude",type:"string",description:C("Sizes to Exclude","newspack"),help:C("Prevent ad refreshs for specific sizes. Accepts string (fluid) or array (300x250). Example: fluid, 300x250.","newspack")},{key:"slot_ids_to_exclude",type:"string",description:C("Slot IDs to Exclude","newspack"),help:C("Prevent ad refreshs for specific slot IDs e.g. div-gpt-ad-grid-1. (Comma Seperated List).","newspack")}];function x(){const[e,t]=(0,s.useState)(!1),[a,n]=(0,s.useState)(null),[i,l]=(0,s.useState)(null);return(0,s.useEffect)((()=>{(async()=>{t(!0);try{l(await d()({path:"/newspack-ads/v1/ad-refresh-control"}))}catch(e){l(null)}t(!1)})()}),[]),i?(S.forEach((e=>{i.hasOwnProperty(e.key)&&(e.value=i[e.key])})),(0,s.createElement)(r.d5.Section,{error:a,disabled:e,sectionKey:"ad-refresh-control",title:C("Ad Refresh Control","newspack"),description:C("Enable Active View refresh for Google Ad Manager ads without needing to modify any code.","newspack"),active:!i.disable_refresh,fields:S,onUpdate:async e=>{n(null),t(!0);try{const t=await d()({path:"/newspack-ads/v1/ad-refresh-control",method:"POST",data:{...i,...e}});l(t)}catch(e){n(e)}t(!1)},onChange:(e,t)=>{l({...i,[e]:t})}})):null}const z=(0,r.a4)((function(){return(0,s.createElement)(r.d5,{pluginSlug:"newspack-ads",title:null},(0,s.createElement)(x,null))}));var T=a(444);const O=(0,s.createElement)(T.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,s.createElement)(T.Path,{d:"M20 10.8H6.7l4.1-4.5-1.1-1.1-5.8 6.3 5.8 5.8 1.1-1.1-4-3.9H20z"}));var P=a(8837);const N=i.__,M=e=>{let{updateWithAPI:t,isConnected:a}=e;const n=(0,s.useRef)(null),[i,l]=(0,s.useState)("");return(0,s.createElement)(s.Fragment,null,(0,s.createElement)(r.M$,{title:N("Service Account connection","newspack")}),a?(0,s.createElement)(r.rj,null,(0,s.createElement)(r.Wu,{isSmall:!0,onClick:()=>n.current.click(),title:N("Update Service Account credentials","newspack"),chevron:!0}),(0,s.createElement)(r.Wu,{isSmall:!0,onClick:()=>t({path:"/newspack/v1/wizard/billboard/credentials",method:"delete",quiet:!0}),isDestructive:!0,title:N("Remove Service Account credentials","newspack"),chevron:!0})):(0,s.createElement)(r.Wu,{onClick:()=>n.current.click(),title:N("Connect your Google Ad Manager account","newspack"),desc:[N("Upload your Service Account credentials file to connect your GAM account with Newspack Ads.","newspack"),i&&(0,s.createElement)(r.qX,{noticeText:i,isError:!0})],chevron:!0}),(0,s.createElement)("input",{type:"file",accept:".json",ref:n,style:{display:"none"},onChange:e=>{e.target.files.length&&e.target.files[0]&&(0,P.Z)(e.target.files[0]).then((e=>(e=>t({path:"/newspack/v1/wizard/billboard/credentials",method:"post",data:{credentials:e},quiet:!0}))(e))).catch((e=>l(e)))}}))};var D=a(7259),U=a(9630);const j=i.__,I=e=>{const[t,a]=(0,s.useState)(!1),n=()=>{a((e=>!e))},{deleteLink:i,editLink:l}=e;return(0,s.createElement)(s.Fragment,null,(0,s.createElement)(r.zx,{className:t&&"popover-active",onClick:n,icon:D.Z,label:j("More options","newspack"),tooltipPosition:"bottom center"}),t&&(0,s.createElement)(r.J2,{position:"bottom left",onFocusOutside:n,onKeyDown:e=>U.ESCAPE===e.keyCode&&n},(0,s.createElement)(o.MenuItem,{onClick:n,className:"screen-reader-text"},j("Close Popover","newspack")),(0,s.createElement)(o.MenuItem,{href:l,className:"newspack-button",isLink:!0},j("Edit","newspack")),(0,s.createElement)(o.MenuItem,{onClick:i,className:"newspack-button"},j("Archive","newspack"))))},F=i.__,W=(0,r.a4)((e=>{let{adUnits:t,onDelete:a,wizardApiFetch:n,updateWithAPI:i,service:l,serviceData:c,fetchAdvertisingData:d}=e;const p=!!c?.status?.error&&`${F("Google Ad Manager Error","newspack")}: ${c.status.error}`,u=async(e,t)=>{await n({path:"/newspack/v1/wizard/billboard/network_code/",method:"POST",data:{network_code:e,is_gam:t},quiet:!0}),d(!0)},[h,g]=(0,s.useState)(c.status.network_code);(0,s.useEffect)((()=>{g(c.status.network_code)}),[c.status.network_code]);const{connection_mode:m}=c.status,w="legacy"===m,k=e=>!e.is_default&&!e.is_legacy&&w,v=e=>!e.is_default&&!k(e);return(0,s.createElement)(s.Fragment,null,(0,s.createElement)(r.Zb,{noBorder:!0},(0,s.createElement)(r.zx,{isLink:!0,href:"#/",icon:O},F("Back","newspack"))),!w&&h&&(0,s.createElement)(r.Yw,{label:F("Connected GAM network code","newspack"),value:h,options:c.available_networks.map((e=>({label:`${e.name} (${e.code})`,value:e.code}))),onChange:async e=>{u(e,!0)}}),!1===c.status?.is_network_code_matched&&(0,s.createElement)(r.qX,{noticeText:F("Your GAM network code is different than the network code the site was configured with. Legacy ad units are likely to not load.","newspack"),isWarning:!0}),p&&(0,s.createElement)(r.qX,{noticeText:p,isError:!0}),c.created_targeting_keys?.length>0&&(0,s.createElement)(r.qX,{noticeText:[F("Created custom targeting keys:")+" ",c.created_targeting_keys.join(", ")+".  ",(0,s.createElement)(o.ExternalLink,{href:`https://admanager.google.com/${c.network_code}#inventory/custom_targeting/list`,key:"google-ad-manager-custom-targeting-link"},F("Visit your GAM dashboard"))],isSuccess:!0}),w&&c.enabled&&(0,s.createElement)(s.Fragment,null,(0,s.createElement)(r.qX,{noticeText:F("Currently operating in legacy mode.","newspack"),isWarning:!0}),(0,s.createElement)("div",{className:"flex items-end"},(0,s.createElement)(r.w4,{label:F("Network Code","newspack"),value:h,onChange:g,withMargin:!1}),(0,s.createElement)("span",{className:"pl3"},(0,s.createElement)(r.zx,{onClick:async()=>{u(h,!1)},isPrimary:!0},F("Save","newspack"))))),(0,s.createElement)("p",null,F("Set up multiple ad units to use on your homepage, articles and other places throughout your site.","newspack"),(0,s.createElement)("br",null),F("You can place ads through our Newspack Ad Block in the Editor, Newspack Ad widget, and using the global placements.","newspack")),(0,s.createElement)(r.Zb,{headerActions:!0,noBorder:!0},(0,s.createElement)("div",{className:"flex justify-end w-100"},(0,s.createElement)(r.zx,{variant:"primary",href:"#/google_ad_manager/create"},F("Add New Ad Unit","newspack")))),(0,s.createElement)(r.Zb,{noBorder:!0},Object.values(t).filter((e=>0!==e.id)).sort(((e,t)=>t.name.localeCompare(e.name))).sort((e=>e.is_legacy?1:-1)).sort((e=>e.is_default?1:-1)).map((e=>{const t=`#${l}/${e.id}`;return(0,s.createElement)(r.fM,{key:e.id,title:e.name,isSmall:!0,titleLink:v(e)&&t,description:()=>(0,s.createElement)("span",null,e.code?(0,s.createElement)(s.Fragment,null,(0,s.createElement)("i",null,F("Code:","newspack"))," ",(0,s.createElement)("code",null,e.code)):null,e.sizes?.length||e.fluid?(0,s.createElement)(s.Fragment,null," ","| ",F("Sizes:","newspack")," ",e.sizes.map(((e,t)=>(0,s.createElement)("code",{key:t},Array.isArray(e)?e.join("x"):e))),e.fluid&&(0,s.createElement)("code",null,F("Fluid","newspack"))):null,e.is_legacy?(0,s.createElement)(s.Fragment,null," ","| ",(0,s.createElement)("i",null,F("Legacy ad unit.","newspack"))):null,e.is_default?(0,s.createElement)(s.Fragment,null," ","| ",(0,s.createElement)("i",null,F("Default ad unit.","newspack"))):null,k(e)?(0,s.createElement)(s.Fragment,null," ","| ",(0,s.createElement)("i",null,F("Disconnected from GAM.","newspack"))):null),actionText:v(e)&&(0,s.createElement)(I,{deleteLink:()=>a(e.id),editLink:t})})}))),("service_account"===m||w)&&(0,s.createElement)(M,{updateWithAPI:i,isConnected:"service_account"===m&&c.status.connected}))})),Z=(0,s.createElement)(T.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,s.createElement)(T.Path,{d:"M20 5h-5.7c0-1.3-1-2.3-2.3-2.3S9.7 3.7 9.7 5H4v2h1.5v.3l1.7 11.1c.1 1 1 1.7 2 1.7h5.7c1 0 1.8-.7 2-1.7l1.7-11.1V7H20V5zm-3.2 2l-1.7 11.1c0 .1-.1.2-.3.2H9.1c-.1 0-.3-.1-.3-.2L7.2 7h9.6z"}));var q=a(8029),L=a.n(q);const $=i.__,H=window.newspack_ads_wizard.iab_sizes;function B(){return[...Object.keys(H).map((e=>e.split("x").map(Number))),"fluid"]}function G(e){Array.isArray(e)&&(e=e.join("x"));const t=H[e];return t?`${t} (${e})`:e}const R=e=>{let{value:t,selectedOptions:a,onChange:n}=e;const[i,l]=(0,s.useState)(!1),o=B().filter((e=>JSON.stringify(t)===JSON.stringify(e)||!a.find((t=>JSON.stringify(t)===JSON.stringify(e))))),c=i?-1:o.findIndex((e=>JSON.stringify(e)===JSON.stringify(t)));return(0,s.createElement)(s.Fragment,null,(0,s.createElement)(r.Yw,{label:$("Size","newspack"),value:c,options:[...o.map(((e,t)=>({label:Array.isArray(e)?G(e):L()(e),value:t}))),{label:$("Custom","newspack"),value:-1}],onChange:e=>{const t=o[e];l(!t),n(t||[])},hideLabelFromVision:!0}),"fluid"!==t||i?(0,s.createElement)(s.Fragment,null,(0,s.createElement)(r.w4,{label:$("Width","newspack"),value:t[0],onChange:e=>n([e,t[1]]),disabled:!i&&-1!==c,type:"number",hideLabelFromVision:!0}),(0,s.createElement)(r.w4,{label:$("Height","newspack"),value:t[1],onChange:e=>n([t[0],e]),disabled:!i&&-1!==c,type:"number",hideLabelFromVision:!0})):(0,s.createElement)("div",{className:"newspack-advertising-wizard__ad-unit-fluid"},$("Fluid is a native ad size that allows more flexibility when styling your ad. It automatically sizes the ad by filling the width of the enclosing column and adjusting the height as appropriate.","newspack")))},X=i.__;class V extends s.Component{handleOnChange(e,t){const{adUnit:a,onChange:n,service:s}=this.props;n({...a,ad_service:s,..."string"==typeof e?{[e]:t}:e})}getSizeOptions(){const{adUnit:e}=this.props;let t=[...e.sizes&&Array.isArray(e.sizes)?e.sizes:[]];return e.fluid&&(t=[...t,"fluid"]),t}getNextAvailableSize(){const e=B(),t=this.getSizeOptions().map((e=>e.toString())),a=e.map((e=>e.toString())).findIndex((e=>!t.includes(e)));return e[a]||[0,0]}render(){const{adUnit:e,service:t,onSave:a,onCancel:n}=this.props,{id:i,code:l,fluid:o=!1,name:c="",path:d=[]}=e,p=e.is_legacy,u=0!==i,h=e.sizes&&Array.isArray(e.sizes)?e.sizes:[],g=!o&&0===h.length,m=this.getSizeOptions();return(0,s.createElement)(s.Fragment,null,(0,s.createElement)(r.Zb,{headerActions:!0,noBorder:!0},(0,s.createElement)("h2",null,X("Ad Unit Details","newspack"))),(0,s.createElement)(r.rj,{gutter:32},(0,s.createElement)(r.w4,{label:X("Name","newspack"),value:c||"",onChange:e=>this.handleOnChange("name",e)}),(u||p)&&(0,s.createElement)(r.w4,{label:X("Code","newspack"),value:p?l:d.length?`${d.map((e=>e.code)).join("/")}/${l}`:l,className:"code",help:p?void 0:X("Identifies the ad unit in the associated ad tag. Once you've created the ad unit, you can't change the code.","newspack"),disabled:!p,onChange:e=>this.handleOnChange("code",e)})),(0,s.createElement)(r.Zb,{headerActions:!0,noBorder:!0},(0,s.createElement)("h2",null,m.length>1?X("Ad Unit Sizes","newspack"):X("Ad Unit Size","newspack")),(0,s.createElement)(r.zx,{variant:"secondary",onClick:()=>this.handleOnChange("sizes",[...h,this.getNextAvailableSize()])},X("Add New Size","newspack"))),g&&(0,s.createElement)(r.qX,{isWarning:!0,noticeText:X("The ad unit must have at least one valid size or fluid size enabled.","newspack")}),(0,s.createElement)(r.rj,{columns:4,gutter:8,className:"newspack-grid__thead"},(0,s.createElement)("span",null,X("Size","newspack")),(0,s.createElement)("span",null,X("Width","newspack")),(0,s.createElement)("span",null,X("Height","newspack")),(0,s.createElement)("span",{className:"screen-reader-text"},X("Action","newspack"))),m.map(((e,t)=>(0,s.createElement)(r.rj,{columns:4,gutter:8,className:"newspack-grid__tbody",key:t},(0,s.createElement)(R,{selectedOptions:m,value:e,onChange:e=>{const a={};"fluid"===m[t]&&(a.fluid=!1),"fluid"===e?(h.splice(t,1),a.fluid=!0):h[t]=e,a.sizes=h,this.handleOnChange(a)}}),(0,s.createElement)(r.zx,{onClick:()=>{"fluid"===e?this.handleOnChange("fluid",!1):(h.splice(t,1),this.handleOnChange("sizes",h))},icon:Z,disabled:m.length<=1,label:X("Delete","newspack"),showTooltip:!0})))),(0,s.createElement)("div",{className:"newspack-buttons-card"},(0,s.createElement)(r.zx,{disabled:0===c.length||p&&0===l.length||g,variant:"primary",onClick:()=>a(i)},X("Save","newspack")),(0,s.createElement)(r.zx,{variant:"secondary",onClick:()=>n(),href:`#/${t}`},X("Cancel","newspack"))))}}const J=(0,r.a4)(V);var Y=a(6483);const K=i.__,Q=(0,r.a4)((()=>{const[e,t]=(0,s.useState)(!1),[a,n]=(0,s.useState)(!1),[i,l]=(0,s.useState)(!1),[c,p]=(0,s.useState)([]);return(0,s.useEffect)((()=>{d()({path:"/newspack-ads/v1/suppression"}).then(l)}),[]),(0,s.useEffect)((()=>{d()({path:(0,Y.addQueryArgs)("/wp/v2/types",{context:"edit"})}).then((e=>{p(Object.values(e).filter((e=>!0===e.viewable&&!0===e.visibility?.show_ui)).map((e=>({value:e.slug,label:e.name}))))})).catch(t)}),[]),!1===i?(0,s.createElement)(r.Pi,null):(0,s.createElement)(s.Fragment,null,e&&(0,s.createElement)(r.qX,{isError:!0,noticeText:e.message}),(0,s.createElement)(r.M$,{title:K("Post Types","newspack"),description:K("Suppress ads on specific post types.","newspack")}),(0,s.createElement)(r.rj,{columns:3,gutter:16},c.map((e=>(0,s.createElement)(o.CheckboxControl,{key:e.value,label:e.label,checked:i?.post_types?.includes(e.value),onChange:t=>{let a=[...i?.post_types||[]];t&&!a.includes(e.value)?a.push(e.value):a=a.filter((t=>t!==e.value)),l({...i,post_types:a})}})))),(0,s.createElement)(r.M$,{title:K("Tags","newspack"),description:K("Suppress ads on specific tags and their archive pages.","newspack")}),(0,s.createElement)(r.HZ,{value:i.tags?.map((e=>parseInt(e)))||[],onChange:e=>{l({...i,tags:e.map((e=>e.id))})},label:K("Tags to suppress ads on (archives and posts)","newspack "),taxonomy:"tags"}),(0,s.createElement)(o.ToggleControl,{disabled:!1===i,checked:i?.tag_archive_pages,onChange:e=>{l({...i,tag_archive_pages:e})},label:K("All tag archive pages","newspack")}),(0,s.createElement)(r.M$,{title:K("Categories","newspack"),description:K("Suppress ads on specific categories and their archive pages.","newspack")}),(0,s.createElement)(r.HZ,{value:i.categories?.map((e=>parseInt(e)))||[],onChange:e=>{l({...i,categories:e.map((e=>e.id))})},label:K("Categories to suppress ads on (archives and posts)","newspack ")}),(0,s.createElement)(o.ToggleControl,{disabled:!1===i,checked:i?.category_archive_pages,onChange:e=>{l({...i,category_archive_pages:e})},label:K("All category archive pages","newspack")}),(0,s.createElement)(r.M$,{title:K("Author Archive Pages","newspack"),description:K("Suppress ads on automatically generated pages displaying a list of posts by an author.","newspack")}),(0,s.createElement)(o.ToggleControl,{disabled:!1===i,checked:i?.author_archive_pages,onChange:e=>{l({...i,author_archive_pages:e})},label:K("Suppress ads on author archive pages","newspack")})," ",(0,s.createElement)(r.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,s.createElement)(r.zx,{isPrimary:!0,disabled:a,onClick:()=>{n(!0),d()({path:"/newspack-ads/v1/suppression",method:"POST",data:{config:i}}).then(l).catch(t).finally((()=>{n(!1)}))}},K("Save","newspack"))))})),ee=i.__,te=(0,r.a4)((function(){return(0,s.createElement)(r.Wy,{plugins:{"super-cool-ad-inserter":{actionText:ee("Configure","newspack"),href:"#/settings"},"ad-refresh-control":{actionText:ee("Configure","newspack"),href:"#/settings"},"publisher-media-kit":{shouldRefreshAfterUpdate:!0,actionText:ee("Edit Media Kit","newspack"),href:newspack_ads_wizard.mediakit_edit_url?newspack_ads_wizard.mediakit_edit_url:""}}})})),ae=i.__,{HashRouter:ne,Redirect:se,Route:ie,Switch:re}=l.Z;class le extends s.Component{constructor(){var e;super(...arguments),e=this,(0,n.Z)(this,"onWizardReady",(()=>{this.fetchAdvertisingData()})),(0,n.Z)(this,"updateWithAPI",(e=>this.props.wizardApiFetch(e).then((e=>new Promise((t=>{this.setState({advertisingData:{...e,adUnits:e.ad_units.reduce(((e,t)=>(e[t.id]=t,e)),{})}},(()=>{this.props.setError(),t(this.state)}))})))).catch((e=>{throw this.props.setError(e),e})))),(0,n.Z)(this,"fetchAdvertisingData",(function(){let t=arguments.length>0&&void 0!==arguments[0]&&arguments[0];return e.updateWithAPI({path:"/newspack/v1/wizard/billboard",quiet:t})})),(0,n.Z)(this,"toggleService",((e,t)=>this.updateWithAPI({path:"/newspack/v1/wizard/billboard/service/"+e,method:t?"POST":"DELETE",quiet:!0}))),(0,n.Z)(this,"onAdUnitChange",(e=>{const{advertisingData:t}=this.state;t.adUnits[e.id]=e,this.setState({advertisingData:t})})),(0,n.Z)(this,"saveAdUnit",(e=>this.updateWithAPI({path:"/newspack/v1/wizard/billboard/ad_unit/"+(e||0),method:"post",data:this.state.advertisingData.adUnits[e],quiet:!0}))),(0,n.Z)(this,"onAdUnitCancel",(()=>{this.fetchAdvertisingData()})),(0,n.Z)(this,"deleteAdUnit",(e=>{if(r.P6.confirmAction(ae("Are you sure you want to archive this ad unit?","newspack")))return this.updateWithAPI({path:"/newspack/v1/wizard/billboard/ad_unit/"+e,method:"delete",quiet:!0})})),(0,n.Z)(this,"updateAdSuppression",(e=>this.updateWithAPI({path:"/newspack/v1/wizard/billboard/suppression",method:"post",data:{config:e},quiet:!0}))),this.state={advertisingData:{adUnits:{},services:{google_ad_manager:{status:{}}},suppression:!1}}}render(){const{advertisingData:e}=this.state,{pluginRequirements:t,wizardApiFetch:a}=this.props,{services:n,adUnits:i}=e,r=[{label:ae("Providers","newspack"),path:"/",exact:!0},{label:ae("Placements","newspack"),path:"/placements"},{label:ae("Settings","newspack"),path:"/settings"},{label:ae("Suppression","newspack"),path:"/suppression"},{label:ae("Add-Ons","newspack"),path:"/addons"}];return(0,s.createElement)(s.Fragment,null,(0,s.createElement)(ne,{hashType:"slash"},(0,s.createElement)(re,null,t,(0,s.createElement)(ie,{path:"/",exact:!0,render:()=>(0,s.createElement)(h,{headerText:"Advertising",subHeaderText:ae("Manage ad providers and their settings.","newspack"),services:n,toggleService:this.toggleService,fetchAdvertisingData:this.fetchAdvertisingData,tabbedNavigation:r})}),(0,s.createElement)(ie,{path:"/placements",render:()=>(0,s.createElement)(A,{headerText:ae("Advertising","newspack"),subHeaderText:ae("Define global advertising placements to serve ad units on your site","newspack"),tabbedNavigation:r})}),(0,s.createElement)(ie,{path:"/settings",render:()=>(0,s.createElement)(z,{headerText:ae("Advertising","newspack"),subHeaderText:ae("Configure display and advanced settings for your ads","newspack"),tabbedNavigation:r})}),(0,s.createElement)(ie,{path:"/google_ad_manager",exact:!0,render:()=>(0,s.createElement)(W,{headerText:"Google Ad Manager",subHeaderText:ae("Monetize your content through Google Ad Manager","newspack"),adUnits:i,service:"google_ad_manager",serviceData:n.google_ad_manager,onDelete:e=>this.deleteAdUnit(e),wizardApiFetch:a,fetchAdvertisingData:this.fetchAdvertisingData,updateWithAPI:this.updateWithAPI,tabbedNavigation:r})}),(0,s.createElement)(ie,{path:"/google_ad_manager/create",render:e=>(0,s.createElement)(J,{headerText:ae("Add New Ad Unit","newspack"),subHeaderText:ae("Allows you to place ads on your site","newspack"),adUnit:i[0]||{id:0,name:"",code:"",sizes:[B()[0]],fluid:!1},service:"google_ad_manager",serviceData:n.google_ad_manager,wizardApiFetch:a,onChange:this.onAdUnitChange,onSave:t=>this.saveAdUnit(t).then((()=>{e.history.push("/google_ad_manager")})).catch((()=>{})),onCancel:this.onAdUnitCancel,tabbedNavigation:r})}),(0,s.createElement)(ie,{path:"/google_ad_manager/:id",render:e=>{const t=e.match.params.id;return(0,s.createElement)(J,{headerText:ae("Edit Ad Unit","newspack"),subHeaderText:ae("Allows you to place ads on your site","newspack"),adUnit:i[t]||{},service:"google_ad_manager",onChange:this.onAdUnitChange,onSave:t=>this.saveAdUnit(t).then((()=>{e.history.push("/google_ad_manager")})),onCancel:this.onAdUnitCancel,tabbedNavigation:r})}}),(0,s.createElement)(ie,{path:"/suppression",render:()=>(0,s.createElement)(Q,{headerText:ae("Advertising","newspack"),subHeaderText:ae("Allows you to manage site-wide ad suppression","newspack"),tabbedNavigation:r,config:e.suppression,onChange:e=>this.updateAdSuppression(e)})}),(0,s.createElement)(ie,{path:"/addons",render:()=>(0,s.createElement)(te,{headerText:ae("Advertising","newspack"),subHeaderText:ae("Add-ons for enhanced advertising","newspack"),tabbedNavigation:r})}),(0,s.createElement)(se,{to:"/"}))))}}(0,s.render)((0,s.createElement)((0,r.uF)(le,["newspack-ads"])),document.getElementById("newspack-advertising-wizard"))},9196:e=>{"use strict";e.exports=window.React},6292:e=>{"use strict";e.exports=window.moment},6989:e=>{"use strict";e.exports=window.wp.apiFetch},5609:e=>{"use strict";e.exports=window.wp.components},9818:e=>{"use strict";e.exports=window.wp.data},9307:e=>{"use strict";e.exports=window.wp.element},2694:e=>{"use strict";e.exports=window.wp.hooks},2629:e=>{"use strict";e.exports=window.wp.htmlEntities},5736:e=>{"use strict";e.exports=window.wp.i18n},9630:e=>{"use strict";e.exports=window.wp.keycodes},444:e=>{"use strict";e.exports=window.wp.primitives},6483:e=>{"use strict";e.exports=window.wp.url}},a={};function n(e){var s=a[e];if(void 0!==s)return s.exports;var i=a[e]={id:e,loaded:!1,exports:{}};return t[e].call(i.exports,i,i.exports,n),i.loaded=!0,i.exports}n.m=t,e=[],n.O=(t,a,s,i)=>{if(!a){var r=1/0;for(d=0;d<e.length;d++){a=e[d][0],s=e[d][1],i=e[d][2];for(var l=!0,o=0;o<a.length;o++)(!1&i||r>=i)&&Object.keys(n.O).every((e=>n.O[e](a[o])))?a.splice(o--,1):(l=!1,i<r&&(r=i));if(l){e.splice(d--,1);var c=s();void 0!==c&&(t=c)}}return t}i=i||0;for(var d=e.length;d>0&&e[d-1][2]>i;d--)e[d]=e[d-1];e[d]=[a,s,i]},n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var a in t)n.o(t,a)&&!n.o(e,a)&&Object.defineProperty(e,a,{enumerable:!0,get:t[a]})},n.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),n.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.nmd=e=>(e.paths=[],e.children||(e.children=[]),e),n.j=490,(()=>{var e;n.g.importScripts&&(e=n.g.location+"");var t=n.g.document;if(!e&&t&&(t.currentScript&&(e=t.currentScript.src),!e)){var a=t.getElementsByTagName("script");a.length&&(e=a[a.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),n.p=e})(),(()=>{var e={490:0};n.O.j=t=>0===e[t];var t=(t,a)=>{var s,i,r=a[0],l=a[1],o=a[2],c=0;if(r.some((t=>0!==e[t]))){for(s in l)n.o(l,s)&&(n.m[s]=l[s]);if(o)var d=o(n)}for(t&&t(a);c<r.length;c++)i=r[c],n.o(e,i)&&e[i]&&e[i][0](),e[r[c]]=0;return n.O(d)},a=self.webpackChunkwebpack=self.webpackChunkwebpack||[];a.forEach(t.bind(null,0)),a.push=t.bind(null,a.push.bind(a))})();var s=n.O(void 0,[351],(()=>n(9021)));s=n.O(s);var i=window;for(var r in s)i[r]=s[r];s.__esModule&&Object.defineProperty(i,"__esModule",{value:!0})})();