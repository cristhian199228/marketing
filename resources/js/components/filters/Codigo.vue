<template>
  <v-text-field
    label="Codigo"
    v-model="codigo"
    clearable
    dense
  ></v-text-field>
</template>

<script>
export default {
  name: "Codigo",
  props: ['store'],
  computed: {
    codigo: {
      get() {
        return this.$store.state[this.store].filters.codigo
      },
      set(val) {
        this.$store.commit(`${this.store}/SET_FILTRO_CODIGO`, val)
      }
    },
  }
}
</script>
