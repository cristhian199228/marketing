<template>
  <v-menu
    v-model="menuFechaInicio"
    :close-on-content-click="false"
    :nudge-right="40"
    transition="scale-transition"
    offset-y
    min-width="auto"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-text-field
        v-model="fechaInicio"
        label="Fecha inicial"
        readonly
        v-bind="attrs"
        v-on="on"
      ></v-text-field>
    </template>
    <v-date-picker
      v-model="fechaInicio"
      @input="menuFechaInicio = false"
      locale="es"
      :max="moment().format('YYYY-MM-DD')"
    ></v-date-picker>
  </v-menu>
</template>

<script>
import moment from "moment";
export default {
  name: "FechaInicio",
  props: ['store'],
  data() {
    return {
      moment,
      menuFechaInicio: false,
    }
  },
  computed: {
    fechaInicio: {
      get() {
        return this.$store.state[this.store].filters.fecha_inicio
      },
      set(date) {
        this.$store.commit(`${this.store}/SET_FILTRO_FECHA_INICIO`, date)
      }
    },
  }
}
</script>