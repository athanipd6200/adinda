 <template>
    <v-app id="inovation">
      <v-overlay :value="overlay" style="z-index:7100;">
        <v-progress-circular
          indeterminate
          size="64"
        ></v-progress-circular>
        <p class="text-center">
          Loading . . .
        </p>
      </v-overlay> 
        <v-navigation-drawer
            v-model="drawer"
            app
            clipped
            style="z-index:6000"
            v-if="this.isLoggedIn"
        >
            <v-list-item two-line>
              <v-list-item-avatar>
                <v-avatar color="primary">
                  <v-icon dark>
                    mdi-account-circle
                  </v-icon>
                </v-avatar>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title>{{ currentUser.name }}</v-list-item-title>
                <v-list-item-subtitle>{{ currentUser.email }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-divider></v-divider>
            <v-list-item link to="/profile">
                <v-list-item-action>
                    <v-icon>mdi-view-dashboard</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Halaman Pengguna</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link to="/">
                <v-list-item-action>
                    <v-icon>mdi-earth</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Halaman Muka Publik</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link to="/list-inovation">
                <v-list-item-action>
                    <v-icon>mdi-newspaper-variant-multiple-outline</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Daftar Inovation</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link to="/list-artikel">
                <v-list-item-action>
                    <v-icon>mdi-newspaper-variant-multiple-outline</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Halaman Artikel</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-navigation-drawer>

        <v-navigation-drawer
            v-model="drawer"
            app
            clipped
            style="z-index:60000"
            v-else
        >
            <v-list dense>
                  <v-list-item link to="/">
                      <v-list-item-action>
                          <v-icon>mdi-earth</v-icon>
                      </v-list-item-action>
                      <v-list-item-content>
                          <v-list-item-title>Halaman Muka Publik</v-list-item-title>
                      </v-list-item-content>
                  </v-list-item>
                  <v-list-item link to="/list-inovation">
                    <v-list-item-action>
                        <v-icon>mdi-newspaper-variant-multiple-outline</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Daftar Inovasi</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="/list-artikel">
                    <v-list-item-action>
                        <v-icon>mdi-newspaper-variant-multiple-outline</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Halaman Artikel</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="/login">
                    <v-list-item-action>
                        <v-icon>mdi-view-dashboard</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Login</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar
            app
            clipped-left
            :src="url_base+'/api/ikon_inovation/'+inovation.ikon_inovation"
            color="secondary"
            style="z-index:2002;"
        >
          <template v-slot:img="{ props }">
            <v-img
              v-bind="props"
              gradient="to top right, rgba(19,84,122,.5), rgba(128,208,199,.8)"
            ></v-img>
          </template>
            <!-- <v-spacer></v-spacer> -->

          <v-slide-group
            multiple
            show-arrows>
            <v-slide-item>
              <v-img @click.stop="drawer = !drawer" max-height="40px" max-width="40px" :src="url_base+'/api/other/logo.png'" />
            </v-slide-item>

            <v-slide-item>
              <h3 style="cursor: pointer;" @click="$router.push('/')" class="white--text mx-4 my-1">ADINDA - Aplikasi Dokumentasi Inovasi dan Artikel</h3>
            </v-slide-item>
            <v-slide-item>
              <v-btn text color="white" dark to="/"> Home </v-btn>
            </v-slide-item>
            <v-slide-item>
              <v-btn text color="white" dark to="/list-inovation"> Daftar Inovasi </v-btn>
            </v-slide-item>
            <v-slide-item>
              <v-btn text color="white" dark to="/list-artikel"> Halaman Artikel </v-btn>
            </v-slide-item>
            <v-slide-item>
              <v-btn text color="white" dark to="/list-infografis"> Infografis </v-btn>
            </v-slide-item>
          </v-slide-group>

        </v-app-bar>
       <v-main >
          <v-container v-if="status_inovation == true">
            <!-- <v-img  style="max-width:80vw" :src="getLinkIkon" alt=""/> -->
            <v-img class="mx-auto"
              style="max-width: 300px;"
              :src="imagePreview(inovation.ikon_inovation)"
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
            <h1 style="text-align:center;margin:10px;" >
              {{ inovation.nama_inovation }}
            </h1>
            <p style="text-align:center;">
              <v-chip
                  v-for="(tag, index) in tags_computed"
                  :key='"tags_"+index'
                  color="primary darken--2"
                  medium
                  style="margin:5px;"
                >
                <span class="pr-2">
                  {{ tag }}
                </span>
              </v-chip>
            </p>
            <p style="text-align:center;">
              <b>Tanggal Update : </b> {{ new Date(inovation.updated_at).toUTCString() }}
            </p>
            <p style="text-align:center;">
              <b>Dekripsi : </b> 
            </p>
            <p style="text-align:center;left:10vw;right:10vw;">
              {{ inovation.deskripsi_inovation }}  
            </p>
            <h5 style="text-align:center;margin-top:10px;">
              Kontak Hubung : {{ inovation.kontak_hubung_inovation }}
            </h5>
            <h5 style="text-align:center;margin-top:10px;">
              Satuan Kerja Asal : {{ inovation.satker_asal_inovation }}
            </h5>
            <p style="text-align:center;margin-top:10px;">
              <b>Tautan Materi : </b>{{ inovation.tautan_materi_inovation }}
            </p>
            <p style="text-align:center;margin-top:10px;">
              <b>Tautan Kode : </b>{{ inovation.tautan_kode_inovation }}
            </p>

            <h3><b>Isi Konten</b></h3>
              <ckeditor :disabled="editorDisabled" :editor="editor_read" v-model="inovation.konten_inovation" :config="editorConfig_read"></ckeditor>

            <h3><b>Gambar</b></h3>
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

            <h2 style="text-align:center;margin-top:100px;">
              KONTEN LAIN
            </h2>

            <div id="news" style="margin-top:10px;">
              <section class="news">
                <article v-for="datum in this.inovations" :key="datum.id_entri">
                  <img :src="url_base+'/api/ikon_inovation/'+datum.ikon_inovation" alt=""/>
                  <div class="text">
                    <h1>{{ datum.nama_inovation}}</h1>

                    <p style="font-size: 14px">
                      {{ datum.deskripsi_inovation }}
                    </p>
                    <div style="padding-top: 5px;font-size: 12px">
                      Kontak Hubung : {{ datum.kontak_hubung_inovation }}
                    </div>
                    <div style="font-size: 12px">
                      Tanggal Update : {{ getTanggal(datum.updated_at) }}
                    </div>
                    <v-btn
                    color="tertiary" 
                    @click='changeArtikel(datum.id_inovation)'
                    style="padding-top: 5px;">
                      Baca lebih lanjut 
                      <v-icon
                        right
                        dark
                      >
                        mdi-dots-vertical
                      </v-icon>
                    </v-btn>
                  </div>
                </article>
              </section>
              <v-btn
                color="tertiary darken--3"
                large
                class="ma-2 white--text text-center"
                :style="{left: '50%', transform:'translateX(-50%)'}"
                to="/list-inovation"
              >
                Baca inovation lainnya
                <v-icon
                  right
                  dark
                >
                  mdi-page-next
                </v-icon>
              </v-btn>
            </div>
          </v-container>
          <v-container v-else>
            <h2>Data tidak ada, terima kasih mengunjungi kami! Silahkan ke Halaman Utama</h2>
          </v-container>
    </v-main>
    <v-footer
      dark
      padless>
      <v-card
        flat
        tile
        class="secondary darken-3 white--text text-center"
        style="min-width:100vw;"
      >

        <v-card-text class="white--text">
          2022 - {{ new Date().getFullYear() }} | <strong>Athan</strong>
        </v-card-text>
      </v-card>
    </v-footer>
  </v-app>
</template>

<script>
  import 'leaflet/dist/leaflet.css'
  import L from 'leaflet'

  import 'leaflet-draw/dist/leaflet.draw.css'
  import 'leaflet-draw'

  import axios from 'axios'

  import { Chart, registerables } from 'chart.js';
  Chart.register(...registerables);

  import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

  export default {
    props: {
      source: String,
    },
    data () {
        return {
          currentUser: {},
          edit_phase: false,
          drawer: false,
          locations: null,
          overlay_inovation:false,
          data_asets: [],
          center: [-2.207680, 113.916357],
          zoom: 15,
          map:null,
          inovations:[],
          inovation:{
            ikon_inovation: '',
            gambar_inovation: '',
          },
          errors: "",
          list_sesi_view: [],
          editor_read: ClassicEditor,
          editorDisabled:true,
          editorConfig_read: {
            toolbar: [],
            isReadOnly:true,
          },
          lokasi_tersedia:true,
          filenames:[],
          tags:[],
          chipKey:0,
          tagKey:0,
          data_diagram_pertama: [],
          data_diagram_kedua: [],
          data_diagram_ketiga: [],
          icons: [
            {icon: 'mdi-facebook', link:'https://www.facebook.com/bpskalteng'},
            {icon: 'mdi-twitter', link:'https://twitter.com/bpskalteng'},
            {icon: 'mdi-youtube', link:'https://s.bps.go.id/youtubebpskalteng'},
            {icon: 'mdi-instagram', link:'https://www.instagram.com/bpskalteng'},
          ],
          isLoggedIn: this.$store.getters.isLogged,
          token: this.$store.getters.token,
          url_base: this.$store.getters.url_base,
          id_inovation: this.$route.params.id_inovation,
          overlay: true,
          status_inovation:true,
        }
      },
      watch: {
      },
      computed: {
        filenames_computed(){
          return this.filenames;
        },
        tags_computed(){
          return this.tags;
        },
        getLinkIkon(){
          return this.url_base + '/api/ikon_inovasi/'+this.inovation.ikon_inovation
        },
      },
      methods: {
        submit () {
        },
        clear () {          
        },
        getTanggal(val){
          // console.log(val)
          var tanggal = new Date(val)
          return tanggal.toUTCString()
        },
        gambar_inovation_edit_arr(){
          return this.inovation.gambar_inovation.split(',')
        },
        async getPreviewFile(file_name, jenis_file){
          this.overlay = true
          var link = this.url_base + /api/ + jenis_file +'/'+ file_name
          var tipe_file = file_name.split('.').slice(-1)[0]
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios(link, {
              method: 'GET',
              responseType: 'blob' //Force to receive data in a Blob Format
          })
          .then(async response => {
          //Create a Blob from the PDF Stream
            var file;
            if(tipe_file == 'pdf'){
              file = new Blob([response.data], {type: 'application/pdf'});
              //Build a URL from the file
              const fileURL = URL.createObjectURL(file);
              //Open the URL on new Window
              window.open(fileURL);
            }else if(tipe_file == 'jpg' || tipe_file == 'jpeg' || tipe_file == 'png'){
              file = new Blob([response.data], {type: 'image/jpeg'});
              //Build a URL from the file
              const fileURL = URL.createObjectURL(file);
              //Open the URL on new Window
              window.open(fileURL);
            }else{
              console.log('file bukan pdf atau image');
              file = new Blob([response.data]);
              this.downloadBlob(file, file_name);
            }
          })
          .catch(error => {
              console.log(error);
          });
          this.overlay = false
        },
        async downloadBlob(blob, name) {
          // Convert your blob into a Blob URL (a special url that points to an object in the browser's memory)
          const blobUrl = URL.createObjectURL(blob);

          // Create a link element
          const link = document.createElement("a");

          // Set link's href to point to the Blob URL
          link.href = blobUrl;
          link.download = name;

          // Append link to the body
          await document.body.appendChild(link);

          // Dispatch click event on the link
          // This is necessary as link.click() does not work on the latest firefox
          await link.dispatchEvent(
            new MouseEvent('click', { 
              bubbles: true, 
              cancelable: true, 
              view: window 
            })
          );

          // Remove link from body
          document.body.removeChild(link);
        },
        imagePreview(val){
          if (!val) return null;
            var link = this.url_base+'/api/ikon_inovation/'+val.toString()
            // console.log(val)
            // console.log(link)
            // return URL.createObjectURL(link);
            return link;
        },
        async initData(){
          var data_temps = []
          this.overlay = true;
          var refs = this
          var data = []
          var map_temp;
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/ambil-inovation/'+this.$route.params.id_inovation+'/3').then((response) => {
            data_temps = response.data
            // console.log(response.data.inovation)
            if(response.data.inovation == null){
              refs.status_inovation = false
            }else{
              refs.lokasi_tersedia = true
              refs.inovation = response.data.inovation
              this.inovation.ikon_inovation = response.data.inovation.ikon_inovation
              refs.inovations = response.data.inovations
              // refs.list_sesi_view = response.data.sesis
              // refs.filenames = response.data.inovation.lampiran_inovation == null ? [] : (response.data.inovation.lampiran_inovation).split(',')
              // refs.tags = response.data.inovation.tags_inovation == null ? [] : (response.data.inovation.tags_inovation).split(',')
              // refs.inovation.teks_isi_inovation = refs.inovation.teks_isi_inovation == null ? 'Tidak ada konten' : refs.inovation.teks_isi_inovation
            }
          }).catch(errors => {
              console.log(errors)
          }).finally(() => {
          });
          // this.overlay_inovation = false;
        },
        async changeArtikel(id_inovation){
          var data_temps = []
          this.overlay = true;
          var refs = this
          var data = []
          var map_temp;
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/ambil-inovation/'+id_inovation+'/3').then((response) => {
            data_temps = response.data
            // console.log(data_temps)
            // refs.lokasi_tersedia = true
            refs.inovation = response.data.inovation
            refs.inovations = response.data.inovations
          }).catch(errors => {
              console.log(errors)
          }).finally(() => {
          });
          this.chipKey += 1
          this.$forceUpdate()
          this.overlay = false;
        },
        async getUser(){
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/user').then(response => {
              if(response.data.status == true){
                console.log('User ada')
                this.currentUser = response.data.user
                this.isLoggedIn = true
              }else{
                console.log('User tidak ada/token tidak berlaku')
                this.isLoggedIn = false
              }
          }).catch(errors => {
              console.log('User kosong')
              // this.logout()
              this.isLoggedIn = false
          });
        },
        logout(){
          // fungsi logout
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          axios.post('/api/logout').then(response => {
              localStorage.removeItem('token');
              this.$router.push('/login');
          }).catch( errors => {
              console.log(errors.response.data);
          })
        },
    },
    async mounted () {
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      await this.getUser();
      await this.initData();
      this.overlay = false;
    },
    created(){
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
    },
  }
</script>
<style scoped>
  >>>.legend {
    padding: 1vh 1vh;
    font: 2vh Arial, Helvetica, sans-serif;
    background: white;
    background: rgba(255, 255, 255, 0.8);
    line-height: 3vh;
    color: #555;
  }
  >>>.legend h4 {
    text-align: center;
    font-size: 2.5vh;
    margin: 0.25vh 1.5vh 1vh;
    color: #777;
  }

  >>>.legend span {
    position: relative;
    bottom: 0.5vh;
  }

  >>>.legend i {
    width: 2.5vh;
    height: 2.5vh;
    float: left;
    margin: 0 1vh 0 0;
    opacity: 0.7;
  }

  >>>.legend i.icon {
    background-size: 2.5vh;
    background-color: rgba(255, 255, 255, 1);
  }

  .select:after{box-sizing:border-box}*{font-family:Roboto,sans-serif}body{margin:0;background:#333;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;text-size-adjust:100%}
  .news{padding:10px;text-align:center;font-size:0}
  .news article{display:inline-block;max-width:400px;margin:10px;background:#eee;text-align:left;vertical-align:top;font-size:1rem;box-shadow:0 0 40px -10px #000;overflow:hidden}
  .news article img{width:100%;height:120px;-o-object-fit:cover;object-fit:cover;-webkit-box-reflect:below 0 linear-gradient(0deg,rgba(0,0,0,.5),transparent 50%)}
  .news article .text{padding:10px;color:#333}.news article 
  .text h1{margin:0 0 .25em;font-weight:500}
  .news article .text p{margin:0 0 1em}.news article .text p:last-child{margin:0}@media (max-width:600px){.news{padding:0}
  .news article{display:block;max-width:100%;margin:0 0 20px}.news article:last-child{margin:0}}
</style>