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
    <!-- dialog untuk memasukkan infografis -->
    <v-dialog
      transition="dialog-bottom-transition"
      min-width="80vh"
      v-model="dialogCreate"
      style="z-index:5000;">
        <v-card>
          <v-toolbar
            color="primary"
            dark>
          Upload Gambar
        </v-toolbar>
          <v-card-text class="mt-3">
            <form @submit.prevent="submit">
              <!-- judul berita -->
              <h3><b>Judul Infografis</b></h3>
              <v-text-field
                v-model="infografis.name"
                :error-messages="errors"
                label="Judul Berita/Artikel/Konten"
                required
                prepend-icon="mdi-alphabetical-variant"></v-text-field>

              <!-- kalimat pengatar berita -->
              <h3><b>Deskripsi</b></h3>
              <v-text-field
                v-model="infografis.description"
                :error-messages="errors"
                label="Kalimat Pengantar Berita/Artikel/Konten"
                required
                prepend-icon="mdi-alphabetical-variant"></v-text-field>

              <!-- tautan link infografis -->
              <h3><b>Tautan Infografis</b></h3>
              <v-text-field
                v-model="infografis.tautan_infografis"
                :error-messages="errors"
                label="Link/Tautan terkait Infografis"
                required
                prepend-icon="mdi-web"></v-text-field>

              <!-- tags artikek -->
              <h3 class="mt-2"><b>Tags Infografis</b></h3>
              <h5 class="mt-2">Tekan tombol enter setelah membuat tag</h5>
              <v-combobox multiple
                color="secondary"
                v-model="tags_select" 
                label="Tags Infografis" 
                append-icon
                chips
                deletable-chips
                class="tag-input"
                :search-input.sync="tags_search" 
                @keyup.tab="updateTags"
                @paste="updateTags">
              </v-combobox>

              <h3 class="mt-2"><b>Tampilkan Infografis Ke Halaman Publik</b></h3>
              <v-select
                v-model="infografis.tampilan_web"
                :items="tampilan_web_items"
                item-text="teks"
                item-value="value"
                label="Tampilankan di Web"
                prepend-icon="mdi-format-list-bulleted-square"
              ></v-select>

              <!-- kalimat pengatar berita -->
              <h3><b>File Infografis (format: JPEG, JPG, PNG, GIF)</b></h3>
                <v-file-input
                  v-model="infografis.source"
                  chips
                  show-size
                  truncate-length="49"
                  :error-messages="errors"
                  label="Gambar Cover Depan Berita"
                  required
                  prepend-icon="mdi-image">
                </v-file-input>
                <v-container style="max-width:75vw;content-align:center;text-align:center;">
                  <v-img :src="imagePreview" />
                </v-container>
            </form>
          </v-card-text>
          <v-card-actions class="justify-end">
            <v-btn
              text
              @click="dialogCreate= false"
            >Close</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              large
              class="ma-2"
              color="primary"
              @click="submitItem"
              :disabled="disabledUploadInfografis"
            >Submit</v-btn>
          </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- dialog untuk menyunting infografis -->
    <v-dialog
      transition="dialog-bottom-transition"
      min-width="80vw"
      v-model="dialogEdit"
      style="z-index:5000;">
        <v-card>
          <v-toolbar
            color="secondary"
            dark>
          Edit Infografis
        </v-toolbar>
          <v-card-text class="mt-3">
            <form @submit.prevent="submit">
              <!-- judul berita -->
              <h3><b>Judul Infografis</b></h3>
              <v-text-field
                v-model="infografis_edit.name"
                :error-messages="errors"
                label="Judul Berita/Artikel/Konten"
                required
                prepend-icon="mdi-alphabetical-variant"></v-text-field>

              <!-- kalimat pengatar berita -->
              <h3><b>Deskripsi</b></h3>
              <v-text-field
                v-model="infografis_edit.description"
                :error-messages="errors"
                label="Kalimat Pengantar Berita/Artikel/Konten"
                required
                prepend-icon="mdi-alphabetical-variant"></v-text-field>

              <!-- tautan link infografis -->
              <h3><b>Tautan Infografis</b></h3>
              <v-text-field
                v-model="infografis_edit.tautan_infografis"
                :error-messages="errors"
                label="Link/Tautan terkait Infografis"
                required
                prepend-icon="mdi-web"></v-text-field>

              <!-- tags artikek -->
              <h3 class="mt-2"><b>Tags Infografis</b></h3>
              <h5 class="mt-2">Tekan tombol enter setelah membuat tag</h5>
              <v-combobox multiple
                color="secondary"
                v-model="tags_select_edit" 
                label="Tags Infografis" 
                append-icon
                chips
                deletable-chips
                class="tag-input"
                :search-input.sync="tags_search_edit" 
                @keyup.tab="updateTagsEdit"
                @paste="updateTagsEdit">
              </v-combobox>

              <h3 class="mt-2"><b>Tampilkan Infografis Ke Halaman Publik</b></h3>
              <v-select
                v-model="infografis_edit.tampilan_web"
                :items="tampilan_web_items"
                item-text="teks"
                item-value="value"
                label="Tampilankan di Web"
                prepend-icon="mdi-format-list-bulleted-square"
              ></v-select>

              <!-- kalimat pengatar berita -->
              <h3><b>File Infografis Baru (format: JPEG, JPG, PNG, GIF)</b></h3>
                <v-file-input
                  v-model="infografis_edit.source_new"
                  chips
                  show-size
                  truncate-length="49"
                  :error-messages="errors"
                  label="Gambar Cover Depan Berita"
                  required
                  prepend-icon="mdi-image">
                </v-file-input>
                <h3><b>Gambar File Infografis Yang Sudah Ada</b></h3>
                <v-container style="max-width:75vw;content-align:center;text-align:center;">
                  <v-img :src="url_base+'/api/infografis/'+infografis_edit.source" />
                </v-container>
            </form>
          </v-card-text>
          <v-card-actions class="justify-end">
            <v-btn
              text
              @click="dialogCreate= false"
            >Close</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              large
              class="ma-2"
              color="primary"
              @click="submitEdit"
            >Submit</v-btn>
          </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- DIALOG UNTUK KONFIRMASI HAPUS -->
    <v-dialog v-model="dialogKonfirmasiHapus" persistent max-width="60vw">
      <v-card>
        <v-card-title class="text-h5">
          Hapus infografis {{infografis.name}} dari daftar infografis aktif? 
        </v-card-title>
        <v-card-text>Dengan menghapus data infografis ini ({{infografis.name}}), data infografis ini akan dihapus permanen</v-card-text>
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
            @click="deleteItem"
          >
            Ya, hapus infografis
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- DIALOG MEMUNCULKAN GAMBAR -->
    <v-dialog
      transition="dialog-bottom-transition"
      max-width="90vw"
      v-model="dialogView"
      scrollable
      style="z-index:5001;">
        <v-card>
          <v-toolbar
            color="primary"
            dark>
          Gambar {{ viewGambarNama }}
          <v-spacer></v-spacer>
          <v-btn text @click="dialogView=false">Close</v-btn>
        </v-toolbar>
        <v-card-text>
          <v-img :src='viewGambarLink'>
          </v-img>
        </v-card-text>
        </v-card>
    </v-dialog>
    <v-layout>
        <div class="flex">
          <div class="sibling-1">
            <h3>Daftar Infografis Tampil 
              <v-btn
                class="mx-2 my-2"
                large
                @click="confirmInfografis"
                color="primary">
                <v-icon class="mr-2">
                  mdi-arrange-send-backward
                </v-icon>
                 Submit/Pasang Infografis
              </v-btn>
            </h3>
            <draggable class="list-group" :list="infografis_selected" group="people" @change="log">
              <v-card class="ma-1" v-for="(element, index) in infografis_selected" :key="index" style="max-width:30vw;">
                <v-img
                  :src='url_base+"/api/infografis/"+element.source'
                  class="white--text align-end"
                  gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                >
                  <v-card-title v-text="element.name"></v-card-title>
                  <v-icon class="ma-2" color='white' @click='lihatInfografis(element)'>
                    mdi-eye
                  </v-icon>
                  <v-icon class="ma-2" color='white' @click='konfirmDelete(element)'>
                    mdi-delete
                  </v-icon>
                </v-img>
              </v-card>
            </draggable>
          </div>

          <div class="sibling-2">
            <h3>Daftar Infografis Tersedia
              <v-btn
                class="mx-2 my-2"
                fab
                dark
                small
                @click="openDialogCreate()"
                color="primary">
                <v-icon dark>
                  mdi-plus
                </v-icon>
              </v-btn>
            </h3>
            <draggable class="list-group" :list="infografis_list" group="people" @change="log">
              <v-card class="ma-1" v-for="(element, index) in infografis_list" :key="index" style="max-width:15vw;">
                <v-img
                  :src='url_base+"/api/infografis/"+element.source'
                  class="white--text align-end"
                  gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
                >
                  <v-card-title v-text="element.name"></v-card-title>
                  <v-icon class="ma-2" color='white' @click='lihatInfografis(element)'>
                    mdi-eye
                  </v-icon>
                  <v-icon class="ma-2" color='white' @click='openEditDialog(element)'>
                    mdi-cog
                  </v-icon>
                  <v-icon class="ma-2" color='white' @click='konfirmDelete(element)'>
                    mdi-delete
                  </v-icon>
                </v-img>
              </v-card>
            </draggable>
          </div>
        </div>
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
  import draggable from 'vuedraggable'
    export default {
      props: {
        source: String,
      },
      name: 'Infografis',
      components: {
        draggable,
      },
      data () {
        return {
          token: this.$store.getters.token,
          url_base: this.$store.getters.url_base,
          overlay:false,
          teksSnackbar:'',
          warnaSnackbar:"blue",
          snackbar:false,
          multiLine:true,
          currentUser:{},
          permissions:[],
          infografis_list:[],
          infografis_selected:[],
          dialogCreate:false,
          dialogView:false,
          dialogEdit:false,
          infografis:{},
          infografis_edit:{},
          errors:'',
          viewGambarLink:'',
          viewGambarNama:'',
          dialogKonfirmasiHapus:false,
          tags_select: [],
          tags_items: [],
          tags_search: "",
          tags_select_edit: [],
          tags_items_edit: [],
          tags_search_edit: "",
          tampilan_web_items:[
            {teks: 'Ya', value: 'true'},
            {teks: 'Tidak', value: 'false'},
          ],
        }
      },
      methods: {
        openDialogCreate(){
          this.clearForm();
          this.dialogCreate = true;
        },
        async init(){
          var data = []
          this.overlay = true;
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/user-permission')
          .then((response) => {
            //Then injecting the result to datatable parameters.

            this.overlay = true
            // this.locations = response.data
            // this.name = response.data.user.name;
            // this.username = response.data.user.username;
            // this.email = response.data.user.email;
            // this.form = response.data.user
            this.permissions = response.data.permissions
            if(!(response.data.permissions).includes('articles.create')){
              this.$router.push('/')
            }
          }).catch(errors => {
            // this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data.message)
            // // console.log(errors.response.data)
            // this.warnaSnackbar= "red"
            // this.snackbar = true
            console.log(errors)
          }).finally(() => {
            this.overlay = false
          });
            this.overlay = false
        },
        async initData(){
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/show-infografis')
            .then((response) => {
              if(response.data.status == true){
                // console.log(response.data);
                this.infografis_list = response.data.infografis_non_selected
                this.infografis_selected = response.data.infografis_selected
              }else{
                this.teksSnackbar = "Terjadi Kesalahan : "+JSON.stringify(response.data.message)
                this.warnaSnackbar= "red"
                this.snackbar = true
              }
              
            }).catch(errors => {
              this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors)
              this.warnaSnackbar= "red"
              this.snackbar = true
            }).finally(() => {
              this.overlay = false
            }); 
        },
        clearForm(){
          this.infografis = [{}];
        },
        konfirmDelete(item){
          // console.log('good')
          this.infografis = item
          this.dialogKonfirmasiHapus = true
        },
        async deleteItem(){
          // start of upload
          this.overlay = true
          const config = {
              headers: {
                  'Content-Type': 'multipart/form-data',
              }
          }

          var formData = new FormData();
          console.log(this.infografis)
          // additional data
          formData.append('id_entri', this.infografis.id_entri);
          formData.append('name', this.infografis.name);
          formData.append('description', this.infografis.description);
          formData.append('source', this.infografis.source);
          await axios.post("/api/delete-infografis", formData, config).then(response => {
            console.log(response.data)
            if(response.data.status == false){
              this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
              this.warnaSnackbar= "red",
              this.snackbar = true
            }else{
              this.teksSnackbar= "Berhasil menghapus infografis",
              this.warnaSnackbar= "green",
              this.snackbar = true
            }
          }).catch(errors => {
            // console.log(errors.response.message)
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }).finally(async() => {
            this.dialogKonfirmasiHapus = false
            await this.initData();
            this.overlay = false
          });
          this.clear_infografis()
          this.overlay = false
        },
        lihatInfografis(item){
          this.dialogView = true
          this.viewGambarNama = item.name
          this.viewGambarLink = this.url_base+'/api/infografis/'+item.source
        },
        async submitItem(){
          // console.log('submit')
           // start of upload
          this.overlay = true
          const config = {
              headers: {
                  'Content-Type': 'multipart/form-data',
              }
          }

          var formData = new FormData();

          // files
          var $teks = "";
          if(this.infografis.source != null){
            // for (let file of this.news.gambar_pembuka_artikel) {
              var file = this.infografis.source
              formData.append("source", file, file.name);
              $teks = $teks + ' '+file.name;
            // }
          }
          
          // additional data
          formData.append('name', this.infografis.name);
          formData.append('description', this.infografis.description);
          formData.append('tautan_infografis', this.infografis.tautan_infografis);
          formData.append('tags_infografis', JSON.stringify(this.tags_select));
          formData.append('tampilan_web', this.infografis.tampilan_web);
          await axios.post("/api/insert-infografis", formData, config).then(response => {
            // console.log(response.data)
            if(response.data.status == false){
              this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
              this.warnaSnackbar= "red",
              this.snackbar = true
            }else{
              this.teksSnackbar= "Berhasil menambahkan infografis ",
              this.warnaSnackbar= "green",
              this.snackbar = true
            }
          }).catch(errors => {
            console.log(errors.response.data)
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data.errors),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }).finally(async () => {
            this.dialogCreate = false
            await this.initData();
            this.overlay = false
          });
          this.overlay = false
          this.clear_infografis()
        },
        updateTags() {
          this.$nextTick(() => {
            this.tags_select.push(...this.tags_search.split(","));
            this.infografis.tags_infografis = this.tags_select;
            this.$nextTick(() => {
              this.tags_search = "";
            });
          });
        },
        updateTagsEdit() {
          this.$nextTick(() => {
            this.tags_select_edit.push(...this.tags_search_edit.split(","));
            this.infografis_edit.tags_infografis = this.tags_select_edit;
            this.$nextTick(() => {
              this.tags_search_edit = "";
            });
          });
        },
        async confirmInfografis(){
          console.log(this.infografis_selected);
          // start of upload
          this.overlay = true
          const config = {
              headers: {
                  'Content-Type': 'multipart/form-data',
              }
          }

          var formData = new FormData();

          // additional data
          formData.append('selected', JSON.stringify(this.infografis_selected));
          await axios.post("/api/update-tampilan-infografis", formData, config).then(response => {
            // console.log(response.data)
            if(response.data.status == false){
              this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
              this.warnaSnackbar= "red",
              this.snackbar = true
            }else{
              this.infografis_list = response.data.infografis_non_selected
              this.infografis_selected = response.data.infografis_selected
              this.teksSnackbar= "Berhasil mengupdate infografis di halaman muka",
              this.warnaSnackbar= "green",
              this.snackbar = true

            }
          }).catch(errors => {
            console.log(errors.response.data)
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors.response.data.errors),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }).finally(async () => {
            await this.initData();
            this.overlay = false
          });
          this.clear_infografis()
          this.overlay = false
        },
        clear_infografis(){
          this.infografis.name = null
          this.infografis.description = null
          this.infografis.source = null
        },
        openEditDialog(item){
          this.infografis_edit = item
          this.tags_select_edit = item.tags_infografis != null ? (item.tags_infografis).split(',') : []
          this.infografis.source = null
          this.dialogEdit = true
        },
        async submitEdit(){
          this.overlay = true
          const config = {
              headers: {
                  'Content-Type': 'multipart/form-data',
              }
          }

          var formData = new FormData();

          // files
          var $teks = "";
          if(this.infografis.source != null){
            // for (let file of this.news.gambar_pembuka_artikel) {
              var file = this.infografis.source_new
              formData.append("source_new", file, file.name);
              $teks = $teks + ' '+file.name;
            // }
          }
          
          // additional data
          formData.append('name', this.infografis_edit.name);
          formData.append('id_entri', this.infografis_edit.id_entri);
          formData.append('description', this.infografis_edit.description);
          formData.append('tautan_infografis', this.infografis_edit.tautan_infografis);
          formData.append('tags_infografis', JSON.stringify(this.tags_select_edit));
          formData.append('tampilan_web', this.infografis_edit.tampilan_web);
          formData.append('source_old', this.infografis_edit.source);
          await axios.post("/api/update-infografis", formData, config).then(response => {
            // console.log(response.data)
            if(response.data.status == false){
              this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(response.data.message),
              this.warnaSnackbar= "red",
              this.snackbar = true
            }else{
              this.teksSnackbar= "Berhasil menyunting infografis ",
              this.warnaSnackbar= "green",
              this.snackbar = true
            }
          }).catch(errors => {
            console.log(errors)
            this.teksSnackbar= "Terjadi Kesalahan : "+JSON.stringify(errors),
            this.warnaSnackbar= "red",
            this.snackbar = true
          }).finally(async() => {
            this.dialogCreate = false
            await this.initData();
            this.overlay = false
          });
          this.overlay = false
          // this.clear_infografis()
        },
        log: function(evt) {
          console.log(evt);
          // this.infografis_selected = evt.moved.element
        },
      },
      computed:{
        // password_confirmation(){
        //   return (this.password.new == this.password.check) && (this.password.new).length >= 8 && (this.password.old != this.password.new)
        // },
        disabledUploadInfografis(){
          return ((this.infografis.name == null) || (this.infografis.description == null) || (this.infografis.source == null) || (this.infografis.tampilan_web == null))
        },
        tagnames_computed(){
          var tags =  (this.infografis.tags_infografis == null ) ? [] : (this.infografis.tags_infografis).split(',');
          return tags;
        },
        tagnames_computed_edit(){
          var tags =  (this.infografis_edit.tags_infografis == null ) ? [] : (this.infografis_edit.tags_infografis).split(',');
          return tags;
        },
        imagePreview(){
        if (!this.infografis.source) return null;
          return URL.createObjectURL(this.infografis.source);
      },
      },
      async mounted () {
        await this.initData();
      },
      async created () {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        await this.init();
      }
    }
</script>
<style scoped>
.flex {
  display: flex;
  width: 45vw;
  height:82vh;
  position: relative;
}

.flex>div {
  flex: 0 0 50%;
  border: 2px solid orange;
  box-sizing: border-box;
  padding:5px;
  overflow: auto;
}

.sibling-2 {
  position: absolute;
  left: 50%;
  top: 0;
  bottom: 0;
  right: 0;
  overflow: auto;
}
</style>
<style scoped>
>>>.tag-input span.chip {
  background-color: rgb(116, 43, 106);
  color: #fff;
  font-size: 1em;
}

>>>.tag-input span.v-chip {
  background-color: rgb(116, 43, 106);
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
