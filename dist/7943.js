"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[7943,6740],{6740:(t,e,r)=>{r.r(e),r.d(e,{default:()=>n});const n={name:"en",weekdays:"Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday".split("_"),months:"January_February_March_April_May_June_July_August_September_October_November_December".split("_"),ordinal:function(t){var e=["th","st","nd","rd"],r=t%100;return"["+t+(e[(r-20)%10]||e[r]||e[0])+"]"}}},6597:(t,e,r)=>{r.d(e,{d:()=>k});var n=r(6740),s=1e3,i=60*s,u=3600*s,a="millisecond",o="second",c="minute",f="hour",h="day",d="week",l="month",$="quarter",_="year",M="date",m="Invalid Date",y=/^(\d{4})[-/]?(\d{1,2})?[-/]?(\d{0,2})[Tt\s]*(\d{1,2})?:?(\d{1,2})?:?(\d{1,2})?[.:]?(\d+)?$/,D=/\[([^\]]+)]|Y{1,4}|M{1,4}|D{1,2}|d{1,4}|H{1,2}|h{1,2}|a|A|m{1,2}|s{1,2}|Z{1,2}|SSS/g,v=function(t,e,r){var n=String(t);return!n||n.length>=e?t:""+Array(e+1-n.length).join(r)+t};const g={s:v,z:function(t){var e=-t.utcOffset(),r=Math.abs(e),n=Math.floor(r/60),s=r%60;return(e<=0?"+":"-")+v(n,2,"0")+":"+v(s,2,"0")},m:function t(e,r){if(e.date()<r.date())return-t(r,e);var n=12*(r.year()-e.year())+(r.month()-e.month()),s=e.clone().add(n,l),i=r-s<0,u=e.clone().add(n+(i?-1:1),l);return+(-(n+(r-s)/(i?s-u:u-s))||0)},a:function(t){return t<0?Math.ceil(t)||0:Math.floor(t)},p:function(t){return{M:l,y:_,w:d,d:h,D:M,h:f,m:c,s:o,ms:a,Q:$}[t]||String(t||"").toLowerCase().replace(/s$/,"")},u:function(t){return void 0===t}};var p="en",S={};S[p]=n.default;var w="$isDayjsObject",Y=function(t){return t instanceof L||!(!t||!t[w])},O=function t(e,r,n){var s;if(!e)return p;if("string"==typeof e){var i=e.toLowerCase();S[i]&&(s=i),r&&(S[i]=r,s=i);var u=e.split("-");if(!s&&u.length>1)return t(u[0])}else{var a=e.name;S[a]=e,s=a}return!n&&s&&(p=s),s||!n&&p},k=function(t,e){if(Y(t))return t.clone();var r="object"==typeof e?e:{};return r.date=t,r.args=arguments,new L(r)},b=g;b.l=O,b.i=Y,b.w=function(t,e){return k(t,{locale:e.$L,utc:e.$u,x:e.$x,$offset:e.$offset})};var L=function(){function t(t){this.$L=O(t.locale,null,!0),this.parse(t),this.$x=this.$x||t.x||{},this[w]=!0}var e=t.prototype;return e.parse=function(t){this.$d=function(t){var e=t.date,r=t.utc;if(null===e)return new Date(NaN);if(b.u(e))return new Date;if(e instanceof Date)return new Date(e);if("string"==typeof e&&!/Z$/i.test(e)){var n=e.match(y);if(n){var s=n[2]-1||0,i=(n[7]||"0").substring(0,3);return r?new Date(Date.UTC(n[1],s,n[3]||1,n[4]||0,n[5]||0,n[6]||0,i)):new Date(n[1],s,n[3]||1,n[4]||0,n[5]||0,n[6]||0,i)}}return new Date(e)}(t),this.init()},e.init=function(){var t=this.$d;this.$y=t.getFullYear(),this.$M=t.getMonth(),this.$D=t.getDate(),this.$W=t.getDay(),this.$H=t.getHours(),this.$m=t.getMinutes(),this.$s=t.getSeconds(),this.$ms=t.getMilliseconds()},e.$utils=function(){return b},e.isValid=function(){return this.$d.toString()!==m},e.isSame=function(t,e){var r=k(t);return this.startOf(e)<=r&&r<=this.endOf(e)},e.isAfter=function(t,e){return k(t)<this.startOf(e)},e.isBefore=function(t,e){return this.endOf(e)<k(t)},e.$g=function(t,e,r){return b.u(t)?this[e]:this.set(r,t)},e.unix=function(){return Math.floor(this.valueOf()/1e3)},e.valueOf=function(){return this.$d.getTime()},e.startOf=function(t,e){var r=this,n=!!b.u(e)||e,s=b.p(t),i=function(t,e){var s=b.w(r.$u?Date.UTC(r.$y,e,t):new Date(r.$y,e,t),r);return n?s:s.endOf(h)},u=function(t,e){return b.w(r.toDate()[t].apply(r.toDate("s"),(n?[0,0,0,0]:[23,59,59,999]).slice(e)),r)},a=this.$W,$=this.$M,m=this.$D,y="set"+(this.$u?"UTC":"");switch(s){case _:return n?i(1,0):i(31,11);case l:return n?i(1,$):i(0,$+1);case d:var D=this.$locale().weekStart||0,v=(a<D?a+7:a)-D;return i(n?m-v:m+(6-v),$);case h:case M:return u(y+"Hours",0);case f:return u(y+"Minutes",1);case c:return u(y+"Seconds",2);case o:return u(y+"Milliseconds",3);default:return this.clone()}},e.endOf=function(t){return this.startOf(t,!1)},e.$set=function(t,e){var r,n=b.p(t),s="set"+(this.$u?"UTC":""),i=(r={},r[h]=s+"Date",r[M]=s+"Date",r[l]=s+"Month",r[_]=s+"FullYear",r[f]=s+"Hours",r[c]=s+"Minutes",r[o]=s+"Seconds",r[a]=s+"Milliseconds",r)[n],u=n===h?this.$D+(e-this.$W):e;if(n===l||n===_){var d=this.clone().set(M,1);d.$d[i](u),d.init(),this.$d=d.set(M,Math.min(this.$D,d.daysInMonth())).$d}else i&&this.$d[i](u);return this.init(),this},e.set=function(t,e){return this.clone().$set(t,e)},e.get=function(t){return this[b.p(t)]()},e.add=function(t,e){var r,n=this;t=Number(t);var a=b.p(e),$=function(e){var r=k(n);return b.w(r.date(r.date()+Math.round(e*t)),n)};if(a===l)return this.set(l,this.$M+t);if(a===_)return this.set(_,this.$y+t);if(a===h)return $(1);if(a===d)return $(7);var M=(r={},r[c]=i,r[f]=u,r[o]=s,r)[a]||1,m=this.$d.getTime()+t*M;return b.w(m,this)},e.subtract=function(t,e){return this.add(-1*t,e)},e.format=function(t){var e=this,r=this.$locale();if(!this.isValid())return r.invalidDate||m;var n=t||"YYYY-MM-DDTHH:mm:ssZ",s=b.z(this),i=this.$H,u=this.$m,a=this.$M,o=r.weekdays,c=r.months,f=r.meridiem,h=function(t,r,s,i){return t&&(t[r]||t(e,n))||s[r].slice(0,i)},d=function(t){return b.s(i%12||12,t,"0")},l=f||function(t,e,r){var n=t<12?"AM":"PM";return r?n.toLowerCase():n};return n.replace(D,(function(t,n){return n||function(t){switch(t){case"YY":return String(e.$y).slice(-2);case"YYYY":return b.s(e.$y,4,"0");case"M":return a+1;case"MM":return b.s(a+1,2,"0");case"MMM":return h(r.monthsShort,a,c,3);case"MMMM":return h(c,a);case"D":return e.$D;case"DD":return b.s(e.$D,2,"0");case"d":return String(e.$W);case"dd":return h(r.weekdaysMin,e.$W,o,2);case"ddd":return h(r.weekdaysShort,e.$W,o,3);case"dddd":return o[e.$W];case"H":return String(i);case"HH":return b.s(i,2,"0");case"h":return d(1);case"hh":return d(2);case"a":return l(i,u,!0);case"A":return l(i,u,!1);case"m":return String(u);case"mm":return b.s(u,2,"0");case"s":return String(e.$s);case"ss":return b.s(e.$s,2,"0");case"SSS":return b.s(e.$ms,3,"0");case"Z":return s}return null}(t)||s.replace(":","")}))},e.utcOffset=function(){return 15*-Math.round(this.$d.getTimezoneOffset()/15)},e.diff=function(t,e,r){var n,a=this,M=b.p(e),m=k(t),y=(m.utcOffset()-this.utcOffset())*i,D=this-m,v=function(){return b.m(a,m)};switch(M){case _:n=v()/12;break;case l:n=v();break;case $:n=v()/3;break;case d:n=(D-y)/6048e5;break;case h:n=(D-y)/864e5;break;case f:n=D/u;break;case c:n=D/i;break;case o:n=D/s;break;default:n=D}return r?n:b.a(n)},e.daysInMonth=function(){return this.endOf(l).$D},e.$locale=function(){return S[this.$L]},e.locale=function(t,e){if(!t)return this.$L;var r=this.clone(),n=O(t,e,!0);return n&&(r.$L=n),r},e.clone=function(){return b.w(this.$d,this)},e.toDate=function(){return new Date(this.valueOf())},e.toJSON=function(){return this.isValid()?this.toISOString():null},e.toISOString=function(){return this.$d.toISOString()},e.toString=function(){return this.$d.toUTCString()},t}(),H=L.prototype;k.prototype=H,[["$ms",a],["$s",o],["$m",c],["$H",f],["$W",h],["$M",l],["$y",_],["$D",M]].forEach((function(t){H[t[1]]=function(e){return this.$g(e,t[0],t[1])}})),k.extend=function(t,e){return t.$i||(t(e,L,k),t.$i=!0),k},k.locale=O,k.isDayjs=Y,k.unix=function(t){return k(1e3*t)},k.en=S[p],k.Ls=S,k.p={}},7943:(t,e,r)=>{r.r(e),r.d(e,{default:()=>s});var n=r(6597),s=(r(6740),{name:"tg",weekdays:"якшанбе_душанбе_сешанбе_чоршанбе_панҷшанбе_ҷумъа_шанбе".split("_"),months:"январ_феврал_март_апрел_май_июн_июл_август_сентябр_октябр_ноябр_декабр".split("_"),weekStart:1,weekdaysShort:"яшб_дшб_сшб_чшб_пшб_ҷум_шнб".split("_"),monthsShort:"янв_фев_мар_апр_май_июн_июл_авг_сен_окт_ноя_дек".split("_"),weekdaysMin:"яш_дш_сш_чш_пш_ҷм_шб".split("_"),ordinal:function(t){return t},formats:{LT:"HH:mm",LTS:"HH:mm:ss",L:"DD/MM/YYYY",LL:"D MMMM YYYY",LLL:"D MMMM YYYY HH:mm",LLLL:"dddd, D MMMM YYYY HH:mm"},relativeTime:{future:"баъди %s",past:"%s пеш",s:"якчанд сония",m:"як дақиқа",mm:"%d дақиқа",h:"як соат",hh:"%d соат",d:"як рӯз",dd:"%d рӯз",M:"як моҳ",MM:"%d моҳ",y:"як сол",yy:"%d сол"}});n.d.locale(s,null,!0)}}]);