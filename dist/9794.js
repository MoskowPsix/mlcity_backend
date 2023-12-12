"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[9794,6740],{9794:(t,e,r)=>{r.r(e),r.d(e,{default:()=>s});var n=r(6597),s=(r(6740),{name:"da",weekdays:"søndag_mandag_tirsdag_onsdag_torsdag_fredag_lørdag".split("_"),weekdaysShort:"søn._man._tirs._ons._tors._fre._lør.".split("_"),weekdaysMin:"sø._ma._ti._on._to._fr._lø.".split("_"),months:"januar_februar_marts_april_maj_juni_juli_august_september_oktober_november_december".split("_"),monthsShort:"jan._feb._mar._apr._maj_juni_juli_aug._sept._okt._nov._dec.".split("_"),weekStart:1,ordinal:function(t){return t+"."},formats:{LT:"HH:mm",LTS:"HH:mm:ss",L:"DD.MM.YYYY",LL:"D. MMMM YYYY",LLL:"D. MMMM YYYY HH:mm",LLLL:"dddd [d.] D. MMMM YYYY [kl.] HH:mm"},relativeTime:{future:"om %s",past:"%s siden",s:"få sekunder",m:"et minut",mm:"%d minutter",h:"en time",hh:"%d timer",d:"en dag",dd:"%d dage",M:"en måned",MM:"%d måneder",y:"et år",yy:"%d år"}});n.d.locale(s,null,!0)},6740:(t,e,r)=>{r.r(e),r.d(e,{default:()=>n});const n={name:"en",weekdays:"Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),months:"January_February_March_April_May_June_July_August_September_October_November_December".split("_"),ordinal:function(t){var e=["th","st","nd","rd"],r=t%100;return"["+t+(e[(r-20)%10]||e[r]||e[0])+"]"}}},6597:(t,e,r)=>{r.d(e,{d:()=>k});var n=r(6740),s=1e3,i=60*s,a=3600*s,u="millisecond",o="second",c="minute",d="hour",f="day",h="week",l="month",$="quarter",m="year",_="date",M="Invalid Date",g=/^(\d{4})[-/]?(\d{1,2})?[-/]?(\d{0,2})[Tt\s]*(\d{1,2})?:?(\d{1,2})?:?(\d{1,2})?[.:]?(\d+)?$/,y=/\[([^\]]+)]|Y{1,4}|M{1,4}|D{1,2}|d{1,4}|H{1,2}|h{1,2}|a|A|m{1,2}|s{1,2}|Z{1,2}|SSS/g,v=function(t,e,r){var n=String(t);return!n||n.length>=e?t:""+Array(e+1-n.length).join(r)+t};const D={s:v,z:function(t){var e=-t.utcOffset(),r=Math.abs(e),n=Math.floor(r/60),s=r%60;return(e<=0?"+":"-")+v(n,2,"0")+":"+v(s,2,"0")},m:function t(e,r){if(e.date()<r.date())return-t(r,e);var n=12*(r.year()-e.year())+(r.month()-e.month()),s=e.clone().add(n,l),i=r-s<0,a=e.clone().add(n+(i?-1:1),l);return+(-(n+(r-s)/(i?s-a:a-s))||0)},a:function(t){return t<0?Math.ceil(t)||0:Math.floor(t)},p:function(t){return{M:l,y:m,w:h,d:f,D:_,h:d,m:c,s:o,ms:u,Q:$}[t]||String(t||"").toLowerCase().replace(/s$/,"")},u:function(t){return void 0===t}};var p="en",S={};S[p]=n.default;var w="$isDayjsObject",Y=function(t){return t instanceof L||!(!t||!t[w])},b=function t(e,r,n){var s;if(!e)return p;if("string"==typeof e){var i=e.toLowerCase();S[i]&&(s=i),r&&(S[i]=r,s=i);var a=e.split("-");if(!s&&a.length>1)return t(a[0])}else{var u=e.name;S[u]=e,s=u}return!n&&s&&(p=s),s||!n&&p},k=function(t,e){if(Y(t))return t.clone();var r="object"==typeof e?e:{};return r.date=t,r.args=arguments,new L(r)},O=D;O.l=b,O.i=Y,O.w=function(t,e){return k(t,{locale:e.$L,utc:e.$u,x:e.$x,$offset:e.$offset})};var L=function(){function t(t){this.$L=b(t.locale,null,!0),this.parse(t),this.$x=this.$x||t.x||{},this[w]=!0}var e=t.prototype;return e.parse=function(t){this.$d=function(t){var e=t.date,r=t.utc;if(null===e)return new Date(NaN);if(O.u(e))return new Date;if(e instanceof Date)return new Date(e);if("string"==typeof e&&!/Z$/i.test(e)){var n=e.match(g);if(n){var s=n[2]-1||0,i=(n[7]||"0").substring(0,3);return r?new Date(Date.UTC(n[1],s,n[3]||1,n[4]||0,n[5]||0,n[6]||0,i)):new Date(n[1],s,n[3]||1,n[4]||0,n[5]||0,n[6]||0,i)}}return new Date(e)}(t),this.init()},e.init=function(){var t=this.$d;this.$y=t.getFullYear(),this.$M=t.getMonth(),this.$D=t.getDate(),this.$W=t.getDay(),this.$H=t.getHours(),this.$m=t.getMinutes(),this.$s=t.getSeconds(),this.$ms=t.getMilliseconds()},e.$utils=function(){return O},e.isValid=function(){return this.$d.toString()!==M},e.isSame=function(t,e){var r=k(t);return this.startOf(e)<=r&&r<=this.endOf(e)},e.isAfter=function(t,e){return k(t)<this.startOf(e)},e.isBefore=function(t,e){return this.endOf(e)<k(t)},e.$g=function(t,e,r){return O.u(t)?this[e]:this.set(r,t)},e.unix=function(){return Math.floor(this.valueOf()/1e3)},e.valueOf=function(){return this.$d.getTime()},e.startOf=function(t,e){var r=this,n=!!O.u(e)||e,s=O.p(t),i=function(t,e){var s=O.w(r.$u?Date.UTC(r.$y,e,t):new Date(r.$y,e,t),r);return n?s:s.endOf(f)},a=function(t,e){return O.w(r.toDate()[t].apply(r.toDate("s"),(n?[0,0,0,0]:[23,59,59,999]).slice(e)),r)},u=this.$W,$=this.$M,M=this.$D,g="set"+(this.$u?"UTC":"");switch(s){case m:return n?i(1,0):i(31,11);case l:return n?i(1,$):i(0,$+1);case h:var y=this.$locale().weekStart||0,v=(u<y?u+7:u)-y;return i(n?M-v:M+(6-v),$);case f:case _:return a(g+"Hours",0);case d:return a(g+"Minutes",1);case c:return a(g+"Seconds",2);case o:return a(g+"Milliseconds",3);default:return this.clone()}},e.endOf=function(t){return this.startOf(t,!1)},e.$set=function(t,e){var r,n=O.p(t),s="set"+(this.$u?"UTC":""),i=(r={},r[f]=s+"Date",r[_]=s+"Date",r[l]=s+"Month",r[m]=s+"FullYear",r[d]=s+"Hours",r[c]=s+"Minutes",r[o]=s+"Seconds",r[u]=s+"Milliseconds",r)[n],a=n===f?this.$D+(e-this.$W):e;if(n===l||n===m){var h=this.clone().set(_,1);h.$d[i](a),h.init(),this.$d=h.set(_,Math.min(this.$D,h.daysInMonth())).$d}else i&&this.$d[i](a);return this.init(),this},e.set=function(t,e){return this.clone().$set(t,e)},e.get=function(t){return this[O.p(t)]()},e.add=function(t,e){var r,n=this;t=Number(t);var u=O.p(e),$=function(e){var r=k(n);return O.w(r.date(r.date()+Math.round(e*t)),n)};if(u===l)return this.set(l,this.$M+t);if(u===m)return this.set(m,this.$y+t);if(u===f)return $(1);if(u===h)return $(7);var _=(r={},r[c]=i,r[d]=a,r[o]=s,r)[u]||1,M=this.$d.getTime()+t*_;return O.w(M,this)},e.subtract=function(t,e){return this.add(-1*t,e)},e.format=function(t){var e=this,r=this.$locale();if(!this.isValid())return r.invalidDate||M;var n=t||"YYYY-MM-DDTHH:mm:ssZ",s=O.z(this),i=this.$H,a=this.$m,u=this.$M,o=r.weekdays,c=r.months,d=r.meridiem,f=function(t,r,s,i){return t&&(t[r]||t(e,n))||s[r].slice(0,i)},h=function(t){return O.s(i%12||12,t,"0")},l=d||function(t,e,r){var n=t<12?"AM":"PM";return r?n.toLowerCase():n};return n.replace(y,(function(t,n){return n||function(t){switch(t){case"YY":return String(e.$y).slice(-2);case"YYYY":return O.s(e.$y,4,"0");case"M":return u+1;case"MM":return O.s(u+1,2,"0");case"MMM":return f(r.monthsShort,u,c,3);case"MMMM":return f(c,u);case"D":return e.$D;case"DD":return O.s(e.$D,2,"0");case"d":return String(e.$W);case"dd":return f(r.weekdaysMin,e.$W,o,2);case"ddd":return f(r.weekdaysShort,e.$W,o,3);case"dddd":return o[e.$W];case"H":return String(i);case"HH":return O.s(i,2,"0");case"h":return h(1);case"hh":return h(2);case"a":return l(i,a,!0);case"A":return l(i,a,!1);case"m":return String(a);case"mm":return O.s(a,2,"0");case"s":return String(e.$s);case"ss":return O.s(e.$s,2,"0");case"SSS":return O.s(e.$ms,3,"0");case"Z":return s}return null}(t)||s.replace(":","")}))},e.utcOffset=function(){return 15*-Math.round(this.$d.getTimezoneOffset()/15)},e.diff=function(t,e,r){var n,u=this,_=O.p(e),M=k(t),g=(M.utcOffset()-this.utcOffset())*i,y=this-M,v=function(){return O.m(u,M)};switch(_){case m:n=v()/12;break;case l:n=v();break;case $:n=v()/3;break;case h:n=(y-g)/6048e5;break;case f:n=(y-g)/864e5;break;case d:n=y/a;break;case c:n=y/i;break;case o:n=y/s;break;default:n=y}return r?n:O.a(n)},e.daysInMonth=function(){return this.endOf(l).$D},e.$locale=function(){return S[this.$L]},e.locale=function(t,e){if(!t)return this.$L;var r=this.clone(),n=b(t,e,!0);return n&&(r.$L=n),r},e.clone=function(){return O.w(this.$d,this)},e.toDate=function(){return new Date(this.valueOf())},e.toJSON=function(){return this.isValid()?this.toISOString():null},e.toISOString=function(){return this.$d.toISOString()},e.toString=function(){return this.$d.toUTCString()},t}(),H=L.prototype;k.prototype=H,[["$ms",u],["$s",o],["$m",c],["$H",d],["$W",f],["$M",l],["$y",m],["$D",_]].forEach((function(t){H[t[1]]=function(e){return this.$g(e,t[0],t[1])}})),k.extend=function(t,e){return t.$i||(t(e,L,k),t.$i=!0),k},k.locale=b,k.isDayjs=Y,k.unix=function(t){return k(1e3*t)},k.en=S[p],k.Ls=S,k.p={}}}]);