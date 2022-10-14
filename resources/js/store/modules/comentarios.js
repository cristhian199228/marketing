import router from "../../router";

const state = {
  data: [],
  nroComentarios: null,
  comentariosNotificaciones: [],
}

const getters = {
  comentarios: state => state.data
}

const mutations = {
  SET_DATA(state, data) {
    state.data = data
  },
  SET_NUMERO_COMENTARIOS(state, data) {
    state.nroComentarios = data
  },
  SET_COMENTARIOS(state, data) {
    state.comentariosNotificaciones = data
  },
}

const actions = {
  async fetchData({commit}, id_solicitud) {
    try {
      const res = await axios.get(`/api/solicitud/${id_solicitud}/comentario`)
      commit('SET_DATA', await res.data)
    } catch (e) {
      await router.push('/')
    }
  },
  async store({commit, dispatch}, data) {
    try {
      const res = await axios.post(`/api/solicitud/${data.id_solicitud}/comentario`, data)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message, {root: true })
      dispatch('fetchData', data.id_solicitud)
    } catch (e) {
      throw new Error(e.response.data.message)
    }
  },
  async nroComentarios({ commit}) {
    try {
      const res = await axios.get(`/api/usuario/nro_comentarios`)
      commit('SET_NUMERO_COMENTARIOS', await res.data)
    } catch (e) {
    }
  },
  async getComentariosNotificaciones({ commit}) {
    commit('SET_COMENTARIOS', [])
    try {
      const res = await axios.get(`/api/usuario/comentarios`)
      commit('SET_COMENTARIOS', await res.data)
    } catch (e) {
    }
  },
  connectChannel({dispatch}, id_solicitud) {
    dispatch('fetchData', id_solicitud)
    Echo.private(`solicitud.${id_solicitud}`)
      .listen('.new.comment', e => {
        dispatch('fetchData', e.comentario.id_solicitud)
      })
  },
  disconnectChannel({}, id_solicitud) {
    Echo.leave(`solicitud.${id_solicitud}`)
  }
}

export default {
  state,
  getters,
  mutations,
  actions,
  namespaced: true
}