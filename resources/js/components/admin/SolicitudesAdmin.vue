<template>
  <div>
    <viewer :images="images" @inited="inited" class="viewer" ref="viewer">
      <img v-for="(src,i) in images" :src="src" :key="i" class="image"  :alt="`Imagen${i}`"/>
    </viewer>
    <p class="display-1 pt-5 text-center">SOLICITUDES</p>
    <v-divider></v-divider>
    <v-data-table
      :headers="headers"
      :items="solicitudes"
      :loading="isLoading"
      :options.sync="options"
      :server-items-length="totalItems"
      :footer-props="{ 'items-per-page-options': [5, 10, 15, 30, 50]}"
      multi-sort
      class="elevation-1"
    >
      <template v-slot:top>
        <FiltersAdmin />
      </template>
      <template v-slot:item.created_at="{item}">
        {{ item.fecha }}
      </template>

      <template v-slot:item.solicitante="{item}">
        {{ item.solicitante ? item.solicitante.full_name : ''  }} 
      </template>

      <template v-slot:item.encargado="{item}">
        {{ item.encargado ? item.encargado.full_name : ''  }} 
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
        <template v-if="item.id_estado !== 1">
          <v-btn v-if="item.adjuntos.length > 0" @click="verAdjuntos(item)" small icon>
            <v-icon small>mdi-eye</v-icon>
          </v-btn>
          <v-btn v-if="item.id_estado !== 5" @click="abrirDialogoAdjuntos(item.id_solicitud)" small icon><v-icon small>mdi-cloud-upload</v-icon></v-btn>
        </template>
      </template>

      <template v-slot:item.comentarios="{item}">
        <v-badge
          :content="item.comentarios_no_leidos_count"
          :value="item.comentarios_no_leidos_count"
          color="pink"
          overlap
          v-if="item.id_estado !== 1 && item.id_estado !== 5"
        >
          <v-btn @click="$router.push({name: 'comentarios.index',params: { id_solicitud: item.id_solicitud }})" icon>
            <v-icon>mdi-comment</v-icon>
          </v-btn>
        </v-badge>
      </template>

      <template v-slot:item.id_estado="{item}">
        <v-chip dark label :color="item.estado.color" small>
          {{ item.estado.nombre }}
        </v-chip>
      </template>

      <template v-slot:item.acciones="{item}">
        <v-btn v-if="item.id_estado === 1" @click="empezarSolicitud(item)" icon><v-icon>mdi-play-circle</v-icon></v-btn>
        <v-btn v-if="item.id_estado !== 1 && item.id_estado !== 5" @click="finalizar(item)" icon>
          <v-icon>mdi-checkbox-marked-circle</v-icon>
        </v-btn>
        <v-btn @click="abrirDialogDetalle(item)" icon><v-icon>mdi-message-bulleted</v-icon></v-btn>
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

      <template v-slot:item.id_prioridad="{ item }">
        <template v-if="item.id_estado !== 1 && item.id_estado !== 5">
          <v-edit-dialog
            large
            @open="open(item)"
            @save="save()"
          >
            <v-chip v-if="item.prioridad" small label :color="item.prioridad.color">
              {{ item.prioridad.nombre }}
            </v-chip>
            <template v-slot:input>
              <div class="mt-4 text-h6">
                Actualizar prioridad
              </div>
              <v-select
                v-model="solicitudCopy.id_prioridad"
                :items="$store.state.solicitud.prioridades"
                item-value="id"
                item-text="nombre"
              ></v-select>
            </template>
          </v-edit-dialog>
        </template>
        <template v-else>
          <v-chip v-if="item.id_estado === 5 && item.prioridad" small label :color="item.prioridad.color">
            {{ item.prioridad.nombre }}
          </v-chip>
        </template>
      </template>
 
      <template v-slot:item.fecha_entrega="{item}">
        <template v-if="item.id_estado !== 1 && item.id_estado !== 5">
          <v-edit-dialog
            large
            @open="open(item)"
            @save="save()"
          >
            <template v-if="item.fecha_entrega">
              {{moment(item.fecha_entrega).format('DD/MM/YYYY')}}
            </template>
            <template v-else>
              <v-chip label small>ESTABLECER</v-chip>
            </template>
            <br>
            <template v-slot:input>
              <div class="mt-4 text-h6">
                Actualizar fecha de entrega
              </div>
              <input class="form-control my-3" placeholder="Fecha de Entrega" type="date" :min="moment().format('YYYY-MM-DD')"
                     v-model="solicitudCopy.fecha_entrega">
            </template>
          </v-edit-dialog>
        </template>
        <template v-else>{{ item.fecha_entrega ? moment(item.fecha_entrega).format('DD/MM/YYYY') : '' }}</template>
      </template>

    </v-data-table>
<!--    <v-btn color="primary" fixed bottom right fab dark @click="$store.dispatch('admin/fetchData')"><v-icon>mdi-update</v-icon></v-btn>-->
    <GuardarAdjuntosDialog
      v-show="dialog"
      @close-dialog="dialog = false"
      :id_solicitud="id_solicitud"
      :show-dialog="dialog"
    />
    <DialogAtenderSolicitud
      v-show="dialogAtender"
      @close-dialog="dialogAtender = false"
      :solicitud="solicitudCopy"
      :show-dialog="dialogAtender"
      />
    <DialogVerAdjuntos
      v-show="dialogAdjuntos"
      @close-dialog="dialogAdjuntos = false"
      :solicitud="solicitudCopy"
      :show-dialog="dialogAdjuntos"
      @ver-photos="verFotos"
      />
    <SolicitudDetalle
      v-show="dialogDetalle"
      @close-dialog="dialogDetalle = false"
      :solicitud="solicitudCopy"
      :show-dialog="dialogDetalle"
      />
  </div>
</template>

<script>

import {mapGetters} from "vuex";
import FiltersAdmin from "./FiltersAdmin";
import GuardarAdjuntosDialog from "./GuardarAdjuntosDialog";
import moment from "moment";
import DialogAtenderSolicitud from "./DialogAtenderSolicitud";
import DialogVerAdjuntos from "../shared/DialogVerAdjuntos";
import SolicitudDetalle from "./SolicitudDetalle";

export default {
  name: "SolicitudesAdmin",
  components: {SolicitudDetalle, DialogAtenderSolicitud, GuardarAdjuntosDialog, FiltersAdmin, DialogVerAdjuntos},
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
        { text: 'Encargado', value: 'encargado', sortable: false },
        { text: 'Codigo', value: 'id_solicitud', sortable: true },
        { text: 'Proyecto', value: 'id_proyecto', sortable: true },
        /*{ text: 'Objetivo', value: 'objetivo', sortable: true },
        { text: 'Medidas', value: 'medidas', sortable: true },
        { text: 'Formato', value: 'formato', sortable: true },*/
        { text: 'Imagen Referencial', value: 'path', sortable: false },
        { text: 'Adjuntos', value: 'adjuntos', sortable: false },
        { text: 'Fecha de Entrega', value: 'fecha_entrega', sortable: true},
        { text: 'Prioridad', value: 'id_prioridad', sortable: true},
        { text: 'Comentarios', value: 'comentarios', sortable: false },
        { text: 'Estado', value: 'id_estado', sortable: true },
        { text: 'Acciones', value: 'acciones', sortable: false },
      ],
      images: [],
      dialog: false,
      dialogAtender: false,
      dialogAdjuntos: false,
      dialogDetalle: false,
      id_solicitud: null,
      solicitudCopy: {},
      adjuntosCopy: {},
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
    verAdjuntos(solicitud) {
      /*this.images = [];
      adjuntos.forEach(adjunto => this.images.push(`/api/solicitud/image/${adjunto.path}`))
      this.$viewer.show();*/
      this.solicitudCopy = { ... solicitud }
      this.dialogAdjuntos = true
    },
    finalizar(solicitud){
      const conforme = confirm('Está seguro de finalizar la solicitud?')
      if (conforme) {
        const data = { ...solicitud }
        data.id_estado = 5
        this.$store.dispatch('solicitud/update', data)
        .then(() => this.$store.dispatch('admin/fetchData'))
        .catch(msg => this.$store.commit('SHOW_ERROR_SNACKBAR', msg.message()))
      }
    },
    empezarSolicitud(solicitud) {
      this.solicitudCopy = { ...solicitud }
      this.dialogAtender = true
    },
    abrirDialogoAdjuntos(id_solicitud) {
      this.id_solicitud = id_solicitud
      this.dialog = true
    },
    abrirDialogDetalle(solicitud) {
      this.solicitudCopy = {...solicitud}
      this.dialogDetalle = true
    },
    open(solicitud) {
      this.solicitudCopy = {...solicitud}
    },
    /*openDate(solicitud) {
      this.solicitudCopy = {...solicitud}
      if (!this.solicitudCopy.fecha_entrega) this.solicitudCopy.fecha_entrega = moment().format('DD/MM/YYYY')
    },*/
    save() {
      this.$store.dispatch('solicitud/update', this.solicitudCopy)
        .then(res => this.$store.dispatch('admin/fetchData'))
        .catch(msg => this.$store.commit('SHOW_ERROR_SNACKBAR', msg))
    }
  },
  computed: {
    ...mapGetters('admin', [
      'solicitudes',
      'isLoading',
      'totalItems'
    ]),
    ...mapGetters('currentUser',[
      'idUsuario'
    ]),
    options: {
      get() {
        return this.$store.state.admin.tableOptions
      },
      set(options) {
        this.$store.commit('admin/SET_TABLE_OPTIONS', options)
      }
    }
  },
  created() {
    this.$store.dispatch('solicitud/fetchPrioridades')
    this.$store.dispatch('admin/fetchData')
  },
  watch: {
    options() {
      this.$store.dispatch('admin/fetchData')
    }
  }
}
</script>

<style>
.image{
  display: none;
}
</style>
