const webpack = require('webpack');
const VueLoadPlugin = require('vue-loader/lib/plugin');
const path = require('path');
const HMTLPlugin = require('html-webpack-plugin');

const config = {
  mode: 'development',
  target: 'web',
  entry: {
    designer: './src/pages/designer/designer.js'
  },
  output: {
    path: path.resolve(__dirname,'dist'),
    filename: 'js/[name].js'
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader'
      },
      {
        test: /\.css$/,
        use: [
          'style-loader',
          'css-loader'
        ]
      },
      {
        test: /\.less$/,
        use: [
          'style-loader',
          'css-loader',
          'less-loader'
        ]
      },
      {
        test: /iconfont\.(ttf|svg|eot|woff)$/,
        use: [{
          loader: 'file-loader',
          options: {
            name: 'font/[name].[ext]'
          }
        }]
      }
    ]
  },
  plugins: [
    new VueLoadPlugin(),
    new HMTLPlugin({
      template: './src/pages/designer/designer.html',
      filename: './designer.html'
    })
  ]
};

module.exports = config;