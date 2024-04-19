<template>
    <section class="main">
        <button
            class="open-history-button dark:bg-gray-700"
            @click="openDataMenu"
        >
            История
        </button>
        <Transition name="slide-fade">
            <ul
                v-if="openModal"
                class="date-list"
            >
                <li
                    v-for="content in contents.data.history_contents.data"
                    :key="content.id"
                    class="date-list-item"
                >
                    {{ content.created_at }}
                </li>
                <li class="date-list-item"> 12.02.2023 </li>
                <li class="date-list-item"> 12.02.2023 </li>
                <li class="date-list-item"> 12.02.2023 </li>
                <li class="date-list-item"> 12.02.2023 </li>
            </ul>
        </Transition>
    </section>
</template>

<script>
    export default {
        name: 'ModalHistoryContnet',
        props: {
            contents: {
                type: Array,
                default() {
                    return []
                },
            },
        },
        data() {
            return {
                openModal: false,
                historyArray: [],
            }
        },
        mounted() {},
        methods: {
            openDataMenu() {
                this.openModal = !this.openModal
                console.log('1')
            },
        },
    }
</script>

<style>
    .main {
        max-height: 400px;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 50;
        position: absolute;
        margin: auto;
        top: 0;
        bottom: 0;
        right: 0;
        position: absolute;
    }

    .open-history-button {
        margin-right: -2rem;
        transform: rotate(90deg);
        background-color: #6393ff;
        color: #fff;
        height: clamp(3rem, 1.63rem + 1.98vw, 3.5rem);
        width: clamp(6rem, 5.259rem + 3.95vw, 10rem);
        border-radius: 8px;
        backface-visibility: hidden;
        transition: 0.3s;
    }

    .open-history-button:hover {
        transition: 0.3s;
        scale: 1.05;
    }

    .date-list {
        padding: 2rem;
        border-radius: 8px;
        background-color: #fdfdfd;
        width: clamp(12.5rem, 10.579rem + 10.25vw, 16rem);
        height: 80%;
        overflow: auto;
        margin-left: -1rem;
        -webkit-box-shadow: 0px 5px 10px 2px rgba(34, 60, 80, 0.2);
        -moz-box-shadow: 0px 5px 10px 2px rgba(34, 60, 80, 0.2);
        box-shadow: 0px 5px 10px 2px rgba(34, 60, 80, 0.2);
    }

    .date-list-item {
        cursor: pointer;
        margin-top: 10px;
        border-radius: 4px;
        margin-left: auto;
        margin-right: auto;
        max-width: 100%;
        font-size: 1.3rem;
        text-align: center;
        background-color: #6393ff;
        color: #f7f7f7;
        transition: 0.3s;
    }

    .date-list-item:hover {
        transition: 0.3s;
        scale: 1.05;
    }

    .slide-fade-enter-active {
        transition: all 0.3s ease;
    }
    .slide-fade-leave-active {
        transition: 0.3s ease;
    }
</style>
