<template>
    <!-- eslint-disable-next-line vue/no-use-computed-property-like-method -->
    <button
        type="button"
        class="max-w-[8rem] bg-[#6e6e6e] mt-2 rounded-lg text-[#ffffff]"
    >
        Сбросить
    </button>
</template>

<script>
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
        methods: {},
    }
</script>
