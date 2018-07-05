import axios from "axios";
import css from "../../css/base.less";
import Vue from "vue/dist/vue.js";
import Router from "vue-router";
import vuebody from "../../components/loginbody.vue";
import vuefooter from "../../components/footer.vue";

import base from "../../js/base.js";

Vue.use(base);

Vue.use(Router);

new Vue({
  el:'#app',
  components: {
    vuebody,
    vuefooter
  }
});