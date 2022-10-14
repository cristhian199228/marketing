<template>
  <div>
    <v-card outlined class="mb-2">
      <v-card-title class="mb-0 pb-0">
        <p class="caption font-weight-medium">{{ comentario.usuario }}</p>
        <v-spacer></v-spacer>
        <p class="caption">
          <Timeago :datetime="comentario.created_at" locale="es" :auto-update="60"></Timeago>
        </p>
      </v-card-title>
      <v-divider class="my-0"></v-divider>
      <v-card-text>
        {{ comentario.mensaje }}
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  name: "Comentario",
  props: ['comentario']
}
</script>