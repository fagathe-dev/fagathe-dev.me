export default function setTranslate(t,s){const a=this,{rtlTranslate:r,params:e,wrapperEl:l,progress:o}=a;let n=0,i=0;let p;a.isHorizontal()?n=r?-t:t:i=t,e.roundLengths&&(n=Math.floor(n),i=Math.floor(i)),a.previousTranslate=a.translate,a.translate=a.isHorizontal()?n:i,e.cssMode?l[a.isHorizontal()?"scrollLeft":"scrollTop"]=a.isHorizontal()?-n:-i:e.virtualTranslate||(a.isHorizontal()?n-=a.cssOverflowAdjustment():i-=a.cssOverflowAdjustment(),l.style.transform=`translate3d(${n}px, ${i}px, 0px)`);const T=a.maxTranslate()-a.minTranslate();p=0===T?0:(t-a.minTranslate())/T,p!==o&&a.updateProgress(t),a.emit("setTranslate",a.translate,s)}