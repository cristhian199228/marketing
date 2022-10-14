<template>
  <div>
    <v-card-title>
      <p class="display-1">COMENTARIOS <span class="font-weight-light">| REQ{{id_solicitud}}</span></p>
    </v-card-title>
    <v-divider class="my-0"></v-divider>
    <v-card-text>
      <div class="d-flex flex-column-reverse">
        <Comentario
          v-for="(c, i) in comentarios"
          :key="i"
          :comentario="c"
        />
      </div>
      <InputComentario class="mt-5" />
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn text color="primary" @click="$router.go(-1)">ATRAS</v-btn>
    </v-card-actions>
  </div>
</template>

<script>
import Comentario from "./Comentario";
import { mapGetters, mapActions } from "vuex";
import InputComentario from "./InputComentario";
import store from '../../store'

export default {
  name: "ComentariosContainer",
  components: {InputComentario, Comentario},
  data() {
    return {
      id_solicitud: this.$route.params.id_solicitud,
    }
  },
  computed: {
    ...mapGetters('comentarios',[
      'comentarios'
    ]),
  },
  methods: {
    ...mapActions('comentarios',['fetchData']),
  },
  beforeRouteEnter(to, from ,next) {
    store.dispatch('comentarios/connectChannel', to.params.id_solicitud)
    next()
  },
  beforeRouteLeave(to, from, next) {
    store.dispatch('comentarios/disconnectChannel', from.params.id_solicitud)
    next()
  },
}
</script>

<style scoped>

</style>