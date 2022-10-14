<template>
  <v-textarea
    v-model="form.mensaje"
    label="Nuevo comentario"
    filled
    clearable
    counter
    rows="2"
    auto-grow
    append-icon="mdi-send"
    @click:append="enviarComentario"
    @keyup.enter="enviarComentario"
    :disabled="isSending"
  ></v-textarea>
</template>

<script>
export default {
  name: "InputComentario",
  data() {
    return {
      form: {
        mensaje: null,
        id_solicitud: this.$route.params.id_solicitud
      },
      isSending: false
    }
  },
  methods: {
    enviarComentario() {
      if (this.form.mensaje) {
        this.isSending = true
        this.$store.dispatch('comentarios/store', this.form)
          .then(() =>{
            this.form.mensaje = ''
            this.isSending = false
          })
          .catch(e => {
            this.$store.commit('SHOW_ERROR_SNACKBAR', e.message)
            this.isSending = false
          })
      }
    }
  }
}
</script>
