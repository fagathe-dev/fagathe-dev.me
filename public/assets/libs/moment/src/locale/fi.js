/*! For license information please see fi.js.LICENSE.txt */
import moment from"../moment";var numbersPast="nolla yksi kaksi kolme neljä viisi kuusi seitsemän kahdeksan yhdeksän".split(" "),numbersFuture=["nolla","yhden","kahden","kolmen","neljän","viiden","kuuden",numbersPast[7],numbersPast[8],numbersPast[9]];function translate(a,e,t,n){var u="";switch(t){case"s":return n?"muutaman sekunnin":"muutama sekunti";case"ss":return n?"sekunnin":"sekuntia";case"m":return n?"minuutin":"minuutti";case"mm":u=n?"minuutin":"minuuttia";break;case"h":return n?"tunnin":"tunti";case"hh":u=n?"tunnin":"tuntia";break;case"d":return n?"päivän":"päivä";case"dd":u=n?"päivän":"päivää";break;case"M":return n?"kuukauden":"kuukausi";case"MM":u=n?"kuukauden":"kuukautta";break;case"y":return n?"vuoden":"vuosi";case"yy":u=n?"vuoden":"vuotta"}return u=verbalNumber(a,n)+" "+u}function verbalNumber(a,e){return a<10?e?numbersFuture[a]:numbersPast[a]:a}export default moment.defineLocale("fi",{months:"tammikuu_helmikuu_maaliskuu_huhtikuu_toukokuu_kesäkuu_heinäkuu_elokuu_syyskuu_lokakuu_marraskuu_joulukuu".split("_"),monthsShort:"tammi_helmi_maalis_huhti_touko_kesä_heinä_elo_syys_loka_marras_joulu".split("_"),weekdays:"sunnuntai_maanantai_tiistai_keskiviikko_torstai_perjantai_lauantai".split("_"),weekdaysShort:"su_ma_ti_ke_to_pe_la".split("_"),weekdaysMin:"su_ma_ti_ke_to_pe_la".split("_"),longDateFormat:{LT:"HH.mm",LTS:"HH.mm.ss",L:"DD.MM.YYYY",LL:"Do MMMM[ta] YYYY",LLL:"Do MMMM[ta] YYYY, [klo] HH.mm",LLLL:"dddd, Do MMMM[ta] YYYY, [klo] HH.mm",l:"D.M.YYYY",ll:"Do MMM YYYY",lll:"Do MMM YYYY, [klo] HH.mm",llll:"ddd, Do MMM YYYY, [klo] HH.mm"},calendar:{sameDay:"[tänään] [klo] LT",nextDay:"[huomenna] [klo] LT",nextWeek:"dddd [klo] LT",lastDay:"[eilen] [klo] LT",lastWeek:"[viime] dddd[na] [klo] LT",sameElse:"L"},relativeTime:{future:"%s päästä",past:"%s sitten",s:translate,ss:translate,m:translate,mm:translate,h:translate,hh:translate,d:translate,dd:translate,M:translate,MM:translate,y:translate,yy:translate},dayOfMonthOrdinalParse:/\d{1,2}\./,ordinal:"%d.",week:{dow:1,doy:4}});