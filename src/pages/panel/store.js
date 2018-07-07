import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);

const state = {
  origin_info: null
}

const mutations = {
  store_origin_info(state,info){
    Vue.set(state,'origin_info',info);
    // state.origin_info = info;
  }
}

export default new Vuex.Store({
  state,
  mutations
})