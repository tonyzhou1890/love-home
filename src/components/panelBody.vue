<template>
  <div class="panel ova">
    <div class="header">
      <div class="logo fl">
        <img :src="logo">
      </div>
      
      <div class="user fr">
        <i class="iconfont cp">&#xe609;</i>
        <i class="iconfont cp">&#xe659;</i>
      </div>
    </div>
    <div class="sidebar fl ova">
      <router-link to="/setting">
        <p class="tac cp">设置</p>
      </router-link>
      <router-link to="/upload">
        <p class="tac cp">上传案例</p>
      </router-link>
    </div>
    <div class="content fl">
      <keep-alive>
        <router-view v-if="has_token"></router-view>
      </keep-alive>
    </div>
  </div>
</template>

<script>
import Router from "vue-router";
import setting from "./setting.vue";
import upload from "./uploadCase.vue";
import axios from "axios";
import store from "../pages/panel/store.js";

const routes = [
  {
    path: '/',
    redirect: '/setting'
  },
  {
    path: '/setting',
    component: setting,
    // props: {
    //   origin_info: false
    // }
    // query: {
    //   useful: info
    // }
  },
  {
    path: '/upload',
    component: upload
  }
];

const router = new Router({
  routes
});

export default {
  data(){
    return {
      logo: './images/website/logo.svg',
      info: '',
      has_token: true
    }
  },
  router,
  store,
  methods: {
    clear(){
      console.log(this.$router);
      document.body.innerHTML = `
      <p>请<a href="./login.html#/login">登录</a>或<a href="./login.html#/register">注册</a></p>
      `;
      // this.$destroy();
    }
  },
  created(){
    if(!window.localStorage.lh_token){
      console.log(this.$router);
      this.clear();
      return;
    }
    let token = encodeURIComponent(window.localStorage.lh_token);
    axios.get('./?token='+token)
      .then(response => {
        if(response.data.response === 'error'){
          localStorage.removeItem('lh_token');
          this.clear();
          return;
        }else if(response.data.response === 'success'){
          if(response.data.token){
            localStorage.setItem('lh_token',response.data.token);
          }
          axios.get('./?setting='+token)
            .then(response => {
              // console.log(response.data);
              console.log(this);
              console.log(this.$router);
              this.info = response.data;
              // this.$router.options.routes[1].component.props.origin_info = response.data;
              this.$store.commit('store_origin_info',this.info);
            })
        }
      });
  },
  // mounted(){
  //   if(!window.localStorage.lh_token){
  //     this.clear();
  //     return;
  //   }
  // }
};


</script>

<style lang="less" scoped>
@darkGray: #666;
@red: #db3939;
@white: white;
@pink: #eee;

.panel {
  width: 1200px;
  margin: auto;
  background-color: @darkGray;
  .header {
    height: 50px;
    background-color: @darkGray;
    .logo {
      width: 65px;
      height: 50px;
      line-height: 50px;
      img {
        width: 100%;
        height: 100%;
        margin-left: 10px;
      }
    }
    .user {
      width: 125px;
      height: 50px;
      line-height: 50px;
      i {
        font-size: 30px;
        margin: 0 12px;
        color: @white;
      }
    }
  }
  .sidebar {
    min-height: 1000px;
    background-color: @darkGray;
    p {
      width: 200px;
      height: 60px;
      font-size: 20px;
      line-height: 40px;
      box-sizing: border-box;
      border: 10px solid @darkGray;
      border-right: 0px;
      color: @white;
      
    }
    .router-link-active p {
      background-color: @pink;
      color: @darkGray;
    }
  }
  .content {
    width: 1000px;
    min-height: 1000px;
    background-color: @pink;
  }
}
</style>