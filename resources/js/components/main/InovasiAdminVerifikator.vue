L<template>
  <v-container>
    <v-overlay :value="overlay" style="z-index:7000;">
      <v-progress-circular
        indeterminate
        size="64"
      ></v-progress-circular>
      <p class="text-center">
        Loading . . .
      </p>
    </v-overlay>
    <!-- DIALOG UNTUK EDIT DATA -->
    <v-dialog v-model="dialogInovationEdit" fullscreen hide-overlay style="z-index:2050" transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar
          dark
          :color="warna_crud_dialog">
          <v-btn
            icon
            dark
            @click="closeEditItem()"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ judul_crud_dialog }} </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn
              dark
              text
              v-if="edit_crud_dialog"
              @click="submitEditItem()"
            >
              Simpan Data
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
              <validation-provider
                v-slot="{ errors }"
                name="Keanggotaan"
              >
                <v-select
                  v-model="inovation_edit.id_keanggotaan"
                  :items="user_memberships"
                  v-if="memberships_items_length"
                  item-value="value"
                  :item-text="item =>`[${item.type.toUpperCase()}] ${item.text}`"
                  :error-messages="errors"
                  label="Keanggotaan Inovasi"
                  prepend-icon="mdi-format-list-bulleted-square"
                ></v-select>
              </validation-provider>

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
                    contain
                    :src="imagePreviewLink(inovation_edit.ikon_inovation)"
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
                rules="size:20480000"
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
                    contain
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
    <!-- DIALOG UNTUK VIEW DATA -->
    <v-dialog v-model="dialogInovationView" fullscreen hide-overlay style="z-index:2051" transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar
          dark
          color="primary darken--3"
        >
          <v-btn
            icon
            dark
            @click="closeViewItem()"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ inovation.nama_inovation }} </v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-divider></v-divider>
        <v-container fluid>
            <div style="text-align: center; content-align:center">
              <v-img class="mx-auto"
                style="max-width: 300px;"
                :src="imagePreviewLink(inovation.ikon_inovation)"
                lazy-src="https://picsum.photos/510/300?random">
                <template v-slot:placeholder>
                  <v-row
                    class="fill-height ma-0"
                    align="center"
                    justify="center"
                  >
                    <v-progress-circular
                      indeterminate
                      color="white lighten-5"
                    ></v-progress-circular>
                  </v-row>
                </template>
              </v-img>
            </div>
            <p><b>Judul Inovasi</b> : {{ inovation.nama_inovation }}</p>
            <p><b>Dekripsi</b> : {{ inovation.deskripsi_inovation }}</p>
            <p><b>Dipublikasikan oleh</b> : {{ inovation.created_by }}</p>
            <p><b>Satuan Kerja Asal</b> : {{ inovation.satker_asal_inovation }}</p>
            <p><b>Email Verifikator</b> : {{ inovation.verified_by }}</p>
            <p><b>Update Terakhir</b> : {{ new Date(inovation.updated_at).toUTCString() }}</p>
            <p><b>Gambar Pendukung Inovasi Yang Ada</b></p>
            <v-row>
              <v-col
                v-for="gambar in gambar_inovation_view_arr()"
                :key="gambar"
                class="d-flex child-flex"
                cols="4"
              >
                <v-img
                  :src="url_base+'/api/gambar_inovation/'+gambar"
                  lazy-src="https://picsum.photos/510/300?random"
                  class="grey lighten-2"
                  contain
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

            <p><b>Isi Inovasi/Berita/Konten</b> :</p>
            <ckeditor :editor="editor_read" v-model="inovation.konten_inovation" :disabled="editorDisabled" :config="editorConfig_read"></ckeditor>
        </v-container>
      </v-card>
    </v-dialog>
    <!-- DIALOG UNTUK KONFIRMASI HAPUS -->
    <v-dialog v-model="dialogKonfirmasiHapus" persistent max-width="50vw">
      <v-card>
        <v-card-title class="text-h5">
          Hapus inovation/berita [{{ inovation.nama_inovation }}]?
        </v-card-title>
        <v-card-text>Dengan menghapus inovation ini, data akan terhapus didalam daftar berita!</v-card-text>
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
            @click="deleteItem()"
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
      :search="search_inovation"
      item-key="nomor_aset"
      class="elevation-1"
    >
      <template v-slot:top>
        <h2 class="mx-4">DAFTAR VERIFIKASI INOVASI
          <!-- <v-btn
            class="mx-2 my-2"
            fab
            dark
            small
            @click="createItem()"
            color="primary"
          >
            <v-icon dark>
              mdi-plus
            </v-icon>
          </v-btn> -->
        </h2>
        <v-toolbar
          flat
        >
        <v-text-field
          v-model="search_inovation"
          append-icon="mdi-magnify"
          label="Cari inovation"
          class="mx-4 my-2"
          single-line
          hide-details
        ></v-text-field>
          <v-spacer></v-spacer>
        </v-toolbar>
        <v-divider class="mx-4"></v-divider>
      </template>
      <template v-slot:item.keanggotaan="{ item }">
        {{ item.nama_organisasi != null ? "(Organisasi) "+item.nama_organisasi : (item.nama_divisi != null ? "(Divisi) "+item.nama_divisi : "(Tim) "+item.nama_tim) }}
      </template>
      <template v-slot:item.penulis_inovation="{ item }">
        {{ item.penulis_inovation }} ({{ item.created_by }})
      </template>
      <template v-slot:item.penyunting_inovation="{ item }">
        {{ item.penyunting_inovation == null ? '-' : item.penyunting_inovation.toString()+' ('+item.updated_by.toString()+')' }}
      </template>
      <template v-slot:item.status_verifikasi_inovation="{ item }">
        <v-chip v-if="item.status_verifikasi_inovation === 1" color="blue" small dark>Terverifikasi</v-chip>
        <v-chip v-else color="orange darken-2" small dark>Belum Terverifikasi</v-chip>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-btn text
          small
          class="mr-2"
          color="primary darken--3"
          @click="viewItem(item)"
        >
          LIHAT INOVATION
        </v-btn>
        <v-btn text
          small
          class="mr-2"
          color="green darken-2"
          @click="editItem(item)"
        >
          EDIT INOVATION
        </v-btn>
        <v-btn text
          small
          class="mr-2"
          color="error darken-3"
          @click="konfirmasiDeleteItem(item)"
        >
          HAPUS INOVATION
        </v-btn>

        <v-btn text
          small
          v-if="item.status_verifikasi_inovation === 1"
          class="mr-2"
          color="secondary darken-3"
          @click="submitUndoneVerification(item)"
        >
          BATALKAN VERIFIKASI
        </v-btn>

        <v-btn text
          small
          v-else
          class="mr-2"
          color="secondary darken-3"
          @click="submitVerification(item)"
        >
          VERIFIKASI INOVATION
        </v-btn>

      </template>
      <template v-slot:no-data>
        BELUM ADA INOVATION
        <!-- <v-btn
          color="primary"
          @click="readDataFromAPI"
        >
          Reset
        </v-btn> -->
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
  import "leaflet/dist/leaflet.css";
  import L from "leaflet";

  import 'leaflet-draw/dist/leaflet.draw.css'
  import 'leaflet-draw'

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
      // ClassicEditor,
      // CKFinder,
      // Map,
    },
    name:'ListAset',
    data () {
      return {
        menu_tanggal_inovation: false,
        editor: ClassicEditor,
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
        inovation:{
          id_entri:'',
          nama_inovation:'',
          ikon_inovation: 'error.png',
          gambar_inovation: '',
          tags_inovation:null,
          status_verifikasi_inovation: null,
        },
        inovation_edit:{
          id_entri:'',
          nama_inovation:'',
          ikon_inovation: 'error.png',
          gambar_inovation: '',
          tags_inovation:null,
          status_verifikasi_inovation: null,
        },
        gambar_pembuka_inovation:'',
        jenis_inovation_items:[
          {teks: 'Pembinaan Statistik', value: 'pembinaan-statistik'},
          {teks: 'Indikator Statistik', value: 'indikator-statistik'},
          {teks: 'Materi Umum', value: 'materi-umum'},
        ],
        tampilan_web_items:[
          {teks: 'Ya', value: true},
          {teks: 'Tidak', value: false},
        ],
        dialog: false,
        dialogInovationEdit: false,
        dialogInovationView:false,
        dialogInovationCreate: false,
        dialogHistori:false,
        dialogKonfirmasiHapus: false,
        dialogKonfirmasiHapusFile: false,
        filenameDihapuskan:'',
        jenisfileDihapuskan: '',
        overlay:false,
        lokasi_item: {},
        geolocation:"",
        page: 0,
        totalPassengers: 0,
        numberOfPages: 0,
        passengers: [],
        loading: true,
        options: {},
        expanded: [],
        singleExpand: false,
        renderComponent:true,
        snackbar: false,
        isLoading: false,
        multiLine: true,
        teksSnackbar: "",
        warnaSnackbar: "",
        map:null,
        mapEdit:null,
        isLoggedIn: this.$store.getters.isLogged,
        token: this.$store.getters.token,
        url_base: this.$store.getters.url_base,
        photos: [],
        desserts:[],
        dessertHeaders: [
          {
            text: 'Judul Inovasi',
            align: 'start',
            sortable: true,
            value: 'nama_inovation',
            witdh: '250px',
          },
          {
            text: 'Keanggotaan Inovasi/Konten',
            sortable: true,
            value: 'keanggotaan',
            width: '350px'
          },
          { text: 'Satuan Kerja Inovasi', value: 'satker_asal_inovation' },
          { text: 'Penulis Inovasi', value: 'created_by' },
          { text: 'Kontak Hubung', value: 'kontak_hubung_inovation' },
          { text: 'Status Verifikasi', value: 'status_verifikasi_inovation' },
          { text: 'Email Verifikator', value: 'verified_by' },
          { text: 'Actions', value: 'actions', sortable: false , witdh: '300px'},
        ],
        expanded: [],
        singleExpand: false,
        colors: ['green', 'purple', 'indigo', 'cyan', 'teal', 'primary'],
        search_inovation:'',
        lampiran_inovation_nonce: 1,
        lampiran_inovation_search: null,
        lampiran_inovation_temps: [],
        lampiran_inovation_deleted: [],
        lampiran_inovation_names: [],
        aset_history: '',
        judul_crud_dialog: 'Judul',
        warna_crud_dialog: 'primary',
        view_crud_dialog: false,
        edit_crud_dialog: true,
        filenames:[],
        tags_select: [],
        tags_items: [],
        tags_search: "",
        tags_select_edit: [],
        tags_items_edit: [],
        tags_search_edit: "",
        errors:'',
        currentUser:{},
        permissions:[],
        user_memberships: [],
        user: null,
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
      lampiran_inovation_temps (val, prev) {
        if (val.length === prev.length) return
        this.lampiran_inovation_temps = val.map(v => {
          
          if (typeof v.text === 'string') {
            v = {
              text: v.text,
              color: 'secondary',
            }

            this.lampiran_inovation_deleted.push(v)

            this.lampiran_inovation_nonce++
          }

          return v

        })
      },
    },
    computed: {
      showPenggunaBarangTingkatPertamaLainnya(){
        return this.aset.pengguna_barang_tingkat_pertama == 0 && this.aset.pengguna_barang_tingkat_pertama_lainnya != null
      },
      filenames_computed(){
        return this.filenames;
      },
      tagnames_computed(){
        var tags =  (this.inovation.tags_inovation == null ) ? [] : (this.inovation.tags_inovation).split(',');
        return tags;
      },
      tageditnames_computed(){
        var tags =  (this.inovation_edit.tags_inovation == null ) ? [] : (this.inovation_edit.tags_inovation).split(',');
        return tags;
      },
      imagePreview(){
        if (!this.inovation.ikon_inovation) return null;
          return URL.createObjectURL(this.inovation.ikon_inovation);
      },
      memberships_items_length(){
        return this.inovation_edit.created_by == this.user.email
      },
    },
    methods: {
      //Reading data from API method. 
      async readDataFromAPI() {
        this.loading = true;
        await axios.get('/api/get-inovations/supervisor')
          .then((response) => {
            //Then injecting the result to datatable parameters.
            this.loading = false;
            this.desserts = response.data;

          });
      },
      gambar_inovation_edit_arr(){
        return this.inovation_edit.gambar_inovation.split(',')
      },
      gambar_inovation_view_arr(){
        return this.inovation.gambar_inovation.split(',')
      },
      imagePreviewLink(val){
        if (!val) return null;
          var link = this.url_base+'/api/ikon_inovation/'+val.toString()
          return link;
      },
      json2list(json_data){
        var result = [];
        json_data.forEach((datum) => {
          result.push(datum.text);
        });
        // console.log(json_data)
        return result;
      },
      getPreviewFile(file_name, jenis_file){
        var link = this.url_base + '/api/' + jenis_file +'/'+ file_name
        var tipe_file = file_name.split('.').slice(-1)[0]
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        axios(link, {
            method: 'GET',
            responseType: 'blob' //Force to receive data in a Blob Format
        })
        .then(response => {
        //Create a Blob from the PDF Stream
          var file;
          if(tipe_file == 'pdf'){
            file = new Blob([response.data], {type: 'application/pdf'});
          }else if(tipe_file == 'jpg' || tipe_file == 'jpeg' || tipe_file == 'png'){
            file = new Blob([response.data], {type: 'image/jpeg'});
          }
        //Build a URL from the file
            const fileURL = URL.createObjectURL(file);
        //Open the URL on new Window
            window.open(fileURL);
        })
        .catch(error => {
            console.log(error);
        });
      },
      async userMemberships(){
        await axios.get('/api/user-memberships/PenulisInovasiOrganisasi&PenulisInovasiDivisi&PenulisInovasiTim/true').then(response => {
            let data = response.data.data
            // console.log(response.data.data)
            let memberships = []
            data.forEach(datum => {
              if(datum.jenis_keanggotaan == 'organisasi'){
                memberships.push({text : datum.nama_organisasi, value: datum.id_keanggotaan, type: 'organisasi'})
              }else if(datum.jenis_keanggotaan == 'divisi'){
                memberships.push({text : datum.nama_divisi, value: datum.id_keanggotaan, type : 'divisi'})
              }else if(datum.jenis_keanggotaan == 'tim'){
                memberships.push({text : datum.nama_tim, value: datum.id_keanggotaan, type: 'tim'})
              }
            });
            this.user_memberships = memberships
        }).catch(errors => {
            console.log(errors)
        })
      },
      konfirmasiDeleteItem(item){
        this.inovation = item
        this.dialogKonfirmasiHapus = true
      },
      async deleteItem () {
        this.overlay = true
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }
        var formData = new FormData();
        var $teks = "";
        formData.append('id_inovation', this.inovation.id_inovation);
        formData.append('id_entri', this.inovation.id_entri);
        formData.append('id_keanggotaan', this.inovation.id_keanggotaan);
        console.log(formData);
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
            this.teksSnackbar= "Berhasil menghapus profil inovasi "
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
      viewItem(item){
        // alert(id_entri)
        // this.view_crud_dialog = true
        // this.edit_crud_dialog = false
        this.dialogInovationView = true
        this.inovation = item
        // this.inovation.tampilan_web = (this.inovation.tampilan_web === 'true')
        this.gambar_pembuka_inovation = this.url_base+"/api/gambar_pembuka_inovation/"+this.inovation.gambar_pembuka_inovation
        this.filenames = item.lampiran_inovation == null ? [] : (item.lampiran_inovation).split(',')
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        this.overlay = false
      },
      async editItem(item){
        this.layout = true
        this.inovation_edit = item
        this.inovation_edit.status_verifikasi_inovation = (this.inovation_edit.status_verifikasi_inovation === 1)
        await this.userMemberships()
        this.dialogInovationEdit = true
        this.edit_crud_dialog = true
        this.dialogInovationEdit = true
        this.layout = false
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
          formData.append('id_keanggotaan', this.inovation_edit.id_keanggotaan);
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
      async submitVerification(item){
        // console.log(this.inovation)
        // start of upload
        this.overlay = true
        this.isLoading = "orange"
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }
        var formData = new FormData();
        formData.append('id_inovation', item.id_inovation);
        formData.append('id_keanggotaan', item.id_keanggotaan);
        formData.append('status_verifikasi_inovation', true);

        await axios.post("/api/verification-inovation", formData, config).then(response => {
          // console.log(response.data)
          if(response.data.status == false){
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }else{
            this.teksSnackbar= response.data.message
            this.warnaSnackbar= "green",
            this.snackbar = true
            this.clearCreateItem()
          }
        }).catch(errors => {
          console.log(errors.response.data)
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data.errors),
          this.warnaSnackbar= "red",
          this.snackbar = true
        }).finally(async() => {
          await this.readDataFromAPI();
          this.isLoading = false
          this.overlay_entri = false
          this.overlay = false
        });
        // end of upload
      },
      async submitUndoneVerification(item){
        // console.log(this.inovation)
        // start of upload
        this.overlay = true
        this.isLoading = "orange"
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }
        var formData = new FormData();
        formData.append('id_inovation', item.id_inovation);
        formData.append('id_keanggotaan', item.id_keanggotaan);
        formData.append('status_verifikasi_inovation', false);

        await axios.post("/api/verification-inovation", formData, config).then(response => {
          // console.log(response.data)
          if(response.data.status == false){
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }else{
            this.teksSnackbar= response.data.message
            this.warnaSnackbar= "green"
            this.snackbar = true
          }
        }).catch(errors => {
          console.log(errors.response.data)
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data.errors),
          this.warnaSnackbar= "red",
          this.snackbar = true
        }).finally(async () => {
          await this.readDataFromAPI();
          this.isLoading = false
          this.overlay_entri = false
          this.overlay = false
        });
        // end of upload
      },
      async initMap(){
        var ref = this;
        var map
        await axios.get('/api/user').then(response => {
          this.user = response.data.user
          // console.log(response.data)
          var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                  osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                  osm = L.tileLayer(osmUrl, { maxZoom: 20, attribution: osmAttrib }),
                  map = new L.Map('map', { center: [-2.207680, 113.916357], zoom: 15 }),
                  drawnItems = L.featureGroup().addTo(map);
          L.control.layers({
                'osm': osm.addTo(map),
                "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', 
                {
                    attribution: 'google'
                })
            }).addTo(map);
        }).catch(errors => {
            console.log(errors)
        });
      },
      clearCreateItem(){
        this.inovation.id_entri=''
        this.inovation.id_inovation=''
        this.inovation.nama_inovation=''
        this.inovation.teks_pembuka_inovation=''
        this.inovation.teks_isi_inovation= ''
        this.inovation.tanggal_inovation=''
        this.inovation.geolocation_inovation=''
        this.inovation.gambar_pembuka_inovation=null
        this.inovation.lampiran_inovation=null
        this.inovation.tags_inovation=null
        // this.$refs.observer.reset()
      },
      async clearViewItem(){
        this.bukti_pembelian_aset_temps=[]
        this.bukti_pembelian_aset_deleted=[]
        this.bukti_pembukuan_aset_temps=[]
        this.bukti_kepemilikan_aset_temps=[]
        this.foto_aset_temps=[]
        this.bukti_pembukuan_aset_deleted=[]
        this.bukti_kepemilikan_aset_deleted=[]
        this.foto_aset_deleted=[]
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
        this.clearCreateItem()
      },
    },
    //this will trigger in the onReady State
    async mounted() {
      // console.log(this.$refs['mapID']);
      this.overlay = true
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      await this.readDataFromAPI();
      // this.editor_read.isReadOnly = true;
      await this.initMap();
      this.overlay = false
    },
    async created(){
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      await axios.get('/api/user-permission').then(response => {
          this.currentUser = response.data.user;
          this.$store.commit('updateRBAC', response.data.permissions)
          this.$store.commit('updateRoles', response.data.roles)
          this.$store.commit('updatePermissions', response.data.permissions)
          if(!response.data.roles.some(r => ['SuperAdmin', 'SupervisorOrganisasi','SupervisorDivisi','SupervisorTim'].includes(r))){
            this.$router.push('/')
          }
      }).catch(errors => {
          console.log(errors);
      }).finally(() => {
          this.permissions = this.$store.getters.permissions
      })
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