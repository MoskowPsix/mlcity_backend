<template>
    <div class="main-grid">
        <div
            v-for="event in events"
            v-if="events.length > 0"
            class="card-wrapper"
        >   
            <div class="info-wrapper">
                <div class="info-header">
                    <div class="price"></div>
                    <div class="edit-button"></div>
                </div>
                <div class="info-footer">
                    <div class="info-text-container">
                        <div class="info-text"></div>
                        <div class="info-text"></div>
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
        },
    }
</script>
<style src="./MyEventsGrid.css"></style>
