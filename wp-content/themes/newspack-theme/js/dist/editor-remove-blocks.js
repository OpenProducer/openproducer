(()=>{"use strict";var e={n:o=>{var r=o&&o.__esModule?()=>o.default:()=>o;return e.d(r,{a:r}),r},d:(o,r)=>{for(var t in r)e.o(r,t)&&!e.o(o,t)&&Object.defineProperty(o,t,{enumerable:!0,get:r[t]})},o:(e,o)=>Object.prototype.hasOwnProperty.call(e,o)};const o=window.wp.blocks,r=window.wp.domReady;var t=e.n(r);const n=updateAllowedBlocks.removeblocks.split(",");t()((function(){n.forEach((function(e){(0,o.unregisterBlockType)(e)}))}))})();