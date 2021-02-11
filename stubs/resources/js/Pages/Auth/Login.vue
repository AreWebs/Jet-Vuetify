<template>
  <guest-layout>
    <v-row justify="center">
      <v-col cols="10" sm="8" md="6">
        <v-card class="p-2">
          <v-card-title>Sign in to your account</v-card-title>
          <v-card-text>
            <validation-errors class="mb-4" />

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
              <v-text-field dense outlined label="Email Address" required autofocus 
                v-model="form.email"></v-text-field>
              <v-text-field dense hide-details outlined label="Password" required
                autocomplete="current-password"
                v-model="form.password"
                :append-icon="showP ? 'mdi-eye' : 'mdi-eye-off'"
                :type="showP ? 'text' : 'password'"
                hint="At least 8 characters"
                @click:append="showP = !showP"></v-text-field>
              <v-checkbox
                v-model="form.remember"
                label="Remember me"
              ></v-checkbox>
              <v-btn block color="primary" type="submit" :disabled="form.processing"> Sign In</v-btn>

            </form>

            <h5 class="my-6 text-center">OR</h5>

            <v-btn block outlined color="primary" class="mb-5">
              <v-icon>mdi-google</v-icon> Sign in with Google
            </v-btn>
            <v-btn block color="primary" class="mb-6">
              <v-icon>mdi-github</v-icon> Sign with Github
            </v-btn>
            <br>
            <div class="mb-3 d-flex justify-space-between">
              <inertia-link v-if="canResetPassword" :href="route('password.request')">
                Forgot your password?
              </inertia-link>
              
              <div>
                 Don't have an account?
                <inertia-link :href="route('register')">
                  Sign up
                </inertia-link>
              </div>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-sheet class="" color="gray pulse" width="200px">
    </v-sheet>
  </guest-layout>
</template>

<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import ValidationErrors from '@/Components/ValidationErrors';

export default {
  components: {
    GuestLayout,
    ValidationErrors
  },

  props: {
    canResetPassword: Boolean,
    status: String
  },

  data() {
    return {
      form: this.$inertia.form({
          email: '',
          password: '',
          remember: false
      }),
      showP: false,
    }
  },

  methods: {
    submit() {
      this.form
          .transform(data => ({
              ... data,
              remember: this.form.remember ? 'on' : ''
          }))
          .post(this.route('login'), {
              onFinish: () => this.form.reset('password'),
          })
    }
  }
}
</script>

<style scoped>
a {
  font-size: 1.15rem;
}
</style>