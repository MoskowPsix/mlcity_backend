/*! For license information please see 4783.js.LICENSE.txt */
"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[4783],{368:(e,t,i)=>{i.d(t,{g:()=>p});var n,r=i(655),o=function(e){function t(t,i){return e.call(this)||this}return r.ZT(t,e),t.prototype.schedule=function(e,t){return void 0===t&&(t=0),this},t}(i(9771).w),s=function(e){function t(t,i){var n=e.call(this,t,i)||this;return n.scheduler=t,n.work=i,n.pending=!1,n}return r.ZT(t,e),t.prototype.schedule=function(e,t){if(void 0===t&&(t=0),this.closed)return this;this.state=e;var i=this.id,n=this.scheduler;return null!=i&&(this.id=this.recycleAsyncId(n,i,t)),this.pending=!0,this.delay=t,this.id=this.id||this.requestAsyncId(n,this.id,t),this},t.prototype.requestAsyncId=function(e,t,i){return void 0===i&&(i=0),setInterval(e.flush.bind(e,this),i)},t.prototype.recycleAsyncId=function(e,t,i){if(void 0===i&&(i=0),null!==i&&this.delay===i&&!1===this.pending)return t;clearInterval(t)},t.prototype.execute=function(e,t){if(this.closed)return new Error("executing a cancelled action");this.pending=!1;var i=this._execute(e,t);if(i)return i;!1===this.pending&&null!=this.id&&(this.id=this.recycleAsyncId(this.scheduler,this.id,null))},t.prototype._execute=function(e,t){var i=!1,n=void 0;try{this.work(e)}catch(e){i=!0,n=!!e&&e||new Error(e)}if(i)return this.unsubscribe(),n},t.prototype._unsubscribe=function(){var e=this.id,t=this.scheduler,i=t.actions,n=i.indexOf(this);this.work=null,this.state=null,this.pending=!1,this.scheduler=null,-1!==n&&i.splice(n,1),null!=e&&(this.id=this.recycleAsyncId(t,e,null)),this.delay=null},t}(o),a=function(){function e(t,i){void 0===i&&(i=e.now),this.SchedulerAction=t,this.now=i}return e.prototype.schedule=function(e,t,i){return void 0===t&&(t=0),new this.SchedulerAction(this,e).schedule(i,t)},e.now=function(){return Date.now()},e}(),l=function(e){function t(i,n){void 0===n&&(n=a.now);var r=e.call(this,i,(function(){return t.delegate&&t.delegate!==r?t.delegate.now():n()}))||this;return r.actions=[],r.active=!1,r.scheduled=void 0,r}return r.ZT(t,e),t.prototype.schedule=function(i,n,r){return void 0===n&&(n=0),t.delegate&&t.delegate!==this?t.delegate.schedule(i,n,r):e.prototype.schedule.call(this,i,n,r)},t.prototype.flush=function(e){var t=this.actions;if(this.active)t.push(e);else{var i;this.active=!0;do{if(i=e.execute(e.state,e.delay))break}while(e=t.shift());if(this.active=!1,i){for(;e=t.shift();)e.unsubscribe();throw i}}},t}(a),u=new l(s),c=i(979),d=i(5631),h=i(1075),v=i(1906);n||(n={});var f=function(){function e(e,t,i){this.kind=e,this.value=t,this.error=i,this.hasValue="N"===e}return e.prototype.observe=function(e){switch(this.kind){case"N":return e.next&&e.next(this.value);case"E":return e.error&&e.error(this.error);case"C":return e.complete&&e.complete()}},e.prototype.do=function(e,t,i){switch(this.kind){case"N":return e&&e(this.value);case"E":return t&&t(this.error);case"C":return i&&i()}},e.prototype.accept=function(e,t,i){return e&&"function"==typeof e.next?this.observe(e):this.do(e,t,i)},e.prototype.toObservable=function(){var e;switch(this.kind){case"N":return(0,h.of)(this.value);case"E":return e=this.error,new v.y((function(t){return t.error(e)}));case"C":return(0,d.c)()}throw new Error("unexpected notification kind value")},e.createNext=function(t){return void 0!==t?new e("N",t):e.undefinedValueNotification},e.createError=function(t){return new e("E",void 0,t)},e.createComplete=function(){return e.completeNotification},e.completeNotification=new e("C"),e.undefinedValueNotification=new e("N",void 0),e}();function p(e,t){void 0===t&&(t=u);var i,n=(i=e)instanceof Date&&!isNaN(+i)?+e-t.now():Math.abs(e);return function(e){return e.lift(new m(n,t))}}var m=function(){function e(e,t){this.delay=e,this.scheduler=t}return e.prototype.call=function(e,t){return t.subscribe(new w(e,this.delay,this.scheduler))},e}(),w=function(e){function t(t,i,n){var r=e.call(this,t)||this;return r.delay=i,r.scheduler=n,r.queue=[],r.active=!1,r.errored=!1,r}return r.ZT(t,e),t.dispatch=function(e){for(var t=e.source,i=t.queue,n=e.scheduler,r=e.destination;i.length>0&&i[0].time-n.now()<=0;)i.shift().notification.observe(r);if(i.length>0){var o=Math.max(0,i[0].time-n.now());this.schedule(e,o)}else this.unsubscribe(),t.active=!1},t.prototype._schedule=function(e){this.active=!0,this.destination.add(e.schedule(t.dispatch,this.delay,{source:this,destination:this.destination,scheduler:e}))},t.prototype.scheduleNotification=function(e){if(!0!==this.errored){var t=this.scheduler,i=new g(t.now()+this.delay,e);this.queue.push(i),!1===this.active&&this._schedule(t)}},t.prototype._next=function(e){this.scheduleNotification(f.createNext(e))},t.prototype._error=function(e){this.errored=!0,this.queue=[],this.destination.error(e),this.unsubscribe()},t.prototype._complete=function(){this.scheduleNotification(f.createComplete()),this.unsubscribe()},t}(c.L),g=function(){return function(e,t){this.time=e,this.notification=t}}()},2876:(e,t,i)=>{i.d(t,{X:()=>o});var n=i(655),r=i(979);function o(e){return void 0===e&&(e=-1),function(t){return t.lift(new s(e,t))}}var s=function(){function e(e,t){this.count=e,this.source=t}return e.prototype.call=function(e,t){return t.subscribe(new a(e,this.count,this.source))},e}(),a=function(e){function t(t,i,n){var r=e.call(this,t)||this;return r.count=i,r.source=n,r}return n.ZT(t,e),t.prototype.error=function(t){if(!this.isStopped){var i=this.source,n=this.count;if(0===n)return e.prototype.error.call(this,t);n>-1&&(this.count=n-1),i.subscribe(this._unsubscribeAndRecycle())}},t}(r.L)},7410:(e,t,i)=>{i.d(t,{Mi:()=>m,W_:()=>p,lr:()=>h});var n=i(6252),r=i(2262);const o={itemsToShow:1,itemsToScroll:1,modelValue:0,transition:300,autoplay:0,snapAlign:"center",wrapAround:!1,throttle:16,pauseAutoplayOnHover:!1,mouseDrag:!0,touchDrag:!0,dir:"ltr",breakpoints:void 0,i18n:{ariaNextSlide:"Navigate to next slide",ariaPreviousSlide:"Navigate to previous slide",ariaNavigateToSlide:"Navigate to slide {slideNumber}",ariaGallery:"Gallery",itemXofY:"Item {currentSlide} of {slidesCount}",iconArrowUp:"Arrow pointing upwards",iconArrowDown:"Arrow pointing downwards",iconArrowRight:"Arrow pointing to the right",iconArrowLeft:"Arrow pointing to the left"}},s={itemsToShow:{default:o.itemsToShow,type:Number},itemsToScroll:{default:o.itemsToScroll,type:Number},wrapAround:{default:o.wrapAround,type:Boolean},throttle:{default:o.throttle,type:Number},snapAlign:{default:o.snapAlign,validator:e=>["start","end","center","center-even","center-odd"].includes(e)},transition:{default:o.transition,type:Number},breakpoints:{default:o.breakpoints,type:Object},autoplay:{default:o.autoplay,type:Number},pauseAutoplayOnHover:{default:o.pauseAutoplayOnHover,type:Boolean},modelValue:{default:void 0,type:Number},mouseDrag:{default:o.mouseDrag,type:Boolean},touchDrag:{default:o.touchDrag,type:Boolean},dir:{default:o.dir,validator:e=>["rtl","ltr"].includes(e)},i18n:{default:o.i18n,type:Object},settings:{default:()=>({}),type:Object}};function a({val:e,max:t,min:i}){return t<i?e:Math.min(Math.max(e,i),t)}function l(e){return e?e.reduce(((e,t)=>{var i;return t.type===n.HY?[...e,...l(t.children)]:"CarouselSlide"===(null===(i=t.type)||void 0===i?void 0:i.name)?[...e,t]:e}),[]):[]}function u({val:e,max:t,min:i=0}){return e>t?u({val:e-(t+1),max:t,min:i}):e<i?u({val:e+(t+1),max:t,min:i}):e}var c,d=(0,n.aZ)({name:"ARIA",setup(){const e=(0,n.f3)("config",(0,r.qj)(Object.assign({},o))),t=(0,n.f3)("currentSlide",(0,r.iH)(0)),i=(0,n.f3)("slidesCount",(0,r.iH)(0));return()=>(0,n.h)("div",{class:["carousel__liveregion","carousel__sr-only"],"aria-live":"polite","aria-atomic":"true"},function(e="",t={}){return Object.entries(t).reduce(((e,[t,i])=>e.replace(`{${t}}`,String(i))),e)}(e.i18n.itemXofY,{currentSlide:t.value+1,slidesCount:i.value}))}}),h=(0,n.aZ)({name:"Carousel",props:s,setup(e,{slots:t,emit:i,expose:c}){var h;const v=(0,r.iH)(null),f=(0,r.iH)([]),p=(0,r.iH)(0),m=(0,r.iH)(0),w=(0,r.qj)(Object.assign({},o));let g,y=Object.assign({},o);const b=(0,r.iH)(null!==(h=e.modelValue)&&void 0!==h?h:0),x=(0,r.iH)(0),S=(0,r.iH)(0),A=(0,r.iH)(0),_=(0,r.iH)(0);let k,C;function T(){g=Object.assign({},e.breakpoints),y=Object.assign(Object.assign(Object.assign({},y),e),{i18n:Object.assign(Object.assign({},y.i18n),e.i18n),breakpoints:void 0}),j(y)}function N(){if(!g||!Object.keys(g).length)return;const e=Object.keys(g).map((e=>Number(e))).sort(((e,t)=>+t-+e));let t=Object.assign({},y);e.some((e=>{const i=window.matchMedia(`(min-width: ${e}px)`).matches;return i&&(t=Object.assign(Object.assign({},t),g[e])),i})),j(t)}function j(e){Object.entries(e).forEach((([e,t])=>w[e]=t))}(0,n.JJ)("config",w),(0,n.JJ)("slidesCount",m),(0,n.JJ)("currentSlide",b),(0,n.JJ)("maxSlide",A),(0,n.JJ)("minSlide",_),(0,n.JJ)("slideWidth",p);const O=function(e,t){let i;return function(...e){i&&clearTimeout(i),i=setTimeout((()=>{(()=>{N(),H()})(...e),i=null}),16)}}();function H(){if(!v.value)return;const e=v.value.getBoundingClientRect();p.value=e.width/w.itemsToShow}function M(){m.value<=0||(S.value=Math.ceil((m.value-1)/2),A.value=function({config:e,slidesCount:t}){const{snapAlign:i,wrapAround:n,itemsToShow:r=1}=e;if(n)return Math.max(t-1,0);let o;switch(i){case"start":o=t-r;break;case"end":o=t-1;break;case"center":case"center-odd":o=t-Math.ceil((r-.5)/2);break;case"center-even":o=t-Math.ceil(r/2);break;default:o=0}return Math.max(o,0)}({config:w,slidesCount:m.value}),_.value=function({config:e,slidesCount:t}){const{wrapAround:i,snapAlign:n,itemsToShow:r=1}=e;let o=0;if(i||r>t)return o;switch(n){case"start":default:o=0;break;case"end":o=r-1;break;case"center":case"center-odd":o=Math.floor((r-1)/2);break;case"center-even":o=Math.floor((r-2)/2)}return o}({config:w,slidesCount:m.value}),w.wrapAround||(b.value=a({val:b.value,max:A.value,min:_.value})))}(0,n.bv)((()=>{(0,n.Y3)((()=>H())),setTimeout((()=>H()),1e3),N(),P(),window.addEventListener("resize",O,{passive:!0}),i("init")})),(0,n.Ah)((()=>{C&&clearTimeout(C),k&&clearInterval(k),window.removeEventListener("resize",O,{passive:!0})}));let L=!1;const E={x:0,y:0},I={x:0,y:0},J=(0,r.qj)({x:0,y:0}),D=(0,r.iH)(!1),q=(0,r.iH)(!1),Y=()=>{D.value=!0},R=()=>{D.value=!1};function V(e){["INPUT","TEXTAREA","SELECT"].includes(e.target.tagName)||(L="touchstart"===e.type,L||e.preventDefault(),!L&&0!==e.button||$.value||(E.x=L?e.touches[0].clientX:e.clientX,E.y=L?e.touches[0].clientY:e.clientY,document.addEventListener(L?"touchmove":"mousemove",X,!0),document.addEventListener(L?"touchend":"mouseup",B,!0)))}const X=function(e,t){let i;return t?function(...n){i||(e.apply(this,n),i=!0,setTimeout((()=>i=!1),t))}:e}((e=>{q.value=!0,I.x=L?e.touches[0].clientX:e.clientX,I.y=L?e.touches[0].clientY:e.clientY;const t=I.x-E.x,i=I.y-E.y;J.y=i,J.x=t}),w.throttle);function B(){const e="rtl"===w.dir?-1:1,t=.4*Math.sign(J.x),i=Math.round(J.x/p.value+t)*e;if(i&&!L){const e=t=>{t.stopPropagation(),window.removeEventListener("click",e,!0)};window.addEventListener("click",e,!0)}z(b.value-i),J.x=0,J.y=0,q.value=!1,document.removeEventListener(L?"touchmove":"mousemove",X,!0),document.removeEventListener(L?"touchend":"mouseup",B,!0)}function P(){!w.autoplay||w.autoplay<=0||(k=setInterval((()=>{w.pauseAutoplayOnHover&&D.value||U()}),w.autoplay))}function Z(){k&&(clearInterval(k),k=null),P()}const $=(0,r.iH)(!1);function z(e){const t=w.wrapAround?e:a({val:e,max:A.value,min:_.value});b.value===t||$.value||(i("slide-start",{slidingToIndex:e,currentSlideIndex:b.value,prevSlideIndex:x.value,slidesCount:m.value}),$.value=!0,x.value=b.value,b.value=t,C=setTimeout((()=>{if(w.wrapAround){const n=u({val:t,max:A.value,min:0});n!==b.value&&(b.value=n,i("loop",{currentSlideIndex:b.value,slidingToIndex:e}))}i("update:modelValue",b.value),i("slide-end",{currentSlideIndex:b.value,prevSlideIndex:x.value,slidesCount:m.value}),$.value=!1,Z()}),w.transition))}function U(){z(b.value+w.itemsToScroll)}function W(){z(b.value-w.itemsToScroll)}const G={slideTo:z,next:U,prev:W};(0,n.JJ)("nav",G),(0,n.JJ)("isSliding",$);const F=(0,n.Fl)((()=>function({config:e,currentSlide:t,slidesCount:i}){const{snapAlign:n,wrapAround:r,itemsToShow:o=1}=e;let s=t;switch(n){case"center":case"center-odd":s-=(o-1)/2;break;case"center-even":s-=(o-2)/2;break;case"end":s-=o-1}return r?s:a({val:s,max:i-o,min:0})}({config:w,currentSlide:b.value,slidesCount:m.value})));(0,n.JJ)("slidesToScroll",F);const K=(0,n.Fl)((()=>{const e="rtl"===w.dir?-1:1,t=F.value*p.value*e;return{transform:`translateX(${J.x-t}px)`,transition:`${$.value?w.transition:0}ms`,margin:w.wrapAround?`0 -${m.value*p.value}px`:"",width:"100%"}}));function Q(){T(),N(),M(),H(),Z()}Object.keys(s).forEach((t=>{["modelValue"].includes(t)||(0,n.YP)((()=>e[t]),Q)})),(0,n.YP)((()=>e.modelValue),(e=>{e!==b.value&&z(Number(e))})),(0,n.YP)(m,M),i("before-init"),T();const ee={config:w,slidesCount:m,slideWidth:p,next:U,prev:W,slideTo:z,currentSlide:b,maxSlide:A,minSlide:_,middleSlide:S};c({updateBreakpointsConfigs:N,updateSlidesData:M,updateSlideWidth:H,initDefaultConfigs:T,restartCarousel:Q,slideTo:z,next:U,prev:W,nav:G,data:ee});const te=t.default||t.slides,ie=t.addons,ne=(0,r.qj)(ee);return()=>{const e=l(null==te?void 0:te(ne)),t=(null==ie?void 0:ie(ne))||[];e.forEach(((e,t)=>e.props.index=t));let i=e;if(w.wrapAround){const t=e.map(((t,i)=>(0,n.Ho)(t,{index:-e.length+i,isClone:!0,key:`clone-before-${i}`}))),r=e.map(((t,i)=>(0,n.Ho)(t,{index:e.length+i,isClone:!0,key:`clone-after-${i}`})));i=[...t,...e,...r]}f.value=e,m.value=Math.max(e.length,1);const r=(0,n.h)("ol",{class:"carousel__track",style:K.value,onMousedownCapture:w.mouseDrag?V:null,onTouchstartPassiveCapture:w.touchDrag?V:null},i),o=(0,n.h)("div",{class:"carousel__viewport"},r);return(0,n.h)("section",{ref:v,class:{carousel:!0,"is-sliding":$.value,"is-dragging":q.value,"is-hover":D.value,"carousel--rtl":"rtl"===w.dir},dir:w.dir,"aria-label":w.i18n.ariaGallery,tabindex:"0",onMouseenter:Y,onMouseleave:R},[o,t,(0,n.h)(d)])}}});!function(e){e.arrowUp="arrowUp",e.arrowDown="arrowDown",e.arrowRight="arrowRight",e.arrowLeft="arrowLeft"}(c||(c={}));const v={arrowUp:"M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z",arrowDown:"M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z",arrowRight:"M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z",arrowLeft:"M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"},f=e=>{const t=(0,n.f3)("config",(0,r.qj)(Object.assign({},o))),i=String(e.name),s=`icon${i.charAt(0).toUpperCase()+i.slice(1)}`;if(!i||"string"!=typeof i||!(i in c))return;const a=v[i],l=(0,n.h)("path",{d:a}),u=t.i18n[s]||e.title||i,d=(0,n.h)("title",u);return(0,n.h)("svg",{class:"carousel__icon",viewBox:"0 0 24 24",role:"img","aria-label":u},[d,l])};f.props={name:String,title:String};const p=(e,{slots:t,attrs:i})=>{const{next:s,prev:a}=t||{},l=(0,n.f3)("config",(0,r.qj)(Object.assign({},o))),u=(0,n.f3)("maxSlide",(0,r.iH)(1)),c=(0,n.f3)("minSlide",(0,r.iH)(1)),d=(0,n.f3)("currentSlide",(0,r.iH)(1)),h=(0,n.f3)("nav",{}),{dir:v,wrapAround:p,i18n:m}=l,w="rtl"===v;return[(0,n.h)("button",{type:"button",class:["carousel__prev",!p&&d.value<=c.value&&"carousel__prev--disabled",null==i?void 0:i.class],"aria-label":m.ariaPreviousSlide,onClick:h.prev},(null==a?void 0:a())||(0,n.h)(f,{name:w?"arrowRight":"arrowLeft"})),(0,n.h)("button",{type:"button",class:["carousel__next",!p&&d.value>=u.value&&"carousel__next--disabled",null==i?void 0:i.class],"aria-label":m.ariaNextSlide,onClick:h.next},(null==s?void 0:s())||(0,n.h)(f,{name:w?"arrowLeft":"arrowRight"}))]};var m=(0,n.aZ)({name:"CarouselSlide",props:{index:{type:Number,default:1},isClone:{type:Boolean,default:!1}},setup(e,{slots:t}){const i=(0,n.f3)("config",(0,r.qj)(Object.assign({},o))),s=(0,n.f3)("currentSlide",(0,r.iH)(0)),a=(0,n.f3)("slidesToScroll",(0,r.iH)(0)),l=(0,n.f3)("isSliding",(0,r.iH)(!1)),u=()=>{const t=Math.floor(a.value),n=Math.ceil(a.value+i.itemsToShow-1);return e.index>=t&&e.index<=n};return()=>{var r;return(0,n.h)("li",{style:{width:100/i.itemsToShow+"%"},class:{carousel__slide:!0,"carousel__slide--clone":e.isClone,"carousel__slide--visible":u(),"carousel__slide--active":e.index===s.value,"carousel__slide--prev":e.index===s.value-1,"carousel__slide--next":e.index===s.value+1,"carousel__slide--sliding":l.value},"aria-hidden":!u()},null===(r=t.default)||void 0===r?void 0:r.call(t))}}})}}]);