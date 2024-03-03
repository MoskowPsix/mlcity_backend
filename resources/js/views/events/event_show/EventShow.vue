<template lang="">
    <div class="flex flex-col pb-20" v-if="event && connectState" :id="'event-'+event.id">
        <!-- Кнопка назад -->
        <button
            v-if="connectState.BackButton"
            @click.prevent="backButton()"
            type="button"
            class="flex m-4 items-center rounded bg-gray-200/40 dark:bg-gray-800/80 max-h-12 min-w-1/12 max-w-[5rem] mr-3 px-4 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-gray-500 dark:text-gray-300/50 transition duration-150 ease-in-out hover:bg-gray-400/30 dark:hover:bg-gray-700/60 active:bg-gray-400/60 dark:active:bg-gray-700/80 ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
            </svg>
        </button>
        <form>
            <section class="flex flex-col justify-center max-w-[90%]  lg:min-w-[80%] lg:max-w-[80%] m-[auto]">
            <!-- <div v-if="connectState.IdLine || connectState.NameLine || connectState.BackButton" class="flex items-center border rounded-lg bg-gray-50 dark:border-gray-700 dark:bg-gray-800/90 dark:text-gray-300 p-2 mb-2">
                <button
                    v-if="connectState.BackButton"
                    @click.prevent="backButton()"
                    type="button"
                    data-te-ripple-init
                    data-te-ripple-color="light"
                    class="flex items-center rounded bg-primary min-w-1/12 max-w-2/12 mr-3 px-4 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                    </svg>
                    <h1 class="flex items-center mr-1 ml-1">Назад</h1>
                </button>


                <label v-if="connectState.IdLine" class="flex items-center w-3/12" :id="'event-'+event.id+'-id'"><h1>ID: {{event.id}}</h1></label>
            </div> -->

            <div class="header-content flex  justify-center dark:text-gray-400">
                <div class="header-content-main flex items-center  justify-center min-w-[100%] flex-col m-2 md:p-5 md:flex-col">
                    <div class="w-[100%] sm:w-[100%] md:w-[100%] xl:w-[100%] text-xs lg:text-lg   ">
                        <!-- <h1 class=" font-[Montserrat-Regular] mb-2" >Название</h1> -->
                        <div v-bind:class="{'border-blue-700/70':this.$props.changedFields != null && this.$props.changedFields.includes('name')}" class="title text-center p-2 w-[100%] border-2 border-[#EDEDED] rounded-md  mt-1 font-[Montserrat-Medium] flex justify-center dark:border-gray-700/80  ">
                            <label  v-if="!state && connectState.NameLine" class="" :id="'event-'+event.id+'-name'"><h1 class="font-bold">{{event.name}}</h1></label>
                            <input v-if="state && connectState.NameLine"  class="text-xs lg:text-lg leading-tight text-neutral-800 dark:text-gray-400 w-2/4   dark:bg-gray-700rounded-lgp-2pl-1borderm-0 bg-transparent text-center border-none  dark:border-gray-700/80" :value="event.name" @input="event => text = event.target.value" type="text" name="name" :id="'event-'+event.id+'-name-input'">
                        </div>


                        <div  class="  md:w-[100%] mt-4 ">
                            <!-- <label class="font-[Montserrat-Regular] text-xs lg:text-lg" for="">Организатор</label> -->
                            <div v-bind:class="{'border-blue-700/70':this.$props.changedFields != null && this.$props.changedFields.includes('sponsor')}" class="flex justify-center border-2 border-[#EDEDED] rounded-md w-[100%] p-0.5 font-[Montserrat-Medium]  sm:text-sm mt-2 dark:border-gray-700/80" >
                                <div class="text-xs lg:text-lg" v-if="event.sponsor && !state">{{event.sponsor}}</div>
                                <input :id="'event-'+event.id+'-sponsor-input'" v-if="state" class="text-xs lg:text-lg w-full dark:bg-transparent text-center border-none" type="text" name="sponsor" id="sponsor" :value="event.sponsor" @input="event => text = event.target.value">
                            </div>
                        </div>

                        <div class="flex justify-between mt-4 flex-col  min-[1577px]:flex-row ">

                            <div  v-if="!state" class="min-w-[34%] mb-4 ">
                                <!-- <label class="font-[Montserrat-Regular] text-xs lg:text-lg " for="">Тип</label> -->
                                <div v-bind:class="{'border-blue-700/70':state}" class="transition duration-1000 border-2 dark:border-gray-700/50 rounded-md font-[Montserrat-Medium] max-w-[60%] py-0.5">
                                    <div v-if="event.types" class="text-center py-2 space-y-2.5">
                                        <p :class="{'border-b-blue-700/70':this.$props.changedTypeIds != null && this.$props.changedTypeIds.includes(etype.id), 'border-red-600': etype.on_delete != null && etype.on_delete}" v-for="etype in event.types" class="border-b-2 mx-4 dark:border-gray-700/50" > {{ etype.name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div  v-if="state" class="hidden xl:block min-w-[34%] ">
                                <div @click.prevent="openTypeFnc()" class=" flex items-center justify-center tetxt-center max-w-[14rem] h-[2rem] mb-4 text-cyan-50  bg-[#4C81F7] hover:bg-[#6393FF] dark:bg-gray-700/50 cursor-pointer text-center unselectable rounded transition hover:dark:bg-gray-700/20">
                                    <label class=" font-[Montserrat-Regular]  cursor-pointer unselectable " for="">Изменить тип</label>
                                </div>
                            </div>
                            <div class="flex">
                                <div v-if="!state" class=" mr-4 flex flex-col items-center lg:mr-4" >
                                    <!-- <label class="font-[Montserrat-Regular] text-xs lg:text-lg" for="">Начало</label> -->
                                    <div v-bind:class="{'border-blue-700/70':this.$props.changedFields != null && this.$props.changedFields.includes('date_start')}" class="flex justify-center border-2 border-[#EDEDED] dark:border-gray-700/70 rounded-md  p-0.5 font-[Montserrat-Medium]   w-[100%]" >
                                        <div class="font-[Montserrat-Medium]  w-[100%] text-xs text-center lg:text-lg ">{{event.date_start}}</div>
                                    </div>
                                </div>
                                <VueDatePicker v-if="state" v-model="eventTime" range model-type="dd.MM.yyyy, HH:mm:ss" :class="themeState ? 'w-full h-full mt-1 dp_theme_dark' : 'w-full h-full mt-1 dp_theme_light'" placeholder="Дата и время события" />

                                <div v-if="!state" class="text-center">
                                    <!-- <label class="font-[Montserrat-Regular] text-xs lg:text-lg" for="">Конец</label> -->
                                    <div v-bind:class="{'border-blue-700/70':this.$props.changedFields != null && this.$props.changedFields.includes('date_end')}" class="flex justify-center border-2 border-[#EDEDED] rounded-md dark:border-gray-700/70  p-0.5 font-[Montserrat-Medium]  w-[100%] text-xs lg:text-lg" >
                                        <div>{{event.date_end}}</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div  class="xl:hidden lg:block min-w-[34%] mt-4">
                            <div v-if="state" @click.prevent="openTypeFnc()" class="flex items-center justify-center tetxt-center max-w-[14rem] h-[2rem] mb-4 text-cyan-50  bg-[#4C81F7] hover:bg-[#6393FF] rounded-md  dark:bg-gray-700/50  cursor-pointer text-center unselectable rounded transition hover:dark:bg-gray-700/20">
                                <label class=" font-[Montserrat-Regular]  cursor-pointer unselectable " for="">Изменить тип</label>
                            </div>
                        </div>

                        <div class="flex w-[100%] mt-4 "  v-if="state">
                            <Transition name="slide-fade">
                                <div :id="'event-'+event.id+'-type'" v-if="connectState.TypeCard && openType" class=" z-50  rounded-lg  h-auto dark:bg-gray-800 dark:border-gray-700/70 p-2">
                                    <h1 class="text-xl font-medium dark:text-gray-300 mb-1">Типы</h1>
                                    <div   class="  max-w-[30rem] lg:max-w-[100%] 2xl:max-w[100%] flex  flex-wrap-reverse  row  mt-2 rounded-lg dark:border-gray-600/60 py-4 tree dark:bg-gray-700/20 " v-if="allTypes && state">
                                        <TypeList  :type="'event'" :sightId="event.id" v-for="etype in allTypes" v-if="allTypes && event.types != null" :allSTypes="etype" :enableState="state" :currentStypes="event.types" @checked="addToCurrentTypes"/>
                                    </div>
                                </div>
                            </Transition>
                        </div>

                        <h3 class=" font-[Montserrat-Bold] text-lg mt-8">Расписание</h3>
                        <div v-if="connectState.PlaceCard && connectState.AuthorCard" class="w-[100%] bg-transparen font-[Montserrat-Regular] ">
                            <div  v-if="connectState.PlaceCard" class="2xl:col-span-3 xl:col-span-1 lg:ol-span-1 mt-2 ">
                                <div :id="'event-'+event.id+'-place'" class=" dark: dark:border-gray-700 p-1 rounded-lg ">
                                    <div v-for="(place, index) in event.places_full" :key="place.id">
                                        <PlacesListCard :class="{'border-green-600':place.new, 'border-red-600':place.delete}" :changedPlaceIds="changedPlaceIds" :changedSeanceIds="changedSeanceIds" :id="'event-'+event.id+'-place-' + place.id" v-if="!place.on_delete" :eventId="event.id" :stateUpd="state" :index="index" :place="JSON.parse(JSON.stringify(place))" @onUpdPlace="setPlace" class="mt-2"/>
                                    </div>
                                    <div v-if="state" @click.prevent="addNewPlace" class="transition border p-2 mt-2 rounded-lg font-medium text-center border-blue-500/70 font-[Montserrat-Regular] text-[#fff] bg-[#4C81F7] hover:bg-[#6393FF] hover:text-gray dark:hover:border-blue-500/30
                                    dark:border-blue-500/70 dark:text-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700
                                    hover:border-blue-500/30  cursor-pointer max-w-[14rem]">Добавить местопровидения</div>
                                </div>
                            </div>


                        </div>

                        <div class="content-descriprion w-[100%]  lg:w-[100%] m-[auto] mt-10 pt-8 p2 dark:border-gray-700/80 p-2 mb-2 text-ms dark:text-gray-400">
                            <h3 class=" font-[Montserrat-Bold] text-lg mb-2" >Описание</h3>
                            <div v-if="!state" class="dark:bg-gray-700/50 dark:border-gray-700/70 p-0.5 rounded">
                                <h3 v-bind:class="{'border-blue-700/70 border rounded-lg':this.$props.changedFields != null && this.$props.changedFields.includes('description')}" class="description font-[Montserrat-Medium] w-[100%] sm:w-[100%] md:w-[100%] xl:w-[100%] m-[auto]  text-xs lg:text-lg p-0.5 dark:bg-gray-700/50 dark:border-gray-700/70">
                                    {{event.description}}
                                </h3>
                            </div>
                            <div>
                                <div class=" rounded-md dark:bg-gray-700/50" v-if="state" >
                                    <textarea :id="'event-'+event.id+'-description-input'"  class=" border-none bg-transparent description font-[Montserrat-Medium] w-[100%] sm:w-[100%] md:w-[100%] xl:w-[100%] m-[auto]  text-xs lg:text-lg p2 " :value="event.description" name="description" cols="30" rows="10" @input="event => text = event.target.value"></textarea>
                                </div>
                            </div>

                            <CarouselGallery :id="'event-'+event.id+'-gallery'" class="w-[100%]  lg:w-[100%] m-[auto]  mb-1 mt-4" v-if="event.files && connectState.Gallery" :files="event.files" :wrightState="state" @onDeleteFile="deleteFiles" @onUpdateFile="updateFiles"></CarouselGallery>

                            <div class="content-description-price mt-10">
                                <h3 class=" font-[Montserrat-Bold] text-lg">Цены</h3>
                                <div class="content-description-price-grid flex justify-center  ">
                                    <div class=" flex row flex-wrap max-w-[80%] ">
                                            <div v-for="(price, index) in event.price" class="flex flex-row mt-2 mr-2">
                                                <PriceSegment :class="{'border-blue-700/70':this.$props.changedPriceIds != null && this.$props.changedPriceIds.includes(price.id),'border-green-600':price.new != null && price.new, 'border-red-600':price.delete != null & price.delete}" class=" p-2 border  dark:border-gray-700/50 rounded-lg" :id="'event-'+event.id+'-price-'+price.id" :price="price" :state="state" :index="index" @onDelPrice="deleteFromCurrentPrices" @onUpdPrice="priceUpd"/>
                                            </div>
                                    </div>
                                </div>

                                <div v-if="state" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" flex items-center justify-center tetxt-center max-w-[14rem] h-[2rem] mb-4 text-cyan-50  bg-[#4C81F7] hover:bg-[#6393FF] rounded-md  dark:bg-gray-700/50 cursor-pointer text-center unselectable rounded transition hover:dark:bg-gray-700/20"
                                v-on:click="addToCurrentPrices()">
                                    Добавить билет
                                </div>

                            </div>
                        </div>



                        <!-- Материалы -->
                        <div class="mt-4">
                            <div  v-if="!state">
                                <!-- <label class="font-[Montserrat-Regular] text-xs lg:text-lg mb-2" for="">Материалы</label> -->
                                <div v-bind:class="{'border-blue-700/70':this.$props.changedFields != null && this.$props.changedFields.includes('materials')}" class="flex justify-center border-2 border-[#EDEDED] dark:border-gray-700/70 rounded-md  p-0.5 font-[Montserrat-Medium]  sm:text-sm min-h-[2rem]" >
                                    <div class="text-xs lg:text-lg" v-if="event.sponsor">{{event.materials}}</div>
                                </div>
                            </div>

                            <label v-if="state">
                                <!-- <h1 class="font-[Montserrat-Regular] text-xs lg:text-lg">Материалы</h1> -->
                                <div class="flex justify-center border-2 border-[#EDEDED] rounded-md  p-0.5 font-[Montserrat-Medium]  sm:text-sm min-h-[2rem] dark:border-gray-700/70  ">
                                    <p :id="'event-'+event.id+'-materials'" v-if="!state" class="text-sm font-normal dark:text-gray-200  mb-2">{{event.materials}}</p>
                                    <input :id="'event-'+event.id+'-materials-input'" v-if="state" class="text-xs lg:text-lg border-none w-full bg-transparent" type="text" name="materials" id="materials" :value="event.materials" @input="event => text = event.target.value">
                                </div>

                            </label>

                        </div>

                        <div :id="'event-'+event.id+'-author'" v-if="connectState.AuthorCard && connectState.StatusCard" class="m-2  max-w-[100%]  xl:flex justify-between mt-8 row">
                            <div class="" v-if="connectState.AuthorCard">
                                <AuthorMiniCard v-if="event.author" :author="event.author"/>
                            </div>

                            <div  v-if="!state && connectState.StatusCard" class="bg-transparent  p-2 mt-1 dark:border-gray-700/70 dark:">
                                <ChangeStatus :id="'event-'+event.id+'-status'" v-if="event.statuses" :editButton="connectState.EditButton" :status="event.statuses[0].name" @statusChanged="statusChange"/>
                            </div>
                    </div>
                    </div>
                </div>
            </div>




                <!-- <div v-if="connectState.DescriptionsCard" class="flex flex-col border rounded-lg bg-gray-50 dark:border-gray-700 dark:bg-gray-800/90 dark:text-gray-300 p-2 mb-2"> -->
                    <!-- <label>
                        <h1 class="text-xl font-medium dark:text-gray-300 mb-2">Спонсор</h1>
                        <p :id="'event-'+event.id+'-sponsor'" v-if="!state" class="text-sm font-normal dark:text-gray-200 mb-2">{{event.sponsor}}</p>
                        <input :id="'event-'+event.id+'-sponsor-input'" v-if="state" class="w-full dark:bg-gray-700/50" type="text" name="sponsor" id="sponsor" :value="event.sponsor" @input="event => text = event.target.value">
                    </label> -->



                    <!-- <label>
                        <h1 class="text-xl font-medium dark:text-gray-300 mb-2">Дата начала и конца</h1>
                        <p :id="'event-'+event.id+'-date_start'" v-if="!state" class="text-sm font-normal dark:text-gray-200 mb-1">Начало: {{event.date_start}}</p>
                        <p :id="'event-'+event.id+'-date_end'" v-if="!state" class="text-sm font-normal dark:text-gray-200">Конец: {{event.date_end}}</p>
                        <VueDatePicker v-if="state" v-model="eventTime" range model-type="dd.MM.yyyy, HH:mm:ss" :class="themeState ? 'w-full h-full mt-1 dp_theme_dark' : 'w-full h-full mt-1 dp_theme_light'" placeholder="Дата и время события" />
                    </label> -->
                <!-- </div> -->
                <!-- <div v-if="connectState.PricesCard && connectState.TypeCard" class="grid m-1 2xl:grid-cols-2 xl:grid-cols-2 lg:grid-cols-2 ">
                    <div :id="'event-'+event.id+'-price'" v-if="connectState.PricesCard" class="border mr-1 2xl:col-span-1 xl:col-span-1 rounded-lg w-full h-auto dark:bg-gray-800 dark:border-gray-700/70 bg-gray-100 p-2">
                        <label>
                            <h1 class="text-xl font-medium dark:text-gray-300 mb-1">Цены</h1>
                            <hr class="dark:border-gray-700/70">
                        </label>
                        <div v-for="(price, index) in event.price" class="flex flex-row mt-2">
                            <PriceSegment class="p-2 border w-full dark:border-gray-700/50 rounded-lg" :id="'event-'+event.id+'-price-'+price.id" :price="price" :state="state" :index="index" @onDelPrice="deleteFromCurrentPrices" @onUpdPrice="sightUpdPrice"/>
                        </div>
                        <svg v-if="state" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-8 text-emerald-600 ml-auto"
                        v-on:click="addToCurrentPrices()">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                </div> -->
       </section>

                <div v-if="connectState.EditButton" class="button-menu ml-[-16%]   fixed  w-full bottom-[0%] bg-[#fff] dark:bg-gray-900  z-50">
                    <div class=" m-[auto] dark:bg-gray-900 min-[2600px]:max-w-[100%] sm:max-w-[70%] md:max-w-[75%] lg:max-w-[70%] xl:max-w-[74%] 2xl:max-w-[76%]">
                        <input v-if="state" @click="clickUpd($event)" class="rounded-lg  text-cyan-50  bg-[#4C81F7] hover:bg-[#6393FF] m-5 p-2 z-50 cursor-pointer font-[Montserrat-Regular]" type="button" value="Применить">
                        <button @click="canceleUpd()" v-if="state" class="rounded-lg bg-gray-600 font-[Montserrat-Regular]  text-cyan-50  m-5 p-2 cursor-pointer">Отмена</button>
                        <button @click="editUpd()" v-if="!state" class="rounded-lg text-cyan-50 font-[Montserrat-Regular] bg-[#4C81F7] hover:bg-[#6393FF] m-5 p-2 cursor-pointer">Редактировать</button>
                    </div>
                </div>
        </form>

    </div>

    </template>
    <script>
    import { mapActions} from 'pinia'
    import { useToastStore } from '../../../stores/ToastStore'
    import { MessageContents } from '../../../enums/content_messages'
    import { useEventStore } from '../../../stores/EventStore'
    import { useLoaderStore } from '../../../stores/LoaderStore'
    import { useHistoryContentStore } from '../../../stores/HistoryContentStore'
    import { useTypeStore } from '../../../stores/TypeStore'
    import { ref } from 'vue'
    import { catchError, map, retry, delay, takeUntil} from 'rxjs/operators'
    import { of, EMPTY, Subject } from 'rxjs'
    import { useDark } from '@vueuse/core'
    import router from '../../../routes'

    import CarouselGallery from '../../../components/carousel_gallery/CarouselGallery.vue'
    import AuthorMiniCard from '../../../components/author-mini-card/AuthorMiniCard.vue'
    import PlacesListCard from '../../../components/places_list_card/PlacesListCard.vue'
    import ChangeStatus from '../../../components/change_status/ChangeStatus.vue'
    import PriceSegment from '../../../components/price_segment/PriceSegment.vue'
    import TypeList from '../../../components/types_list/TypeList.vue'
    import VueDatePicker from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'


        // В props connectState нужно передать объект {} со следующими полями(true отобразить, false не отображать):
        // BackButton: true,
        // NameLine: true,
        // IdLine: true,
        // GalleryCard: true,
        // DescriptionsCard: true,
        // PricesCard: true,
        // TypeCard: true,
        // PlaceCard: true,
        // AuthorCard: true,
        // StatusCard: true,
        // EditButton: true,
    export default {
        name: 'EventShow',
        setup() {
            let themeState =  useDark() // Переменная состояния темы(false: light, true: dark)
            const destroy$ =  new Subject()
            const formatter = ref({
                date: 'DD.MM.YYYY, hh:mm:ss',
                month: 'MMM',
            })
            const eventTime = ref({
                startDate: "",
                endDate: ""
             })
            return {
                themeState,
                eventTime,
                formatter,
                destroy$
            }
        },
        mounted() {
            this.openLoaderFullPage
            this.getEvent()
            this.getAllTypes()
        },
        props: {
            connectState: {
                type: Object,
                default: {
                    BackButton: true,
                    NameLine: true,
                    IdLine: true,
                    Gallery: true,
                    DescriptionsCard: true,
                    PricesCard: true,
                    TypeCard: true,
                    PlaceCard: true,
                    AuthorCard: true,
                    StatusCard: true,
                    EditButton: true,
                }
            },
            id:{
                type: Number,
                default: null
            },
            event_:{
                type: Object,
                default: null
            },
            changedFields:{
                type: Array,
                default: null
            },
            changedPlaceIds:{
                type: Array,
                default: null
            },
            changedSeanceIds:{
                type:Array,
                default: null
            },
            changedTypeIds:{
                type: Array,
                default: null
            },
            changedPriceIds:{
                type: Array,
                default: null
            }


        },
        data() {
            return {
                event: [],
                openType: true,
                eventUpd: new FormData(),
                state: false,
                allTypes: null,
                filesDel: [],
                filesUpd: [],
                pricesDel: [],
                pricesUpd: [],
                placeUpd: [],
                typesDel: [],
                typesUpd: [],
                history_mode: false,
                history_add: true,
                history_edit: true,
                history_delete: true,
                priceId: 0,
            }
        },

        components: {
            CarouselGallery,
            AuthorMiniCard,
            PlacesListCard,
            ChangeStatus,
            PriceSegment,
            VueDatePicker,
            TypeList
        },
        methods: {
            ...mapActions(useEventStore, ['getEventForIds', 'changeStatus']),
            ...mapActions(useToastStore, ['showToast']),
            ...mapActions(useLoaderStore, ['openLoaderFullPage', 'closeLoaderFullPage']),
            ...mapActions(useHistoryContentStore, ['saveHistory']),
            ...mapActions(useTypeStore,['getEventTypes']),
            editUpd() {
                this.eventTime = [
                    this.event.date_start,
                    this.event.date_end
                ]
                this.state = true
            },
            canceleUpd() {
                this.event = null
                this.filesDel = []
                this.filesUpd = []
                this.pricesDel = []
                this.pricesUpd = []
                this.placeUpd = []
                this.typesDel = []
                this.typesUpd = []
                this.getEventTypes()
                this.getEvent()
                this.state = false
            },

            openTypeFnc(){
                this.openType = !this.openType
            },

            clickUpd(event) {
                // Передаём форму обработанную в масси в локальную переменную функции
                let mass = Object.entries(event.target.form)
                let historyEvent = {
                    history_files: [],
                    history_places: [],
                }
                // Перебираем массив и формируем форм дату
                mass.forEach(item => {
                    switch(item[1].id) {
                        case(`event-${this.event.id}-name-input`):
                        if (item[1].value != this.event.name) {
                            historyEvent.name = item[1].value
                        }
                        break;
                        case(`event-${this.event.id}-sponsor-input`):
                        if (item[1].value != this.event.sponsor) {
                            historyEvent.sponsor = item[1]
                        }
                        break;
                        case(`event-${this.event.id}-description-input`):
                        if (item[1].value != this.event.description) {
                            historyEvent.description = item[1].value
                        }
                        break;
                        case(`event-${this.event.id}-materials-input`):
                        if (item[1].value != this.event.materials) {
                            historyEvent.materials = item[1].value
                        }
                        break;
                    }
                })
                if(this.eventTime[0] != this.event.date_start && this.eventTime.length) {
                    historyEvent.date_start = this.eventTime[0]
                }
                if(this.eventTime[1] != this.event.date_end && this.eventTime.length) {
                    historyEvent.date_end = this.eventTime[1]
                }

                if (this.typesDel.length != 0 || this.typesUpd.length != 0){
                    historyEvent.history_types = []

                    this.typesDel.forEach(item => {
                        historyEvent.history_types.push(item)
                    })
                    this.typesUpd.forEach(item => {
                        historyEvent.history_types.push(item)
                    })
                }
                historyEvent.history_prices = []
                    this.pricesDel.forEach((item) => {
                        item.on_delete = true
                        historyEvent.history_prices.push(item)
                    })
                    this.pricesUpd.forEach((item) => {
                        delete item.id
                        delete item.new_id
                        historyEvent.history_prices.push(item)
                    })
                historyEvent.history_files = []
                // Перебираем и передаём фото на добавлений в форм дату
                this.filesUpd.forEach((item) => {
                    historyEvent.history_files.push(item)
                })
                // Перебираем, добавляем поле и передаём фото на удаление в форм дату
                this.filesDel.forEach((item) => {
                    item.onDelete = true
                    historyEvent.history_files.push(item)
                })
                if (this.placeUpd) {
                    this.placeUpd.forEach((item, key) => {
                            Object.keys(item).find(k => {
                                if ((k == 'index')) {
                                    delete this.placeUpd[key].index
                                    return true
                                }
                            })
                            if (item.id == 0) {
                                delete item.id
                                delete this.placeUpd[key].id
                            }
                            else if(item.on_delete == true){
                                let saveAttr = ['place_id','on_delete','id']
                                Object.keys(item).forEach(key => {
                                    if(!saveAttr.includes(key)){
                                        item.place_id = item.id
                                        delete item[key]
                                    }
                                })
                            }
                            else{
                                this.placeUpd[key].place_id = JSON.parse(JSON.stringify(item.id))
                                delete this.placeUpd[key].id
                            }
                            Object.keys(item).find(k => {
                                if ((k == 'seances')) {
                                    this.placeUpd[key].history_seances = []
                                    item.seances.forEach((i,k) => {
                                        Object.keys(item).find(ind => {
                                            if ((ind == 'index')) {
                                                delete this.placeUpd[key].history_seances[k].index
                                                return true
                                            }
                                        })
                                        if (i.id == 0) {
                                            delete i.id
                                        } else {
                                            i.seance_id = i.id
                                            delete i.id
                                        }
                                        delete i.index
                                        this.placeUpd[key].history_seances.push(JSON.parse(JSON.stringify(i)))
                                    })
                                    delete this.placeUpd[key].seances
                                    return true
                                }
                            })
                        })
                    historyEvent.history_places = {...this.placeUpd}
                }

                this.state = false
                const params = {
                    id: this.event.id,
                    type: "Event",
                    history_content: {...historyEvent}
                }
                this.openLoaderFullPage()
                this.saveHistory(params).pipe(
                    map(response => {
                        this.showToast(MessageContents.success_upd_content, 'success')
                        this.eventUpd = new FormData
                        this.event = null
                        this.filesDel = []
                        this.filesUpd = []
                        this.pricesDel = []
                        this.pricesUpd = []
                        this.placeUpd = []
                        this.typesDel = []
                        this.typesUpd = []
                        this.getEventTypes()
                        this.getEvent()
                    }),
                    takeUntil(this.destroy$),
                    catchError(err => {
                        399 < err.response.status && err.response.status < 500 ? this.showToast(MessageContents.warning_upd_content + ': ' + err.message, 'warning') : null
                        499 < err.response.status && err.response.status < 600 ? this.showToast(MessageContents.error_upd_content + ': ' + err.message, 'error') : null
                        console.log(err)
                        return of(EMPTY)
                    })
                ).subscribe()
            },
            statusChange(status) {
                // Меняем статус
                this.openLoaderFullPage()
                this.changeStatus(status, this.event.id).pipe(
                    map(response => {
                        this.showToast(MessageContents.success_upd_status_content, 'success')
                        this.getEvent()
                    }),
                    catchError(err => {
                        399 < err.response.status && err.response.status < 500 ? this.showToast(MessageContents.warning_upd_status_content + ': ' + err.message, 'warning') : null
                        499 < err.response.status && err.response.status < 600 ? this.showToast(MessageContents.error_upd_status_content + ': ' + err.message, 'error') : null
                        console.log(err)
                        return of(EMPTY)
                    }),
                    takeUntil(this.destroy$)
                ).subscribe()
            },
            // deleteFromCurrentPrices(price) {
            //     if (this.event.price.find(item => item.id === price.id)) {
            //         this.event.price = this.event.price.filter(item => item.id !== price.id)
            //         if (price.id){
            //             this.pricesDel.push({"id":price.id, "on_delete":true})
            //         }
            //     }
            // },

            deleteFromCurrentPrices(price){

                for(let i = 0; i < this.event.price.length; i++){

                    if((Object.keys(this.event.price[i]).indexOf("new_id") != -1) && (this.event.price[i].new_id == price.new_id)){
                        this.event.price.splice(i, 1)
                    }
                    else if(this.event.price[i].id == price.id){
                        // this.sight.prices.splice(i, 1)
                    }

                }
                for(let i = 0; i < this.pricesUpd.length; i++){

                    if((Object.keys(this.pricesUpd[i]).indexOf("new_id") != -1) && (this.pricesUpd[i].new_id == price.new_id)){
                        this.pricesUpd.splice(i, 1)
                    }
                }

                if (price.id && !price.new_id){
                    this.pricesDel.push({"price_id":price.id, "on_delete":true})
                    this.event.price.find((i, k) => {
                        if (i.id == price.id) {
                            this.event.price.splice(k, 1)
                            return true
                        }
                    })
                }
            },
            checkObjInArray(obj, array){
            for (let i = 0; i<array.length; i++){
                if (array[i].id === obj.id){
                    return true
                }
            }
            return false
        },

        addToCurrentTypes(type){
            if (this.event.types.find(item => item.id === type.id)){
                if (this.checkObjInArray(type, this.typesDel)){
                    this.typesDel = this.typesDel.filter(item => item.id !== type.id)
                } else {
                    this.typesDel.push({"id": type.id, "on_delete":true})
                }

            } else {
                if(this.typesUpd.find(item => item.id === type.id)) {
                    this.typesUpd = this.typesUpd.filter(item => item.id !== type.id)
                } else {
                    this.typesUpd.push({"id": type.id})
                }
            }
        },

        priceUpd(price){
            if(price.id != undefined && !this.checkObjInArray(price,this.pricesUpd)){
                let p = {'id':price.id, 'cost_rub':price.cost_rub,'descriptions':price.descriptions, 'price_id':price.id}
                this.pricesUpd.push(p)
            }
        },
            // addToCurrentPrices(){
            //     this.event.price.push({"cost_rub":null, "descriptions":""})

            // },

            addToCurrentPrices(){
                let price1 = {"cost_rub":0, "descriptions":"Описание", "new_id":this.priceId}
                let price2 = price1
                this.pricesUpd.push(price2)
                this.event.price.push(price1)
                this.priceId++
            },
            getAllTypes(){
                this.openLoaderFullPage()
                this.getEventTypes().pipe(
                    retry(3),
                    delay(200),
                    catchError(error => {
                        console.log(err)
                        this.closeLoaderFullPage()
                        return of(EMPTY)
                    })
                ).subscribe(response => {
                    this.allTypes = response.data.types
                    this.closeLoaderFullPage()
                })
            },
            getEvent() {
                let id
                this.$props.id ? id = this.id : id = this.$route.params.id
                this.openLoaderFullPage()
                if(this.$props.event_ == null){
                    this.getEventForIds(id).pipe(
                    map(response => {
                        this.event = response.data
                        // this.event.date_start = this.$helpers.OutputCurentTime.outputCurentTime(response.data.date_start)
                        // this.event.date_end = this.$helpers.OutputCurentTime.outputCurentTime(response.data.date_end)
                        this.closeLoaderFullPage()
                    }),
                    catchError(err => {
                        console.log(err)
                        this.showToast('При загрузке события возникла ошибка: ' + err.message, 'error')
                        this.closeLoaderFullPage()
                        return of(EMPTY)
                    }),
                    takeUntil(this.destroy$),
                ).subscribe()
                }
                else{
                    this.event = this.$props.event_
                }

            },
            deleteFiles(file) {
                this.event.files.find((item, index) => {
                    if (item.name == file.name ) {
                        let coin = this.filesUpd.find((itm, i) => {
                            if (itm.name == file.name ) {
                                this.filesUpd.splice(i, 1)
                                return true
                            }
                        })
                        coin ? null : this.filesDel.push(file)
                        this.event.files.splice(index, 1)
                        return true
                    }
                })
            },
            updateFiles(files) {
                files = Array.from(files)
                files.forEach(file => {
                    let reader = new FileReader()
                    reader.readAsDataURL(file)
                    reader.onload = () => {
                        this.event.files.push({link: reader.result, name: file.name, size: file.size, type: file.type})
                    }
                    this.filesUpd.push(file)
                })
            },
            backButton(){
                router.go(-1)
            },
            setPlace(place) {
                let event = JSON.parse(JSON.stringify(this.event))
                let index = place.index
                let placeOnDel = Object.keys(place).find(key => {
                    if ((key == 'on_delete') && (place[key] == true)) {
                        return true
                    } else {
                        return false
                    }
                });
                if (placeOnDel) {
                    // Если есть поле on_delete
                    if(place.id) {
                        // Если есть поле id и place существует в БД
                        event.places_full[index].on_delete = true
                        this.placeUpd.push(place)
                    } else {
                        // Если нет поля id и place не существует в БД
                        event.places_full.splice(place.index)
                    }
                } else { // Если поля on_delete нету
                    // this.$helpers.deepMerge(this.event.places_full[index],place)
                    let mergePlace = {...place}
                    delete mergePlace.seances
                    this.$helpers.DeepMerge.deepMerge(event.places_full[index], JSON.parse(JSON.stringify(mergePlace)))
                    // Проверяем новое ли это обновление для place или place уже обновлялся и есть в массииве
                    let getIndex = this.placeUpd.findIndex((item, key) => {
                        if(item.index == index) {
                            return true
                        }
                    })
                    if (getIndex != -1) {
                        // Проверяем есть ли сеансы
                        let seancesOnUpd = Object.keys(place).find(key => {
                                if ((key == 'seances')) {
                                    return true
                                } else {
                                    return false
                                }
                            });
                        if (seancesOnUpd) {
                            // Перебираем массив пришедших сеансов
                            place.seances.forEach((item, key) => {
                                // Проверяем есть ли поля on_delete в объекте seance и стоит ли у него значение true
                                let seanceOnDel = Object.keys(item).find(key => {
                                    if ((key == 'on_delete') && (item[key] == true)) {
                                        return true
                                    } else {
                                        return false
                                    }
                                });
                                if (seanceOnDel) {
                                    // Если есть поле on_delete со значением true
                                    if ((item.id !== 0) && item.id){
                                        // Если есть поле id не нулевое, то сеанс есть в бд и его нужно зафиксировать
                                        let oldSeance = this.placeUpd[getIndex].seances.findIndex((i,k) => {
                                            if (i.index == item.index) {
                                                this.placeUpd[getIndex].seances[k] = JSON.parse(JSON.stringify(item))
                                                return true
                                            }
                                        })
                                        if (oldSeance != 0 && oldSeance) {
                                            this.placeUpd[getIndex].seances.push(JSON.parse(JSON.stringify(item)))
                                        }
                                        event.places_full[place.index].seances[item.index].on_delete = true
                                    } else {
                                        let newSeances = []
                                        // Если поле id нулевое, то просто удалить
                                        this.placeUpd[getIndex].seances.map((i,k) => {
                                            if (i.index !== item.index) {
                                                newSeances.push(JSON.parse(JSON.stringify(i)))
                                                return true
                                            }
                                        })
                                        place.seances = JSON.parse(JSON.stringify(newSeances))
                                        this.placeUpd[getIndex].seances = JSON.parse(JSON.stringify(newSeances))
                                        event.places_full[place.index].seances.splice(item.index, 1)
                                    }
                                } else {
                                    // Если нет поля on_delete или его значение false
                                    // Проверяем есть ли сеансы у плэйса на обновлении
                                    let seanceOnUpd = Object.keys(this.placeUpd[getIndex]).find(key => {
                                        if (key == 'seances') {
                                            return true
                                        } else {
                                            return false
                                        }
                                    })
                                    if (seanceOnUpd) {
                                        // Если сеансы уже есть перебираем массив сеансов которые уже на обновлении
                                        let sean = 0;
                                        this.placeUpd[getIndex].seances.forEach((i, k) => {
                                            // Если не совпадает индекс то добавляем запоминаем ключь, а остальное в плэйс
                                            if (i.index !== item.index) {
                                                place.seances.push(JSON.parse(JSON.stringify(i)))
                                            } else {
                                                sean = k
                                            }
                                        })
                                        if (sean && sean != 0) {
                                            this.placeUpd[getIndex].seances[sean] = JSON.parse(JSON.stringify(item))
                                        } else {
                                            this.placeUpd[getIndex].seances.push(JSON.parse(JSON.stringify(item)))
                                        }

                                        // this.placeUpd[getIndex].seances = JSON.parse(JSON.stringify(place.seances))
                                    } else {
                                        // Если сеансов ещё нет
                                        this.placeUpd[getIndex].seances = []
                                        this.placeUpd[getIndex].seances.push(JSON.parse(JSON.stringify(...place.seances)))
                                    }
                                    event.places_full[index].seances[item.index] = JSON.parse(JSON.stringify(item))
                                }

                            })
                        }
                        const mergePlaceUpd = JSON.parse(JSON.stringify(place))
                        delete mergePlaceUpd.seances
                        this.$helpers.DeepMerge.deepMerge(this.placeUpd[getIndex], mergePlaceUpd)
                    } else {
                        // Если нету в массиве, то добавляем
                        let seanceOnUpd = Object.keys(place).find(key => {
                            if ((key == 'seances')) {
                                return true
                            } else {
                                return false
                            }
                        })
                        if(seanceOnUpd) {
                            place.seances.forEach((item, key) => {
                                // Проверяем есть ли поля on_delete в объекте seance и стоит ли у него значение true
                                let seanceOnDel = Object.keys(item).find(key => {
                                    if ((key == 'on_delete') && (item[key] == true)) {
                                        return true
                                    } else {
                                        return false
                                    }
                                })
                                if (seanceOnDel) {
                                    event.places_full[place.index].seances[item.index].on_delete = true
                                }
                            })
                        }
                        this.placeUpd.push(JSON.parse(JSON.stringify(place)))
                    }
                }
                this.event = event
            },
            addNewPlace() {
                this.event.places_full.push({
                    id: 0,
                    address: this.event.places_full[0].address,
                    event_id: this.event.id,
                    sight_id: this.event.places_full[0].sight_id,
                    latitude: this.event.places_full[0].latitude,
                    longitude: this.event.places_full[0].longitude,
                    location_id: 1,
                    seances: [],
                    location: {},
                    index: this.event.places_full.length
                })
            }
        },
    }
    </script>
    <style>

    .button-menu{
        max-height: 80px;
        -webkit-box-shadow: 0px -5px 5px -5px rgba(34, 60, 80, 0.29);
        -moz-box-shadow: 0px -5px 5px -5px rgba(34, 60, 80, 0.29);
        box-shadow: 0px -5px 5px -5px rgba(34, 60, 80, 0.29);
    }
    .unselectable {
        -webkit-user-select: none;
        -webkit-touch-callout: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;

        }

        .slide-fade-enter-active {
                transition: all 0.3s ease-out;
                }

        .slide-fade-leave-active {
            transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
            }

        .slide-fade-enter-from,
        .slide-fade-leave-to {
            transform: translateX(20px);
            opacity: 0;
            }


        /* Светлый стиль datepicker */



        .dp_theme_light {
            --dp-background-color: #fff;
            --dp-text-color: #212121;
            --dp-hover-color: #f3f3f3;
            --dp-hover-text-color: #212121;
            --dp-hover-icon-color: #959595;
            --dp-primary-color: #1976d2;
            --dp-primary-disabled-color: #6bacea;
            --dp-primary-text-color: #f8f5f5;
            --dp-secondary-color: #c0c4cc;
            --dp-border-color: #ddd;
            --dp-menu-border-color: #ddd;
            --dp-border-color-hover: #aaaeb7;
            --dp-disabled-color: #f6f6f6;
            --dp-scroll-bar-background: #f3f3f3;
            --dp-scroll-bar-color: #959595;
            --dp-success-color: #76d275;
            --dp-success-color-disabled: #a3d9b1;
            --dp-icon-color: #959595;
            --dp-danger-color: #ff6f60;
            --dp-marker-color: #ff6f60;
            --dp-tooltip-color: #fafafa;
            --dp-disabled-color-text: #8e8e8e;
            --dp-highlight-color: rgb(25 118 210 / 10%);
            --dp-range-between-dates-background-color: var(--dp-hover-color, #f3f3f3);
            --dp-range-between-dates-text-color: var(--dp-hover-text-color, #212121);
            --dp-range-between-border-color: var(--dp-hover-color, #f3f3f3);
        }
        /* Тёмный стиль datepicker */
        .dp_theme_dark {
            --dp-background-color: #2b3444;
            --dp-text-color: #fff;
            --dp-hover-color: #484848;
            --dp-hover-text-color: #fff;
            --dp-hover-icon-color: #959595;
            --dp-primary-color: #005cb2;
            --dp-primary-disabled-color: #61a8ea;
            --dp-primary-text-color: #fff;
            --dp-secondary-color: #a9a9a9;
            --dp-border-color: #323c4c;
            --dp-menu-border-color: #2d2d2d;
            --dp-border-color-hover: #aaaeb7;
            --dp-disabled-color: #737373;
            --dp-disabled-color-text: #d0d0d0;
            --dp-scroll-bar-background: #212121;
            --dp-scroll-bar-color: #484848;
            --dp-success-color: #00701a;
            --dp-success-color-disabled: #428f59;
            --dp-icon-color: #959595;
            --dp-danger-color: #e53935;
            --dp-marker-color: #e53935;
            --dp-tooltip-color: #3e3e3e;
            --dp-highlight-color: rgb(0 92 178 / 20%);
            --dp-range-between-dates-background-color: var(--dp-hover-color, #484848);
            --dp-range-between-dates-text-color: var(--dp-hover-text-color, #fff);
            --dp-range-between-border-color: var(--dp-hover-color, #fff);
        }
    </style>
