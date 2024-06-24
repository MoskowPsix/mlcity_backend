<template>
    <!-- eslint-disable-next-line vue/no-use-computed-property-like-method -->
    <button
        type="button"
        class="max-w-[8rem] bg-[#6e6e6e] mt-2 rounded-lg text-[#ffffff]"
        @click="clearTextInput"
    >
        Сбросить
    </button>
</template>

<script>
    import { useEventFilterStore } from '../../stores/EventFilterStore'
    import { useSightFilterStore } from '../../stores/SightFilterStore'
    import { mapActions } from 'pinia'
    export default {
        name: 'ClearButton',
        props: {
            // eslint-disable-next-line vue/require-prop-types
            input: {
                default() {
                    return {}
                },
            },
        },
        data() {
            return {
                count: 0,
            }
        },
        methods: {
            ...mapActions(useEventFilterStore, ['clearFilters']),
            ...mapActions(useSightFilterStore, ['clearFilters']),
            // eslint-disable-next-line vue/return-in-computed-property
            clearTextInput() {
                // eslint-disable-next-line vue/no-side-effects-in-computed-properties
                this.clearFilters()
            },
        },
    }
</script>
