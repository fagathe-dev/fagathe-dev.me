import{h}from"vue";function renderVirtual(e,r,t){if(!t)return null;const p=e=>{let t=e;return e<0?t=r.length+e:t>=r.length&&(t-=r.length),t},l=e.value.isHorizontal()?{[e.value.rtlTranslate?"right":"left"]:`${t.offset}px`}:{top:`${t.offset}px`},{from:o,to:s}=t,n=e.value.params.loop?-r.length:0,a=e.value.params.loop?2*r.length:r.length,u=[];for(let e=n;e<a;e+=1)e>=o&&e<=s&&u.push(r[p(e)]);return u.map((r=>(r.props||(r.props={}),r.props.style||(r.props.style={}),r.props.swiperRef=e,r.props.style=l,h(r.type,{...r.props},r.children))))}export{renderVirtual};