<template>
  <v-select
    label="Prioridad"
    dense
    v-model="prioridad"
    :items="$store.state.solicitud.prioridades"
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
    prioridad: {
      get() {
        return this.$store.state[this.store].filters.prioridad
      },
      set(val) {
        this.$store.commit(`${this.store}/SET_FILTRO_PRIORIDAD`, val)
      }
    },
  },
  methods: {
    remove(id) {
      const idx = this.prioridad.indexOf(id)
      this.prioridad.splice(idx, 1)
    }
  },
  mounted() {
    this.$store.dispatch('solicitud/fetchPrioridades')
  },
}
</script>
