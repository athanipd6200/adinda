L<template>
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
    <!-- DIALOG UNTUK EDIT -->
    <v-dialog v-model="dialogInovationEdit" fullscreen hide-overlay style="z-index:2050" transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar
          dark
          color="secondary">
          <v-btn
            icon
            dark
            @click="closeEditItem()"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>EDIT INOVATION </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn
              dark
              text
              @click="submitEditItem()"
            >
              SUBMIT HASIL EDIT
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-divider></v-divider>
        <v-container>
          <validation-observer
            ref="observer_edit_inovation"
            v-slot="{ invalid }"
          >
            <form @submit.prevent="submit">
              <h2 class="mt-4 secondary--text"><b>Profil Inovation</b></h2>
              <!-- Nama inovasi -->
              <h3><b>Nama Inovasi</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Nama Inovasi"
                rules="required"
              >
                <v-text-field
                  v-model="inovation_edit.nama_inovation"
                  :error-messages="errors"
                  label="Nama Inovasi"
                  required
                ></v-text-field>
              </validation-provider>

              <!-- kalimat pengantar inovation -->
              <h3><b>Dekripsi Singkat/Abstrak Inovasi</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Dekripsi Singkat Inovasi"
                rules="required"
              >
                <v-textarea
                  v-model="inovation_edit.deskripsi_inovation"
                  :error-messages="errors"
                  filled
                  auto-grow
                  rows="4"
                  row-height="30"
                  shaped
                  label="Deskripsi Singkat/Abstrak Inovasi"
                ></v-textarea>
              </validation-provider>

              <!-- Satuan Kerja Asal Inovasi -->
              <h3><b>Satuan Kerja Asal Inovasi</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Satuan Kerja Asal Inovasi"
                rules="required"
              >
                <v-text-field
                  v-model="inovation_edit.satker_asal_inovation"
                  :error-messages="errors"
                  label="Satuan Kerja Asal Inovasi"
                  required
                ></v-text-field>
              </validation-provider>

              <!-- konten inovation -->
              <h3><b>Isi Konten</b></h3>
              <ckeditor :editor="editor" v-model="inovation_edit.konten_inovation" :config="editorConfig" class="mb-4"></ckeditor>

              <!-- Narahubung untuk Inovasi -->
              <h3><b>Narahubung Inovasi</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Narahubung Inovasi"
                rules="required"
              >
                <v-text-field
                  v-model="inovation_edit.kontak_hubung_inovation"
                  :error-messages="errors"
                  label="Narahubung Inovasi"
                  required
                ></v-text-field>
              </validation-provider>

              <!-- tautan materi inovation -->
              <h3><b>Tautan Materi Inovasi</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Tautan Materi Inovasi"
                rules="required"
              >
                <v-text-field
                  v-model="inovation_edit.tautan_materi_inovation"
                  :error-messages="errors"
                  label="Tautan Materi Inovasi"
                  required
                ></v-text-field>
              </validation-provider>

              <!-- tautan kode inovation -->
              <h3><b>Tautan Kode Inovasi</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Tautan Kode Inovasi"
                rules="required"
              >
                <v-text-field
                  v-model="inovation_edit.tautan_kode_inovation"
                  :error-messages="errors"
                  label="Tautan Kode Inovasi"
                  required
                ></v-text-field>
              </validation-provider>

              <!-- ikon inovation -->
              <h3><b>Ikon/Logo Inovation Yang Baru (Jika Ingin Diperbaharui)</b></h3>
              <validation-provider
                v-slot="{ errors }"
                style="z-index:2010;"
                rules="size:20480"

              >
                <v-file-input
                  v-model="inovation_edit.ikon_inovation_new"
                  accept="image/*"
                  chips
                  show-size
                  truncate-length="49"
                  :error-messages="errors"
                  label="Ikon/Logo Inovation"
                  prepend-icon="mdi-image"
                ></v-file-input>
              </validation-provider>
              <h3><b>Ikon/Logo Inovation Yang Ada</b></h3>
              <v-row>
                <v-col
                  class="d-flex child-flex"
                  cols="auto">
                  <v-img 
                    class="grey lighten-2" 
                    style="max-width: 400px;" 
                    :src="imagePreview(inovation_edit.ikon_inovation)"
                    lazy-src="https://picsum.photos/510/300?random">
                    <template v-slot:placeholder>
                          <v-row
                            class="fill-height ma-0"
                            align="center"
                            justify="center"
                          >
                            <v-progress-circular
                              indeterminate
                              color="grey lighten-5"
                            ></v-progress-circular>
                          </v-row>
                        </template>
                  </v-img>
                </v-col>
              </v-row>

              <h3><b>Gambar Pendukung Inovasi Yang Baru</b></h3>
              <!-- image carousel -->
              <validation-provider
                v-slot="{ errors }"
                rules="size:20480"
                style="z-index:2010;">
                  <v-file-input
                    v-model="inovation_edit.gambar_inovation_new"
                    accept="image/*"
                    show-size
                    chips
                    prepend-icon="mdi-camera"
                    label="Silahkan pilih gambar - gambar pilihan untuk di upload"
                    multiple
                  ></v-file-input>
              </validation-provider>

              <h3><b>Gambar Pendukung Inovasi Yang Ada (Jika Ingin Diperbaharui)</b></h3>
              <v-row>
                <v-col
                  v-for="gambar in gambar_inovation_edit_arr()"
                  :key="gambar"
                  class="d-flex child-flex"
                  cols="4"
                >
                  <v-img
                    :src="url_base+'/api/gambar_inovation/'+gambar"
                    lazy-src="https://picsum.photos/510/300?random"
                    class="grey lighten-2"
                  >
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center"
                      >
                        <v-progress-circular
                          indeterminate
                          color="grey lighten-5"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                </v-col>
              </v-row>

            </form>
            <v-divider class="my-4"></v-divider>
          </validation-observer>
        </v-container>
      </v-card>
    </v-dialog>
    <!-- DIALOG UNTUK CREATE DATA -->
    <v-dialog v-model="dialogInovationCreate" fullscreen hide-overlay style="z-index:2052" transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar
          dark
          color="primary">
          <v-btn
            icon
            dark
            @click="closeCreateItem()"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>PROFIL INOVASI </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn
              dark
              text
              @click="submitCreateItem()"
            >
              TAMBAHKAN INOVASI
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-divider></v-divider>
        <v-container>
          <validation-observer
            ref="observer_create_inovation"
            v-slot="{ invalid }"
          >
            <form @submit.prevent="submit">
              <h2 class="mt-4 secondary--text"><b>Profil Inovation</b></h2>
              <!-- Nama inovasi -->
              <h3><b>Nama Inovasi</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Nama Inovasi"
                rules="required"
              >
                <v-text-field
                  v-model="inovation.nama_inovation"
                  :error-messages="errors"
                  label="Nama Inovasi"
                  required
                ></v-text-field>
              </validation-provider>

              <!-- kalimat pengantar inovation -->
              <h3><b>Dekripsi Singkat/Abstrak Inovasi</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Dekripsi Singkat Inovasi"
                rules="required"
              >
                <v-textarea
                  v-model="inovation.deskripsi_inovation"
                  :error-messages="errors"
                  filled
                  auto-grow
                  rows="4"
                  row-height="30"
                  shaped
                  label="Deskripsi Singkat/Abstrak Inovasi"
                ></v-textarea>
              </validation-provider>

              <!-- Satuan Kerja Asal Inovasi -->
              <h3><b>Satuan Kerja Asal Inovasi</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Satuan Kerja Asal Inovasi"
                rules="required"
              >
                <v-text-field
                  v-model="inovation.satker_asal_inovation"
                  :error-messages="errors"
                  label="Satuan Kerja Asal Inovasi"
                  required
                ></v-text-field>
              </validation-provider>

              <!-- konten inovation -->
              <h3><b>Isi Konten</b></h3>
              <ckeditor :editor="editor" v-model="inovation.konten_inovation" :config="editorConfig" class="mb-4"></ckeditor>

              <!-- Narahubung untuk Inovasi -->
              <h3><b>Narahubung Inovasi</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Narahubung Inovasi"
                rules="required"
              >
                <v-text-field
                  v-model="inovation.kontak_hubung_inovation"
                  :error-messages="errors"
                  label="Narahubung Inovasi"
                  required
                ></v-text-field>
              </validation-provider>

              <!-- tautan materi inovation -->
              <h3><b>Tautan Materi Inovasi</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Tautan Materi Inovasi"
                rules="required"
              >
                <v-text-field
                  v-model="inovation.tautan_materi_inovation"
                  :error-messages="errors"
                  label="Tautan Materi Inovasi"
                  required
                ></v-text-field>
              </validation-provider>

              <!-- tautan kode inovation -->
              <h3><b>Tautan Kode Inovasi</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Tautan Kode Inovasi"
                rules="required"
              >
                <v-text-field
                  v-model="inovation.tautan_kode_inovation"
                  :error-messages="errors"
                  label="Tautan Kode Inovasi"
                  required
                ></v-text-field>
              </validation-provider>

              <!-- ikon inovation -->
              <h3><b>Ikon/Logo Inovation</b></h3>
              <validation-provider
                v-slot="{ errors }"
                style="z-index:2010;"
                rules="required|size:20480"

              >
                <v-file-input
                  v-model="inovation.ikon_inovation"
                  chips
                  show-size
                  truncate-length="49"
                  :error-messages="errors"
                  label="Ikon/Logo Inovation"
                  prepend-icon="mdi-image"
                ></v-file-input>
              </validation-provider>

              <h3><b>Gambar Pendukung Inovasi (Kalau Ada)</b></h3>
              <!-- image carousel -->
              <validation-provider
                v-slot="{ errors }"
                rules="size:20480"
                style="z-index:2010;">
                  <v-file-input
                    v-model="inovation.gambar_inovation"
                    :rules="image_rules"
                    accept="image/*"
                    show-size
                    chips
                    prepend-icon="mdi-camera"
                    label="Silahkan pilih gambar - gambar pilihan untuk di upload"
                    multiple
                  ></v-file-input>
              </validation-provider>
            </form>
            <v-divider class="my-4"></v-divider>
          </validation-observer>
        </v-container>
      </v-card>
    </v-dialog>
    <!-- DIALOG UNTUK KONFIRMASI HAPUS -->
    <v-dialog v-model="dialogKonfirmasiHapus" persistent max-width="50vw">
      <v-card>
        <v-card-title class="text-h5">
          Hapus data inovasi <b> [ {{ inovation_delete.nama_inovation }} ]</b>?
        </v-card-title>
        <v-card-text>Dengan menghapus ini, data akan terhapus didalam daftar</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            text
            @click="dialogKonfirmasiHapus = false"
          >
            Batalkan
          </v-btn>
          <v-btn
            color="red"
            text
            @click="deleteItem(inovation_delete)"
          >
            Ya, hapus inovation
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
    <v-data-table
      :headers="dessertHeaders"
      :items="desserts"
      :search="search_inovasi"
      item-key="id_inovation"
      class="elevation-1"
    >
      <template v-slot:top>
        <h2 class="mx-4">DAFTAR INOVATION 
          <v-btn
            class="mx-2 my-2"
            fab
            dark
            small
            @click="createInovation()"
            color="primary"
          >
            <v-icon dark>
              mdi-plus
            </v-icon>
          </v-btn>
        </h2>
        <v-toolbar
          flat
        >
        <v-text-field
          v-model="search_inovasi"
          append-icon="mdi-magnify"
          label="Cari Berita"
          class="mx-4 my-2"
          single-line
          hide-details
        ></v-text-field>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-divider class="mx-4"></v-divider>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-btn text
          small
          class="mr-2"
          color="green"
          @click="editInovation(item)"
        >
          VIEW/EDIT INOVATION
        </v-btn>
        <v-btn text
          small
          class="mr-2"
          color="red"
          @click="konfirmasiDeleteItem(item)"
        >
          HAPUS INOVATION
        </v-btn>
      </template>
      <template v-slot:item.updated_at="{ item }">
        {{ getTanggal(item) }}
      </template>
      <template v-slot:no-data>
        <v-btn
          color="primary"
          @click="readDataFromAPI"
        >
          RECALL DATA
        </v-btn>
      </template>
    </v-data-table>
  </v-container>
<!-- </div> -->
</template>
<script>
  import axios from 'axios'
  // script untuk validasi
  import { required, digits, email, max, regex, min, image, size } from 'vee-validate/dist/rules'
  import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
  // script untuk peta
  // import "leaflet/dist/leaflet.css";
  // import L from "leaflet";

  // import 'leaflet-draw/dist/leaflet.draw.css'
  // import 'leaflet-draw'

  import draggable from 'vuedraggable'
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
  // import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';
  // import CKFinder from '@ckeditor/ckeditor5-ckfinder/src/ckfinder';
  // import Link from '@ckeditor/ckeditor5-link/src/link';
  // import CKFinderUploadAdapter from '@ckeditor/ckeditor5-adapter-ckfinder/src/uploadadapter';


  setInteractionMode('eager')

  extend('digits', {
    ...digits,
    message: '{_field_} needs to be {length} digits. ({_value_})',
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

  let id = 1;
  export default {
    components: {
      ValidationProvider,
      ValidationObserver,
      draggable,
      // ClassicEditor,
      // CKFinder,
      // Map,
    },
    name:'ListAset',
    data () {
      return {
        edit_phase: false,
        dragging: true,
        timeProps: {
          useSeconds: true,
          ampmInTitle: true
        },
        editor: ClassicEditor,
        overlay: true,
        image_rules:[
          value => !value || value.size < 23483023 || 'Ukuran total gambar harus kurang dari 20 MB!',
        ],
        editorConfig: {
          // plugins: [CKFinder, Link, CKFinderUploadAdapter],
          ckfinder: {
            // browseUrl: '/ckfinder/browser',
            uploadUrl: '/ckfinder/connector?command=QuickUpload&type=Files&responseType=json',
          },
          // toolbar: [ 'ckfinder'],
          // filebrowserBrowseUrl: '${this.$store.getters.url_base}/ckfinder/browser',
          // // Use named CKFinder connector route
          // filebrowserUploadUrl: '${this.$store.getters.url_base}/ckfinder/connector?command=QuickUpload&type=Files'
            // // The configuration of the editor.
            // plugins: [ CKFinder],

            // // Enable the CKFinder button in the toolbar.
            // toolbar: [ 'ckfinder' ],

            // ckfinder: {
            //     // Upload the images to the server using the CKFinder QuickUpload command.
            //     uploadUrl: 'https://example.com/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images&responseType=json',

            //     // Define the CKFinder configuration (if necessary).
            //     options: {
            //         resourceType: 'Images'
            //     }
            // }

        },
        editor_read: ClassicEditor,
        editorDisabled:true,
        editorConfig_read: {
          toolbar: [],
          // isReadOnly:true,
        },
        editorData_read:'',
        componentKey:0,
        label_dialog_foto: "Lokasi Foto",
        latlng_titik: {},
        inovation:{},
        inovation_edit:{
          gambar_inovation: "",
        },
        inovation_delete:{},
        gambar_pembuka_inovation:'',
        dialogInovationEdit: false,
        dialogInovationCreate: false,
        dialogKonfirmasiHapus: false,
        snackbar: false,
        isLoading: false,
        multiLine: true,
        teksSnackbar: "",
        warnaSnackbar: "",
        isLoggedIn: this.$store.getters.isLogged,
        token: this.$store.getters.token,
        url_base: this.$store.getters.url_base,
        photos: [],
        desserts:[],
        dessertHeaders: [
          {
            text: 'Daftar Inovation',
            align: 'start',
            sortable: true,
            value: 'nama_inovation',
          },
          { text: 'Asal Satuan Kerja', value: 'satker_asal_inovation' },
          { text: 'Kontak Hubung Inovasi', value: 'kontak_hubung_inovation' },
          { text: 'Post By', value: 'created_by' },
          { text: 'Updated at', value: 'updated_at'},
          { text: 'Actions', value: 'actions', sortable: false },
        ],
        colors: ['green', 'purple', 'indigo', 'cyan', 'teal', 'primary'],
        errors:'',
        currentUser:{},
        permissions:[],
        search_inovasi: '',
      }
    },
    watch: {
      options: {
        handler() {
          this.readDataFromAPI();
        },
      },
      dialog (val) {
        val || this.close()
      },
      deep: true,
    },
    computed: {

    },
    methods: {
      imagePreview(val){
        if (!val) return null;
          var link = this.url_base+'/api/ikon_inovation/'+val.toString()
          // console.log(val)
          // console.log(link)
          // return URL.createObjectURL(link);
          return link;
      },
      gambar_inovation_edit_arr(){
        return this.inovation_edit.gambar_inovation.split(',')
      },
      getTanggal(val){
        // console.log(val)
        var tanggal = new Date(val.updated_at)
        return tanggal.toUTCString()
      },
      //Reading data from API method.
      async readDataFromAPI() {
        // this.loading = true;
        await axios.get('/api/get-inovations')
          .then((response) => {
            //Then injecting the result to datatable parameters.
            // console.log('ambil data dari database')
            // console.log(response.data)
            this.desserts = response.data;
          }).catch(errors => {
            console.log(errors)
            // this.loading = false;
            this.desserts = [];
          }).finally(() => {
            // this.loading = false;
          });
      },
      json2list(json_data){
        var result = [];
        json_data.forEach((datum) => {
          result.push(datum.text);
        });
        // console.log(json_data)
        return result;
      },
      async download_rekap_aset(){
        this.overlay_list = true
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        await axios.get('/api/rekap-asets/', {
          responseType: 'blob',
        }).then((response) => {
          const nama_zip = "rekap_aset" + ".zip"
          const url = URL.createObjectURL(new Blob([response.data]))
          const link = document.createElement('a')
          link.href = url
          link.setAttribute(
            'download',
            nama_zip
          )
          document.body.appendChild(link)
          link.click()
        }).catch(errors => {
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data)
          console.log(errors.response.data)
          this.warnaSnackbar= "red"
          this.snackbar = true
        }).finally(() => {
          this.overlay_list = false
        })
      },
      konfirmasiDeleteItem(item){
        this.inovation_delete = item
        this.dialogKonfirmasiHapus = true
      },
      async deleteItem (item) {
        this.overlay = true
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }
        var formData = new FormData();
        formData.append('id_inovation', this.inovation_delete.id_inovation);
        formData.append('id_entri', this.inovation_delete.id_entri);
        // console.log(formData);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        await axios.post("/api/delete-inovation", formData, config).then(response => {
          // console.log(response.data)
          if(response.data.status == false){
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message)
            // console.log(r)
            this.warnaSnackbar= "red"
            this.snackbar = true
          }else{
            this.dialogKonfirmasiHapus = false
            this.teksSnackbar= "Berhasil menghapus data"
            this.warnaSnackbar= "success"
            this.snackbar = true
          }
        }).catch(errors => {
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data)
          console.log(errors.response.data)
          this.warnaSnackbar= "red"
          this.snackbar = true
        }).finally(async () => {
          // this.clear()
          await this.readDataFromAPI()
          this.isLoading = false
          this.overlay = false
        })
      },
      editInovation(item){
        this.inovation_edit = item
        // console.log(item)
        this.dialogInovationEdit = true
        // this.edit_phase = true
        // this.inovation = {};
        // this.edit_phase= true;
        // this.list_sesi_edit =[];
        // this.sesi_edit.id_entri = [];
        // this.sesi_edit.id_sesi = [];
        // this.sesi_edit.id_inovation = [];
        // this.sesi_edit.judul_sesi = [];
        // this.sesi_edit.sambutan_sesi = [];
        // this.sesi_edit.moderator_sesi = [];
        // this.sesi_edit.pemateri_sesi = [];
        // this.sesi_edit.penampil_sesi = [];
        // this.sesi_edit.tugas_sesi = [];
        // this.sesi_edit.tautan_sesi = [];
        // this.sesi_edit.materi_sesi = [];
        // this.sesi_edit.konten_sesi = [];
        // this.sesi_edit.tanggal_awal_sesi = [];
        // this.sesi_edit.tanggal_akhir_sesi = [];
        // this.sesi_edit.waktu_awal_sesi = [];
        // this.sesi_edit.waktu_akhir_sesi = [];
        // this.sesi_edit.able_tautan_sesi = [];
        // this.sesi_edit.able_tugas_sesi = [];
        // this.sesi_edit.able_lampiran_sesi = [];
        // this.sesi_edit.able_materi_publik = [];
        // var refs = this

        // axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        // // this.inovation.tampilan_web = (this.inovation.tampilan_web === 'true')
        // axios.get("/api/show-inovations/"+item.id_inovation).then(response => {
        //   // console.log(response.data)
        //   if(response.data.status == false){
        //     this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
        //     this.warnaSnackbar= "red",
        //     this.snackbar = true
        //   }else{
        //     this.clearCreateItem()
        //     console.log('data terakses')
        //     console.log(response.data)
        //     refs.inovation = response.data.inovation
        //     refs.inovation.image_preview = response.data.inovation.sampul_inovation
        //     refs.inovation.sampul_inovation = null
        //     refs.editors_select = response.data.inovation.editors_inovation != null ? (response.data.inovation.editors_inovation).split(',') : null;
        //     refs.tags_select = response.data.inovation.tags_inovation != null ? (response.data.inovation.tags_inovation).split(',') : null;
        //     var list_sesi_temp = response.data.sesis
        //     var id = 0;
        //     list_sesi_temp.forEach(element => {
        //       this.list_sesi_edit.push(
        //         { name: element.judul_sesi, id: id},
        //       );
        //       this.sesi_edit.id_entri[id] = (element.id_entri);
        //       this.sesi_edit.id_sesi[id] = (element.id_sesi);
        //       this.sesi_edit.id_inovation[id] = (element.id_inovation);
        //       this.sesi_edit.judul_sesi[id] = (element.judul_sesi);
        //       this.sesi_edit.jenis_sesi[id] = (element.jenis_sesi);
        //       this.sesi_edit.sambutan_sesi[id] = (element.sambutan_sesi);
        //       this.sesi_edit.moderator_sesi[id] = (element.moderator_sesi);
        //       this.sesi_edit.pemateri_sesi[id] = (element.pemateri_sesi);
        //       this.sesi_edit.penampil_sesi[id] = (element.penampil_sesi);
        //       this.sesi_edit.tugas_sesi[id] = (element.tugas_sesi);
        //       this.sesi_edit.tautan_sesi[id] = (element.tautan_sesi);
        //       this.sesi_edit.materi_sesi[id] = (element.materi_sesi);
        //       this.sesi_edit.konten_sesi[id] = (element.konten_sesi);
        //       this.sesi_edit.tanggal_awal_sesi[id] = (element.tanggal_awal_sesi);
        //       this.sesi_edit.tanggal_akhir_sesi[id] = (element.tanggal_akhir_sesi);
        //       this.sesi_edit.waktu_awal_sesi[id] = (element.waktu_awal_sesi);
        //       this.sesi_edit.waktu_akhir_sesi[id] = (element.waktu_akhir_sesi);
        //       this.sesi_edit.able_tautan_sesi[id] = (element.able_tautan_sesi);
        //       this.sesi_edit.able_tugas_sesi[id] = (element.able_tugas_sesi);
        //       this.sesi_edit.able_lampiran_sesi[id] = (element.able_lampiran_sesi);
        //       this.sesi_edit.able_materi_publik[id] = (element.able_materi_publik);
        //       id++;
        //     });
        //   }
        // }).catch(errors => {
        //   console.log(errors)
        //   this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors),
        //   this.warnaSnackbar= "red",
        //   this.snackbar = true
        // }).finally(() => {
        //   this.isLoading = false
        //   this.overlay_entri = false
        //   this.overlay_list = false
        // });
        // this.dialogInovationEdit = true
        // this.overlay_list = false

      },
      createInovation(){
        // this.inovation.id_inovation=''
        // this.inovation.nama_inovation=''
        // this.inovation.ikon_inovation=null
        // this.inovation.deskripsi_inovasi=''
        // this.inovation.konten_inovation= ''
        // this.inovation.gambar_inovation=null
        // this.inovation.tautan_kode_inovation=''
        // this.inovation.tautan_materi_inovation=''
        // this.inovation.kontak_hubung_inovation=''
        // this.inovation.satker_asal_inovation=''
        this.dialogInovationCreate = true
      },
      async resetCreateInovation(){
        this.inovation.id_inovation=''
        this.inovation.nama_inovation=''
        this.inovation.ikon_inovation=null
        this.inovation.deskripsi_inovasi=''
        this.inovation.konten_inovation= ''
        this.inovation.gambar_inovation=null
        this.inovation.tautan_kode_inovation=''
        this.inovation.tautan_materi_inovation=''
        this.inovation.kontak_hubung_inovation=''
        this.inovation.satker_asal_inovation=''
        this.dialogInovationCreate = false
      },
      async submitEditItem(){
        var status_form = await this.$refs.observer_edit_inovation.validate()
        this.overlay = true
        if(status_form == true){
          this.isLoading = "secondary"
          var formData = new FormData();
          const config = {
              headers: {
                  'Content-Type': 'multipart/form-data',
              }
          }

          // menambahkan data inovation kedalam form untuk di upload
          formData.append('id_inovation', this.inovation_edit.id_inovation);
          formData.append('nama_inovation', this.inovation_edit.nama_inovation);
          formData.append('satker_asal_inovation', this.inovation_edit.satker_asal_inovation);
          formData.append('kontak_hubung_inovation', this.inovation_edit.kontak_hubung_inovation);
          formData.append('deskripsi_inovation', this.inovation_edit.deskripsi_inovation);
          formData.append('konten_inovation', this.inovation_edit.konten_inovation);
          formData.append('tautan_materi_inovation', this.inovation_edit.tautan_materi_inovation);
          formData.append('tautan_kode_inovation', this.inovation_edit.tautan_kode_inovation);
          formData.append('ikon_inovation', this.inovation_edit.ikon_inovation);
          formData.append('created_by', this.inovation_edit.created_by);
          formData.append('gambar_inovation', this.inovation_edit.gambar_inovation);
          // formData.append('tags_inovation', JSON.stringify(this.tags_select));
          if(this.inovation_edit.ikon_inovation_new != null){
              formData.append("ikon_inovation_new", this.inovation_edit.ikon_inovation_new, this.inovation_edit.ikon_inovation_new.name);
          }
          var file = null;
          var filenames = [];
          if(this.inovation_edit.gambar_inovation_new != null){
            for (let file of this.inovation_edit.gambar_inovation_new) {
              formData.append(`gambar_inovation_new_file[]`, file, file.name);
              filenames.push(file.name);
              formData.append(`gambar_inovation_new_filename[]`, file.name);
            }
              formData.append(`gambar_inovation_new`, JSON.stringify(filenames));
          }

          await axios.post("/api/update-inovation", formData, config).then(response => {
            // console.log(response.data)
            if(response.data.status == false){
              this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
              this.warnaSnackbar= "red",
              this.snackbar = true
            }else{
              this.teksSnackbar= JSON.stringify(response.data.message),
              this.warnaSnackbar= "green",
              this.snackbar = true
              this.dialogInovationEdit = false
              this.clearCreateItem()
            }
          }).catch(errors => {
            console.log(errors.response.data)
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data.errors),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }).finally(async () => {
            await this.readDataFromAPI();
            this.isLoading = false
            this.overlay = false
          });
          // end of upload
          this.inovation_edit = {
            gambar_inovation: "",
          };
        }else{
          this.teksSnackbar= "Terjadi Kesalahan : Form masih ada belum valid",
          this.warnaSnackbar= "red",
          this.snackbar = true
          this.overlay = false
        }
      },
      submitViewItem(){
        // alert(this.restored_id)
        this.overlay_list = true
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }

        // declare form data
        var formData = new FormData();
        // additional data
        formData.append('id_aset', this.aset.id_aset);
        formData.append('id_entri', this.restored_id);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        axios.post("/api/restore-data-aset", formData, config).then(response => {
          // console.log(response.data)
          this.teksSnackbar= "Berhasil memutakhirkan data aset "
          this.warnaSnackbar= "success"
        }).catch(errors => {
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data)
          console.log(errors.response.data)
          this.warnaSnackbar= "red"
        }).finally(() => {
          // this.clear()
          this.readDataFromAPI()
          this.dialogInovationView = false 
          this.dialogHistori = false
          this.isLoading = false
          this.overlay_list = false
          this.snackbar = true
        })
      },
      async submitCreateItem(){
        var status_form = await this.$refs.observer_create_inovation.validate()
        // start of upload
        if(status_form == true){
          this.overlay = true
          this.isLoading = "primary"
          const config = {
              headers: {
                  'Content-Type': 'multipart/form-data',
              }
          }
          var formData = new FormData();
          // menambahkan data inovation kedalam form untuk di upload
          formData.append('nama_inovation', this.inovation.nama_inovation);
          formData.append('satker_asal_inovation', this.inovation.satker_asal_inovation);
          formData.append('ikon_inovation', this.inovation.ikon_inovation);
          formData.append('kontak_hubung_inovation', this.inovation.kontak_hubung_inovation);
          formData.append('deskripsi_inovation', this.inovation.deskripsi_inovation);
          formData.append('konten_inovation', this.inovation.konten_inovation);
          formData.append('tautan_materi_inovation', this.inovation.tautan_materi_inovation);
          formData.append('tautan_kode_inovation', this.inovation.tautan_kode_inovation);
          // formData.append('tags_inovation', JSON.stringify(this.tags_select));
          var file = null;
          var filenames = [];
          if(this.inovation.gambar_inovation != null){
            for (let file of this.inovation.gambar_inovation) {
              formData.append(`gambar_inovation_file[]`, file, file.name);
              filenames.push(file.name);
              formData.append(`gambar_inovation_filename[]`, file.name);
            }
              formData.append(`gambar_inovation`, JSON.stringify(filenames));
          }
          // console.log(formData)

          await axios.post("/api/insert-inovation", formData, config).then(async response => {
            // console.log(response.data)
            if(response.data.status == false){
              this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
              this.warnaSnackbar= "red",
              this.snackbar = true
            }else{
              this.teksSnackbar= JSON.stringify(response.data.message),
              this.warnaSnackbar= "green",
              this.snackbar = true

              await this.resetCreateInovation();
              await this.readDataFromAPI();
            }
          }).catch(errors => {
            console.log(errors.response.data)
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data.errors),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }).finally(() => {
            this.isLoading = false
            this.overlay = false
          });
          // end of upload
        }else{
          this.teksSnackbar= "Form masih error",
          this.warnaSnackbar= "red lighten-1",
          this.snackbar = true
        }
      },
      clearCreateItem(){
        this.inovation.id_entri=''
        this.inovation.id_inovation=''
        this.inovation.judul_inovation=''
        this.inovation.teks_pembuka_inovation=''
        this.inovation = {}
        this.inovation.sampul_inovation=null
        this.inovation.lampiran_inovation=null
        this.inovation.tags_inovation=null
        this.list_sesi = [
          { name: "John", text: "", id: 0, waktu_awal_sesi:null, waktu_akhir_sesi: null, konten_sesi: null,},
        ]

        this.sesi = {
          judul_sesi: [],
          jenis_sesi: [],
          sambutan_sesi: [],
          moderator_sesi: [],
          pemateri_sesi: [],
          penampil_sesi: [],
          tautan_sesi: [],
          tugas_sesi: [],
          konten_sesi: [],
          lampiran_sesi: [],
          // waktu_mulai_sesi: [],
          // waktu_akhir_sesi: [],
          able_tautan_sesi: [],
          able_tugas_sesi: [],
          able_lampiran_sesi: [],
          able_materi_publik: [],
          tanggal_awal_sesi: [],
          waktu_awal_sesi: [],
          tanggal_akhir_sesi: [],
          waktu_akhir_sesi: [],
          materi_sesi: [],
        }
        this.menu_tanggal_awal_sesi = []
        this.menu_waktu_awal_sesi = []
        // this.$refs.observer.reset()
      },
      clearViewItem(){
        this.inovation = {};
        this.list_sesi = [];
      },
      closeEditItem() {
        this.dialogInovationEdit = false
        // this.clear()
      },
      closeViewItem() {
        this.dialogInovationView = false
        this.clearViewItem()
      },
      closeCreateItem(){
        this.dialogInovationCreate = false
        // this.clearCreateItem()
      },
    },
    //this will trigger in the onReady State
    async mounted() {
      // console.log(this.$refs['mapID']);
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      await this.readDataFromAPI();
      // this.editor_read.isReadOnly = true;
      // this.initMap();
      // this.sesi.judul_sesi = Array(100)
      // this.sesi.waktu_awal_sesi[0] = null
      // this.sesi.waktu_akhir_sesi[0] = null
      this.overlay = false
    },
    async created(){
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      await axios.get('/api/user-permission').then(async response => {
          this.currentUser = response.data.user;
          await this.$store.commit('updateRBAC', response.data.permissions)
          if(!(response.data.permissions).includes('articles.create')){
            this.$router.push('/')
          }
      }).catch(errors => {
          console.log(errors);
      }).finally(() => {
          this.permissions = this.$store.getters.rbac
      })
      // this.editor_read.isReadOnly = true;
      // console.log(this.$refs['mapID']);
      // this.initMap();
    },
  }
</script>
<style scoped>
>>>.tag-input span.chip {
  background-color: orange;
  color: #fff;
  font-size: 1em;
}

>>>.tag-input span.v-chip {
  background-color: orange;
  color: #fff;
  font-size:1em;
  padding-left:7px;
}

>>>.tag-input span.v-chip::before {
    content: "label";
    font-family: 'Material Icons';
    font-weight: normal;
    font-style: normal;
    font-size: 20px;
    line-height: 1;
    letter-spacing: normal;
    text-transform: none;
    display: inline-block;
    white-space: nowrap;
    word-wrap: normal;
    direction: ltr;
    -webkit-font-feature-settings: 'liga';
    -webkit-font-smoothing: antialiased;
}

</style>