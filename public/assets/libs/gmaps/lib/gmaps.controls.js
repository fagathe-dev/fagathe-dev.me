GMaps.prototype.createControl=function(t){var o=document.createElement("div");for(var e in o.style.cursor="pointer",!0!==t.disableDefaultStyles&&(o.style.fontFamily="Roboto, Arial, sans-serif",o.style.fontSize="11px",o.style.boxShadow="rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px"),t.style)o.style[e]=t.style[e];for(var n in t.id&&(o.id=t.id),t.title&&(o.title=t.title),t.classes&&(o.className=t.classes),t.content&&("string"==typeof t.content?o.innerHTML=t.content:t.content instanceof HTMLElement&&o.appendChild(t.content)),t.position&&(o.position=google.maps.ControlPosition[t.position.toUpperCase()]),t.events)!function(o,e){google.maps.event.addDomListener(o,e,(function(){t.events[e].apply(this,[this])}))}(o,n);return o.index=1,o},GMaps.prototype.addControl=function(t){var o=this.createControl(t);return this.controls.push(o),this.map.controls[o.position].push(o),o},GMaps.prototype.removeControl=function(t){var o,e=null;for(o=0;o<this.controls.length;o++)this.controls[o]==t&&(e=this.controls[o].position,this.controls.splice(o,1));if(e)for(o=0;o<this.map.controls.length;o++){var n=this.map.controls[t.position];if(n.getAt(o)==t){n.removeAt(o);break}}return t};