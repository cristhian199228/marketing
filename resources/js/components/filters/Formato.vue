<template>
  <v-select
    label="Formato"
    dense
    v-model="formato"
    :items="formatos"
    clearable
  ></v-select>
</template>

<script>
export default {
  name: "Formato",
  props: ['store'],
  data() {
    return {
      formatos: [
        'digital', 'impreso', 'video'
      ],
    }
  },
  computed: {
    formato: {
      get() {
        return this.$store.state[this.store].filters.formato
      },
      set(val) {
        this.$store.commit(`${this.store}/SET_FILTRO_FORMATO`, val)
      }
    },
  }
}
</script>
