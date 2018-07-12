import "babel-polyfill";

import css from "../../css/base.less";
import css2 from "../../source/font/iconfont.css";
import Vue from "vue/dist/vue.js";
import axios from "axios";
import base from "../../js/base.js";

import vueheader from "../../components/header.vue";
import vuebody from "../../components/detailBody.vue";
import vuefooter from "../../components/footer.vue";

Vue.use(base);

new Vue({
  el:'#app',
  data: {
    response: 'success',
    base_info: {},
    case_info: {},
    designer: {},
    user: {},
    cases: []
  },
  components: {
    vueheader,
    vuebody,
    vuefooter
  },
  created(){
    let el = document.getElementsByTagName('vuebody')[0];
    let data = JSON.parse(el.innerHTML);
    console.log(data);
    this.response = data.response;
    this.base_info = data.base_info;
    if(data.case_info){
      this.case_info = data.case_info;
    }
    if(data.designer_info){
      this.designer = data.designer_info;
    }
    if(data.user_info){
      this.user = data.user_info;
    }
    if(data.related_cases){
      this.cases = data.related_cases;
    }
    el.style.display = '';
  }
})