<template>
  <v-select
    label="Estado"
    dense
    v-model="estado"
    :items="$store.state.solicitud.estados"
    item-text="nombre"
    item-value="id_estado"
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
  name: "Estado",
  props: ['store'],
  computed: {
    estado: {
      get() {
        return this.$store.state[this.store].filters.estado
      },
      set(val) {
        this.$store.commit(`${this.store}/SET_FILTRO_ESTADO`, val)
      }
    },
  },
  methods: {
    remove(id) {
      const idx = this.estado.indexOf(id)
      this.estado.splice(idx, 1)
    }
  },
  created() {
    this.$store.dispatch('solicitud/fetchEstados')
  }
}
</script>
