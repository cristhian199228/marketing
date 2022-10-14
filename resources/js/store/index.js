import Vue from 'vue'
import Vuex from 'vuex'
import currentUser from "./modules/currentUser";
import solicitud from "./modules/solicitud";
import admin from './modules/admin'
import comentarios from "./modules/comentarios";
import encargado from "./modules/encargado";

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    currentUser,
    admin,
    solicitud,
    comentarios,
    encargado
  },
  state: {
    snackbar: {
      show: false,
      message: null,
      status: null,
    },
  },
  getters: {

  },
  mutations: {
    SHOW_ERROR_SNACKBAR(state, message){
      state.snackbar.show = true
      state.snackbar.status = 'error'
      state.snackbar.message = message
    },
    SHOW_SUCCESS_SNACKBAR(state, message){
      state.snackbar.show = true
      state.snackbar.status = 'success'
      state.snackbar.message = message
    },
  },
  actions: {}
})