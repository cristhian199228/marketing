<template>
  <div>
    <viewer :images="images" @inited="inited" class="viewer" ref="viewer">
      <img v-for="(src,i) in images" :src="src" :key="i" class="image"  :alt="`Imagen${i}`"/>
    </viewer>
    <p class="display-1 pt-5 text-center">SOLICITUDES <span class="font-weight-lighter">| {{ $store.getters["currentUser/proyecto"] }}</span></p>
    <v-divider></v-divider>
    <v-data-table
      :headers="headers"
      :items="solicitudes"
      :loading="isLoading"
      :options.sync="options"
      :server-items-length="totalItems"
      :footer-props="{ 'items-per-page-options': [5, 10, 15, 30, 50]}"
      multi-sort
      single-expand
      show-expand
      class="elevation-1"
    >
      <template v-slot:top>
        <FiltersEncargado />
      </template>

      <template v-slot:item.created_at="{item}">
        {{ item.fecha }}
      </template>

      <template v-slot:item.solicitante="{item}">
        {{ item.solicitante ? item.solicitante.full_name : ''  }}
      </template>

      <template v-slot:item.id_solicitud="{item}">
        REQ{{ item.id_solicitud }}
      </template>

      <template v-slot:item.medidas="{item}">
        <template v-if="item.altura && item.ancho">
          {{ `${item.ancho}x${item.altura} ${item.unidad_medida}` }}
        </template>
      </template>

      <template v-slot:item.path="{item}">
        <template v-if="item.path">
          <v-btn small icon @click="verFotos(item.path)"><v-icon small>mdi-eye</v-icon></v-btn>
        </template>
      </template>

      <template v-slot:item.adjuntos="{item}">
        <v-btn v-if="item.adjuntos.length > 0" @click="verAdjuntos(item.adjuntos)" small icon>
          <v-icon small>mdi-eye</v-icon>
        </v-btn>
      </template>

      <template v-slot:item.id_estado="{item}">
        <v-chip dark label :color="item.estado.color" small>
          {{ item.estado.nombre }}
        </v-chip>
      </template>

      <template v-slot:expanded-item="{ headers, item }">
        <td :colspan="headers.length">
          <div>
            <p class="subtitle-1">Descripcion</p>
            <p>{{ item.descripcion }}</p>
          </div>
          <div>
            <p class="subtitle-1">Contenido</p>
            <p>{{ item.contenido }}</p>
          </div>
        </td>
      </template>

      <template v-slot:item.fecha_entrega="{item}">
        {{ item.fecha_entrega ? moment(item.fecha_entrega).format('DD/MM/YYYY') : ''}}
      </template>

      <template v-slot:item.id_prioridad="{ item }">
        <v-chip v-if="item.prioridad" small label :color="item.prioridad.color">
          {{ item.prioridad.nombre }}
        </v-chip>
      </template>

    </v-data-table>
  </div>
</template>

<script>

import {mapGetters} from "vuex";
import moment from "moment";
import FiltersEncargado from "./FiltersEncargado";

export default {
  name: "SolicitudesProyecto",
  components: {FiltersEncargado},
  data() {
    return {
      headers: [
        {
          text: 'N°',
          align: 'start',
          sortable: false,
          value: 'contador',
        },
        { text: 'Fecha', value: 'created_at', sortable: true },
        { text: 'Solicitante', value: 'solicitante', sortable: false },
        { text: 'Codigo', value: 'id_solicitud', sortable: true },
        { text: 'Objetivo', value: 'objetivo', sortable: true },
        { text: 'Medidas', value: 'medidas', sortable: true },
        { text: 'Formato', value: 'formato', sortable: true },
        { text: 'Imagen Referencial', value: 'path', sortable: false },
        { text: 'Adjuntos', value: 'adjuntos', sortable: false },
        { text: 'Fecha de Entrega', value: 'fecha_entrega', sortable: true},
        { text: 'Prioridad', value: 'id_prioridad', sortable: true},
        { text: 'Estado', value: 'id_estado', sortable: true },
      ],
      images: [],
      menu: false,
      moment,
    }
  },
  methods: {
    inited (viewer) {
      this.$viewer = viewer
    },
    verFotos(path){
      this.images = [];
      this.images.push(`/api/solicitud/image/${path}`)
      this.$viewer.show();
    },
    verAdjuntos(adjuntos) {
      this.images = [];
      adjuntos.forEach(adjunto => this.images.push(`/api/solicitud/image/${adjunto.path}`))
      this.$viewer.show();
    },
  },
  computed: {
    ...mapGetters('encargado', [
      'solicitudes',
      'isLoading',
      'totalItems'
    ]),
    options: {
      get() {
        return this.$store.state.encargado.tableOptions
      },
      set(options) {
        this.$store.commit('encargado/SET_TABLE_OPTIONS', options)
      }
    }
  },
  created() {
    this.$store.dispatch('encargado/fetchData')
  },
  watch: {
    options() {
      this.$store.dispatch('encargado/fetchData')
    }
  }
}
</script>

<style>
.image{
  display: none;
}
</style>-->
