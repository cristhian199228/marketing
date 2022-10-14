import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store'
import Home from "../components/home/Home";
import ContainerUsuario from "../components/usuario/ContainerUsuario";
import SolicitudesUsuario from "../components/usuario/SolicitudesUsuario";
import ContainerAdmin from "../components/admin/ContainerAdmin";
import SolicitudesAdmin from "../components/admin/SolicitudesAdmin";
import GuardarSolicitud from "../components/solicitudes/GuardarSolicitud";
import ComentariosContainer from "../components/comentarios/ComentariosContainer";
import SolicitudesProyecto from "../components/encargado/SolicitudesProyecto";

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: "/solicitudes",
        component: ContainerUsuario,
        children: [
            {path: '', component: SolicitudesUsuario, name: 'solicitud.index'},
            {path: "crear", component: GuardarSolicitud, name: 'solicitud.crear'},
            {path: "editar/:idSolicitud", component: GuardarSolicitud, name: 'solicitud.editar'},
            {path: ':id_solicitud/comentarios', component: ComentariosContainer, name: 'comentarios.index'}
        ],
        meta: {requiresAuth: true}
    },
    {
        path: "/solicitudesProyecto",
        component: SolicitudesProyecto,
        meta: {requiresAuth: true, encargadoProyecto: true}
    },
    {
        path: "/admin",
        component: ContainerAdmin,
        children: [
            {path: "", component: SolicitudesAdmin},
        ],
        meta: {requiresAuth: true, isAdmin: true}
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.state.currentUser.isAuthenticated) {
            next('/')
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
    if (to.matched.some(record => record.meta.isAdmin)) {
        if (store.getters["currentUser/isAdmin"]) {
            next()
        } else {
            next('/')
        }
    } else {
        next() // make sure to always call next()!
    }

    if (to.matched.some(record => record.meta.encargadoProyecto)) {
        if (store.getters["currentUser/proyecto"]) {
            next()
        } else {
            next('/')
        }
    } else {
        next() // make sure to always call next()!
    }
})

export default router;
