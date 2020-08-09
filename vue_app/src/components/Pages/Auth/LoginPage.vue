<template>
    <v-container class="d-flex flex-wrap justify-center">
      <!-- Login Sheet -->
      <v-sheet elevation=2 class="pl-10 pr-10" width="40%" style="min-width: 250px;">
        <div>
          <!-- Login Header Text -->
          <v-row>
            <v-col>
                <h1>Login</h1>
            </v-col>
          </v-row>

          <!-- Login Form -->
          <v-row>
            <v-col>
              <v-form ref="loginForm" v-model="valid">
                <!-- Login Form Inputs -->
                <v-text-field solo v-model="email" label="Email" :rules="[v => !!v || 'Email is required']" required></v-text-field>
                <v-text-field solo v-model="password" label="Password" :rules="[v => !!v || 'Password is required']" required></v-text-field>
                <v-checkbox v-model="rememberMe" label="Remember Me"></v-checkbox>

                <!-- Login Form Actions -->
                <v-btn @click="login">Login</v-btn>
                <v-btn class="ml-2" small text color="primary">Forgot Password?</v-btn>
              </v-form>
            </v-col>
          </v-row>
        </div>
      </v-sheet>
    </v-container>
</template>

<script>

export default {
  name: 'LoginPage',
  components: {},
  data: () => {
    return {
      email: '',
      password: '',
      rememberMe: false,
      valid: false
    }
  },
  mounted() {
  },
  methods: {
    login () {
      if (!this.$refs.loginForm.validate()) {
        return;
      }
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>