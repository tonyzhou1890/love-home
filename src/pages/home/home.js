import "babel-polyfill";

import Vue from "vue/dist/vue.js";
import $ from "jquery";
import axios from "axios";
import css from "../../css/base.less";
import css2 from "../../source/font/iconfont.css";
import vueheader from "../../components/header.vue";
import vuebody from "../../components/homebody.vue";
import vuefooter from "../../components/footer.vue";

new Vue({
  el:'#app',
  data: {
    homedata:{}
  },
  components: {
    vueheader,
    vuebody,
    vuefooter
  },
  created(){
    let el = document.getElementsByTagName('vuebody')[0]
    let data = el.innerHTML;
    this.homedata = JSON.parse(data);
    el.style.display = '';
  }
})