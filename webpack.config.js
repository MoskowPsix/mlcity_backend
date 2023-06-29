'use strict'
const { VueLoaderPlugin } = require('vue-loader')
const BundleAnalyzerPlugin = require("webpack-bundle-analyzer").BundleAnalyzerPlugin
module.exports = {
    mode: 'production',
    entry: [
      './resources/js/app.js'
    ],
    module: {
      rules: [
        {
          test: /\.vue$/,
          use: 'vue-loader',
        },
        {
          test: /\.css$/i,
          use: ['style-loader', 'css-loader'], 
        },
        {
          test: /\.(png|svg|jpg|jpeg|gif)$/i,
          type: 'asset/resource',
        },
      ]
    },
    plugins: [
        new BundleAnalyzerPlugin(),
        new VueLoaderPlugin()
    ],
  }