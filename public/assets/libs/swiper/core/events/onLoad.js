import{processLazyPreloader}from"../../shared/process-lazy-preloader.js";export default function onLoad(a){const e=this;processLazyPreloader(e,a.target),e.params.cssMode||"auto"!==e.params.slidesPerView&&!e.params.autoHeight||e.update()}