function prepareClasses(e,s){const r=[];return e.forEach((e=>{"object"==typeof e?Object.keys(e).forEach((i=>{e[i]&&r.push(s+i)})):"string"==typeof e&&r.push(s+e)})),r}export default function addClasses(){const e=this,{classNames:s,params:r,rtl:i,el:o,device:d}=e,a=prepareClasses(["initialized",r.direction,{"free-mode":e.params.freeMode&&r.freeMode.enabled},{autoheight:r.autoHeight},{rtl:i},{grid:r.grid&&r.grid.rows>1},{"grid-column":r.grid&&r.grid.rows>1&&"column"===r.grid.fill},{android:d.android},{ios:d.ios},{"css-mode":r.cssMode},{centered:r.cssMode&&r.centeredSlides},{"watch-progress":r.watchSlidesProgress}],r.containerModifierClass);s.push(...a),o.classList.add(...s),e.emitContainerClasses()}