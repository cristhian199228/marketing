<template>
  <validation-observer ref="observer" v-slot="{ invalid }">
    <v-dialog v-model="showDialog" max-width="450px" persistent>
      <v-card>
        <v-card-title>ATENDER REQ{{ solicitud.id_solicitud }}</v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <p>Por favor llene los siguientes campos para atender esta solicitud</p>
          <validation-provider v-slot="{ errors }" name="prioridad" rules="required">
            <v-select
              label="Prioridad"
              dense
              v-model="solicitud.id_prioridad"
              :items="$store.state.solicitud.prioridades"
              item-value="id"
              item-text="nombre"
              :error-messages="errors"
            ></v-select>
          </validation-provider>
          <v-menu
            v-model="menu"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
<!--              <validation-provider v-slot="{ errors }" name="fecha de entrega" rules="required">-->
                <v-text-field
                  v-model="solicitud.fecha_entrega"
                  label="Fecha de entrega"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
<!--              </validation-provider>-->
            </template>
            <v-date-picker
              v-model="solicitud.fecha_entrega"
              @input="menu = false"
              locale="es"
              :min="moment().format('YYYY-MM-DD')"
            ></v-date-picker>
          </v-menu>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="close" text>CANCELAR</v-btn>
          <v-btn color="primary" :disabled="invalid" @click="guardar">GUARDAR</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </validation-observer>
</template>

<script>
import moment from "moment";
export default {
  name: "DialogAtenderSolicitud",
  props: {
    showDialog: {
      type: Boolean,
      default: false
    },
    solicitud: Object,
  },
  data() {
    return {
      moment,
      menu: false
    }
  },
  methods: {
    close() {
      this.$emit('close-dialog')
      this.$refs.observer.reset()
    },
    guardar() {
      this.solicitud.id_estado = 3
      this.solicitud.id_encargado = this.$store.state.currentUser.currentUser.id
      this.$store.dispatch('solicitud/update', this.solicitud)
      .then(res => {
        this.close()
        this.$store.dispatch('admin/fetchData')
      })
      .catch(err => {
        this.$store.commit('SHOW_ERROR_SNACKBAR', err)
      })
    }
  }
}
</script>

<style scoped>

</style>