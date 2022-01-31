<template>
   <v-app id="inspire">
     <v-main>
       <v-container fluid fill-height>
          <v-layout flex align-center justify-center>
            <v-flex xs12 sm4 elevation-6>
              <v-card class="elevation-12" :loading="isLoading">
                <v-toolbar
                  color="primary"
                  dark
                  flat
                >
                  <v-toolbar-title >Register </v-toolbar-title>
                </v-toolbar>
              
                <v-card-text>
                  <div>
                    <validation-observer
                      ref="observer"
                      v-slot="{ invalid }"
                    >
                      <form @submit.prevent="submit">
                        <!-- Nama -->
                        <validation-provider
                          v-slot="{ errors }"
                          name="Nama"
                          rules="required|min:5"
                        >
                          <v-text-field
                            v-model="form.name"
                            :error-messages="errors"
                            label="Nama"
                            prepend-icon="mdi-account"
                            required
                          ></v-text-field>
                        </validation-provider>

                        <!-- Username -->
                        <validation-provider
                          v-slot="{ errors }"
                          name="Username"
                          rules="required|min:5"
                        >
                          <v-text-field
                            v-model="form.username"
                            :error-messages="errors"
                            label="Username"
                            prepend-icon="mdi-account-box"
                            required
                          ></v-text-field>
                        </validation-provider>

                        <!-- email -->
                        <validation-provider
                          v-slot="{ errors }"
                          name="email"
                          rules="required|email"
                        >
                          <v-text-field
                            v-model="form.email"
                            :error-messages="errors"
                            label="E-mail"
                            prepend-icon="mdi-email"
                            required
                          ></v-text-field>
                        </validation-provider>

                        <!-- password -->
                        <validation-provider
                          v-slot="{ errors }"
                          name="password"
                          rules="required|password:@confirm|min:8"
                        >
                          <v-text-field
                            v-model="form.password"
                            :error-messages="errors"
                            prepend-icon="mdi-lock"
                            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                            :type="show1 ? 'text' : 'password'"
                            counter
                            @click:append="show1 = !show1"
                            name="password"
                            label="Password"
                            hint="At least 8 characters"
                            
                          ></v-text-field>
                        </validation-provider>

                        <!-- password confirmation-->
                        <validation-provider
                          v-slot="{ errors }"
                          name="confirm"
                          rules="required|min:8"
                        >
                          <v-text-field
                            v-model="form.password_confirmation"
                            :error-messages="errors"
                            prepend-icon="mdi-lock-question"
                            type='password'
                            label="Password Confirmation"
                            hint="At least 8 characters"
                            counter
                          ></v-text-field>
                        </validation-provider>

                        <v-btn
                          class="mr-4"
                          type="submit"
                          :disabled="invalid"
                        >
                          submit
                        </v-btn>
                        <!-- <v-spacer></v-spacer> -->
                        <p class="ml-3 float-right">Sudah punya akun? <router-link to="/login">Login!</router-link></p>

                        
<!--                         <v-btn @click="clear">
                          reset
                        </v-btn> -->
                      </form>
                    </validation-observer>
                  </div>
                </v-card-text>

              </v-card>
            </v-flex>
          </v-layout>
       </v-container>
     </v-main>

     <v-snackbar
        v-model="snackbar"
        :multi-line="multiLine"
        top
        :color= warnaSnackbar
      >
        {{ teksSnackbar }}

        <template v-slot:action="{ attrs }">
          <v-btn
            color="white"
            text
            v-bind="attrs"
            @click="snackbar = false"
          >
            Close
          </v-btn>
        </template>
      </v-snackbar>
   </v-app>
 </template>

<script>
  import axios from 'axios'
  import { required, digits, email, max, regex, min } from 'vee-validate/dist/rules'
  import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'

  setInteractionMode('eager')

  extend('digits', {
    ...digits,
    message: '{_field_} needs to be {length} digits. ({_value_})',
  })

  extend('required', {
    ...required,
    message: '{_field_} can not be empty',
  })

  extend('max', {
    ...max,
    message: '{_field_} may not be greater than {length} characters',
  })

  extend('min', {
    ...min,
    message: '{_field_} may not be lesser than {length} characters',
  })

  extend('regex', {
    ...regex,
    message: '{_field_} {_value_} does not match {regex}',
  })

  extend('email', {
    ...email,
    message: 'Email must be valid',
  })

  extend('password', {
    params: ['target'],
    validate(value, { target }) {
      return value === target;
    },
    message: 'Password confirmation does not match'
  })



  export default {
    name: 'Register',
    components: {
      ValidationProvider,
      ValidationObserver,
    },
    data() {
      return {
        form: {
          name: '',
          username: '',
          email: '',
          password: '',
          password_confirmation: '',
        },
        show1: false,
        snackbar: false,
        isLoading: false,
        multiLine: true,
        teksSnackbar: "",
        warnaSnackbar: "",
        // password: '',
        rules: {
          required: value => !!value || 'Required.',
          min: v => v.length >= 8 || 'Min 8 characters',
          emailMatch: () => (`The email and password you entered don't match`),
        },
      }
    },

    methods: {
      submit () {
        this.$refs.observer.validate()
        this.isLoading = "white"
        axios.post("/api/register", this.form).then(response => {
          console.log(response.data)
          this.teksSnackbar= "Berhasil menambahkan akun ",
          this.warnaSnackbar= "primary",
          this.snackbar = true
          this.clear()
          
        }).catch(errors => {
          console.log(errors.response.data.errors)
          this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"]),
          this.warnaSnackbar= "red",
          this.snackbar = true

        }).finally(() => {
          this.isLoading = false
        })
      },
      clear () {
        this.form.name = ''
        this.form.username = ''
        this.form.email = ''
        this.form.password = ''
        this.form.password_confirmation = ''
        this.form.roles = null
        this.$refs.observer.reset()
      },
    },
  }
</script>
<style scoped>
#inspire {
  background: url('https://upload.wikimedia.org/wikipedia/commons/d/d1/Jembatan_Kahayan_Palangkaraya.jpg')
    no-repeat center center fixed !important;
  background-size: cover;
}
</style>