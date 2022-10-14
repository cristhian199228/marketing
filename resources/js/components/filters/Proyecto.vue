<template>
  <v-select
    label="Proyecto"
    dense
    v-model="proyecto"
    :items="$store.state.solicitud.proyectos"
    item-value="id"
    item-text="nombre"
    multiple
    chips
  >
    <template v-slot:selection="{ item }">
      <v-chip
        small
        label
        color="primary"
        close
        @click:close="remove(item.id)"
      >
        {{ item.nombre }}
      </v-chip>
    </template>
  </v-select>
</template>

<script>
export default {
  name: "Proyecto",
  props: ['store'],
  computed: {
    proyecto: {
      get() {
        return this.$store.state[this.store].filters.proyecto
      },
      set(val) {
        this.$store.commit(`${this.store}/SET_FILTRO_PROYECTO`, val)
      }
    },
  },
  methods: {
    remove(id) {
      const idx = this.proyecto.indexOf(id)
      this.proyecto.splice(idx, 1)
    }
  },
  mounted() {
    this.$store.dispatch('solicitud/fetchProyectos')
  }
}
</script>
