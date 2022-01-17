L<template>
  <v-container>
    <v-overlay :value="overlay_list" style="z-index:2100;">
      <v-progress-circular
        indeterminate
        size="64"
      ></v-progress-circular>
      <p class="text-center">
        Loading . . .
      </p>
    </v-overlay>
    <!-- DIALOG UNTUK VIEW DATA ATAU EDIT -->
    <v-dialog v-model="dialogNewsEdit" fullscreen hide-overlay style="z-index:2050" transition="dialog-bottom-transition">
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
            ref="observer"
            v-slot="{ invalid }"
          >
            <form @submit.prevent="submit">
              <!-- judul berita -->
              <h3><b>Judul Berita/Artikel/Konten</b></h3>
              <v-text-field
                v-model="news_edit.judul_artikel"
                :error-messages="errors"
                label="Judul Berita/Artikel/Konten"
                prepend-icon="mdi-alphabetical-variant"
              ></v-text-field>

              <h3 class="mt-2"><b>Kategori Berita/Artikel/Konten</b></h3>
              <v-select
                v-model="news_edit.jenis_artikel"
                :items="jenis_artikel_items"
                item-text="teks"
                item-value="value"
                :error-messages="errors"
                label="Kategori Artikel"
                prepend-icon="mdi-format-list-bulleted-square"
                required
              ></v-select>

              <!-- judul berita -->
              <h3><b>Tanggal Berita/Artikel/Konten</b></h3>
              <v-menu
                ref="menu_tanggal_artikel"
                v-model="menu_tanggal_artikel"
                :close-on-content-click="false"
                :return-value.sync="news_edit.tanggal_artikel"
                transition="scale-transition"
                offset-y
                min-width="auto"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="news_edit.tanggal_artikel"
                    label="Tanggal Artikel"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="news_edit.tanggal_artikel"
                  no-title
                  scrollable
                >
                  <v-spacer></v-spacer>
                  <v-btn
                    text
                    color="primary"
                    @click="news_edit.tanggal_artikel = null"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.menu_tanggal_artikel.save(news_edit.tanggal_artikel)"
                  >
                    OK
                  </v-btn>
                </v-date-picker>
              </v-menu>

              <!-- kalimat pengantar berita -->
              <h3><b>Gambar Depan Berita</b></h3>
              <validation-provider
                v-slot="{ errors }"
                rules="size:20480"
                style="z-index:2010;"

              >
                <v-file-input
                  v-model="news_edit.gambar_pembuka_artikel_new"
                  chips
                  show-size
                  truncate-length="49"
                  :error-messages="errors"
                  label="Gambar Cover Depan Berita"
                  prepend-icon="mdi-image"
                ></v-file-input>
              </validation-provider>
              <h4 class="mt-2"><b>Gambar Depan Berita Yang Sudah Ada</b></h4>
              <v-img  style="max-width:80vw;" :src="url_base + '/api/gambar_pembuka_artikel/'+news_edit.gambar_pembuka_artikel" alt=""/>

              <!-- kalimat pengatar berita -->
              <h3><b>Kalimat Pengantar Berita/Artikel/Konten</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Kalimat Pengantar Berita"
                rules="required"
              >
                <v-text-field
                  v-model="news_edit.teks_pembuka_artikel"
                  :error-messages="errors"
                  label="Kalimat Pengantar Berita/Artikel/Konten"
                  prepend-icon="mdi-alphabetical-variant"
                ></v-text-field>
              </validation-provider>

              <!-- konten berita -->
              <h3><b>Isi Konten</b></h3>
              <ckeditor :editor="editor" v-model="news_edit.teks_isi_artikel" :config="editorConfig" class="mb-4"></ckeditor>

              <!-- tags artikel -->
              <h3 class="mt-2"><b>Tags Konten</b></h3>
              <h5><b>Pisahkan tag dengan enter</b></h5>
                <v-layout wrap>
                  <v-flex xs12>
                    <v-combobox multiple
                      color="primary"
                      v-model="tags_select_edit" 
                      label="Tags Artikel" 
                      append-icon
                      chips
                      deletable-chips
                      class="tag-input"
                      :search-input.sync="tags_search_edit" 
                      @keyup.tab="updateTagsEdit"
                      @paste="updateTagsEdit">
                    </v-combobox>
                  </v-flex>
                </v-layout>
                <!-- {{ tags_search }} -->

              <!-- lampiran -->
              <h3><b>Lampiran/Dokumen Pendukung</b></h3>
              <v-file-input
                v-model="news_edit.lampiran_artikel_new"
                chips
                multiple
                show-size
                truncate-length="49"
                :error-messages="errors"
                label="File/Dokumen/Lampiran untuk Pendukung"
                prepend-icon="mdi-image"
              ></v-file-input>
              
              <!-- Dokumen Bukti Pembukuan Aset yang sudah diupload -->

              <v-combobox
                v-model="lampiran_artikel_temps"
                :filter="filter"
                :hide-no-data="!lampiran_artikel_search"
                :items="lampiran_artikel_deleted"
                :search-input.sync="lampiran_artikel_search"
                hide-selected
                background-color="primary"
                label="Silahkan membatalkan file yang dihapus dengan cara memilih dari dropdown"
                multiple
                small-chips
                solo
              >
                <template v-slot:no-data>
                  <v-list-item>
                    <span class="subheading">Tidak ada file : </span>
                    <v-chip
                      :color="`${colors[lampiran_artikel_nonce - 1]} darken-2`"
                      label
                      small
                    >
                      {{ lampiran_artikel_search }}
                    </v-chip>
                  </v-list-item>
                </template>
                <template v-slot:selection="{ attrs, item, parent, selected }">
                  <v-chip
                    close
                    close-icon="mdi-delete"
                    v-if="item === Object(item)"
                    v-bind="attrs"
                    :color="`${item.color} darken-2`"
                    :input-value="selected"
                    @click="getPreviewFile(item.text, 'lampiran_artikel')"
                    @click:close="parent.selectItem(item)"
                    medium
                  >
                    <span class="pr-2">
                      {{ item.text }}
                    </span>
                  </v-chip>
                </template>
                <template v-slot:item="{ index, item }">
                  <v-chip
                    :color="`${item.color} darken-2`"
                    text-color="black"
                    dark
                    label
                    medium
                  >
                    {{ item.text }}
                  </v-chip>
                  <v-spacer></v-spacer>
                </template>
              </v-combobox>

              <h3><b>Tampilkan Artikel/Berita ke Halaman Publik?</b></h3>
              <v-select
              v-model="news_edit.tampilan_web"
                :items="tampilan_web_items"
                item-text="teks"
                item-value="value"
                label="Tampilankan di Web"
                prepend-icon="mdi-format-list-bulleted-square"
              ></v-select>

              <h3 class="mt-2"><b>Penulis Berita/Artikel/Konten</b></h3>
              <v-text-field
                v-model="news_edit.penulis_artikel"
                :error-messages="errors"
                label="Penulis Berita/Artikel/Konten"
                prepend-icon="mdi-alphabetical-variant"
              ></v-text-field>

              <h3><b>Lokasi</b></h3>
              <div> {{ latlng_titik }} </div>
              <!-- Lokasi Aset -->
              <v-container fluid>
                <div id="cover_map_edit">
                  <div id="mapEdit" ref="mapEdit" style="width: 100%; height: 80vh"></div>
                </div>
              </v-container>
              <v-container fluid>
                <v-card class="mx-auto" >
                  <p class="px-3 mx-1">
                    <b>Kordinat yang digunakan adalah terakhir input :</b> {{ news_edit.geolocation_artikel }}
                  </p>
                </v-card>
              </v-container>
            </form>
          </validation-observer>
          
        </v-container>
      </v-card>
    </v-dialog>
    <!-- DIALOG UNTUK VIEW DATA -->
    <v-dialog v-model="dialogNewsView" fullscreen hide-overlay style="z-index:2051" transition="dialog-bottom-transition">
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
          <v-toolbar-title>{{ news.judul_artikel }} </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn
              dark
              text
              v-if="!edit_crud_dialog"
              @click="submitViewItem()"
            >
              Jadikan Data Ini Default
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-divider></v-divider>
        <v-container fluid>
            <p><b>Judul Artikel</b> : {{ news.judul_artikel }}</p>
            <p><b>Jenis Artikel</b> : {{ news.jenis_artikel }}</p>
            <p><b>Tanggal Artikel</b> : {{ news.tanggal_artikel }}</p>
            <p><b>Teks Pengantar</b> : {{ news.teks_pembuka_artikel }}</p>
            <p><b>Penulis Artikel</b> : {{ news.penulis_artikel }}</p>
            <p><b>Penyunting Artikel</b> : {{ news.penyunting_artikel }}</p>
            <p><b>Tags Artikel</b> :</p>
            <v-chip
                v-for="(filename, index) in tagnames_computed"
                :key="'tag_'+index"
                color="primary darken--2"
                medium
                style="margin:5px;"
              >
              <span class="pr-2">
                {{ filename }}
              </span>
            </v-chip>
            <p><b>Lampiran Artikel</b> : </p>
            <v-chip
                v-for="(filename, index) in filenames_computed"
                :key="'lampiran_'+index"
                color="primary darken--2"
                @click="getPreviewFile(filename, 'lampiran_artikel')"
                medium
                style="margin:5px;"
              >
              <span class="pr-2">
                {{ filename }}
              </span>
            </v-chip>
            <p><b>Isi Artikel/Berita/Konten</b> :</p>
            <ckeditor :editor="editor_read" v-model="news.teks_isi_artikel" :disabled="editorDisabled" :config="editorConfig_read"></ckeditor>
        </v-container>
      </v-card>
    </v-dialog>
    <!-- DIALOG UNTUK CREATE DATA -->
    <v-dialog v-model="dialogNewsCreate" fullscreen hide-overlay style="z-index:2052" transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar
          dark
          :color="warna_crud_dialog">
          <v-btn
            icon
            dark
            @click="closeCreateItem()"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>BUAT BERITA/ARTIKEL/KONTEN </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn
              dark
              text
              @click="submitCreateItem()"
            >
              UPLOAD BERITA
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-divider></v-divider>
        <v-container>
          <validation-observer
            ref="observer"
            v-slot="{ invalid }"
          >
            <form @submit.prevent="submit">
              <!-- judul berita -->
              <h3><b>Judul Berita/Artikel/Konten</b></h3>
              <v-text-field
                v-model="news.judul_artikel"
                :error-messages="errors"
                label="Judul Berita/Artikel/Konten"
                prepend-icon="mdi-alphabetical-variant"
              ></v-text-field>

              <h3 class="mt-2"><b>Kategori Berita/Artikel/Konten</b></h3>
              <v-select
                v-model="news.jenis_artikel"
                :items="jenis_artikel_items"
                item-text="teks"
                item-value="value"
                :error-messages="errors"
                label="Kategori Artikel"
                prepend-icon="mdi-format-list-bulleted-square"
                required
              ></v-select>

              <!-- judul berita -->
              <h3><b>Tanggal Berita/Artikel/Konten</b></h3>
              <v-menu
                ref="menu_tanggal_artikel"
                v-model="menu_tanggal_artikel"
                :close-on-content-click="false"
                :return-value.sync="news.tanggal_artikel"
                transition="scale-transition"
                offset-y
                min-width="auto"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="news.tanggal_artikel"
                    label="Tanggal Artikel"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="news.tanggal_artikel"
                  no-title
                  scrollable
                >
                  <v-spacer></v-spacer>
                  <v-btn
                    text
                    color="primary"
                    @click="news.tanggal_artikel = null"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.menu_tanggal_artikel.save(news.tanggal_artikel)"
                  >
                    OK
                  </v-btn>
                </v-date-picker>
              </v-menu>

              <!-- kalimat pengatar berita -->
              <h3><b>Gambar Depan Berita</b></h3>
              <validation-provider
                v-slot="{ errors }"
                rules="size:20480"
                style="z-index:2010;"

              >
                <v-file-input
                  v-model="news.gambar_pembuka_artikel"
                  chips
                  show-size
                  truncate-length="49"
                  :error-messages="errors"
                  label="Gambar Cover Depan Berita"
                  prepend-icon="mdi-image"
                ></v-file-input>
              </validation-provider>
              <v-img :src="imagePreview" style="max-height:40vh;"/>

              <!-- kalimat pengatar berita -->
              <h3><b>Kalimat Pengantar Berita/Artikel/Konten</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Kalimat Pengantar Berita"
                rules="required"
              >
                <v-text-field
                  v-model="news.teks_pembuka_artikel"
                  :error-messages="errors"
                  label="Kalimat Pengantar Berita/Artikel/Konten"
                  prepend-icon="mdi-alphabetical-variant"
                ></v-text-field>
              </validation-provider>

              <!-- konten berita -->
              <h3><b>Isi Konten</b></h3>
              <ckeditor :editor="editor" v-model="news.teks_isi_artikel" :config="editorConfig"></ckeditor>

              <!-- tags artikek -->
              <h3 class="mt-2"><b>Tags Konten</b></h3>
                <v-layout wrap>
                  <v-flex xs12>
                    <v-combobox multiple
                      color="primary"
                      v-model="tags_select" 
                      label="Tags Artikel" 
                      append-icon
                      chips
                      deletable-chips
                      class="tag-input"
                      :search-input.sync="tags_search" 
                      @keyup.tab="updateTags"
                      @paste="updateTags">
                    </v-combobox>
                  </v-flex>
                </v-layout>
                {{ tags_search }}

              <!-- lampiran -->
              <h3><b>Lampiran/Dokumen Pendukung</b></h3>
              <v-file-input
                    v-model="news.lampiran_artikel"
                    chips
                    multiple
                    show-size
                    truncate-length="49"
                    :error-messages="errors"
                    label="File/Dokumen/Lampiran untuk Pendukung"
                    prepend-icon="mdi-image"
                  ></v-file-input>

              <h3><b>Tampikan Artikel/Berita ke Halaman Publik?</b></h3>
              <v-select
              v-model="news.tampilan_web"
                :items="tampilan_web_items"
                item-text="teks"
                item-value="value"
                label="Tampilankan di Web"
                prepend-icon="mdi-format-list-bulleted-square"
              ></v-select>

              <h3 class="mt-2"><b>Penulis Berita/Artikel/Konten</b></h3>
              <v-text-field
                v-model="news.penulis_artikel"
                :error-messages="errors"
                label="Penulis Berita/Artikel/Konten"
                prepend-icon="mdi-alphabetical-variant"
              ></v-text-field>

              <h3><b>Lokasi</b></h3>
              <div> {{ latlng_titik }} </div>
              <!-- Lokasi Aset -->
              <v-container fluid>
                <div id="cover_map_create">
                  <div id="mapCreate" ref="mapCreate" style="width: 100%; height: 80vh"></div>
                </div>
              </v-container>
              <v-container fluid>
                <v-card class="mx-auto" >
                  <p class="px-3 mx-1">
                    <b>Kordinat yang digunakan adalah terakhir input :</b> {{ news.geolocation_artikel }}
                  </p>
                </v-card>
              </v-container>
            </form>
          </validation-observer>
          
        </v-container>
      </v-card>
    </v-dialog>
    <!-- DIALOG UNTUK KONFIRMASI HAPUS -->
    <v-dialog v-model="dialogKonfirmasiHapus" persistent max-width="50vw">
      <v-card>
        <v-card-title class="text-h5">
          Hapus artikel/berita [{{ news.judul_artikel }}]?
        </v-card-title>
        <v-card-text>Dengan menghapus artikel ini, data akan terhapus didalam daftar berita!</v-card-text>
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
            @click="deleteItem(aset)"
          >
            Ya, hapus artikel
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
      :search="search_aset"
      item-key="nomor_aset"
      class="elevation-1"
    >
      <template v-slot:top>
        <h2 class="mx-4">DAFTAR BERITA 
          <v-btn
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
          </v-btn>
        </h2>
        <v-toolbar
          flat
        >
        <v-text-field
          v-model="search_aset"
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
          color="primary darken--3"
          @click="viewItem(item)"
        >
          LIHAT ARTIKEL
        </v-btn>
        <v-btn text
          small
          class="mr-2"
          color="green"
          @click="editItem(item)"
        >
          EDIT ARTIKEL
        </v-btn>
        <v-btn text
          small
          class="mr-2"
          color="red"
          @click="konfirmasiDeleteItem(item)"
        >
          HAPUS ARTIKEL
        </v-btn>

        <v-btn text
          small
          class="mr-2"
          color="secondary"
          @click="submitVerification(item)"
        >
          VERIFIKASI ARTIKEL
        </v-btn>

        <v-btn text
          small
          class="mr-2"
          color="primary"
          @click="submitUndoneVerification(item)"
        >
          BATALKAN VERIFIKASI
        </v-btn>

      </template>
      <template v-slot:no-data>
        <v-btn
          color="primary"
          @click="readDataFromAPI"
        >
          Reset
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
        menu_tanggal_artikel: false,
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
        news:{
          id_entri:'',
          judul_artikel:'',
          jenis_artikel:'',
          teks_pembuka_artikel:'',
          teks_isi_artikel: '',
          tanggal_artikel:'',
          geolocation_artikel:'',
          gambar_pembuka_artikel:null,
          lampiran_artikel:null,
          tags_artikel:null,
        },
        news_edit:{
          id_entri:'',
          judul_artikel:'',
          jenis_artikel:'',
          teks_pembuka_artikel:'',
          teks_isi_artikel: '',
          tanggal_artikel:'',
          geolocation_artikel:'',
          gambar_pembuka_artikel:null,
          gambar_pembuka_artikel_new: null,
          lampiran_artikel:null,
          lampiran_artikel_new: null,
          tags_artikel:null,
        },
        gambar_pembuka_artikel:'',
        jenis_artikel_items:[
          {teks: 'Pembinaan Statistik', value: 'pembinaan-statistik'},
          {teks: 'Indikator Statistik', value: 'indikator-statistik'},
          {teks: 'Materi Umum', value: 'materi-umum'},
        ],
        tampilan_web_items:[
          {teks: 'Ya', value: 'true'},
          {teks: 'Tidak', value: 'false'},
        ],
        dialog: false,
        dialogNewsEdit: false,
        dialogNewsView:false,
        dialogNewsCreate: false,
        dialogHistori:false,
        dialogKonfirmasiHapus: false,
        dialogKonfirmasiHapusFile: false,
        filenameDihapuskan:'',
        jenisfileDihapuskan: '',
        overlay_list:false,
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
        daftar_kecamatan:[
          {kecamatan:'Bukit Batu', value:'Bukit Batu'},
          {kecamatan:'Jekan Raya', value:'Jekan Raya'},
          {kecamatan:'Pahandut', value:'Pahandut'},
          {kecamatan:'Rakumpit', value:'Rakumpit'},
          {kecamatan:'Sabangau', value:'Sabangau'},
        ],
        status_kepemilikan_tanah_map :{
          hak_milik : {teks: 'Hak Milik', value: 'hak_milik', color: '#fcc44c'},
          hak_guna_usaha : {teks: 'Hak Guna Usaha', value: 'hak_guna_usaha', color: '#cfcb6b'},
          hak_guna_bangunan : {teks: 'Hak Guna Bangunan', value: 'hak_guna_bangunan', color: '#bfd2e7'},
          hak_pakai : {teks: 'Hak Pakai', value: 'hak_pakai', color: '#73b966'},
          hak_pengelolaan : {teks: 'Hak Pengelolaan', value: 'hak_pengelolaan', color: '#ce70ae'},
          hak_wakaf : {teks: 'Hak Wakaf', value: 'hak_wakaf', color: '#657fc1'},
          tanah_adat : {teks: 'Tanah Adat', value: 'tanah_adat', color: '#7becf6'},
          skt_spt : {teks: 'Surat Keterangan Tanah', value: 'skt_spt', color: '#7ecdad'},
          belum_terdaftar : {teks: 'Belum Terdaftar', value: 'belum_terdaftar', color: '#959595'},
          lainnya : {teks: 'Lainnya', value: 'lainnya', color: '#d5d2cd'},
        },
        daftar_kelurahan_desa:[
          {kecamatan:'Bukit Batu', kelurahan:'Banturung', value:'Banturung'},
          {kecamatan:'Bukit Batu', kelurahan:'Habaring Hurung', value:'Habaring Hurung'},
          {kecamatan:'Bukit Batu', kelurahan:'Kanarakan', value:'Kanarakan'},
          {kecamatan:'Bukit Batu', kelurahan:'Marang', value:'Marang'},
          {kecamatan:'Bukit Batu', kelurahan:'Sei Gohong', value:'Sei Gohong'},
          {kecamatan:'Bukit Batu', kelurahan:'Tangkiling', value:'Tangkiling'},
          {kecamatan:'Bukit Batu', kelurahan:'Tumbang Tahai', value:'Tumbang Tahai'},
          {kecamatan:'Jekan Raya', kelurahan:'Bukit Tunggal', value:'Bukit Tunggal'},
          {kecamatan:'Jekan Raya', kelurahan:'Menteng', value:'Menteng'},
          {kecamatan:'Jekan Raya', kelurahan:'Palangka', value:'Palangka'},
          {kecamatan:'Jekan Raya', kelurahan:'Petuk Katimpung', value:'Petuk Katimpung'},
          {kecamatan:'Pahandut', kelurahan:'Langkai', value:'Langkai'},
          {kecamatan:'Pahandut', kelurahan:'Pahandut', value:'Pahandut'},
          {kecamatan:'Pahandut', kelurahan:'Pahandut Seberang', value:'Pahandut Seberang'},
          {kecamatan:'Pahandut', kelurahan:'Panarung', value:'Panarung'},
          {kecamatan:'Pahandut', kelurahan:'Tanjung Pinang', value:'Tanjung Pinang'},
          {kecamatan:'Pahandut', kelurahan:'Tumbang Rungan', value:'Tumbang Rungan'},
          {kecamatan:'Rakumpit', kelurahan:'Bukit Sua', value:'Bukit Sua'},
          {kecamatan:'Rakumpit', kelurahan:'Gaung Baru', value:'Gaung Baru'},
          {kecamatan:'Rakumpit', kelurahan:'Mungku Baru', value:'Mungku Baru'},
          {kecamatan:'Rakumpit', kelurahan:'Pager', value:'Pager'},
          {kecamatan:'Rakumpit', kelurahan:'Panjehang', value:'Panjehang'},
          {kecamatan:'Rakumpit', kelurahan:'Petuk Barunai', value:'Petuk Barunai'},
          {kecamatan:'Rakumpit', kelurahan:'Petuk Bukit', value:'Petuk Bukit'},
          {kecamatan:'Sabangau', kelurahan:'Bereng Bengkel', value:'Bereng Bengkel'},
          {kecamatan:'Sabangau', kelurahan:'Danau Tundai', value:'Danau Tundai'},
          {kecamatan:'Sabangau', kelurahan:'Kalampangan', value:'Kalampangan'},
          {kecamatan:'Sabangau', kelurahan:'Kameloh Baru', value:'Kameloh Baru'},
          {kecamatan:'Sabangau', kelurahan:'Kereng Bangkirai', value:'Kereng Bangkirai'},
          {kecamatan:'Sabangau', kelurahan:'Sabaru', value:'Sabaru'},
        ],
        photos: [],
        desserts:[],
        dessertHeaders: [
          {
            text: 'Daftar Artikel',
            align: 'start',
            sortable: true,
            value: 'judul_artikel',
          },
          { text: 'Jenis Artikel', value: 'jenis_artikel' },
          { text: 'Penulis Artikel', value: 'penulis_artikel' },
          { text: 'Tanggal Artikel', value: 'tanggal_artikel' },
          { text: 'Email Pembuat', value: 'email_creator_artikel' },
          { text: 'Status Verifikasi', value: 'verification_artikel' },
          { text: 'Email Verifikator', value: 'verificator_artikel' },
          { text: 'Actions', value: 'actions', sortable: false },
        ],
        expanded: [],
        singleExpand: false,
        colors: ['green', 'purple', 'indigo', 'cyan', 'teal', 'primary'],
        search_aset:'',
        lampiran_artikel_nonce: 1,
        lampiran_artikel_search: null,
        lampiran_artikel_temps: [],
        lampiran_artikel_deleted: [],
        lampiran_artikel_names: [],
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
      lampiran_artikel_temps (val, prev) {
        if (val.length === prev.length) return
        this.lampiran_artikel_temps = val.map(v => {
          
          if (typeof v.text === 'string') {
            v = {
              text: v.text,
              color: 'secondary',
            }

            this.lampiran_artikel_deleted.push(v)

            this.lampiran_artikel_nonce++
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
        var tags =  (this.news.tags_artikel == null ) ? [] : (this.news.tags_artikel).split(',');
        return tags;
      },
      tageditnames_computed(){
        var tags =  (this.news_edit.tags_artikel == null ) ? [] : (this.news_edit.tags_artikel).split(',');
        return tags;
      },
      imagePreview(){
        if (!this.news.gambar_pembuka_artikel) return null;
          return URL.createObjectURL(this.news.gambar_pembuka_artikel);
      },
    },
    methods: {
      //Reading data from API method. 
      readDataFromAPI() {
        this.loading = true;
        axios.get('/api/get-artikels')
          .then((response) => {
            //Then injecting the result to datatable parameters.
            this.loading = false;
            this.desserts = response.data;
          });
      },
      save_tanggal_artikel () {
        this.$refs.menu_tanggal_artikel.save(news.tanggal_artikel)
      },
      updateTags() {
        this.$nextTick(() => {
          this.tags_select.push(...this.tags_search.split(","));
          this.news.tags_artikel = this.tags_select;
          this.$nextTick(() => {
            this.tags_search = "";
          });
        });
      },
      updateTagsEdit() {
        this.$nextTick(() => {
          this.tags_select_edit.push(...this.tags_search_edit.split(","));
          this.news_edit.tags_artikel = this.tags_select_edit;
          this.$nextTick(() => {
            this.tags_search_edit = "";
          });
        });
      },
      filter (item, queryText, itemText) {
        if (item.header) return false

        const hasValue = val => val != null ? val : ''

        const text = hasValue(itemText)
        const query = hasValue(queryText)

        return text.toString()
          .toLowerCase()
          .indexOf(query.toString().toLowerCase()) > -1
      },
      json2list(json_data){
        var result = [];
        json_data.forEach((datum) => {
          result.push(datum.text);
        });
        // console.log(json_data)
        return result;
      },
      getCentroid(arr) {
        var twoTimesSignedArea = 0;
        var cxTimes6SignedArea = 0;
        var cyTimes6SignedArea = 0;

        var length = arr.length
        // console.log(length)

        var x = function (i) { return arr[i % length][0] };
        var y = function (i) { return arr[i % length][1] };

        for ( var i = 0; i < arr.length; i++) {
            var twoSA = x(i)*y(i+1) - x(i+1)*y(i);
            twoTimesSignedArea += twoSA;
            cxTimes6SignedArea += (x(i) + x(i+1)) * twoSA;
            cyTimes6SignedArea += (y(i) + y(i+1)) * twoSA;
        }
        var sixSignedArea = 3 * twoTimesSignedArea;
        return [ cxTimes6SignedArea / sixSignedArea, cyTimes6SignedArea / sixSignedArea];        
      },
      getPreviewFile(file_name, jenis_file){
        var link = this.url_base + /api/ + jenis_file +'/'+ file_name
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
      download_rekap_aset(){
        this.overlay_list = true
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        axios.get('/api/rekap-asets/', {
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
        this.news = item
        this.dialogKonfirmasiHapus = true
      },
      deleteItem (item) {
        this.overlay_list = true
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }
        var formData = new FormData();
        var $teks = "";
        formData.append('id_artikel', this.news.id_artikel);
        formData.append('id_entri', this.news.id_entri);
        console.log(formData);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        axios.post("/api/hapus-artikel", formData, config).then(response => {
          // console.log(response.data)
          if(response.data.status == false){
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message)
            // console.log(r)
            this.warnaSnackbar= "red"
            this.snackbar = true
          }else{
            this.dialogKonfirmasiHapus = false
            this.teksSnackbar= "Berhasil menghapus artikel "
            this.warnaSnackbar= "success"
            this.snackbar = true
          }
          
        }).catch(errors => {
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data)
          console.log(errors.response.data)
          this.warnaSnackbar= "red"
          this.snackbar = true
        }).finally(() => {
          // this.clear()
          this.readDataFromAPI()
          this.isLoading = false
          this.overlay_list = false
        })   
      },
      viewItem(item){
        // alert(id_entri)
        // this.view_crud_dialog = true
        // this.edit_crud_dialog = false
        this.dialogNewsView = true
        this.news = item
        // this.news.tampilan_web = (this.news.tampilan_web === 'true')
        this.gambar_pembuka_artikel = this.url_base+"/api/gambar_pembuka_artikel/"+this.news.gambar_pembuka_artikel
        this.filenames = item.lampiran_artikel == null ? [] : (item.lampiran_artikel).split(',')
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        
        this.overlay_list = false
      }, 
      editItem(item){
        this.view_crud_dialog = false
        this.edit_crud_dialog = true
        this.dialogNewsEdit = true
        this.news_edit = item
        this.tags_select_edit = item.tags_artikel != null ? (item.tags_artikel).split(',') : [] 
        this.news_edit.lampiran_artikel_new = null
        this.news_edit.gambar_pembuka_artikel_new = null
        // this.news.tampilan_web = (this.news.tampilan_web === 'true')
        // this.news.tampilan_web = (this.news.tampilan_web === 'true')
        this.judul_crud_dialog = 'Edit Data : ' + item.judul_artikel
        this.warna_crud_dialog = 'primary darken--3'

        var lampiran_temps = this.news_edit.lampiran_artikel
        var lampiran_artikel_arr = (lampiran_temps != null ? (lampiran_temps).toString().split(',') : [])
        this.lampiran_artikel_temps = [];
        this.lampiran_artikel_deleted = [];
        (this.lampiran_artikel_deleted).push({ header: 'Batalkan penghapusan file' },);
        for (var i = lampiran_artikel_arr.length - 1; i >= 0; i--) {
          (this.lampiran_artikel_temps).push({text:lampiran_artikel_arr[i], color:'primary'});
        }
        
        var data = []
        var map_temp;
        var refs = this
        if(this.mapEdit != null){
          document.getElementById('cover_map_edit').innerHTML = '<div id="mapEdit" ref="mapEdit" style="width: 100%; height: 80vh"></div>';
        }

        // console.log(JSON.parse(item.geolocation_artikel));

        if(!JSON.parse(item.geolocation_artikel)){
          console.log("Kosong")
          this.dialogNewsEdit = true
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          axios.get('/api/user').then(response => {
            // console.log(response.data)
            
            var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                  osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                  osm = L.tileLayer(osmUrl, { maxZoom: 19, attribution: osmAttrib }),
                  map_temp = new L.Map('mapEdit', { center: [-2.207680, 113.916357], zoom: 15 }),
                  drawnItems = L.featureGroup().addTo(map_temp);
              L.control.layers({
                  'osm': osm.addTo(map_temp),
                  "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', 
                  {
                      attribution: 'google'
                  })
              }).addTo(map_temp);

              map_temp.addControl(new L.Control.Draw({
                  edit: {
                      featureGroup: drawnItems,
                      poly: {
                          allowIntersection: false
                      }
                  },
                  draw: {
                      polygon: {
                          allowIntersection: false,
                          showArea: true
                      },
                      marker : true,
                      circle: false,
                      rectangle: false,
                      circlemarker: false,
                  }
              }));

              map_temp.on('mousemove', function(event){
                // this.tooltip_cords = "bisa";
                // console.log(event.latlng)
                refs.latlng_titik = event.latlng;
                L.drawLocal.draw.handlers.polygon.tooltip.cont = "Latitude : " + event.latlng.lat + " Longitude : "+ event.latlng.lng;
                L.drawLocal.draw.handlers.polyline.tooltip.cont = "Lanjutkan penggambaran garis" + " (Latitude : " + event.latlng.lat + " Longitude : "+ event.latlng.lng +")";
                L.drawLocal.draw.handlers.polyline.tooltip.end = "Silahkan klik tombol Finish untuk mengakhiri penggambaran garis" + " (Latitude : " + event.latlng.lat + " Longitude : "+ event.latlng.lng +")";
              });

              L.drawLocal.draw.handlers.polygon.tooltip.start = "Ambil titik awal";
              L.drawLocal.draw.handlers.polygon.tooltip.end = "Sambungkan titik akhir dengan titik awal untuk menutup kordinat";
              L.drawLocal.draw.handlers.polyline.tooltip.start = "Ambil titik awal";

              map_temp.on(L.Draw.Event.CREATED, function (event) {
                  drawnItems.clearLayers();
                  var layer = event.layer;

                  drawnItems.addLayer(layer);
                  var shape = layer.toGeoJSON()
                  var shape_for_db = JSON.stringify(shape);
                  // simpan shp to table
                  refs.news_edit.geolocation_artikel = shape_for_db;
              });

              map_temp.on('draw:edited', function (e) {
                var layers = e.layers;

                layers.eachLayer(function (layer) {
                    var shape = layer.toGeoJSON()
                    var shape_for_db = JSON.stringify(shape);
                    // simpan shp to table
                    refs.news_edit.geolocation_artikel = shape_for_db;
                });
              });

            refs.mapEdit = map_temp
          }).catch(errors => {
              console.log(errors)
          });
        }else{
          // console.log("ada isi "+item.geolocation_artikel)
          this.dialogNewsEdit = true
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          axios.get('/api/user').then(response => {
            // console.log(response.data)
            
            var lokasi_temp = JSON.parse(item.geolocation_artikel);
            // console.log(lokasi_temp["geometry"]["coordinates"])
            var lotlat_temp = lokasi_temp['geometry']['type'] == 'Polygon' ? lokasi_temp["geometry"]["coordinates"][0][0] : (lokasi_temp['geometry']['type'] == 'LineString' ? lokasi_temp["geometry"]["coordinates"][0] : lokasi_temp["geometry"]["coordinates"])
            var lat_temp = lotlat_temp[1]
            var lot_temp = lotlat_temp[0]
            
            var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    osm = L.tileLayer(osmUrl, { maxZoom: 19, attribution: osmAttrib }),
                    map_temp = new L.Map('mapEdit', { center: [lat_temp, lot_temp], zoom: 15 }),
                    drawnItems = L.featureGroup().addTo(map_temp);
            L.control.layers({
                'osm': osm.addTo(map_temp),
                "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', 
                {
                    attribution: 'google'
                })
            }).addTo(map_temp);

            map_temp.addControl(new L.Control.Draw({
                  edit: {
                      featureGroup: drawnItems,
                      poly: {
                          allowIntersection: false
                      }
                  },
                  draw: {
                      polygon: {
                          allowIntersection: false,
                          showArea: true
                      },
                      marker: true,
                      circle: false,
                      rectangle: false,
                      circlemarker: false,
                  }
              }));

              map_temp.on('mousemove', function(event){
                // this.tooltip_cords = "bisa";
                // console.log(event.latlng)
                refs.latlng_titik = event.latlng;
                L.drawLocal.draw.handlers.polygon.tooltip.cont = "Latitude : " + event.latlng.lat + " Longitude : "+ event.latlng.lng;
                L.drawLocal.draw.handlers.polyline.tooltip.cont = "Lanjutkan penggambaran garis" + " (Latitude : " + event.latlng.lat + " Longitude : "+ event.latlng.lng +")";
                L.drawLocal.draw.handlers.polyline.tooltip.end = "Silahkan klik tombol Finish untuk mengakhiri penggambaran garis" + " (Latitude : " + event.latlng.lat + " Longitude : "+ event.latlng.lng +")";
              });

              L.drawLocal.draw.handlers.polygon.tooltip.start = "Ambil titik awal";
              L.drawLocal.draw.handlers.polygon.tooltip.end = "Sambungkan titik akhir dengan titik awal untuk menutup kordinat";
              L.drawLocal.draw.handlers.polyline.tooltip.start = "Ambil titik awal";

              map_temp.on(L.Draw.Event.CREATED, function (event) {
                  drawnItems.clearLayers();
                  var layer = event.layer;

                  drawnItems.addLayer(layer);
                  var shape = layer.toGeoJSON()
                  var shape_for_db = JSON.stringify(shape);
                  // simpan shp to table
                  refs.news.geolocation_artikel = shape_for_db;
              });

              map_temp.on('draw:edited', function (e) {
                var layers = e.layers;

                layers.eachLayer(function (layer) {
                    var shape = layer.toGeoJSON()
                    var shape_for_db = JSON.stringify(shape);
                    // simpan shp to table
                    refs.news.geolocation_artikel = shape_for_db;
                });
              });

            var layerGroup = L.geoJSON(JSON.parse(item.geolocation_artikel),
              { 
                //feature untuk data hasil parsing, layer untuk aplikasi
                onEachFeature: function (feature, layer) {
                  layer.bindPopup('<h3>'+item.label_artikel+'</h3><p>nomor: '+item.nomor_surat+'</p></b><p>tahun: '+item.tahun_perolehan+'</p>');
                  if (feature.geometry.type == 'Polygon') {
                    layer.setStyle({
                      fillColor : 'yellow',
                      fillOpacity: 0.3,
                      color: 'black'
                    })
                  } else if(feature.geometry.type == 'LineString'){
                    layer.setStyle({
                      weight: 4,
                      dashArray: '5, 10',
                      lineCap: 'square', // Optional, just to avoid round borders.
                      color: 'black'
                    })
                  }else if(location.jenis_artikel == 'mobil'){
                    // layer.setIcon(car)
                  }else if(location.jenis_artikel == 'motor'){
                    // layer.setIcon(motor)
                  }else if(location.jenis_artikel == 'public'){
                    // layer.setIcon(prasarana)
                  }else if(location.jenis_artikel == 'building'){
                    // layer.setIcon(building)
                  }else {
                    console.log('green')
                  }
                },
                
              })
            layerGroup.addTo(map_temp);

            refs.mapEdit = map_temp
          }).catch(errors => {
              console.log(errors)
          });
        }
        this.readDataFromAPI()   
      },
      createItem(){
        this.news.id_entri=''
        this.news.judul_artikel=''
        this.news.jenis_artikel=null
        this.news.teks_pembuka_artikel=''
        this.news.teks_isi_artikel= ''
        this.news.tanggal_artikel=''
        this.news.geolocation_artikel=''
        this.news.gambar_pembuka_artikel=null
        this.news.lampiran_artikel=null
        this.news.tags_artikel=null
        this.dialogNewsCreate = true
        var data = []
        var map_temp;
        var refs = this
        if(this.mapCreate != null){
          document.getElementById('cover_map_create').innerHTML = '<div id="mapCreate" ref="mapCreate" style="width: 100%; height: 80vh"></div>';
        }
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        axios.get('/api/user').then(response => {
          var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                osm = L.tileLayer(osmUrl, { maxZoom: 19, attribution: osmAttrib }),
                map_temp = new L.Map('mapCreate', { center: [-2.207680, 113.916357], zoom: 15 }),
                drawnItems = L.featureGroup().addTo(map_temp);
            L.control.layers({
                'osm': osm.addTo(map_temp),
                "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', 
                {
                    attribution: 'google'
                })
            }).addTo(map_temp);

            map_temp.addControl(new L.Control.Draw({
                edit: {
                    featureGroup: drawnItems,
                    poly: {
                        allowIntersection: false
                    }
                },
                draw: {
                    polygon: {
                        allowIntersection: false,
                        showArea: true
                    },
                    marker : true,
                    circle: false,
                    rectangle: false,
                    circlemarker: false,
                }
            }));

            map_temp.on('mousemove', function(event){
              // this.tooltip_cords = "bisa";
              // console.log(event.latlng)
              refs.latlng_titik = event.latlng;
              L.drawLocal.draw.handlers.polygon.tooltip.cont = "Latitude : " + event.latlng.lat + " Longitude : "+ event.latlng.lng;
              L.drawLocal.draw.handlers.polyline.tooltip.cont = "Lanjutkan penggambaran garis" + " (Latitude : " + event.latlng.lat + " Longitude : "+ event.latlng.lng +")";
              L.drawLocal.draw.handlers.polyline.tooltip.end = "Silahkan klik tombol Finish untuk mengakhiri penggambaran garis" + " (Latitude : " + event.latlng.lat + " Longitude : "+ event.latlng.lng +")";
            });

            L.drawLocal.draw.handlers.polygon.tooltip.start = "Ambil titik awal";
            L.drawLocal.draw.handlers.polygon.tooltip.end = "Sambungkan titik akhir dengan titik awal untuk menutup kordinat";
            L.drawLocal.draw.handlers.polyline.tooltip.start = "Ambil titik awal";

            map_temp.on(L.Draw.Event.CREATED, function (event) {
                drawnItems.clearLayers();
                var layer = event.layer;

                drawnItems.addLayer(layer);
                var shape = layer.toGeoJSON()
                var shape_for_db = JSON.stringify(shape);
                // simpan shp to table
                refs.news.geolocation_artikel = shape_for_db;
            });

            map_temp.on('draw:edited', function (e) {
              var layers = e.layers;

              layers.eachLayer(function (layer) {
                  var shape = layer.toGeoJSON()
                  var shape_for_db = JSON.stringify(shape);
                  // simpan shp to table
                  refs.news.geolocation_artikel = shape_for_db;
              });
            });

          refs.mapEdit = map_temp
        }).catch(errors => {
            console.log(errors)
        });
      },
      submitEditItem(){
        this.$refs.observer.validate()
        this.overlay_list = true
        this.isLoading = "white"

        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }

        // declare form data
        var formData = new FormData();

        // files
        var $teks = "";
        if(this.news_edit.gambar_pembuka_artikel_new != null){
          // for (let file of this.news.gambar_pembuka_artikel) {
            var file = this.news_edit.gambar_pembuka_artikel_new
            formData.append("gambar_pembuka_artikel_new", file, file.name);
            $teks = $teks + ' '+file.name;
          // }
        }

        var lampiran_artikel_new_names =[];
        if(this.news_edit.lampiran_artikel_new != null){
          for (let file of this.news_edit.lampiran_artikel_new) {
            formData.append("lampiran_artikel_new[]", file, file.name);
            lampiran_artikel_new_names.push(file.name);
          }
        }
        var lampiran_artikel_temps_arr = this.json2list(this.lampiran_artikel_temps);
        var lampiran_artikel_deleted_arr = this.json2list(this.lampiran_artikel_deleted);
        formData.append('lampiran_artikel_temps', JSON.stringify(lampiran_artikel_temps_arr));
        formData.append('lampiran_artikel_deleted', JSON.stringify(lampiran_artikel_deleted_arr));
        formData.append('judul_artikel', this.news_edit.judul_artikel);
        formData.append('id_artikel', this.news_edit.id_artikel);
        formData.append('gambar_pembuka_artikel', this.news_edit.gambar_pembuka_artikel);
        formData.append('penulis_artikel', this.news_edit.penulis_artikel);
        formData.append('jenis_artikel', this.news_edit.jenis_artikel);
        formData.append('teks_pembuka_artikel', this.news_edit.teks_pembuka_artikel);
        formData.append('teks_isi_artikel', this.news_edit.teks_isi_artikel);
        formData.append('tags_artikel', JSON.stringify(this.tags_select_edit));
        formData.append('tanggal_artikel', this.news_edit.tanggal_artikel);
        formData.append('tampilan_web', this.news_edit.tampilan_web);
        formData.append('lampiran_artikel_filename', JSON.stringify(lampiran_artikel_new_names));
        formData.append('geolocation_artikel', this.news_edit.geolocation_artikel);

        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        axios.post("/api/update-artikel", formData, config).then(response => {
          console.log(response.data)
          this.teksSnackbar= "Berhasil memutakhirkan artikel "
          this.warnaSnackbar= "success"
        }).catch(errors => {
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data)
          console.log(errors.response.data)
          this.warnaSnackbar= "red"
        }).finally(() => {
          this.readDataFromAPI()
          this.dialogNewsEdit = false 
          this.isLoading = false
          this.overlay_list = false
          this.snackbar = true
        })
        this.overlay_list = false
        this.isLoading = "white"
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
          console.log(response.data)
          this.teksSnackbar= "Berhasil memutakhirkan data aset "
          this.warnaSnackbar= "success"
        }).catch(errors => {
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data)
          console.log(errors.response.data)
          this.warnaSnackbar= "red"
        }).finally(() => {
          // this.clear()
          this.readDataFromAPI()
          this.dialogNewsView = false 
          this.dialogHistori = false
          this.isLoading = false
          this.overlay_list = false
          this.snackbar = true
        })
      },
      submitCreateItem(){
        // console.log(this.news)
        // start of upload
        this.overlay_list = true
        this.isLoading = "orange"
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }

        var formData = new FormData();

        // files
        var $teks = "";
        if(this.news.gambar_pembuka_artikel != null){
          // for (let file of this.news.gambar_pembuka_artikel) {
            var file = this.news.gambar_pembuka_artikel
            formData.append("gambar_pembuka_artikel", file, file.name);
            $teks = $teks + ' '+file.name;
          // }
        }

        var $teks_lampiran_artikel = "";
        var lampiran_artikel_filename=[];
        if(this.news.lampiran_artikel != null){
          for (let file of this.news.lampiran_artikel) {
            formData.append("lampiran_artikel[]", file, file.name);
            $teks_lampiran_artikel = $teks_lampiran_artikel + ' '+file.name;
            lampiran_artikel_filename.push(file.name);

          }
        }
        // console.log(lampiran_artikel_filename); 
        
        // additional data
        formData.append('judul_artikel', this.news.judul_artikel);
        formData.append('penulis_artikel', this.news.penulis_artikel);
        formData.append('jenis_artikel', this.news.jenis_artikel);
        formData.append('teks_pembuka_artikel', this.news.teks_pembuka_artikel);
        formData.append('teks_isi_artikel', this.news.teks_isi_artikel);
        formData.append('tags_artikel', JSON.stringify(this.tags_select));
        formData.append('tanggal_artikel', this.news.tanggal_artikel);
        formData.append('tampilan_web', this.news.tampilan_web);
        formData.append('lampiran_artikel_filename', JSON.stringify(lampiran_artikel_filename));
        formData.append('geolocation_artikel', this.news.geolocation_artikel);
        console.log(JSON.stringify(this.tags_select));
        axios.post("/api/insert-artikel", formData, config).then(response => {
          // console.log(response.data)
          if(response.data.status == false){
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }else{
            this.teksSnackbar= "Berhasil menambahkan data aset ",
            this.warnaSnackbar= "green",
            this.snackbar = true
            this.clearCreateItem()
          }
        }).catch(errors => {
          console.log(errors.response.data)
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data.errors),
          this.warnaSnackbar= "red",
          this.snackbar = true
        }).finally(() => {
          this.readDataFromAPI();
          this.isLoading = false
          this.overlay_entri = false
          this.overlay_list = false
        });
        // end of upload
      },
      submitVerification(item){
        // console.log(this.news)
        // start of upload
        this.overlay_list = true
        this.isLoading = "orange"
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }
        var formData = new FormData();
        formData.append('id_artikel', item.id_artikel);
        formData.append('verification_artikel', 'true');

        axios.post("/api/verification-artikel", formData, config).then(response => {
          // console.log(response.data)
          if(response.data.status == false){
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }else{
            this.teksSnackbar= "Berhasil menambahkan data aset ",
            this.warnaSnackbar= "green",
            this.snackbar = true
            this.clearCreateItem()
          }
        }).catch(errors => {
          console.log(errors.response.data)
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data.errors),
          this.warnaSnackbar= "red",
          this.snackbar = true
        }).finally(() => {
          this.readDataFromAPI();
          this.isLoading = false
          this.overlay_entri = false
          this.overlay_list = false
        });
        // end of upload
      },
      submitUndoneVerification(item){
        // console.log(this.news)
        // start of upload
        this.overlay_list = true
        this.isLoading = "orange"
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }
        var formData = new FormData();
        formData.append('id_artikel', item.id_artikel);
        formData.append('verification_artikel', 'false');

        axios.post("/api/verification-artikel", formData, config).then(response => {
          // console.log(response.data)
          if(response.data.status == false){
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }else{
            this.teksSnackbar= "Berhasil menambahkan data aset ",
            this.warnaSnackbar= "green",
            this.snackbar = true
          }
        }).catch(errors => {
          console.log(errors.response.data)
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data.errors),
          this.warnaSnackbar= "red",
          this.snackbar = true
        }).finally(() => {
          this.readDataFromAPI();
          this.isLoading = false
          this.overlay_entri = false
          this.overlay_list = false
        });
        // end of upload
      },
      initMap(){
        var ref = this;
        var map
        axios.get('/api/user').then(response => {
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
        this.news.id_entri=''
        this.news.id_artikel=''
        this.news.judul_artikel=''
        this.news.teks_pembuka_artikel=''
        this.news.teks_isi_artikel= ''
        this.news.tanggal_artikel=''
        this.news.geolocation_artikel=''
        this.news.gambar_pembuka_artikel=null
        this.news.lampiran_artikel=null
        this.news.tags_artikel=null
        // this.$refs.observer.reset()
      },
      clearViewItem(){
        this.aset.tanggal_pembelian= ''
        this.aset.tanggal_pembukuan= ''
        this.aset.label_aset= ''
        this.aset.luas_aset= ''
        this.aset.alamat_aset= ''
        this.aset.kelurahan_aset= ''
        this.aset.kecamatan_aset= ''
        this.aset.jenis_perolehan= ''
        this.aset.jenis_perolehan_lainnya= ''
        this.aset.subjek_perolehan= ''
        this.aset.tahun_perolehan= ''
        this.aset.nilai_perolehan= ''
        this.aset.dokumen_kepemilikan_aset= ''
        this.aset.nomor_surat= ''
        this.aset.nomor_sertifikat= ''
        this.aset.tanggal_sertifikat= ''
        this.aset.tanggal_pembukuan_sertifikat= ''
        this.aset.tanggal_penerbitan_sertifikat= ''
        this.aset.kepemilikan_sertifikat= ''
        this.aset.kenamaan_sertifikat= ''
        this.aset.lokasi_sertifikat= ''
        this.aset.rencana_sertifikasi= ''
        this.aset.kendala_sertifikasi= ''
        this.aset.jenis_aset= ''
        this.aset.pengguna_barang_tingkat_pertama=null
        this.aset.pengguna_barang_tingkat_pertama_lainnya=''
        this.aset.status_kepemilikan_tanah= ''
        this.aset.nib= ''
        this.aset.nilai_pembukuan= ''
        this.aset.tampilan_web= true
        this.aset.geolocation_aset= {}
        this.aset.foto_aset= null
        this.aset.keterangan_aset= ''
        this.aset.tanggal_nilai_pembukuan= ''
        this.aset.bukti_pembelian_aset=null
        this.aset.bukti_pembukuan_aset=null
        this.aset.bukti_kepemilikan_aset=null
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
        this.dialogNewsEdit = false
        // this.clear()
      },
      closeViewItem() {
        this.dialogNewsView = false
        this.clearViewItem()
      },
      closeCreateItem(){
        this.dialogNewsCreate = false
        this.clearCreateItem()
      },
    },
    //this will trigger in the onReady State
    mounted() {
      // console.log(this.$refs['mapID']);
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      this.readDataFromAPI();
      // this.editor_read.isReadOnly = true;
      this.initMap();
    },
    created(){
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      axios.get('/api/user-permission').then(response => {
          this.currentUser = response.data.user;
          this.$store.commit('updateRBAC', response.data.permissions)
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