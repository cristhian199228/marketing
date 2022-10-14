<template>
  <div>
    <v-menu
      v-model="menu"
      :close-on-content-click="false"
      offset-y
      max-width="400px"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn outlined color="primary" v-bind="attrs" v-on="on">
          <v-icon left>
            mdi-filter-variant
          </v-icon>
          Filtros
        </v-btn>
      </template>
      <v-card>
        <v-card-text>
          <FechaInicio :store="module" />
          <FechaFin :store="module" />
          <FullName :store="module" />
          <Codigo :store="module" />
          <Formato :store="module" />
          <Prioridad :store="module" />
          <Estado :store="module" />
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="$store.commit('encargado/LIMPIAR_FILTROS')" text>LIMPIAR</v-btn>
          <v-btn @keyup.enter="aplicarFiltros" color="primary" @click="aplicarFiltros">APLICAR</v-btn>
        </v-card-actions>
      </v-card>
    </v-menu>
  </div>
</template>

<script>
import FechaInicio from "../filters/FechaInicio";
import FechaFin from "../filters/FechaFin";
import Codigo from "../filters/Codigo";
import Proyecto from "../filters/Proyecto";
import Formato from "../filters/Formato";
import Estado from "../filters/Estado";
import FullName from "../filters/FullName";
import FullNameEncargado from "../filters/FullNameEncargado";
import Prioridad from "../filters/Prioridad";

export default {
  name: "FiltersEncargado",
  components: {FullNameEncargado, FullName, Estado, Formato, Proyecto, Codigo, FechaFin, FechaInicio, Prioridad},
  data() {
    return {
      menu: false,
      module: 'encargado'
    }
  },
  methods: {
    aplicarFiltros() {
      this.$store.commit('encargado/SET_FIRST_PAGE')
      this.menu = false
      this.$store.dispatch('encargado/fetchData')
    }
  }
}
</script>
