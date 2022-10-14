<template>
  <v-text-field
    label="Encargado"
    v-model="fullName"
    clearable
    dense
  ></v-text-field>
</template>

<script>
export default {
  name: "FullNameEncargado",
  props: ['store'],
  computed: {
    fullName: {
      get() {
        return this.$store.state[this.store].filters.encargado
      },
      set(val) {
        this.$store.commit(`${this.store}/SET_FILTRO_ENCARGADO`, val)
      }
    },
  }
}
</script>