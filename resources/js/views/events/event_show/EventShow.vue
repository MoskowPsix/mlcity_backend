<template class="flex" lang="">
    <div
        v-if="event && connectState"
        :id="'event-' + event.id"
        class="lg:pb-20 lg:mt-20"
    >
        <!-- Кнопка назад -->
        <!-- <button
            v-if="connectState.BackButton"
            @click.prevent="backButton()"
            type="button"
            class="flex m-4 items-center rounded bg-gray-200/40 dark:bg-gray-800/80 max-h-12 min-w-1/12 max-w-[5rem] mr-3 px-4 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-gray-500 dark:text-gray-300/50 transition duration-150 ease-in-out hover:bg-gray-400/30 dark:hover:bg-gray-700/60 active:bg-gray-400/60 dark:active:bg-gray-700/80 ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
            </svg>
        </button> -->
        <div
            v-if="connectState.EditButton"
            class="button-menu absolute top-[0%] bg-[#fff] dark:bg-gray-900 z-50 w-full lg:w-[75%] flex justify-between"
        >
            <div class="flex dark:bg-gray-900 w-full justify-between">
                <div class="flex">
                    <button
                        v-if="connectState.BackButton"
                        type="button"
                        class="flex m-4 items-center rounded bg-gray-200/40 dark:bg-gray-800/80 max-h-12 min-w-1/12 max-w-[5rem] mr-3 px-4 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-gray-500 dark:text-gray-300/50 transition duration-150 ease-in-out hover:bg-gray-400/30 dark:hover:bg-gray-700/60 active:bg-gray-400/60 dark:active:bg-gray-700/80"
                        @click.prevent="backButton()"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75"
                            />
                        </svg>
                    </button>
                    <input
                        v-if="state"
                        class="rounded-lg text-cyan-50 bg-[#4C81F7] hover:bg-[#6393FF] m-5 p-2 z-50 cursor-pointer font-[Montserrat-Regular]"
                        type="button"
                        value="Применить"
                        @click="clickUpd()"
                    />
                    <button
                        v-if="state"
                        class="rounded-lg bg-gray-600 font-[Montserrat-Regular] text-cyan-50 m-5 p-2 cursor-pointer"
                        @click="canceleUpd()"
                        >Отмена</button
                    >
                    <button
                        v-if="
                            !state &&
                            (role == 'root' ||
                                JSON.parse(user).id == event.user_id)
                        "
                        class="rounded-lg text-cyan-50 font-[Montserrat-Regular] bg-[#4C81F7] hover:bg-[#6393FF] m-5 p-2 cursor-pointer"
                        @click="editUpd()"
                        >Редактировать</button
                    >
                </div>
            </div>
            <div class="hidden lg:flex">
                <div
                    v-if="connectState.AuthorCard && connectState.StatusCard"
                    :id="'event-' + event.id + '-author'"
                >
                    <!-- || (role != 'root' || role != 'Admin' || role != 'Moderator' -->
                    <div
                        v-if="
                            !state &&
                            connectState.StatusCard &&
                            (role == 'root' ||
                                role == 'Admin' ||
                                role == 'Moderator')
                        "
                        class="bg-transparent p-2 mt-1 dark:border-gray-700/70 flex dark:"
                    >
                        <span
                            v-if="
                                connectState.AuthorCard &&
                                connectState.StatusCard
                            "
                            class="flex items-center mr-4"
                            >Статус:
                        </span>
                        <ChangeStatus
                            v-if="event.statuses"
                            :id="'event-' + event.id + '-status'"
                            :edit-button="connectState.EditButton"
                            :status="event.statuses[0].name"
                            @status-changed="statusChange"
                        />
                    </div>
                </div>
            </div>
        </div>
        <iframe
            v-if="frameState && !state"
            width="100%"
            height="1000rem"
            class="z-10"
            :src="'http://localhost:8100/events/' + event.id"
        >
        </iframe>
        <div
            v-if="
                connectState.AuthorCard && JSON.parse(user).id != event.user_id
            "
            class="max-w-[25rem] hidden lg:block m-8"
        >
            <AuthorMiniCard
                v-if="event.author"
                :author="event.author"
            />
        </div>

        <div class="flex lg:hidden mt-4">
            <div
                v-if="connectState.AuthorCard && connectState.StatusCard"
                :id="'event-' + event.id + '-author'"
            >
                <!-- || (role != 'root' || role != 'Admin' || role != 'Moderator' -->
                <div
                    v-if="
                        !state &&
                        connectState.StatusCard &&
                        (role == 'root' ||
                            role == 'Admin' ||
                            role == 'Moderator')
                    "
                    class="bg-transparent p-2 mt-1 dark:border-gray-700/70 flex"
                >
                    <p
                        v-if="
                            connectState.AuthorCard && connectState.StatusCard
                        "
                        class="flex items-center mr-4"
                        >Статус:</p
                    >
                    <ChangeStatus
                        v-if="event.statuses"
                        :id="'event-' + event.id + '-status'"
                        :edit-button="connectState.EditButton"
                        :status="event.statuses[0].name"
                        @status-changed="statusChange"
                    />
                    <div
                        v-if="
                            connectState.AuthorCard &&
                            JSON.parse(user).id != event.user_id
                        "
                        class=""
                    >
                        <AuthorMiniCard
                            v-if="event.author"
                            :author="event.author"
                        />
                    </div>
                </div>
            </div>
        </div>
        <form
            id="editForm"
            class="max-w-[90%]"
        >
            <section
                v-if="!frameState || state"
                class="flex justify-center max-w-[90%] lg:min-w-[80%] lg:max-w-[80%] m-[auto]"
            >
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

                <div
                    class="header-content flex justify-center dark:text-gray-400"
                >
                    <div
                        class="header-content-main flex items-center justify-center min-w-[100%] flex-col m-2 md:p-5 md:flex-col"
                    >
                        <div
                            class="w-[100%] sm:w-[100%] md:w-[100%] xl:w-[100%] text-xs lg:text-lg"
                        >
                            <!-- <h1 class=" font-[Montserrat-Regular] mb-2" >Название</h1> -->
                            <CarouselGallery
                                v-if="event.files && connectState.Gallery"
                                :id="'event-' + event.id + '-gallery'"
                                class="w-[100%] lg:w-[100%] m-[auto] mb-4 mt-4"
                                :files="event.files"
                                :wright-state="state"
                                @on-delete-file="deleteFiles"
                                @on-update-file="updateFiles"
                            ></CarouselGallery>
                            <div
                                :class="{
                                    'border-blue-700/70':
                                        $props.changedFields != null &&
                                        $props.changedFields.includes('name'),
                                }"
                                class="title text-center p-2 w-[100%] border-2 border-[#EDEDED] rounded-md mt-1 font-[Montserrat-Medium] flex justify-center dark:border-gray-700/80"
                            >
                                <label
                                    v-if="!state && connectState.NameLine"
                                    :id="'event-' + event.id + '-name'"
                                    class=""
                                    ><h1 class="font-bold">{{
                                        event.name
                                    }}</h1></label
                                >
                                <input
                                    v-if="state && connectState.NameLine"
                                    :id="'event-' + event.id + '-name-input'"
                                    class="text-xs lg:text-lg leading-tight text-neutral-800 dark:text-gray-400 dark:bg-gray-700 rounded-lgp-2pl-1 border m-0 bg-transparent w-[100%] text-center border-none dark:border-gray-700/80"
                                    :value="event.name"
                                    type="text"
                                    name="name"
                                    placeholder="Введите название мероприятия"
                                    @input="
                                        (event) => (text = event.target.value)
                                    "
                                />
                            </div>

                            <div
                                class="flex justify-between mt-4 flex-col min-[1577px]:flex-row"
                            >
                                <div
                                    v-if="!state"
                                    class="min-w-[34%] mb-4"
                                >
                                    <!-- <label class="font-[Montserrat-Regular] text-xs lg:text-lg " for="">Тип</label> -->
                                    <div
                                        :class="{ 'border-blue-700/70': state }"
                                        class="transition duration-1000 border-2 rounded-md font-[Montserrat-Medium] max-w-[60%] py-0.5"
                                    >
                                        <div
                                            v-if="event.types"
                                            class="text-center py-2 space-y-2.5"
                                        >
                                            <p
                                                v-for="etype in event.types"
                                                :key="etype.id"
                                                :class="{
                                                    'border-b-blue-700/70':
                                                        $props.changedTypeIds !=
                                                            null &&
                                                        $props.changedTypeIds.includes(
                                                            etype.id,
                                                        ),
                                                    'border-red-600':
                                                        etype.on_delete !=
                                                            null &&
                                                        etype.on_delete,
                                                }"
                                                class="border-b-2 mx-4"
                                            >
                                                {{ etype.name }}</p
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div
                                    v-if="state"
                                    class="hidden xl:block min-w-[34%]"
                                >
                                </div>
                                <div class="flex">
                                    <div
                                        v-if="!state"
                                        class="mr-4 flex flex-col items-center lg:mr-4"
                                    >
                                        <!-- <label class="font-[Montserrat-Regular] text-xs lg:text-lg" for="">Начало</label> -->
                                        <div
                                            :class="{
                                                'border-blue-700/70':
                                                    $props.changedFields !=
                                                        null &&
                                                    $props.changedFields.includes(
                                                        'date_start',
                                                    ),
                                            }"
                                            class="flex justify-center border-2 border-[#EDEDED] dark:border-gray-700/70 rounded-md p-0.5 font-[Montserrat-Medium] w-[100%]"
                                        >
                                            <div
                                                class="font-[Montserrat-Medium] w-[100%] text-xs text-center lg:text-lg"
                                                >{{ event.date_start }}</div
                                            >
                                        </div>
                                    </div>
                                    <VueDatePicker
                                        v-if="state"
                                        v-model="eventTime"
                                        range
                                        model-type="dd.MM.yyyy, HH:mm:ss"
                                        :class="
                                            themeState
                                                ? 'w-full h-full mt-1 dp_theme_dark'
                                                : 'w-full h-full mt-1 dp_theme_light'
                                        "
                                        placeholder="Дата и время события"
                                    />

                                    <div
                                        v-if="!state"
                                        class="text-center"
                                    >
                                        <!-- <label class="font-[Montserrat-Regular] text-xs lg:text-lg" for="">Конец</label> -->
                                        <div
                                            :class="{
                                                'border-blue-700/70':
                                                    $props.changedFields !=
                                                        null &&
                                                    $props.changedFields.includes(
                                                        'date_end',
                                                    ),
                                            }"
                                            class="flex justify-center border-2 border-[#EDEDED] rounded-md dark:border-gray-700/70 p-0.5 font-[Montserrat-Medium] w-[100%] text-xs lg:text-lg"
                                        >
                                            <div>{{ event.date_end }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="xl:hidden lg:block min-w-[34%] mt-4">
                            </div>

                            <div
                                v-if="
                                    connectState.PlaceCard &&
                                    connectState.AuthorCard
                                "
                                class="w-[100%] bg-transparen font-[Montserrat-Regular]"
                            >
                                <div
                                    v-if="connectState.PlaceCard"
                                    class="2xl:col-span-3 xl:col-span-1 lg:ol-span-1 mt-2"
                                >
                                    <div
                                        :id="'event-' + event.id + '-place'"
                                        class="dark: dark:border-gray-700 p-1 rounded-lg mt-4"
                                    >
                                        <div
                                            v-for="(
                                                place, index
                                            ) in event.places_full"
                                            :key="place.id"
                                        >
                                            <PlacesListCard
                                                v-if="!place.on_delete"
                                                :id="
                                                    'event-' +
                                                    event.id +
                                                    '-place-' +
                                                    place.id
                                                "
                                                :changed-place-ids="
                                                    changedPlaceIds
                                                "
                                                :changed-seance-ids="
                                                    changedSeanceIds
                                                "
                                                :event-id="event.id"
                                                :state-upd="state"
                                                :index="index"
                                                :place="
                                                    JSON.parse(
                                                        JSON.stringify(place),
                                                    )
                                                "
                                                class="mt-2"
                                                @on-upd-place="setPlace"
                                            />
                                        </div>
                                        <div class="">
                                            <button
                                                v-if="pagePlaceForPageEvent"
                                                class="w-full dark:bg-blue-500/60 py-3 bg-blue-500/60 dark:text-gray-300 rounded-lg shadow-lg"
                                                @click.prevent="
                                                    getPlacesForEvent()
                                                "
                                            >
                                                <svg
                                                    v-if="loaderPlaces"
                                                    aria-hidden="true"
                                                    class="mx-auto w-7 h-7 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                                    viewBox="0 0 100 101"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                                        fill="currentColor"
                                                    />
                                                    <path
                                                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                                        fill="currentFill"
                                                    />
                                                </svg>
                                                <p v-if="!loaderPlaces"
                                                    >Показать ещё</p
                                                >
                                            </button>
                                        </div>
                                        <div
                                            v-if="state"
                                            class="flex items-center justify-center tetxt-center max-w-[14rem] xl:h-[4rem] md:h-[4rem] sm:h-[3rem] mb-4 text-cyan-50 bg-[#4C81F7] hover:bg-[#6393FF] rounded-md dark:bg-gray-700/50 cursor-pointer text-center unselectable transition hover:dark:bg-gray-700/20"
                                            @click.prevent="addNewPlace"
                                            >Добавить местопровидения</div
                                        >
                                    </div>
                                </div>
                            </div>
                            <div
                                class="content-descriprion w-[100%] lg:w-[100%] m-[auto] mt-10 pt-8 p2 dark:border-gray-700/80 p-2 mb-2 text-ms dark:text-gray-400"
                            >
                                <h3 class="font-[Montserrat-Bold] text-lg mb-2"
                                    >Описание</h3
                                >
                                <div
                                    v-if="!state"
                                    class="dark:bg-gray-700/50 dark:border-gray-700/70 p-0.5 rounded"
                                >
                                    <h3
                                        :class="{
                                            'border-blue-700/70 border rounded-lg':
                                                $props.changedFields != null &&
                                                $props.changedFields.includes(
                                                    'description',
                                                ),
                                        }"
                                        class="description font-[Montserrat-Medium] w-[100%] sm:w-[100%] md:w-[100%] xl:w-[100%] m-[auto] text-xs lg:text-lg p-0.5 dark:bg-gray-700/50 dark:border-gray-700/70"
                                    >
                                        {{ event.description }}
                                    </h3>
                                </div>
                                <div>
                                    <div
                                        v-if="state"
                                        class="rounded-md dark:bg-gray-700/50"
                                    >
                                        <textarea
                                            :id="
                                                'event-' +
                                                event.id +
                                                '-description-input'
                                            "
                                            class="border-none bg-transparent description font-[Montserrat-Medium] w-[100%] sm:w-[100%] md:w-[100%] xl:w-[100%] m-[auto] text-xs lg:text-lg p2"
                                            :value="event.description"
                                            name="description"
                                            cols="30"
                                            rows="10"
                                            @input="
                                                (event) =>
                                                    (text = event.target.value)
                                            "
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="state"
                                class="flex w-[100%] mt-4"
                            >
                                <Transition name="slide-fade">
                                    <div
                                        :id="'event-' + event.id + '-type'"
                                        class="z-50 rounded-lg h-auto dark:bg-gray-800 dark:border-gray-700/70 p-2"
                                    >
                                        <h1
                                            class="text-xl font-medium dark:text-gray-300 mb-1"
                                            >Типы</h1
                                        >
                                        <div
                                            v-if="allTypes && state"
                                            class="max-w-[30rem] lg:max-w-[100%] 2xl:max-w[100%] flex flex-wrap-reverse row mt-2 rounded-lg dark:border-gray-600/60 py-4 tree dark:bg-gray-700/20"
                                        >
                                            <div
                                                v-for="etype in allTypes"
                                                :key="etype.id"
                                            >
                                                <TypeList
                                                    v-if="
                                                        allTypes &&
                                                        event.types != null
                                                    "
                                                    :type="'event'"
                                                    :sight-id="event.id"
                                                    :all-s-types="etype"
                                                    :enable-state="state"
                                                    :current-stypes="
                                                        event.types
                                                    "
                                                    @checked="addToCurrentTypes"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </Transition>
                            </div>
                            <div class="content-description-price mt-10">
                                <h3 class="font-[Montserrat-Bold] text-lg"
                                    >Цены</h3
                                >
                                <div
                                    class="content-description-price-grid flex justify-center m-[auto]"
                                >
                                    <div
                                        class="m-[auto] flex row flex-wrap max-w-[82%]"
                                    >
                                        <div
                                            v-for="(
                                                price, index
                                            ) in event.price"
                                            :key="price.id"
                                            class="flex flex-row mt-2 mr-2 max-w-[15rem]"
                                        >
                                            <PriceSegment
                                                :id="
                                                    'event-' +
                                                    event.id +
                                                    '-price-' +
                                                    price.id
                                                "
                                                class="p-2 border dark:border-gray-700/50 rounded-lg"
                                                :price="price"
                                                :state="state"
                                                :index="index"
                                                @on-del-price="
                                                    deleteFromCurrentPrices
                                                "
                                                @on-upd-price="priceUpd"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div
                                    v-if="state"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="flex items-center justify-center max-w-[14rem] h-[3rem] mb-4 text-cyan-50 bg-[#4C81F7] hover:bg-[#6393FF] rounded-md dark:bg-gray-700/50 cursor-pointer text-center unselectable transition hover:dark:bg-gray-700/20"
                                    @click="addToCurrentPrices()"
                                >
                                    Добавить билет
                                </div>
                            </div>
                            <!-- Материалы -->
                            <div class="mt-4">
                                <div v-if="!state">
                                    <label
                                        class="font-[Montserrat-Regular] text-xs lg:text-lg mb-2"
                                        for=""
                                        >Материалы</label
                                    >
                                    <div
                                        :class="{
                                            'border-blue-700/70':
                                                $props.changedFields != null &&
                                                $props.changedFields.includes(
                                                    'materials',
                                                ),
                                        }"
                                        class="flex justify-center border-2 border-[#EDEDED] dark:border-gray-700/70 rounded-md p-0.5 font-[Montserrat-Medium] sm:text-sm min-h-[2rem]"
                                    >
                                        <div
                                            v-if="event.sponsor"
                                            class="text-xs lg:text-lg"
                                            >{{ event.materials }}</div
                                        >
                                    </div>
                                </div>

                                <label v-if="state">
                                    <h1
                                        class="font-[Montserrat-Regular] text-xs lg:text-lg"
                                        >Материалы</h1
                                    >
                                    <div
                                        class="flex justify-center border-2 border-[#EDEDED] rounded-md p-0.5 font-[Montserrat-Medium] sm:text-sm min-h-[2rem] dark:border-gray-700/70"
                                    >
                                        <p
                                            v-if="!state"
                                            :id="
                                                'event-' +
                                                event.id +
                                                '-materials'
                                            "
                                            class="text-sm font-normal dark:text-gray-200 mb-2"
                                            >{{ event.materials }}</p
                                        >
                                        <input
                                            v-if="state"
                                            :id="
                                                'event-' +
                                                event.id +
                                                '-materials-input'
                                            "
                                            class="text-xs text-center lg:text-lg border-none w-full bg-transparent"
                                            placeholder="Введите ссылки и т.д"
                                            type="text"
                                            name="materials"
                                            :value="event.materials"
                                            @input="
                                                (event) =>
                                                    (text = event.target.value)
                                            "
                                        />
                                    </div>
                                </label>
                            </div>

                            <div class="md:w-[100%] mt-4">
                                <label
                                    class="font-[Montserrat-Regular] text-xs lg:text-lg mb-2"
                                    for=""
                                    >Оганизатор</label
                                >
                                <div
                                    :class="{
                                        'border-blue-700/70':
                                            $props.changedFields != null &&
                                            $props.changedFields.includes(
                                                'sponsor',
                                            ),
                                    }"
                                    class="flex justify-center border-2 border-[#EDEDED] rounded-md w-[100%] p-0.5 font-[Montserrat-Medium] sm:text-sm mt-2 dark:border-gray-700/80"
                                >
                                    <div
                                        v-if="event.sponsor && !state"
                                        class="text-xs lg:text-lg"
                                        >{{ event.sponsor }}</div
                                    >
                                    <input
                                        v-if="state"
                                        :id="
                                            'event-' +
                                            event.id +
                                            '-sponsor-input'
                                        "
                                        class="text-xs lg:text-lg w-full dark:bg-transparent text-center border-none"
                                        type="text"
                                        name="sponsor"
                                        placeholder="Введите оганизатора"
                                        :value="event.sponsor"
                                        @input="
                                            (event) =>
                                                (text = event.target.value)
                                        "
                                    />
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
        </form>
    </div>
</template>
<script>
    import { mapActions, mapState } from 'pinia'
    import { useToastStore } from '../../../stores/ToastStore'
    import { MessageContents } from '../../../enums/content_messages'
    import { useEventStore } from '../../../stores/EventStore'
    import { useLoaderStore } from '../../../stores/LoaderStore'
    import { useHistoryContentStore } from '../../../stores/HistoryContentStore'
    import { useTypeStore } from '../../../stores/TypeStore'
    import { ref } from 'vue'
    import { catchError, map, retry, delay, takeUntil } from 'rxjs/operators'
    import { of, EMPTY, Subject } from 'rxjs'
    import { useDark } from '@vueuse/core'
    import { useLocalStorageStore } from '../../../stores/LocalStorageStore'
    import router from '../../../routes'

    import CarouselGallery from '../../../components/carousel_gallery/CarouselGallery.vue'
    import AuthorMiniCard from '../../../components/author-mini-card/AuthorMiniCard.vue'
    import PlacesListCard from '../../../components/places_list_card/PlacesListCard.vue'
    import ChangeStatus from '../../../components/change_status/ChangeStatus.vue'
    import PriceSegment from '../../../components/price_segment/PriceSegment.vue'
    import TypeList from '../../../components/types_list/TypeList.vue'
    import VueDatePicker from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'
    import { usePlaceStore } from '../../../stores/PlaceStore'
    import { usePlaceQueryBuilderStore } from '../../../stores/PlaceQueryBuilderStore'

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

        components: {
            CarouselGallery,
            AuthorMiniCard,
            PlacesListCard,
            ChangeStatus,
            PriceSegment,
            VueDatePicker,
            TypeList,
        },
        props: {
            connectState: {
                type: Object,
                default() {
                    return {
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
            },
            id: {
                type: Number,
                default: null,
            },
            // eslint-disable-next-line vue/prop-name-casing
            event_: {
                type: Object,
                default: null,
            },
            changedFields: {
                type: Array,
                default: null,
            },
            changedPlaceIds: {
                type: Array,
                default: null,
            },
            changedSeanceIds: {
                type: Array,
                default: null,
            },
            changedTypeIds: {
                type: Array,
                default: null,
            },
            frameState: {
                type: Boolean,
                default: true,
            },
        },
        setup() {
            let themeState = useDark() // Переменная состояния темы(false: light, true: dark)
            const destroy$ = new Subject()
            const formatter = ref({
                date: 'DD.MM.YYYY, hh:mm:ss',
                month: 'MMM',
            })
            const eventTime = ref({
                startDate: '',
                endDate: '',
            })
            return {
                themeState,
                eventTime,
                formatter,
                destroy$,
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
                statusNow: '',
                loaderPlaces: false,
            }
        },
        computed: {
            ...mapState(useLocalStorageStore, ['user', 'role']),
            ...mapState(usePlaceQueryBuilderStore, ['pagePlaceForPageEvent']),
        },
        mounted() {
            this.openLoaderFullPage
            this.getEvent()
            this.getAllTypes()
            this.checkRout()
        },
        methods: {
            ...mapActions(useLocalStorageStore, ['getUserLocalStorage']),
            ...mapActions(usePlaceStore, ['getPlaces']),
            ...mapActions(usePlaceQueryBuilderStore, [
                'queryBuilder',
                'setPagePlaceForPageEvent',
            ]),
            ...mapActions(useEventStore, ['getEventForIds', 'changeStatus']),
            ...mapActions(useToastStore, ['showToast']),
            ...mapActions(useLoaderStore, [
                'openLoaderFullPage',
                'closeLoaderFullPage',
            ]),
            ...mapActions(useHistoryContentStore, ['saveHistory']),
            ...mapActions(useTypeStore, ['getEventTypes']),
            // ...mapActions(useLocalStorageStore, ['getUser', 'getRole']),
            editUpd() {
                this.eventTime = [this.event.date_start, this.event.date_end]
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

            openTypeFnc() {
                this.openType = !this.openType
            },

            clickUpd() {
                console.log(document.getElementById('editForm'))
                // Передаём форму обработанную в масси в локальную переменную функции
                let mass = Object.entries(document.getElementById('editForm'))
                let historyEvent = {
                    history_files: [],
                    history_places: [],
                }
                // Перебираем массив и формируем форм дату
                mass.forEach((item) => {
                    switch (item[1].id) {
                        case `event-${this.event.id}-name-input`:
                            if (item[1].value != this.event.name) {
                                historyEvent.name = item[1].value
                            }
                            break
                        case `event-${this.event.id}-sponsor-input`:
                            if (item[1].value != this.event.sponsor) {
                                historyEvent.sponsor = item[1]
                            }
                            break
                        case `event-${this.event.id}-description-input`:
                            if (item[1].value != this.event.description) {
                                historyEvent.description = item[1].value
                            }
                            break
                        case `event-${this.event.id}-materials-input`:
                            if (item[1].value != this.event.materials) {
                                historyEvent.materials = item[1].value
                            }
                            break
                    }
                })
                if (
                    this.eventTime[0] != this.event.date_start &&
                    this.eventTime.length
                ) {
                    historyEvent.date_start = this.eventTime[0]
                }
                if (
                    this.eventTime[1] != this.event.date_end &&
                    this.eventTime.length
                ) {
                    historyEvent.date_end = this.eventTime[1]
                }

                if (this.typesDel.length != 0 || this.typesUpd.length != 0) {
                    historyEvent.history_types = []

                    this.typesDel.forEach((item) => {
                        historyEvent.history_types.push(item)
                    })
                    this.typesUpd.forEach((item) => {
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
                    item.on_delete = true
                    historyEvent.history_files.push(item)
                })
                if (this.placeUpd) {
                    this.placeUpd.forEach((item, key) => {
                        Object.keys(item).find((k) => {
                            if (k == 'index') {
                                delete this.placeUpd[key].index
                                return true
                            }
                        })
                        if (item.id == 0) {
                            delete item.id
                            delete this.placeUpd[key].id
                        } else {
                            this.placeUpd[key].place_id = JSON.parse(
                                JSON.stringify(item.id),
                            )
                            delete this.placeUpd[key].id
                        }
                        Object.keys(item).find((k) => {
                            if (k == 'seances') {
                                this.placeUpd[key].history_seances = []
                                item.seances.forEach((i, k) => {
                                    Object.keys(item).find((ind) => {
                                        if (ind == 'index') {
                                            delete this.placeUpd[key]
                                                .history_seances[k].index
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
                                    this.placeUpd[key].history_seances.push(
                                        JSON.parse(JSON.stringify(i)),
                                    )
                                })
                                delete this.placeUpd[key].seances
                                return true
                            }
                        })
                    })
                    historyEvent.history_places = { ...this.placeUpd }
                }

                this.state = false
                const params = {
                    id: this.event.id,
                    type: 'Event',
                    history_content: { ...historyEvent },
                }
                this.openLoaderFullPage()
                this.saveHistory(params)
                    .pipe(
                        map(() => {
                            this.showToast(
                                MessageContents.success_upd_content,
                                'success',
                            )
                            this.eventUpd = new FormData()
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
                        catchError((err) => {
                            399 < err.response.status &&
                            err.response.status < 500
                                ? this.showToast(
                                      MessageContents.warning_upd_content +
                                          ': ' +
                                          err.message,
                                      'warning',
                                  )
                                : null
                            499 < err.response.status &&
                            err.response.status < 600
                                ? this.showToast(
                                      MessageContents.error_upd_content +
                                          ': ' +
                                          err.message,
                                      'error',
                                  )
                                : null
                            console.log(err)
                            return of(EMPTY)
                        }),
                    )
                    .subscribe()
            },
            statusChange(status) {
                // Меняем статус
                this.openLoaderFullPage()
                this.changeStatus(status, this.event.id)
                    .pipe(
                        map(() => {
                            this.showToast(
                                MessageContents.success_upd_status_content,
                                'success',
                            )
                            this.getEvent()
                        }),
                        catchError((err) => {
                            399 < err.response.status &&
                            err.response.status < 500
                                ? this.showToast(
                                      MessageContents.warning_upd_status_content +
                                          ': ' +
                                          err.message,
                                      'warning',
                                  )
                                : null
                            499 < err.response.status &&
                            err.response.status < 600
                                ? this.showToast(
                                      MessageContents.error_upd_status_content +
                                          ': ' +
                                          err.message,
                                      'error',
                                  )
                                : null
                            console.log(err)
                            return of(EMPTY)
                        }),
                        takeUntil(this.destroy$),
                    )
                    .subscribe()
            },
            // deleteFromCurrentPrices(price) {
            //     if (this.event.price.find(item => item.id === price.id)) {
            //         this.event.price = this.event.price.filter(item => item.id !== price.id)
            //         if (price.id){
            //             this.pricesDel.push({"id":price.id, "on_delete":true})
            //         }
            //     }
            // },

            deleteFromCurrentPrices(price) {
                for (let i = 0; i < this.event.price.length; i++) {
                    if (
                        Object.keys(this.event.price[i]).indexOf('new_id') !=
                            -1 &&
                        this.event.price[i].new_id == price.new_id
                    ) {
                        this.event.price.splice(i, 1)
                    } else if (this.event.price[i].id == price.id) {
                        // this.sight.prices.splice(i, 1)
                    }
                }
                for (let i = 0; i < this.pricesUpd.length; i++) {
                    if (
                        Object.keys(this.pricesUpd[i]).indexOf('new_id') !=
                            -1 &&
                        this.pricesUpd[i].new_id == price.new_id
                    ) {
                        this.pricesUpd.splice(i, 1)
                    }
                }

                if (price.id && !price.new_id) {
                    this.pricesDel.push({ price_id: price.id, on_delete: true })
                    this.event.price.find((i, k) => {
                        if (i.id == price.id) {
                            this.event.price.splice(k, 1)
                            return true
                        }
                    })
                }
            },
            checkObjInArray(obj, array) {
                for (let i = 0; i < array.length; i++) {
                    if (array[i].id === obj.id) {
                        return true
                    }
                }
                return false
            },

            addToCurrentTypes(type) {
                if (this.event.types.find((item) => item.id === type.id)) {
                    if (this.checkObjInArray(type, this.typesDel)) {
                        this.typesDel = this.typesDel.filter(
                            (item) => item.id !== type.id,
                        )
                    } else {
                        this.typesDel.push({ id: type.id, on_delete: true })
                    }
                } else {
                    if (this.typesUpd.find((item) => item.id === type.id)) {
                        this.typesUpd = this.typesUpd.filter(
                            (item) => item.id !== type.id,
                        )
                    } else {
                        this.typesUpd.push({ id: type.id })
                    }
                }
            },

            priceUpd(price) {
                if (
                    price.id != undefined &&
                    !this.checkObjInArray(price, this.pricesUpd)
                ) {
                    let p = {
                        id: price.id,
                        cost_rub: price.cost_rub,
                        descriptions: price.descriptions,
                        price_id: price.id,
                    }
                    this.pricesUpd.push(p)
                }
                // console.log('На обновление', this.pricesUpd)
                // console.log('Все цены', this.event.price)
            },
            // addToCurrentPrices(){
            //     this.event.price.push({"cost_rub":null, "descriptions":""})

            // },

            addToCurrentPrices() {
                let price1 = {
                    cost_rub: 0,
                    descriptions: 'Описание',
                    new_id: this.priceId,
                }
                let price2 = price1
                this.pricesUpd.push(price2)
                this.event.price.push(price1)
                this.priceId++
            },
            checkRout() {
                let user = this.getUserLocalStorage()
                console.log(user.id)
                if (this.$route.params.state) {
                    this.state = true
                }
            },
            getAllTypes() {
                this.openLoaderFullPage()
                this.getEventTypes()
                    .pipe(
                        retry(3),
                        delay(200),
                        catchError((err) => {
                            console.log(err)
                            this.closeLoaderFullPage()
                            return of(EMPTY)
                        }),
                    )
                    .subscribe((response) => {
                        this.allTypes = response.data.types
                        this.closeLoaderFullPage()
                    })
            },
            getEvent() {
                let id
                this.$props.id ? (id = this.id) : (id = this.$route.params.id)
                this.openLoaderFullPage()
                if (this.$props.event_ == null) {
                    this.getEventForIds(id)
                        .pipe(
                            map((response) => {
                                this.event = response.data
                                this.statusNow = response.data.statuses[0].name
                                this.getPlacesForEvent()
                                // this.event.date_start = this.$helpers.OutputCurentTime.outputCurentTime(response.data.date_start)
                                // this.event.date_end = this.$helpers.OutputCurentTime.outputCurentTime(response.data.date_end)
                                this.closeLoaderFullPage()
                            }),
                            catchError((err) => {
                                console.log(err)
                                this.showToast(
                                    'При загрузке события возникла ошибка: ' +
                                        err.message,
                                    'error',
                                )
                                this.closeLoaderFullPage()
                                return of(EMPTY)
                            }),
                            takeUntil(this.destroy$),
                        )
                        .subscribe()
                } else {
                    this.event = this.$props.event_
                }
            },
            getPlacesForEvent() {
                this.loaderPlaces = true
                this.getPlaces(
                    this.event.id,
                    this.queryBuilder('placeForPageEvent'),
                )
                    .pipe(
                        map((response) => {
                            // console.log(response.data.places)
                            this.event.places_full
                                ? response.data.places.data.forEach((item) => {
                                      this.event.places_full.push(item)
                                  })
                                : (this.event.places_full =
                                      response.data.places.data)
                            response.data.places.next_cursor
                                ? this.setPagePlaceForPageEvent(
                                      response.data.places.next_cursor,
                                  )
                                : null
                            this.loaderPlaces = false
                        }),
                        takeUntil(this.destroy$),
                        catchError((err) => {
                            this.loaderPlaces = false
                            console.log(err)
                            this.showToast(
                                'При загрузке мест возникла ошибка: ' +
                                    err.message,
                                'error',
                            )
                            return of(EMPTY)
                        }),
                    )
                    .subscribe()
            },
            getUserId() {
                return this.user.id
            },
            deleteFiles(file) {
                this.event.files.find((item, index) => {
                    if (item.name == file.name) {
                        let coin = this.filesUpd.find((itm, i) => {
                            if (itm.name == file.name) {
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
                files.forEach((file) => {
                    let reader = new FileReader()
                    reader.readAsDataURL(file)
                    reader.onload = () => {
                        this.event.files.push({
                            link: reader.result,
                            name: file.name,
                            size: file.size,
                            type: file.type,
                        })
                    }
                    this.filesUpd.push(file)
                })
            },
            backButton() {
                router.go(-1)
            },
            setPlace(place) {
                let event = JSON.parse(JSON.stringify(this.event))
                let index = place.index
                let placeOnDel = Object.keys(place).find((key) => {
                    if (key == 'on_delete' && place[key] == true) {
                        return true
                    } else {
                        return false
                    }
                })
                if (placeOnDel) {
                    // Если есть поле on_delete
                    if (place.id) {
                        // Если есть поле id и place существует в БД
                        event.places_full[index].on_delete = true
                        this.placeUpd.push(place)
                    } else {
                        // Если нет поля id и place не существует в БД
                        event.places_full.splice(place.index)
                    }
                } else {
                    // Если поля on_delete нету
                    // this.$helpers.deepMerge(this.event.places_full[index],place)
                    let mergePlace = { ...place }
                    delete mergePlace.seances
                    this.$helpers.DeepMerge.deepMerge(
                        event.places_full[index],
                        JSON.parse(JSON.stringify(mergePlace)),
                    )
                    // Проверяем новое ли это обновление для place или place уже обновлялся и есть в массииве
                    let getIndex = this.placeUpd.findIndex((item) => {
                        if (item.index == index) {
                            return true
                        }
                    })
                    if (getIndex != -1) {
                        // Проверяем есть ли сеансы
                        let seancesOnUpd = Object.keys(place).find((key) => {
                            if (key == 'seances') {
                                return true
                            } else {
                                return false
                            }
                        })
                        if (seancesOnUpd) {
                            // Перебираем массив пришедших сеансов
                            place.seances.forEach((item) => {
                                // Проверяем есть ли поля on_delete в объекте seance и стоит ли у него значение true
                                let seanceOnDel = Object.keys(item).find(
                                    (key) => {
                                        if (
                                            key == 'on_delete' &&
                                            item[key] == true
                                        ) {
                                            return true
                                        } else {
                                            return false
                                        }
                                    },
                                )
                                if (seanceOnDel) {
                                    // Если есть поле on_delete со значением true
                                    if (item.id !== 0 && item.id) {
                                        // Если есть поле id не нулевое, то сеанс есть в бд и его нужно зафиксировать
                                        let oldSeance = this.placeUpd[
                                            getIndex
                                        ].seances.findIndex((i, k) => {
                                            if (i.index == item.index) {
                                                this.placeUpd[getIndex].seances[
                                                    k
                                                ] = JSON.parse(
                                                    JSON.stringify(item),
                                                )
                                                return true
                                            }
                                        })
                                        if (oldSeance != 0 && oldSeance) {
                                            this.placeUpd[
                                                getIndex
                                            ].seances.push(
                                                JSON.parse(
                                                    JSON.stringify(item),
                                                ),
                                            )
                                        }
                                        event.places_full[place.index].seances[
                                            item.index
                                        ].on_delete = true
                                    } else {
                                        let newSeances = []
                                        // Если поле id нулевое, то просто удалить
                                        this.placeUpd[getIndex].seances.map(
                                            (i) => {
                                                if (i.index !== item.index) {
                                                    newSeances.push(
                                                        JSON.parse(
                                                            JSON.stringify(i),
                                                        ),
                                                    )
                                                    return true
                                                }
                                            },
                                        )
                                        place.seances = JSON.parse(
                                            JSON.stringify(newSeances),
                                        )
                                        this.placeUpd[getIndex].seances =
                                            JSON.parse(
                                                JSON.stringify(newSeances),
                                            )
                                        event.places_full[
                                            place.index
                                        ].seances.splice(item.index, 1)
                                    }
                                } else {
                                    // Если нет поля on_delete или его значение false
                                    // Проверяем есть ли сеансы у плэйса на обновлении
                                    let seanceOnUpd = Object.keys(
                                        this.placeUpd[getIndex],
                                    ).find((key) => {
                                        if (key == 'seances') {
                                            return true
                                        } else {
                                            return false
                                        }
                                    })
                                    if (seanceOnUpd) {
                                        // Если сеансы уже есть перебираем массив сеансов которые уже на обновлении
                                        let sean = 0
                                        this.placeUpd[getIndex].seances.forEach(
                                            (i, k) => {
                                                // Если не совпадает индекс то добавляем запоминаем ключь, а остальное в плэйс
                                                if (i.index !== item.index) {
                                                    place.seances.push(
                                                        JSON.parse(
                                                            JSON.stringify(i),
                                                        ),
                                                    )
                                                } else {
                                                    sean = k
                                                }
                                            },
                                        )
                                        if (sean && sean != 0) {
                                            this.placeUpd[getIndex].seances[
                                                sean
                                            ] = JSON.parse(JSON.stringify(item))
                                        } else {
                                            this.placeUpd[
                                                getIndex
                                            ].seances.push(
                                                JSON.parse(
                                                    JSON.stringify(item),
                                                ),
                                            )
                                        }

                                        // this.placeUpd[getIndex].seances = JSON.parse(JSON.stringify(place.seances))
                                    } else {
                                        // Если сеансов ещё нет
                                        this.placeUpd[getIndex].seances = []
                                        this.placeUpd[getIndex].seances.push(
                                            JSON.parse(
                                                JSON.stringify(
                                                    ...place.seances,
                                                ),
                                            ),
                                        )
                                    }
                                    event.places_full[index].seances[
                                        item.index
                                    ] = JSON.parse(JSON.stringify(item))
                                }
                            })
                        }
                        const mergePlaceUpd = JSON.parse(JSON.stringify(place))
                        delete mergePlaceUpd.seances
                        this.$helpers.DeepMerge.deepMerge(
                            this.placeUpd[getIndex],
                            mergePlaceUpd,
                        )
                    } else {
                        // Если нету в массиве, то добавляем
                        let seanceOnUpd = Object.keys(place).find((key) => {
                            if (key == 'seances') {
                                return true
                            } else {
                                return false
                            }
                        })
                        if (seanceOnUpd) {
                            place.seances.forEach((item) => {
                                // Проверяем есть ли поля on_delete в объекте seance и стоит ли у него значение true
                                let seanceOnDel = Object.keys(item).find(
                                    (key) => {
                                        if (
                                            key == 'on_delete' &&
                                            item[key] == true
                                        ) {
                                            return true
                                        } else {
                                            return false
                                        }
                                    },
                                )
                                if (seanceOnDel) {
                                    event.places_full[place.index].seances[
                                        item.index
                                    ].on_delete = true
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
                    index: this.event.places_full.length,
                })
            },
        },
    }
</script>
<style>
    .button-menu {
        position: fixed;
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
        --dp-range-between-dates-background-color: var(
            --dp-hover-color,
            #f3f3f3
        );
        --dp-range-between-dates-text-color: var(
            --dp-hover-text-color,
            #212121
        );
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
        --dp-range-between-dates-background-color: var(
            --dp-hover-color,
            #484848
        );
        --dp-range-between-dates-text-color: var(--dp-hover-text-color, #fff);
        --dp-range-between-border-color: var(--dp-hover-color, #fff);
    }
</style>
