import Vue from "vue/dist/vue.js";
import css from "../../css/base.less";
import "../../source/font/iconfont.css";

import vueheader from "../../components/header.vue";
import vuebody from "../../components/aboutbody.vue";
import vuefooter from "../../components/footer.vue";


new Vue({
  el:'#app',
  data: {
    aboutdata:{}
  },
  components: {
    vueheader,
    vuebody,
    vuefooter
  },
  created(){
    let el = document.getElementsByTagName('vuebody')[0]
    let data = el.innerHTML;
    this.aboutdata = JSON.parse(data);
    el.style.display = '';
  }
})