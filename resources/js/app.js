import VueViewer from "v-viewer";
import "viewerjs/dist/viewer.css";
import VueTimeago from 'vue-timeago'

require("./bootstrap");
import vuetify from "../plugins/vuetify";
import router from "./router";
import store from "./store";

import { extend, ValidationProvider, ValidationObserver, setInteractionMode, localize} from "vee-validate";
import {required, integer, min, max, required_if, email, alpha,
    alpha_spaces, double, length, between} from "vee-validate/dist/rules";
import es from 'vee-validate/dist/locale/es.json'

setInteractionMode("eager");
extend("required", {
    ...required,
});
extend("min", {
    ...min,
});

extend("email", {
    ...email,
});

extend("max", {
    ...max,
});

extend("integer", {
    ...integer,
});

extend("required_if", {
    ...required_if,
});

extend("alpha", {
    ...alpha,
});
extend("alpha_spaces", {
    ...alpha_spaces,
});

extend("double", {
    ...double,
});

extend("length", {
    ...length,
});

extend("between", {
    ...between,
});

localize('es')
localize({
    es
})

window.Vue = require("vue").default;

Vue.use(VueTimeago, {
    name: 'Timeago',
    locale: 'en',
    locales: {
        'es': require('date-fns/locale/es')
    }
})

Vue.component("app-container", require("./components/App").default);
Vue.component("viewer", VueViewer);
Vue.component('validation-observer', ValidationObserver)
Vue.component('validation-provider', ValidationProvider)

Vue.use(VueViewer);


if (window.localStorage.hasOwnProperty('marketing-user')) {
    const user = JSON.parse(window.localStorage.getItem('marketing-user'))
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${user.token}`
    store.commit('currentUser/SET_USER', user)
}

const app = new Vue({
    el: "#app",
    vuetify,
    router,
    store
});
