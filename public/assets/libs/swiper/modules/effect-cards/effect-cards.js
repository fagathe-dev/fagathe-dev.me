import createShadow from"../../shared/create-shadow.js";import effectInit from"../../shared/effect-init.js";import effectTarget from"../../shared/effect-target.js";import effectVirtualTransitionEnd from"../../shared/effect-virtual-transition-end.js";import{getSlideTransformEl}from"../../shared/utils.js";export default function EffectCards({swiper:e,extendParams:t,on:a}){t({cardsEffect:{slideShadows:!0,rotate:!0,perSlideRotate:2,perSlideOffset:8}});effectInit({effect:"cards",swiper:e,on:a,setTranslate:()=>{const{slides:t,activeIndex:a}=e,s=e.params.cardsEffect,{startTranslate:r,isTouched:i}=e.touchEventsData,o=e.translate;for(let n=0;n<t.length;n+=1){const l=t[n],d=l.progress,f=Math.min(Math.max(d,-4),4);let c=l.swiperSlideOffset;e.params.centeredSlides&&!e.params.cssMode&&(e.wrapperEl.style.transform=`translateX(${e.minTranslate()}px)`),e.params.centeredSlides&&e.params.cssMode&&(c-=t[0].swiperSlideOffset);let p=e.params.cssMode?-c-e.translate:-c,m=0;const h=-100*Math.abs(f);let M=1,u=-s.perSlideRotate*f,w=s.perSlideOffset-.75*Math.abs(f);const S=e.virtual&&e.params.virtual.enabled?e.virtual.from+n:n,$=(S===a||S===a-1)&&f>0&&f<1&&(i||e.params.cssMode)&&o<r,E=(S===a||S===a+1)&&f<0&&f>-1&&(i||e.params.cssMode)&&o>r;if($||E){const e=(1-Math.abs((Math.abs(f)-.5)/.5))**.5;u+=-28*f*e,M+=-.5*e,w+=96*e,m=-25*e*Math.abs(f)+"%"}if(p=f<0?`calc(${p}px + (${w*Math.abs(f)}%))`:f>0?`calc(${p}px + (-${w*Math.abs(f)}%))`:`${p}px`,!e.isHorizontal()){const e=m;m=p,p=e}const T=f<0?""+(1+(1-M)*f):""+(1-(1-M)*f),x=`\n        translate3d(${p}, ${m}, ${h}px)\n        rotateZ(${s.rotate?u:0}deg)\n        scale(${T})\n      `;if(s.slideShadows){let e=l.querySelector(".swiper-slide-shadow");e||(e=createShadow(s,l)),e&&(e.style.opacity=Math.min(Math.max((Math.abs(f)-.5)/.5,0),1))}l.style.zIndex=-Math.abs(Math.round(d))+t.length;effectTarget(s,l).style.transform=x}},setTransition:t=>{const a=e.slides.map((e=>getSlideTransformEl(e)));a.forEach((e=>{e.style.transitionDuration=`${t}ms`,e.querySelectorAll(".swiper-slide-shadow").forEach((e=>{e.style.transitionDuration=`${t}ms`}))})),effectVirtualTransitionEnd({swiper:e,duration:t,transformElements:a})},perspective:()=>!0,overwriteParams:()=>({watchSlidesProgress:!0,virtualTranslate:!e.params.cssMode})})}