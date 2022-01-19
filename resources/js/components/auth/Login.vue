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
                  <v-toolbar-title >Selamat datang</v-toolbar-title>
                </v-toolbar>
              
                <v-card-text>
                  <div>
                      <v-form v-model="valid" ref="form">
                        <v-text-field
                          label="Enter your e-mail address"
                          v-model="form.email"
                          prepend-icon= "mdi-email"
                          :rules="emailRules"
                          required
                        ></v-text-field>
                        <v-text-field
                          label="Enter your password"
                          v-model="form.password"
                          prepend-icon= "mdi-lock"
                          min="8"
                          type='password'
                          :rules="passwordRules"
                          counter
                          required
                        ></v-text-field>
                        <v-layout justify-space-between>
                            <v-btn @click="submit" :class=" { 'primary darken-4 white--text' : valid, disabled: !valid }">Login</v-btn>
                            <!-- <a href="">Forgot Password</a> -->
                            <!-- <router-link to="/register" class="ml-3 float-right">Register</router-link> -->
                        </v-layout>
                      </v-form>
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
    export default {
      data () {
          return {
            form: {
              email: '',
              password: '',
              device_name: 'browser',
            },
            multiLine: true,
            snackbar: false,
            isLoading: false,
            teksSnackbar: "",
            warnaSnackbar: "",
            valid: false,
            e1: false,
            passwordRules: [
              (v) => !!v || 'Password is required',
            ],
            emailRules: [
              (v) => !!v || 'E-mail is required',
              (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
            ],
          }
        },
        methods: {
          getUser(){
            axios.get('/api/user').then(response => {
                this.currentUser = response.data;
                // this.$router.push('/dashboard');
            }).catch(errors => {
                console.log(errors);
            });
          },
          submit () {
            if (this.$refs.form.validate()) {
              this.isLoading = "white"
              // this.$refs.form.$el.submit()
              axios.post("/api/login", this.form).then(response => {
                if(response.data.status == true){
                  console.log("Nama token "+response.data.token)
                  this.$store.commit('updateToken', response.data.token)
                  this.teksSnackbar= "Berhasil login "
                  this.warnaSnackbar= "primary",
                  this.snackbar = true
                  this.clear()
                  this.$router.push('/')
                }else{
                  this.teksSnackbar= 'Gagal login'
                  this.warnaSnackbar= red
                  this.snackbar = true
                }
                
              }).catch(errors => {
                this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors),
                this.warnaSnackbar= "red",
                this.snackbar = true
              }).finally(() => {
                this.isLoading = false
              })
            }
          },
          clear () {
            this.$refs.form.reset()
          }
        },
        mounted() {
          // this.getUser();
          if(localStorage.getItem('token')){
            this.$router.push('/')
          }
        },
    }
</script>
<style scoped>
#inspire {
  background: url('/other/pattern.png') !important;
  background-size: cover;
}
</style>
