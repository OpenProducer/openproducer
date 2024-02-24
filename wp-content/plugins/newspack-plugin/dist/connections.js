(()=>{"use strict";var e,t={4845:(e,t,n)=>{n.r(t),n(5674);var a=n(9307),s=n(5736),c=n(1732),r=n(9674),l=n(6989),i=n.n(l);const o=s.__,p={jetpack:{pluginSlug:"jetpack",editLink:"admin.php?page=jetpack#/settings",name:"Jetpack",fetchStatus:()=>i()({path:"/newspack/v1/plugins/jetpack"}).then((e=>({jetpack:{status:e.Configured?e.Status:"inactive"}})))},"google-site-kit":{pluginSlug:"google-site-kit",editLink:"admin.php?page=googlesitekit-splash",name:o("Site Kit by Google","newspack"),fetchStatus:()=>i()({path:"/newspack/v1/plugins/google-site-kit"}).then((e=>({"google-site-kit":{status:e.Configured?e.Status:"inactive"}})))}},u=e=>e.pluginSlug?(0,a.createElement)(c.OQ,{plugin:e.pluginSlug,editLink:e.editLink,compact:!0,isLink:!0},o("Connect","newspack")):e.url?(0,a.createElement)(c.zx,{isLink:!0,href:e.url,target:"_blank"},o("Connect","newspack")):"unavailable_site_id"===e.error?.code?(0,a.createElement)("span",{className:"i newspack-error"},o("Jetpack connection required","newspack")):void 0,d=({setError:e})=>{const[t,n]=c.PT.useObjectState(p),s=Object.values(t);return(0,a.useEffect)((()=>{s.forEach((async t=>{const a=await t.fetchStatus().catch(e);n(a)}))}),[]),(0,a.createElement)(a.Fragment,null,s.map((e=>{const t="inactive"===e.status,n=!e.status;return(0,a.createElement)(c.fM,{key:e.name,title:e.name,description:`${o("Status:","newspack")} ${n?o("Loading…","newspack"):t?"google-site-kit"===e.pluginSlug?o("Not connected for this user","newspack"):o("Not connected","newspack"):o("Connected","newspack")}`,actionText:t?u(e):null,checkbox:t||n?"unchecked":"checked",badge:e.badge,indent:e.indent,isMedium:!0})})))};var m=n(8635),h=n(9630),w=n(5609);const k=s.__,g=({setError:e})=>{const[t,n]=(0,a.useState)({}),[r,l]=(0,a.useState)(!1),[o,p]=(0,a.useState)(),[u,d]=(0,a.useState)(!1),m=(0,a.useRef)(null),g=Boolean(t&&t.username),E=t=>e(t.message||k("Something went wrong.","newspack")),b=()=>{l(!1),p()};(0,a.useEffect)((()=>{d(!0),i()({path:"/newspack/v1/oauth/mailchimp"}).then((e=>{n(e)})).catch(E).finally((()=>d(!1)))}),[]),(0,a.useEffect)((()=>{r&&m.current.querySelector("input").focus()}),[r]);const f=()=>{e(),d(!0),i()({path:"/newspack/v1/oauth/mailchimp",method:"POST",data:{api_key:o}}).then((e=>{n(e)})).catch((t=>{e(t.message||k("Something went wrong during verification of your Mailchimp API key.","newspack"))})).finally((()=>{d(!1),b()}))};return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(c.fM,{title:"Mailchimp",description:`${k("Status:","newspack")} ${u?k("Loading…","newspack"):g?(0,s.sprintf)(k("Connected as %s","newspack"),t.username):k("Not connected","newspack")}`,checkbox:g?"checked":"unchecked",actionText:(0,a.createElement)(c.zx,{isLink:!0,isDestructive:g,onClick:g?()=>{d(!0),i()({path:"/newspack/v1/oauth/mailchimp",method:"DELETE"}).then((()=>{n({}),d(!1)})).catch(E)}:()=>l(!0),disabled:u},k(g?"Disconnect":"Connect","newspack")),isMedium:!0}),r&&(0,a.createElement)(c.u_,{title:k("Add Mailchimp API Key","newspack"),onRequestClose:b},(0,a.createElement)("div",{ref:m},(0,a.createElement)(c.rj,{columns:1,gutter:8},(0,a.createElement)(c.w4,{placeholder:"123457103961b1f4dc0b2b2fd59c137b-us1",label:k("Mailchimp API Key","newspack"),hideLabelFromVision:!0,value:o,onChange:p,onKeyDown:e=>{h.ENTER===e.keyCode&&""!==o&&(e.preventDefault(),f())}}),(0,a.createElement)("p",null,(0,a.createElement)(w.ExternalLink,{href:"https://mailchimp.com/help/about-api-keys/#Find_or_generate_your_API_key"},k("Find or generate your API key","newspack"))))),(0,a.createElement)(c.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,a.createElement)(c.zx,{isSecondary:!0,onClick:b},k("Cancel","newspack")),(0,a.createElement)(c.zx,{isPrimary:!0,disabled:!o,onClick:f},k(u?"Connecting…":g?"Connected":"Connect","newspack")))))};var E=n(3967),b=n.n(E),f=n(7361),v=n.n(f);const y=s.__,_=[{service:"stripe",label:y("Stripe","newspack")}],C=({setError:e})=>{const[t,n]=(0,a.useState)(),[s,r]=(0,a.useState)(!1),[l,o]=(0,a.useState)(null),p=t=>e(t.message||y("Something went wrong.","newspack")),u=s||!(void 0!==t)||!l;return(0,a.useEffect)((()=>{r(!0),i()({path:"/newspack/v1/oauth/fivetran"}).then((e=>{n(e.connections_statuses),o(e.has_accepted_tos)})).catch(p).finally((()=>r(!1)))}),[]),(0,a.createElement)(a.Fragment,null,(0,a.createElement)("div",null,y("In order to use the this features, you must read and accept","newspack")," ",(0,a.createElement)("a",{href:"https://newspack.com/terms-of-service/"},y("Newspack Terms of Service","newspack")),":"),(0,a.createElement)(w.CheckboxControl,{className:b()("mt1",{"o-50":null===l}),checked:l,disabled:null===l,onChange:e=>{i()({path:"/newspack/v1/oauth/fivetran-tos",method:"POST",data:{has_accepted:e}}),o(e)},label:y("I've read and accept Newspack Terms of Service","newspack")}),_.map((e=>{const n=((e,t)=>{const n=void 0!==t,a=v()(t,[e.service,"setup_state"]),s=v()(t,[e.service,"sync_state"]),c=v()(t,[e.service,"schema_status"]),r=c&&"ready"!==c||"paused"===s;let l="-";return a?"ready"===c?l=`${a}, ${s}`:r&&(l=`${a}, ${s}. ${y("Sync is in progress – please check back in a while.","newspack")}`):n&&(l=y("Not connected","newspack")),{label:l,isConnected:"connected"===a,isPending:r}})(e,t);return(0,a.createElement)(c.fM,{key:e.service,title:e.label,description:`${y("Status:","newspack")} ${n.label}`,isPending:n.isPending,actionText:(0,a.createElement)(w.Button,{disabled:u,onClick:()=>(({service:e})=>{r(!0),i()({path:`/newspack/v1/oauth/fivetran/${e}`,method:"POST",data:{service:e}}).then((({url:e})=>window.location=e)).catch(p)})(e),isLink:!0},n.isConnected?y("Re-connect","newspack"):y("Connect","newspack")),checkbox:n.isConnected?"checked":"unchecked",isMedium:!0})})))},S=s.__,x=()=>{const[e,t]=(0,a.useState)(null),[n,s]=(0,a.useState)(!1),[r,l]=(0,a.useState)({}),[o,p]=(0,a.useState)({});(0,a.useEffect)((()=>{(async()=>{s(!0);try{const e=await i()({path:"/newspack/v1/recaptcha"});l(e),p(e)}catch(e){t(e.message||S("Error fetching settings.","newspack-plugin"))}finally{s(!1)}})()}),[]);const u=async e=>{t(null),s(!0);try{l(await i()({path:"/newspack/v1/recaptcha",method:"POST",data:e})),p({})}catch(e){t(e?.message||S("Error updating settings.","newspack-plugin"))}finally{s(!1)}};return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(c.M$,{id:"recaptcha",title:S("reCAPTCHA v3","newspack-plugin")}),(0,a.createElement)(c.fM,{isMedium:!0,title:S("Enable reCAPTCHA v3","newspack-plugin"),description:()=>(0,a.createElement)(a.Fragment,null,S("Enabling reCAPTCHA v3 can help protect your site against bot attacks and credit card testing.","newspack-plugin")," ",(0,a.createElement)(w.ExternalLink,{href:"https://www.google.com/recaptcha/admin/create"},S("Get started"))),hasGreyHeader:!!r.use_captcha,toggleChecked:!!r.use_captcha,toggleOnChange:()=>u({use_captcha:!r.use_captcha}),actionContent:r.use_captcha&&(0,a.createElement)(c.zx,{variant:"primary",disabled:n||!Object.keys(o).length,onClick:()=>u(o)},S("Save Settings","newspack-plugin")),disabled:n},r.use_captcha&&(0,a.createElement)(a.Fragment,null,e&&(0,a.createElement)(c.qX,{isError:!0,noticeText:e}),r.use_captcha&&(!r.site_key||!r.site_secret)&&(0,a.createElement)(c.qX,{noticeText:S("You must enter a valid site key and secret to use reCAPTCHA.","newspack-plugin")}),(0,a.createElement)(c.rj,{noMargin:!0,rowGap:16},(0,a.createElement)(c.w4,{value:o?.site_key||"",label:S("Site Key","newspack-plugin"),onChange:e=>p({...o,site_key:e}),disabled:n,autoComplete:"off"}),(0,a.createElement)(c.w4,{type:"password",value:o?.site_secret||"",label:S("Site Secret","newspack-plugin"),onChange:e=>p({...o,site_secret:e}),disabled:n,autoComplete:"off"}),(0,a.createElement)(c.w4,{type:"number",step:"0.05",min:"0",max:"1",value:o?.threshold||"",label:S("Threshold","newspack-plugin"),onChange:e=>p({...o,threshold:e}),disabled:n,help:(0,a.createElement)(w.ExternalLink,{href:"https://developers.google.com/recaptcha/docs/v3#interpreting_the_score"},S("Learn more about the threshold value","newspack-plugin"))})))))};var T=n(6292),N=n.n(T),P=n(793),A=n(8184),M=n(444);const q=(0,a.createElement)(M.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,a.createElement)(M.Path,{d:"M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"}));var L=n(9522),O=n(7259),$=n(1984);const j=s.__,F=e=>{let t=e.slice(8);return e.length>45&&(t=`${e.slice(8,38)}...${e.slice(-10)}`),t},I=e=>{const{label:t,url:n}=e;return(0,a.createElement)(a.Fragment,null,t&&(0,a.createElement)("span",{className:"newspack-webhooks__endpoint__label"},t),(0,a.createElement)("span",{className:"newspack-webhooks__endpoint__url"},F(n)))},R=e=>e.requests.some((e=>e.errors.length)),z=({disabled:e,position:t="bottom left",isSystem:n,onEdit:s=(()=>{}),onDelete:r=(()=>{}),onView:l=(()=>{})})=>{const[i,o]=(0,a.useState)(!1);return(0,a.useEffect)((()=>{o(!1)}),[e]),(0,a.createElement)(a.Fragment,null,(0,a.createElement)(c.zx,{className:i&&"popover-active",onClick:()=>o(!i),icon:O.Z,disabled:e,label:j("Endpoint Actions","newspack"),tooltipPosition:t}),i&&(0,a.createElement)(c.J2,{position:t,onFocusOutside:()=>o(!1),onKeyDown:e=>h.ESCAPE===e.keyCode&&o(!1)},(0,a.createElement)(w.MenuItem,{onClick:()=>o(!1),className:"screen-reader-text"},j("Close Endpoint Actions","newspack")),(0,a.createElement)(w.MenuItem,{onClick:l,className:"newspack-button"},j("View Requests","newspack")),!n&&(0,a.createElement)(w.MenuItem,{onClick:s,className:"newspack-button"},j("Edit","newspack")),!n&&(0,a.createElement)(w.MenuItem,{onClick:r,className:"newspack-button",isDestructive:!0},j("Remove","newspack"))))},Z=({disabled:e,onConfirm:t,onClose:n,title:s,description:r})=>(0,a.createElement)(c.u_,{title:s,onRequestClose:n},(0,a.createElement)("p",null,r),(0,a.createElement)(c.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,a.createElement)(c.zx,{isSecondary:!0,onClick:n,disabled:e},j("Cancel","newspack")),(0,a.createElement)(c.zx,{isPrimary:!0,onClick:t,disabled:e},j("Confirm","newspack")))),B=()=>{const[e,t]=(0,a.useState)(!1),[n,r]=(0,a.useState)(!1),[l,o]=(0,a.useState)([]),[p,u]=(0,a.useState)([]),[d,m]=(0,a.useState)(!1),[h,k]=(0,a.useState)(!1),[g,E]=(0,a.useState)(!1),[b,f]=(0,a.useState)(!1),[v,y]=(0,a.useState)(!1),[_,C]=(0,a.useState)(!1),[S,x]=(0,a.useState)(!1);return(0,a.useEffect)((()=>{i()({path:"/newspack/v1/data-events/actions"}).then((e=>{o(e)})).catch((e=>{r(e)}))}),[]),(0,a.useEffect)((()=>{t(!0),i()({path:"/newspack/v1/webhooks/endpoints"}).then((e=>{u(e)})).catch((e=>{r(e)})).finally((()=>{t(!1)}))}),[]),(0,a.useEffect)((()=>{C(!1),y(!1),x(!1)}),[b]),(0,a.createElement)(c.Zb,{noBorder:!0,className:"mt64"},!1!==n&&(0,a.createElement)(c.qX,{isError:!0,noticeText:n.message}),(0,a.createElement)("div",{className:"flex justify-between items-end"},(0,a.createElement)(c.M$,{title:j("Webhook Endpoints","newspack-plugin"),description:j("Register webhook endpoints to integrate reader activity data to third-party services or private APIs","newspack-plugin"),noMargin:!0}),(0,a.createElement)(c.zx,{variant:"primary",onClick:()=>f({global:!0}),disabled:e},j("Add New Endpoint","newspack-plugin"))),p.length>0&&(0,a.createElement)(a.Fragment,null,p.map((e=>(0,a.createElement)(c.fM,{isMedium:!0,className:"newspack-webhooks__endpoint mt16",toggleChecked:!e.disabled,toggleOnChange:()=>k(e),key:e.id,title:I(e),disabled:e.system,description:()=>e.disabled&&e.disabled_error?j("This endpoint is disabled due to excessive request errors","newspack-plugin")+": "+e.disabled_error:(0,a.createElement)(a.Fragment,null,j("Actions:","newspack-plugin")," ",e.global?(0,a.createElement)("span",{className:"newspack-webhooks__endpoint__action"},j("global","newspack-plugin")):e.actions.map((e=>(0,a.createElement)("span",{key:e,className:"newspack-webhooks__endpoint__action"},e)))),actionText:(0,a.createElement)(z,{onEdit:()=>f(e),onDelete:()=>m(e),onView:()=>E(e),isSystem:e.system})})))),!1!==d&&(0,a.createElement)(Z,{title:j("Remove Endpoint","newspack-plugin"),description:(0,s.sprintf)(j("Are you sure you want to remove the endpoint %s?","newspack-plugin"),`"${F(d.url)}"`),onClose:()=>m(!1),onConfirm:()=>{return e=d,t(!0),void i()({path:`/newspack/v1/webhooks/endpoints/${e.id}`,method:"DELETE"}).then((e=>{u(e)})).catch((e=>{r(e)})).finally((()=>{t(!1),m(!1)}));var e},disabled:e}),!1!==h&&(0,a.createElement)(Z,{title:h.disabled?j("Enable Endpoint","newspack-plugin"):j("Disable Endpoint","newspack-plugin"),description:h.disabled?(0,s.sprintf)(j("Are you sure you want to enable the endpoint %s?","newspack-plugin"),`"${F(h.url)}"`):(0,s.sprintf)(j("Are you sure you want to disable the endpoint %s?","newspack-plugin"),`"${F(h.url)}"`),endpoint:h,onClose:()=>k(!1),onConfirm:()=>{return e=h,t(!0),void i()({path:`/newspack/v1/webhooks/endpoints/${e.id}`,method:"POST",data:{disabled:!e.disabled}}).then((e=>{u(e)})).catch((e=>{r(e)})).finally((()=>{t(!1),k(!1)}));var e},disabled:e}),!1!==g&&(0,a.createElement)(c.u_,{title:j("Latest Requests","newspack-plugin"),onRequestClose:()=>E(!1)},(0,a.createElement)("p",null,(0,s.sprintf)(j("Most recent requests for %s","newspack-plugin"),(e=>{const{label:t,url:n}=e;return t||F(n)})(g))),g.requests.length>0?(0,a.createElement)("table",{className:"newspack-webhooks__requests "+(R(g)?"has-error":"")},(0,a.createElement)("tr",null,(0,a.createElement)("th",null),(0,a.createElement)("th",{colSpan:"2"},j("Action","newspack-plugin")),R(g)&&(0,a.createElement)("th",{colSpan:"2"},j("Error","newspack-plugin"))),g.requests.map((e=>{return(0,a.createElement)("tr",{key:e.id},(0,a.createElement)("td",{className:`status status--${e.status}`},(0,a.createElement)($.Z,{icon:(t=e.status,{pending:P.Z,finished:A.Z,killed:q}[t]||L.Z)})),(0,a.createElement)("td",{className:"action-name"},e.action_name),(0,a.createElement)("td",{className:"scheduled"},"pending"===e.status?(0,s.sprintf)(j("sending in %s","newspack-plugin"),N()(1e3*parseInt(e.scheduled)).fromNow(!0)):(0,s.sprintf)(j("processed %s","newspack-plugin"),N()(1e3*parseInt(e.scheduled)).fromNow())),R(g)&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)("td",{className:"error"},e.errors&&e.errors.length>0?e.errors[e.errors.length-1]:"--"),(0,a.createElement)("td",null,(0,a.createElement)("span",{className:"error-count"},(0,s.sprintf)(j("Attempt #%s","newspack-plugin"),e.errors.length)))));var t}))):(0,a.createElement)(c.qX,{noticeText:j("This endpoint hasn't received any requests yet.","newspack-plugin")})),!1!==b&&(0,a.createElement)(c.u_,{title:j("Webhook Endpoint","newspack-plugin"),onRequestClose:()=>{f(!1),y(!1)}},!1!==v&&(0,a.createElement)(c.qX,{isError:!0,noticeText:v.message}),!0===b.disabled&&(0,a.createElement)(c.qX,{noticeText:j("This webhook endpoint is currently disabled.","newspack-plugin"),className:"mt0"}),b.disabled&&b.disabled_error&&(0,a.createElement)(c.qX,{isError:!0,noticeText:j("Request Error: ","newspack-plugin")+b.disabled_error,className:"mt0"}),S&&(0,a.createElement)(c.qX,{isError:!0,noticeText:j("Test Error: ","newspack-plugin")+S.message,className:"mt0"}),(0,a.createElement)(c.rj,{columns:1,gutter:16,className:"mt0"},(0,a.createElement)(c.w4,{label:j("URL","newspack-plugin"),help:j("The URL to send requests to. It's required for the URL to be under a valid TLS/SSL certificate. You can use the test button below to verify the endpoint response.","newspack-plugin"),className:"code",value:b.url,onChange:e=>f({...b,url:e}),disabled:e}),(0,a.createElement)(c.w4,{label:j("Authentication token (optional)","newspack-plugin"),help:j("If your endpoint requires a token authentication, enter it here. It will be sent as a Bearer token in the Authorization header.","newspack-plugin"),value:b.bearer_token,onChange:e=>f({...b,bearer_token:e}),disabled:e}),(0,a.createElement)(c.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},_&&(0,a.createElement)("div",{className:"newspack-webhooks__test-response status--"+(_.success?"success":"error")},(0,a.createElement)("span",{className:"message"},_.message),(0,a.createElement)("span",{className:"code"},_.code)),(0,a.createElement)(c.zx,{isSecondary:!0,onClick:()=>{return e=b.url,n=b.bearer_token,t(!0),x(!1),C(!1),void i()({path:"/newspack/v1/webhooks/endpoints/test",method:"POST",data:{url:e,bearer_token:n}}).then((e=>{C(e)})).catch((e=>{x(e)})).finally((()=>{t(!1)}));var e,n},disabled:e||!b.url},j("Send a test request","newspack-plugin")))),(0,a.createElement)("hr",null),(0,a.createElement)(c.w4,{label:j("Label (optional)","newspack-plugin"),help:j("A label to help you identify this endpoint. It will not be sent to the endpoint.","newspack-plugin"),value:b.label,onChange:e=>f({...b,label:e}),disabled:e}),(0,a.createElement)(c.rj,{columns:1,gutter:16},(0,a.createElement)("h3",null,j("Actions","newspack-plugin")),(0,a.createElement)(w.CheckboxControl,{checked:b.global,onChange:e=>f({...b,global:e}),label:j("Global","newspack-plugin"),help:j("Leave this checked if you want this endpoint to receive data from all actions.","newspack-plugin"),disabled:e}),l.length>0&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)("p",null,j("If this endpoint is not global, select which actions should trigger this endpoint:","newspack-plugin")),(0,a.createElement)(c.rj,{columns:2,gutter:16},l.map(((t,n)=>(0,a.createElement)(w.CheckboxControl,{key:n,disabled:b.global||e,label:t,checked:b.actions&&b.actions.includes(t)||!1,indeterminate:b.global,onChange:()=>{const e=b.actions||[];e.includes(t)?e.splice(e.indexOf(t),1):e.push(t),f({...b,actions:e})}}))))),(0,a.createElement)(c.Zb,{buttonsCard:!0,noBorder:!0,className:"justify-end"},(0,a.createElement)(c.zx,{isPrimary:!0,onClick:()=>{var e;e=b,t(!0),y(!1),i()({path:`/newspack/v1/webhooks/endpoints/${e.id||""}`,method:"POST",data:e}).then((e=>{u(e),f(!1)})).catch((e=>{y(e)})).finally((()=>{t(!1)}))},disabled:e},j("Save","newspack-plugin"))))))},D=s.__,X=s.__,{HashRouter:H,Redirect:G,Route:K,Switch:V}=r.Z,J=(0,c.a4)((()=>{const[e,t]=(0,a.useState)(),n=e=>n=>t(n?e+n:null);return(0,a.createElement)(a.Fragment,null,e&&(0,a.createElement)(c.qX,{isError:!0,noticeText:e}),(0,a.createElement)(c.M$,{title:D("Plugins","newspack")}),(0,a.createElement)(d,null),(0,a.createElement)(c.M$,{title:D("APIs","newspack")}),newspack_connections_data.can_connect_google&&(0,a.createElement)(m.Z,{setError:n(D("Google: ","newspack-plugin"))}),(0,a.createElement)(g,{setError:n(D("Mailchimp: ","newspack-plugin"))}),newspack_connections_data.can_connect_fivetran&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)(c.M$,{title:"Fivetran"}),(0,a.createElement)(C,{setError:n(D("Fivetran: ","newspack-plugin"))})),(0,a.createElement)(x,{setError:n(D("reCAPTCHA: ","newspack-plugin"))}),newspack_connections_data.can_use_webhooks&&(0,a.createElement)(B,null))}));(0,a.render)((0,a.createElement)((0,c.uF)((({pluginRequirements:e,wizardApiFetch:t,startLoading:n,doneLoading:s})=>{const c={headerText:X("Connections","newspack"),subHeaderText:X("Connections to third-party services","newspack"),wizardApiFetch:t,startLoading:n,doneLoading:s};return(0,a.createElement)(H,{hashType:"slash"},(0,a.createElement)(V,null,e,(0,a.createElement)(K,{exact:!0,path:"/",render:()=>(0,a.createElement)(J,c)}),(0,a.createElement)(G,{to:"/"})))}))),document.getElementById("newspack-connections-wizard"))},9196:e=>{e.exports=window.React},6292:e=>{e.exports=window.moment},6989:e=>{e.exports=window.wp.apiFetch},5609:e=>{e.exports=window.wp.components},9818:e=>{e.exports=window.wp.data},9307:e=>{e.exports=window.wp.element},2694:e=>{e.exports=window.wp.hooks},2629:e=>{e.exports=window.wp.htmlEntities},5736:e=>{e.exports=window.wp.i18n},9630:e=>{e.exports=window.wp.keycodes},444:e=>{e.exports=window.wp.primitives},6483:e=>{e.exports=window.wp.url}},n={};function a(e){var s=n[e];if(void 0!==s)return s.exports;var c=n[e]={id:e,loaded:!1,exports:{}};return t[e].call(c.exports,c,c.exports,a),c.loaded=!0,c.exports}a.m=t,e=[],a.O=(t,n,s,c)=>{if(!n){var r=1/0;for(p=0;p<e.length;p++){for(var[n,s,c]=e[p],l=!0,i=0;i<n.length;i++)(!1&c||r>=c)&&Object.keys(a.O).every((e=>a.O[e](n[i])))?n.splice(i--,1):(l=!1,c<r&&(r=c));if(l){e.splice(p--,1);var o=s();void 0!==o&&(t=o)}}return t}c=c||0;for(var p=e.length;p>0&&e[p-1][2]>c;p--)e[p]=e[p-1];e[p]=[n,s,c]},a.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return a.d(t,{a:t}),t},a.d=(e,t)=>{for(var n in t)a.o(t,n)&&!a.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},a.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),a.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),a.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.nmd=e=>(e.paths=[],e.children||(e.children=[]),e),a.j=806,(()=>{var e;a.g.importScripts&&(e=a.g.location+"");var t=a.g.document;if(!e&&t&&(t.currentScript&&(e=t.currentScript.src),!e)){var n=t.getElementsByTagName("script");n.length&&(e=n[n.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),a.p=e})(),(()=>{var e={806:0};a.O.j=t=>0===e[t];var t=(t,n)=>{var s,c,[r,l,i]=n,o=0;if(r.some((t=>0!==e[t]))){for(s in l)a.o(l,s)&&(a.m[s]=l[s]);if(i)var p=i(a)}for(t&&t(n);o<r.length;o++)c=r[o],a.o(e,c)&&e[c]&&e[c][0](),e[r[o]]=0;return a.O(p)},n=globalThis.webpackChunkwebpack=globalThis.webpackChunkwebpack||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var s=a.O(void 0,[351],(()=>a(4845)));s=a.O(s);var c=window;for(var r in s)c[r]=s[r];s.__esModule&&Object.defineProperty(c,"__esModule",{value:!0})})();