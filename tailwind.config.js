/** @type {import('tailwindcss').Config} */

const colors = require("tailwindcss/colors")
const BundleAnalyzerPlugin = require("webpack-bundle-analyzer").BundleAnalyzerPlugin

module.exports = {
  plugins: [
      require('flowbite/plugin'),
      { src: '~/plugins/ymapPlugin.js',  mode: 'client' },
      require("tw-elements/dist/plugin.cjs")
  ],
  content: [
    "./resources//*.blade.php",
    "./resources//*.js",
    "./resources/**/*.vue",
    "./node_modules/vue-tailwind-datepicker/**/*.js",
    "./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"
  ],
  theme: {
    extend: {
      colors: {
        "vtd-primary": colors.sky, // Light mode Datepicker color
        "vtd-secondary": colors.gray, // Dark mode Datepicker color
      },
    },
  },
  darkMode: "class",
}
