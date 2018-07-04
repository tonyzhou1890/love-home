<template>
  <div class="stage ova">
    <div class="tab tac ova">
      <router-link to="/login">
        <p class="fl">登录</p>
      </router-link>
      <router-link to="/register">
        <p class="fl">注册</p>
      </router-link>
    </div>
    <router-view></router-view>
  </div>
</template>

<script>
import axios from "axios";
import login from "./login.vue";
import register from "./register.vue";
import Router from "vue-router";

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    component: login
  },
  {
    path: '/register',
    component: register
  }
];

const router = new Router({
  routes
});

export default {
  router
};

</script>

<style lang="less" scoped >
@lightGray: lightGray;
@darkGray: #666;
@pink: #edd9d9;
@white: white;
.stage {
  width: 500px;
  margin: auto;
  margin-top: 140px;
  margin-bottom: 220px;
  box-shadow: 5px 5px 5px @lightGray,-5px -5px 5px @lightGray;
  .tab {
    p {
      width: 50%;
      height: 50px;
      line-height: 50px;
      font-size: 30px;
      color: @darkGray;
      background-color: @pink;

    }
    .router-link-active p {
      background-color: @white;
    }
  }
}
</style>