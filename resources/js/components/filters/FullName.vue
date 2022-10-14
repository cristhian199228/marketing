<template>
  <v-text-field
    label="Solicitante"
    v-model="fullName"
    clearable
    dense
  ></v-text-field>
</template>

<script>
export default {
  name: "FullName",
  props: ['store'],
  computed: {
    fullName: {
      get() {
        return this.$store.state[this.store].filters.solicitante
      },
      set(val) {
        this.$store.commit(`${this.store}/SET_FILTRO_SOLICITANTE`, val)
      }
    },
  }
}
</script>
