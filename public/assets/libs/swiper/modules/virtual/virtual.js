import{getDocument}from"ssr-window";import{createElement,elementChildren,setCSSProperty}from"../../shared/utils.js";export default function Virtual({swiper:e,extendParams:s,on:t,emit:r}){let i;s({virtual:{enabled:!1,slides:[],cache:!0,renderSlide:null,renderExternal:null,renderExternalUpdate:!0,addSlidesBefore:0,addSlidesAfter:0}});const l=getDocument();e.virtual={cache:{},from:void 0,to:void 0,slides:[],offset:0,slidesGrid:[]};const a=l.createElement("div");function d(s,t){const r=e.params.virtual;if(r.cache&&e.virtual.cache[t])return e.virtual.cache[t];let i;return r.renderSlide?(i=r.renderSlide.call(e,s,t),"string"==typeof i&&(a.innerHTML=i,i=a.children[0])):i=e.isElement?createElement("swiper-slide"):createElement("div",e.params.slideClass),i.setAttribute("data-swiper-slide-index",t),r.renderSlide||(i.innerHTML=s),r.cache&&(e.virtual.cache[t]=i),i}function n(s){const{slidesPerView:t,slidesPerGroup:i,centeredSlides:l,loop:a}=e.params,{addSlidesBefore:n,addSlidesAfter:o}=e.params.virtual,{from:c,to:u,slides:p,slidesGrid:f,offset:h}=e.virtual;e.params.cssMode||e.updateActiveIndex();const m=e.activeIndex||0;let v,g,E;v=e.rtlTranslate?"right":e.isHorizontal()?"left":"top",l?(g=Math.floor(t/2)+i+o,E=Math.floor(t/2)+i+n):(g=t+(i-1)+o,E=(a?t:i)+n);let S=m-E,x=m+g;a||(S=Math.max(S,0),x=Math.min(x,p.length-1));let w=(e.slidesGrid[S]||0)-(e.slidesGrid[0]||0);function A(){e.updateSlides(),e.updateProgress(),e.updateSlidesClasses(),r("virtualUpdate")}if(a&&m>=E?(S-=E,l||(w+=e.slidesGrid[0])):a&&m<E&&(S=-E,l&&(w+=e.slidesGrid[0])),Object.assign(e.virtual,{from:S,to:x,offset:w,slidesGrid:e.slidesGrid,slidesBefore:E,slidesAfter:g}),c===S&&u===x&&!s)return e.slidesGrid!==f&&w!==h&&e.slides.forEach((s=>{s.style[v]=w-Math.abs(e.cssOverflowAdjustment())+"px"})),e.updateProgress(),void r("virtualUpdate");if(e.params.virtual.renderExternal)return e.params.virtual.renderExternal.call(e,{offset:w,from:S,to:x,slides:function(){const e=[];for(let s=S;s<=x;s+=1)e.push(p[s]);return e}()}),void(e.params.virtual.renderExternalUpdate?A():r("virtualUpdate"));const b=[],M=[],y=e=>{let s=e;return e<0?s=p.length+e:s>=p.length&&(s-=p.length),s};if(s)e.slidesEl.querySelectorAll(`.${e.params.slideClass}, swiper-slide`).forEach((e=>{e.remove()}));else for(let s=c;s<=u;s+=1)if(s<S||s>x){const t=y(s);e.slidesEl.querySelectorAll(`.${e.params.slideClass}[data-swiper-slide-index="${t}"], swiper-slide[data-swiper-slide-index="${t}"]`).forEach((e=>{e.remove()}))}const P=a?-p.length:0,C=a?2*p.length:p.length;for(let e=P;e<C;e+=1)if(e>=S&&e<=x){const t=y(e);void 0===u||s?M.push(t):(e>u&&M.push(t),e<c&&b.push(t))}if(M.forEach((s=>{e.slidesEl.append(d(p[s],s))})),a)for(let s=b.length-1;s>=0;s-=1){const t=b[s];e.slidesEl.prepend(d(p[t],t))}else b.sort(((e,s)=>s-e)),b.forEach((s=>{e.slidesEl.prepend(d(p[s],s))}));elementChildren(e.slidesEl,".swiper-slide, swiper-slide").forEach((s=>{s.style[v]=w-Math.abs(e.cssOverflowAdjustment())+"px"})),A()}t("beforeInit",(()=>{if(!e.params.virtual.enabled)return;let s;if(void 0===e.passedParams.virtual.slides){const t=[...e.slidesEl.children].filter((s=>s.matches(`.${e.params.slideClass}, swiper-slide`)));t&&t.length&&(e.virtual.slides=[...t],s=!0,t.forEach(((s,t)=>{s.setAttribute("data-swiper-slide-index",t),e.virtual.cache[t]=s,s.remove()})))}s||(e.virtual.slides=e.params.virtual.slides),e.classNames.push(`${e.params.containerModifierClass}virtual`),e.params.watchSlidesProgress=!0,e.originalParams.watchSlidesProgress=!0,e.params.initialSlide||n()})),t("setTranslate",(()=>{e.params.virtual.enabled&&(e.params.cssMode&&!e._immediateVirtual?(clearTimeout(i),i=setTimeout((()=>{n()}),100)):n())})),t("init update resize",(()=>{e.params.virtual.enabled&&e.params.cssMode&&setCSSProperty(e.wrapperEl,"--swiper-virtual-size",`${e.virtualSize}px`)})),Object.assign(e.virtual,{appendSlide:function(s){if("object"==typeof s&&"length"in s)for(let t=0;t<s.length;t+=1)s[t]&&e.virtual.slides.push(s[t]);else e.virtual.slides.push(s);n(!0)},prependSlide:function(s){const t=e.activeIndex;let r=t+1,i=1;if(Array.isArray(s)){for(let t=0;t<s.length;t+=1)s[t]&&e.virtual.slides.unshift(s[t]);r=t+s.length,i=s.length}else e.virtual.slides.unshift(s);if(e.params.virtual.cache){const s=e.virtual.cache,t={};Object.keys(s).forEach((e=>{const r=s[e],l=r.getAttribute("data-swiper-slide-index");l&&r.setAttribute("data-swiper-slide-index",parseInt(l,10)+i),t[parseInt(e,10)+i]=r})),e.virtual.cache=t}n(!0),e.slideTo(r,0)},removeSlide:function(s){if(null==s)return;let t=e.activeIndex;if(Array.isArray(s))for(let r=s.length-1;r>=0;r-=1)e.virtual.slides.splice(s[r],1),e.params.virtual.cache&&delete e.virtual.cache[s[r]],s[r]<t&&(t-=1),t=Math.max(t,0);else e.virtual.slides.splice(s,1),e.params.virtual.cache&&delete e.virtual.cache[s],s<t&&(t-=1),t=Math.max(t,0);n(!0),e.slideTo(t,0)},removeAllSlides:function(){e.virtual.slides=[],e.params.virtual.cache&&(e.virtual.cache={}),n(!0),e.slideTo(0,0)},update:n})}