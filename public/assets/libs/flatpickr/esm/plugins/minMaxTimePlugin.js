import{calculateSecondsSinceMidnight,compareDates,compareTimes,createDateFormatter,parseSeconds}from"../utils/dates";function minMaxTimePlugin(e){void 0===e&&(e={});var i={formatDate:createDateFormatter({}),tableDateFormat:e.tableDateFormat||"Y-m-d",defaults:{minTime:void 0,maxTime:void 0}};return function(t){return{onReady:function(){i.formatDate=this.formatDate,i.defaults={minTime:this.config.minTime&&i.formatDate(this.config.minTime,"H:i"),maxTime:this.config.maxTime&&i.formatDate(this.config.maxTime,"H:i")},t.loadedPlugins.push("minMaxTime")},onChange:function(){var m,n=this.latestSelectedDateObj,a=n&&(m=n,void 0!==e.table?e.table[i.formatDate(m,i.tableDateFormat)]:e.getTimeLimits&&e.getTimeLimits(m));if(n&&void 0!==a)if(this.set(a),t.config.minTime.setFullYear(n.getFullYear()),t.config.maxTime.setFullYear(n.getFullYear()),t.config.minTime.setMonth(n.getMonth()),t.config.maxTime.setMonth(n.getMonth()),t.config.minTime.setDate(n.getDate()),t.config.maxTime.setDate(n.getDate()),t.config.minTime>t.config.maxTime){var o=calculateSecondsSinceMidnight(t.config.minTime.getHours(),t.config.minTime.getMinutes(),t.config.minTime.getSeconds()),s=calculateSecondsSinceMidnight(t.config.maxTime.getHours(),t.config.maxTime.getMinutes(),t.config.maxTime.getSeconds()),g=calculateSecondsSinceMidnight(n.getHours(),n.getMinutes(),n.getSeconds());if(g>s&&g<o){var c=parseSeconds(o);t.setDate(new Date(n.getTime()).setHours(c[0],c[1],c[2]),!1)}}else compareDates(n,t.config.maxTime,!1)>0?t.setDate(new Date(n.getTime()).setHours(t.config.maxTime.getHours(),t.config.maxTime.getMinutes(),t.config.maxTime.getSeconds(),t.config.maxTime.getMilliseconds()),!1):compareDates(n,t.config.minTime,!1)<0&&t.setDate(new Date(n.getTime()).setHours(t.config.minTime.getHours(),t.config.minTime.getMinutes(),t.config.minTime.getSeconds(),t.config.minTime.getMilliseconds()),!1);else{var r=i.defaults||{minTime:void 0,maxTime:void 0};if(this.set(r),!n)return;var T=t.config,f=T.minTime,u=T.maxTime;f&&compareTimes(n,f)<0?t.setDate(new Date(n.getTime()).setHours(f.getHours(),f.getMinutes(),f.getSeconds(),f.getMilliseconds()),!1):u&&compareTimes(n,u)>0&&t.setDate(new Date(n.getTime()).setHours(u.getHours(),u.getMinutes(),u.getSeconds(),u.getMilliseconds()))}}}}}export default minMaxTimePlugin;