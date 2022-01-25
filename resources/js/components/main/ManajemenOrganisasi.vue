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
            <!-- <v-btn @click="clearFormOrganisasi" v-if="!organisasi_edit_mode">
              reset
            </v-btn> -->

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
                :items="organisasis"
                item-text="nama_organisasi"
                item-value="id_organisasi"
                label="Pilih Organisasi"
                prepend-icon="mdi-office-building"
                :disabled="divisi.jenis_keanggotaan != null"
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
            <!-- <v-btn @click="clearFormDivisi" v-if="!divisi_edit_mode">
              reset
            </v-btn> -->

            <v-btn @click="dialog_divisi = false">
              close
            </v-btn>
          </form>
        </validation-observer>
      </v-card-text>
    </v-card>
  </v-dialog>

  <!-- DIALOG UNTUK Tim -->
  <v-dialog v-model="dialog_tim" persistent style="z-index:2001;">
    <v-card>
      <v-card-title class="text-h5">
        <v-icon>mdi-account-multiple"</v-icon>{{ timTitle }}
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
                :items="organisasis"
                item-text="nama_organisasi"
                item-value="id_organisasi"
                label="Pilih Organisasi"
                prepend-icon="mdi-office-building"
                @change="onChangeOrganisasiTim"
                required
                :disabled="divisi.jenis_keanggotaan != null"
              ></v-select>
            </validation-provider>

            <!-- Nama Divisi -->
            <validation-provider
              v-slot="{ errors }"
              name="Nama Divisi"
            >
              <v-select
                v-model="tim.id_divisi"
                :items="divisis"
                item-text="nama_divisi"
                item-value="id_divisi"
                label="Pilih Divisi"
                prepend-icon="mdi-home-modern"
                :disabled="divisi.jenis_keanggotaan != null"
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
            <!-- <v-btn @click="clearFormTim" v-if="!tim_edit_mode">
              reset
            </v-btn> -->

            <v-btn @click="dialog_tim = false">
              close
            </v-btn>
          </form>
        </validation-observer>
      </v-card-text>
    </v-card>
  </v-dialog>

  <!-- DIALOG UNTUK anggota dari keanggotaan-->
  <v-dialog fullscreen transition="dialog-bottom-transition" v-model="dialog_keanggotaan" style="z-index:2001;">
   <v-toolbar
      dark
      color="primary darken-3"
    >
      <v-toolbar-title>Keanggotaan {{ keanggotaan.jenis_keanggotaan }} {{ keanggotaan.nama_keanggotaan }}</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-toolbar-items>
        <v-btn
          icon
          dark
          @click="dialog_keanggotaan = false"
        >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-toolbar-items>
    </v-toolbar>
    <v-card>
      <v-card-text>
        <v-data-table
          :headers="keanggotaan_headers"
          :items="keanggotaan_items"
          :search="keanggotaan_search_value"
          item-key="id_entri"
          class="elevation-1"
          sort-by="name">
          <template v-slot:top>
            <v-toolbar
              flat
            >
              <v-toolbar-title>Daftar Anggota</v-toolbar-title>
              <v-divider
                class="mx-4"
                inset
                vertical
              ></v-divider>
              <v-spacer></v-spacer>
              <v-btn
                @click="addAnggotaOrganisasi"
                color="primary darken-3"
                class="white--text">
                Tambah Anggota
              </v-btn>
              <v-dialog v-model="dialog_delete_organisasi" max-width="75vw">
                <v-card>
                  <v-card-title class="headline">Apakah ingin melakukan penghapusan untuk data ({{ organisasi.nama_organisasi }})?</v-card-title>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialog_delete_organisasi = false">Cancel</v-btn>
                    <v-btn color="red darken-1" text @click="submitDeleteDataOrganisasi">OK</v-btn>
                    <v-spacer></v-spacer>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-toolbar>
            <v-toolbar flat>
              <v-text-field
              v-model="keanggotaan_search_value"
              append-icon="mdi-magnify"
              label="Cari anggota . . ."
              single-line
              hide-details
            ></v-text-field>
            </v-toolbar>
          </template>
          <template v-slot:item.created_at ="{ item }">
            {{ new Date(item.created_at).toUTCString() }}
          </template>
          <template v-slot:item.actions_organisasi="{ item }">
            <div>
              <v-btn color="primary darken-3" class="ma-3" @click="editAnggotaKeanggotaan(item)">
                <v-icon medium>
                  mdi-account-edit
                </v-icon>
                Hak Akses Anggota
              </v-btn>
              <v-btn class="ma-3" color="red" @click="deleteAnggotaKeanggotaan(item)">
                <v-icon medium >
                  mdi-account-remove
                </v-icon>
                Hapus Keanggotaan
              </v-btn>
            </div>
          </template>
          <template v-slot:no-data>
            NO DATA YET
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>
  </v-dialog>

  <!-- DIALOG UNTUK tambah anggota -->
  <v-dialog v-model="dialog_tambah_anggota_keanggotaan" persistent style="z-index:2005;">
    <v-card>
      <v-card-title class="text-h5 primary darken-3">
        Cari anggota untuk {{ keanggotaan_organisasi.nama_organisasi }}
      </v-card-title>
      <v-card-text class="black--text text--darken-3">
        <div id="pencarian" style="margin-top:5px;text-align:center;content-align:center;margin-right:20px;margin-left:20px;">
          <v-text-field
            label="Kolom pencarian user"
            append-icon="mdi-account-search"
            v-model="search_pengguna"
            @keyup.enter="updatingPencarianPengguna"
            @click:append="updatingPencarianPengguna"
          ></v-text-field>
        </div>
      </v-card-text>
      <v-card-text>
         <v-data-table
          :headers="header_daftar_pengguna"
          :items="daftar_pengguna"
          :search="search_tabel_daftar_pengguna"
          item-key="name"
          class="elevation-1 white-text"
          sort-by="name">
          <!-- <template v-slot:top>
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
                </v-card>
              </v-dialog>
            </v-toolbar>
            <v-toolbar flat>
              <v-text-field
              v-model="search_tabel_daftar_pengguna"
              append-icon="mdi-magnify"
              label="Search"
              single-line
              hide-details
            ></v-text-field>
            </v-toolbar>
          </template> -->
          <template v-slot:item.actions="{ item }">
            <div v-if="cekKeanggotaan(item) == true">
              <v-btn color="primary darken-2" @click="submitAddAnggota(item)">
                <v-icon>mdi-account-plus</v-icon>Jadikan anggota
              </v-btn>
            </div>
            <div v-else>
              <v-btn color="error darken-2" @click="submitDeleteAnggota(item)">
                <v-icon>mdi-account-minus</v-icon>Hapus keanggotaan
              </v-btn>
            </div>
          </template>
          <template v-slot:no-data>
            NOT FOUND
          </template>
          <template v-slot:item.created_at="{ item }">
            {{ new Date(item.created_at).toUTCString() }}
          </template>
        </v-data-table>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <!-- <v-btn
          class="mr-4"
          color="primary"
          @click="submitCreateDataOrganisasi"
        >
          submit anggota
        </v-btn> -->

        <v-btn @click="closeDialogAddAnggota">
          close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <!-- DIALOG untuk role anggota dari keanggotaan -->
  <v-dialog v-model="dialog_role_anggota_keanggotaan" persistent style="z-index:2005;">
    <v-card>
      <v-card-title class="text-h5 primary darken-3">
        Role untuk anggota
      </v-card-title>
          <v-card-text>
            <v-container fluid>
              <!-- role organisasi -->
              <v-row v-if="keanggotaan.jenis_keanggotaan == 'organisasi'">
                <v-col
                  cols="12"
                  sm="6"
                  md="6">
                  <v-checkbox
                    v-model="role_anggota"
                    label="Admin Organisasi"
                    color="red darken-3"
                    value="AdminOrganisasi"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Supervisor Organisasi"
                    color="red lighten-3"
                    value="SupervisorOrganisasi"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Penulis Konten Inovasi Organisasi"
                    color="indigo darken-3"
                    value="PenulisInovasiOrganisasi"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Penyunting Konten Inovasi Organisasi"
                    color="indigo lighten-3"
                    value="PenyuntingInovasiOrganisasi"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Penulis Konten Artikel Organisasi"
                    color="green darken-3"
                    value="PenulisArtikelOrganisasi"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Penyunting Konten Artikel Organisasi"
                    color="green lighten-3"
                    value="PenyuntingArtikelOrganisasi"
                    hide-details
                  ></v-checkbox>
                </v-col>
              </v-row>

              <!-- role divisi -->
              <v-row v-else-if="keanggotaan.jenis_keanggotaan == 'divisi'">
                <v-col
                  cols="12"
                  sm="6"
                  md="6">
                  <v-checkbox
                    v-model="role_anggota"
                    label="Admin Divisi"
                    color="red darken-3"
                    value="AdminDivisi"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Supervisor Divisi"
                    color="red lighten-3"
                    value="SupervisorDivisi"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Penulis Konten Inovasi Divisi"
                    color="indigo darken-3"
                    value="PenulisInovasiDivisi"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Penyunting Konten Inovasi Divisi"
                    color="indigo lighten-3"
                    value="PenyuntingInovasiDivisi"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Penulis Konten Artikel Divisi"
                    color="green darken-3"
                    value="PenulisArtikelDivisi"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Penyunting Konten Artikel Divisi"
                    color="green lighten-3"
                    value="PenyuntingArtikelDivisi"
                    hide-details
                  ></v-checkbox>
                </v-col>
              </v-row>

              <!-- role tim -->
              <v-row v-else>
                <v-col
                  cols="12"
                  sm="6"
                  md="6">
                  <v-checkbox
                    v-model="role_anggota"
                    label="Admin Tim"
                    color="red darken-3"
                    value="AdminTim"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Supervisor Tim"
                    color="red lighten-3"
                    value="SupervisorTim"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Penulis Konten Inovasi Tim"
                    color="indigo darken-3"
                    value="PenulisInovasiTim"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Penyunting Konten Inovasi Tim"
                    color="indigo lighten-3"
                    value="PenyuntingInovasiTim"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Penulis Konten Artikel Tim"
                    color="green darken-3"
                    value="PenulisArtikelTim"
                    hide-details
                  ></v-checkbox>
                </v-col>
                <v-col
                  cols="12"
                  sm="6"
                  md="6"
                >
                  <v-checkbox
                    v-model="role_anggota"
                    label="Penyunting Konten Artikel Tim"
                    color="green lighten-3"
                    value="PenyuntingArtikelTim"
                    hide-details
                  ></v-checkbox>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          class="mr-4"
          color="primary darken-3"
          @click="submitEditRoleAnggota"
        >
          submit role
        </v-btn>

        <v-btn @click="closeDialogRoleAnggota">
          close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  <!-- DATATABLE -->
  <v-card>
    <!-- kepala tab -->
    <v-tabs
      v-model="tab"
      background-color="primary accent-4"
      centered
      icons-and-text
    >
      <v-tabs-slider></v-tabs-slider>
      <v-tab href="#tab-1">
        Tim
        <v-icon>mdi-account-multiple</v-icon>
      </v-tab>
      <v-tab href="#tab-2">
        Divisi
        <v-icon>mdi-home-modern</v-icon>
      </v-tab>
      <v-tab href="#tab-3">
        Organisasi
        <v-icon>mdi-office-building</v-icon>
      </v-tab>
    </v-tabs>
    <!-- konten dari tab -->
    <v-tabs-items v-model="tab">
      <!-- konten tab 1 tim -->
      <v-tab-item
        value="tab-1">
        <v-card flat>
          <v-card-text>
            <v-data-table
              :headers="tim_headers"
              :items="tim_items"
              :search="tim_search_value"
              item-key="id_entri"
              class="elevation-1"
              sort-by="nama_tim">
              <template v-slot:top>
                <v-toolbar
                  flat
                >
                  <v-toolbar-title>Daftar Tim</v-toolbar-title>
                  <v-divider
                    class="mx-4"
                    inset
                    vertical
                  ></v-divider>
                  <v-spacer></v-spacer>
                  <v-btn
                    @click="createDataTim"
                    color="primary darken-3"
                    class="white--text">
                    Buat Tim
                  </v-btn>
                  <v-dialog v-model="dialog_delete_tim" max-width="75vw">
                    <v-card outlined shaped color="grey lighten-2">
                      <v-card-title>Penghapusan Data Tim</v-card-title>
                      <v-card-text class="headline">Apakah ingin melakukan penghapusan untuk data ({{ tim.nama_tim }})?</v-card-text>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="white" class="blue--text" @click="dialog_delete_tim = false">Cancel</v-btn>
                        <v-btn color="red lighten" class="white--text" @click="submitDeleteDataTim">HAPUS</v-btn>
                        <v-spacer></v-spacer>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-toolbar>
                <v-toolbar flat>
                  <v-text-field
                  v-model="tim_search_value"
                  append-icon="mdi-magnify"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>
                </v-toolbar>
              </template>
              <template v-slot:item.logo_tim="{ item }">
                <!-- <div class="p-2"> -->
                  <v-img :src="url_base+'/api/logo_tim/'+item.logo_tim" :alt="item.logo_tim" class="ma-2" contain max-width="100px" height="100px"></v-img>
                <!-- </div> -->
              </template>
              <template v-slot:item.created_at ="{ item }">
                {{ new Date(item.created_at).toUTCString() }}
              </template>
              <template v-slot:item.actions_tim="{ item }">
                <div>
                  <v-btn class="ma-3"
                      color="primary darken-3"
                      @click="editDataTim(item)">
                    <v-icon medium>
                      mdi-pencil
                    </v-icon>
                    Edit Profil Tim
                  </v-btn>

                  <v-btn class="ma-3"
                      color="secondary darken-3"
                       @click="konfigurasiKeanggotaan(item.id_tim, item.nama_tim,'tim', item.id_organisasi)">
                    <v-icon medium>
                      mdi-account-cog-outline
                    </v-icon>
                    Anggota Tim
                  </v-btn>

                  <v-btn class="ma-3"
                      color="error darken-3"
                       @click="deleteDataTim(item)">
                    <v-icon medium>
                      mdi-delete
                    </v-icon>
                    Hapus Tim
                  </v-btn>
                </div>
              </template>
              <template v-slot:no-data>
                NO DATA YET
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-tab-item>
      <!-- konten tab 2 divisi -->
      <v-tab-item
        value="tab-2">
        <v-card flat>
          <v-card-text>
            <v-data-table
              :headers="divisi_headers"
              :items="divisi_items"
              :search="divisi_search_value"
              item-key="id_entri"
              class="elevation-1"
              sort-by="name_divisi">
              <template v-slot:top>
                <v-toolbar
                  flat
                >
                  <v-toolbar-title>Daftar Divisi</v-toolbar-title>
                  <v-divider
                    class="mx-4"
                    inset
                    vertical
                  ></v-divider>
                  <v-spacer></v-spacer>
                  <v-btn
                    @click="createDataDivisi"
                    color="primary darken-3"
                    class="white--text">
                    Buat Divisi
                  </v-btn>
                  <v-dialog v-model="dialog_delete_divisi" max-width="75vw">
                    <v-card>
                      <v-card-title class="headline">Apakah ingin melakukan penghapusan untuk data ({{ divisi.nama_divisi }})?</v-card-title>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="dialog_delete_divisi = false">Cancel</v-btn>
                        <v-btn color="red darken-1" text @click="submitDeleteDataDivisi">OK</v-btn>
                        <v-spacer></v-spacer>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-toolbar>
                <v-toolbar flat>
                  <v-text-field
                  v-model="divisi_search_value"
                  append-icon="mdi-magnify"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>
                </v-toolbar>
              </template>
              <template v-slot:item.logo_divisi="{ item }">
                <!-- <div class="p-2"> -->
                  <v-img :src="url_base+'/api/logo_divisi/'+item.logo_divisi" :alt="item.logo_divisi" class="ma-2" contain max-width="100px" height="100px"></v-img>
                <!-- </div> -->
              </template>
              <template v-slot:item.created_at ="{ item }">
                {{ new Date(item.created_at).toUTCString() }}
              </template>
              <template v-slot:item.actions_divisi="{ item }">
                <div>
                  <v-btn class="ma-3"
                      color="primary darken-3"
                      @click="editDataDivisi(item)">
                    <v-icon medium>
                      mdi-pencil
                    </v-icon>
                    Edit Profil Divisi
                  </v-btn>

                  <v-btn class="ma-3"
                      color="secondary darken-3"
                      @click="konfigurasiKeanggotaan(item.id_divisi, item.nama_divisi,'divisi', item.id_organisasi)">
                    <v-icon medium>
                      mdi-account-cog-outline
                    </v-icon>
                    Anggota Divisi
                  </v-btn>

                  <v-btn class="ma-3"
                      color="error darken-3"
                       @click="deleteDataDivisi(item)">
                    <v-icon medium>
                      mdi-delete
                    </v-icon>
                    Hapus Divisi
                  </v-btn>
                </div>
              </template>
              <template v-slot:no-data>
                NO DATA YET
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-tab-item>
      <!-- konten tab 3 organisasi -->
      <v-tab-item
        value="tab-3">
        <v-card flat>
          <v-card-text>
            <v-data-table
              :headers="organisasi_headers"
              :items="organisasi_items"
              :search="organisasi_search_value"
              item-key="id_entri"
              class="elevation-1"
              sort-by="nama_organisasi">
              <template v-slot:top>
                <v-toolbar
                  flat
                >
                  <v-toolbar-title>Daftar Organisasi</v-toolbar-title>
                  <v-divider
                    class="mx-4"
                    inset
                    vertical
                  ></v-divider>
                  <v-spacer></v-spacer>
                  <v-btn
                    @click="createDataOrganisasi"
                    color="primary darken-3"
                    class="white--text">
                    Buat Organisasi
                  </v-btn>
                  <v-dialog v-model="dialog_delete_organisasi" max-width="75vw">
                    <v-card>
                      <v-card-title class="headline">Apakah ingin melakukan penghapusan untuk data ({{ organisasi.nama_organisasi }})?</v-card-title>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="dialog_delete_organisasi = false">Cancel</v-btn>
                        <v-btn color="red darken-1" text @click="submitDeleteDataOrganisasi">OK</v-btn>
                        <v-spacer></v-spacer>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>
                </v-toolbar>
                <v-toolbar flat>
                  <v-text-field
                  v-model="organisasi_search_value"
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
              <template v-slot:item.actions_organisasi="{ item }">
                <div>
                  <v-btn class="ma-3"
                      color="primary darken-3"
                      @click="editDataOrganisasi(item)">
                    <v-icon medium>
                      mdi-pencil
                    </v-icon>
                    Edit Profil Organisasi
                  </v-btn>

                  <v-btn class="ma-3"
                      color="secondary darken-3"
                      @click="konfigurasiKeanggotaan(item.id_organisasi, item.nama_organisasi,'organisasi', item.id_organisasi)">
                    <v-icon medium>
                      mdi-account-cog-outline
                    </v-icon>
                    Anggota Organisasi
                  </v-btn>

                  <v-btn class="ma-3"
                      color="error darken-3"
                       @click="deleteDataOrganisasi(item)">
                    <v-icon medium>
                      mdi-delete
                    </v-icon>
                    Hapus Organisasi
                  </v-btn>
                </div>
              </template>
              <template v-slot:no-data>
                NO DATA YET
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
  </v-card>
  <v-snackbar
      v-model="snackbar"
      :multi-line="multiLine"
      top
      :color= warnaSnackbar
      style="z-index:2100">  
      {{ teksSnackbar }}
    <!-- <template v-slot:action="{ attrs }">
      <v-btn
        color="white"
        text
        v-bind="attrs"
        @click="snackbar = false"
      >
        Close
      </v-btn>
    </template> -->
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
        overlay_text: 'Loading . . .',
        teksSnackbar:'',
        warnaSnackbar:'primary',
        snackbar:false,
        multiLine:true,
        permissions : [],
        tab: null,
        tim: {},
        organisasi_edit_mode: true,
        divisi_edit_mode: true,
        tim_edit_mode: true,
        dialog_organisasi: false,
        dialog_divisi: false,
        dialog_tim: false,
        dialog_delete_organisasi: false,
        dialog_delete_divisi: false,
        dialog_delete_tim: false,
        status_delete: '',
        tim_search_value:'',
        tim_headers: [
          {
            text: 'Logo Organisasi',
            value: 'logo_tim',
            sortable: false,
          },
          {
            text: 'Nama Tim',
            align: 'start',
            value: 'nama_tim',
            width: '250px'
          },
          {
            text: 'Nama Divisi',
            value: 'divisi.nama_divisi',
            width: '200px'
          },
          {
            text: 'Nama Organisasi',
            value: 'organisasi.nama_organisasi',
            width: '200px'
          },
          { text: 'Alamat', value: 'alamat_tim' },
          { text: 'Terdaftarkan', value: 'created_at' },
          { text: 'Dimutakhirkan', value: 'updated_by' },
          { text: 'Actions', value: 'actions_tim', sortable: false, width: '150px' },
        ],
        tim_items: [],
        divisi_search_value:'',
        divisi_headers: [
          {
            text: 'Logo Divisi',
            value: 'logo_divisi',
            sortable: false,
          },
          {
            text: 'Nama Divisi',
            value: 'nama_divisi',
            width: '200px'
          },
          {
            text: 'Nama Organisasi',
            value: 'organisasi.nama_organisasi',
            width: '200px'
          },
          { text: 'Alamat', value: 'alamat_divisi' },
          { text: 'Terdaftarkan', value: 'created_at' },
          { text: 'Dimutakhirkan', value: 'updated_by' },
          { text: 'Actions', value: 'actions_divisi', sortable: false, width: '150px' },
        ],
        divisi_items: [],
        organisasi_search_value:'',
        organisasi_headers: [
          {
            text: 'Logo Organisasi',
            value: 'logo_organisasi',
            sortable: false,
          },
          {
            text: 'Nama Organisasi',
            value: 'nama_organisasi',
            width: '200px'
          },
          { text: 'Alamat Organisasi', value: 'alamat_organisasi' },
          { text: 'Terdaftarkan', value: 'created_at' },
          { text: 'Dimutakhirkan', value: 'updated_by' },
          { text: 'Actions', value: 'actions_organisasi', sortable: false, width: '150px' },
        ],
        organisasi_items: [],
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
        dialog_keanggotaan: false,
        dialog_tambah_anggota_keanggotaan: false,
        keanggotaan: {
          nama_keanggotaan:'',
          jenis_keanggotaan: '',
          id_keanggotaan:'',
          id_organisasi:''
        },
        keanggotaan_organisasi: {},
        keanggotaan_search_value:'',
        keanggotaan_headers: [
          {
            text: 'Nama',
            value: 'name',
            width: '200px'
          },
          { text: 'Email', value: 'email' },
          { text: 'Terdaftarkan', value: 'created_at' },
          { text: 'Actions', value: 'actions_organisasi', sortable: false, width: '150px' },
        ],
        keanggotaan_items: [],
        search_pengguna: '',
        search_tabel_daftar_pengguna: '',
        loading_pencarian: '',
        daftar_pengguna: [],
        header_daftar_pengguna: [
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
        dialog_role_anggota_keanggotaan: false,
        role_anggota: [],
        user_selected: {},
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
        val || this.closeDialogOrganisasi()
      },
      dialog_divisi (val) {
        val || this.closeDialogDivisi()
      },
      dialog_tim (val) {
        val || this.closeDialogTim()
      },
    },

    async created () {
      this.overlay = true;
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      await axios.get('/api/user-permission').then(response => {
          this.currentUser = response.data.user;
          this.$store.commit('updateRBAC', response.data.permissions)
          if(!(response.data.permissions).includes('users.update')){
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
        this.overlay_text = 'Mengambil data organisasi . . .'
        await axios.get('/api/user-members').then(response => {
            console.log(response)
        }).catch(errors => {
            console.log(errors)
        });
        await axios.get('/api/user-memberships/AdminOrganisasi&PenulisOrganisasi/detail').then(response => {
            console.log(response)
        }).catch(errors => {
            console.log(errors)
        });
        await axios.get('/api/read-organisasi').then(response => {
            if(response.data.status == true){
              // this.items = response.data.data
              this.organisasis = response.data.data
              this.organisasi_items = response.data.data
            }else{
              console.log(response.data.message)
            }
        }).catch(errors => {
            console.log(errors.response.data.errors)
        });
        this.overlay_text = 'Mengambil data divisi . . .'
        await axios.get('/api/read-divisi').then(response => {
            if(response.data.status == true){
              // this.items = response.data.data
              this.divisi_items = response.data.data
              // console.log(response.data.data)
              // this.organisasi_items = response.data.data
            }else{
              console.log(response.data.message)
            }
        }).catch(errors => {
            console.log(errors.response.data.errors)
        });
        this.overlay_text = 'Mengambil data tim . . .'
        await axios.get('/api/read-tim').then(response => {
            if(response.data.status == true){
              // this.items = response.data.data
              this.tim_items = response.data.data
              // console.log(response.data.data)
            }else{
              console.log(response.data.message)
            }
        }).catch(errors => {
            console.log(errors.response.data.errors)
        });
        this.overlay_text = 'Loading . . .'
        // console.log(this.items)
      },
      async updatingPencarianPengguna(){
        if(this.search_pengguna == null || this.search_pengguna == ''){
          this.overlay = true
          this.loading_pencarian = false
          this.daftar_pengguna = []
          this.overlay = false
        }else{
          this.overlay = true
          this.daftar_pengguna = []
          this.loading_pencarian = true
          var refs = this
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          // console.log('/api/users/'+this.search_pengguna.toString()+`?jenis_keanggotaan=organisasi&id_keanggotaan=${this.keanggotaan_organisasi.id_organisasi}&keanggotaan_pengguna=tambah_anggota`)
          // axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          let url = '/api/users/'+this.search_pengguna.toString()+`?jenis_keanggotaan=organisasi&id_keanggotaan=${this.keanggotaan_organisasi.id_organisasi}&keanggotaan_pengguna=tambah_anggota`
          await axios.get(url).then((response) => {
            refs.daftar_pengguna = response.data
          }).catch(errors => {
            console.log(errors)
          }).finally(() => {
            this.loading_pencarian = false
            console.log('Berhasil')
          });
          this.overlay = false
        }
      },
      async editAnggotaKeanggotaan(item){
        this.overlay_text = 'Mengambil role user di keanggotaan . . .'
        this.overlay = true
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        let url = `/api/take-keanggotaan/?id_user=${item.id}&id_keanggotaan=${this.keanggotaan.id_keanggotaan}&jenis_keanggotaan=${this.keanggotaan.jenis_keanggotaan}`
        // console.log(url)
        this.user_selected = item
        await axios.get(url).then(response => {
            if(response.data.status == true){
              let data = response.data.data
              let list_role = []
              data.forEach(element => {
                list_role.push(element['role_keanggotaan']);
              })
              this.role_anggota = list_role
            }else{
              console.log(response.data.message)
            }
        }).catch(errors => {
            console.log(errors.response.data.errors)
        }).finally(()=>{});
        this.dialog_role_anggota_keanggotaan = true
        this.overlay = false
      },
      async deleteAnggotaKeanggotaan(item){
        this.overlay_text = 'Menghapus keanggotaan . . .'
        this.overlay = true
        await this.submitDeleteAnggota(item)
        await this.konfigurasiKeanggotaan(this.keanggotaan.id_keanggotaan, this.keanggotaan.nama_keanggotaan, this.keanggotaan.jenis_keanggotaan, this.keanggotaan.id_organisasi)
        this.overlay = false
        this.overlay_text = 'Loading . . .'
      },
      async submitAddAnggota(user_add){
        this.overlay = true
        this.overlay_text = 'Memasukkan pengguna kedalam keanggotaan . . .'
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }
        var formData = new FormData();
        // menambahkan data inovation kedalam form untuk di upload
        formData.append('id_user', user_add.id);
        formData.append('id_keanggotaan', this.keanggotaan.id_keanggotaan);
        formData.append('jenis_keanggotaan', this.keanggotaan.jenis_keanggotaan);
        formData.append('id_organisasi', this.keanggotaan.id_organisasi);
        await axios.post("/api/create-keanggotaan",  formData, config).then(response => {
          if(response.data.status = true){
            this.teksSnackbar= response.data.message
            this.warnaSnackbar= "primary darken-2"
          }else{
            this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.message)
            this.warnaSnackbar= "red"
          }
        }).catch(errors => {
          this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"]),
          this.warnaSnackbar= "red",
          this.snackbar = true
        }).finally(async () => {
          this.overlay_text = 'Memaperbaharui data tampilan . . .'
          let url = '/api/users/'+this.search_pengguna.toString()+`?jenis_keanggotaan=organisasi&id_keanggotaan=${this.keanggotaan_organisasi.id_organisasi}&keanggotaan_pengguna=tambah_anggota`
          let refs = this
          await axios.get(url).then((response) => {
            refs.daftar_pengguna = response.data
          }).catch(errors => {
            console.log(errors)
          }).finally(() => {
            this.loading_pencarian = false
            console.log('Berhasil')
          });
          this.overlay = false
          // await this.konfigurasiKeanggotaan(this.keanggotaan.id_keanggotaan, this.keanggotaan.nama_keanggotaan, this.keanggotaan.jenis_keanggotaan)
          this.snackbar = true
        })
      },
      async submitDeleteAnggota(user_add){
        this.overlay = true
        this.overlay_text = 'Menghilangkan pengguna kedalam keanggotaan . . .'
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }
        var formData = new FormData();
        // menambahkan data inovation kedalam form untuk di upload
        formData.append('id_user', user_add.id);
        formData.append('id_keanggotaan', this.keanggotaan.id_keanggotaan);
        formData.append('jenis_keanggotaan', this.keanggotaan.jenis_keanggotaan);
        formData.append('id_organisasi', this.keanggotaan.id_organisasi);
        // console.log(formData)
        await axios.post("/api/delete-keanggotaan", formData, config).then(response => {
          if(response.data.status = true){
            this.teksSnackbar= response.data.message
            this.warnaSnackbar= "primary darken-2"
          }else{
            this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.message)
            this.warnaSnackbar= "red"
          }
        }).catch(errors => {
          this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.data.errors["message"]),
          this.warnaSnackbar= "red",
          this.snackbar = true
        }).finally(async () => {
          this.overlay_text = 'Memperbaharui data tampilan . . .'
          let url = '/api/users/'+this.search_pengguna.toString()+`?jenis_keanggotaan=organisasi&id_keanggotaan=${this.keanggotaan_organisasi.id_organisasi}&keanggotaan_pengguna=tambah_anggota`
          let refs = this
          await axios.get(url).then((response) => {
            refs.daftar_pengguna = response.data
          }).catch(errors => {
            console.log(errors)
          }).finally(() => {
            this.loading_pencarian = false
            console.log('Berhasil')
          });
          this.overlay = false
          // await this.konfigurasiKeanggotaan(this.keanggotaan.id_keanggotaan, this.keanggotaan.nama_keanggotaan, this.keanggotaan.jenis_keanggotaan)
          this.snackbar = true
        })
      },
      async submitEditRoleAnggota(){
        this.overlay = true
        this.overlay_text = 'Mengubah role anggota . . .'
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }
        let user_temp = this.user_selected
        var formData = new FormData();
        // menambahkan data inovation kedalam form untuk di upload
        formData.append('id_user', user_temp.id);
        formData.append('id_keanggotaan', this.keanggotaan.id_keanggotaan);
        formData.append('jenis_keanggotaan', this.keanggotaan.jenis_keanggotaan);
        formData.append('role_keanggotaan', this.role_anggota);
        console.log(this.role_anggota)
        await axios.post("/api/update-keanggotaan",  formData, config).then(response => {
          if(response.data.status = true){
            this.teksSnackbar= response.data.message
            this.warnaSnackbar= "primary darken-2"
          }else{
            this.teksSnackbar= "Terjadi Kesalahan : "+ (response.data.message)
            this.warnaSnackbar= "red"
          }
        }).catch(errors => {
          this.teksSnackbar= "Terjadi Kesalahan : "+ (errors)
          this.warnaSnackbar= "red"
          console.log(errors)
        }).finally(() => {
          this.overlay_text = 'Loading . . .'
          this.overlay = false
          this.snackbar = true
        })
      },
      async konfigurasiKeanggotaan(id_keanggotaan, nama_keanggotaan, jenis_keanggotaan, id_organisasi){
        this.keanggotaan = {
          'id_keanggotaan' : id_keanggotaan,
          'nama_keanggotaan' : nama_keanggotaan,
          'jenis_keanggotaan' : jenis_keanggotaan,
          'id_organisasi' : id_organisasi
        }
        this.overlay = true
        this.overlay_text = "Ambil data anggota"
        // this.keanggotaan_organisasi = item
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        let url = '/api/users?id_keanggotaan='+id_keanggotaan+'&jenis_keanggotaan='+jenis_keanggotaan
        // console.log(url)
        await axios.get(url).then(response => {
            this.keanggotaan_items = response.data
            // console.log(response.data)
        }).catch(errors => {
          console.log(errors)
        });
        this.dialog_keanggotaan = true
        this.overlay = false
        this.overlay_text = 'Loading . . . '

      },
      cekKeanggotaan(item){
        // console.log(item)
        let check = true
        item.keanggotaans.forEach(element => {
          // print(element)
          if(element.id_keanggotaan == this.keanggotaan.id_keanggotaan && element.jenis_keanggotaan == this.keanggotaan.jenis_keanggotaan){
            // console.log('masuk')
            check = false
          }
        });
        return check
      },
      async closeDialogAddAnggota(){
        this.overlay_text = "Memperbaharui daftar anggota"
        this.overlay = true
        await this.konfigurasiKeanggotaan(this.keanggotaan.id_keanggotaan, this.keanggotaan.nama_keanggotaan, this.keanggotaan.jenis_keanggotaan, this.keanggotaan.id_organisasi)
        this.dialog_tambah_anggota_keanggotaan = false
        this.daftar_pengguna = []
        this.search_pengguna = ''
        this.overlay = false
      },
      async closeDialogRoleAnggota(){
        this.overlay_text = "Memperbaharui daftar anggota"
        this.overlay = true
        this.role_anggota = []
        this.dialog_role_anggota_keanggotaan = false
        this.overlay = false
      },
      async onChangeOrganisasiTim(event) {
        this.overlay = true
        // console.log(event.target.value)
        if(this.tim.id_organisasi != null){
          // ambil data divisi
          this.overlay_text = 'Ambil data divisi'
          await axios.get('/api/read-divisi/'+this.tim.id_organisasi).then(response => {
              if(response.data.status == true){
                // this.items = response.data.data
                this.divisis = response.data.data
                this.divisis.push({
                  id_divisi: null,
                  nama_divisi: 'Tidak memiliki divisi'
                })
              }else{
                console.log(response.data.message)
              }
          }).catch(errors => {
              console.log(errors.response.data.errors)
          });
        }else{
          this.tim.id_divisi = null
        }
        this.overlay = false
        this.overlay_text = 'Loading . . .'
      },
      addAnggotaOrganisasi(jenis_keanggotaan){
        this.overlay_text = "Ambil data anggota dari organisaasi "+this.keanggotaan_organisasi.nama_organisasi
        this.overlay = true
        this.dialog_tambah_anggota_keanggotaan = true
        this.overlay_text = 'Loading . . .'
        this.overlay = false
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
        this.edited_index_organisasi = this.organisasi_items.indexOf(item)
        this.organisasi = Object.assign({}, item)
        this.organisasi_edit_mode = true;
        this.dialog_organisasi = true;
        this.overlay = false
      },
      deleteDataOrganisasi(item){
        this.overlay = true
        this.edited_index_organisasi = this.organisasi_items.indexOf(item)
        this.organisasi = Object.assign({}, item)
        // this.organisasi_edit_mode = true;
        this.dialog_delete_organisasi = true;
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
            this.closeDialogOrganisasi()
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
            this.closeDialogOrganisasi()
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
      async submitDeleteDataOrganisasi(){},
      closeDialogOrganisasi () {
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
        this.edited_index_divisi = this.divisi_items.indexOf(item)
        this.divisi = Object.assign({}, item)
        this.divisi_edit_mode = true;
        this.dialog_divisi = true;
        this.overlay = false
      },
      deleteDataDivisi(item){
        this.overlay = true
        this.edited_index_divisi = this.divisi_items.indexOf(item)
        this.divisi = Object.assign({}, item)
        // this.divisi_edit_mode = true;
        this.dialog_delete_divisi = true;
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
            this.closeDialogDivisi()
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
            this.closeDialogDivisi()
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
      async submitDeleteDataDivisi(){},
      closeDialogDivisi () {
        this.dialog_divisi = false
        this.$nextTick(() => {
          this.divisi = Object.assign({}, this.default_item_divisi)
          this.edited_index_divisi = -1
        })
      },
      // fungsi terkait untuk pengelolaan tim
      createDataTim(){
        this.overlay = true
        this.edited_index_tim = -1
        this.tim = Object.assign({}, this.default_item_tim)
        this.tim_edit_mode = false;
        this.dialog_tim = true;
        this.overlay = false
      },
      async editDataTim(item){
        this.overlay = true
        this.edited_index_tim = this.tim_items.indexOf(item)
        this.tim = Object.assign({}, item)
        console.log(this.tim)
        if(this.tim.id_organisasi != null){
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/read-divisi/'+this.tim.id_organisasi).then(response => {
              if(response.data.status == true){
                // this.items = response.data.data
                this.divisis = response.data.data
                this.divisis.push({
                  id_divisi: null,
                  nama_divisi: 'Tidak memiliki divisi'
                })
              }else{
                console.log(response.data.message)
              }
          }).catch(errors => {
              console.log(errors.response.data.errors)
          });
        }else{
          this.tim.id_divisi = null
        }
        this.tim_edit_mode = true;
        this.dialog_tim = true;
        this.overlay = false
      },
      deleteDataTim(item){
        this.overlay = true
        this.edited_index_tim = this.tim_items.indexOf(item)
        this.tim = Object.assign({}, item)
        // this.tim_edit_mode = true;
        this.dialog_delete_tim = true;
        this.overlay = false
      },
      async submitCreateDataTim() {
        let status_form = this.$refs.observer_tim.validate()
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
          formData.append('nama_tim', this.tim.nama_tim);
          formData.append('id_organisasi', this.tim.id_organisasi);
          formData.append('id_divisi', this.tim.id_divisi);
          formData.append('alamat_tim', this.tim.alamat_tim);
          formData.append('keterangan_tim', this.tim.keterangan_tim);
          formData.append('logo_tim_new', this.tim.logo_tim_new);
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.post("/api/create-tim",  formData, config).then(response => {
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
            this.closeDialogTim()
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
      async submitEditDataTim() {
        let status_form = this.$refs.observer_tim.validate()
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
          formData.append('id_tim', this.tim.id_tim);
          formData.append('id_organisasi', this.tim.id_organisasi);
          formData.append('id_divisi', this.tim.id_divisi);
          formData.append('nama_tim', this.tim.nama_tim);
          formData.append('alamat_tim', this.tim.alamat_tim);
          formData.append('keterangan_tim', this.tim.keterangan_tim);
          // isian logo_tim berupa string untuk nama yang lama
          formData.append('logo_tim', this.tim.logo_tim);
          // isian logo_tim_new berupa file
          formData.append('logo_tim_new', this.tim.logo_tim_new);
          // console.log(formData)
          // console.log(this.tim)
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.post("/api/update-tim",  formData, config).then(response => {
            if(response.data.status = true){
              this.teksSnackbar= response.data.message
              this.warnaSnackbar= "primary darken-2"
            }else{
              this.teksSnackbar= "Terjadi Kesalahan : "+ (errors.response.message)
              this.warnaSnackbar= "red"
            }
          }).catch(errors => {
            this.teksSnackbar= "Terjadi Kesalahan : "+ (errors),
            this.warnaSnackbar= "red",
            this.snackbar = true
            console.log(errors)
          }).finally(async () => {
            await this.initialize()
            this.closeDialogTim()
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
      async submitDeleteDataTim(){
        this.overlay = true
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }
        var formData = new FormData();
        formData.append('id_tim', this.tim.id_tim);
        formData.append('id_organisasi', this.tim.id_organisasi);
        formData.append('id_entri', this.tim.id_entri);
        // console.log(formData);
        this.overlay_text = "Proses menghapus data . . ."
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        await axios.post("/api/delete-tim", formData, config).then(response => {
          // console.log(response.data)
          if(response.data.status == false){
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message)
            this.warnaSnackbar= "red"

          }else{
            this.dialogKonfirmasiHapus = false
            this.teksSnackbar= response.data.message
            this.warnaSnackbar= "primary lighten-2"
          }
        }).catch(errors => {
          this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data)
          console.log(errors)
          this.warnaSnackbar= "error"
        }).finally(async () => {
          // this.clear()
          await this.initialize()
          this.overlay = false
          this.dialog_delete_tim = false
          this.snackbar = true
        })
      },
      closeDialogTim () {
        this.dialog_tim = false
        this.$nextTick(() => {
          this.tim = Object.assign({}, this.default_item_tim)
          this.edited_index_tim = -1
        })
      },
    },
  }
</script>