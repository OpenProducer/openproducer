(()=>{var e,t={9605:(e,t,n)=>{"use strict";n(8209);var s=n(6087),a=n(7723),i=n(179),r=n.n(i),o=n(6638),l=n.n(o),c=n(1455),d=n.n(c),p=n(7677),u=n(8851),h=n(8351),_=n(5573),w=n(790);const g=(0,w.jsx)(_.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",children:(0,w.jsx)(_.Path,{d:"M18.5 5.5V8H20V5.5h2.5V4H20V1.5h-1.5V4H16v1.5h2.5zM12 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-6h-1.5v6a.5.5 0 01-.5.5H6a.5.5 0 01-.5-.5V6a.5.5 0 01.5-.5h6V4z"})});var m=n(7209),k=n(3832),x=n(6427),b=n(1727),v=n(216);const{useHistory:y}=v.A,f=newspack_aux_data.is_e2e?12:40,j=f+3,S={plugin_configuration:{message:(0,a.__)("Installation","newspack")},starter_content:{message:(0,a.__)("Starter content","newspack")}},C=e=>d()({path:`/newspack/v1/wizard/newspack-setup-wizard/starter-content/${e}`,method:"post"}),A=(0,b.c_)((({buttonAction:e})=>{const[t,n]=(0,s.useState)(0),[i,r]=(0,s.useState)([]),[o,c]=(0,s.useState)(null),[_,v]=(0,s.useState)(!0),[A,T]=(0,s.useState)([]),[z,N]=(0,s.useState)(""),[L,P]=(0,s.useState)(),E="generated"===L,F="import"===L,O=e=>t=>T((n=>[...n,{...e,error:t}])),W=(_?j:0)+i.length;(0,s.useEffect)((()=>(document.body.classList.add("newspack-wizard__welcome","newspack-wizard__blue"),d()({path:"/newspack/v1/wizard/newspack-setup-wizard/initial-check/"}).then((e=>{r(e.plugins),c(e.is_ssl)})),()=>document.body.classList.remove("newspack-wizard__welcome","newspack-wizard__blue"))),[]);const H=()=>n((e=>e+1)),M=async()=>{T([]),n(0),await new Promise((e=>setTimeout(e,1)));const e=i.map((e=>"active"===e.Status?(H(),()=>Promise.resolve()):()=>d()({path:`/newspack/v1/plugins/${e.Slug}/configure/`,method:"POST"}).then(H).catch(O({info:S.plugin_configuration,item:`${(0,a.__)("Failed to install","newspack")} ${e.Name}`}))));for(let t=0;t<e.length;t++)await e[t]();(E&&_||F)&&(await((e,t="")=>d()({path:"/newspack/v1/wizard/newspack-setup-wizard/starter-content/init",method:"post",data:{type:e,site:t}}))(L,z).then(H).catch((e=>{window.location="/wp-admin/admin.php?page=newspack-setup-wizard&newspack-notice=_error_"+e.message})),await Promise.allSettled(l()(f,(e=>C(`post/${e}`).then(H).catch(O({info:S.starter_content,item:(0,a.__)("Failed to create a post.","newspack")}))))),await C("homepage").then(H).catch(O({info:S.starter_content,item:(0,a.__)("Failed to create the homepage.","newspack")})),await C("theme").then(H).catch(O({info:S.starter_content,item:(0,a.__)("Failed to activate the theme.","newspack")})))},R=y(),$=e.href,I=A.length>0,B=0===t,D=t===W&&!I,U=(0,s.useRef)(),[G,q]=(0,s.useState)(5);(0,s.useEffect)((()=>{0===G&&(clearInterval(U.current),R.push($.replace("#","")))}),[G]),(0,s.useEffect)((()=>{D&&5===G&&(U.current=setInterval((()=>{q((e=>e-1))}),1e3))}),[D,G]);return(0,w.jsxs)(w.Fragment,{children:[B&&(0,w.jsxs)(b.xA,{columns:1,gutter:8,children:[(0,w.jsx)(b.qV,{simple:!0,size:36,white:!0}),(0,w.jsx)(b.X3,{title:()=>(0,w.jsxs)(w.Fragment,{children:[(0,a.__)("Welcome to Newspack,","newspack"),(0,w.jsx)("br",{}),(0,a.__)("the platform for news","newspack")]}),heading:1,centered:!0,isWhite:!0,noMargin:!0})]}),(0,w.jsx)(b.Zp,{isNarrow:!0,isWhite:!0,className:0===A.length&&t>0&&!D?"loading":null,children:(0,w.jsxs)(b.xA,{columns:1,children:[!B&&(0,w.jsxs)("h1",{children:[I?(0,w.jsx)(p.A,{className:"newspack--error",icon:u.A}):D?(0,w.jsx)("span",{className:"newspack-checkbox-icon newspack-checkbox-icon--checked",children:(0,w.jsx)(p.A,{icon:h.A})}):void 0,I?(0,a.__)("Installation error","newspack"):D?(0,a.__)("Installation complete","newspack"):(0,a.__)("Installing…","newspack")]}),0===A.length&&t>0?(0,w.jsx)(b.z2,{completed:t,total:W}):null,(0,w.jsxs)("p",{children:[I?(0,a.__)("There has been an error during the installation. Please retry or manually install required plugins to continue with the configuration of your site.","newspack"):B?(0,a.__)("We will help you get set up by installing the most relevant plugins first before requiring a few details from you in order to build your site.","newspack"):D?(0,a.__)("Click the button below to start configuring your site.","newspack"):_?(0,a.__)("We are now installing core plugins and pre-populating your site with categories and placeholder stories to help you pre-configure it. All placeholder content can be deleted later.","newspack"):(0,a.__)("We are now installing core plugins.","newspack"),D&&(0,w.jsxs)(w.Fragment,{children:[(0,w.jsx)("br",{}),(0,w.jsxs)("i",{children:[(0,a.__)("Automatic redirection in","newspack")," ",G," ",(0,a.__)("seconds…","newspack")]})]})]}),!1===o&&(0,w.jsx)(b.$T,{isError:!0,noticeText:(0,a.__)("This site does not use HTTPS. Newspack can't be installed.","newspack")}),A.length?A.map(((e,t)=>(0,w.jsx)(b.Lz,{isSmall:!0,title:e.info.message+": "+e.item,actionText:(0,a.__)("Retry","newspack"),onClick:M,secondaryActionText:(0,a.__)("Skip","newspack"),secondaryDestructive:!0,onSecondaryActionClick:()=>(e=>{const t=[];for(let n=0;n<A.length;++n)e!==n&&t.push(A[n]);T(t),H()})(t),className:"newspack--error-actioncard"},t))):null,(B||D)&&(0,w.jsxs)(w.Fragment,{children:[(0,w.jsx)(b.Du,{}),B&&(0,w.jsxs)(w.Fragment,{children:[(0,w.jsxs)(b.Zp,{noBorder:!0,children:[(0,w.jsx)(b.dr,{href:"#",title:(0,a.__)("Start a new site","newspack"),desc:(0,a.__)("You don't have content to import","newspack"),icon:g,className:"br--top",isPressed:E,onClick:()=>P("generated"),grouped:!0}),(0,w.jsx)(b.dr,{href:"#",title:(0,a.__)("Migrate an existing WordPress site","newspack"),desc:(0,a.__)("You have content to import","newspack"),icon:m.A,className:"br--bottom",isPressed:F,onClick:()=>P("import"),grouped:!0})]}),F&&(0,w.jsx)(b.xt,{label:(0,a.__)("URL","newspack"),placeholder:"https://yourgroovydomain.com/",type:"url",help:(0,a.__)("We will import the last 50 articles from your existing site to help you with the set up and customization.","newspack"),onChange:e=>N(e)})]}),(0,w.jsxs)(b.Zp,{noBorder:!0,className:"newspack-card__footer",children:[B&&E&&(0,w.jsx)(x.CheckboxControl,{checked:_,label:(0,a.__)("Install starter content","newspack"),onChange:v}),B&&(E||F)&&(0,w.jsx)(b.$n,{disabled:!o||F&&!(0,k.isURL)(z),isPrimary:!0,onClick:M,children:(0,a.__)("Get Started","newspack")}),!B&&(0,w.jsx)(b.$n,{disabled:!o,isPrimary:!0,href:D?$:null,children:(0,a.__)("Continue","newspack")})]})]})]})})]})})),T=document.title.replace(newspack_aux_data.site_title,"__SITE_TITLE__"),z=(0,b.c_)((({setError:e,wizardApiFetch:t,renderPrimaryButton:n})=>{const[{currencies:i=[],countries:r=[],wpseoFields:o=[]},l]=(0,s.useState)({}),[c,p]=b.JL.useObjectState({});(0,s.useEffect)((()=>{t({path:"/newspack/v1/profile/",method:"GET"}).then((e=>{l({currencies:e.currencies,countries:e.countries,wpseoFields:e.wpseo_fields}),p(e.profile)})).catch(e)}),[]),(0,s.useEffect)((()=>{"string"==typeof c.site_title&&(document.title=T.replace("__SITE_TITLE__",c.site_title))}),[c.site_title]);const u=({options:e,label:t,key:n,type:s,placeholder:a,className:i})=>e?(0,w.jsx)(b.Yq,{label:t,value:c[n],onChange:p(n),options:e,className:i}):"image"===s?(0,w.jsx)(b.BR,{label:t,style:{width:"102px",height:"102px"},image:c[n],isCovering:!0,onChange:p(n)}):(0,w.jsx)(b.xt,{label:t,value:c[n]||"",onChange:p(n),placeholder:a,className:i});return(0,w.jsxs)(s.Fragment,{children:[(0,w.jsx)(b.X3,{title:(0,a.__)("Site Profile","newspack"),description:(0,a.__)("Add and manage the basic information","newspack")}),(0,w.jsxs)(b.xA,{columns:3,gutter:32,rowGap:16,className:"newspack-site-profile",children:[u({key:"site_icon",label:(0,a.__)("Site Icon","newspack"),type:"image"}),(0,w.jsxs)(b.xA,{columns:1,gutter:16,children:[u({key:"site_title",label:(0,a.__)("Site Title","newspack")}),u({key:"tagline",label:(0,a.__)("Tagline","newspack")})]}),(0,w.jsxs)(b.xA,{columns:1,gutter:16,children:[u({options:r,key:"countrystate",label:(0,a.__)("Country","newspack")}),u({options:i,key:"currency",label:(0,a.__)("Currency")})]})]}),(0,w.jsx)(b.X3,{title:(0,a.__)("Social Accounts","newspack"),description:(0,a.__)("Allow visitors to quickly access your social profiles","newspack")}),(0,w.jsx)(b.xA,{columns:3,gutter:32,rowGap:16,children:o.map((e=>(0,w.jsx)(s.Fragment,{children:u({...e})},e.key)))}),(0,w.jsx)("div",{className:"newspack-buttons-card",children:n({onClick:()=>d()({path:"/newspack/v1/profile/",method:"POST",data:{profile:c}})})})]})}),{hidePrimaryButton:!0});var N=n(5880),L=n.n(N),P=n(5950),E=n.n(P),F=n(3916),O=n.n(F),W=n(583),H=n.n(W),M=n(6942),R=n.n(M),$=n(2193),I=n.n($),B=n(7143),D=n(7948);const U={once:{tieredLabel:(0,a.__)("One-time donations"),staticLabel:(0,a.__)("Suggested one-time donation amount")},month:{tieredLabel:(0,a.__)("Monthly donations"),staticLabel:(0,a.__)("Suggested donation amount per month")},year:{tieredLabel:(0,a.__)("Annual donations"),staticLabel:(0,a.__)("Suggested donation amount per year")}},G=Object.keys(U),q=()=>{const e=b.sQ.useWizardData("reader-revenue"),{updateWizardSettings:t}=(0,B.useDispatch)(b.sQ.STORE_NAMESPACE);if(!e.donation_data||"errors"in e.donation_data)return null;const{amounts:n,currencySymbol:s,tiered:i,disabledFrequencies:r,minimumDonation:o}=e.donation_data,l=e=>n=>t({slug:"newspack-reader-revenue-wizard",path:["donation_data",...e],value:n}),c=G.map((e=>({key:e,...U[e]}))),d=parseFloat(o),p=window.newspack_reader_revenue?.can_use_name_your_price;return(0,w.jsxs)(w.Fragment,{children:[(0,w.jsxs)(b.Zp,{headerActions:!0,noBorder:!0,children:[(0,w.jsx)(b.X3,{title:(0,a.__)("Suggested Donations","newspack-plugin"),description:(0,a.__)("Set suggested donation amounts. These will be the default settings for the Donate block.","newspack-plugin"),noMargin:!0}),p&&(0,w.jsx)(b.Yq,{label:(0,a.__)("Donation Type","newspack-plugin"),onChange:()=>l(["tiered"])(!i),buttonOptions:[{value:!0,label:(0,a.__)("Tiered","newspack-plugin")},{value:!1,label:(0,a.__)("Untiered","newspack-plugin")}],buttonSmall:!0,value:i,hideLabelFromVision:!0})]}),i?(0,w.jsx)(b.xA,{columns:1,children:c.map((e=>{const t=r[e.key],i=Object.values(r).filter(Boolean).length===G.length-1;return(0,w.jsx)(b.Zp,{noBorder:!0,children:(0,w.jsxs)(b.xA,{columns:1,gutter:8,children:[(0,w.jsx)(x.ToggleControl,{checked:!t,onChange:()=>l(["disabledFrequencies",e.key])(!t),label:e.tieredLabel,disabled:!t&&i}),!t&&(0,w.jsxs)(b.xA,{columns:3,rowGap:16,children:[(0,w.jsx)(D.F,{currencySymbol:s,label:(0,a.__)("Low-tier"),error:n[e.key][0]<d?(0,a.__)("Warning: suggested donations should be at least the minimum donation amount.","newspack-plugin"):null,value:n[e.key][0],min:d,onChange:l(["amounts",e.key,0])}),(0,w.jsx)(D.F,{currencySymbol:s,label:(0,a.__)("Mid-tier"),error:n[e.key][1]<d?(0,a.__)("Warning: suggested donations should be at least the minimum donation amount.","newspack-plugin"):null,value:n[e.key][1],min:d,onChange:l(["amounts",e.key,1])}),(0,w.jsx)(D.F,{currencySymbol:s,label:(0,a.__)("High-tier"),error:n[e.key][2]<d?(0,a.__)("Warning: suggested donations should be at least the minimum donation amount.","newspack-plugin"):null,value:n[e.key][2],min:d,onChange:l(["amounts",e.key,2])})]})]})},e.key)}))}):(0,w.jsx)(b.xA,{columns:1,children:(0,w.jsx)(b.Zp,{noBorder:!0,children:(0,w.jsx)(b.xA,{columns:3,rowGap:16,children:c.map((e=>{const t=r[e.key],i=Object.values(r).filter(Boolean).length===G.length-1;return(0,w.jsxs)(b.xA,{columns:1,gutter:16,children:[(0,w.jsx)(x.ToggleControl,{checked:!t,onChange:()=>l(["disabledFrequencies",e.key])(!t),label:e.tieredLabel,disabled:!t&&i}),!t&&(0,w.jsx)(D.F,{currencySymbol:s,label:e.staticLabel,value:n[e.key][3],min:d,error:n[e.key][3]<d?(0,a.__)("Warning: suggested donations should be at least the minimum donation amount.","newspack-plugin"):null,onChange:l(["amounts",e.key,3])},e.key)]},e.key)}))})})}),(0,w.jsx)(b.xA,{columns:3,children:(0,w.jsx)(b.xt,{label:(0,a.__)("Minimum donation","newspack-plugin"),help:(0,a.__)("Set minimum donation amount. Setting a reasonable minimum donation amount can help protect your site from bot attacks.","newspack-plugin"),type:"number",min:1,value:d,onChange:e=>l(["minimumDonation"])(e)})})]})},{SettingsCard:K}=b.wB,V=()=>{const e=b.sQ.useWizardData("reader-revenue"),{testMode:t=!1,publishableKey:n="",secretKey:s="",testPublishableKey:i="",testSecretKey:r=""}=e.stripe_data||{},{updateWizardSettings:o}=(0,B.useDispatch)(b.sQ.STORE_NAMESPACE),l=e=>t=>o({slug:"newspack-reader-revenue-wizard",path:["stripe_data",e],value:t});return(0,w.jsxs)(b.xA,{columns:1,gutter:16,children:[(0,w.jsxs)(b.xA,{columns:1,gutter:16,children:[(0,w.jsxs)("p",{className:"newspack-payment-setup-screen__api-keys-instruction",children:[(0,a.__)("Configure Stripe and enter your API keys","newspack-plugin")," – ",(0,w.jsx)(x.ExternalLink,{href:"https://stripe.com/docs/keys#api-keys",children:(0,a.__)("learn how")})]}),(0,w.jsx)(x.CheckboxControl,{label:(0,a.__)("Use Stripe in test mode","newspack-plugin"),checked:t,onChange:l("testMode"),help:(0,a.__)("Test mode will not capture real payments. Use it for testing your purchase flow.","newspack-plugin")})]}),(0,w.jsx)(b.xA,{noMargin:!0,rowGap:16,children:t?(0,w.jsxs)(w.Fragment,{children:[(0,w.jsx)(b.xt,{type:"password",value:i,label:(0,a.__)("Test Publishable Key","newspack-plugin"),onChange:l("testPublishableKey")}),(0,w.jsx)(b.xt,{type:"password",value:r,label:(0,a.__)("Test Secret Key","newspack-plugin"),onChange:l("testSecretKey")})]}):(0,w.jsxs)(w.Fragment,{children:[(0,w.jsx)(b.xt,{type:"password",value:n,label:(0,a.__)("Publishable Key","newspack-plugin"),onChange:l("publishableKey")}),(0,w.jsx)(b.xt,{type:"password",value:s,label:(0,a.__)("Secret Key","newspack-plugin"),onChange:l("secretKey")})]})})]})};var Z=n(8059),Q=n.n(Z);const X=({className:e,onUpdate:t,initialProvider:n,newslettersConfig:i,isOnboarding:r=!0,authUrl:o=!1,setInitialProvider:l=(()=>{}),setAuthUrl:c=(()=>{}),setLockedLists:p=(()=>{})})=>{const[u,h]=(0,s.useState)(!1),[_,g]=(0,s.useState)(!1),[m,k]=b.JL.useObjectState({});(0,s.useEffect)((()=>{const e=i?.newspack_newsletters_service_provider;p(!(!n||e===n)),!n&&e&&l(e)}),[i?.newspack_newsletters_service_provider]),(0,s.useEffect)((()=>{v(i?.newspack_newsletters_service_provider)}),[i?.newspack_newsletters_service_provider]);const v=e=>{c(!1),e&&"constant_contact"===e&&(h(!0),d()({path:`/newspack-newsletters/v1/${e}/verify_token`}).then((e=>{!e.valid&&e.auth_url?c(e.auth_url):c(!1)})).catch((()=>{c(!1)})).finally((()=>{h(!1)})))},y=e=>{k(e),t&&t(O()(e.settings,H()("value")))},f=()=>{g(!1),d()({path:"/newspack/v1/wizard/newspack-engagement-wizard/newsletters"}).then(y).catch(g)},j=()=>{const e=m.settings.newspack_newsletters_service_provider,t=e?.value;return e?.options?.find((e=>e.value===t))?.name},S=()=>{o&&(window.open(o,"esp_oauth","width=500,height=600").opener={verify:Q()((()=>{window.location.reload()}))})},C=async()=>{g(!1),h(!0),d()({path:"/newspack/v1/wizard/newspack-engagement-wizard/newsletters",method:"POST",data:i}).finally((()=>{l(i?.newspack_newsletters_service_provider),v(i?.newspack_newsletters_service_provider),p(!1),h(!1)}))};(0,s.useEffect)(f,[]);const A=e=>({disabled:u,value:m.settings[e]?.value||"",checked:Boolean(m.settings[e]?.value),label:m.settings[e]?.description,placeholder:m.settings[e]?.placeholder,options:m.settings[e]?.options?.map((e=>({value:e.value,label:e.name})))||null,onChange:t=>y({settings:{[e]:{value:t}}})});return!_&&I()(m)?(0,w.jsx)("div",{className:"flex justify-around mt4",children:(0,w.jsx)(b.E$,{})}):(0,w.jsxs)("div",{className:e,children:[!1===m.configured&&(0,w.jsx)(b.I2,{plugins:["newspack-newsletters"],withoutFooterButton:!0,onStatus:({complete:e})=>e&&f()}),!0===m.configured&&(()=>{const e=A("newspack_newsletters_service_provider");return(0,w.jsx)(b.Lz,{isMedium:!0,title:(0,a.__)("Email Service Provider","newspack-plugin"),description:(0,a.__)("Connect an email service provider (ESP) to author and send newsletters.","newspack-plugin"),notification:_?_?.message||(0,a.__)("Something went wrong.","newspack-plugin"):null,notificationLevel:"error",hasGreyHeader:!0,actionContent:(0,w.jsx)(b.$n,{disabled:u,variant:"primary",onClick:C,children:(0,a.__)("Save Settings","newspack-plugin")}),disabled:u,children:(0,w.jsxs)(b.xA,{gutter:16,columns:1,children:[!1!==o&&(0,w.jsxs)(b.Zp,{isSmall:!0,children:[(0,w.jsx)("h3",{children:(0,a.__)("Authorize Application","newspack-plugin")}),(0,w.jsx)("p",{children:(0,a.sprintf)(
// translators: %s is the name of the ESP.
// translators: %s is the name of the ESP.
(0,a.__)("Authorize %s to connect to Newspack.","newspack-plugin"),j())}),(0,w.jsx)(b.$n,{isSecondary:!0,onClick:S,children:(0,a.__)("Authorize","newspack-plugin")})]}),"campaign_monitor"===m?.settings?.newspack_newsletters_service_provider?.value&&(0,w.jsxs)(x.Notice,{status:"warning",isDismissible:!1,children:[(0,w.jsx)("h2",{children:(0,a.__)("Campaign Monitor support will be deprecated","newspack-plugin")}),(0,w.jsx)("p",{children:(0,a.__)("Please connect a different service provider to ensure continued support.","newspack-")})]}),L()(m.settings).filter((t=>!t.provider||t.provider===e.value)).map((e=>{if(r&&!e.onboarding)return null;switch(e.type){case"select":return(0,w.jsx)(b.Yq,{...A(e.key)},e.key);case"checkbox":return(0,w.jsx)(x.CheckboxControl,{...A(e.key)},e.key);default:return(0,w.jsxs)(b.xA,{columns:1,gutter:8,children:[(0,w.jsx)(b.xt,{...A(e.key)}),e.help&&e.helpURL&&(0,w.jsx)("p",{children:(0,w.jsx)(x.ExternalLink,{href:e.helpURL,children:e.help})})]},e.key)}}))]})})})()]})},Y=({lockedLists:e,onUpdate:t,initialProvider:n})=>{const[i,r]=(0,s.useState)(!1),[o,l]=(0,s.useState)(!1),[c,p]=(0,s.useState)([]),u=e=>{p(e),"function"==typeof t&&t(e)},h=(e,t)=>n=>{const s=[...c];s[e][t]=n,u(s)};return(0,s.useEffect)((()=>{r(!1),l(!0),d()({path:"/newspack-newsletters/v1/lists"}).then(u).catch(r).finally((()=>l(!1)))}),[n]),o||c?.length||i?!o||c?.length||i?(0,w.jsx)(w.Fragment,{children:(0,w.jsx)(b.Lz,{isMedium:!0,title:(0,a.__)("Subscription Lists","newspack-plugin"),description:(0,a.__)("Manage the lists available to readers for subscription.","newspack-plugin"),notification:i?i?.message||(0,a.__)("Something went wrong.","newspack-plugin"):e?(0,a.__)("Please save your ESP settings before changing your subscription lists.","newspack-plugin"):null,notificationLevel:i?"error":"warning",hasGreyHeader:!0,actionContent:(0,w.jsxs)(w.Fragment,{children:[newspack_engagement_wizard.new_subscription_lists_url&&(0,w.jsx)(b.$n,{variant:"secondary",disabled:o||e,href:newspack_engagement_wizard.new_subscription_lists_url,children:(0,a.__)("Add New","newspack-plugin")}),(0,w.jsx)(b.$n,{isPrimary:!0,onClick:()=>{r(!1),l(!0),d()({path:"/newspack-newsletters/v1/lists",method:"post",data:{lists:c}}).then(u).catch(r).finally((()=>l(!1)))},disabled:o||e,children:(0,a.__)("Save Subscription Lists","newspack-plugin")})]}),disabled:o||e,children:!e&&c.map(((e,t)=>(0,w.jsx)(b.Lz,{isSmall:!0,simple:!0,hasWhiteHeader:!0,title:e.remote_name,description:e?.type_label?e.type_label:null,disabled:o,toggleOnChange:h(t,"active"),toggleChecked:e.active,className:e?.id&&(e.id.startsWith("group")||e.id.startsWith("tag"))?"newspack-newsletters-sub-list-item":"",actionText:e?.edit_link?(0,w.jsx)(x.ExternalLink,{href:e.edit_link,children:(0,a.__)("Edit","newspack-plugin")}):null,children:e.active&&"local"!==e?.type&&(0,w.jsxs)(w.Fragment,{children:[(0,w.jsx)(b.xt,{label:(0,a.__)("List title","newspack-plugin"),value:e.title,disabled:o||"local"===e?.type,onChange:h(t,"title")}),(0,w.jsx)(x.TextareaControl,{label:(0,a.__)("List description","newspack-plugin"),value:e.description,disabled:o||"local"===e?.type,onChange:h(t,"description")})]})},e.id)))})}):(0,w.jsx)("div",{className:"flex justify-around mt4",children:(0,w.jsx)(b.E$,{})}):null},J=()=>{const[{newslettersConfig:e},t]=b.JL.useObjectState({}),[n,a]=(0,s.useState)(""),[i,r]=(0,s.useState)(!1),[o,l]=(0,s.useState)(!1);return(0,w.jsxs)(w.Fragment,{children:[(0,w.jsx)(X,{isOnboarding:!1,onUpdate:e=>t({newslettersConfig:e}),authUrl:o,setAuthUrl:l,newslettersConfig:e,setLockedLists:r,initialProvider:n,setInitialProvider:a}),(0,w.jsx)(Y,{lockedLists:i,initialProvider:n})]})};(0,b.c_)((()=>(0,w.jsx)(w.Fragment,{children:(0,w.jsx)(J,{})})));var ee=n(133);const te={"reader-revenue":{label:(0,a.__)("Reader Revenue","newspack"),description:(0,a.__)("Encourage site visitors to contribute to your publishing through donations","newspack"),Component:({className:e})=>{const t=b.sQ.useWizardData("reader-revenue");return(0,w.jsx)("div",{className:R()(e,{"o-50":I()(t)}),children:"nrh"===t.platform_data?.platform?(0,w.jsx)("p",{children:(0,a.__)("Looks like this Newspack instance is already configured to use News Revenue Hub as the Reader Revenue platform. To edit these settings, visit the Reader Revenue section from the Newspack dashboard.","newspack")}):(0,w.jsxs)(w.Fragment,{children:[(0,w.jsx)(q,{}),(0,w.jsx)("h2",{children:(0,a.__)("Payment gateway","newspack")}),(0,w.jsx)(V,{})]})})},configuration:{is_service_enabled:!1}},newsletters:{label:(0,a.__)("Newsletters","newspack"),description:(0,a.__)("Create email newsletters and send them to your mail lists, all without leaving your website","newspack"),Component:X,configuration:{is_service_enabled:!1}},"google-ad-manager":{label:(0,a.__)("Google Ad Manager","newspack"),description:(0,a.__)("An advanced ad inventory creation and management platform, allowing you to be specific about ad placements","newspack"),Component:ee.A,configuration:{is_service_enabled:!1}}},ne=(0,b.c_)((({renderPrimaryButton:e})=>{const[t,n]=b.JL.useObjectState(te),[a,i]=(0,s.useState)(!0),r=E()(t),o=b.sQ.useWizardData("reader-revenue");return(0,s.useEffect)((()=>{d()({path:"/newspack/v1/wizard/newspack-setup-wizard/services"}).then((e=>{n(e),i(!1)}))}),[]),(0,w.jsxs)(w.Fragment,{children:[L()(t).map(((e,t)=>{const s=r[t],i=e.Component;return(0,w.jsx)(b.Lz,{isMedium:!0,title:e.label,description:e.description,className:s,toggleChecked:e.configuration.is_service_enabled,hasGreyHeader:e.configuration.is_service_enabled,toggleOnChange:e=>n({[s]:{configuration:{is_service_enabled:e}}}),disabled:a,href:e.configuration.is_service_enabled&&e.href,actionText:e.configuration.is_service_enabled&&e.actionText,children:e.configuration.is_service_enabled&&i?(0,w.jsx)(i,{className:"newspack-action-card__region-children__inner",configuration:e.configuration,onUpdate:e=>n({[s]:{configuration:e}})}):null},t)})),(0,w.jsx)("div",{className:"newspack-buttons-card",children:e({onClick:async()=>{const e=O()(t,H()("configuration"));return e["reader-revenue"]={...e["reader-revenue"],...o},d()({path:"/newspack/v1/wizard/newspack-setup-wizard/services",method:"POST",data:e})}})})]})}),{hidePrimaryButton:!0});var se=n(783);const ae=(0,b.c_)((()=>{(0,s.useEffect)((()=>(document.body.classList.add("newspack-wizard__completed","newspack-wizard__blue"),document.querySelector(".newspack-wizard__header").remove(),()=>document.body.classList.remove("newspack-wizard__completed","newspack-wizard__blue"))),[]);const e=R()("flex","flex-column","justify-between"),t=R()("flex","flex-row-reverse");return(0,w.jsxs)(w.Fragment,{children:[(0,w.jsx)(b.Zp,{noBorder:!0,children:(0,w.jsx)(b.X3,{title:(0,a.__)("You’re ready to go!","newspack"),description:(0,a.__)("While you’re off to a great start, there’s plenty more you can do:","newspack"),heading:1,centered:!0,isWhite:!0})}),(0,w.jsx)(b.Zp,{isWhite:!0,children:(0,w.jsxs)(b.xA,{children:[(0,w.jsx)(b.Lz,{title:(0,a.__)("Configure Newspack","newspack"),description:(0,a.__)("Go in-depth with our various options to set up Newspack to meet your needs.","newspack"),className:e,children:(0,w.jsx)("div",{className:t,children:(0,w.jsx)(b.$n,{variant:"primary",href:newspack_urls.dashboard,children:(0,a.__)("Go to the Dashboard","newspack")})})}),(0,w.jsx)(b.Lz,{title:(0,a.__)("Explore our documentation","newspack"),description:(0,a.__)("Read about the different tools, plugins, and themes that make up Newspack.","newspack"),className:e,children:(0,w.jsx)("div",{className:t,children:(0,w.jsx)(b.$n,{variant:"primary",href:newspack_urls.support,children:(0,a.__)("Read Documentation","newspack")})})}),(0,w.jsx)(b.Lz,{title:(0,a.__)("Update your homepage","newspack"),description:(0,a.__)("We’ve created the basics, now it’s time to update the content.","newspack"),className:e,children:(0,w.jsx)("div",{className:t,children:(0,w.jsx)(b.$n,{variant:"primary",href:newspack_urls.homepage,children:(0,a.__)("Edit Homepage","newspack")})})}),(0,w.jsx)(b.Lz,{title:(0,a.__)("View your site","newspack"),description:(0,a.__)("Preview what you’ve created so far. It looks great!","newspack"),className:e,children:(0,w.jsx)("div",{className:t,children:(0,w.jsx)(b.$n,{variant:"primary",href:newspack_urls.site,children:(0,a.__)("Visit Site","newspack")})})})]})})]})}),{hidePrimaryButton:!0}),{HashRouter:ie,Route:re}=v.A,oe=[{path:"/",label:(0,a.__)("Welcome","newspack"),render:e=>(0,w.jsx)(A,{...r()(e,["routes","headerText","buttonText"])}),isHiddenInNav:!0},{path:"/settings",label:(0,a.__)("Settings","newspack"),subHeaderText:(0,a.__)("Share a few details so we can start setting up your site","newspack"),render:z},{path:"/services",label:(0,a.__)("Services","newspack"),subHeaderText:(0,a.__)("Activate and configure the services that you need","newspack"),render:ne},{path:"/design",label:(0,a.__)("Design","newspack"),subHeaderText:(0,a.__)("Customize the look and feel of your site","newspack"),render:se.A},{path:"/completed",label:(0,a.__)("Completed","newspack"),render:ae,isHiddenInNav:!0}];(0,s.render)((0,s.createElement)((0,b.Aq)((({wizardApiFetch:e,setError:t})=>(0,w.jsxs)(s.Fragment,{children:[newspack_aux_data.has_completed_setup&&(0,w.jsx)(b.$T,{isWarning:!0,className:"ma0",children:(0,a.__)("Heads up! The setup has already been completed. No need to run it again.","newspack")}),(0,w.jsx)(ie,{hashType:"slash",children:oe.map(((n,s)=>{const i=oe[s+1]?.path,r=i?{href:"#"+i}:{};return(0,w.jsx)(re,{path:n.path,exact:"/"===n.path,render:()=>n.render({wizardApiFetch:e,setError:t,disableUpcomingInTabbedNavigation:!0,tabbedNavigation:oe,headerText:n.label,subHeaderText:n.subHeaderText,buttonText:i?n.buttonText||(0,a.__)("Continue"):(0,a.__)("Finish"),buttonAction:r})},s)}))})]})),[]),{simpleFooter:!0}),document.getElementById("newspack-setup-wizard"))},4066:(e,t,n)=>{var s=n(3488);e.exports=function(e){return"function"==typeof e?e:s}},6638:(e,t,n)=>{var s=n(8096),a=n(4066),i=n(1489),r=4294967295,o=Math.min;e.exports=function(e,t){if((e=i(e))<1||e>9007199254740991)return[];var n=r,l=o(e,r);t=a(t),e-=r;for(var c=s(l,t);++n<e;)t(n);return c}},1609:e=>{"use strict";e.exports=window.React},790:e=>{"use strict";e.exports=window.ReactJSXRuntime},6154:e=>{"use strict";e.exports=window.moment},1455:e=>{"use strict";e.exports=window.wp.apiFetch},6427:e=>{"use strict";e.exports=window.wp.components},7143:e=>{"use strict";e.exports=window.wp.data},6087:e=>{"use strict";e.exports=window.wp.element},2619:e=>{"use strict";e.exports=window.wp.hooks},8537:e=>{"use strict";e.exports=window.wp.htmlEntities},7723:e=>{"use strict";e.exports=window.wp.i18n},8558:e=>{"use strict";e.exports=window.wp.keycodes},5573:e=>{"use strict";e.exports=window.wp.primitives},3832:e=>{"use strict";e.exports=window.wp.url}},n={};function s(e){var a=n[e];if(void 0!==a)return a.exports;var i=n[e]={id:e,loaded:!1,exports:{}};return t[e].call(i.exports,i,i.exports,s),i.loaded=!0,i.exports}s.m=t,e=[],s.O=(t,n,a,i)=>{if(!n){var r=1/0;for(d=0;d<e.length;d++){for(var[n,a,i]=e[d],o=!0,l=0;l<n.length;l++)(!1&i||r>=i)&&Object.keys(s.O).every((e=>s.O[e](n[l])))?n.splice(l--,1):(o=!1,i<r&&(r=i));if(o){e.splice(d--,1);var c=a();void 0!==c&&(t=c)}}return t}i=i||0;for(var d=e.length;d>0&&e[d-1][2]>i;d--)e[d]=e[d-1];e[d]=[n,a,i]},s.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return s.d(t,{a:t}),t},s.d=(e,t)=>{for(var n in t)s.o(t,n)&&!s.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},s.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),s.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),s.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},s.nmd=e=>(e.paths=[],e.children||(e.children=[]),e),s.j=930,(()=>{var e;s.g.importScripts&&(e=s.g.location+"");var t=s.g.document;if(!e&&t&&(t.currentScript&&(e=t.currentScript.src),!e)){var n=t.getElementsByTagName("script");if(n.length)for(var a=n.length-1;a>-1&&(!e||!/^http(s?):/.test(e));)e=n[a--].src}if(!e)throw new Error("Automatic publicPath is not supported in this browser");e=e.replace(/#.*$/,"").replace(/\?.*$/,"").replace(/\/[^\/]+$/,"/"),s.p=e})(),(()=>{var e={930:0};s.O.j=t=>0===e[t];var t=(t,n)=>{var a,i,[r,o,l]=n,c=0;if(r.some((t=>0!==e[t]))){for(a in o)s.o(o,a)&&(s.m[a]=o[a]);if(l)var d=l(s)}for(t&&t(n);c<r.length;c++)i=r[c],s.o(e,i)&&e[i]&&e[i][0](),e[i]=0;return s.O(d)},n=globalThis.webpackChunknewspack=globalThis.webpackChunknewspack||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))})();var a=s.O(void 0,[223],(()=>s(9605)));a=s.O(a)})();