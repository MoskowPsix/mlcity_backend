"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[3554],{3554:(e,r,a)=>{a.r(r),a.d(r,{default:()=>w});var t=a(6252),s=a(9963),l=a(7973);const o={class:"bg-gray-50 dark:bg-gray-900"},d={class:"flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0"},i={class:"w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700"},c={class:"p-6 space-y-4 md:space-y-6 sm:p-8"},m=(0,t._)("a",{href:"#",class:"flex items-center items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white"},[(0,t._)("img",{src:l,class:"h-6 mr-3 sm:h-7",alt:"Flowbite Logo"}),(0,t._)("span",{class:"self-center text-xl font-semibold whitespace-nowrap dark:text-white"},[(0,t.Uk)("MyLittleCity"),(0,t._)("p",{class:"text-xs items-center"},"AdminPanel")])],-1),n=(0,t._)("label",{for:"email",class:"block mb-2 text-sm font-medium text-gray-900 dark:text-white"},"Еmail",-1),u=(0,t._)("label",{for:"password",class:"block mb-2 text-sm font-medium text-gray-900 dark:text-white"},"Пароль",-1),p=(0,t._)("div",{class:"flex items-center justify-between"},[(0,t._)("div",{class:"flex items-start"},[(0,t._)("div",{class:"ml-3 text-sm"})])],-1),g=(0,t._)("button",{type:"submit",class:"w-full dark:text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"},"Войти",-1);var b=a(2262),y=a(2861),f=a(2201),x=a(6985);const k={name:"login",setup(){const e=(0,b.iH)(""),r=(0,b.iH)(""),a=(0,f.tv)();return{email:e,password:r,submit:async()=>{const t=await y.Z.post("login",{email:e.value,password:r.value}).catch((e=>{(0,x.E)().warning("Ошибка авторизации: "+e.message)}));localStorage.setItem("token",t.data.access_token);const s=await y.Z.get("listUsers?id="+t.data.user.id).catch((e=>console.log(e)));localStorage.setItem("role",s.data.users.data[0].roles[0].name),await a.push({path:"/"})}}}};(0,a(4112).k)().closeBar();const w=(0,a(3744).Z)(k,[["render",function(e,r,a,l,b,y){return(0,t.wg)(),(0,t.iD)("section",o,[(0,t._)("div",d,[(0,t._)("div",i,[(0,t._)("div",c,[m,(0,t._)("form",{class:"space-y-4 md:space-y-6",onSubmit:r[2]||(r[2]=(0,s.withModifiers)(((...e)=>l.submit&&l.submit(...e)),["prevent"]))},[(0,t._)("div",null,[n,(0,t.wy)((0,t._)("input",{type:"email",name:"email",id:"email","onUpdate:modelValue":r[0]||(r[0]=e=>l.email=e),class:"bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500",placeholder:"name@company.com",required:""},null,512),[[s.vModelText,l.email]])]),(0,t._)("div",null,[u,(0,t.wy)((0,t._)("input",{type:"password",name:"password",id:"password","onUpdate:modelValue":r[1]||(r[1]=e=>l.password=e),placeholder:"••••••••",class:"bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500",required:""},null,512),[[s.vModelText,l.password]])]),p,g],32)])])])])}]])}}]);