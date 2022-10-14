<template>
  <v-dialog v-if="solicitud && solicitud.solicitante" v-model="showDialog" persistent max-width="600px" scrollable>
    <v-card>
      <v-card-title>DETALLES SOLICITUD {{ solicitud.id_solicitud }}</v-card-title>
      <v-divider></v-divider>
      <v-card-text style="height: 600px;">
        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title>Solicitante</v-list-item-title>
            <p class="font-weight-light">{{ this.solicitud.solicitante.full_name}}</p>
          </v-list-item-content>
        </v-list-item>

        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title>Proyecto</v-list-item-title>
            <p class="font-weight-light">{{ this.solicitud.proyecto.nombre}}</p>
          </v-list-item-content>
        </v-list-item>

        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title>Objetivo</v-list-item-title>
            <p class="font-weight-light">{{ this.solicitud.objetivo}}</p>
          </v-list-item-content>
        </v-list-item>

        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title>Descripcion</v-list-item-title>
            <p class="font-weight-light">{{ this.solicitud.descripcion}}</p>
          </v-list-item-content>
        </v-list-item>

        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title>Contenido</v-list-item-title>
            <p class="font-weight-light">{{ this.solicitud.contenido}}</p>
          </v-list-item-content>
        </v-list-item>

        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title>Medidas</v-list-item-title>
            <p class="font-weight-light">{{  `${this.solicitud.ancho}x${this.solicitud.altura} ${this.solicitud.unidad_medida}` }}</p>
          </v-list-item-content>
        </v-list-item>

        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title>Formato</v-list-item-title>
            <p class="font-weight-light">{{ this.solicitud.formato}}</p>
          </v-list-item-content>
        </v-list-item>

      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" text @click="close">
          CERRAR
          <v-icon right>mdi-close</v-icon>
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "SolicitudDetalle",
  props: {
    showDialog: {
      type: Boolean,
      default: false
    },
    solicitud: Object,
  },
  methods: {
    close() {
      this.$emit('close-dialog')
    }
  },

}
</script>

<style scoped>

</style>