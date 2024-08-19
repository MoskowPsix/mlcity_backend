<template>
    <div class="h-3/4 w-3/4 bg-white rounded-lg">
        <form class="p-4">
            <div class="space-x-4 p-2">
                <div
                    class="border-gray-500 border-b rounded p-2 flex items-center"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
                        />
                    </svg>

                    <input
                        v-model="searchText"
                        class="ml-2 focus:outline-none w-full"
                        placeholder="Поиск типов"
                        @input="searchType"
                    />
                </div>
            </div>

            <div>
                <p class="text-sm ml-2 text-gray-400"
                    >Текст поиска: {{ searchText }}</p
                >
            </div>
        </form>
    </div>
</template>
<script>
    import { useTypeStore } from '../../stores/TypeStore'
    import { mapActions } from 'pinia'
    import { catchError, retry, delay } from 'rxjs/operators'

    export default {
        name: 'SearchType',
        data() {
            return {
                searchText: '',
                timeout: null,
            }
        },
        methods: {
            ...mapActions(useTypeStore, ['getTypeByText', 'getTypes']),
            searchType() {
                // console.log(this.timeout)
                if (this.timeout) {
                    clearTimeout(this.timeout)
                }
                this.timeout = setTimeout(() => {
                    this.getType()
                }, 1000)
            },

            getType() {
                let param = {
                    name: this.searchText,
                }
                // console.log(params)
                this.getTypeByText(param, 'Sight')
                    .pipe(
                        retry(3),
                        delay(200),
                        catchError((error) => {
                            console.log(error)
                        }),
                    )
                    .subscribe()
                // this.getTypes().subscribe(response => console.log(response))
            },
        },
    }
</script>
<style>
    input:focus {
        border: none;
    }
</style>
