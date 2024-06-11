<template>
    <div
        v-if="events.length"
        class="main-grid"
    >
        <div
            v-for="event in events"
            :key="event.id"
            class="card-wrapper"
        >
            <router-link
                :to="{
                    name: 'event-edit',
                    params: { id: event.id, state: 'state' },
                }"
            >
                <div
                    class="edit-button"
                    @click="alert(1)"
                >
                    <img
                        src="/storage/app/public/icons/edit_pen.svg"
                        alt=""
                    />
                </div>
            </router-link>

            <div class="info-wrapper">
                <div class="info-header">
                    <div class="price"> {{ event.price[0].cost_rub }} ₽ </div>
                </div>
                <div class="info-footer">
                    <div class="info-text-container">
                        <div
                            v-if="
                                formatDate(event.date_start) !==
                                formatDate(event.date_end)
                            "
                            class="info-text"
                        >
                            {{
                                // eslint-disable-next-line vue/no-deprecated-filter
                                formatDate(event.date_start)
                            }}
                            -
                            {{
                                // eslint-disable-next-line vue/no-deprecated-filter
                                formatDate(event.date_end)
                            }}
                        </div>
                        <div
                            v-if="
                                formatDate(event.date_start) ==
                                formatDate(event.date_end)
                            "
                            class="info-text"
                        >
                            {{
                                // eslint-disable-next-line vue/no-deprecated-filter
                                formatDate(event.date_start)
                            }}
                        </div>
                    </div>
                </div>
            </div>

            <router-link :to="{ name: 'event', params: { id: event.id } }">
                <div
                    v-if="event.files.length > 0"
                    class="card"
                    :style="{
                        'background-image': `url(${event.files[0].link})`,
                    }"
                >
                </div>
                <div
                    v-if="event.files.length == 0"
                    class="card"
                    :style="{
                        'background-image': `url(/storage/images/nophoto.jpg)`,
                    }"
                    style="background-size: 110%"
                >
                </div>

                {{ event.name }}
            </router-link>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    import 'moment/dist/locale/ru'

    export default {
        name: 'MyEventsGrid',
        props: {
            events: {
                type: Object,
                default() {
                    return {}
                },
            },
        },
        setup() {},
        data() {
            return {
                cards: [1, 2, 3],
            }
        },
        mounted() {
            this.firstLog()
        },
        methods: {
            firstLog() {
                setTimeout(() => {
                    console.log(this.events[0])
                }, 5000)
            },

            formatDate(date) {
                date = moment(date)
                date.locale('ru')
                // console.log(date.locale())
                return date.format('D MMM')
            },
        },
    }
</script>
<style src="./MyEventsGrid.css"></style>
