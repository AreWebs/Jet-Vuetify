<template>
  <guest-layout>
    <v-row justify="center">
      <v-col cols="10" sm="8" md="7">
        
        <v-card class="py-3 px-3 mt-2" >
          <transition mode="out-in" name="slide-fade">
            <!-- init -->
            <div key="registerInit" v-if="!signUpEmail">
              <v-card-title>Create your account</v-card-title>

              <v-card-text>
                <v-btn class="my-5" color="primary" block @click="signUpEmail=true">
                  <v-icon>mdi-email-outline</v-icon> Sign Up with Email
                </v-btn>
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn class="mb-5" color="white" block v-bind="attrs"
                      v-on="on">
                      <v-icon>mdi-google</v-icon> Sign Up with Google
                    </v-btn>
                  </template>
                  <span>Disponible en proximas actualizaciones</span>
                </v-tooltip>
                <v-tooltip bottom>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn class="mb-5" color="indigo" dark block v-bind="attrs"
                      v-on="on">
                      <v-icon>mdi-github</v-icon> Sign Up with Github
                    </v-btn>
                  </template>
                  <span>Disponible en proximas actualizaciones</span>
                </v-tooltip>
              </v-card-text>
            </div>

            <div key="registerEmail" v-else>
              <v-card-title>Sign Up with Email</v-card-title>
              <v-card-text class="pb-4">
                Sign up with <inertia-link>Google</inertia-link> or <inertia-link>Github</inertia-link> instead
              </v-card-text>

              <v-card-text>
                <validation-errors class="mb-4" />

                <form @submit.prevent="submit">
                  <v-text-field required autofocus dense
                    outlined label="Full Name" v-model="form.name"></v-text-field>
                  <v-text-field required dense
                    outlined label="Email address" v-model="form.email"></v-text-field>
                  <v-text-field dense outlined label="Password" required
                    autocomplete="new-password"
                    v-model="form.password"
                    :append-icon="showP ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showP ? 'text' : 'password'"
                    hint="At least 8 characters"
                    @click:append="showP = !showP"></v-text-field>
                  <v-text-field dense outlined label="Confirm Password" required
                    autocomplete="new-password"
                    v-model="form.password_confirmation"
                    :append-icon="showCP ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="showCP ? 'text' : 'password'"
                    hint="At least 8 characters"
                    @click:append="showCP = !showCP"></v-text-field>
                  <v-btn color="success" block type="submit"  :disabled="form.processing">Register</v-btn>
                </form>
              </v-card-text>
            </div>

          </transition>
          
          <v-card-text class="mb-3" style="font-size: 14px;">
            By signing up you agree to the 
            <inertia-link href="register">Terms of Service</inertia-link> and 
            <inertia-link href="register">Privacy Policy</inertia-link>
          </v-card-text>

        </v-card>
       
      </v-col>
    </v-row>
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
  
  data() {
    return {
      signUpEmail: false,
      showP: false,
      showCP: false,
      form: this.$inertia.form({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: true,
      })
    }
  },

  methods: {
    submit() {
      this.form.post(this.route('register'), {
        onFinish: () => this.form.reset('password', 'password_confirmation'),
      })
    }
  }
}
</script>

<style>

</style>