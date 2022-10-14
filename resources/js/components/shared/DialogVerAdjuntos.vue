<template>
  <v-dialog v-if="solicitud" v-model="showDialog" max-width="700px" persistent :retain-focus="false">
    <v-card>
      <v-card-title>Adjuntos</v-card-title>
      <v-simple-table dense fixed-header>
        <template v-slot:default>
          <thead>
          <tr>
            <th>Archivo</th>
            <th>Acciones</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="file in solicitud.adjuntos" :key="file.id">
            <td>{{ file.path }}</td>
            <td>
              <v-btn v-if="imgExtensions.includes(file.path.split('.').pop())"
                     @click="$emit('ver-photos', file.path)"
                     small icon><v-icon small>mdi-eye</v-icon></v-btn>
              <v-btn small icon @click="download(file.id)"><v-icon small>mdi-download</v-icon></v-btn>
            </td>
          </tr>
          </tbody>
        </template>
      </v-simple-table>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn @click="downloadAll()" text color="primary">
          <v-icon left>mdi-download</v-icon>
          Descargar Todo
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn text color="normal" @click="$emit('close-dialog')">
          <v-icon left>mdi-close</v-icon>
          CERRAR
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: ['solicitud', 'showDialog'],
  data() {
    return {
      imgExtensions: ['jpg','png','jpeg','bmp', 'gif'],
    }
  },
  methods: {
    download(id) {
      window.open(`/api/solicitud_adjunto/descargar/${id}`, '_blank')
    },
    downloadAll() {
      window.open(`/api/solicitud/descargarTodo/${this.solicitud.id_solicitud}`, '_blank')
    }
  }
}
</script>