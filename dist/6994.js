"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6994],{250:(e,r,t)=>{t.d(r,{Z:()=>_});var a=t(6252),l=t(3577);const o={class:"w-[100%] mx-auto rounded-lg"},s={key:0,class:"absolute flex border left-1/2 top-[9rem] w-48 h-48 z-50 bg-slate-300/70 dark:bg-gray-900/70 dark:border-gray-700/50 border-gray-200/90 text-gray-800/70 dark:text-gray-400/70 p-3 rounded-lg"},n=[(0,a._)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"h-40 w-40"},[(0,a._)("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15"})],-1)],i=["src","alt"],d={class:"flex flex-row min-h-[7rem] min-w-[7rem]"},g=(0,a._)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"currentColor",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"w-[6rem] h-[6rem]"},[(0,a._)("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M12 6v12m6-6H6"})],-1),u=["onClick"],c=["src","alt"],p=["onClick"],m=[(0,a._)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"w-4 h-4 mx-auto"},[(0,a._)("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"})],-1)];var w=t(7410),h=t(2262),k=t(9963);const v=["data-active","onDragenter","onDragover","onDragleave","onDrop"],f={__name:"DropZone",emits:["files-dropped","state"],setup(e,{emit:r}){const t=r;let l=(0,h.iH)(!1),o=null;function s(){l.value=!0,clearTimeout(o),t("state",!0)}function n(){o=setTimeout((()=>{l.value=!1,t("state",!1)}),50)}function i(e){n(),t("files-dropped",[...e.dataTransfer.files])}return(e,r)=>((0,a.wg)(),(0,a.iD)("div",{"data-active":(0,h.SU)(l),onDragenter:(0,k.withModifiers)(s,["prevent"]),onDragover:(0,k.withModifiers)(s,["prevent"]),onDragleave:(0,k.withModifiers)(n,["prevent"]),onDrop:(0,k.withModifiers)(i,["prevent"])},[(0,a.WI)(e.$slots,"default")],40,v))}},x={name:"CarouselGallery",props:{files:Array,wrightState:Boolean},data:()=>({currentSlide:0,stateDrop:!1}),components:{Carousel:w.lr,Slide:w.Mi,Navigation:w.W_,DropZone:f},methods:{onFileChanged(e){this.emitUpdImg(e.target.files)},chooseFiles:function(){document.getElementById("fileUpload").click()},setState(e){this.stateDrop=e},slideTo(e){this.currentSlide=e},emitDelImg(e){this.wrightState&&this.$emit("onDeleteFile",e)},emitUpdImg(e){this.wrightState&&this.$emit("onUpdateFile",e)}}},_=(0,t(3744).Z)(x,[["render",function(e,r,t,w,h,k){const v=(0,a.up)("Slide"),f=(0,a.up)("Carousel"),x=(0,a.up)("DropZone");return(0,a.wg)(),(0,a.j4)(x,{onFilesDropped:k.emitUpdImg,onState:k.setState,class:"border w-full h-full rounded-lg dark:bg-gray-800 bg-gray-100 border-gray-400/70 shadow-lg dark:border-gray-700"},{default:(0,a.w5)((()=>[(0,a._)("div",o,[h.stateDrop&&t.wrightState?((0,a.wg)(),(0,a.iD)("div",s,n)):(0,a.kq)("v-if",!0),(0,a.Wm)(f,{id:"gallery","items-to-show":1,"wrap-around":!1,modelValue:h.currentSlide,"onUpdate:modelValue":r[0]||(r[0]=e=>h.currentSlide=e),class:"rounded-lg"},{default:(0,a.w5)((()=>[((0,a.wg)(!0),(0,a.iD)(a.HY,null,(0,a.Ko)(t.files,(e=>((0,a.wg)(),(0,a.j4)(v,{key:e,class:"rounded-lg"},{default:(0,a.w5)((()=>[(0,a._)("div",{class:"absolute w-[100%] h-[100%] blur-lg bg-gray-50/10 z-20 rounded-lg",style:(0,l.normalizeStyle)({backgroundImage:`url(${e.link})`})},null,4),(0,a._)("img",{src:e.link,alt:e.name,class:"rounded-lg max-h-[20rem] max-w-[30rem] z-30"},null,8,i)])),_:2},1024)))),128))])),_:1},8,["modelValue"]),(0,a._)("div",d,[t.wrightState?((0,a.wg)(),(0,a.iD)("div",{key:0,onClick:r[2]||(r[2]=e=>k.chooseFiles()),class:"dark:text-gray-400/70 dark:bg-gray-700/70 hover:dark:text-gray-400 hover:dark:bg-gray-700 bg-slate-400/30 border-slate-400/40 text-gray-600 hover:bg-slate-400/60 hover:text-gray-700/90 w-[6rem] h-[6rem] m-3 rounded-lg p-0.2"},[g,(0,a._)("input",{id:"fileUpload",type:"file",onChange:r[1]||(r[1]=e=>k.onFileChanged(e)),hidden:""},null,32)])):(0,a.kq)("v-if",!0),(0,a.Wm)(f,{id:"thumbnails","items-to-show":4,"wrap-around":!0,modelValue:h.currentSlide,"onUpdate:modelValue":r[3]||(r[3]=e=>h.currentSlide=e),ref:"carousel",class:"border dark:border-gray-600/50 bg-slate-400/30 border-slate-400/40 dark:bg-gray-700/50 m-2 rounded-lg w-10/12 mx-auto"},{default:(0,a.w5)((()=>[((0,a.wg)(!0),(0,a.iD)(a.HY,null,(0,a.Ko)(t.files,((e,r)=>((0,a.wg)(),(0,a.j4)(v,{key:e},{default:(0,a.w5)((()=>[(0,a._)("div",{class:"flex flex-col max-h-[7rem] max-w-[7rem]",onClick:e=>k.slideTo(r)},[(0,a._)("img",{src:e.link,alt:e.name,class:"rounded-lg"},null,8,c),t.wrightState?((0,a.wg)(),(0,a.iD)("button",{key:0,class:"absolute dark:text-red-200 text-red-600 bg-red-200/70 dark:bg-red-600/70 p-1 rounded-lg",onClick:r=>k.emitDelImg(e)},m,8,p)):(0,a.kq)("v-if",!0)],8,u)])),_:2},1024)))),128))])),_:1},8,["modelValue"])])])])),_:1},8,["onFilesDropped","onState"])}]])},6994:(e,r,t)=>{t.r(r),t.d(r,{default:()=>ne});var a=t(6252),l=t(9963),o=t(3577);const s={key:0,class:"flex flex-col min-w-full min-h-full bg-gray-300 dark:bg-gray-900 p-1"},n={class:"flex items-center border rounded-lg bg-gray-50 dark:border-gray-700 dark:bg-gray-800/90 dark:text-gray-300 p-2 mb-2"},i=[(0,a._)("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"currentColor",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"w-6 h-6"},[(0,a._)("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75"})],-1),(0,a._)("h1",{class:"flex items-center mr-1 ml-1"},"Назад",-1)],d={class:"flex items-center w-8/12"},g={class:"flex items-center w-3/12"},u={class:"grid 2xl:grid-cols-4 xl:grid-cols-3 lg:grid-cols-2 md:grid-cols-1 sm:grid-cols-1 w-full p-1"},c={class:"2xl:col-span-3 xl:col-span-2 lg:ol-span-2 mt-1 mb-1"};var p=t(9305),m=t(5238),w=t(5960),h=t(3048),k=t(2876),v=t(368),f=t(5709),x=t(486),_=t(1558),b=t(5244),y=t(1075),D=t(5631),C=t(6722),S=t(250);const F={class:"flex flex-col items-center border rounded-lg w-full h-full dark:bg-gray-800/80 dark:border-gray-700/80 p-2"},M=["src","alt"],U={class:"flex flex-col items-center p-2"},j={class:"mb-2 text-2xl"},q={class:"flex flex-row border rounded-lg dark:border-gray-700/80 p-2 mb-2 text-ms dark:text-gray-400"},B={key:0,xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"ml-2 w-5 h-5 text-green-500"},L=[(0,a._)("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M4.5 12.75l6 6 9-13.5"},null,-1)],Z={key:1,xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"ml-2 w-5 h-5 text-red-500"},E=[(0,a._)("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M6 18L18 6M6 6l12 12"},null,-1)],H={class:"flex flex-row border rounded-lg dark:border-gray-700/80 p-2 mb-1 text-ms dark:text-gray-400"},I={key:0,xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"ml-2 w-5 h-5 text-green-500"},Y=[(0,a._)("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M4.5 12.75l6 6 9-13.5"},null,-1)],P={key:1,xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"ml-2 w-5 h-5 text-red-500"},z=[(0,a._)("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M6 18L18 6M6 6l12 12"},null,-1)],O={name:"AuthorMiniCard",props:["author"],mounted(){console.log(this.author)}};var A=t(3744);const T=(0,A.Z)(O,[["render",function(e,r,t,l,s,n){return(0,a.wg)(),(0,a.iD)("div",F,[(0,a._)("img",{class:"border w-48 h-48 rounded-3xl dark:border-gray-700/80",src:t.author.avatar,alt:t.author.name},null,8,M),(0,a._)("label",U,[(0,a._)("h1",j,"Имя: "+(0,o.toDisplayString)(t.author.name),1),(0,a._)("p",q,[(0,a.Uk)(" Почта: "+(0,o.toDisplayString)(t.author.email)+" ",1),t.author.email_verified_at?((0,a.wg)(),(0,a.iD)("svg",B,L)):(0,a.kq)("v-if",!0),t.author.email_verified_at?(0,a.kq)("v-if",!0):((0,a.wg)(),(0,a.iD)("svg",Z,E))]),(0,a._)("p",H,[(0,a.Uk)(" Номер: +7 "+(0,o.toDisplayString)(t.author.number)+" ",1),t.author.number_verified_at?((0,a.wg)(),(0,a.iD)("svg",I,Y)):(0,a.kq)("v-if",!0),t.author.number_verified_at?(0,a.kq)("v-if",!0):((0,a.wg)(),(0,a.iD)("svg",P,z))])])])}]]),$={class:"flex flex-row justify-content-center"},V={class:"w-11/12 ml-2"},W={class:"dark:text-gray-200 text-xl"},K={class:"dark:text-gray-400 text-sm"},G={class:"w-1/12 my-auto"},R={key:0,class:"my-auto mx-auto w-6 h-6",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},Q=[(0,a._)("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M19.5 8.25l-7.5 7.5-7.5-7.5"},null,-1)],N={key:1,class:"my-auto mx-auto w-6 h-6",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},X=[(0,a._)("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M4.5 15.75l7.5-7.5 7.5 7.5"},null,-1)],J={key:0},ee={key:1,class:"flex flex-row mt-2"},re=[(0,a._)("div",{class:"w-1/2"},[(0,a.kq)(' <YandexMap :center="[place.latitude, place.longitude]" :marker="place"/> ')],-1),(0,a._)("div",{class:"w-1/2"},null,-1)];var te=t(5836);const ae={name:"YandexMap",props:{markers:Array,marker:Object,center:Array,zoom:Number=16},components:{YandexMap:te.CB,YandexMarker:te.k7},setup:()=>({settings:{apiKey:"226cca4a-d7de-46b5-9bc8-889f70ebfe64",lang:"ru_RU",coordorder:"latlong",debug:!0,version:"2.1"}})},le={name:"PlaceListCard",data:()=>({state:!1}),props:{place:Object},components:{YandexMap:(0,A.Z)(ae,[["render",function(e,r,t,l,s,n){const i=(0,a.up)("YandexMarker"),d=(0,a.up)("YandexMap",!0);return(0,a.wg)(),(0,a.iD)(a.HY,null,[(0,a.Uk)((0,o.toDisplayString)(t.center)+" ",1),(0,a._)("div",null,[(0,a.Wm)(d,{class:"absolute insert-0",settings:l.settings,zoom:t.zoom,behaviors:[],controls:[],coordinates:t.center},{default:(0,a.w5)((()=>[t.marker?((0,a.wg)(),(0,a.j4)(i,{key:0,coordinates:[t.marker.latitbe,t.marker.longitube],"marker-id":t.marker.id},null,8,["coordinates","marker-id"])):(0,a.kq)("v-if",!0),t.markers?((0,a.wg)(!0),(0,a.iD)(a.HY,{key:1},(0,a.Ko)(t.markers,(e=>((0,a.wg)(),(0,a.iD)("div",null,[(0,a.Wm)(i,{coordinates:[e.latitbe,e.longitube],"marker-id":e.id},null,8,["coordinates","marker-id"])])))),256)):(0,a.kq)("v-if",!0)])),_:1},8,["settings","zoom","coordinates"])])],64)}]])},methods:{placeUpd(){},placeDel(){},seancesUpd(){},seancesDel(){}}},oe=(0,A.Z)(le,[["render",function(e,r,t,l,s,n){return(0,a.wg)(),(0,a.iD)("div",{onClick:r[0]||(r[0]=e=>s.state=!s.state),class:"transition border dark:border-gray-700/80 p-2 rounded-lg w-full dark:bg-gray-800 active:dark:bg-gray-700 active:dark:border-gray-600/80 active:scale-95"},[(0,a._)("div",$,[(0,a._)("label",V,[(0,a._)("h1",W,(0,o.toDisplayString)(t.place.location.name),1),(0,a._)("p",K,(0,o.toDisplayString)(t.place.address),1)]),(0,a._)("div",G,[s.state?(0,a.kq)("v-if",!0):((0,a.wg)(),(0,a.iD)("svg",R,Q)),s.state?((0,a.wg)(),(0,a.iD)("svg",N,X)):(0,a.kq)("v-if",!0)])]),s.state?((0,a.wg)(),(0,a.iD)("hr",J)):(0,a.kq)("v-if",!0),s.state?((0,a.wg)(),(0,a.iD)("div",ee,re)):(0,a.kq)("v-if",!0)])}]]),se={name:"EventShow",setup:()=>({destroy$:new b.xQ}),data:()=>({event:[],state:!0,filesDel:[],filesUpd:[]}),components:{CarouselGallery:S.Z,AuthorMiniCard:T,PlacesListCard:oe},methods:{...(0,p.nv)(w.b,["getEventForIds"]),...(0,p.nv)(m.E,["showToast"]),...(0,p.nv)(h.f,["openLoaderFullPage","closeLoaderFullPage"]),getEvent(){(0,k.X)(3),(0,v.g)(100),this.openLoaderFullPage(),this.getEventForIds(this.$route.params.id).pipe((0,f.U)((e=>{this.event=e.data,console.log(e),this.closeLoaderFullPage()})),(0,x.K)((e=>(console.log(e),C.Z.go(-1),this.closeLoaderFullPage(),(0,y.of)(D.E)))),(0,_.R)(this.destroy$)).subscribe()},deleteFiles(e){this.event.files.find(((r,t)=>{if(r.name==e.name)return!this.filesUpd.find(((r,t)=>{if(r.name==e.name)return this.filesUpd.splice(t,1),!0}))&&this.filesDel.push(e),this.event.files.splice(t,1),!0}))},updateFiles(e){(e=Array.from(e)).forEach((e=>{let r=new FileReader;r.readAsDataURL(e),console.log(r),r.onload=()=>{this.event.files.push({link:r.result,name:e.name,size:e.size,type:e.type})},this.filesUpd.push(e)})),console.log(this.filesUpd)},backButton(){C.Z.go(-1)}},mounted(){this.openLoaderFullPage,this.getEvent()}},ne=(0,A.Z)(se,[["render",function(e,r,t,p,m,w){const h=(0,a.up)("CarouselGallery"),k=(0,a.up)("PlacesListCard"),v=(0,a.up)("AuthorMiniCard");return m.event?((0,a.wg)(),(0,a.iD)("div",s,[(0,a._)("div",n,[(0,a._)("button",{onClick:r[0]||(r[0]=(0,l.withModifiers)((e=>w.backButton()),["prevent"])),type:"button","data-te-ripple-init":"","data-te-ripple-color":"light",class:"flex items-center rounded bg-primary min-w-1/12 max-w-2/12 mr-3 px-4 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"},i),(0,a._)("label",d,[(0,a._)("h1",null,"Имя: "+(0,o.toDisplayString)(m.event.name),1)]),(0,a._)("label",g,[(0,a._)("h1",null,"ID: "+(0,o.toDisplayString)(m.event.id),1)])]),m.event.files?((0,a.wg)(),(0,a.j4)(h,{key:0,files:m.event.files,wrightState:m.state,onOnDeleteFile:w.deleteFiles,onOnUpdateFile:w.updateFiles},null,8,["files","wrightState","onOnDeleteFile","onOnUpdateFile"])):(0,a.kq)("v-if",!0),(0,a._)("button",{onClick:r[1]||(r[1]=e=>m.state?m.state=!1:m.state=!0),class:"p-2 bg-green-500 rounded-lg border border-green-300 text-green-100 mt-1 md-1"},"Редактировать файлы"),(0,a._)("div",u,[(0,a._)("div",c,[((0,a.wg)(!0),(0,a.iD)(a.HY,null,(0,a.Ko)(m.event.places_full,(e=>((0,a.wg)(),(0,a.iD)("div",null,[(0,a.Wm)(k,{place:e},null,8,["place"])])))),256))]),(0,a.kq)(' <CarouselGallery v-if="event.files" :files="event.files" :wrightState="state" @onDeleteFile="deleteFiles" @onUpdateFile="updateFiles" class="flex w-9/12"></CarouselGallery> '),m.event.author?((0,a.wg)(),(0,a.j4)(v,{key:0,author:m.event.author,class:"col-span-1"},null,8,["author"])):(0,a.kq)("v-if",!0)]),(0,a.kq)(" <div>Удалённые {{filesDel}}</div>\r\n    <div>Обновлённые {{filesUpd}}</div> ")])):(0,a.kq)("v-if",!0)}]])},5960:(e,r,t)=>{t.d(r,{b:()=>s});var a=t(1257),l=t(9305),o=t(4072);const s=(0,l.Q_)("useEvent",{actions:{getEvents:e=>(0,o.D)(a.Z.get("events",{params:e})),getEventForIds:e=>(0,o.D)(a.Z.get(`events/${e}`))}})},5238:(e,r,t)=>{t.d(r,{E:()=>o});var a=t(9305),l=t(3002);const o=(0,a.Q_)("useToast",{actions:{showToast(e,r){let t={position:"top-right",timeout:4984,closeOnClick:!0,pauseOnFocusLoss:!0,pauseOnHover:!0,draggable:!0,draggablePercent:1,showCloseButtonOnHover:!0,hideProgressBar:!0,closeButton:!1,icon:!0,rtl:!1};switch(r){case"success":(0,l.pm)().success(e,t);break;case"info":(0,l.pm)().info(e,t);break;case"warning":(0,l.pm)().warning(e,t);break;case"error":(0,l.pm)().error(e,t)}(0,l.pm)()}}})}}]);