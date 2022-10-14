import { signOut } from "../../msal";

const state = {
    currentUser: null,
    isAuthenticated: false,
    isLoading: false
};

const getters = {
    fullName: state => state.currentUser.full_name,
    idUsuario: state => state.currentUser.id,
    correo: state => `${state.currentUser.email}`,
    isAdmin: state => state.currentUser.is_admin,
    proyecto: state => state.currentUser.proyecto ? state.currentUser.proyecto.nombre : null
};

const mutations = {
    SET_USER(state, user) {
        state.currentUser = user;
        state.isAuthenticated = Boolean(user);
    },
    SET_LOADING(state, loading) {
        state.isLoading = loading;
    }
};

const actions = {
    login({ commit }, token) {
        commit("SET_LOADING", true);
        axios.post("/api/login", { token })
            .then(res => {
                commit('SHOW_SUCCESS_SNACKBAR', "Autenticado correctamente", {root: true});
                commit("SET_LOADING", false);
                commit("SET_USER", res.data);
                window.localStorage.setItem('marketing-user', JSON.stringify(res.data))
                window.axios.defaults.headers.common['Authorization'] = `Bearer ${res.data.token}`
            })
            .catch(err => {
                commit('SHOW_ERROR_SNACKBAR', "Ocurrió un error, por favor intente nuevamente", {root: true});
                commit("SET_LOADING", false);
                commit("SET_USER", null);
            });
    },
    logout({}) {
        window.localStorage.removeItem('marketing-user')
        axios.post('api/logout').then(res => {
            signOut()
        }).catch(e => {
            console.error(e)
        })
    }
};

export default {
    state,
    getters,
    mutations,
    actions,
    namespaced: true
};
