<template>
  <v-container>
  <v-overlay :value="overlay" style="z-index:7100;content-align:center;text-align:center;">
    <v-progress-circular
      indeterminate
      size="64"
    ></v-progress-circular>
    <p class="text-center">
      {{ overlay_text }}
    </p>
  </v-overlay>
  <!-- DIALOG UNTUK KONFIRMASI HAPUS -->
  <v-dialog v-model="dialogUserCreate" persistent style="z-index:2001;">
    <v-card>
      <v-card-title class="text-h5">
        Register User
      </v-card-title>
      <v-container>
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
                name="password"
                label="Password"
                hint="At least 8 characters"
                counter
                @click:append="show1 = !show1"
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
              color="primary"
              :disabled="invalid"
            >
              submit
            </v-btn>
            
            <v-btn @click="clear_form">
              reset
            </v-btn>

            <v-btn @click="dialogUserCreate = false">
              close
            </v-btn>
          </form>
        </validation-observer>
      </v-container>
    </v-card>
  </v-dialog>
  <v-dialog v-model="dialogUserRBAC" style="z-index:2002;">
    <!-- <v-container> -->
      <v-data-table
        v-model="permission_selected"
        :headers="permission_headers"
        :items="permission_items"
        :single-select="permission_singleSelect"
        :items-per-page="5"
        item-key="id"
        show-select
        class="elevation-1">
        <template v-slot:top>
          <v-row class="mx-2">
          <h2 class="my-6 mx-4">RBAC</h2>
          <v-switch
            v-model="permission_singleSelect"
            label="Single select"
            class="pa-3"
          ></v-switch>
          <v-spacer></v-spacer>
          <v-btn
            class="mx-8 my-8"
            color="primary"
            @click="submitRBAC"
          >
            submit
          </v-btn>
        </v-row>
          
          <!-- <v-row> -->
            <!-- <v-spacer></v-spacer>
            <v-btn
              class="mx-8 my-8"
              color="primary"
              @click="submitRBAC"
            >
              submit
            </v-btn> -->
        <!-- </v-row> -->

        </template>
      </v-data-table>
      
    <!-- </v-container> -->
  </v-dialog>

  <!-- Isi menu -->
  <v-data-table
    :headers="headers"
    :items="users"
    :search="search_users"
    item-key="name"
    class="elevation-1"
    sort-by="name">
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>Daftar Pengguna</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-btn
          class="mx-2 my-2"
          fab
          dark
          small
          @click="createUser()"
          color="primary"
        >
          <v-icon dark>
            mdi-plus
          </v-icon>
        </v-btn>

        <v-dialog
          v-model="dialog"
          max-width="60vw"
        >
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.id"
                      label="ID"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.name"
                      label="Nama"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.email"
                      label="Email"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

<!--             <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="blue darken-1"
                text
                @click="close"
              >
                Cancel
              </v-btn>
              <v-btn
                color="blue darken-1"
                text
                @click="save"
              >
                Save
              </v-btn>
            </v-card-actions> -->
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="75vw">
          <v-card>
            <v-card-title class="headline">Apakah ingin melakukan pemberhentian akses untuk user ini ({{editedItem.name}})?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="red darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogInfo" max-width="60vw">
          <v-card>
            <v-card-title class="headline">Informasi Petugas</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
      <v-toolbar flat>
        <v-text-field
        v-model="search_users"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      
      <div v-if='item.status_account == "active"'>
        <v-icon
        medium
        class="ma-3"
        color="primary"
        @click="editItem(item)"
      >
        mdi-information-outline
      </v-icon>
      <v-icon
        medium
        class="ma-3"
        color="red"
        @click="deleteItem(item)"
      >
        mdi-delete
      </v-icon>
      <v-icon
        medium
        class="ma-3"
        color="secondary"
        @click="rbacItem(item)"
      >
        mdi-application-cog
      </v-icon>
    </div>
    <div v-else>
      <v-icon
        medium
        class="ma-3"
        color="primary"
        @click="editItem(item)"
      >
        mdi-information-outline
      </v-icon>
      <v-icon
        medium
        class="ma-3"
        color="secondary"
        @click="activate(item)">
        mdi-lock-open-alert
      </v-icon>
    </div>
    </template>
    <template v-slot:no-data>
      <v-btn
        color="primary"
        @click="initialize"
      >
        Reset
      </v-btn>
    </template>
  </v-data-table>
  <v-snackbar
      v-model="snackbar"
      :multi-line="multiLine"
      top
      :color= warnaSnackbar
      style="z-index:2100">  
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
  // script untuk validasi
  import { required, digits, email, max, regex, min, image, size, ext } from 'vee-validate/dist/rules'
  import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
  import axios from 'axios'

  setInteractionMode('eager')

  extend('digits', {
    ...digits,
    message: '{_field_} needs to be {length} digits. ({_value_})',
  })

  extend('ext', {
    ...ext,
    message: '{_field_} harus berekstensi xlsx',
  })

  extend('image', {
    ...image,
    message: '{_field_} harus berupa gambar',
  })

  extend('size', {
    ...size,
    message: '{_field_} harus berukuran kurang dari 20M size.',
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

  export default {
    components: {
      ValidationProvider,
      ValidationObserver,
    },
    // props: ['id'],
    name:'DetailKegiatan',
    data() {
      return {
        form: {
          name: '',
          username: '',
          email: '',
          password: '',
          password_confirmation: '',
        },
        search_users:'',
        show1:false,
        currentUser: {},
        overlay: true,
        overlay_text: 'Loading  . . .',
        singleExpand: false,
        drawer: false,
        dialog: false,
        dialogUserCreate: false,
        dialogUserRBAC:false,
        expanded:[],
        dialogDelete: false,
        dialogInfo: false,
        token: this.$store.getters.token,
        url_base: this.$store.getters.url_base,
        isLoggedIn : this.$store.getters.isLoggedIn,
        permissions : [],
        headers: [
          {
            text: 'Nama Pengguna',
            align: 'start',
            sortable: false,
            value: 'name',
          },
          { text: 'Email', value: 'email' },
          { text: 'Terdaftarkan', value: 'created_at' },
          { text: 'Status', value: 'status_account'},
          { text: 'Actions', value: 'actions', sortable: false, width: '20%' },
        ],
        users: [],
        editedIndex: -1,
        editedItem: {
          name: '',
          email: 0,
          id: 0,
        },
        defaultItem: {
          name: '',
          email: 0,
          id: 0,
        },
        teksSnackbar:'',
        warnaSnackbar:'primary',
        snackbar:false,
        multiLine:true,
        rules: {
          required: value => !!value || 'Required.',
          min: v => v.length >= 8 || 'Min 8 characters',
          emailMatch: () => (`The email and password you entered don't match`),
        },
        user:{
          name:'',
          username:'',
          email:'',
          permissions:null,
        },
        permission_singleSelect: false,
        permission_selected: [],
        permission_headers: [
          // {
          //   text: 'ID',
          //   align: 'start',
          //   sortable: false,
          //   value: 'id',
          // },
          { text: 'Name', value: 'name' },
          { text: 'Didaftarkan', value: 'created_at' },
        ],
        permission_items: [],
    }},

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Pendaftaran Baru' : 'Data Pengguna'
      },
    },

    watch: {
      dialog (val) {
        val || this.close()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
    },

    async created () {
      this.overlay = true
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      await axios.get('/api/user-permission').then(response => {
          this.currentUser = response.data.user;
          this.$store.commit('updateRBAC', response.data.permissions)
          if(!(response.data.permissions).includes('users.create')){
            this.$router.push('/')
          }
      }).catch(errors => {
          console.log(errors);
      }).finally(() => {
          this.permissions = this.$store.getters.rbac
      })
    },

    async mounted () {
      await this.initialize();
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      setTimeout(() => this.overlay = false, 1500);
      this.overlay = false
    },

    methods: {
      async initialize () {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        await axios.get('/api/users').then(response => {
            this.users = response.data
        }).catch(errors => {
          console.log(errors)
        });
      },
      activate (item){
        alert(item)
      },

      async submit () {
        this.$refs.observer.validate()
        this.overlay = true
        this.isLoading = "white"
        await axios.post("/api/register", this.form).then(response => {
          this.dialogUserCreate = false
          console.log(response.data)
          this.teksSnackbar= "Berhasil menambahkan akun "
          this.warnaSnackbar= "primary"
          this.clear_form()
        }).catch(errors => {
          console.log(errors.response.data.errors)
          this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"]),
          this.warnaSnackbar= "red"
        }).finally(async () => {
          this.isLoading = false
          await this.initialize()
          this.overlay = false
          this.snackbar = true
        })
      },
      clear_form () {
        this.form.name = ''
        this.form.username = ''
        this.form.email = ''
        this.form.password = ''
        this.form.password_confirmation = ''
        this.form.roles = null
        this.$refs.observer.reset()
      },

      createUser(){
        // this.news.id_entri=''
        // this.news.judul_artikel=''
        // this.news.jenis_artikel=null
        // this.news.teks_pembuka_artikel=''
        // this.news.teks_isi_artikel= ''
        // this.news.tanggal_artikel=''
        // this.news.geolocation_artikel=''
        // this.news.gambar_pembuka_artikel=null
        // this.news.lampiran_artikel=null
        // this.news.tags_artikel=[]
        this.dialogUserCreate = true
        var data = []
      },

      editItem (item) {
        this.editedIndex = this.users.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      infoItem (item) {
        this.editedIndex = this.users.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogInfo = true
      },

      deleteItem (item) {
        this.editedIndex = this.users.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
      },

      deleteItemConfirm () {
        this.users.splice(this.editedIndex, 1)
        this.closeDelete()
      },

      async rbacItem(item){
        // console.log(item);
        this.overlay = true
        var refs = this
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        await axios.get('/api/get-permissions/'+item.id).then(response => {
          refs.user = item
          if(response.data.status == true){
            refs.user.permissions = response.data
            refs.permission_items = response.data.available
            refs.permission_selected = response.data.taken
            console.log(response.data)
            this.dialogUserRBAC = true
          }else{
            this.teksSnackbar= "Terjadi Kesalahan : "+ (JSON.stringify(response.data.message)),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }
        }).catch(errors => {
          console.log(errors)
        }).finally(() => {
          this.isLoading = false
          this.overlay = false
        })
      },

      async submitRBAC(){
        // console.log(this.permission_selected)
        var permissions_temp = [];
        for (const val of this.permission_selected) { // You can use `let` instead of `const` if you like
            // console.log(val['name']);
            permissions_temp.push(val['name']);
        };

        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }

        // declare form data
        var formData = new FormData();
        formData.append('permissions', JSON.stringify(permissions_temp));
        this.overlay = true
        this.isLoading = "white"
        // console.log(JSON.stringify(permissions_temp));
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        console.log("/api/add-permissions/"+this.user.id, formData, config);
        await axios.post("/api/add-permissions/"+this.user.id, formData, config).then(response => {
          this.dialogUserCreate = false
          console.log(response.data)
          if(response.data.status == true){
            this.teksSnackbar= "Berhasil mengedit permission ",
            this.warnaSnackbar= "primary"
          }else{
            this.teksSnackbar= "Terjadi Kesalahan : "+ (JSON.stringify(response.data)),
            this.warnaSnackbar= "red"
          }
        }).catch(errors => {
          console.log(errors)
          this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.message),
          this.warnaSnackbar= "red"
        }).finally(async () => {
          this.isLoading = false
          await this.initialize()
          this.overlay = false
          this.snackbar = true
        })
      },

      close () {
        this.dialog = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },

      async save () {
        if (this.editedIndex > -1) {
          Object.assign(this.users[this.editedIndex], this.editedItem)
          await axios.post("/api/structural_edit", this.editedItem).then(response => {
            console.log(response.data)
            // this.teksSnackbar= "Berhasil login ",
            // this.warnaSnackbar= "primary",
            // this.snackbar = true
            // this.clear()
          }).catch(errors => {
            console.log(errors.response.data.errors)
            // this.errors = errors.response.data.errors
          }).finally(async () => {
            this.isLoading = false
            await this.initialize()
            this.close()
          })
        } else {
          this.users.push(this.editedItem)
        }
      },
    },
  }
</script>