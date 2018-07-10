import Vue from "vue/dist/vue.js";
import axios from "axios";
import css from "../../css/base.less";
import css2 from "../../source/font/iconfont.css";
import base from "../../js/base.js";

import vueheader from "../../components/header.vue";
import vuebody from "../../components/designerBody.vue";
import vuefooter from "../../components/footer.vue";

Vue.use(base);

new Vue({
  el:'#app',
  data: {
    response: 'success',
    base_info: {},
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
    if(data.designer_info){
      this.designer = data.designer_info;
    }
    if(data.user_info){
      this.user = data.user_info;
    }
    if(data.cases){
      this.cases = data.cases;
    }
    el.style.display = '';
  }
});