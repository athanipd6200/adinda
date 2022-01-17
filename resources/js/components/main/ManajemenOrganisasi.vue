<template>
  <v-container>
  <v-overlay :value="overlay" style="z-index:7100;">
    <v-progress-circular
      indeterminate
      size="64"
    ></v-progress-circular>
    <p class="text-center">
      Loading . . .
    </p>
  </v-overlay>
  <!-- DIALOG UNTUK Organisasi -->
  <v-dialog v-model="dialog_organisasi" persistent style="z-index:2001;">
    <v-card>
      <v-card-title class="text-h5">
        <v-icon>mdi-office-building"</v-icon>{{ organisasiTitle }}
        <!-- {{organisasi_edit_mode ? 'Edit Profil Organisasi' : 'Daftarkan Profile Organisasi'}} -->
      </v-card-title>
      <v-card-text>
        <validation-observer
          ref="observer_organisasi"
          v-slot="{ invalid }"
        >
          <form @submit.prevent="submit">
            <!-- Nama Organisasi-->
            <validation-provider
              v-slot="{ errors }"
              name="Nama"
              rules="required|min:5"
            >
              <v-text-field
                v-model="organisasi.nama_organisasi"
                :error-messages="errors"
                label="Nama Organisasi"
                prepend-icon="mdi-office-building"
                required
              ></v-text-field>
            </validation-provider>

            <!-- Alamat Organisasi -->
            <validation-provider
              v-slot="{ errors }"
              name="Alamat"
              rules="required|min:20"
            >
              <v-text-field
                v-model="organisasi.alamat_organisasi"
                :error-messages="errors"
                label="Alamat Organisasi"
                prepend-icon="mdi-map-marker"
                required
              ></v-text-field>
            </validation-provider>

            <!-- Keterangan Organisasi -->
            <validation-provider
              v-slot="{ errors }"
              name="Keterangan Organisasi"
            >
              <v-text-field
                v-model="organisasi.keterangan_organisasi"
                :error-messages="errors"
                label="Keterangan Organisasi"
                prepend-icon="mdi-note"
              ></v-text-field>
            </validation-provider>

            <!-- Logo organisasi -->
            <!-- <validation-provider
              v-slot="{ errors }"
              name="Logo Organisasi"
              v-if="!organisasi_edit_mode"
            >
              <v-file-input
                accept="image/*"
                label="File gambar logo organisasi"
                v-model="organisasi.logo_organisasi"
                :error-messages="errors"
                prepend-icon="mdi-image"
                chips
                show-size
                v-if="!organisasi_edit_mode"
              ></v-file-input>
            </validation-provider> -->

            <validation-provider
              v-slot="{ errors }"
              name="Logo Organisasi Baru"
            >
              <v-file-input
                accept="image/*"
                label="File gambar logo organisasi baru"
                v-model="organisasi.logo_organisasi_new"
                :error-messages="errors"
                prepend-icon="mdi-image-plus"
              ></v-file-input>
            </validation-provider>

            <v-btn
              class="mr-4"
              color="primary"
              @click="submitCreateDataOrganisasi"
              :disabled="invalid"
              v-if="!organisasi_edit_mode"
            >
              submit create
            </v-btn>
            <v-btn
              class="mr-4"
              color="primary darken-3"
              @click="submitEditDataOrganisasi"
              :disabled="invalid"
              v-if="organisasi_edit_mode"
            >
              submit edit
            </v-btn>
            <v-btn @click="clearFormOrganisasi" v-if="!organisasi_edit_mode">
              reset
            </v-btn>

            <v-btn @click="dialog_organisasi = false">
              close
            </v-btn>
          </form>
        </validation-observer>
      </v-card-text>
    </v-card>
  </v-dialog>

  <!-- DIALOG UNTUK Divisi -->
  <v-dialog v-model="dialog_divisi" persistent style="z-index:2001;">
    <v-card>
      <v-card-title class="text-h5">
        <v-icon>mdi-home-modern"</v-icon>{{ divisiTitle }}
        <!-- {{divisi_edit_mode ? 'Edit Profil Divisi' : 'Daftarkan Profile Divisi'}} -->
      </v-card-title>
      <v-card-text>
        <validation-observer
          ref="observer_divisi"
          v-slot="{ invalid }"
        >
          <form @submit.prevent="submit">

            <!-- Nama Organisasi -->
            <validation-provider
              v-slot="{ errors }"
              name="Nama Organisasi"
              rules="required"
            >
              <v-select
                v-model="divisi.id_organisasi"
                :items="items"
                item-text="nama_organisasi"
                item-value="id_organisasi"
                label="Pilih Organisasi"
                prepend-icon="mdi-office-building"
                required
              ></v-select>
            </validation-provider>

            <!-- Nama Divisi-->
            <validation-provider
              v-slot="{ errors }"
              name="Nama"
              rules="required|min:5"
            >
              <v-text-field
                v-model="divisi.nama_divisi"
                :error-messages="errors"
                label="Nama Divisi"
                prepend-icon="mdi-home-modern"
                required
              ></v-text-field>
            </validation-provider>

            <!-- Alamat Divisi -->
            <validation-provider
              v-slot="{ errors }"
              name="Lokasi"
              rules="min:20"
            >
              <v-text-field
                v-model="divisi.alamat_divisi"
                :error-messages="errors"
                label="Lokasi Divisi"
                prepend-icon="mdi-map-marker"
              ></v-text-field>
            </validation-provider>

            <!-- Keterangan Divisi -->
            <validation-provider
              v-slot="{ errors }"
              name="Keterangan Divisi"
            >
              <v-text-field
                v-model="divisi.keterangan_divisi"
                :error-messages="errors"
                label="Keterangan Divisi"
                prepend-icon="mdi-note"
              ></v-text-field>
            </validation-provider>

            <validation-provider
              v-slot="{ errors }"
              name="Logo Divisi Baru"
            >
              <v-file-input
                accept="image/*"
                label="File gambar logo divisi baru"
                v-model="divisi.logo_divisi_new"
                :error-messages="errors"
                prepend-icon="mdi-image-plus"
              ></v-file-input>
            </validation-provider>

            <v-btn
              class="mr-4"
              color="primary"
              @click="submitCreateDataDivisi"
              :disabled="invalid"
              v-if="!divisi_edit_mode"
            >
              submit create
            </v-btn>
            <v-btn
              class="mr-4"
              color="primary darken-3"
              @click="submitEditDataDivisi"
              :disabled="invalid"
              v-if="divisi_edit_mode"
            >
              submit edit
            </v-btn>
            <v-btn @click="clearFormDivisi" v-if="!divisi_edit_mode">
              reset
            </v-btn>

            <v-btn @click="dialog_divisi = false">
              close
            </v-btn>
          </form>
        </validation-observer>
      </v-card-text>
    </v-card>
  </v-dialog>

  <!-- DIALOG UNTUK Divisi -->
  <v-dialog v-model="dialog_tim" persistent style="z-index:2001;">
    <v-card>
      <v-card-title class="text-h5">
        <v-icon>mdi-home-modern"</v-icon>{{ timTitle }}
        <!-- {{tim_edit_mode ? 'Edit Profil Tim' : 'Daftarkan Profile Tim'}} -->
      </v-card-title>
      <v-card-text>
        <validation-observer
          ref="observer_tim"
          v-slot="{ invalid }"
        >
          <form @submit.prevent="submit">

            <!-- Nama Organisasi -->
            <validation-provider
              v-slot="{ errors }"
              name="Nama Organisasi"
              rules="required"
            >
              <v-select
                v-model="tim.id_organisasi"
                :items="items"
                item-text="nama_organisasi"
                item-value="id_organisasi"
                label="Pilih Organisasi"
                prepend-icon="mdi-office-building"
                required
              ></v-select>
            </validation-provider>

            <!-- Nama Divisi -->
            <validation-provider
              v-slot="{ errors }"
              name="Nama Divisi"
            >
              <v-select
                v-model="tim.id_divisi"
                :items="items"
                item-text="nama_divisi"
                item-value="id_divisi"
                label="Pilih Divisi (dapat kosong)"
                prepend-icon="mdi-modern-home"
              ></v-select>
            </validation-provider>

            <!-- Nama Tim-->
            <validation-provider
              v-slot="{ errors }"
              name="Nama"
              rules="required|min:5"
            >
              <v-text-field
                v-model="tim.nama_tim"
                :error-messages="errors"
                label="Nama Tim"
                prepend-icon="mdi-account-multiple"
                required
              ></v-text-field>
            </validation-provider>

            <!-- Alamat Tim -->
            <validation-provider
              v-slot="{ errors }"
              name="Lokasi"
              rules="min:20"
            >
              <v-text-field
                v-model="tim.alamat_tim"
                :error-messages="errors"
                label="Lokasi Tim"
                prepend-icon="mdi-map-marker"
              ></v-text-field>
            </validation-provider>

            <!-- Keterangan Tim -->
            <validation-provider
              v-slot="{ errors }"
              name="Keterangan Tim"
            >
              <v-text-field
                v-model="tim.keterangan_tim"
                :error-messages="errors"
                label="Keterangan Tim"
                prepend-icon="mdi-note"
              ></v-text-field>
            </validation-provider>

            <validation-provider
              v-slot="{ errors }"
              name="Logo Tim Baru"
            >
              <v-file-input
                accept="image/*"
                label="File gambar logo tim baru"
                v-model="tim.logo_tim_new"
                :error-messages="errors"
                prepend-icon="mdi-image-plus"
              ></v-file-input>
            </validation-provider>

            <v-btn
              class="mr-4"
              color="primary"
              @click="submitCreateDataTim"
              :disabled="invalid"
              v-if="!tim_edit_mode"
            >
              submit create
            </v-btn>
            <v-btn
              class="mr-4"
              color="primary darken-3"
              @click="submitEditDataTim"
              :disabled="invalid"
              v-if="tim_edit_mode"
            >
              submit edit
            </v-btn>
            <v-btn @click="clearFormTim" v-if="!tim_edit_mode">
              reset
            </v-btn>

            <v-btn @click="dialog_tim = false">
              close
            </v-btn>
          </form>
        </validation-observer>
      </v-card-text>
    </v-card>
  </v-dialog>
  <!-- DATATABLE -->
  <v-data-table
    :headers="headers"
    :items="items"
    :search="search_value"
    item-key="id_entri"
    class="elevation-1"
    sort-by="name_organisasi">
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>Daftar Organisasi Divisi Tim</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-menu
            bottom
            :close-on-content-click="true">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                  color="primary darken-3"
                  class="white--text"
                  fab
                  v-bind="attrs"
                  v-on="on">
                  <v-icon>
                    mdi-plus
                  </v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item >
                <v-btn
                  @click="createDataOrganisasi"
                  color="primary darken-3"
                  class="white--text">
                  Buat Organisasi
                </v-btn>
              </v-list-item>
              <v-list-item >
                <v-btn
                  @click="createDataDivisi"
                  color="primary darken-3"
                  class="white--text">
                  Buat Divisi
                </v-btn>
              </v-list-item>
              <v-list-item >
                <v-btn
                  @click="createDataTim"
                  color="primary darken-3"
                  class="white--text">
                  Buat Tim
                </v-btn>
              </v-list-item>
            </v-list>
        </v-menu>
        <v-dialog v-model="dialog_delete" max-width="75vw">
          <v-card>
            <v-card-title class="headline">Apakah ingin melakukan pemberhentian akses untuk user ini ({{status_delete }})?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="dialog_delete = false">Cancel</v-btn>
              <v-btn color="red darken-1" text @click="confirmationDelete">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
      <v-toolbar flat>
        <v-text-field
        v-model="search_value"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
      </v-toolbar>
    </template>
    <template v-slot:item.logo_organisasi="{ item }">
      <!-- <div class="p-2"> -->
        <v-img :src="url_base+'/api/logo_organisasi/'+item.logo_organisasi" :alt="item.logo_organisasi" class="ma-2" contain max-width="100px" height="100px"></v-img>
      <!-- </div> -->
    </template>
    <template v-slot:item.created_at ="{ item }">
      {{ new Date(item.created_at).toUTCString() }}
    </template>
    <template v-slot:item.actions="{ item }">
      <div>
        <v-icon
          medium
          class="ma-3"
          color="primary darken-3"
          @click="editDataOrganisasi(item)"
        >
          mdi-pencil
        </v-icon>
        <!-- <v-icon
          medium
          class="ma-3"
          color="primary"
          @click="editItem(item)"
        >
          mdi-information-outline
        </v-icon> -->
        <v-icon
          medium
          class="ma-3"
          color="red"
          @click="deleteItem(item)"
        >
          mdi-delete
        </v-icon>
      </div>
    </template>
    <template v-slot:no-data>
      NO DATA YET
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
    message: '{_field_} harus sebanyak {length} digits. ({_value_})',
  })

  extend('ext', {
    ...ext,
    message: '{_field_} harus berekstensi {_value_}',
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
    message: '{_field_} tidak boleh kosong',
  })

  extend('max', {
    ...max,
    message: '{_field_} tidak boleh lebih dari {length} characters',
  })

  extend('min', {
    ...min,
    message: '{_field_} tidak boleh kurang dari {length} characters',
  })

  extend('regex', {
    ...regex,
    message: '{_field_} {_value_} tidak sama dengan {regex}',
  })

  extend('email', {
    ...email,
    message: 'Email harus berupa email valid',
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
        token: this.$store.getters.token,
        url_base: this.$store.getters.url_base,
        isLoggedIn : this.$store.getters.isLoggedIn,
        overlay: true,
        teksSnackbar:'',
        warnaSnackbar:'primary',
        snackbar:false,
        multiLine:true,
        permissions : [],
        tim: {},
        organisasi_edit_mode: true,
        divisi_edit_mode: true,
        tim_edit_mode: true,
        dialog_organisasi: false,
        dialog_divisi: false,
        dialog_tim: false,
        dialog_delete: false,
        status_delete: '',
        search_value:'',
        headers: [
          {
            text: 'Logo Organisasi',
            value: 'logo_organisasi',
            sortable: false,
          },
          {
            text: 'Nama Organisasi',
            align: 'start',
            value: 'nama_organisasi',
          },
          { text: 'Alamat', value: 'alamat_organisasi' },
          { text: 'Terdaftarkan', value: 'created_at' },
          { text: 'Actions', value: 'actions', sortable: false, width: '200px' },
        ],
        items: [],
        edited_index_organisasi: -1,
        organisasi: {
          id_organisasi: '',
          nama_organisasi: '',
          alamat_organisasi: '',
          keterangan_organisasi: '',
          logo_organisasi_new: null,
          logo_organisasi: '',
        },
        default_item_organisasi: {
          nama_organisasi: '',
          alamat_organisasi: '',
          keterangan_organisasi: '',
          logo_organisasi_new: null,
          logo_organisasi: '',
        },
        organisasis: [],
        divisis: [],
        edited_index_divisi: -1,
        divisi: {
          id_organisasi: '',
          id_divisi: '',
          nama_divisi: '',
          alamat_divisi: '',
          keterangan_divisi: '',
          logo_divisi_new: null,
          logo_divisi: '',
        },
        default_item_divisi: {
          id_organisasi: '',
          nama_divisi: '',
          alamat_divisi: '',
          keterangan_divisi: '',
          logo_divisi_new: null,
          logo_divisi: '',
        },
        edited_index_tim: -1,
        tim: {
          id_organisasi: '',
          id_divisi: '',
          id_tim: '',
          nama_tim: '',
          alamat_tim: '',
          keterangan_tim: '',
          logo_tim_new: null,
          logo_tim: '',
        },
        default_item_tim: {
          id_organisasi: '',
          id_divisi: '',
          nama_tim: '',
          alamat_tim: '',
          keterangan_tim: '',
          logo_tim_new: null,
          logo_tim: '',
        },
    }},

    computed: {
      organisasiTitle () {
        return this.edited_index_organisasi === -1 ? 'Pendaftaran Profil Organisasi' : 'Edit Profile Organisasi'
      },
      divisiTitle () {
        return this.edited_index_divisi === -1 ? 'Pendaftaran Profil Divisi' : 'Edit Profile Divisi'
      },
      timTitle () {
        return this.edited_index_tim === -1 ? 'Pendaftaran Profil Tim' : 'Edit Profile Tim'
      },
    },

    watch: {
      dialog_organisasi (val) {
        val || this.close_dialog_organisasi()
      },
      dialog_divisi (val) {
        val || this.close_dialog_divisi()
      },
      dialog_tim (val) {
        val || this.close_dialog_tim()
      },
    },

    async created () {
      this.overlay = true;
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
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      await this.initialize()
      this.overlay = false
    },

    methods: {
      async initialize () {
        this.overlay = true;
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        await axios.get('/api/read-organisasi').then(response => {
            if(response.data.status == true){
              this.items = response.data.data
              this.organisasis = response.data.data
            }else{
              console.log(response.data.message)
            }
        }).catch(errors => {
            console.log(errors.response.data.errors)
        });
      },
      close_dialog_delete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },
      confirmationDelete(){},
      // fungsi terkait untuk pengelolaan organisasi
      createDataOrganisasi(){
        this.overlay = true
        this.edited_index_organisasi = -1
        this.organisasi = Object.assign({}, this.default_item_organisasi)
        this.organisasi_edit_mode = false;
        this.dialog_organisasi = true;
        this.overlay = false
      },
      editDataOrganisasi(item){
        this.overlay = true
        // console.log(this.organisasi)
        // console.log(this.items)
        this.edited_index_organisasi = this.items.indexOf(item)
        this.organisasi = Object.assign({}, item)
        this.organisasi_edit_mode = true;
        this.dialog_organisasi = true;
        this.overlay = false
      },
      async submitCreateDataOrganisasi() {
        let status_form = this.$refs.observer_organisasi.validate()
        this.overlay = true
        this.isLoading = "white"
        if(status_form){
          const config = {
              headers: {
                  'Content-Type': 'multipart/form-data',
              }
          }
          var formData = new FormData();
          // menambahkan data inovation kedalam form untuk di upload
          formData.append('nama_organisasi', this.organisasi.nama_organisasi);
          formData.append('alamat_organisasi', this.organisasi.alamat_organisasi);
          formData.append('keterangan_organisasi', this.organisasi.keterangan_organisasi);
          formData.append('logo_organisasi_new', this.organisasi.logo_organisasi_new);
          await axios.post("/api/create-organisasi",  formData, config).then(response => {
            if(response.data.status = true){
              this.teksSnackbar= response.data.message
              this.warnaSnackbar= "primary darken-2"
            }else{
              this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"])
              this.warnaSnackbar= "red"
            }
          }).catch(errors => {
            this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"]),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }).finally(async () => {
            await this.initialize()
            this.close_dialog_organisasi()
            this.overlay = false
            this.snackbar = true
          })
        }else{
          this.overlay = false
          this.teksSnackbar= "Terjadi Kesalahan form",
          this.warnaSnackbar= "error",
          this.snackbar = true
        }
      },
      async submitEditDataOrganisasi() {
        let status_form = this.$refs.observer_organisasi.validate()
        this.overlay = true
        this.isLoading = "white"
        // console.log('Masuk edit data')
        if(status_form){
          // console.log('Start edit data')
          const config = {
              headers: {
                  'Content-Type': 'multipart/form-data',
              }
          }
          var formData = new FormData();
          // menambahkan data inovation kedalam form untuk di upload
          formData.append('id_organisasi', this.organisasi.id_organisasi);
          formData.append('nama_organisasi', this.organisasi.nama_organisasi);
          formData.append('alamat_organisasi', this.organisasi.alamat_organisasi);
          formData.append('keterangan_organisasi', this.organisasi.keterangan_organisasi);
          formData.append('logo_organisasi', this.organisasi.logo_organisasi);
          formData.append('logo_organisasi_new', this.organisasi.logo_organisasi_new);
          // console.log(formData)
          await axios.post("/api/update-organisasi",  formData, config).then(response => {
            if(response.data.status = true){
              this.teksSnackbar= response.data.message
              this.warnaSnackbar= "primary darken-2"
            }else{
              this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.message)
              this.warnaSnackbar= "red"
            }
          }).catch(errors => {
            this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"]),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }).finally(async () => {
            await this.initialize()
            this.close_dialog_organisasi()
            this.overlay = false
            this.snackbar = true
          })
        }else{
          this.overlay = false
          this.teksSnackbar= "Terjadi Kesalahan form",
          this.warnaSnackbar= "error",
          this.snackbar = true
        }
      },
      clearFormOrganisasi () {
        this.$nextTick(() => {
          this.organisasi = Object.assign({}, this.default_item_organisasi)
          this.edited_index_organisasi = -1
        })
        // this.$refs.observer_organisasi.reset()
      },
      close_dialog_organisasi () {
        this.dialog_organisasi = false
        this.$nextTick(() => {
          this.organisasi = Object.assign({}, this.default_item_organisasi)
          this.edited_index_organisasi = -1
        })
      },
      // fungsi terkait untuk pengelolaan divisi
      createDataDivisi(){
        this.overlay = true
        this.edited_index_divisi = -1
        this.divisi = Object.assign({}, this.default_item_divisi)
        this.divisi_edit_mode = false;
        this.dialog_divisi = true;
        this.overlay = false
      },
      editDataDivisi(item){
        this.overlay = true
        this.edited_index_divisi = this.items.indexOf(item)
        this.divisi = Object.assign({}, item)
        this.divisi_edit_mode = true;
        this.dialog_divisi = true;
        this.overlay = false
      },
      async submitCreateDataDivisi() {
        let status_form = this.$refs.observer_divisi.validate()
        this.overlay = true
        this.isLoading = "white"
        if(status_form){
          const config = {
              headers: {
                  'Content-Type': 'multipart/form-data',
              }
          }
          var formData = new FormData();
          // menambahkan data inovation kedalam form untuk di upload
          formData.append('nama_divisi', this.divisi.nama_divisi);
          formData.append('id_organisasi', this.divisi.id_organisasi);
          formData.append('alamat_divisi', this.divisi.alamat_divisi);
          formData.append('keterangan_divisi', this.divisi.keterangan_divisi);
          formData.append('logo_divisi_new', this.divisi.logo_divisi_new);
          await axios.post("/api/create-divisi",  formData, config).then(response => {
            if(response.data.status = true){
              this.teksSnackbar= response.data.message
              this.warnaSnackbar= "primary darken-2"
            }else{
              this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"])
              this.warnaSnackbar= "red"
            }
          }).catch(errors => {
            this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"]),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }).finally(async () => {
            await this.initialize()
            this.close_dialog_divisi()
            this.overlay = false
            this.snackbar = true
          })
        }else{
          this.overlay = false
          this.teksSnackbar= "Terjadi Kesalahan form",
          this.warnaSnackbar= "error",
          this.snackbar = true
        }
      },
      async submitEditDataDivisi() {
        let status_form = this.$refs.observer_divisi.validate()
        this.overlay = true
        this.isLoading = "white"
        // console.log('Masuk edit data')
        if(status_form){
          // console.log('Start edit data')
          const config = {
              headers: {
                  'Content-Type': 'multipart/form-data',
              }
          }
          var formData = new FormData();
          // menambahkan data inovation kedalam form untuk di upload
          formData.append('id_divisi', this.divisi.id_divisi);
          formData.append('id_organisasi', this.divisi.id_organisasi);
          formData.append('nama_divisi', this.divisi.nama_divisi);
          formData.append('alamat_divisi', this.divisi.alamat_divisi);
          formData.append('keterangan_divisi', this.divisi.keterangan_divisi);
          // isian logo_divisi berupa string untuk nama yang lama
          formData.append('logo_divisi', this.divisi.logo_divisi);
          // isian logo_divisi_new berupa file
          formData.append('logo_divisi_new', this.divisi.logo_divisi_new);
          // console.log(formData)
          await axios.post("/api/update-divisi",  formData, config).then(response => {
            if(response.data.status = true){
              this.teksSnackbar= response.data.message
              this.warnaSnackbar= "primary darken-2"
            }else{
              this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.message)
              this.warnaSnackbar= "red"
            }
          }).catch(errors => {
            this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"]),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }).finally(async () => {
            await this.initialize()
            this.close_dialog_divisi()
            this.overlay = false
            this.snackbar = true
          })
        }else{
          this.overlay = false
          this.teksSnackbar= "Terjadi Kesalahan form",
          this.warnaSnackbar= "error",
          this.snackbar = true
        }
      },
      clearFormDivisi () {
        this.$nextTick(() => {
          this.divisi = Object.assign({}, this.default_item_divisi)
          this.edited_index_divisi = -1
        })
        // this.$refs.observer_organisasi.reset()
      },
      close_dialog_divisi () {
        this.dialog_divisi = false
        this.$nextTick(() => {
          this.divisi = Object.assign({}, this.default_item_divisi)
          this.edited_index_divisi = -1
        })
      },
      // fungsi terkait untuk pengelolaan tim
      createDataDivisi(){
        this.overlay = true
        this.edited_index_divisi = -1
        this.divisi = Object.assign({}, this.default_item_divisi)
        this.divisi_edit_mode = false;
        this.dialog_divisi = true;
        this.overlay = false
      },
      editDataDivisi(item){
        this.overlay = true
        this.edited_index_divisi = this.items.indexOf(item)
        this.divisi = Object.assign({}, item)
        this.divisi_edit_mode = true;
        this.dialog_divisi = true;
        this.overlay = false
      },
      async submitCreateDataDivisi() {
        let status_form = this.$refs.observer_divisi.validate()
        this.overlay = true
        this.isLoading = "white"
        if(status_form){
          const config = {
              headers: {
                  'Content-Type': 'multipart/form-data',
              }
          }
          var formData = new FormData();
          // menambahkan data inovation kedalam form untuk di upload
          formData.append('nama_divisi', this.divisi.nama_divisi);
          formData.append('id_organisasi', this.divisi.id_organisasi);
          formData.append('alamat_divisi', this.divisi.alamat_divisi);
          formData.append('keterangan_divisi', this.divisi.keterangan_divisi);
          formData.append('logo_divisi_new', this.divisi.logo_divisi_new);
          await axios.post("/api/create-divisi",  formData, config).then(response => {
            if(response.data.status = true){
              this.teksSnackbar= response.data.message
              this.warnaSnackbar= "primary darken-2"
            }else{
              this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"])
              this.warnaSnackbar= "red"
            }
          }).catch(errors => {
            this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"]),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }).finally(async () => {
            await this.initialize()
            this.close_dialog_divisi()
            this.overlay = false
            this.snackbar = true
          })
        }else{
          this.overlay = false
          this.teksSnackbar= "Terjadi Kesalahan form",
          this.warnaSnackbar= "error",
          this.snackbar = true
        }
      },
      async submitEditDataDivisi() {
        let status_form = this.$refs.observer_divisi.validate()
        this.overlay = true
        this.isLoading = "white"
        // console.log('Masuk edit data')
        if(status_form){
          // console.log('Start edit data')
          const config = {
              headers: {
                  'Content-Type': 'multipart/form-data',
              }
          }
          var formData = new FormData();
          // menambahkan data inovation kedalam form untuk di upload
          formData.append('id_divisi', this.divisi.id_divisi);
          formData.append('id_organisasi', this.divisi.id_organisasi);
          formData.append('nama_divisi', this.divisi.nama_divisi);
          formData.append('alamat_divisi', this.divisi.alamat_divisi);
          formData.append('keterangan_divisi', this.divisi.keterangan_divisi);
          // isian logo_divisi berupa string untuk nama yang lama
          formData.append('logo_divisi', this.divisi.logo_divisi);
          // isian logo_divisi_new berupa file
          formData.append('logo_divisi_new', this.divisi.logo_divisi_new);
          // console.log(formData)
          await axios.post("/api/update-divisi",  formData, config).then(response => {
            if(response.data.status = true){
              this.teksSnackbar= response.data.message
              this.warnaSnackbar= "primary darken-2"
            }else{
              this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.message)
              this.warnaSnackbar= "red"
            }
          }).catch(errors => {
            this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"]),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }).finally(async () => {
            await this.initialize()
            this.close_dialog_divisi()
            this.overlay = false
            this.snackbar = true
          })
        }else{
          this.overlay = false
          this.teksSnackbar= "Terjadi Kesalahan form",
          this.warnaSnackbar= "error",
          this.snackbar = true
        }
      },
      clearFormDivisi () {
        this.$nextTick(() => {
          this.divisi = Object.assign({}, this.default_item_divisi)
          this.edited_index_divisi = -1
        })
        // this.$refs.observer_organisasi.reset()
      },
      close_dialog_divisi () {
        this.dialog_divisi = false
        this.$nextTick(() => {
          this.divisi = Object.assign({}, this.default_item_divisi)
          this.edited_index_divisi = -1
        })
      },
    },
  }
</script>