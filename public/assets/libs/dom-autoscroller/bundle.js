"use strict";function _interopDefault(e){return e&&"object"==typeof e&&"default"in e?e.default:e}var typeFunc=require("type-func"),animationFramePolyfill=require("animation-frame-polyfill"),domSet=require("dom-set"),domPlane=require("dom-plane"),mousemoveDispatcher=_interopDefault(require("dom-mousemove-dispatcher"));function AutoScroller(e,n){void 0===n&&(n={});var t=this,o=4,i=!1;this.margin=n.margin||-1,this.scrollWhenOutside=n.scrollWhenOutside||!1;var r={},a=domPlane.createPointCB(r),m=mousemoveDispatcher(),l=!1;window.addEventListener("mousemove",a,!1),window.addEventListener("touchmove",a,!1),isNaN(n.maxSpeed)||(o=n.maxSpeed),this.autoScroll=typeFunc.boolean(n.autoScroll),this.syncMove=typeFunc.boolean(n.syncMove,!1),this.destroy=function(n){window.removeEventListener("mousemove",a,!1),window.removeEventListener("touchmove",a,!1),window.removeEventListener("mousedown",w,!1),window.removeEventListener("touchstart",w,!1),window.removeEventListener("mouseup",p,!1),window.removeEventListener("touchend",p,!1),window.removeEventListener("pointerup",p,!1),window.removeEventListener("mouseleave",y,!1),window.removeEventListener("mousemove",F,!1),window.removeEventListener("touchmove",F,!1),window.removeEventListener("scroll",v,!0),e=[],n&&h()},this.add=function(){for(var n=[],t=arguments.length;t--;)n[t]=arguments[t];return domSet.addElements.apply(void 0,[e].concat(n)),this},this.remove=function(){for(var n=[],t=arguments.length;t--;)n[t]=arguments[t];return domSet.removeElements.apply(void 0,[e].concat(n))};var u,d,c=null;"[object Array]"!==Object.prototype.toString.call(e)&&(e=[e]),d=e,e=[],d.forEach((function(e){e===window?c=window:t.add(e)})),Object.defineProperties(this,{down:{get:function(){return l}},maxSpeed:{get:function(){return o}},point:{get:function(){return r}},scrolling:{get:function(){return i}}});var s,f=null;function v(n){for(var t=0;t<e.length;t++)if(e[t]===n.target){i=!0;break}i&&animationFramePolyfill.requestAnimationFrame((function(){return i=!1}))}function w(){l=!0}function p(){l=!1,h()}function h(){animationFramePolyfill.cancelAnimationFrame(s),animationFramePolyfill.cancelAnimationFrame(u)}function y(){l=!1}function g(){for(var n=null,t=0;t<e.length;t++)inside(r,e[t])&&(n=e[t]);return n}function F(n){if(t.autoScroll()&&!n.dispatched){var o=n.target,i=document.body;f&&!inside(r,f)&&(t.scrollWhenOutside||(f=null)),o&&o.parentNode===i?o=g():(o=function(n){if(!n)return null;if(f===n)return n;if(domSet.hasElement(e,n))return n;for(;n=n.parentNode;)if(domSet.hasElement(e,n))return n;return null}(o))||(o=g()),o&&o!==f&&(f=o),c&&(animationFramePolyfill.cancelAnimationFrame(u),u=animationFramePolyfill.requestAnimationFrame(E)),f&&(animationFramePolyfill.cancelAnimationFrame(s),s=animationFramePolyfill.requestAnimationFrame(L))}}function E(){S(c),animationFramePolyfill.cancelAnimationFrame(u),u=animationFramePolyfill.requestAnimationFrame(E)}function L(){f&&(S(f),animationFramePolyfill.cancelAnimationFrame(s),s=animationFramePolyfill.requestAnimationFrame(L))}function S(e){var n,o,i=domPlane.getClientRect(e);n=r.x<i.left+t.margin?Math.floor(Math.max(-1,(r.x-i.left)/t.margin-1)*t.maxSpeed):r.x>i.right-t.margin?Math.ceil(Math.min(1,(r.x-i.right)/t.margin+1)*t.maxSpeed):0,o=r.y<i.top+t.margin?Math.floor(Math.max(-1,(r.y-i.top)/t.margin-1)*t.maxSpeed):r.y>i.bottom-t.margin?Math.ceil(Math.min(1,(r.y-i.bottom)/t.margin+1)*t.maxSpeed):0,t.syncMove()&&m.dispatch(e,{pageX:r.pageX+n,pageY:r.pageY+o,clientX:r.x+n,clientY:r.y+o}),setTimeout((function(){o&&function(e,n){e===window?window.scrollTo(e.pageXOffset,e.pageYOffset+n):e.scrollTop+=n}(e,o),n&&function(e,n){e===window?window.scrollTo(e.pageXOffset+n,e.pageYOffset):e.scrollLeft+=n}(e,n)}))}window.addEventListener("mousedown",w,!1),window.addEventListener("touchstart",w,!1),window.addEventListener("mouseup",p,!1),window.addEventListener("touchend",p,!1),window.addEventListener("pointerup",p,!1),window.addEventListener("mousemove",F,!1),window.addEventListener("touchmove",F,!1),window.addEventListener("mouseleave",y,!1),window.addEventListener("scroll",v,!0)}function AutoScrollerFactory(e,n){return new AutoScroller(e,n)}function inside(e,n,t){return t?e.y>t.top&&e.y<t.bottom&&e.x>t.left&&e.x<t.right:domPlane.pointInside(e,n)}module.exports=AutoScrollerFactory;