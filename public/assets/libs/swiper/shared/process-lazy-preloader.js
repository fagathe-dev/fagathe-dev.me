export const processLazyPreloader=(e,s)=>{if(!e||e.destroyed||!e.params)return;const r=s.closest(e.isElement?"swiper-slide":`.${e.params.slideClass}`);if(r){const s=r.querySelector(`.${e.params.lazyPreloaderClass}`);s&&s.remove()}};const unlazy=(e,s)=>{if(!e.slides[s])return;const r=e.slides[s].querySelector('[loading="lazy"]');r&&r.removeAttribute("loading")};export const preload=e=>{if(!e||e.destroyed||!e.params)return;let s=e.params.lazyPreloadPrevNext;const r=e.slides.length;if(!r||!s||s<0)return;s=Math.min(s,r);const a="auto"===e.params.slidesPerView?e.slidesPerViewDynamic():Math.ceil(e.params.slidesPerView),t=e.activeIndex,l=t+a-1;if(e.params.rewind)for(let a=t-s;a<=l+s;a+=1){const s=(a%r+r)%r;s!==t&&s>l&&unlazy(e,s)}else for(let a=Math.max(l-s,0);a<=Math.min(l+s,r-1);a+=1)a!==t&&a>l&&unlazy(e,a)};