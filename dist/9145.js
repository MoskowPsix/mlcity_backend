"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[9145],{368:(t,e,r)=>{r.d(e,{g:()=>f});var n,o=r(655),s=function(t){function e(e,r){return t.call(this)||this}return o.ZT(e,t),e.prototype.schedule=function(t,e){return void 0===e&&(e=0),this},e}(r(9771).w),a=function(t){function e(e,r){var n=t.call(this,e,r)||this;return n.scheduler=e,n.work=r,n.pending=!1,n}return o.ZT(e,t),e.prototype.schedule=function(t,e){if(void 0===e&&(e=0),this.closed)return this;this.state=t;var r=this.id,n=this.scheduler;return null!=r&&(this.id=this.recycleAsyncId(n,r,e)),this.pending=!0,this.delay=e,this.id=this.id||this.requestAsyncId(n,this.id,e),this},e.prototype.requestAsyncId=function(t,e,r){return void 0===r&&(r=0),setInterval(t.flush.bind(t,this),r)},e.prototype.recycleAsyncId=function(t,e,r){if(void 0===r&&(r=0),null!==r&&this.delay===r&&!1===this.pending)return e;clearInterval(e)},e.prototype.execute=function(t,e){if(this.closed)return new Error("executing a cancelled action");this.pending=!1;var r=this._execute(t,e);if(r)return r;!1===this.pending&&null!=this.id&&(this.id=this.recycleAsyncId(this.scheduler,this.id,null))},e.prototype._execute=function(t,e){var r=!1,n=void 0;try{this.work(t)}catch(t){r=!0,n=!!t&&t||new Error(t)}if(r)return this.unsubscribe(),n},e.prototype._unsubscribe=function(){var t=this.id,e=this.scheduler,r=e.actions,n=r.indexOf(this);this.work=null,this.state=null,this.pending=!1,this.scheduler=null,-1!==n&&r.splice(n,1),null!=t&&(this.id=this.recycleAsyncId(e,t,null)),this.delay=null},e}(s),i=function(){function t(e,r){void 0===r&&(r=t.now),this.SchedulerAction=e,this.now=r}return t.prototype.schedule=function(t,e,r){return void 0===e&&(e=0),new this.SchedulerAction(this,t).schedule(r,e)},t.now=function(){return Date.now()},t}(),c=function(t){function e(r,n){void 0===n&&(n=i.now);var o=t.call(this,r,(function(){return e.delegate&&e.delegate!==o?e.delegate.now():n()}))||this;return o.actions=[],o.active=!1,o.scheduled=void 0,o}return o.ZT(e,t),e.prototype.schedule=function(r,n,o){return void 0===n&&(n=0),e.delegate&&e.delegate!==this?e.delegate.schedule(r,n,o):t.prototype.schedule.call(this,r,n,o)},e.prototype.flush=function(t){var e=this.actions;if(this.active)e.push(t);else{var r;this.active=!0;do{if(r=t.execute(t.state,t.delay))break}while(t=e.shift());if(this.active=!1,r){for(;t=e.shift();)t.unsubscribe();throw r}}},e}(i),l=new c(a),u=r(979),d=r(5631),h=r(1075),g=r(1906);n||(n={});var p=function(){function t(t,e,r){this.kind=t,this.value=e,this.error=r,this.hasValue="N"===t}return t.prototype.observe=function(t){switch(this.kind){case"N":return t.next&&t.next(this.value);case"E":return t.error&&t.error(this.error);case"C":return t.complete&&t.complete()}},t.prototype.do=function(t,e,r){switch(this.kind){case"N":return t&&t(this.value);case"E":return e&&e(this.error);case"C":return r&&r()}},t.prototype.accept=function(t,e,r){return t&&"function"==typeof t.next?this.observe(t):this.do(t,e,r)},t.prototype.toObservable=function(){var t;switch(this.kind){case"N":return(0,h.of)(this.value);case"E":return t=this.error,new g.y((function(e){return e.error(t)}));case"C":return(0,d.c)()}throw new Error("unexpected notification kind value")},t.createNext=function(e){return void 0!==e?new t("N",e):t.undefinedValueNotification},t.createError=function(e){return new t("E",void 0,e)},t.createComplete=function(){return t.completeNotification},t.completeNotification=new t("C"),t.undefinedValueNotification=new t("N",void 0),t}();function f(t,e){void 0===e&&(e=l);var r,n=(r=t)instanceof Date&&!isNaN(+r)?+t-e.now():Math.abs(t);return function(t){return t.lift(new y(n,e))}}var y=function(){function t(t,e){this.delay=t,this.scheduler=e}return t.prototype.call=function(t,e){return e.subscribe(new b(t,this.delay,this.scheduler))},t}(),b=function(t){function e(e,r,n){var o=t.call(this,e)||this;return o.delay=r,o.scheduler=n,o.queue=[],o.active=!1,o.errored=!1,o}return o.ZT(e,t),e.dispatch=function(t){for(var e=t.source,r=e.queue,n=t.scheduler,o=t.destination;r.length>0&&r[0].time-n.now()<=0;)r.shift().notification.observe(o);if(r.length>0){var s=Math.max(0,r[0].time-n.now());this.schedule(t,s)}else this.unsubscribe(),e.active=!1},e.prototype._schedule=function(t){this.active=!0,this.destination.add(t.schedule(e.dispatch,this.delay,{source:this,destination:this.destination,scheduler:t}))},e.prototype.scheduleNotification=function(t){if(!0!==this.errored){var e=this.scheduler,r=new m(e.now()+this.delay,t);this.queue.push(r),!1===this.active&&this._schedule(e)}},e.prototype._next=function(t){this.scheduleNotification(p.createNext(t))},e.prototype._error=function(t){this.errored=!0,this.queue=[],this.destination.error(t),this.unsubscribe()},e.prototype._complete=function(){this.scheduleNotification(p.createComplete()),this.unsubscribe()},e}(u.L),m=function(){return function(t,e){this.time=t,this.notification=e}}()},2876:(t,e,r)=>{r.d(e,{X:()=>s});var n=r(655),o=r(979);function s(t){return void 0===t&&(t=-1),function(e){return e.lift(new a(t,e))}}var a=function(){function t(t,e){this.count=t,this.source=e}return t.prototype.call=function(t,e){return e.subscribe(new i(t,this.count,this.source))},t}(),i=function(t){function e(e,r,n){var o=t.call(this,e)||this;return o.count=r,o.source=n,o}return n.ZT(e,t),e.prototype.error=function(e){if(!this.isStopped){var r=this.source,n=this.count;if(0===n)return t.prototype.error.call(this,e);n>-1&&(this.count=n-1),r.subscribe(this._unsubscribeAndRecycle())}},e}(o.L)},3438:(t,e,r)=>{r.d(e,{b:()=>i});var n=r(655),o=r(979);function s(){}var a=r(4156);function i(t,e,r){return function(n){return n.lift(new c(t,e,r))}}var c=function(){function t(t,e,r){this.nextOrObserver=t,this.error=e,this.complete=r}return t.prototype.call=function(t,e){return e.subscribe(new l(t,this.nextOrObserver,this.error,this.complete))},t}(),l=function(t){function e(e,r,n,o){var i=t.call(this,e)||this;return i._tapNext=s,i._tapError=s,i._tapComplete=s,i._tapError=n||s,i._tapComplete=o||s,(0,a.m)(r)?(i._context=i,i._tapNext=r):r&&(i._context=r,i._tapNext=r.next||s,i._tapError=r.error||s,i._tapComplete=r.complete||s),i}return n.ZT(e,t),e.prototype._next=function(t){try{this._tapNext.call(this._context,t)}catch(t){return void this.destination.error(t)}this.destination.next(t)},e.prototype._error=function(t){try{this._tapError.call(this._context,t)}catch(t){return void this.destination.error(t)}this.destination.error(t)},e.prototype._complete=function(){try{this._tapComplete.call(this._context)}catch(t){return void this.destination.error(t)}return this.destination.complete()},e}(o.L)},7935:(t,e,r)=>{r.d(e,{Z:()=>l});var n=r(6252),o=r(9963);const s={class:"border border-gray-700 rounded-lg grid grid-cols-2 p-2 dark:bg-gray-800 bg-gray-50 mt-1"},a={class:"flex justify-evenly"},i={class:"flex justify-evenly"},c={name:"PaginateBar",props:["nextPage","backPage"],methods:{onBackPage(){this.$emit("onBackPage",this.backPage)},onNextPage(){this.$emit("onNextPage",this.nextPage)}}},l=(0,r(3744).Z)(c,[["render",function(t,e,r,c,l,u){return(0,n.wg)(),(0,n.iD)("div",s,[(0,n._)("div",a,[r.backPage?((0,n.wg)(),(0,n.iD)("button",{key:0,onClick:e[0]||(e[0]=(0,o.withModifiers)((t=>u.onBackPage()),["prevent"])),class:"mr-2 border bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-gray-700 border-gray-400 dark:hover:text-color-300/70 dark:text-gray-400/70 dark:border-gray-600 dark:bg-gray-900/40 dark:hover:bg-gray-700 pt-2 pb-2 pl-4 pr-4 shadow-lg rounded-lg"},"Назад")):(0,n.kq)("v-if",!0)]),(0,n._)("div",i,[r.nextPage?((0,n.wg)(),(0,n.iD)("button",{key:0,onClick:e[1]||(e[1]=(0,o.withModifiers)((t=>u.onNextPage()),["prevent"])),class:"border bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-gray-700 border-gray-400 dark:hover:text-color-300/70 dark:text-gray-400/70 dark:border-gray-600 dark:bg-gray-900/40 dark:hover:bg-gray-700 pt-2 pb-2 pl-4 pr-4 shadow-lg rounded-lg"},"Далее")):(0,n.kq)("v-if",!0)])])}]])},9145:(t,e,r)=>{r.r(e),r.d(e,{default:()=>at});var n=r(6252);const o={key:0,class:"flex justify-center m-1"};var s=r(9305),a=r(3048),i=r(1257),c=r(4072);const l=(0,s.Q_)("useContent",{actions:{getContents:t=>(0,c.D)(i.Z.get("history-content",{params:t}))}});r(6110);var u=r(9233);const d=(0,s.Q_)("useHistoryContentsFilter",{state:()=>({contentName:new u.X(localStorage.getItem("contentNameFilter")||""),contentDate:new u.X(localStorage.getItem("contentDateFilter")||"~"),contentSponsor:new u.X(localStorage.getItem("contentSponsorFilter")||""),contentSearchText:new u.X(localStorage.getItem("contentTextFilter")||""),contentStatuses:new u.X(localStorage.getItem("contentStatusesFilter")||"1"),contentStatusLast:new u.X(localStorage.getItem("contentStatusLastFilter")||"true"),contentUser:new u.X(localStorage.getItem("contentUserFilter")||"")}),actions:{setContentName(t){localStorage.setItem("contentNameFilter",t),this.contentName=t},getContentName:()=>localStorage.getItem("contentNameFilter"),setContentDate(t){localStorage.setItem("contentDateFilter",t),this.contentDate=t},getContentDate(){return localStorage.getItem("contentDateFilter")||this.contentDate.getValue()},setContentSponsor(t){localStorage.setItem("contentSponsorFilter",t),this.contentSponsor=t},getContentSponsor:()=>localStorage.getItem("contentSponsorFilter"),setContentText(t){localStorage.setItem("contentTextFilter",t),this.contentSearchText=t},getContentText:()=>localStorage.getItem("contentTextFilter"),setContentStatuses(t){localStorage.setItem("contentStatusesFilter",t),this.contentStatuses=t},getContentStatuses:()=>localStorage.getItem("contentStatusesFilter"),setContentStatusLast(t){localStorage.setItem("contentStatusLastFilter",t),this.contentStatusLast=t,console.log(this.contentStatusLast)},getContentStatusLast:()=>localStorage.getItem("contentStatusLastFilter"),setContentUser(t){localStorage.setItem("contentUserFilter",t),this.contentUser=t},getContentUser:()=>localStorage.getItem("contentUserFilter")}}),h=(0,s.Q_)("useHistoryContentsQueryBuilder",{actions:{queryBuilder(t){return this.updateParams(),"contentsForPageContents"===t&&this.contentsForPageContents(),this.queryParams},updateParams(){this.name=d().getContentName(),this.date=d().getContentDate(),this.sponsor=d().getContentSponsor(),this.searchText=d().getContentText(),this.statuses=d().getContentStatuses(),this.statusLast=d().getContentStatusLast(),this.user=d().getContentUser()},contentsForPageContents(){let t=["",""];this.date&&(t=this.date.split("~")),this.queryParams={name:this.name,dateStart:t[0],dateEnd:t[1],sponsor:this.sponsor,searchText:this.searchText,statuses:this.statuses,statusesLast:this.statusLast,user:this.user,page:this.pageContentsForPageContent}},setPageContentsForPageContents(t){this.pageContentsForPageContent=t}},state:()=>({queryParams:[],name:null,date:null,sponsor:null,searchText:null,statuses:null,statusLast:null,user:null,pageContentsForPageContent:null})});var g=r(2876),p=r(368),f=r(3438),y=r(5709),b=r(486),m=r(1558),x=r(5244),v=r(1075),k=r(5631),w=r(5238);class C{static error_content="При загрузке контента на редактирование произошла ошибка";static warning_content="Неудачная загрузка конткнта на модерацию";static info_content="При загрузке записей на редактирование ничего не пришло"}var _=r(7935),S=r(3577);const P={class:"relative z-10 overflow-x-auto border border-gray-300 dark:border-gray-700 dark:border-gra rounded-lg"},D={class:"border border-gray-300 dark:border-gray-700 rounded-lg w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"},N=(0,n._)("thead",{class:"border border-gray-300 dark:border-gray-700 rounded-lg text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-800 dark:text-gray-400"},[(0,n._)("tr",null,[(0,n._)("th",{scope:"col",class:"px-6 py-3"}," id "),(0,n._)("th",{scope:"col",class:"px-6 py-3"}," Тип "),(0,n._)("th",{scope:"col",class:"px-6 py-3"}," Имя "),(0,n._)("th",{scope:"col",class:"px-6 py-3"}," Автор "),(0,n._)("th",{scope:"col",class:"px-6 py-3"}," Дата начала "),(0,n._)("th",{scope:"col",class:"px-6 py-3"}," Дата окончания ")])],-1),T={class:"bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"},F={scope:"row",class:"px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"},L={class:"px-6 py-4"},U={class:"flex justify-between"},I={key:0,"aria-hidden":"true",class:"flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white enable-background:new 0 0 24 24",fill:"currentColor",viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},V=[(0,n._)("path",{d:"M16.53 11.06L15.47 10l-4.88 4.88-2.12-2.12-1.06 1.06L10.59 17l5.94-5.94zM19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z"},null,-1)],A={key:1,"aria-hidden":"true",class:"flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white",fill:"currentColor",viewBox:"0 0 500 600",xmlns:"http://www.w3.org/2000/svg"},H=[(0,n._)("path",{d:"M 501.62 92.11 L 267.24 2.04 a 31.958 31.958 0 0 0 -22.47 0 L 10.38 92.11 A 16.001 16.001 0 0 0 0 107.09 V 144 c 0 8.84 7.16 16 16 16 h 480 c 8.84 0 16 -7.16 16 -16 v -36.91 c 0 -6.67 -4.14 -12.64 -10.38 -14.98 Z M 64 192 v 160 H 48 c -8.84 0 -16 7.16 -16 16 v 48 h 448 v -48 c 0 -8.84 -7.16 -16 -16 -16 h -16 V 192 h -64 v 160 h -96 V 192 h -64 v 160 h -96 V 192 H 64 Z m 432 256 H 16 c -8.84 0 -16 7.16 -16 16 v 32 c 0 8.84 7.16 16 16 16 h 480 c 8.84 0 16 -7.16 16 -16 v -32 c 0 -8.84 -7.16 -16 -16 -16 Z"},null,-1)],M={key:2,viewBox:"0 0 128 128",xmlns:"http://www.w3.org/2000/svg","xmlns:xlink":"http://www.w3.org/1999/xlink",fill:"currentColor","aria-hidden":"true",class:"flex-shrink-0 w-6 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"},q=[(0,n._)("path",{d:"M110.55 117.41L76.92 63.69l29.73-44.82c.45-.69.5-1.57.1-2.3a2.222 2.222 0 0 0-1.97-1.18H80.34c-.74 0-1.45.38-1.86 1L51.49 57.08V17.64c0-1.24-1.01-2.24-2.24-2.24h-21.9c-1.24 0-2.24 1-2.24 2.24V118.6c0 1.24 1 2.24 2.24 2.24h21.9c1.23 0 2.24-1 2.24-2.24V70.55l30.82 49.24c.41.65 1.13 1.05 1.9 1.05h24.44c.81 0 1.57-.44 1.96-1.16c.39-.71.37-1.58-.06-2.27z"},null,-1)],B={class:"px-6 py-4"},E={class:"px-6 py-4"},Z={class:"dark:text-gray-200 text-base"},O={class:"dark:text-gray-400 text-ml"},X={class:"px-6 py-4"},Q={class:"px-6 py-4"},Y={name:"ContentTable",props:["contents"],methods:{}};var z=r(3744);const $=(0,z.Z)(Y,[["render",function(t,e,r,o,s,a){return(0,n.wg)(),(0,n.iD)(n.HY,null,[(0,n.kq)(' <div class="text-gray-300 p-2 border" v-for="content in contents">\r\n        {{content.name}}\r\n    </div> '),(0,n._)("div",P,[(0,n._)("table",D,[N,(0,n._)("tbody",null,[((0,n.wg)(!0),(0,n.iD)(n.HY,null,(0,n.Ko)(r.contents,(t=>((0,n.wg)(),(0,n.iD)("tr",T,[(0,n._)("th",F,(0,S.toDisplayString)(t.id),1),(0,n._)("td",L,[(0,n._)("div",U,["App\\Models\\Event"===t.history_contentable_type?((0,n.wg)(),(0,n.iD)("svg",I,V)):(0,n.kq)("v-if",!0),"App\\Models\\Sight"===t.history_contentable_type?((0,n.wg)(),(0,n.iD)("svg",A,H)):(0,n.kq)("v-if",!0),t.cult_id?((0,n.wg)(),(0,n.iD)("svg",M,q)):(0,n.kq)("v-if",!0)])]),(0,n._)("td",B,(0,S.toDisplayString)(t.name),1),(0,n._)("td",E,[(0,n._)("h1",Z,(0,S.toDisplayString)(t.user.id)+" / "+(0,S.toDisplayString)(t.user.name),1),(0,n._)("p",O,(0,S.toDisplayString)(t.user.email),1)]),(0,n._)("td",X,(0,S.toDisplayString)(t.date_start),1),(0,n._)("td",Q,(0,S.toDisplayString)(t.date_end),1)])))),256))])])])],2112)}]]);var j=r(9963);const K={class:"border dark:bg-gray-800 bg-gray-200 border-gray-300 dark:border-gray-700 shadow rounded grid grid-cols-4 gap-6 p-6 dark:text-gray-300"},R={class:"flex border p-1 rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"},W=["value"],G=(0,n._)("label",{"data-te-select-label-ref":""},"статусы",-1),J={class:"mb-[0.125rem] block min-h-[1.5rem] pl-7 border rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"},tt=(0,n._)("label",{class:"inline-block pl-[0.15rem] mt-[0.4rem] hover:cursor-pointer",for:"checkboxDefault"}," Последний статус ",-1);var et=r(6618),rt=r(7145);const nt={name:"HistoryContentFilter",components:{VueTailwindDatepicker:r(946).Z},setup:()=>({destroy$:new x.xQ,formatter:{date:"YYYY-MM-DD hh:mm:ss",month:"MM"}}),data(){return{contentName:this.getContentName(),contentDate:{startDate:this.getContentDate().split("~")[0].slice(0,19).replace("T"," "),endDate:this.getContentDate().split("~")[1].slice(0,19).replace("T"," ")},contentSponsor:this.getContentSponsor(),contentSearchText:this.getContentText(),contentStatuses:this.getContentStatuses(),contentStatusLast:this.getContentStatusLast(),contentUser:this.getContentUser(),statuses:[],allStatuses:[]}},methods:{...(0,s.nv)(d,["setContentName","setContentDate","setContentSponsor","setContentText","setContentStatuses","setContentStatusLast","setContentUser","getContentName","getContentDate","getContentSponsor","getContentText","getContentStatuses","getContentStatusLast","getContentUser"]),...(0,s.nv)(et.e,["getStatuses"]),getAllStatuses(){this.getStatuses().pipe((0,y.U)((t=>{this.statuses=t.data.statuses})),(0,b.K)((t=>(console.log(t),(0,v.of)(k.E)))),(0,m.R)(this.destroy$)).subscribe()}},mounted(){this.getAllStatuses(),(0,rt.vN)({Select:rt.Ph},{allowReinits:!0})},watch:{contentName(t){(t.length>3||0==t)&&this.setContentName(t)},contentDate(t){this.setContentDate(t.startDate+"~"+t.endDate)},contentSponsor(t){(t.length>3||0==t)&&this.setContentSponsor(t)},contentSearchText(t){(t.length>3||0==t)&&this.setContentText(t)},contentStatuses(t){this.setContentStatuses(t)},contentStatusLast(t){this.setContentStatusLast(t)},contentUser(t){(t.length>3||0==t)&&this.setContentUser(t)}}},ot=(0,z.Z)(nt,[["render",function(t,e,r,o,s,a){const i=(0,n.up)("VueTailwindDatepicker");return(0,n.wg)(),(0,n.iD)("div",K,[(0,n.wy)((0,n._)("input",{"data-te-input-wrapper-init":"","onUpdate:modelValue":e[0]||(e[0]=t=>s.contentName=t),type:"text",name:"name",id:"name",placeholder:"Название",class:"rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"},null,512),[[j.vModelText,s.contentName]]),(0,n.wy)((0,n._)("input",{"onUpdate:modelValue":e[1]||(e[1]=t=>s.contentSponsor=t),type:"text",name:"sponsor",id:"sponsor",placeholder:"Спонсор мероприятия",class:"rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"},null,512),[[j.vModelText,s.contentSponsor]]),(0,n.wy)((0,n._)("input",{"onUpdate:modelValue":e[2]||(e[2]=t=>s.contentSearchText=t),type:"text",name:"text",id:"text",placeholder:"Поиск по тексту",class:"rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"},null,512),[[j.vModelText,s.contentSearchText]]),(0,n.wy)((0,n._)("input",{"onUpdate:modelValue":e[3]||(e[3]=t=>s.contentUser=t),type:"text",name:"user",id:"user",placeholder:"Имя автора",class:"rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"},null,512),[[j.vModelText,s.contentUser]]),(0,n.Wm)(i,{formatter:o.formatter,modelValue:s.contentDate,"onUpdate:modelValue":e[4]||(e[4]=t=>s.contentDate=t),placeholder:"Дата начала и конца"},null,8,["formatter","modelValue"]),(0,n._)("div",R,[(0,n._)("div",null,[(0,n.wy)((0,n._)("select",{class:"h-6","onUpdate:modelValue":e[5]||(e[5]=t=>s.contentStatuses=t),"data-te-select-init":"",multiple:""},[((0,n.wg)(!0),(0,n.iD)(n.HY,null,(0,n.Ko)(s.statuses,(t=>((0,n.wg)(),(0,n.iD)("option",{value:t.id+" "},(0,S.toDisplayString)(t.name),9,W)))),256))],512),[[j.vModelSelect,s.contentStatuses]]),G])]),(0,n._)("div",J,[(0,n.wy)((0,n._)("input",{"true-value":1,"false-value":0,class:"relative float-left -ml-[0.5rem] mr-[6px] mt-[0.5rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]",type:"checkbox","onUpdate:modelValue":e[6]||(e[6]=t=>s.contentStatusLast=t),id:"checkboxDefault"},null,512),[[j.vModelCheckbox,s.contentStatusLast]]),tt]),(0,n.wy)((0,n._)("input",{"onUpdate:modelValue":e[7]||(e[7]=t=>s.contentUser=t),type:"text",name:"user",id:"user",placeholder:"Имя или почта автора",class:"rounded-lg dark:bg-gray-800 dark:border-gray-700 border-gray-400/50"},null,512),[[j.vModelText,s.contentUser]])])}]]),st={name:"HistoryContent",setup:()=>({destroy$:new x.xQ}),data:()=>({contents:null,total:0,nextPage:null,backPage:null}),components:{PaginateBar:_.Z,HistoryContentTable:$,HistoryContentFilter:ot},computed:{...(0,s.rn)(d,["contentName","contentDate","contentSponsor","contentSearchText","contentStatuses","contentStatusLast","contentUser"])},methods:{...(0,s.nv)(a.f,["openLoaderFullPage","closeLoaderFullPage"]),...(0,s.nv)(l,["getContents"]),...(0,s.nv)(w.E,["showToast"]),...(0,s.nv)(h,["queryBuilder","setPageContentsForPageContents"]),viewBackPage(){this.setPageContentsForPageContents(this.backPage),this.getAllContents()},viewNextPage(){this.setPageContentsForPageContents(this.nextPage),this.getAllContents()},getAllContents(){this.openLoaderFullPage(),this.getContents(this.queryBuilder("contentsForPageContents")).pipe((0,g.X)(3),(0,p.g)(100),(0,f.b)((()=>{this.closeLoaderFullPage()})),(0,y.U)((t=>{console.log(t.data),t.data.historyContents.data.length?(this.contents=t.data.historyContents.data,this.nextPage=t.data.historyContents.next_cursor,this.backPage=t.data.historyContents.prev_cursor):(this.contents=null,this.showToast(C.info_content,"info"))})),(0,b.K)((t=>(console.log(t),this.closeLoaderFullPage(),399<t.response.status&&t.response.status<500&&this.showToast(C.warning_content+": "+t.message,"warning"),499<t.response.status&&t.response.status<600&&this.showToast(C.error_content+": "+t.message,"error"),(0,v.of)(k.E)))),(0,m.R)(this.destroy$)).subscribe()}},mounted(){this.getAllContents()},watch:{contentName(){this.getAllContents()},contentDate(){this.getAllContents()},contentSponsor(){this.getAllContents()},contentSearchText(){this.getAllContents()},contentStatuses(){this.getAllContents()},contentStatusLast(){this.getAllContents()},contentUser(){this.getAllContents()}}},at=(0,z.Z)(st,[["render",function(t,e,r,s,a,i){const c=(0,n.up)("HistoryContentFilter"),l=(0,n.up)("HistoryContentTable"),u=(0,n.up)("PaginateBar");return(0,n.wg)(),(0,n.iD)(n.HY,null,[(0,n.Wm)(c,{class:"m-1"}),(0,n.Wm)(l,{contents:a.contents,class:"m-1"},null,8,["contents"]),a.nextPage||a.backPage?((0,n.wg)(),(0,n.iD)("div",o,[(0,n.Wm)(u,{nextPage:a.nextPage,backPage:a.backPage,onOnBackPage:e[0]||(e[0]=t=>i.viewBackPage()),onOnNextPage:e[1]||(e[1]=t=>i.viewNextPage()),class:"w-[70%]"},null,8,["nextPage","backPage"])])):(0,n.kq)("v-if",!0)],64)}]])},6618:(t,e,r)=>{r.d(e,{e:()=>a});var n=r(1257),o=r(9305),s=r(4072);const a=(0,o.Q_)("useStatus",{actions:{getStatuses:()=>(0,s.D)(n.Z.get("statuses"))}})},5238:(t,e,r)=>{r.d(e,{E:()=>s});var n=r(9305),o=r(3002);const s=(0,n.Q_)("useToast",{actions:{showToast(t,e){let r={position:"top-right",timeout:4984,closeOnClick:!0,pauseOnFocusLoss:!0,pauseOnHover:!0,draggable:!0,draggablePercent:1,showCloseButtonOnHover:!0,hideProgressBar:!0,closeButton:!1,icon:!0,rtl:!1};switch(e){case"success":(0,o.pm)().success(t,r);break;case"info":(0,o.pm)().info(t,r);break;case"warning":(0,o.pm)().warning(t,r);break;case"error":(0,o.pm)().error(t,r)}(0,o.pm)()}}})}}]);