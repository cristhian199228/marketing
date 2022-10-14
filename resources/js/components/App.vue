<template>
  <v-app id="inspire">
    <v-snackbar
      transition="scale-transition"
      top
      :color="snackbar.status"
      v-model="snackbar.show"
      timeout="3500"
    >
      {{ snackbar.message }}
    </v-snackbar>

    <v-main>
      <v-container fluid class="pa-2">
        <router-view></router-view>
      </v-container>
    </v-main>

    <v-overlay :value="isLoading">
      <v-progress-circular
        indeterminate
        size="64"
      ></v-progress-circular>
    </v-overlay>

    <template>
      <v-navigation-drawer v-model="drawer" clipped app>
        <v-list v-if="isAuthenticated" dense>
          <v-list-item-group color="indigo darken-4">
            <v-list-item to="/solicitudes">
              <v-list-item-icon>
                <v-icon>mdi-home</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title
                >Mis solicitudes</v-list-item-title
                >
              </v-list-item-content>
            </v-list-item>

            <v-list-item to="/solicitudesProyecto" v-if="$store.getters['currentUser/proyecto']">
              <v-list-item-icon>
                <v-icon>mdi-domain</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Mi Proyecto</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <v-list-item to="/admin" v-if="$store.getters['currentUser/isAdmin']">
              <v-list-item-icon>
                <v-icon>mdi-tools</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Administrador</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <v-list-item @click="logout">
              <v-list-item-icon>
                <v-icon>mdi-power</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>Salir</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-navigation-drawer>

      <v-app-bar color="#1E286C" dark app clipped-left>
        <v-app-bar-nav-icon
          @click.stop="drawer = !drawer"
        ></v-app-bar-nav-icon>
        <v-toolbar-title>APP MARKETING</v-toolbar-title>
        <v-spacer></v-spacer>
        <template v-if="isAuthenticated">
          <v-menu
            v-model="menu"
            :close-on-content-click="false"
            offset-y
            max-width="400px"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn icon dark v-bind="attrs" v-on="on">
                <v-icon>mdi-account-circle</v-icon>
              </v-btn>
            </template>
            <v-card>
              <v-list>
                <v-list-item>
                  <v-list-item-avatar>
                    <img :src="userPhoto ? userPhoto: require('../assets/default-user-image.png').default" alt="FOTO USUARIO"/>
                  </v-list-item-avatar>
                  <v-list-item-content>
                    <v-list-item-title>{{ fullName }}</v-list-item-title>
                    <v-list-item-subtitle>{{ correo }}</v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
              <v-divider></v-divider>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn dark color="#1E286C" @click="logout">SALIR</v-btn>
              </v-card-actions>
            </v-card>
          </v-menu>
        </template>
        <template v-else>
          <v-btn @click="login" icon>
            <v-icon>mdi-login</v-icon>
          </v-btn>
        </template>
      </v-app-bar>

      <v-footer dark app color="#1E286C">
        &#64;INTERNATIONAL SOS {{ new Date().getFullYear() }}
      </v-footer>
    </template>
  </v-app>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { signIn} from "../msal";

export default {
  name: "App",
  data: () => ({
    drawer: null,
    fav: true,
    menu: false,
    menu2: false,
    selected: [2],
  }),
  computed: {
    ...mapState(["snackbar"]),
    ...mapState("currentUser", ["isAuthenticated", "currentUser", 'isLoading']),
    ...mapState('comentarios',['nroComentarios','comentariosNotificaciones']),
    ...mapGetters("currentUser", ["fullName", "correo"]),
    userPhoto() {
      return null;
    },
  },
  methods: {
    logout(){
      this.$store.dispatch('currentUser/logout')
    },
    login(){
      signIn()
    },
  },
};
</script>
