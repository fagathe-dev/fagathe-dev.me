/*! For license information please see index.js.LICENSE.txt */
!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):(e="undefined"!=typeof globalThis?globalThis:e||self).monthSelectPlugin=t()}(this,(function(){"use strict";var e=function(){return e=Object.assign||function(e){for(var t,n=1,o=arguments.length;n<o;n++)for(var a in t=arguments[n])Object.prototype.hasOwnProperty.call(t,a)&&(e[a]=t[a]);return e},e.apply(this,arguments)};function t(e){for(;e.firstChild;)e.removeChild(e.firstChild)}function n(e){try{return"function"==typeof e.composedPath?e.composedPath()[0]:e.target}catch(t){return e.target}}var o={shorthand:!1,dateFormat:"F Y",altFormat:"F Y",theme:"light"};return function(a){var r=e(e({},o),a);return function(e){e.config.dateFormat=r.dateFormat,e.config.altFormat=r.altFormat;var o={monthsContainer:null};function a(){if(o.monthsContainer){t(o.monthsContainer);for(var n,a,i=document.createDocumentFragment(),c=0;c<12;c++){var s=e.createDay("flatpickr-monthSelect-month",new Date(e.currentYear,c),0,c);s.dateObj.getMonth()===(new Date).getMonth()&&s.dateObj.getFullYear()===(new Date).getFullYear()&&s.classList.add("today"),s.textContent=(n=c,a=r.shorthand,e.l10n.months[a?"shorthand":"longhand"][n]),s.addEventListener("click",l),i.appendChild(s)}o.monthsContainer.appendChild(i),e.config.minDate&&e.currentYear===e.config.minDate.getFullYear()?e.prevMonthNav.classList.add("flatpickr-disabled"):e.prevMonthNav.classList.remove("flatpickr-disabled"),e.config.maxDate&&e.currentYear===e.config.maxDate.getFullYear()?e.nextMonthNav.classList.add("flatpickr-disabled"):e.nextMonthNav.classList.remove("flatpickr-disabled")}}function i(){if(e.rContainer&&e.selectedDates.length){for(var t=e.rContainer.querySelectorAll(".flatpickr-monthSelect-month.selected"),n=0;n<t.length;n++)t[n].classList.remove("selected");var o=e.selectedDates[0].getMonth(),a=e.rContainer.querySelector(".flatpickr-monthSelect-month:nth-child("+(o+1)+")");a&&a.classList.add("selected")}}function c(){var t=e.selectedDates[0];(t&&((t=new Date(t)).setFullYear(e.currentYear),e.config.minDate&&t<e.config.minDate&&(t=e.config.minDate),e.config.maxDate&&t>e.config.maxDate&&(t=e.config.maxDate),e.currentYear=t.getFullYear()),e.currentYearElement.value=String(e.currentYear),e.rContainer)&&e.rContainer.querySelectorAll(".flatpickr-monthSelect-month").forEach((function(t){t.dateObj.setFullYear(e.currentYear),e.config.minDate&&t.dateObj<e.config.minDate||e.config.maxDate&&t.dateObj>e.config.maxDate?t.classList.add("flatpickr-disabled"):t.classList.remove("flatpickr-disabled")}));i()}function l(t){t.preventDefault(),t.stopPropagation();var o=n(t);if(o instanceof Element&&!o.classList.contains("flatpickr-disabled")&&!o.classList.contains("notAllowed")&&(s(o.dateObj),e.config.closeOnSelect)){var a="single"===e.config.mode,r="range"===e.config.mode&&2===e.selectedDates.length;(a||r)&&e.close()}}function s(t){var n=new Date(e.currentYear,t.getMonth(),t.getDate()),o=[];switch(e.config.mode){case"single":o=[n];break;case"multiple":o.push(n);break;case"range":2===e.selectedDates.length?o=[n]:(o=e.selectedDates.concat([n])).sort((function(e,t){return e.getTime()-t.getTime()}))}e.setDate(o,!0),i()}var d={37:-1,39:1,40:3,38:-3};function f(){var t;"range"===(null===(t=e.config)||void 0===t?void 0:t.mode)&&1===e.selectedDates.length&&e.clear(!1),e.selectedDates.length||a()}return{onParseConfig:function(){e.config.enableTime=!1},onValueUpdate:i,onKeyDown:function(t,n,a,r){var i=void 0!==d[r.keyCode];if((i||13===r.keyCode)&&e.rContainer&&o.monthsContainer){var c=e.rContainer.querySelector(".flatpickr-monthSelect-month.selected"),l=Array.prototype.indexOf.call(o.monthsContainer.children,document.activeElement);if(-1===l){var f=c||o.monthsContainer.firstElementChild;f.focus(),l=f.$i}i?o.monthsContainer.children[(12+l+d[r.keyCode])%12].focus():13===r.keyCode&&o.monthsContainer.contains(document.activeElement)&&s(document.activeElement.dateObj)}},onReady:[function(){r._stubbedCurrentMonth=e._initialDate.getMonth(),e._initialDate.setMonth(r._stubbedCurrentMonth),e.currentMonth=r._stubbedCurrentMonth},function(){if(e.rContainer){t(e.rContainer);for(var n=0;n<e.monthElements.length;n++){var o=e.monthElements[n];o.parentNode&&o.parentNode.removeChild(o)}}},function(){e.rContainer&&(o.monthsContainer=e._createElement("div","flatpickr-monthSelect-months"),o.monthsContainer.tabIndex=-1,a(),e.rContainer.appendChild(o.monthsContainer),e.calendarContainer.classList.add("flatpickr-monthSelect-theme-"+r.theme))},function(){e._bind(e.prevMonthNav,"click",(function(t){t.preventDefault(),t.stopPropagation(),e.changeYear(e.currentYear-1),c(),a()})),e._bind(e.nextMonthNav,"click",(function(t){t.preventDefault(),t.stopPropagation(),e.changeYear(e.currentYear+1),c(),a()})),e._bind(o.monthsContainer,"mouseover",(function(t){"range"===e.config.mode&&e.onMouseOver(n(t),"flatpickr-monthSelect-month")}))},i,function(){e.config.onClose.push(f),e.loadedPlugins.push("monthSelect")}],onDestroy:[function(){r._stubbedCurrentMonth&&(e._initialDate.setMonth(r._stubbedCurrentMonth),e.currentMonth=r._stubbedCurrentMonth,delete r._stubbedCurrentMonth)},function(){if(null!==o.monthsContainer)for(var e=o.monthsContainer.querySelectorAll(".flatpickr-monthSelect-month"),t=0;t<e.length;t++)e[t].removeEventListener("click",l)},function(){e.config.onClose=e.config.onClose.filter((function(e){return e!==f}))}]}}}}));