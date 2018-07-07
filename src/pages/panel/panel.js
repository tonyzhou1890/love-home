import axios from "axios";
import css from "../../css/base.less";
import css2 from "../../source/font/iconfont.css";
import Vue from "vue/dist/vue.js";
import Router from "vue-router";
import Vuex from "vuex";

import base from "../../js/base.js";
import vuebody from "../../components/panelBody.vue";

Vue.use(Router);
Vue.use(Vuex);
Vue.use(base);

new Vue({
  el:'#app',
  components:{
    vuebody
  }
});