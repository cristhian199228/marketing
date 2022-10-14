<template>
  <v-dialog v-model="showDialog" width="500px" persistent>
    <v-card>
      <v-card-title>SUBIR ARCHIVOS</v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-file-input
          v-model="files"
          placeholder="Suba sus archivos"
          label="Adjuntos"
          multiple
          prepend-icon="mdi-paperclip"
          accept="*"
          ref="photoRef"
        >
          <template v-slot:selection="{ text }">
            <v-chip
              small
              label
              color="primary"
            >
              {{ text }}
            </v-chip>
          </template>
        </v-file-input>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text color="normal" @click="close">CERRAR</v-btn>
        <v-btn :loading="isLoading" color="primary" @click="save">SUBIR</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "GuardarAdjuntosDialog",
  props: {
    showDialog: {
      type: Boolean,
      default: false,
    },
    id_solicitud: {
      type: Number
    }
  },
  data() {
    return {
      files: [],
      isLoading: false,
    }
  },
  methods: {
    save() {
      if (this.files.length > 0){
        this.isLoading = true
        this.$store.dispatch('solicitud/saveAdjuntos', {
          photos: this.files,
          id_solicitud: this.id_solicitud
        }).then(msg => {
          this.$store.dispatch('admin/fetchData')
          this.$store.commit('SHOW_SUCCESS_SNACKBAR', msg)
          this.isLoading = false
          this.$emit('close-dialog')
          this.$refs.photoRef.reset()
        }).catch(err => {
          this.$store.commit('SHOW_ERROR_SNACKBAR', err)
          this.isLoading = false
        })
      } else {
        this.$store.commit('SHOW_ERROR_SNACKBAR', 'Seleccione fotos para subir!')
      }

    },
    close() {
      this.$emit('close-dialog')
    }
  }
}
</script>
