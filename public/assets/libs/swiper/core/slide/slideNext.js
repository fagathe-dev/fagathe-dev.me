export default function slideNext(e=this.params.speed,i=!0,r){const t=this,{enabled:s,params:n,animating:l}=t;if(!s)return t;let d=n.slidesPerGroup;"auto"===n.slidesPerView&&1===n.slidesPerGroup&&n.slidesPerGroupAuto&&(d=Math.max(t.slidesPerViewDynamic("current",!0),1));const o=t.activeIndex<n.slidesPerGroupSkip?1:d,a=t.virtual&&n.virtual.enabled;if(n.loop){if(l&&!a&&n.loopPreventsSliding)return!1;t.loopFix({direction:"next"}),t._clientLeft=t.wrapperEl.clientLeft}return n.rewind&&t.isEnd?t.slideTo(0,e,i,r):t.slideTo(t.activeIndex+o,e,i,r)}