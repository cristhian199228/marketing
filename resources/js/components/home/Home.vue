<template>
  <div>
    <template v-if="isAuthenticated">
      <p>Bienvenido {{ fullName }} </p>
    </template>
    <template v-else>
      <p class="display-1">BIENVENIDO</p>
      <v-btn color="primary" @click="login">INGRESAR</v-btn>
    </template>
  </div>
</template>

<script>
import { signIn } from '../../msal'
import { mapState, mapGetters } from 'vuex'
export default {
  methods: {
    login() {
      signIn()
    }
  },
  computed: {
    ...mapState('currentUser',[
      'isAuthenticated'
    ]),
    ...mapGetters('currentUser',[
      'fullName'
    ])
  }
};
</script>
