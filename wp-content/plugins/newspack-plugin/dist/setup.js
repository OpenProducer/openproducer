!function(){"use strict";var e,t={5177:function(e,t,n){n.r(t),n(5674);var a=n(9307),r=n(5736),s=n(2819),o=n(6989),i=n.n(o),l=n(1984),c=n(5430),u=n(8184),p=n(444),d=(0,a.createElement)(p.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,a.createElement)(p.Path,{d:"M18.5 5.5V8H20V5.5h2.5V4H20V1.5h-1.5V4H16v1.5h2.5zM12 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-6h-1.5v6a.5.5 0 01-.5.5H6a.5.5 0 01-.5-.5V6a.5.5 0 01.5-.5h6V4z"})),m=n(5332),w=n(6483),h=n(4009),g=n(8614);const k=r.__,{useHistory:f}=g.Z,b=newspack_aux_data.is_e2e?12:40,v=b+3,y={plugin_configuration:{message:k("Installation","newspack")},starter_content:{message:k("Demo content","newspack")}},E=e=>i()({path:`/newspack/v1/wizard/newspack-setup-wizard/starter-content/${e}`,method:"post"}),_=(0,h.a4)((e=>{let{buttonAction:t}=e;const[n,r]=(0,a.useState)(0),[o,p]=(0,a.useState)([]),[g,_]=(0,a.useState)(null),[S,x]=(0,a.useState)(!0),[T,C]=(0,a.useState)([]),[P,N]=(0,a.useState)(""),[z,M]=(0,a.useState)(),F="generated"===z,A="import"===z,O=e=>t=>C((n=>[...n,{...e,error:t}])),j=(S?v:0)+o.length;(0,a.useEffect)((()=>(document.body.classList.add("newspack-wizard__welcome","newspack-wizard__blue"),i()({path:"/newspack/v1/wizard/newspack-setup-wizard/initial-check/"}).then((e=>{p(e.plugins),_(e.is_ssl)})),()=>document.body.classList.remove("newspack-wizard__welcome","newspack-wizard__blue"))),[]);const W=()=>r((e=>e+1)),Z=async()=>{C([]),r(0),await new Promise((e=>setTimeout(e,1)));const e=o.map((e=>"active"===e.Status?(W(),()=>Promise.resolve()):()=>i()({path:`/newspack/v1/plugins/${e.Slug}/configure/`,method:"POST"}).then(W).catch(O({info:y.plugin_configuration,item:`${k("Failed to install","newspack")} ${e.Name}`}))));for(let t=0;t<e.length;t++)await e[t]();(F&&S||A)&&(await function(e){let t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"";return i()({path:"/newspack/v1/wizard/newspack-setup-wizard/starter-content/init",method:"post",data:{type:e,site:t}})}(z,P).then(W).catch((e=>{window.location="/wp-admin/admin.php?page=newspack-setup-wizard&newspack-notice=_error_"+e.message})),await Promise.allSettled((0,s.times)(b,(e=>E(`post/${e}`).then(W).catch(O({info:y.starter_content,item:k("Failed to create a post.","newspack")}))))),await E("homepage").then(W).catch(O({info:y.starter_content,item:k("Failed to create the homepage.","newspack")})),await E("theme").then(W).catch(O({info:y.starter_content,item:k("Failed to activate the theme.","newspack")})))},H=f(),I=t.href,R=T.length>0,B=0===n,$=n===j&&!R,L=(0,a.useRef)(),[U,V]=(0,a.useState)(5);(0,a.useEffect)((()=>{0===U&&(clearInterval(L.current),H.push(I.replace("#","")))}),[U]),(0,a.useEffect)((()=>{$&&5===U&&(L.current=setInterval((()=>{V((e=>e-1))}),1e3))}),[$,U]);return(0,a.createElement)(a.Fragment,null,B&&(0,a.createElement)(h.rj,{columns:1,gutter:8},(0,a.createElement)(h.VQ,{simple:!0,size:36,white:!0}),(0,a.createElement)(h.M$,{title:()=>(0,a.createElement)(a.Fragment,null,k("Welcome to Newspack,","newspack"),(0,a.createElement)("br",null),k("the platform for news","newspack")),heading:1,centered:!0,isWhite:!0,noMargin:!0})),(0,a.createElement)(h.Zb,{isNarrow:!0,isWhite:!0,className:0===T.length&&n>0&&!$?"loading":null},!B&&(0,a.createElement)("h1",null,R?(0,a.createElement)(l.Z,{className:"newspack--error",icon:c.Z}):$?(0,a.createElement)("span",{className:"newspack-checkbox-icon newspack-checkbox-icon--checked"},(0,a.createElement)(l.Z,{icon:u.Z})):void 0,k(R?"Installation error":$?"Installation complete":"Installing…","newspack")),0===T.length&&n>0?(0,a.createElement)(h.ko,{completed:n,total:j}):null,(0,a.createElement)("p",null,k(R?"There has been an error during the installation. Please retry or manually install required plugins to continue with the configuration of your site.":B?"We will help you get set up by installing the most relevant plugins first before requiring a few details from you in order to build your site.":$?"Click the button below to start configuring your site.":S?"We are now installing core plugins and pre-populating your site with categories and placeholder stories to help you pre-configure it. All placeholder content can be deleted later.":"We are now installing core plugins.","newspack"),$&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)("br",null),(0,a.createElement)("i",null,k("Automatic redirection in","newspack")," ",U," ",k("seconds…","newspack")))),!1===g&&(0,a.createElement)(h.qX,{isError:!0,noticeText:k("This site does not use HTTPS. Newspack can't be installed.","newspack")}),T.length?T.map(((e,t)=>(0,a.createElement)(h.fM,{isSmall:!0,key:t,title:e.info.message+": "+e.item,actionText:k("Retry","newspack"),onClick:Z,secondaryActionText:k("Skip","newspack"),onSecondaryActionClick:()=>(e=>{const t=[];for(let n=0;n<T.length;++n)e!==n&&t.push(T[n]);C(t),W()})(t),className:"newspack--error-actioncard"}))):null,(B||$)&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)(h.C0,null),B&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)(h.Wu,{href:"#",title:k("Start a new site","newspack"),desc:k("You don't have content to import","newspack"),icon:d,className:"br--top",isPressed:F,onClick:()=>M("generated"),grouped:!0}),(0,a.createElement)(h.Wu,{href:"#",title:k("Migrate an existing WordPress site","newspack"),desc:k("You have content to import","newspack"),icon:m.Z,className:"br--bottom",isPressed:A,onClick:()=>M("import"),grouped:!0}),A&&(0,a.createElement)(h.w4,{label:k("URL","newspack"),placeholder:"https://yourgroovydomain.com/",type:"url",help:k("We will import the last 50 articles from your existing site to help you with the set up and customization.","newspack"),onChange:e=>N(e)})),(0,a.createElement)(h.Zb,{noBorder:!0,className:"newspack-card__footer"},B&&F&&(0,a.createElement)(h.Z8,{checked:S,label:k("Install demo content","newspack"),onChange:x}),B&&(F||A)&&(0,a.createElement)(h.zx,{disabled:!g||A&&!(0,w.isURL)(P),isPrimary:!0,onClick:Z},k("Get Started","newspack")),!B&&(0,a.createElement)(h.zx,{disabled:!g,isPrimary:!0,href:$?I:null},k("Continue","newspack"))))))})),S=r.__,x=document.title.replace(newspack_aux_data.site_title,"__SITE_TITLE__");var T=(0,h.a4)((e=>{let{setError:t,wizardApiFetch:n,renderPrimaryButton:r}=e;const[{currencies:s=[],countries:o=[],wpseoFields:l=[]},c]=(0,a.useState)({}),[u,p]=h.PT.useObjectState({});(0,a.useEffect)((()=>{n({path:"/newspack/v1/profile/",method:"GET"}).then((e=>{c({currencies:e.currencies,countries:e.countries,wpseoFields:e.wpseo_fields}),p(e.profile)})).catch(t)}),[]),(0,a.useEffect)((()=>{"string"==typeof u.site_title&&(document.title=x.replace("__SITE_TITLE__",u.site_title))}),[u.site_title]);const d=e=>{let{options:t,label:n,key:r,type:s,placeholder:o,className:i}=e;return t?(0,a.createElement)(h.Yw,{label:n,value:u[r],onChange:p(r),options:t,className:i}):"image"===s?(0,a.createElement)(h.Ur,{label:n,style:{width:"136px",height:"136px"},image:u[r],info:S("The Site Icon is used as a browser and app icon for your site. Icons must be square, and at least 512 pixels wide and tall.","newspack"),isCovering:!0,onChange:p(r)}):(0,a.createElement)(h.w4,{label:n,value:u[r]||"",onChange:p(r),placeholder:o,className:i})};return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(h.M$,{title:S("Site Profile","newspack"),description:S("Add and manage the basic information","newspack")}),(0,a.createElement)(h.rj,{columns:3,gutter:32,className:"newspack-site-profile"},d({key:"site_icon",label:S("Site Icon","newspack"),type:"image"}),(0,a.createElement)(h.Zb,{noBorder:!0},d({key:"site_title",label:S("Site Title","newspack")}),d({key:"tagline",label:S("Tagline","newspack")})),(0,a.createElement)(h.Zb,{noBorder:!0},d({options:o,key:"countrystate",label:S("Country","newspack")}),d({options:s,key:"currency",label:S("Currency")}))),(0,a.createElement)(h.M$,{title:S("Social Accounts","newspack"),description:S("Allow visitors to quickly access your social profiles","newspack")}),(0,a.createElement)(h.rj,{columns:3,gutter:32,rowGap:16},l.map((e=>(0,a.createElement)(a.Fragment,{key:e.key},d({...e}))))),(0,a.createElement)("div",{className:"newspack-buttons-card"},r({onClick:()=>i()({path:"/newspack/v1/profile/",method:"POST",data:{profile:u}})})))}),{hidePrimaryButton:!0}),C=n(4184),P=n.n(C),N=n(9818),z=n(7405);const M=r.__,F=()=>{const e=h.en.useWizardData("reader-revenue"),{suggestedAmounts:t=[0,0,0],suggestedAmountUntiered:n=0,currencySymbol:r="$",tiered:s=!1}=e.donation_data||{},{updateWizardSettings:o}=(0,N.useDispatch)(h.en.STORE_NAMESPACE),i=e=>t=>o({slug:"newspack-reader-revenue-wizard",path:["donation_data",...e],value:t});return(0,a.createElement)(a.Fragment,null,(0,a.createElement)("h2",null,M("Suggested donations")),(0,a.createElement)("p",null,M("Set suggested monthly donation amounts. The one-time and annual suggested donation amount will be adjusted according to the monthly amount.")),(0,a.createElement)(h.hp,{label:M("Set exact monthly donation tiers"),checked:s,onChange:i(["tiered"])}),s?(0,a.createElement)(h.rj,{columns:3,gutter:8},(0,a.createElement)(z.P,{currencySymbol:r,label:M("Low-tier"),value:t[0],onChange:i(["suggestedAmounts",0])}),(0,a.createElement)(z.P,{currencySymbol:r,label:M("Mid-tier"),value:t[1],onChange:i(["suggestedAmounts",1])}),(0,a.createElement)(z.P,{currencySymbol:r,label:M("High-tier"),value:t[2],onChange:i(["suggestedAmounts",2])})):(0,a.createElement)(z.P,{currencySymbol:r,label:M("Suggested donation amount per month"),value:n,onChange:i(["suggestedAmountUntiered"])}))};var A=n(5609);const O=r.__,{SettingsCard:j}=h.Zr,W=()=>{const e=h.en.useWizardData("reader-revenue"),{testMode:t=!1,publishableKey:n="",secretKey:r="",testPublishableKey:s="",testSecretKey:o=""}=e.stripe_data||{},{updateWizardSettings:i}=(0,N.useDispatch)(h.en.STORE_NAMESPACE),l=e=>t=>i({slug:"newspack-reader-revenue-wizard",path:["stripe_data",e],value:t});return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(h.Zb,{noBorder:!0},(0,a.createElement)("p",{className:"newspack-payment-setup-screen__api-keys-instruction"},O("Configure Stripe and enter your API keys","newspack")," – ",(0,a.createElement)(A.ExternalLink,{href:"https://stripe.com/docs/keys#api-keys"},O("learn how"))),(0,a.createElement)(h.Z8,{label:O("Use Stripe in test mode"),checked:t,onChange:l("testMode"),tooltip:"Test mode will not capture real payments. Use it for testing your purchase flow."})),(0,a.createElement)(h.rj,{noMargin:!0},t?(0,a.createElement)(a.Fragment,null,(0,a.createElement)(h.w4,{type:"password",value:s,label:O("Test Publishable Key"),onChange:l("testPublishableKey")}),(0,a.createElement)(h.w4,{type:"password",value:o,label:O("Test Secret Key"),onChange:l("testSecretKey")})):(0,a.createElement)(a.Fragment,null,(0,a.createElement)(h.w4,{type:"password",value:n,label:O("Publishable Key"),onChange:l("publishableKey")}),(0,a.createElement)(h.w4,{type:"password",value:r,label:O("Secret Key"),onChange:l("secretKey")}))))},Z=r.__;var H=n(7462),I=n(932);const R=r.__,B=e=>{let{className:t,onUpdate:n,isOnboarding:r=!0}=e;const[o,l]=(0,a.useState)(),[c,u]=h.PT.useObjectState({}),p=e=>{u(e),n&&n((0,s.mapValues)(e.settings,(0,s.property)("value")))},d=()=>{i()({path:"/newspack/v1/wizard/newspack-engagement-wizard/newsletters"}).then(p).catch(l)};(0,a.useEffect)(d,[]);const m=e=>{var t,n,a,r,s,o;return{value:(null===(t=c.settings[e])||void 0===t?void 0:t.value)||"",checked:Boolean(null===(n=c.settings[e])||void 0===n?void 0:n.value),label:null===(a=c.settings[e])||void 0===a?void 0:a.description,placeholder:null===(r=c.settings[e])||void 0===r?void 0:r.placeholder,options:(null===(s=c.settings[e])||void 0===s||null===(o=s.options)||void 0===o?void 0:o.map((e=>({value:e.value,label:e.name}))))||null,onChange:t=>p({settings:{[e]:{value:t}}})}};return!o&&(0,s.isEmpty)(c)?(0,a.createElement)("div",{className:"flex justify-around mt4"},(0,a.createElement)(h.Pi,null)):o?(0,a.createElement)(h.qX,{noticeText:o.message||R("Something went wrong.","newspack"),isError:!0}):(0,a.createElement)("div",{className:t},!1===c.configured&&(0,a.createElement)(h.xf,{plugins:["newspack-newsletters"],withoutFooterButton:!0,onStatus:e=>{let{complete:t}=e;return t&&d()}}),!0===c.configured&&(()=>{const e=m("newspack_newsletters_service_provider");return(0,a.createElement)(h.rj,{gutter:32,columns:1},(0,s.values)(c.settings).filter((t=>!t.provider||t.provider===e.value)).map((e=>{if(r&&!e.onboarding)return null;switch(e.type){case"select":return(0,a.createElement)(h.Yw,(0,H.Z)({key:e.key},m(e.key)));case"checkbox":return(0,a.createElement)(h.Z8,(0,H.Z)({key:e.key},m(e.key)));default:return(0,a.createElement)(h.rj,{columns:1,gutter:"0",key:e.key},(0,a.createElement)(h.w4,m(e.key)),e.help&&e.helpURL&&(0,a.createElement)("p",null,(0,a.createElement)(A.ExternalLink,{href:e.helpURL},e.help)))}})))})())},$=()=>{const[{status:e,url:t,error:n,newslettersConfig:r},s]=h.PT.useObjectState({});(0,a.useEffect)((()=>{(0,I.B)().then(s)}),[]);const o="active"===e;return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(h.M$,{title:R("Signup forms","newspack"),description:()=>(0,a.createElement)(a.Fragment,null,R("Connects your site to Mailchimp and sets up a Mailchimp block you can use to get new subscribers for your newsletter","newspack"),(0,a.createElement)("br",null),R("The Mailchimp connection to your site for this feature is managed through Jetpack and WordPress.com","newspack"),(0,a.createElement)("br",null),t?(0,a.createElement)(A.ExternalLink,{href:t},R(o?"Manage your Mailchimp connection":"Set up Mailchimp")):null)}),o&&(0,a.createElement)(h.qX,{noticeText:R("You can insert newsletter sign up forms in your content using the Mailchimp block."),isSuccess:!0}),"unavailable_site_id"===(null==n?void 0:n.code)&&(0,a.createElement)(h.qX,{noticeText:R("Connect Jetpack in order to configure Mailchimp."),isWarning:!0}),(0,a.createElement)(h.M$,{title:R("Authoring","newspack")}),(0,a.createElement)(B,{isOnboarding:!1,onUpdate:e=>s({newslettersConfig:e})}),(0,a.createElement)("div",{className:"newspack-buttons-card"},(0,a.createElement)(h.zx,{isPrimary:!0,onClick:async()=>i()({path:"/newspack/v1/wizard/newspack-engagement-wizard/newsletters",method:"POST",data:r})},R("Save","newspack"))))};(0,h.a4)((()=>(0,a.createElement)(a.Fragment,null,(0,a.createElement)($,null),(0,a.createElement)(h.M$,{title:R("WooCommerce integration","newspack")})," ",(0,a.createElement)(h.xf,{plugins:["mailchimp-for-woocommerce"],withoutFooterButton:!0}))));const L=r.__,U={"reader-revenue":{label:L("Reader Revenue","newspack"),description:L("Encourage site visitors to contribute to your publishing through donations","newspack"),Component:e=>{var t;let{className:n}=e;const r=h.en.useWizardData("reader-revenue");return(0,a.createElement)("div",{className:P()(n,{"o-50":(0,s.isEmpty)(r)})},"nrh"===(null===(t=r.platform_data)||void 0===t?void 0:t.platform)?(0,a.createElement)("p",null,Z("Looks like this Newspack instance is already configured to use News Revenue Hub as the Reader Revenue platform. To edit these settings, visit the Reader Revenue section from the Newspack dashboard.","newspack")):(0,a.createElement)(a.Fragment,null,(0,a.createElement)(F,null),(0,a.createElement)("h2",null,Z("Payment gateway","newspack")),(0,a.createElement)(W,null)))},configuration:{is_service_enabled:!1}},newsletters:{label:L("Newsletters","newspack"),description:L("Create email newsletters and send them to your mail lists, all without leaving your website","newspack"),Component:B,configuration:{is_service_enabled:!1}},"google-ad-manager":{label:L("Google Ad Manager","newspack"),description:L("An advanced ad inventory creation and management platform, allowing you to be specific about ad placements","newspack"),configuration:{is_service_enabled:!1}}};var V=(0,h.a4)((e=>{let{renderPrimaryButton:t}=e;const[n,r]=h.PT.useObjectState(U),[o,l]=(0,a.useState)(!0),c=(0,s.keys)(n),u=h.en.useWizardData("reader-revenue");return(0,a.useEffect)((()=>{i()({path:"/newspack/v1/wizard/newspack-setup-wizard/services"}).then((e=>{r(e),l(!1)}))}),[]),(0,a.createElement)(a.Fragment,null,(0,s.values)(n).map(((e,t)=>{const n=c[t],s=e.Component;return(0,a.createElement)(h.fM,{isMedium:!0,key:t,title:e.label,description:e.description,className:n,toggleChecked:e.configuration.is_service_enabled,hasGreyHeader:e.configuration.is_service_enabled,toggleOnChange:e=>r({[n]:{configuration:{is_service_enabled:e}}}),disabled:o,href:e.configuration.is_service_enabled&&e.href,actionText:e.configuration.is_service_enabled&&e.actionText},e.configuration.is_service_enabled&&s?(0,a.createElement)(s,{className:"newspack-action-card__region-children__inner",configuration:e.configuration,onUpdate:e=>r({[n]:{configuration:e}})}):null)})),(0,a.createElement)("div",{className:"newspack-buttons-card"},t({onClick:async()=>{const e=(0,s.mapValues)(n,(0,s.property)("configuration"));return e["reader-revenue"]={...e["reader-revenue"],...u},i()({path:"/newspack/v1/wizard/newspack-setup-wizard/services",method:"POST",data:e})}})))}),{hidePrimaryButton:!0}),K=n(3741);const q=r.__;var D=(0,h.a4)((()=>{(0,a.useEffect)((()=>(document.body.classList.add("newspack-wizard__completed","newspack-wizard__blue"),document.querySelector(".newspack-wizard__header").remove(),()=>document.body.classList.remove("newspack-wizard__completed","newspack-wizard__blue"))),[]);const e=P()("flex","flex-column","justify-between"),t=P()("flex","flex-row-reverse");return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(h.Zb,{noBorder:!0},(0,a.createElement)(h.M$,{title:q("You’re ready to go!","newspack"),description:q("While you’re off to a great start, there’s plenty more you can do:","newspack"),heading:1,centered:!0,isWhite:!0})),(0,a.createElement)(h.Zb,{isWhite:!0},(0,a.createElement)(h.rj,null,(0,a.createElement)(h.fM,{title:q("Configure Newspack","newspack"),description:q("Go in-depth with our various options to set up Newspack to meet your needs.","newspack"),className:e},(0,a.createElement)("div",{className:t},(0,a.createElement)(h.zx,{isPrimary:!0,isSmall:!0,href:newspack_urls.dashboard},q("Go to Dashboard","newspack")))),(0,a.createElement)(h.fM,{title:q("Explore our documentation","newspack"),description:q("Read about the different tools, plugins, and themes that make up Newspack.","newspack"),className:e},(0,a.createElement)("div",{className:t},(0,a.createElement)(h.zx,{isPrimary:!0,isSmall:!0,href:newspack_urls.support},q("Read Documentation","newspack")))),(0,a.createElement)(h.fM,{title:q("Update your homepage","newspack"),description:q("We’ve created the basics, now it’s time to update the content.","newspack"),className:e},(0,a.createElement)("div",{className:t},(0,a.createElement)(h.zx,{isPrimary:!0,isSmall:!0,href:newspack_urls.homepage},q("Edit Homepage","newspack")))),(0,a.createElement)(h.fM,{title:q("View your site","newspack"),description:q("Preview what you’ve created so far. It looks great!","newspack"),className:e},(0,a.createElement)("div",{className:t},(0,a.createElement)(h.zx,{isPrimary:!0,isSmall:!0,href:newspack_urls.site},q("Visit Site","newspack")))))))}),{hidePrimaryButton:!0});const G=r.__,{HashRouter:Y,Route:X}=g.Z,J=[{path:"/",label:G("Welcome","newspack"),render:e=>(0,a.createElement)(_,(0,s.omit)(e,["routes","headerText","buttonText"])),isHiddenInNav:!0},{path:"/settings",label:G("Settings","newspack"),subHeaderText:G("Share a few details so we can start setting up your site","newspack"),render:T},{path:"/services",label:G("Services","newspack"),subHeaderText:G("Activate and configure the services that you need","newspack"),render:V},{path:"/design",label:G("Design","newspack"),subHeaderText:G("Customize the look and feel of your site","newspack"),render:K.Z},{path:"/completed",label:G("Completed","newspack"),render:D,isHiddenInNav:!0}];(0,a.render)((0,a.createElement)((0,h.uF)((e=>{let{wizardApiFetch:t,setError:n}=e;return(0,a.createElement)(a.Fragment,null,newspack_aux_data.has_completed_setup&&(0,a.createElement)(h.qX,{isWarning:!0,className:"ma0"},G("Heads up! The setup has already been completed. No need to run it again.","newspack")),(0,a.createElement)(Y,{hashType:"slash"},J.map(((e,r)=>{var s;const o=null===(s=J[r+1])||void 0===s?void 0:s.path,i=o?{href:"#"+o}:{};return(0,a.createElement)(X,{key:r,path:e.path,exact:"/"===e.path,render:()=>e.render({wizardApiFetch:t,setError:n,disableUpcomingInTabbedNavigation:!0,tabbedNavigation:J,headerText:e.label,subHeaderText:e.subHeaderText,buttonText:o?e.buttonText||G("Continue"):G("Finish"),buttonAction:i})})}))))}),[]),{simpleFooter:!0}),document.getElementById("newspack-setup-wizard"))},9196:function(e){e.exports=window.React},2819:function(e){e.exports=window.lodash},6292:function(e){e.exports=window.moment},6989:function(e){e.exports=window.wp.apiFetch},5609:function(e){e.exports=window.wp.components},9818:function(e){e.exports=window.wp.data},9307:function(e){e.exports=window.wp.element},2694:function(e){e.exports=window.wp.hooks},2629:function(e){e.exports=window.wp.htmlEntities},5736:function(e){e.exports=window.wp.i18n},9630:function(e){e.exports=window.wp.keycodes},444:function(e){e.exports=window.wp.primitives},6483:function(e){e.exports=window.wp.url}},n={};function a(e){var r=n[e];if(void 0!==r)return r.exports;var s=n[e]={exports:{}};return t[e].call(s.exports,s,s.exports,a),s.exports}a.m=t,e=[],a.O=function(t,n,r,s){if(!n){var o=1/0;for(u=0;u<e.length;u++){n=e[u][0],r=e[u][1],s=e[u][2];for(var i=!0,l=0;l<n.length;l++)(!1&s||o>=s)&&Object.keys(a.O).every((function(e){return a.O[e](n[l])}))?n.splice(l--,1):(i=!1,s<o&&(o=s));if(i){e.splice(u--,1);var c=r();void 0!==c&&(t=c)}}return t}s=s||0;for(var u=e.length;u>0&&e[u-1][2]>s;u--)e[u]=e[u-1];e[u]=[n,r,s]},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,{a:t}),t},a.d=function(e,t){for(var n in t)a.o(t,n)&&!a.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},a.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.j=350,function(){var e;a.g.importScripts&&(e=a.g.location+"");var t=a.g.document;if(!e&&t&&(t.currentScript&&(e=t.currentScript.src),!e)){var n=t.getElementsByTagName("script");n.length&&(e=n[n.length-1].src)}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),a.p=e}(),function(){var e={350:0};a.O.j=function(t){return 0===e[t]};var t=function(t,n){var r,s,o=n[0],i=n[1],l=n[2],c=0;if(o.some((function(t){return 0!==e[t]}))){for(r in i)a.o(i,r)&&(a.m[r]=i[r]);if(l)var u=l(a)}for(t&&t(n);c<o.length;c++)s=o[c],a.o(e,s)&&e[s]&&e[s][0](),e[o[c]]=0;return a.O(u)},n=self.webpackChunkwebpack=self.webpackChunkwebpack||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))}();var r=a.O(void 0,[351],(function(){return a(5177)}));r=a.O(r);var s=window;for(var o in r)s[o]=r[o];r.__esModule&&Object.defineProperty(s,"__esModule",{value:!0})}();