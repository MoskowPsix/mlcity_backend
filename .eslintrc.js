module.exports = {
    env: {
        node: true,
    },
    extends: [
        'eslint:recommended',
        'plugin:vue/vue3-recommended',
        'eslint-config-prettier',
        'prettier',
    ],
    plugins: ['prettier', 'eslint-plugin-prettier'],
    rules: {
        // prettier: ['error'],
        'prettier/prettier': 'error',
        // override/add rules settings here, such as:
        // 'vue/no-unused-vars': 'error'
    },
}
