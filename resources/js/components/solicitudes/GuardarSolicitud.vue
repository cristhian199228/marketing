<template>
  <div>
    <validation-observer ref="observer" v-slot="{ invalid }">
      <v-card outlined>
        <p class="display-1 mt-5 text-center">
          {{ `${idSolicitud ? 'EDITAR' : 'NUEVA'} SOLICITUD`}}
        </p>
        <v-divider></v-divider>
        <v-card-text>
          <p>
            Por favor llene el siguiente formulario
          </p>
          <v-row>
            <v-col cols="12" md="8" lg="8">
<!--              <validation-provider v-slot="{ errors }" name="prioridad" rules="required">
                <v-select
                  v-model="form.id_prioridad"
                  label="Prioridad*"
                  :items="$store.state.solicitud.prioridades"
                  item-value="id"
                  item-text="nombre"
                  dense
                  :error-messages="errors"
                >
                </v-select>
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
                  <validation-provider v-slot="{ errors }" name="fecha de entrega" rules="required">
                    <v-text-field
                      v-model="form.fecha_entrega"
                      label="Fecha de entrega*"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      :error-messages="errors"
                    ></v-text-field>
                  </validation-provider>
                </template>
                <v-date-picker
                  v-model="form.fecha_entrega"
                  @input="menu = false"
                  locale="es"
                  :min="moment().format('YYYY-MM-DD')"
                ></v-date-picker>
              </v-menu>-->
              <validation-provider v-slot="{ errors }" name="proyecto" rules="required">
                <v-select
                  v-model="form.id_proyecto"
                  label="Proyecto*"
                  :items="$store.state.solicitud.proyectos"
                  item-value="id"
                  item-text="nombre"
                  dense
                  :error-messages="errors"
                >
                </v-select>
              </validation-provider>
              <validation-provider v-slot="{ errors }" name="objetivo" rules="required">
                <v-text-field
                  v-model="form.objetivo"
                  label="Objetivo*"
                  dense
                  :error-messages="errors"
                ></v-text-field>
              </validation-provider>
              <validation-provider v-slot="{ errors }" name="descripcion" rules="required">
                <v-textarea
                  v-model="form.descripcion"
                  label="Descripcion*"
                  dense
                  :error-messages="errors"
                ></v-textarea>
              </validation-provider>
              <validation-provider v-slot="{ errors }" name="contenido" rules="required">
                <v-textarea
                  v-model="form.contenido"
                  label="Contenido*"
                  dense
                  :error-messages="errors"
                ></v-textarea>
              </validation-provider>
              <div class="d-flex">
                <v-text-field
                  v-model="form.ancho"
                  label="Ancho"
                  dense
                  type="number"
                  class="mr-2"
                ></v-text-field>
                <v-text-field
                  v-model.number="form.altura"
                  label="Altura"
                  dense
                  type="number"
                  class="mr-2"
                ></v-text-field>
                <v-select
                  v-model="form.unidad_medida"
                  label="Unidad de medida"
                  :items="unidades"
                  dense
                >
                </v-select>
              </div>
              <v-select
                v-model="form.formato"
                label="Formato"
                :items="formatos"
                dense
              >
              </v-select>
              <div style="height: 600px;width: 1000px;" class="text-center">
                <tui-image-editor ref="editor" :include-ui="useDefaultUI" :options="options"></tui-image-editor>
              </div>
            </v-col>
          </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text color="primary" to="/solicitudes">CANCELAR</v-btn>
          <v-btn :loading="loading" color="primary" @click="guardar" :disabled="invalid">GUARDAR</v-btn>
        </v-card-actions>
      </v-card>
    </validation-observer>
  </div>
</template>

<script>
import 'tui-color-picker/dist/tui-color-picker.css';
import 'tui-image-editor/dist/tui-image-editor.css';
import ImageEditor from '@toast-ui/vue-image-editor/src/ImageEditor.vue';
import moment from "moment";

export default {
  name: "GuardarSolicitud",
  components: {
    'tui-image-editor': ImageEditor,
  },
  data() {
    return {
      useDefaultUI: true,
      options: {
        cssMaxWidth: 800,
        cssHeight: 600,
        initMenu: 'filter',
        menuBarPosition: 'left'
      },
      unidades: [
        'm', 'cm', 'px'
      ],
      formatos: [
        'digital', 'impreso', 'video'
      ],
      form: {
        objetivo: null,
        descripcion: null,
        contenido: null,
        altura: null,
        ancho: null,
        unidad_medida: null,
        formato: null,
        image: null,
        id_prioridad: null,
        fecha_entrega: null,
        id_proyecto: null,
      },
      files: [],
      idSolicitud: this.$route.params.idSolicitud,
      menu: false,
      moment,
      loading: false
    };
  },
  methods: {
    guardar() {
      this.form.image = this.$refs.editor ? this.$refs.editor.invoke('toDataURL') : null
      this.loading = true
      if (this.idSolicitud) {
        this.$store.dispatch('solicitud/update', this.form)
        .then(() => {
          this.loading = false
          this.$router.push('/solicitudes')
        })
        .catch(message => {
          this.$store.commit('SHOW_ERROR_SNACKBAR', message)
          this.loading = false
        })
      } else {
        this.$store.dispatch('solicitud/store', this.form).then(() => {
          this.loading = false
          this.$router.push('/solicitudes')
        })
        .catch(err => this.$store.commit('SHOW_ERROR_SNACKBAR', err))
      }
    },
  },
  mounted() {
    this.$store.dispatch('solicitud/fetchPrioridades')
    this.$store.dispatch('solicitud/fetchProyectos')
    if (this.idSolicitud) {
      this.$store.dispatch('solicitud/showSolicitud', this.idSolicitud)
        .then(sol => {
          this.form = {...sol}
          let actions = this.$refs.editor.invoke('getActions');
          if(sol.path && actions) {
            actions.main.initLoadImage(`/api/solicitud/image/${sol.path}`, 'My sample image');
            this.$refs.editor.invoke('ui.activeMenuEvent');
          }
      }).catch(err => {
        this.$store.commit('SHOW_ERROR_SNACKBAR', err)
        this.$router.push('/')
      })
    }
  },
};
</script>

