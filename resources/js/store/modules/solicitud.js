import moment from "moment";
import axios from "axios";

const state = {
  data: {},
  filters: {
    fecha_inicio: moment().format('YYYY-MM-DD'),
    fecha_fin: moment().format('YYYY-MM-DD'),
    codigo: null,
    proyecto: null,
    formato: null,
    estado: null,
    prioridad: null,
  },
  tableOptions: {},
  loading: false,
  estados: [],
  prioridades: [],
  proyectos: [],
}

const getters = {
  solicitudes: state => state.data.data,
  totalItems: state => state.data.total,
  isLoading: state => state.loading,
}

const mutations = {
  SET_DATA(state, data) {
    state.data = data
  },
  SET_LOADING(state, isLoading) {
    state.loading = isLoading
  },
  SET_TABLE_OPTIONS(state, options) {
    state.tableOptions = options
  },
  SET_FILTRO_FECHA_INICIO(state, fecha) {
    state.filters.fecha_inicio = fecha
  },
  SET_FILTRO_FECHA_FIN(state, fecha) {
    state.filters.fecha_fin = fecha
  },
  SET_FILTRO_CODIGO(state, data) {
    state.filters.codigo = data
  },
  SET_FILTRO_PROYECTO(state, data) {
    state.filters.proyecto = data
  },
  SET_FILTRO_FORMATO(state, data) {
    state.filters.formato = data
  },
  SET_FILTRO_ESTADO(state, data) {
    state.filters.estado = data
  },
  SET_FILTRO_PRIORIDAD(state, data) {
    state.filters.prioridad = data
  },
  SET_PRIORIDADES(state, data) {
    state.prioridades = data
  },
  SET_PROYECTOS(state, data) {
    state.proyectos = data
  },
  SET_ESTADOS(state, data) {
    state.estados = data
  },
  LIMPIAR_FILTROS(state) {
    for (const key in state.filters) {
      state.filters[key] = null
    }
  },
  SET_FIRST_PAGE(state) {
    state.tableOptions.page = 1
  }
}

const actions = {
  async store({commit}, data) {
    try {
      const res = await axios.post('/api/solicitud', data)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message, {root: true })
    } catch (e) {
      throw Error(e.response.data.message)
    }
  },
  async saveAdjuntos({commit}, {photos, id_solicitud}) {
    const config = {
      headers: { "content-type": "multipart/form-data" },
    };
    const formData = new FormData();
    for (let i = 0; i < photos.length; i++) {
      formData.append('photos[]', photos[i])
    }
    formData.append('id_solicitud', id_solicitud)
    try {
      const res = await axios.post(`/api/solicitud_adjunto`, formData, config)
      return await res.data.message
    } catch (e) {
      throw Error(await e.response.data.message)
    }
  },
  async showSolicitud({commit}, idSolicitud) {
    try {
      const res = await axios.get(`/api/solicitud/${idSolicitud}`)
      return res.data
    } catch (e) {
      throw Error(await e.response.data.message)
    }
  },
  async update({commit, dispatch}, data) {
    try {
      const res = await axios.put(`/api/solicitud/${data.id_solicitud}`, data)
      return await commit('SHOW_SUCCESS_SNACKBAR', await res.data.message, {root: true })
    } catch (e) {
      throw Error(await e.response.data.message)
    }
  },
  async delete({commit, dispatch}, idSolicitud) {
    try {
      const res = await axios.delete(`/api/solicitud/${idSolicitud}`)
      commit('SHOW_SUCCESS_SNACKBAR', await res.data.message, {root: true })
      dispatch('fetchData')
    } catch (e) {
      commit('SHOW_ERROR_SNACKBAR', await e.response.data.message, {root: true })
    }
  },
  async fetchData({commit}) {
    const config = {
      params: {
        ...state.filters,
        ...state.tableOptions,
      }
    }
    commit('SET_LOADING', true);
    try {
      const res = await axios.get(`/api/usuario/solicitud`, config)
      commit('SET_DATA', await res.data)
      commit('SET_LOADING', false);
    } catch (e) {
      commit('SET_LOADING', false);
    }
  },
  async fetchPrioridades({commit}) {
    try {
      const { data } = await axios.get('/api/prioridades')
      commit('SET_PRIORIDADES', data)
    } catch (e) {
    }
  },
  async fetchProyectos({commit}) {
    try {
      const { data } = await axios.get('/api/proyectos')
      commit('SET_PROYECTOS', data)
    } catch (e) {
    }
  },
  async fetchEstados({commit}) {
    try {
      const { data } = await axios.get('/api/estados')
      commit('SET_ESTADOS', data)
    } catch (e) {
    }
  }
}

export default {
  state,
  getters,
  mutations,
  actions,
  namespaced: true
}