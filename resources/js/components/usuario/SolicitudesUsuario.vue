<template>
  <div>
    <viewer :images="images" @inited="inited" class="viewer" ref="viewer">
      <img v-for="(src,i) in images" :src="src" :key="i" class="image"  :alt="`Imagen${i}`"/>
    </viewer>
    <v-card-title>
      <p class="display-1">MIS SOLICITUDES</p>
      <v-spacer></v-spacer>
      <v-btn color="primary" to="/solicitudes/crear" class="my-auto">NUEVA SOLICITUD</v-btn>
    </v-card-title>
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
      class="mt-5 elevation-1"
    >
      <template v-slot:top>
        <FiltersUsuario />
      </template>

      <template v-slot:item.created_at="{item}">
        {{ item.fecha }}
      </template>

      <template v-slot:item.id_solicitud="{item}">
        REQ{{ item.id_solicitud }}
      </template>

      <template v-slot:item.id_proyecto="{item}">
        {{ item.proyecto.nombre  }} 
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
        <v-btn v-if="item.adjuntos.length > 0" @click="verAdjuntos(item)" small icon>
          <v-icon small>mdi-eye</v-icon>
        </v-btn>
      </template>

      <template v-slot:item.fecha_entrega="{item}">
        {{ item.fecha_entrega ? moment(item.fecha_entrega).format('DD/MM/YYYY') : '' }}
      </template>

      <template v-slot:item.id_prioridad="{ item }">
        <v-chip v-if="item.prioridad" small label :color="item.prioridad.color">
          {{ item.prioridad.nombre }}
        </v-chip>
      </template>

      <template v-slot:item.comentarios="{item}">
        <v-badge
          :content="item.comentarios_no_leidos_count"
          :value="item.comentarios_no_leidos_count"
          color="pink"
          v-if="item.id_estado !== 1 && item.id_estado !== 5"
          overlap
        >
          <v-btn @click="$router.push({name: 'comentarios.index',params: { id_solicitud: item.id_solicitud }})" icon>
            <v-icon>mdi-comment</v-icon>
          </v-btn>
        </v-badge>
      </template>

      <template v-slot:item.prioridad="{item}">
        <v-chip v-if="item.prioridad" small label :color="item.prioridad.color">
          {{ item.prioridad.nombre }}
        </v-chip>
      </template>

      <template v-slot:item.id_estado="{item}">
        <v-chip dark label :color="item.estado.color" small>
          {{ item.estado.nombre }}
        </v-chip>
      </template>

      <template v-slot:item.acciones="{item}">
         <template v-if="item.id_estado !== 5">
          <v-btn icon @click="editarSolicitud(item)" small><v-icon small>mdi-pencil</v-icon></v-btn>
          <v-btn v-if="item.id_estado === 1" icon @click="eliminarSolicitud(item)" small><v-icon small>mdi-delete</v-icon></v-btn>
         </template>
      </template>

      <template v-slot:expanded-item="{ headers, item }">
        <td :colspan="headers.length">
          <div>
            <p class="subtitle-1">Descripcion</p>
            <p>
              {{ item.descripcion }}
            </p>
          </div>
          <div>
            <p class="subtitle-1">Contenido</p>
            <p>
              {{ item.contenido }}
            </p>
          </div>
        </td>
      </template>
    </v-data-table>
    <DialogVerAdjuntos
      v-show="dialogAdjuntos"
      @close-dialog="dialogAdjuntos = false"
      :solicitud="solicitudCopy"
      :show-dialog="dialogAdjuntos"
      @ver-photos="verFotos"
    />
<!--    <v-btn color="primary" fixed bottom right fab dark @click="$store.dispatch('solicitud/fetchData')"><v-icon>mdi-update</v-icon></v-btn>-->
  </div>
</template>

<script>
import {mapGetters} from "vuex";
import FiltersUsuario from "../solicitudes/FiltersUsuario";
import DialogVerAdjuntos from "../shared/DialogVerAdjuntos";
import moment from "moment";

export default {
  name: "SolicitudesUsuario",
  components: {FiltersUsuario, DialogVerAdjuntos},
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
        { text: 'Codigo', value: 'id_solicitud', sortable: true },
        { text: 'Proyecto', value: 'id_proyecto', sortable: true },
        { text: 'Objetivo', value: 'objetivo', sortable: true },
        { text: 'Medidas', value: 'medidas', sortable: true },
        { text: 'Formato', value: 'formato', sortable: true },
        { text: 'Imagen Referencial', value: 'path', sortable: false },
        { text: 'Adjuntos', value: 'adjuntos', sortable: false },
        { text: 'Fecha de Entrega', value: 'fecha_entrega'},
        { text: 'Prioridad', value: 'id_prioridad'},
        { text: 'Comentarios', value: 'comentarios', sortable: false },
        { text: 'Estado', value: 'id_estado', sortable: true },
        { text: 'Acciones', value: 'acciones', sortable: false },
      ],
      images: [],
      module: 'user',
      solicitudCopy: {},
      dialogAdjuntos: false,
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
    verAdjuntos(solicitud) {
      this.solicitudCopy = { ... solicitud }
      this.dialogAdjuntos = true
    },
    eliminarSolicitud({ id_solicitud }) {
      const conf = confirm(`Esta seguro de eliminar la solicitud ${id_solicitud}?`)
      if (conf) this.$store.dispatch('solicitud/delete', id_solicitud)
    },
    editarSolicitud({id_solicitud}){
      this.$router.push({
        name: 'solicitud.editar',
        params: {
          idSolicitud: id_solicitud
        }
      })
    }
  },
  computed: {
    ...mapGetters('solicitud', [
      'solicitudes',
      'isLoading',
      'totalItems',
    ]),
    options: {
      get() {
        return this.$store.state.solicitud.tableOptions
      },
      set(options) {
        this.$store.commit('solicitud/SET_TABLE_OPTIONS', options)
      }
    }
  },
  created() {
    this.$store.dispatch(`solicitud/fetchData`)
  },
  watch: {
    options() {
      this.$store.dispatch('solicitud/fetchData')
    }
  }
}
</script>

<style>
.image{
  display: none;
}
</style>-->
