!function(){"use strict";var e={r:function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},t={};e.r(t);var o=window.wp.element,n=window.wp.blocks,r=window.wp.i18n;var i=function(e){let{icon:t,size:n=24,...r}=e;return(0,o.cloneElement)(t,{width:n,height:n,...r})},l=window.wp.primitives;var a=(0,o.createElement)(l.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},(0,o.createElement)(l.Path,{d:"M13.8 5.2H3v1.5h10.8V5.2zm-3.6 12v1.5H21v-1.5H10.2zm7.2-6H6.6v1.5h10.8v-1.5z"})),c=window.wp.coreData,s=JSON.parse('{"name":"newspack-block-theme/article-subtitle","title":"Article Subtitle","category":"newspack","attributes":{},"supports":{"html":false,"color":{"gradients":true,"__experimentalDefaultControls":{"background":true,"text":true,"link":true}},"spacing":{"margin":true,"padding":true},"typography":{"fontSize":true,"lineHeight":true}}}');const __=r.__;s={title:__("Article Subtitle","newspack-block-theme"),icon:{src:(0,o.createElement)(i,{icon:a}),foreground:"#36f"},edit:e=>{let{context:{postType:t,postId:o}}=e;const[n={}]=(0,c.useEntityProp)("postType",t,"meta",o);return n[newspack_block_theme_subtitle_block.post_meta_name]||__("Article subtitle","newspack-block-theme")},usesContext:["postId","postType"],...s},(0,n.registerBlockType)(s.name,s);var p=window;for(var u in t)p[u]=t[u];t.__esModule&&Object.defineProperty(p,"__esModule",{value:!0})}();