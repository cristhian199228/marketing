import moment from "moment";

const state = {
  data: {},
  filters: {
    fecha_inicio: moment().format('YYYY-MM-DD'),
    fecha_fin: moment().format('YYYY-MM-DD'),
    codigo: null,
    proyecto: null,
    formato: null,
    estado: null,
    solicitante: null,
    prioridad: null,
  },
  tableOptions: {},
  loading: false,
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
  SET_FILTRO_SOLICITANTE(state, data) {
    state.filters.solicitante = data
  },
  SET_FILTRO_ENCARGADO(state, data) {
    state.filters.encargado = data
  },
  SET_FILTRO_PRIORIDAD(state, data) {
    state.filters.prioridad = data
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
  async fetchData({commit}) {
    const config = {
      params: {
        ...state.filters,
        ...state.tableOptions,
      }
    }
    commit('SET_LOADING', true);
    try {
      const res = await axios.get(`/api/proyecto/solicitudes`, config)
      commit('SET_DATA', await res.data)
      commit('SET_LOADING', false);
    } catch (e) {
      commit('SET_LOADING', false);
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