var __assign=this&&this.__assign||function(){return __assign=Object.assign||function(e){for(var t,n=1,a=arguments.length;n<a;n++)for(var o in t=arguments[n])Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o]);return e},__assign.apply(this,arguments)};import{monthToStr}from"../../utils/formatting";import{clearNode,getEventTarget}from"../../utils/dom";var defaultConfig={shorthand:!1,dateFormat:"F Y",altFormat:"F Y",theme:"light"};function monthSelectPlugin(e){var t=__assign(__assign({},defaultConfig),e);return function(e){e.config.dateFormat=t.dateFormat,e.config.altFormat=t.altFormat;var n={monthsContainer:null};function a(){if(n.monthsContainer){clearNode(n.monthsContainer);for(var a=document.createDocumentFragment(),o=0;o<12;o++){var r=e.createDay("flatpickr-monthSelect-month",new Date(e.currentYear,o),0,o);r.dateObj.getMonth()===(new Date).getMonth()&&r.dateObj.getFullYear()===(new Date).getFullYear()&&r.classList.add("today"),r.textContent=monthToStr(o,t.shorthand,e.l10n),r.addEventListener("click",i),a.appendChild(r)}n.monthsContainer.appendChild(a),e.config.minDate&&e.currentYear===e.config.minDate.getFullYear()?e.prevMonthNav.classList.add("flatpickr-disabled"):e.prevMonthNav.classList.remove("flatpickr-disabled"),e.config.maxDate&&e.currentYear===e.config.maxDate.getFullYear()?e.nextMonthNav.classList.add("flatpickr-disabled"):e.nextMonthNav.classList.remove("flatpickr-disabled")}}function o(){if(e.rContainer&&e.selectedDates.length){for(var t=e.rContainer.querySelectorAll(".flatpickr-monthSelect-month.selected"),n=0;n<t.length;n++)t[n].classList.remove("selected");var a=e.selectedDates[0].getMonth(),o=e.rContainer.querySelector(".flatpickr-monthSelect-month:nth-child("+(a+1)+")");o&&o.classList.add("selected")}}function r(){var t=e.selectedDates[0];(t&&((t=new Date(t)).setFullYear(e.currentYear),e.config.minDate&&t<e.config.minDate&&(t=e.config.minDate),e.config.maxDate&&t>e.config.maxDate&&(t=e.config.maxDate),e.currentYear=t.getFullYear()),e.currentYearElement.value=String(e.currentYear),e.rContainer)&&e.rContainer.querySelectorAll(".flatpickr-monthSelect-month").forEach((function(t){t.dateObj.setFullYear(e.currentYear),e.config.minDate&&t.dateObj<e.config.minDate||e.config.maxDate&&t.dateObj>e.config.maxDate?t.classList.add("flatpickr-disabled"):t.classList.remove("flatpickr-disabled")}));o()}function i(t){t.preventDefault(),t.stopPropagation();var n=getEventTarget(t);if(n instanceof Element&&!n.classList.contains("flatpickr-disabled")&&!n.classList.contains("notAllowed")&&(c(n.dateObj),e.config.closeOnSelect)){var a="single"===e.config.mode,o="range"===e.config.mode&&2===e.selectedDates.length;(a||o)&&e.close()}}function c(t){var n=new Date(e.currentYear,t.getMonth(),t.getDate()),a=[];switch(e.config.mode){case"single":a=[n];break;case"multiple":a.push(n);break;case"range":2===e.selectedDates.length?a=[n]:(a=e.selectedDates.concat([n])).sort((function(e,t){return e.getTime()-t.getTime()}))}e.setDate(a,!0),o()}var l={37:-1,39:1,40:3,38:-3};function s(){var t;"range"===(null===(t=e.config)||void 0===t?void 0:t.mode)&&1===e.selectedDates.length&&e.clear(!1),e.selectedDates.length||a()}return{onParseConfig:function(){e.config.enableTime=!1},onValueUpdate:o,onKeyDown:function(t,a,o,r){var i=void 0!==l[r.keyCode];if((i||13===r.keyCode)&&e.rContainer&&n.monthsContainer){var s=e.rContainer.querySelector(".flatpickr-monthSelect-month.selected"),d=Array.prototype.indexOf.call(n.monthsContainer.children,document.activeElement);if(-1===d){var f=s||n.monthsContainer.firstElementChild;f.focus(),d=f.$i}i?n.monthsContainer.children[(12+d+l[r.keyCode])%12].focus():13===r.keyCode&&n.monthsContainer.contains(document.activeElement)&&c(document.activeElement.dateObj)}},onReady:[function(){t._stubbedCurrentMonth=e._initialDate.getMonth(),e._initialDate.setMonth(t._stubbedCurrentMonth),e.currentMonth=t._stubbedCurrentMonth},function(){if(e.rContainer){clearNode(e.rContainer);for(var t=0;t<e.monthElements.length;t++){var n=e.monthElements[t];n.parentNode&&n.parentNode.removeChild(n)}}},function(){e.rContainer&&(n.monthsContainer=e._createElement("div","flatpickr-monthSelect-months"),n.monthsContainer.tabIndex=-1,a(),e.rContainer.appendChild(n.monthsContainer),e.calendarContainer.classList.add("flatpickr-monthSelect-theme-"+t.theme))},function(){e._bind(e.prevMonthNav,"click",(function(t){t.preventDefault(),t.stopPropagation(),e.changeYear(e.currentYear-1),r(),a()})),e._bind(e.nextMonthNav,"click",(function(t){t.preventDefault(),t.stopPropagation(),e.changeYear(e.currentYear+1),r(),a()})),e._bind(n.monthsContainer,"mouseover",(function(t){"range"===e.config.mode&&e.onMouseOver(getEventTarget(t),"flatpickr-monthSelect-month")}))},o,function(){e.config.onClose.push(s),e.loadedPlugins.push("monthSelect")}],onDestroy:[function(){t._stubbedCurrentMonth&&(e._initialDate.setMonth(t._stubbedCurrentMonth),e.currentMonth=t._stubbedCurrentMonth,delete t._stubbedCurrentMonth)},function(){if(null!==n.monthsContainer)for(var e=n.monthsContainer.querySelectorAll(".flatpickr-monthSelect-month"),t=0;t<e.length;t++)e[t].removeEventListener("click",i)},function(){e.config.onClose=e.config.onClose.filter((function(e){return e!==s}))}]}}}export default monthSelectPlugin;