const path = require('path');
const VueLoadPlugin = require('vue-loader/lib/plugin');
const webpack = require('webpack');
const HTMLPlugin = require('html-webpack-plugin');

const config = {
  mode:'development',
  target:'web',
  entry: {
    login: './src/pages/login/login.js'
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
    new HTMLPlugin({
      template: './src/pages/login/login.html',
      filename: './login.html'
    })
  ]
};

module.exports = config;