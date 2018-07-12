const webpack = require('webpack');
const VueLoadPlugin = require('vue-loader/lib/plugin');
const path = require('path');
const HTMLPlugin = require('html-webpack-plugin');
const miniCSS = require('mini-css-extract-plugin');
const isDev = process.env.NODE_ENV === 'development';

const config = {
  mode: isDev ? 'development' : 'production',
  target: 'web',
  entry: {
    home: './src/pages/home/home.js',
    designer: './src/pages/designer/designer.js',
    about: './src/pages/about/about.js',
    detail: './src/pages/detail/detail.js',
    login: './src/pages/login/login.js',
    panel: './src/pages/panel/panel.js'
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
          miniCSS.loader,
          'css-loader'
        ]
      },
      {
        test: /\.less$/,
        use: [
          miniCSS.loader,
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
      },
      {
        test: /\.js$/,
        use: {
          loader: 'babel-loader'
        },
        exclude: path.resolve(__dirname,'node_modules/')
      }
    ]
  },
  plugins: [
    new VueLoadPlugin(),
    new miniCSS({
      filename: "css/[name].css",
      allChunks: true
    }),
    new HTMLPlugin({
      template: './src/pages/home/home.html',
      filename: './home.html',
      chunks: ['lib','home']
    }),
    new HTMLPlugin({
      template: './src/pages/about/about.html',
      filename: './about.html',
      chunks: ['lib','about']
    }),
    new HTMLPlugin({
      template: './src/pages/login/login.html',
      filename: './login.html',
      chunks: ['lib','login']
    }),
    new HTMLPlugin({
      template: './src/pages/detail/detail.html',
      filename: './detail.html',
      chunks: ['lib','detail']
    }),
    new HTMLPlugin({
      template: './src/pages/designer/designer.html',
      filename: './designer.html',
      chunks: ['lib','designer']
    }),
    new HTMLPlugin({
      template: './src/pages/panel/panel.html',
      filename: './panel.html',
      chunks: ['lib','panel']
    })
  ],
  optimization: {
    splitChunks: {
      cacheGroups: {
        lib: {
          name: 'lib',
          filename: 'js/[name].js',
          minChunks: 2,
          chunks: 'initial'
        }
      }
    }
  }
};

module.exports = config;