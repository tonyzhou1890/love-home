<template>
  <div class="panel ova">
    <div class="header">
      <div class="logo fl">
        <a href="./" target="_blank"><img :src="logo"></a>
      </div>
      
      <div class="user fr">
        <a @click="sorry">
          <i class="iconfont cp">&#xe609;</i>
        </a>
        
        <i class="iconfont cp" @click="logout">&#xe659;</i>
      </div>
    </div>
    <div class="sidebar fl ova">
      <router-link to="/setting">
        <p class="tac cp">设置</p>
      </router-link>
      <router-link to="/upload" v-if="is_designer">
        <p class="tac cp">上传案例</p>
      </router-link>
    </div>
    <div class="content fl">
      <keep-alive>
        <router-view></router-view>
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
      is_designer: false,
      once: true
    }
  },
  router,
  store,
  methods: {
    clear(){

      // console.log(this.$router);
      document.body.innerHTML = `
      <p style="font-size: 20px;text-align:center;padding-top:100px;">请<a href="./login.html#/login" target="_blank" style="color:#db3939;">登录</a>或<a href="./login.html#/register" target="_blank" style="color:#db3939;">注册</a></p>
      `;
      // this.$destroy();
    },
    sorry(){
      alert('该功能暂不可用！');
    },
    logout(){
      let token = encodeURIComponent(window.localStorage.lh_token);
      axios.get('./?logout='+token)
        .then(response => {
          console.log(response.data);
          // window.location.reload();
        });
      window.localStorage.removeItem('lh_token');
      this.clear()
    }
  },
  created(){
    //如果不是设计师却访问上传案例页面，就跳转到设置页面。
    if(!this.is_designer && '/upload' === this.$route.path && this.once){
      console.log(this.$route.path);
      this.once = false;
      this.$router.replace({path:'/setting'});
    }
    console.log(this.$router);
    window.route = this.$router;
    
    if(!window.localStorage.lh_token){
      // console.log(this.$router);
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
              
              this.info = response.data;
              if(this.info.designer){
                this.is_designer = true;
              }
              this.$store.commit('store_origin_info',this.info);
            })
        }
      });
      
  },
  mounted(){
    
    // if(!window.localStorage.lh_token){
    //   this.clear();
    //   return;
    // }
  }
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
        &:hover {
          color: @red;
        }
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