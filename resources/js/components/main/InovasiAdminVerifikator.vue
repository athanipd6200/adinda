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
    <!-- DIALOG UNTUK EDIT -->
    <v-dialog v-model="dialogPembinaanEdit" fullscreen hide-overlay style="z-index:2050" transition="dialog-bottom-transition">
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
          <v-toolbar-title>EDIT PEMBINAAN </v-toolbar-title>
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
            ref="observer"
            v-slot="{ invalid }"
          >
            <form @submit.prevent="submit">
              <h2 class="mt-4 secondary--text"><b>Profil Pembinaan</b></h2>
              <!-- penanggung jawab pembinaan -->
              <h3><b>Penanggung Jawab Pembinaan</b></h3>
              <v-text-field
                v-model="pembinaan.pembina_pembinaan"
                :error-messages="errors"
                label="Penanggung Jawab Pembinaan"
                prepend-icon="mdi-account"
              ></v-text-field>

              <!-- judul pembinaan -->
              <h3><b>Judul Pembinaan</b></h3>
              <v-text-field
                v-model="pembinaan.judul_pembinaan"
                :error-messages="errors"
                label="Judul Pembinaan"
                prepend-icon="mdi-alphabetical-variant"
              ></v-text-field>

              <!-- kategori pembinaan -->
              <h3 class="mt-2"><b>Kategori/Jenis Pembinaan</b></h3>
              <v-select
                v-model="pembinaan.jenis_pembinaan"
                :items="jenis_pembinaan_items"
                item-text="teks"
                item-value="value"
                :error-messages="errors"
                label="Kategori/Jenis Pembinaan"
                prepend-icon="mdi-format-list-bulleted-square"
                required
              ></v-select>

              <!-- kalimat pengantar pembinaan -->
              <h3><b>Kalimat Pengantar Pembinaan</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Kalimat Pengantar Pembinaan"
                rules="required"
              >
                <v-textarea
                  v-model="pembinaan.teks_pengantar_pembinaan"
                  :error-messages="errors"
                  filled
                  auto-grow
                  rows="4"
                  row-height="30"
                  shaped
                  background-color="secondary lighten-3"
                  class="white--text"
                  label="Kalimat Pengantar/Pembuka Pembinaan"
                ></v-textarea>
              </validation-provider>

              <!-- sampul pembinaan -->
              <h3><b>Gambar Depan Pembinaan</b></h3>
              <validation-provider
                v-slot="{ errors }"
                rules="size:20480"
                style="z-index:2010;"

              >
                <v-file-input
                  v-model="pembinaan.sampul_pembinaan"
                  chips
                  show-size
                  truncate-length="49"
                  :error-messages="errors"
                  label="Gambar Cover Depan Pembinaan"
                  prepend-icon="mdi-image"
                ></v-file-input>
              </validation-provider>
              <v-img  style="max-width:80vw;" :src="url_base + '/api/sampul_pembinaan/'+pembinaan.image_preview" alt=""/>

              <v-divider class="my-4"></v-divider>

              <!-- tanggal awal pembinaan -->
              <h3><b>Tanggal Awal Pembinaan</b></h3>
              <v-menu
                ref="menu_tanggal_awal_pembinaan"
                v-model="menu_tanggal_awal_pembinaan"
                :close-on-content-click="false"
                :return-value.sync="pembinaan.tanggal_awal_pembinaan"
                transition="scale-transition"
                offset-y
                min-width="auto"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="pembinaan.tanggal_awal_pembinaan"
                    label="Tanggal Awal Pembinaan"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="pembinaan.tanggal_awal_pembinaan"
                  no-title
                  scrollable
                >
                  <v-spacer></v-spacer>
                  <v-btn
                    text
                    color="primary"
                    @click="pembinaan.tanggal_awal_pembinaan = null"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.menu_tanggal_awal_pembinaan.save(pembinaan.tanggal_awal_pembinaan)"
                  >
                    OK
                  </v-btn>
                </v-date-picker>
              </v-menu>

              <!-- tanggal akhir pembinaan -->
              <h3><b>Tanggal Akhir Pembinaan</b></h3>
              <v-menu
                ref="menu_tanggal_akhir_pembinaan"
                v-model="menu_tanggal_akhir_pembinaan"
                :close-on-content-click="false"
                :return-value.sync="pembinaan.tanggal_akhir_pembinaan"
                transition="scale-transition"
                offset-y
                min-width="auto"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="pembinaan.tanggal_akhir_pembinaan"
                    label="Tanggal Akhir Pembinaan"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="pembinaan.tanggal_akhir_pembinaan"
                  no-title
                  scrollable
                >
                  <v-spacer></v-spacer>
                  <v-btn
                    text
                    color="primary"
                    @click="pembinaan.tanggal_akhir_pembinaan = null"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.menu_tanggal_akhir_pembinaan.save(pembinaan.tanggal_akhir_pembinaan)"
                  >
                    OK
                  </v-btn>
                </v-date-picker>
              </v-menu>

              <v-divider class="my-4"></v-divider>

              <h2 class="mt-4 secondary--text"><b>Sertifikat, Editor Tambahan, Tags</b></h2>

              <!-- sampul pembinaan -->
              <h3><b>Tautan Sertifikat Pembinaan</b></h3>
              <v-text-field
                v-model="pembinaan.sertifikat_pembinaan"
                :error-messages="errors"
                label="Tautan/Link Sertifikat Pembinaan"
                prepend-icon="mdi-certificate"
              ></v-text-field>

              <h3 class="mt-2"><b>Editor Tambahan untuk Pembinaan</b></h3>
              <h5>Pisahkan dengan enter</h5>
              <v-layout wrap>
                <v-flex xs12>
                  <v-combobox multiple
                    color="primary"
                    v-model="editors_select" 
                    label="Email penyunting tambahan pembinaan" 
                    append-icon
                    chips
                    deletable-chips
                    class="tag-input"
                    :search-input.sync="editors_search" 
                    @keyup.tab="updateEditors"
                    @paste="updateEditors">
                  </v-combobox>
                </v-flex>
              </v-layout>

              <!-- tags pembinaan -->
              <h3 class="mt-2"><b>Tags/Hashtag Pembinaan </b></h3>
              <h5>Pisahkan dengan enter tanpa hashtag</h5>
              <v-layout wrap>
                <v-flex xs12>
                  <v-combobox multiple
                    color="primary"
                    v-model="tags_select" 
                    label="Tags Pembinaan" 
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
                <!-- {{ tags_search }} -->

              <v-divider class="my-4"></v-divider>

              <!-- BAGIAN AKSES2 PEMBINAAN -->
              
              <h2 class="mt-4 secondary--text"><b>Hak Akses Pembinaan</b></h2>
              
              <h3><b>Akses Tampilkan Pembinaan Ke Publik (Untuk Lihat)</b></h3>
              <v-select
                v-model="pembinaan.able_akses_halaman_pembinaan"
                :items="tampilan_web_items"
                item-text="teks"
                item-value="value"
                label="Tampilankan di Halaman Muka/Publik?"
                prepend-icon="mdi-format-list-bulleted-square"
              ></v-select>

              <h3><b>Akses Pendaftaran secara mandiri dibuka?</b></h3>
              <v-select
                v-model="pembinaan.able_akses_pendaftaran_pembinaan"
                :items="tampilan_web_items"
                item-text="teks"
                item-value="value"
                label="Buka akses pendaftaran mandiri?"
                prepend-icon="mdi-format-list-bulleted-square"
              ></v-select>

              <h3><b>Akses Sertifikat langsung dibuka?</b></h3>
              <v-select
                v-model="pembinaan.able_akses_sertifikat_pembinaan"
                :items="tampilan_web_items"
                item-text="teks"
                item-value="value"
                label="Tampikan file sertifikat kepada pengguna?"
                prepend-icon="mdi-format-list-bulleted-square"
              ></v-select>

              <h3><b>Hanya Super Admin yang bisa edit?</b></h3>
              <v-select
                v-model="pembinaan.only_super_admin"
                :items="tampilan_web_items"
                item-text="teks"
                item-value="value"
                label="Hanya super admin yang bisa edit>"
                prepend-icon="mdi-format-list-bulleted-square"
              ></v-select>

              <v-divider class="my-4"></v-divider>

              <!-- BAGIAN AKSES2 PEMBINAAN -->
              
              <h2 class="mt-4 secondary--text darken-2--text"><b>Bagian Sesi</b> 
                <v-btn
                  class="mx-2 my-2"
                  fab
                  dark
                  small
                  @click="createSession()"
                  color="primary">
                  <v-icon dark>
                    mdi-plus
                  </v-icon>
                </v-btn>
              </h2>

              <div class="col-12">
                <draggable tag="ul" :list="list_sesi_edit" class="list-group" handle=".handle">
                  <div
                    class="my-4 list-group-item"
                    v-for="(element, idx) in list_sesi_edit"
                    :key="'sesi_'+element.name+'_'+idx"
                  >
                    <v-row class="my-4">

                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-icon v-on="on" v-bind="attrs" color="secondary lighten-1" class="mr-2 handle">mdi-gesture-tap-hold</v-icon>
                        </template>
                        <span>Klik dan tahan untuk memindahkan urutan sesi</span>
                      </v-tooltip>

                      
                      <h3 class="mx-2 secondary--text lighten-1--text">SESI {{idx + 1}}</h3>

                      <v-spacer></v-spacer>

                      <v-icon class="red--text ml-2 close" @click="removeAt(idx)">mdi-close</v-icon>
                    </v-row>
                    <v-row class="ma-2">
                      <v-expansion-panels focusable>
                        <v-expansion-panel>
                          <v-expansion-panel-header>Detail</v-expansion-panel-header>
                            <v-expansion-panel-content style="padding: 5px;min-height:45vh;">
                              <!-- Detail session -->
                              <!-- sambutan sesi -->
                              <h3 class="my-2 secondary--text lighten-1--text"><b>Profil Sesi</b></h3>
                              <h4><b>Nama Sesi</b></h4>
                              <v-text-field
                                v-model="sesi_edit.judul_sesi[element.id]"
                                :error-messages="errors"
                                label="Nama sesi"
                                prepend-icon="mdi-format-title"
                              ></v-text-field>

                              <!-- kategori pembinaan -->
                              <h4 class="mt-2"><b>Kategori/Jenis Sesi</b></h4>
                              <v-select
                                v-model="sesi_edit.jenis_sesi[element.id]"
                                :items="jenis_pembinaan_items"
                                item-text="teks"
                                item-value="value"
                                :error-messages="errors"
                                label="Kategori/Jenis Pembinaan"
                                prepend-icon="mdi-format-list-bulleted-square"
                                required
                              ></v-select>

                              <!-- waktu pembinaan awal -->
                              <h4 class="mt-2"><b>Mulai Sesi</b></h4>
                              <v-row>
                                <v-col class="col-6">
                                  <v-date-picker
                                    v-model="sesi_edit.tanggal_awal_sesi[element.id]"
                                    color="green lighten-1"
                                    header-color="primary"
                                  ></v-date-picker>
                                </v-col>
                                <v-col class="col-6">
                                  <v-time-picker
                                    v-model="sesi_edit.waktu_awal_sesi[element.id]"
                                    format="24hr"
                                  ></v-time-picker>
                                </v-col>
                              </v-row>

                              <!-- waktu pembinaan awal -->
                              <h4 class="mt-2"><b>Akhir Sesi</b></h4>
                              <v-row>
                                <v-col class="col-6">
                                  <v-date-picker
                                    v-model="sesi_edit.tanggal_akhir_sesi[element.id]"
                                    color="green lighten-1"
                                    header-color="primary"
                                  ></v-date-picker>
                                </v-col>
                                <v-col class="col-6">
                                  <v-time-picker
                                    v-model="sesi_edit.waktu_akhir_sesi[element.id]"
                                    format="24hr"
                                  ></v-time-picker>
                                </v-col>
                              </v-row>

                              <!-- pemateri sesi -->
                              <h4  class="mt-2"><b>Pemateri oleh</b></h4>
                              <v-text-field
                                :error-messages="errors"
                                label="Pemateri sesi"
                                prepend-icon="mdi-account-group"
                                class='pemateri_sesi'
                                v-model="sesi_edit.pemateri_sesi[element.id]"
                              ></v-text-field>

                              <v-divider class="my-2"></v-divider>  
                              <v-row>
                                <h3 class="my-2 secondary--text lighten-1--text"><b>Tambahan Profile Sesi</b></h3>
                                <v-tooltip bottom>
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-icon v-on="on" v-bind="attrs" class="mx-2" color="secondary lighten-1">mdi-help-circle</v-icon>
                                  </template>
                                  <span>Tidak wajib diisi</span>
                                </v-tooltip>
                                </v-row>
                              <!-- sambutan sesi -->
                              <h4 class="mt-2 grey--text darken-2--text"><b>Sambutan oleh</b></h4>
                              <h5>*jika ada</h5>
                              <v-text-field
                                :error-messages="errors"
                                label="Sambutan sesi"
                                prepend-icon="mdi-account-question"
                                class='sambutan_sesi'
                                v-model="sesi_edit.sambutan_sesi[element.id]"
                              ></v-text-field>

                              <!-- moderator sesi -->
                              <h4 class="mt-2 grey--text darken-2--text"><b>Moderator oleh</b></h4>
                              <h5>*jika ada</h5>
                              <v-text-field
                                :error-messages="errors"
                                label="Moderator sesi"
                                prepend-icon="mdi-account-question"
                                class='moderator_sesi'
                                v-model="sesi_edit.moderator_sesi[element.id]"
                              ></v-text-field>

                              <!-- penampil sesi -->
                              <h4 class="mt-2 grey--text darken-2--text"><b>Penampilan\Pertunjukkan oleh</b></h4>
                              <h5>*jika ada</h5>
                              <v-text-field
                                :error-messages="errors"
                                label="Penampilan sesi"
                                prepend-icon="mdi-account-question"
                                class='penampil_sesi'
                                v-model="sesi_edit.penampil_sesi[element.id]"
                              ></v-text-field>

                              <v-divider class="my-2"></v-divider>

                              <h3 class="my-2 secondary--text lighten-1--text"><b>Konten Sesi</b></h3>
                              

                              <h4 class="mt-2"><b>Isi Konten/Penjelasan Sesi</b></h4>
                              <ckeditor 
                                :editor="editor" 
                                class='moderator_sesi mb-4'
                                v-model="sesi_edit.konten_sesi[element.id]" 
                                :config="editorConfig" 
                                ></ckeditor>

                              <v-divider class="my-2"></v-divider>

                              <h3 class="my-2 secondary--text lighten-1--text"><b>Tautan/Link dan Materi terkait Sesi</b></h3>

                              <!-- tautan sesi -->
                              <h4 class="mt-2"><b>Tautan/Link Kelas Interaktif (Zoom, Google Meet)</b></h4>
                              <v-text-field
                                :error-messages="errors"
                                label="Tautan/Link Kelas Interaktif"
                                prepend-icon="mdi-google-classroom"
                                class='tautan_sesi'
                                v-model="sesi_edit.tautan_sesi[element.id]"
                              ></v-text-field>

                              <!-- bahan materi sesi -->
                               <h4 class="mt-2"><b>Tautan/Link Materi Sesi</b></h4>
                              <v-text-field
                                :error-messages="errors"
                                label="Tautan materi"
                                prepend-icon="mdi-clipboard-text"
                                class='materi_sesi'
                                v-model="sesi_edit.materi_sesi[element.id]"
                              ></v-text-field>

                              <!-- kuis sesi -->
                              <h4 class="mt-2"><b>Tautan/Link Kuis Tugas Sesi</b></h4>
                              <v-text-field
                                :error-messages="errors"
                                label="Tautan tugas"
                                prepend-icon="mdi-clipboard-text"
                                class='tugas_sesi'
                                v-model="sesi_edit.tugas_sesi[element.id]"
                              ></v-text-field>

                              <!-- <h4 class="mt-2"><b>Lampiran/Dokumen Materi Sesi</b></h4>
                              <v-file-input
                                chips
                                multiple
                                show-size
                                truncate-length="49"
                                :error-messages="errors"
                                label="File/Dokumen/Lampiran untuk Pendukung"
                                prepend-icon="mdi-file"
                                class='lampiran_sesi'
                                v-model="sesi_edit.lampiran_sesi[element.id]"
                              ></v-file-input> -->

                              <v-divider class="my-4"></v-divider>
                              <h3 class="my-2 secondary--text lighten-1--text"><b>Hak Akses Sesi</b></h3>
                              
                              <h4 class="mt-2"><b>Buka akses tautan sesi?</b></h4>
                              <v-select
                                :items="tampilan_web_items"
                                item-text="teks"
                                item-value="value"
                                label="Buka akses tautan sesi"
                                prepend-icon="mdi-format-list-bulleted-square"
                                class='able_tautan_sesi'
                                v-model="sesi_edit.able_tautan_sesi[element.id]"
                              ></v-select>

                              <h4 class="mt-2"><b>Buka akses tautan tugas?</b></h4>
                              <v-select
                                :items="tampilan_web_items"
                                item-text="teks"
                                item-value="value"
                                label="Buka akses tautan tugas"
                                prepend-icon="mdi-format-list-bulleted-square"
                                class='able_tugas_sesi'
                                v-model="sesi_edit.able_tugas_sesi[element.id]"
                              ></v-select>

                              <h4 class="mt-2"><b>Buka akses lampiran sesi (materi/file)?</b></h4>
                              <v-select
                                :items="tampilan_web_items"
                                item-text="teks"
                                item-value="value"
                                label="Buka akses lampiran file"
                                prepend-icon="mdi-format-list-bulleted-square"
                                class='able_lampiran_sesi'
                                v-model="sesi_edit.able_lampiran_sesi[element.id]"
                              ></v-select>

                              <h4 class="mt-2"><b>Buka akses sesi sebagai materi ke halaman publik?</b></h4>
                              <v-select
                                :items="tampilan_web_items"
                                item-text="teks"
                                item-value="value"
                                label="Buka akses sesi sebagai materi ke halaman muka publik"
                                prepend-icon="mdi-format-list-bulleted-square"
                                class='able_lampiran_sesi'
                                v-model="sesi_edit.able_materi_publik[element.id]"
                              ></v-select>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                      </v-expansion-panels>
                    </v-row>
                  </div>
                </draggable>
              </div>

            </form>
            <v-divider class="my-4"></v-divider>
          </validation-observer>
          
        </v-container>
      </v-card>
    </v-dialog>
    <!-- DIALOG UNTUK VIEW DATA -->
    <v-dialog v-model="dialogPembinaanView" fullscreen hide-overlay style="z-index:2051" transition="dialog-bottom-transition">
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
          <v-toolbar-title>{{ pembinaan_view.judul_pembinaan }} </v-toolbar-title>
          <v-spacer></v-spacer>
          <!-- <v-toolbar-items>
            <v-btn
              dark
              text
              v-if="!edit_crud_dialog"
              @click="submitViewItem()"
            >
              Jadikan Data Ini Default
            </v-btn>
          </v-toolbar-items> -->
        </v-toolbar>
        <v-divider></v-divider>
        <v-container fluid>
            <v-container bg grid-list-md text-xs-center>
              <v-layout row wrap align-center>
                <v-flex>
                  <v-img  style="max-width:80vw" :src="url_base + '/api/sampul_pembinaan/'+pembinaan_view.sampul_pembinaan" alt=""/>
                </v-flex>
              </v-layout>
            </v-container>
            <p><b>Judul Pembinaan</b> : {{ pembinaan_view.judul_pembinaan }}</p>
            <p><b>Jenis Pembinaan</b> : {{ pembinaan_view.jenis_pembinaan }}</p>
            <p><b>Tanggal Pembinaan Awal</b> : {{ pembinaan_view.tanggal_awal_pembinaan }}</p>
            <p><b>Tanggal Pembinaan Akhir</b> : {{ pembinaan_view.tanggal_akhir_pembinaan }}</p>
            <p><b>Teks Pengantar</b> : {{ pembinaan_view.teks_pengantar_pembinaan }}</p>
            <p><b>Pembina Pembinaan</b> : {{ pembinaan_view.pembina_pembinaan }}</p>
            <p><b>Penyunting Pembinaan</b> : {{ pembinaan_view.editors_pembinaan }}</p>
            <p><b>Sertifikat Pembinaan</b> : {{ pembinaan_view.sertifikat_pembinaan }}</p>
            <p><b>Tags Pembinaan</b> :{{pembinaan_view.tags_pembinaan}}</p>
            <v-chip
                v-for="(filename, index) in tagviewnames_computed"
                :key="'tagviewname_'+index"
                color="primary darken--2"
                medium
                style="margin:5px;"
              >
              <span class="pr-2">
                {{ filename }}
              </span>
            </v-chip>
            <p><b>Teks Pengantar</b> :</p>
            <v-textarea
              v-model="pembinaan_view.teks_pengantar_pembinaan"
              :error-messages="errors"
              filled
              auto-grow
              rows="4"
              row-height="30"
              shaped
              :readonly="!edit_phase"
              style="background-color:lightgrey;"
              class="black--text"
              label="Teks Pengantar/Pembuka Pembinaan"
            ></v-textarea>

            <div v-for="item,idx in list_sesi_view" :key="'list_sesi_'+idx">
              <v-row class="my-4">
                <h3 class="mx-2 secondary--text lighten-1--text">SESI {{idx + 1}}</h3>

                <v-spacer></v-spacer>
              </v-row>
              <v-row class="ma-2">
                <v-expansion-panels focusable>
                  <v-expansion-panel>
                    <v-expansion-panel-header>Detail</v-expansion-panel-header>
                      <v-expansion-panel-content style="padding: 5px;min-height:45vh;">
                        <!-- Detail session -->
                        <!-- sambutan sesi -->
                        <h3 class="my-2 secondary--text lighten-1--text"><b>Profil Sesi</b></h3>
                        <h4><b>Nama Sesi</b></h4>
                        <v-text-field
                          v-model="item.judul_sesi"
                          :error-messages="errors"
                          label="Nama sesi"
                          prepend-icon="mdi-format-title"
                          :readonly="!edit_phase"
                        ></v-text-field>

                        <!-- kategori pembinaan -->
                        <h4 class="mt-2"><b>Kategori/Jenis Sesi</b></h4>
                        <v-select
                          v-model="item.jenis_sesi"
                          :items="jenis_pembinaan_items"
                          item-text="teks"
                          item-value="value"
                          :error-messages="errors"
                          label="Kategori/Jenis Pembinaan"
                          prepend-icon="mdi-format-list-bulleted-square"
                          required
                          :readonly="!edit_phase"
                        ></v-select>

                        <!-- waktu pembinaan awal -->
                        <h4 class="mt-2"><b>Mulai Sesi</b></h4>
                        <v-row>
                          <v-col class="col-6">
                            <v-date-picker
                              v-model="item.tanggal_awal_sesi"
                              color="green lighten-1"
                              header-color="primary"
                              :readonly="!edit_phase"
                            ></v-date-picker>
                          </v-col>
                          <v-col class="col-6">
                            <v-time-picker
                              v-model="item.waktu_awal_sesi"
                              format="24hr"
                              :readonly="!edit_phase"
                            ></v-time-picker>
                          </v-col>
                        </v-row>

                        <!-- waktu pembinaan awal -->
                        <h4 class="mt-2"><b>Akhir Sesi</b></h4>
                        <v-row>
                          <v-col class="col-6">
                            <v-date-picker
                              v-model="item.tanggal_akhir_sesi"
                              color="green lighten-1"
                              header-color="primary"
                              :readonly="!edit_phase"
                            ></v-date-picker>
                          </v-col>
                          <v-col class="col-6">
                            <v-time-picker
                              v-model="item.waktu_akhir_sesi"
                              format="24hr"
                              :readonly="!edit_phase"
                            ></v-time-picker>
                          </v-col>
                        </v-row>

                        <!-- pemateri sesi -->
                        <h4  class="mt-2"><b>Pemateri oleh</b></h4>
                        <v-text-field
                          :error-messages="errors"
                          label="Pemateri sesi"
                          prepend-icon="mdi-account-group"
                          class='pemateri_sesi'
                          v-model="item.pemateri_sesi"
                          :readonly="!edit_phase"
                        ></v-text-field>

                        <v-divider class="my-2"></v-divider>  
                        <v-row>
                          <h3 class="my-2 secondary--text lighten-1--text"><b>Tambahan Profile Sesi</b></h3>
                          <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                              <v-icon v-on="on" v-bind="attrs" class="mx-2" color="secondary lighten-1">mdi-help-circle</v-icon>
                            </template>
                            <span>Tidak wajib diisi</span>
                          </v-tooltip>
                          </v-row>
                        <!-- sambutan sesi -->
                        <h4 class="mt-2 grey--text darken-2--text"><b>Sambutan oleh</b></h4>
                        <h5>*jika ada</h5>
                        <v-text-field
                          :error-messages="errors"
                          label="Sambutan sesi"
                          prepend-icon="mdi-account-question"
                          class='sambutan_sesi'
                          v-model="item.sambutan_sesi"
                          :readonly="!edit_phase"
                        ></v-text-field>

                        <!-- moderator sesi -->
                        <h4 class="mt-2 grey--text darken-2--text"><b>Moderator oleh</b></h4>
                        <h5>*jika ada</h5>
                        <v-text-field
                          :error-messages="errors"
                          label="Moderator sesi"
                          prepend-icon="mdi-account-question"
                          class='moderator_sesi'
                          v-model="item.moderator_sesi"
                          :readonly="!edit_phase"
                        ></v-text-field>

                        <!-- penampil sesi -->
                        <h4 class="mt-2 grey--text darken-2--text"><b>Penampilan\Pertunjukkan oleh</b></h4>
                        <h5>*jika ada</h5>
                        <v-text-field
                          :error-messages="errors"
                          label="Penampilan sesi"
                          prepend-icon="mdi-account-question"
                          class='penampil_sesi'
                          v-model="item.penampil_sesi"
                          :readonly="!edit_phase"
                        ></v-text-field>

                        <v-divider class="my-2"></v-divider>

                        <h3 class="my-2 secondary--text lighten-1--text"><b>Konten Sesi</b></h3>
                        

                        <h4 class="mt-2"><b>Isi Konten/Penjelasan Sesi</b></h4>
                        <ckeditor class='moderator_sesi mb-4' :editor="editor_read" :value="item.konten_sesi" :disabled="editorDisabled" :config="editorConfig_read"></ckeditor>
        

                        <v-divider class="my-2"></v-divider>

                        <h3 class="my-2 secondary--text lighten-1--text"><b>Tautan/Link dan Materi terkait Sesi</b></h3>

                        <!-- tautan sesi -->
                        <h4 class="mt-2"><b>Tautan/Link Kelas Interaktif (Zoom, Google Meet)</b></h4>
                        <v-text-field
                          :error-messages="errors"
                          label="Tautan/Link Kelas Interaktif"
                          prepend-icon="mdi-google-classroom"
                          class='tautan_sesi'
                          :readonly="!edit_phase"
                          v-model="item.tautan_sesi"
                        ></v-text-field>

                        <!-- bahan materi sesi -->
                          <h4 class="mt-2"><b>Tautan/Link Materi Sesi</b></h4>
                        <v-text-field
                          :error-messages="errors"
                          label="Tautan materi"
                          prepend-icon="mdi-clipboard-text"
                          class='materi_sesi'
                          v-model="item.materi_sesi"
                          :readonly="!edit_phase"
                        ></v-text-field>

                        <!-- kuis sesi -->
                        <h4 class="mt-2"><b>Tautan/Link Kuis Tugas Sesi</b></h4>
                        <v-text-field
                          :error-messages="errors"
                          label="Tautan tugas"
                          prepend-icon="mdi-clipboard-text"
                          class='tugas_sesi'
                          v-model="item.tugas_sesi"
                          :readonly="!edit_phase"
                        ></v-text-field>

                        <v-divider class="my-4"></v-divider>
                        <h3 class="my-2 secondary--text lighten-1--text"><b>Hak Akses Sesi</b></h3>
                        
                        <h4 class="mt-2"><b>Buka akses tautan sesi?</b></h4>
                        <v-select
                          :items="tampilan_web_items"
                          item-text="teks"
                          item-value="value"
                          label="Buka akses tautan sesi"
                          prepend-icon="mdi-format-list-bulleted-square"
                          class='able_tautan_sesi'
                          v-model="item.able_tautan_sesi"
                          :readonly="!edit_phase"
                        ></v-select>

                        <h4 class="mt-2"><b>Buka akses tautan tugas?</b></h4>
                        <v-select
                          :items="tampilan_web_items"
                          item-text="teks"
                          item-value="value"
                          label="Buka akses tautan tugas"
                          prepend-icon="mdi-format-list-bulleted-square"
                          class='able_tugas_sesi'
                          v-model="item.able_tugas_sesi"
                          :readonly="!edit_phase"
                        ></v-select>

                        <h4 class="mt-2"><b>Buka akses lampiran sesi (materi/file)?</b></h4>
                        <v-select
                          :items="tampilan_web_items"
                          item-text="teks"
                          item-value="value"
                          label="Buka akses lampiran file"
                          prepend-icon="mdi-format-list-bulleted-square"
                          class='able_lampiran_sesi'
                          v-model="item.able_lampiran_sesi"
                          :readonly="!edit_phase"
                        ></v-select>

                        <h4 class="mt-2"><b>Buka akses sesi sebagai materi ke halaman publik?</b></h4>
                        <v-select
                          :items="tampilan_web_items"
                          item-text="teks"
                          item-value="value"
                          label="Buka akses sesi sebagai materi ke halaman muka publik"
                          prepend-icon="mdi-format-list-bulleted-square"
                          class='able_lampiran_sesi'
                          v-model="item.able_materi_publik"
                          :readonly="!edit_phase"
                        ></v-select>
                      </v-expansion-panel-content>
                  </v-expansion-panel>
                </v-expansion-panels>
              </v-row>
            </div>
        </v-container>
      </v-card>
    </v-dialog>
    <!-- DIALOG UNTUK CREATE DATA -->
    <v-dialog v-model="dialogPembinaanCreate" fullscreen hide-overlay style="z-index:2052" transition="dialog-bottom-transition">
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
          <v-toolbar-title>RANCANGAN PEMBINAAN </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn
              dark
              text
              @click="submitCreateItem()"
            >
              BUAT PEMBINAAN
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
              <h2 class="mt-4 secondary--text"><b>Profil Pembinaan</b></h2>
              <!-- penanggung jawab pembinaan -->
              <h3><b>Penanggung Jawab Pembinaan</b></h3>
              <v-text-field
                v-model="pembinaan.pembina_pembinaan"
                :error-messages="errors"
                label="Penanggung Jawab Pembinaan"
                prepend-icon="mdi-account"
              ></v-text-field>

              <!-- judul pembinaan -->
              <h3><b>Judul Pembinaan</b></h3>
              <v-text-field
                v-model="pembinaan.judul_pembinaan"
                :error-messages="errors"
                label="Judul Pembinaan"
                prepend-icon="mdi-alphabetical-variant"
              ></v-text-field>

              <!-- kategori pembinaan -->
              <h3 class="mt-2"><b>Kategori/Jenis Pembinaan</b></h3>
              <v-select
                v-model="pembinaan.jenis_pembinaan"
                :items="jenis_pembinaan_items"
                item-text="teks"
                item-value="value"
                :error-messages="errors"
                label="Kategori/Jenis Pembinaan"
                prepend-icon="mdi-format-list-bulleted-square"
                required
              ></v-select>

              <!-- kalimat pengantar pembinaan -->
              <h3><b>Kalimat Pengantar Pembinaan</b></h3>
              <validation-provider
                v-slot="{ errors }"
                name="Kalimat Pengantar Pembinaan"
                rules="required"
              >
                <v-textarea
                  v-model="pembinaan.teks_pengantar_pembinaan"
                  :error-messages="errors"
                  filled
                  auto-grow
                  rows="4"
                  row-height="30"
                  shaped
                  label="Kalimat Pengantar/Pembuka Pembinaan"
                ></v-textarea>
              </validation-provider>

              <!-- sampul pembinaan -->
              <h3><b>Gambar Depan Pembinaan</b></h3>
              <validation-provider
                v-slot="{ errors }"
                rules="size:20480"
                style="z-index:2010;"

              >
                <v-file-input
                  v-model="pembinaan.sampul_pembinaan"
                  chips
                  show-size
                  truncate-length="49"
                  :error-messages="errors"
                  label="Gambar Cover Depan Pembinaan"
                  prepend-icon="mdi-image"
                ></v-file-input>
              </validation-provider>
              <v-img :src="imagePreview"/>

              <v-divider class="my-4"></v-divider>

              <!-- tanggal awal pembinaan -->
              <h3><b>Tanggal Awal Pembinaan</b></h3>
              <v-menu
                ref="menu_tanggal_awal_pembinaan"
                v-model="menu_tanggal_awal_pembinaan"
                :close-on-content-click="false"
                :return-value.sync="pembinaan.tanggal_awal_pembinaan"
                transition="scale-transition"
                offset-y
                min-width="auto"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="pembinaan.tanggal_awal_pembinaan"
                    label="Tanggal Awal Pembinaan"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="pembinaan.tanggal_awal_pembinaan"
                  no-title
                  scrollable
                >
                  <v-spacer></v-spacer>
                  <v-btn
                    text
                    color="primary"
                    @click="pembinaan.tanggal_awal_pembinaan = null"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.menu_tanggal_awal_pembinaan.save(pembinaan.tanggal_awal_pembinaan)"
                  >
                    OK
                  </v-btn>
                </v-date-picker>
              </v-menu>

              <!-- tanggal akhir pembinaan -->
              <h3><b>Tanggal Akhir Pembinaan</b></h3>
              <v-menu
                ref="menu_tanggal_akhir_pembinaan"
                v-model="menu_tanggal_akhir_pembinaan"
                :close-on-content-click="false"
                :return-value.sync="pembinaan.tanggal_akhir_pembinaan"
                transition="scale-transition"
                offset-y
                min-width="auto"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="pembinaan.tanggal_akhir_pembinaan"
                    label="Tanggal Akhir Pembinaan"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="pembinaan.tanggal_akhir_pembinaan"
                  no-title
                  scrollable
                >
                  <v-spacer></v-spacer>
                  <v-btn
                    text
                    color="primary"
                    @click="pembinaan.tanggal_akhir_pembinaan = null"
                  >
                    Cancel
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.menu_tanggal_akhir_pembinaan.save(pembinaan.tanggal_akhir_pembinaan)"
                  >
                    OK
                  </v-btn>
                </v-date-picker>
              </v-menu>

              <v-divider class="my-4"></v-divider>

              <h2 class="mt-4 secondary--text"><b>Sertifikat, Editor Tambahan, Tags</b></h2>

              <!-- sampul pembinaan -->
              <h3><b>Tautan Sertifikat Pembinaan</b></h3>
              <v-text-field
                v-model="pembinaan.sertifikat_pembinaan"
                :error-messages="errors"
                label="Tautan/Link Sertifikat Pembinaan"
                prepend-icon="mdi-certificate"
              ></v-text-field>

              <h3 class="mt-2"><b>Editor Tambahan untuk Pembinaan</b></h3>
              <h5>Pisahkan dengan enter</h5>
              <v-layout wrap>
                <v-flex xs12>
                  <v-combobox multiple
                    color="primary"
                    v-model="editors_select" 
                    label="Email penyunting tambahan pembinaan" 
                    append-icon
                    chips
                    deletable-chips
                    class="tag-input"
                    :search-input.sync="editors_search" 
                    @keyup.tab="updateEditors"
                    @paste="updateEditors">
                  </v-combobox>
                </v-flex>
              </v-layout>

              <!-- tags pembinaan -->
              <h3 class="mt-2"><b>Tags/Hashtag Pembinaan </b></h3>
              <h5>Pisahkan dengan enter tanpa hashtag</h5>
              <v-layout wrap>
                <v-flex xs12>
                  <v-combobox multiple
                    color="primary"
                    v-model="tags_select" 
                    label="Tags Pembinaan" 
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
                <!-- {{ tags_search }} -->

              <v-divider class="my-4"></v-divider>

              <!-- BAGIAN AKSES2 PEMBINAAN -->
              
              <h2 class="mt-4 secondary--text"><b>Hak Akses Pembinaan</b></h2>
              
              <h3><b>Akses Tampilkan Pembinaan Ke Publik (Untuk Lihat)</b></h3>
              <v-select
                v-model="pembinaan.able_akses_halaman_pembinaan"
                :items="tampilan_web_items"
                item-text="teks"
                item-value="value"
                label="Tampilankan di Halaman Muka/Publik?"
                prepend-icon="mdi-format-list-bulleted-square"
              ></v-select>

              <h3><b>Akses Pendaftaran secara mandiri dibuka?</b></h3>
              <v-select
                v-model="pembinaan.able_akses_pendaftaran_pembinaan"
                :items="tampilan_web_items"
                item-text="teks"
                item-value="value"
                label="Buka akses pendaftaran mandiri?"
                prepend-icon="mdi-format-list-bulleted-square"
              ></v-select>

              <h3><b>Akses Sertifikat langsung dibuka?</b></h3>
              <v-select
                v-model="pembinaan.able_akses_sertifikat_pembinaan"
                :items="tampilan_web_items"
                item-text="teks"
                item-value="value"
                label="Tampikan file sertifikat kepada pengguna?"
                prepend-icon="mdi-format-list-bulleted-square"
              ></v-select>

              <h3><b>Hanya Super Admin yang bisa edit?</b></h3>
              <v-select
                v-model="pembinaan.only_super_admin"
                :items="tampilan_web_items"
                item-text="teks"
                item-value="value"
                label="Hanya super admin yang bisa edit>"
                prepend-icon="mdi-format-list-bulleted-square"
              ></v-select>

              <v-divider class="my-4"></v-divider>

              <!-- BAGIAN AKSES2 PEMBINAAN -->
              
              <h2 class="mt-4 secondary--text darken-2--text"><b>Bagian Sesi</b> 
                <v-btn
                  class="mx-2 my-2"
                  fab
                  dark
                  small
                  @click="createSession()"
                  color="primary">
                  <v-icon dark>
                    mdi-plus
                  </v-icon>
                </v-btn>
              </h2>

              <div class="col-12">
                <draggable tag="ul" :list="list_sesi" class="list-group" handle=".handle">
                  <div
                    class="my-4 list-group-item"
                    v-for="(element, idx) in list_sesi"
                    :key="'sesi_'+element.name+'_'+idx"
                  >
                    <v-row class="my-4">

                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-icon v-on="on" v-bind="attrs" color="secondary lighten-1" class="mr-2 handle">mdi-gesture-tap-hold</v-icon>
                        </template>
                        <span>Klik dan tahan untuk memindahkan urutan sesi</span>
                      </v-tooltip>

                      
                      <h3 class="mx-2 secondary--text lighten-1--text">SESI {{idx + 1}}</h3>

                      <v-spacer></v-spacer>

                      <v-icon class="red--text ml-2 close" @click="removeAt(idx)">mdi-close</v-icon>
                    </v-row>
                    <v-row class="ma-2">
                      <v-expansion-panels focusable>
                        <v-expansion-panel>
                          <v-expansion-panel-header>Detail</v-expansion-panel-header>
                            <v-expansion-panel-content style="padding: 5px;min-height:45vh;">
                              <!-- Detail session -->
                              <!-- sambutan sesi -->
                              <h3 class="my-2 secondary--text lighten-1--text"><b>Profil Sesi</b></h3>
                              <h4><b>Nama Sesi</b></h4>
                              <v-text-field
                                v-model="sesi.judul_sesi[element.id]"
                                :error-messages="errors"
                                label="Nama sesi"
                                prepend-icon="mdi-format-title"
                              ></v-text-field>

                              <!-- kategori pembinaan -->
                              <h4 class="mt-2"><b>Kategori/Jenis Sesi</b></h4>
                              <v-select
                                v-model="sesi.jenis_sesi[element.id]"
                                :items="jenis_pembinaan_items"
                                item-text="teks"
                                item-value="value"
                                :error-messages="errors"
                                label="Kategori/Jenis Pembinaan"
                                prepend-icon="mdi-format-list-bulleted-square"
                                required
                              ></v-select>

                              <!-- waktu pembinaan awal -->
                              <h4 class="mt-2"><b>Mulai Sesi</b></h4>
                              <v-row>
                                <v-col class="col-6">
                                  <v-date-picker
                                    v-model="sesi.tanggal_awal_sesi[element.id]"
                                    color="green lighten-1"
                                    header-color="primary"
                                  ></v-date-picker>
                                </v-col>
                                <v-col class="col-6">
                                  <v-time-picker
                                    v-model="sesi.waktu_awal_sesi[element.id]"
                                    format="24hr"
                                  ></v-time-picker>
                                </v-col>
                              </v-row>

                              <!-- waktu pembinaan awal -->
                              <h4 class="mt-2"><b>Akhir Sesi</b></h4>
                              <v-row>
                                <v-col class="col-6">
                                  <v-date-picker
                                    v-model="sesi.tanggal_akhir_sesi[element.id]"
                                    color="green lighten-1"
                                    header-color="primary"
                                  ></v-date-picker>
                                </v-col>
                                <v-col class="col-6">
                                  <v-time-picker
                                    v-model="sesi.waktu_akhir_sesi[element.id]"
                                    format="24hr"
                                  ></v-time-picker>
                                </v-col>
                              </v-row>

                              <!-- pemateri sesi -->
                              <h4  class="mt-2"><b>Pemateri oleh</b></h4>
                              <v-text-field
                                :error-messages="errors"
                                label="Pemateri sesi"
                                prepend-icon="mdi-account-group"
                                class='pemateri_sesi'
                                v-model="sesi.pemateri_sesi[element.id]"
                              ></v-text-field>

                              <v-divider class="my-2"></v-divider>  
                              <v-row>
                                <h3 class="my-2 secondary--text lighten-1--text"><b>Tambahan Profile Sesi</b></h3>
                                <v-tooltip bottom>
                                  <template v-slot:activator="{ on, attrs }">
                                    <v-icon v-on="on" v-bind="attrs" class="mx-2" color="secondary lighten-1">mdi-help-circle</v-icon>
                                  </template>
                                  <span>Tidak wajib diisi</span>
                                </v-tooltip>
                                </v-row>
                              <!-- sambutan sesi -->
                              <h4 class="mt-2 grey--text darken-2--text"><b>Sambutan oleh</b></h4>
                              <h5>*jika ada</h5>
                              <v-text-field
                                :error-messages="errors"
                                label="Sambutan sesi"
                                prepend-icon="mdi-account-question"
                                class='sambutan_sesi'
                                v-model="sesi.sambutan_sesi[element.id]"
                              ></v-text-field>

                              <!-- moderator sesi -->
                              <h4 class="mt-2 grey--text darken-2--text"><b>Moderator oleh</b></h4>
                              <h5>*jika ada</h5>
                              <v-text-field
                                :error-messages="errors"
                                label="Moderator sesi"
                                prepend-icon="mdi-account-question"
                                class='moderator_sesi'
                                v-model="sesi.moderator_sesi[element.id]"
                              ></v-text-field>

                              <!-- penampil sesi -->
                              <h4 class="mt-2 grey--text darken-2--text"><b>Penampilan\Pertunjukkan oleh</b></h4>
                              <h5>*jika ada</h5>
                              <v-text-field
                                :error-messages="errors"
                                label="Penampilan sesi"
                                prepend-icon="mdi-account-question"
                                class='penampil_sesi'
                                v-model="sesi.penampil_sesi[element.id]"
                              ></v-text-field>

                              <v-divider class="my-2"></v-divider>

                              <h3 class="my-2 secondary--text lighten-1--text"><b>Konten Sesi</b></h3>
                              

                              <h4 class="mt-2"><b>Isi Konten/Penjelasan Sesi</b></h4>
                              <ckeditor 
                                :editor="editor" 
                                class='moderator_sesi mb-4'
                                v-model="sesi.konten_sesi[element.id]" 
                                :config="editorConfig" 
                                ></ckeditor>

                              <v-divider class="my-2"></v-divider>

                              <h3 class="my-2 secondary--text lighten-1--text"><b>Tautan/Link dan Materi terkait Sesi</b></h3>

                              <!-- tautan sesi -->
                              <h4 class="mt-2"><b>Tautan/Link Kelas Interaktif (Zoom, Google Meet)</b></h4>
                              <v-text-field
                                :error-messages="errors"
                                label="Tautan/Link Kelas Interaktif"
                                prepend-icon="mdi-google-classroom"
                                class='tautan_sesi'
                                v-model="sesi.tautan_sesi[element.id]"
                              ></v-text-field>

                              <!-- bahan materi sesi -->
                               <h4 class="mt-2"><b>Tautan/Link Materi Sesi</b></h4>
                              <v-text-field
                                :error-messages="errors"
                                label="Tautan materi"
                                prepend-icon="mdi-clipboard-text"
                                class='materi_sesi'
                                v-model="sesi.materi_sesi[element.id]"
                              ></v-text-field>

                              <!-- kuis sesi -->
                              <h4 class="mt-2"><b>Tautan/Link Kuis Tugas Sesi</b></h4>
                              <v-text-field
                                :error-messages="errors"
                                label="Tautan tugas"
                                prepend-icon="mdi-clipboard-text"
                                class='tugas_sesi'
                                v-model="sesi.tugas_sesi[element.id]"
                              ></v-text-field>

                              <!-- <h4 class="mt-2"><b>Lampiran/Dokumen Materi Sesi</b></h4>
                              <v-file-input
                                chips
                                multiple
                                show-size
                                truncate-length="49"
                                :error-messages="errors"
                                label="File/Dokumen/Lampiran untuk Pendukung"
                                prepend-icon="mdi-file"
                                class='lampiran_sesi'
                                v-model="sesi.lampiran_sesi[element.id]"
                              ></v-file-input> -->

                              <v-divider class="my-4"></v-divider>
                              <h3 class="my-2 secondary--text lighten-1--text"><b>Hak Akses Sesi</b></h3>
                              
                              <h4 class="mt-2"><b>Buka akses tautan sesi?</b></h4>
                              <v-select
                                :items="tampilan_web_items"
                                item-text="teks"
                                item-value="value"
                                label="Buka akses tautan sesi"
                                prepend-icon="mdi-format-list-bulleted-square"
                                class='able_tautan_sesi'
                                v-model="sesi.able_tautan_sesi[element.id]"
                              ></v-select>

                              <h4 class="mt-2"><b>Buka akses tautan tugas?</b></h4>
                              <v-select
                                :items="tampilan_web_items"
                                item-text="teks"
                                item-value="value"
                                label="Buka akses tautan tugas"
                                prepend-icon="mdi-format-list-bulleted-square"
                                class='able_tugas_sesi'
                                v-model="sesi.able_tugas_sesi[element.id]"
                              ></v-select>

                              <h4 class="mt-2"><b>Buka akses lampiran sesi (materi/file)?</b></h4>
                              <v-select
                                :items="tampilan_web_items"
                                item-text="teks"
                                item-value="value"
                                label="Buka akses lampiran file"
                                prepend-icon="mdi-format-list-bulleted-square"
                                class='able_lampiran_sesi'
                                v-model="sesi.able_lampiran_sesi[element.id]"
                              ></v-select>

                              <h4 class="mt-2"><b>Buka akses sesi sebagai materi ke halaman publik?</b></h4>
                              <v-select
                                :items="tampilan_web_items"
                                item-text="teks"
                                item-value="value"
                                label="Buka akses sesi sebagai materi ke halaman muka publik"
                                prepend-icon="mdi-format-list-bulleted-square"
                                class='able_lampiran_sesi'
                                v-model="sesi.able_materi_publik[element.id]"
                              ></v-select>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                      </v-expansion-panels>
                    </v-row>
                  </div>
                </draggable>
              </div>

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
          Hapus pembinaan/berita <b>[{{ pembinaan.judul_pembinaan }}]</b>?
        </v-card-title>
        <v-card-text>Dengan menghapus pembinaan ini, data akan terhapus didalam daftar pembinaan!</v-card-text>
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
            @click="deleteItem(pembinaan)"
          >
            Ya, hapus pembinaan
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
        <h2 class="mx-4">DAFTAR PEMBINAAN 
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
          LIHAT PEMBINAAN
        </v-btn>
        <v-btn text
          small
          class="mr-2"
          color="green"
          @click="editItem(item)"
        >
          EDIT PEMBINAAN
        </v-btn>
        <v-btn text
          small
          class="mr-2"
          color="red"
          @click="konfirmasiDeleteItem(item)"
        >
          HAPUS PEMBINAAN
        </v-btn>
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
  import "leaflet/dist/leaflet.css";
  import L from "leaflet";

  import 'leaflet-draw/dist/leaflet.draw.css'
  import 'leaflet-draw'

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
        list_sesi: [
          { name: "John", text: "", id: 0, waktu_awal_sesi:null, waktu_akhir_sesi: null, konten_sesi: null,},
        ],
        list_sesi_edit: [],
        dragging: true,
        timeProps: {
          useSeconds: true,
          ampmInTitle: true
        },
        menu_tanggal_pembinaan: false,
        menu_tanggal_awal_pembinaan: false,
        menu_tanggal_akhir_pembinaan: false,
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
        pembinaan:{
          id_entri:'',
          judul_pembinaan:'',
          jenis_pembinaan:'',
          teks_pembuka_pembinaan:'',
          teks_isi_pembinaan: '',
          tanggal_pembinaan:'',
          geolocation_pembinaan:'',
          gambar_pembuka_pembinaan:null,
          lampiran_pembinaan:null,
          tags_pembinaan:"",
        },
        sesi:{
          id_entri: [],
          id_pembinaan: [],
          id_sesi: [],
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
          materi_sesi: [],
          able_tautan_sesi: [],
          able_tugas_sesi: [],
          able_lampiran_sesi: [],
          able_materi_publik: [],
          tanggal_awal_sesi: [],
          waktu_awal_sesi: [],
          tanggal_akhir_sesi: [],
          waktu_akhir_sesi: [],
        },
        sesi_edit: {
          id_entri: [],
          id_pembinaan: [],
          id_sesi: [],
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
          materi_sesi: [],
          able_tautan_sesi: [],
          able_tugas_sesi: [],
          able_lampiran_sesi: [],
          able_materi_publik: [],
          tanggal_awal_sesi: [],
          waktu_awal_sesi: [],
          tanggal_akhir_sesi: [],
          waktu_akhir_sesi: [],
        },
        list_sesi_view: [],
        pembinaan_view: [],
        menu_tanggal_awal_sesi: [],
        menu_waktu_awal_sesi: [],
        gambar_pembuka_pembinaan:'',
        jenis_pembinaan_items:[
          {teks: 'Pembinaan Statistik', value: 'pembinaan-statistik'},
          {teks: 'Indikator Statistik', value: 'indikator-statistik'},
          {teks: 'Materi Umum', value: 'materi-umum'},
        ],
        tampilan_web_items:[
          {teks: 'Ya', value: 'true'},
          {teks: 'Tidak', value: 'false'},
        ],
        dialog: false,
        dialogPembinaanEdit: false,
        dialogPembinaanView:false,
        dialogPembinaanCreate: false,
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
        photos: [],
        desserts:[],
        dessertHeaders: [
          {
            text: 'Daftar Pembinaan',
            align: 'start',
            sortable: true,
            value: 'judul_pembinaan',
          },
          { text: 'Jenis Pembinaan', value: 'jenis_pembinaan' },
          { text: 'Pembina Pembinaan', value: 'pembina_pembinaan' },
          { text: 'Tanggal Pembuatan', value: 'created_at' },
          { text: 'Actions', value: 'actions', sortable: false },
        ],
        expanded: [],
        singleExpand: false,
        colors: ['green', 'purple', 'indigo', 'cyan', 'teal', 'primary'],
        search_aset:'',
        nonce_pembelian: 1,
        search_pembelian: null,
        nonce_pembukuan: 1,
        search_pembukuan: null,
        nonce_kepemilikan: 1,
        search_kepemilikan: null,
        nonce_foto: 1,
        search_foto: null,
        aset_history: '',
        judul_crud_dialog: 'Judul',
        warna_crud_dialog: 'primary',
        view_crud_dialog: false,
        edit_crud_dialog: true,
        filenames:[],
        tags_select: [],
        tags_items: [],
        tags_search: "",
        editors_select: [],
        editors_items: [],
        editors_search: "",
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
    },
    computed: {
      filenames_computed(){
        return this.filenames;
      },
      tagnames_computed(){
        var tags =  (this.pembinaan.tags_pembinaan == null ) ? [] : (this.pembinaan.tags_pembinaan).split(',');
        return tags;
      },
      tagviewnames_computed(){
        var tags =  (this.pembinaan_view.tags_pembinaan == null ) ? [] : (this.pembinaan_view.tags_pembinaan).split(',');
        return tags;
      },
      editornames_computed(){
        var editors =  (this.pembinaan.editors_pembinaan == null ) ? [] : (this.pembinaan.editors_pembinaan).split(',');
        return editors;
      },
      editorviewnames_computed(){
        var editors =  (this.pembinaan_view.editors_pembinaan == null ) ? [] : (this.pembinaan_view.editors_pembinaan).split(',');
        return editors;
      },
      imagePreview(){
        if (!this.pembinaan.sampul_pembinaan) return null;
          return URL.createObjectURL(this.pembinaan.sampul_pembinaan);
      },
    },
    methods: {
      removeAt(idx) {
        this.list_sesi.splice(idx, 1);
        // this.sesi.judul_sesi.splice(idx, 1);
      },
      createSession(){
        this.sesi.judul_sesi[id] = null
        this.list_sesi.push({ name: "Juan " + id, id, text: "" });
        id++;
      },
      saveTanggalAwalSesi(ref, idx){
        console.log(ref)
        this.$refs["menu_tanggal_awal_sesi"].save(this.sesi.tanggal_awal_sesi[idx]);
        console.log(this.$refs)
      },
      saveWaktuAwalSesi(ref){
        // this.$refs[ref].save(this.sesi.waktu_awal_sesi[idx]);
      },
      //Reading data from API method. 
      readDataFromAPI() {
        this.loading = true;
        axios.get('/api/show-pembinaans')
          .then((response) => {
            //Then injecting the result to datatable parameters.
            console.log('ambil data dari database')
            console.log(response.data)
            this.loading = false;
            this.desserts = response.data;
          }).catch(errors => {
            console.log('data eror')
            this.loading = false;
            this.desserts = [];
          }).finally(() => {
            this.loading = false;
          });
      },
      save_tanggal_pembinaan () {
        this.$refs.menu_tanggal_pembinaan.save(pembinaan.tanggal_pembinaan)
      },
      save_tanggal_awal_pembinaan () {
        this.$refs.menu_tanggal_awal_pembinaan.save(pembinaan.tanggal_awal_pembinaan)
        
      },
      save_tanggal_akhir_pembinaan () {
        this.$refs.menu_tanggal_akhir_pembinaan.save(pembinaan.tanggal_akhir_pembinaan)
      },
      updateTags() {
        this.$nextTick(() => {
          this.tags_select.push(...this.tags_search.split(","));
          this.pembinaan.tags_pembinaan = this.tags_select;
          this.$nextTick(() => {
            this.tags_search = "";
          });
        });
      },
      updateEditors() {
        this.$nextTick(() => {
          this.editors_select.push(...this.editors_search.split(","));
          this.pembinaan.editors_pembinaan = this.editors_select;
          this.$nextTick(() => {
            this.editors_search = "";
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
        var label = this.aset.label_aset
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
        this.pembinaan = item
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
        var teks = "";
        formData.append('id_pembinaan', this.pembinaan.id_pembinaan);
        formData.append('id_entri', this.pembinaan.id_entri);
        console.log(formData);
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        axios.post("/api/delete-pembinaan", formData, config).then(response => {
          // console.log(response.data)
          if(response.data.status == false){
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message)
            // console.log(r)
            this.warnaSnackbar= "red"
            this.snackbar = true
          }else{
            this.dialogKonfirmasiHapus = false
            this.teksSnackbar= "Berhasil menghapus pembinaan "
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
        var refthis = this
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        // this.pembinaan.tampilan_web = (this.pembinaan.tampilan_web === 'true')
        axios.get("/api/show-pembinaans/"+item.id_pembinaan).then(response => {
          // console.log(response.data)
          if(response.data.status == false){
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }else{
            this.clearCreateItem()
            console.log('data terakses')
            console.log(response.data)
            this.pembinaan_view = response.data.pembinaan
            this.list_sesi_view = response.data.sesis
            
          }
        }).catch(errors => {
          console.log(errors.response.data)
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data.errors),
          this.warnaSnackbar= "red",
          this.snackbar = true
        }).finally(() => {
          this.isLoading = false
          this.overlay_entri = false
          this.overlay_list = false
        });
        this.dialogPembinaanView = true
        this.overlay_list = false
      }, 
      editItem(item){
        this.dialogPembinaanEdit = true
        this.edit_phase = true
        this.pembinaan = {};
        this.edit_phase= true;
        this.list_sesi_edit =[];
        this.sesi_edit.id_entri = [];
        this.sesi_edit.id_sesi = [];
        this.sesi_edit.id_pembinaan = [];
        this.sesi_edit.judul_sesi = [];
        this.sesi_edit.sambutan_sesi = [];
        this.sesi_edit.moderator_sesi = [];
        this.sesi_edit.pemateri_sesi = [];
        this.sesi_edit.penampil_sesi = [];
        this.sesi_edit.tugas_sesi = [];
        this.sesi_edit.tautan_sesi = [];
        this.sesi_edit.materi_sesi = [];
        this.sesi_edit.konten_sesi = [];
        this.sesi_edit.tanggal_awal_sesi = [];
        this.sesi_edit.tanggal_akhir_sesi = [];
        this.sesi_edit.waktu_awal_sesi = [];
        this.sesi_edit.waktu_akhir_sesi = [];
        this.sesi_edit.able_tautan_sesi = [];
        this.sesi_edit.able_tugas_sesi = [];
        this.sesi_edit.able_lampiran_sesi = [];
        this.sesi_edit.able_materi_publik = [];
        var refs = this

        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        // this.pembinaan.tampilan_web = (this.pembinaan.tampilan_web === 'true')
        axios.get("/api/show-pembinaans/"+item.id_pembinaan).then(response => {
          // console.log(response.data)
          if(response.data.status == false){
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }else{
            this.clearCreateItem()
            console.log('data terakses')
            console.log(response.data)
            refs.pembinaan = response.data.pembinaan
            refs.pembinaan.image_preview = response.data.pembinaan.sampul_pembinaan
            refs.pembinaan.sampul_pembinaan = null
            refs.editors_select = response.data.pembinaan.editors_pembinaan != null ? (response.data.pembinaan.editors_pembinaan).split(',') : null;
            refs.tags_select = response.data.pembinaan.tags_pembinaan != null ? (response.data.pembinaan.tags_pembinaan).split(',') : null;
            var list_sesi_temp = response.data.sesis
            var id = 0;
            list_sesi_temp.forEach(element => {
              this.list_sesi_edit.push(
                { name: element.judul_sesi, id: id},
              );
              this.sesi_edit.id_entri[id] = (element.id_entri);
              this.sesi_edit.id_sesi[id] = (element.id_sesi);
              this.sesi_edit.id_pembinaan[id] = (element.id_pembinaan);
              this.sesi_edit.judul_sesi[id] = (element.judul_sesi);
              this.sesi_edit.jenis_sesi[id] = (element.jenis_sesi);
              this.sesi_edit.sambutan_sesi[id] = (element.sambutan_sesi);
              this.sesi_edit.moderator_sesi[id] = (element.moderator_sesi);
              this.sesi_edit.pemateri_sesi[id] = (element.pemateri_sesi);
              this.sesi_edit.penampil_sesi[id] = (element.penampil_sesi);
              this.sesi_edit.tugas_sesi[id] = (element.tugas_sesi);
              this.sesi_edit.tautan_sesi[id] = (element.tautan_sesi);
              this.sesi_edit.materi_sesi[id] = (element.materi_sesi);
              this.sesi_edit.konten_sesi[id] = (element.konten_sesi);
              this.sesi_edit.tanggal_awal_sesi[id] = (element.tanggal_awal_sesi);
              this.sesi_edit.tanggal_akhir_sesi[id] = (element.tanggal_akhir_sesi);
              this.sesi_edit.waktu_awal_sesi[id] = (element.waktu_awal_sesi);
              this.sesi_edit.waktu_akhir_sesi[id] = (element.waktu_akhir_sesi);
              this.sesi_edit.able_tautan_sesi[id] = (element.able_tautan_sesi);
              this.sesi_edit.able_tugas_sesi[id] = (element.able_tugas_sesi);
              this.sesi_edit.able_lampiran_sesi[id] = (element.able_lampiran_sesi);
              this.sesi_edit.able_materi_publik[id] = (element.able_materi_publik);
              id++;
            });
            
          }
        }).catch(errors => {
          console.log(errors)
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors),
          this.warnaSnackbar= "red",
          this.snackbar = true
        }).finally(() => {
          this.isLoading = false
          this.overlay_entri = false
          this.overlay_list = false
        });
        this.dialogPembinaanEdit = true
        this.overlay_list = false

      },
      createItem(){
        this.pembinaan.id_entri=''
        this.pembinaan.judul_pembinaan=''
        this.pembinaan.jenis_pembinaan=null
        this.pembinaan.teks_pembuka_pembinaan=''
        this.pembinaan.teks_isi_pembinaan= ''
        this.pembinaan.tanggal_pembinaan=''
        this.pembinaan.geolocation_pembinaan=''
        this.pembinaan.gambar_pembuka_pembinaan=null
        this.pembinaan.lampiran_pembinaan=null
        this.pembinaan.tags_pembinaan=null
        
        this.dialogPembinaanCreate = true
      },
      submitEditItem(){
        this.$refs.observer.validate()
        this.overlay_list = true
        this.isLoading = "secondary"

        var formData = new FormData();
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }

        // menambahkan data pembinaan kedalam form untuk di upload
        formData.append('id_pembinaan', this.pembinaan.id_pembinaan);
        formData.append('judul_pembinaan', this.pembinaan.judul_pembinaan);
        formData.append('jenis_pembinaan', this.pembinaan.jenis_pembinaan);
        formData.append('teks_pengantar_pembinaan', this.pembinaan.teks_pengantar_pembinaan);
        formData.append('pembina_pembinaan', this.pembinaan.pembina_pembinaan);
        formData.append('konten_pembinaan', this.pembinaan.konten_pembinaan);
        formData.append('sertifikat_pembinaan', this.pembinaan.sertifikat_pembinaan);
        formData.append('tanggal_awal_pembinaan', this.pembinaan.tanggal_awal_pembinaan);
        formData.append('tanggal_akhir_pembinaan', this.pembinaan.tanggal_akhir_pembinaan);
        formData.append('editors_pembinaan', JSON.stringify(this.editors_select));
        formData.append('tags_pembinaan', JSON.stringify(this.tags_select));
        formData.append('able_akses_halaman_pembinaan', this.pembinaan.able_akses_halaman_pembinaan);
        formData.append('able_akses_sertifikat_pembinaan', this.pembinaan.able_akses_sertifikat_pembinaan);
        formData.append('able_akses_pendaftaran_pembinaan', this.pembinaan.able_akses_pendaftaran_pembinaan);
        formData.append('only_super_admin', this.pembinaan.only_super_admin);
        if(this.pembinaan.sampul_pembinaan != null){
            formData.append("sampul_pembinaan", this.pembinaan.sampul_pembinaan, this.pembinaan.sampul_pembinaan.name);
        }
        formData.append('sampul_pembinaan', this.pembinaan.sampul_pembinaan);
        formData.append('sampul_pembinaan_old', this.pembinaan.image_preview);
        
        // menambahkan data sesi
        var id_sesi = 0;
        var file = null;
        var lampiran_sesi = [];
        var filenames = [];
        this.list_sesi_edit.forEach(element => {
          id_sesi = element.id;
          formData.append(`list_sesi[${id_sesi}]`, id_sesi);
          formData.append(`id_entri[${id_sesi}]`, this.sesi_edit.id_entri[id_sesi]);
          formData.append(`id_pembinaan[${id_sesi}]`, this.sesi_edit.id_pembinaan[id_sesi]);
          formData.append(`id_sesi[${id_sesi}]`, this.sesi_edit.id_sesi[id_sesi]);
          formData.append(`judul_sesi[${id_sesi}]`, this.sesi_edit.judul_sesi[id_sesi]);
          formData.append(`jenis_sesi[${id_sesi}]`, this.sesi_edit.jenis_sesi[id_sesi]);
          formData.append(`sambutan_sesi[${id_sesi}]`, this.sesi_edit.sambutan_sesi[id_sesi]);
          formData.append(`moderator_sesi[${id_sesi}]`, this.sesi_edit.moderator_sesi[id_sesi]);
          formData.append(`pemateri_sesi[${id_sesi}]`, this.sesi_edit.pemateri_sesi[id_sesi]);
          formData.append(`penampil_sesi[${id_sesi}]`, this.sesi_edit.penampil_sesi[id_sesi]);
          formData.append(`tautan_sesi[${id_sesi}]`, this.sesi_edit.tautan_sesi[id_sesi]);
          formData.append(`tugas_sesi[${id_sesi}]`, this.sesi_edit.tugas_sesi[id_sesi]);
          formData.append(`materi_sesi[${id_sesi}]`, this.sesi_edit.materi_sesi[id_sesi]);
          formData.append(`konten_sesi[${id_sesi}]`, this.sesi_edit.konten_sesi[id_sesi]);
          formData.append(`tanggal_awal_sesi[${id_sesi}]`, this.sesi_edit.tanggal_awal_sesi[id_sesi]);
          formData.append(`tanggal_akhir_sesi[${id_sesi}]`, this.sesi_edit.tanggal_akhir_sesi[id_sesi]);
          formData.append(`waktu_awal_sesi[${id_sesi}]`, this.sesi_edit.waktu_awal_sesi[id_sesi]);
          formData.append(`waktu_akhir_sesi[${id_sesi}]`, this.sesi_edit.waktu_akhir_sesi[id_sesi]);
          formData.append(`able_tautan_sesi[${id_sesi}]`, this.sesi_edit.able_tautan_sesi[id_sesi]);
          formData.append(`able_tugas_sesi[${id_sesi}]`, this.sesi_edit.able_tugas_sesi[id_sesi]);
          formData.append(`able_lampiran_sesi[${id_sesi}]`, this.sesi_edit.able_lampiran_sesi[id_sesi]);
          formData.append(`able_materi_publik[${id_sesi}]`, this.sesi_edit.able_materi_publik[id_sesi]);
        });

        formData.append(`lampiran_sesi`, JSON.stringify(lampiran_sesi));

        // console.log(formData)

        axios.post("/api/update-pembinaan", formData, config).then(response => {
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
          this.dialogPembinaanEdit = false
        });
        // end of upload
      this.pembinaan = {};
      this.edit_phase= false;
      this.list_sesi_edit =[];
      this.list_sesi =[];
       this.overlay_list = true
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
          this.dialogPembinaanView = false 
          this.dialogHistori = false
          this.isLoading = false
          this.overlay_list = false
          this.snackbar = true
        })
      },
      submitCreateItem(){
        // start of upload
        this.overlay_list = true
        this.isLoading = "orange"
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }
        var formData = new FormData();

        // menambahkan data pembinaan kedalam form untuk di upload
        formData.append('judul_pembinaan', this.pembinaan.judul_pembinaan);
        formData.append('jenis_pembinaan', this.pembinaan.jenis_pembinaan);
        formData.append('teks_pengantar_pembinaan', this.pembinaan.teks_pengantar_pembinaan);
        formData.append('pembina_pembinaan', this.pembinaan.pembina_pembinaan);
        formData.append('konten_pembinaan', this.pembinaan.konten_pembinaan);
        formData.append('sampul_pembinaan', this.pembinaan.sampul_pembinaan);
        formData.append('sertifikat_pembinaan', this.pembinaan.sertifikat_pembinaan);
        formData.append('tanggal_awal_pembinaan', this.pembinaan.tanggal_awal_pembinaan);
        formData.append('tanggal_akhir_pembinaan', this.pembinaan.tanggal_akhir_pembinaan);
        formData.append('editors_pembinaan', JSON.stringify(this.editors_select));
        formData.append('tags_pembinaan', JSON.stringify(this.tags_select));
        formData.append('able_akses_halaman_pembinaan', this.pembinaan.able_akses_halaman_pembinaan);
        formData.append('able_akses_sertifikat_pembinaan', this.pembinaan.able_akses_sertifikat_pembinaan);
        formData.append('able_akses_pendaftaran_pembinaan', this.pembinaan.able_akses_pendaftaran_pembinaan);
        formData.append('only_super_admin', this.pembinaan.only_super_admin);
        if(this.pembinaan.sampul_pembinaan != null){
            formData.append("sampul_pembinaan", this.pembinaan.sampul_pembinaan, this.pembinaan.sampul_pembinaan.name);
        }
        
        // menambahkan data sesi
        var id_sesi = 0;
        var file = null;
        var lampiran_sesi = [];
        var filenames = [];
        this.list_sesi.forEach(element => {
          id_sesi = element.id;
          formData.append(`list_sesi[${id_sesi}]`, id_sesi);
          formData.append(`judul_sesi[${id_sesi}]`, this.sesi.judul_sesi[id_sesi]);
          formData.append(`jenis_sesi[${id_sesi}]`, this.sesi.jenis_sesi[id_sesi]);
          formData.append(`sambutan_sesi[${id_sesi}]`, this.sesi.sambutan_sesi[id_sesi]);
          formData.append(`moderator_sesi[${id_sesi}]`, this.sesi.moderator_sesi[id_sesi]);
          formData.append(`pemateri_sesi[${id_sesi}]`, this.sesi.pemateri_sesi[id_sesi]);
          formData.append(`penampil_sesi[${id_sesi}]`, this.sesi.penampil_sesi[id_sesi]);
          formData.append(`tautan_sesi[${id_sesi}]`, this.sesi.tautan_sesi[id_sesi]);
          formData.append(`tugas_sesi[${id_sesi}]`, this.sesi.tugas_sesi[id_sesi]);
          formData.append(`materi_sesi[${id_sesi}]`, this.sesi.materi_sesi[id_sesi]);
          formData.append(`konten_sesi[${id_sesi}]`, this.sesi.konten_sesi[id_sesi]);
          formData.append(`tanggal_awal_sesi[${id_sesi}]`, this.sesi.tanggal_awal_sesi[id_sesi]);
          formData.append(`tanggal_akhir_sesi[${id_sesi}]`, this.sesi.tanggal_akhir_sesi[id_sesi]);
          formData.append(`waktu_awal_sesi[${id_sesi}]`, this.sesi.waktu_awal_sesi[id_sesi]);
          formData.append(`waktu_akhir_sesi[${id_sesi}]`, this.sesi.waktu_akhir_sesi[id_sesi]);
          formData.append(`able_tautan_sesi[${id_sesi}]`, this.sesi.able_tautan_sesi[id_sesi]);
          formData.append(`able_tugas_sesi[${id_sesi}]`, this.sesi.able_tugas_sesi[id_sesi]);
          formData.append(`able_lampiran_sesi[${id_sesi}]`, this.sesi.able_lampiran_sesi[id_sesi]);
          formData.append(`able_materi_publik[${id_sesi}]`, this.sesi.able_materi_publik[id_sesi]);

          // if(this.sesi.lampiran_sesi[id_sesi] != null){
          //   filenames = [];
          //   for (let file of this.sesi.lampiran_sesi[id_sesi]) {
          //     formData.append(`lampiran_sesi[${id_sesi}][]`, file, file.name);
          //     filenames.push(file.name);
          //     formData.append(`lampiran_sesi_filename[${id_sesi}][]`, file.name);
          //   }
          //   lampiran_sesi.push(filenames);
          // }
          // id_sesi++;
        });

        formData.append(`lampiran_sesi`, JSON.stringify(lampiran_sesi));

        console.log(formData)

        axios.post("/api/insert-pembinaan", formData, config).then(response => {
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
        this.pembinaan.id_entri=''
        this.pembinaan.id_pembinaan=''
        this.pembinaan.judul_pembinaan=''
        this.pembinaan.teks_pembuka_pembinaan=''
        this.pembinaan = {}
        this.pembinaan.sampul_pembinaan=null
        this.pembinaan.lampiran_pembinaan=null
        this.pembinaan.tags_pembinaan=null
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
        this.pembinaan = {};
        this.list_sesi = [];
      },
      closeEditItem() {
        this.dialogPembinaanEdit = false
        // this.clear()
      },
      closeViewItem() {
        this.dialogPembinaanView = false
        this.clearViewItem()
      },
      closeCreateItem(){
        this.dialogPembinaanCreate = false
        // this.clearCreateItem()
      },
    },
    //this will trigger in the onReady State
    mounted() {
      // console.log(this.$refs['mapID']);
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      this.readDataFromAPI();
      // this.editor_read.isReadOnly = true;
      // this.initMap();
      // this.sesi.judul_sesi = Array(100)
      // this.sesi.waktu_awal_sesi[0] = null
      // this.sesi.waktu_akhir_sesi[0] = null
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