import Vue from 'vue';
import Vuex from 'vuex';
import * as mutations from './mutations';
import * as actions from './actions';
import * as getters from './getters';

// Modules
import media from './modules/media';

Vue.use(Vuex);

const state = {
  api: {
    fetching: false,
    fetchStatus: null,
  },
};

export default new Vuex.Store({
  modules: {
    media,
  },
  mutations,
  getters,
  actions,
  state,
});
