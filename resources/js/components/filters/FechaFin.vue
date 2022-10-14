<template>
  <v-menu
    v-model="menuFechaFin"
    :close-on-content-click="false"
    :nudge-right="40"
    transition="scale-transition"
    offset-y
    min-width="auto"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-text-field
        v-model="fechaFin"
        label="Fecha final"
        readonly
        v-bind="attrs"
        v-on="on"
      ></v-text-field>
    </template>
    <v-date-picker
      v-model="fechaFin"
      @input="menuFechaFin = false"
      locale="es"
      :max="moment().format('YYYY-MM-DD')"
    ></v-date-picker>
  </v-menu>
</template>

<script>
import moment from "moment";
export default {
  name: "FechaFin",
  props: ['store'],
  data() {
    return {
      moment,
      menuFechaFin: false,
    }
  },
  computed: {
    fechaFin: {
      get() {
        return this.$store.state[this.store].filters.fecha_fin
      },
      set(date) {
        this.$store.commit(`${this.store}/SET_FILTRO_FECHA_FIN`, date)
      }
    },
  }
}
</script>