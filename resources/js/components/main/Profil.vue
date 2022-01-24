<template>
  <v-container fluid>
    <v-overlay :value="overlay" style="z-index:7000;">
      <v-progress-circular
        indeterminate
        size="64"
      ></v-progress-circular>
      <p class="text-center">
        Loading . . .
      </p>
    </v-overlay>
    <v-layout column>
        <v-card class="mx-auto" tile>
          <v-img height="200" width="80vw" src="./other/account-pattern.png">
            <v-row style="margin:2.5%;position:absolute;">
              <v-list-item>
               <v-list-item-avatar size="100">
                  <img
                    src="https://www.w3schools.com/howto/img_avatar.png"
                    alt="John"
                  >
                </v-list-item-avatar>
              </v-list-item>
            </v-row>
            <v-row style="margin:2.5%;position:absolute; top: 100px">
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title class="title" style="">{{ this.name }}</v-list-item-title>
                  <v-list-item-subtitle>{{ this.email}} | {{ this.username }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-row>
          </v-img>
          <v-tabs
            v-model="tab"
            background-color="transparent"
            grow
          >
            <v-tab>
              Info Akun
            </v-tab>
            <v-tab>
              Ganti Password
            </v-tab>
            <v-tab>
              Status
            </v-tab>
          </v-tabs>

          <v-tabs-items v-model="tab">
            <v-tab-item>
              <v-card-text>
                <v-text-field
                    v-model="form.name"
                    label="Nama"></v-text-field>
                <v-text-field
                    v-model="form.username"
                    label="Username"
                    disabled></v-text-field>
                <v-text-field
                    v-model="form.email"
                    disabled
                    label="Alamat email"></v-text-field>
              </v-card-text>
              <v-card-actions>
                  <v-btn color="primary" :loading="loading_info" @click.native="updateInfo">
                      <v-icon left dark>mdi-check</v-icon>
                      Save Changes
                  </v-btn>
              </v-card-actions>
            </v-tab-item>
            <v-tab-item>
              <v-card-text>
                <v-text-field
                    v-model="password.old"
                    label="Password Lama"
                    prepend-icon="mdi-lock"
                    :append-icon="show_password_old ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="show_password_old ? 'text' : 'password'"
                    counter
                    @click:append="show_password_old = !show_password_old"></v-text-field>
                <v-text-field
                    v-model="password.new"
                    label="Password Baru"
                    prepend-icon="mdi-lock"
                    :append-icon="show_password ? 'mdi-eye' : 'mdi-eye-off'"
                    :type="show_password ? 'text' : 'password'"
                    counter
                    @click:append="show_password = !show_password"></v-text-field>
                <v-text-field
                    v-model="password.check"
                    label="Password Baru check"
                    type="password"
                    prepend-icon="mdi-lock-question"
                    counter></v-text-field>
              </v-card-text>
              <v-card-actions>
                  <v-btn color="tertiary" :loading="loading_password" :disabled="!password_confirmation" @click.native="updatePassword">
                      <v-icon left dark>mdi-check</v-icon>
                      Send Changes
                  </v-btn>
              </v-card-actions>
            </v-tab-item>
            <v-tab-item>
              <v-card-text>
                <div v-if="permissions.length > 0" style="margin-top:10px;">
                  <v-chip
                      v-for="(permission, index) in permissions"
                      :key="index"
                      color="primary darken--2"
                      medium
                      style="margin:5px;"
                    >
                    <span class="pr-2">
                      {{ permission }}
                    </span>
                  </v-chip>
                </div>
              </v-card-text>
            </v-tab-item>
          </v-tabs-items>
      </v-card>
    </v-layout>
    <v-snackbar
        v-model="snackbar"
        :multi-line="multiLine"
        top
        :color= warnaSnackbar
        style="z-index:7001">  
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
  </v-container>
</template>

<script>
    import axios from 'axios'
    export default {
    props: {
      source: String,
    },
    data () {
        return {
          token: this.$store.getters.token,
          url_base: this.$store.getters.url_base,
          email: '',
          name : '',
          username: '',
          loading_info:false,
          loading_password:false,
          overlay:true,
          tab:null,
          teksSnackbar:'',
          warnaSnackbar:"blue",
          snackbar:false,
          multiLine:true,
          form: {
              name: '',
              username: '',
              email: '',
              photos: 'MALE_CAUCASIAN_BLOND_BEARD'
          },
          password:{
            old:'',
            new:'',
            check:'',
          },
          show_password: false,
          show_password_old:false,
          currentUser:{},
          permissions:[],
        }
      },
    methods: {
      async initData(){
        var data = []
        this.loading = true;
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        await axios.get('/api/user-permission')
        .then((response) => {
          //Then injecting the result to datatable parameters.

          this.loading = true
          // this.locations = response.data
          this.name = response.data.user.name;
          this.username = response.data.user.username;
          this.email = response.data.user.email;
          this.form = response.data.user
          this.permissions = response.data.permissions
        }).catch(errors => {
          // this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data.message)
          // // console.log(errors.response.data)
          // this.warnaSnackbar= "red"
          // this.snackbar = true
        }).finally(() => {
          // this.loading = false
        });
          // this.loading = false
      },
      async updateInfo(){
        // console.log(this.form)
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        await axios.post("/api/change-info", this.form).then(response => {
          // console.log(response.data)
          if(response.data.status == true){
            this.teksSnackbar= response.data.message,
            this.warnaSnackbar= "primary",
            this.snackbar = true
          }else{
            this.teksSnackbar= "Terjadi Kesalahan : "+ (response.data.message),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }
        }).catch(errors => {
          // console.log(errors.response.data.errors)
          this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.message),
          this.warnaSnackbar= "red",
          this.snackbar = true
        }).finally(async () => {
          await this.initData()
        })
        this.overlay = false
      },
      async updatePassword(){
        // console.log(this.form)
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        await axios.post("/api/change-password", this.password).then(response => {
          // console.log(response.data)
          if(response.data.status == true){
            this.teksSnackbar= response.data.message,
            this.warnaSnackbar= "primary",
            this.snackbar = true
            this.reset_password_field()
          }else{
            this.teksSnackbar= "Terjadi Kesalahan : "+ (response.data.message),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }
        }).catch(errors => {
          // console.log(errors.response.data.errors)
          this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.message),
          this.warnaSnackbar= "red",
          this.snackbar = true
        }).finally(async () => {
          await this.initData()
        })
        this.overlay = false
      },
      reset_password_field(){
        this.password.old = ''
        this.password.new = ''
        this.password.check = ''
      }
    },
    computed:{
      password_confirmation(){
        return (this.password.new == this.password.check) && (this.password.new).length >= 8 && (this.password.old != this.password.new)
      },
    },
    async mounted () {
      await this.initData();
      this.overlay = false
    },
    created () {
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
    }
  }
</script>
