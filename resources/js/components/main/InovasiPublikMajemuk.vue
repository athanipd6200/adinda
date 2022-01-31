 <template>
    <v-app id="inovation">
      <v-overlay :value="overlay" style="z-index:7100;content-align:center;text-align:center;" >
        <v-progress-circular
          indeterminate
          size="64"
        ></v-progress-circular>
        <p class="text-center">
          {{ loading_text }}
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
            color="primary"
            style="z-index:2002;"
        >
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
         <div id="pencarian" style="margin-top:5px;text-align:center;content-align:center;margin-right:20px;margin-left:20px;">
            <v-text-field
              label="Kolom pencarian inovasi"
              append-icon="mdi-lightbulb-on"
              v-model="searchText"
              @keyup.enter="updatingPencarian"
              @click:append="updatingPencarian"
            ></v-text-field>
          </div>

          <v-container v-if="inovations_length"  style="content-align:center;text-align:center;min-height:80vh">
            <div v-if="overlay">
              <img style="margin-top:30vh;" id="walk" src="https://i.imgur.com/ArYIIjU.gif"/>
              <h2>LOADING . . .</h2>
            </div>
            <div v-else>
              <h2 style="margin-top:10vh;margin-bottom:10px;">INOVASI TERKAIT BELUM ADA</h2>
              <!-- <img style="width:300px;min-height:100px;" id="walk" :src="link_gif" type="video/mp4"/> -->
              <video width="320" autoplay  loop muted>
                <source :src="link_gif" type="video/mp4">
                <source :src="link_gif" type="video/ogg">
                <source :src="link_gif" type="video/webm">
                Your browser does not support the video tag.
              </video>
            </div>
          </v-container>
          <!-- <v-container> -->
            <div id="news" style="margin-top:10px;min-height:60vh;">
              <section class="news">
                <article v-for="datum in this.inovations" :key="datum.id_entri" data-aos="fade-up" data-aos-delay="500" class="mx-2">
                  <img :src="url_base+'/api/ikon_inovation/'+datum.ikon_inovation" alt=""/>
                  <div class="text">
                    <h1>{{ datum.nama_inovation}}</h1>

                    <p style="font-size: 14px">
                      {{ datum.deskripsi_inovation }}
                    </p>
                    <div style="padding-top: 5px;font-size: 12px">
                      Kontak Hubung: {{ datum.kontak_hubung_inovation }}
                    </div>
                    <div style="font-size: 12px">
                      Tanggal Update: {{ new Date(datum.updated_at).toUTCString() }}
                    </div>
                    <v-btn
                    color="tertiary" 
                    :to="'/inovation/'+datum.id_inovation"
                    style="padding-top: 5px;">
                      Cek lebih lanjut
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
            </div>
            <v-spacer></v-spacer>
            <v-pagination
                v-model="pagination.current"
                :length="pagination.total"
                @input="onPageChange"
              ></v-pagination>
            <!-- </v-container> -->
    </v-main>
    <v-footer
      dark
      padless>
      <v-card
        flat
        tile
        class="primary white--text text-center"
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
  import AOS from 'aos'
  import 'aos/dist/aos.css'

  import axios from 'axios'

  // import { Chart, registerables } from 'chart.js';
  // Chart.register(...registerables);

  export default {
    props: {
      source: String,
    },
    data () {
        return {
          currentUser: {},
          drawer: false,
          locations: null,
          overlay_inovation:false,
          data_asets: [],
          center: [-2.207680, 113.916357],
          zoom: 15,
          map:null,
          inovations:[],
          data_diagram_pertama: [],
          data_diagram_kedua: [],
          data_diagram_ketiga: [],
          jenis_inovation: this.$route.params.jenis_inovation,
          icons: [
            {icon: 'mdi-facebook', link:'https://www.facebook.com/bpskalteng'},
            {icon: 'mdi-twitter', link:'https://twitter.com/bpskalteng'},
            {icon: 'mdi-youtube', link:'https://s.bps.go.id/youtubebpskalteng'},
            {icon: 'mdi-instagram', link:'https://www.instagram.com/bpskalteng'},
          ],
          isLoggedIn: this.$store.getters.isLogged,
          token: this.$store.getters.token,
          url_base: this.$store.getters.url_base,
          overlay: true,
          pagination:{
            current:1,
            total:0,
          },
          permissions:[],
          searchText: '',
          status_artikel_kosong: false,
          loading_text: 'Loading . . .',
          link_gif: "https://media0.giphy.com/media/55rPLgQe7h8AlTUSMo/giphy-downsized-small.mp4"
        }
      },
      computed:{
        inovations_length(){
          return this.inovations.length == 0 || this.inovations == null
        },
      },
      methods: {
        submit () {
        },
        clear () {
        },
        getTanggal(val){
          // console.log(val)
          var tanggal = new Date(val.updated_at)
          return tanggal.toUTCString()
        },
        async updatingPencarian(){
          if(this.searchText == null || this.searchText == ''){
            this.loading_text = 'Memulai pencarian inovasi untuk dirimu . . .'
            this.overlay = true
            this.loading_pencarian = false
            var refs = this
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            await axios.get('/api/inovations?page='+this.pagination.current).then((response) => {
              // var data_temps = response.data
              // console.log(data_temps)
              refs.loading_text = 'Berhasil mengambil data . . .'
              refs.inovations = response.data.data
              refs.pagination.current = response.data.current_page;
              refs.pagination.total = response.data.last_page;
              this.status_artikel_kosong = false
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                console.log('Berhasil')
                refs.loading_text = 'Berhasil mengubah data tampilan . . .'
            });
            this.overlay = false
          }else{
            this.loading_text = 'Memulai pencarian inovasi untuk dirimu . . .'
            this.overlay = true
            this.inovations = []
            this.loading_pencarian = true
            var refs = this
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            await axios.get('/api/inovations?pencarian='+this.searchText.toString()).then((response) => {
              // var data_temps = response.data
              // console.log(data_temps)
              refs.loading_text = 'Berhasil mengambil data . . .'
              refs.inovations = response.data
              if(refs.inovations.length == 0){
                this.status_artikel_kosong = true
                this.changeLinkGIF()
              }else{
                this.status_artikel_kosong = false
              }
            }).catch(errors => {
              console.log(errors)
            }).finally(() => {
              this.loading_pencarian = false
              console.log('Berhasil')
              refs.loading_text = 'Berhasil mengubah data tampilan . . .'
            });
            this.overlay = false
          }
        },
        async initData(){
          var data_temps = []
          this.loading_text = 'Memulai mengambil inovasi untuk dirimu . . .'
          this.overlay = true;
          var refs = this
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/inovations?page='+this.pagination.current).then((response) => {
            data_temps = response.data
            // console.log(data_temps)
            refs.inovations = response.data.data
            refs.pagination.current = response.data.current_page;
            refs.pagination.total = response.data.last_page;
            this.loading_text = 'Berhasil mengambil data . . .'
          }).catch(errors => {
              console.log(errors)
          }).finally(() => {
          });
          // this.overlay_inovation = false;
        },
        onPageChange() {
            this.initData();
        },
        changeArtikel(id_inovation){
          var data_temps = []
          this.overlay_inovation = true;
          var refs = this
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          axios.get('/api/ambil-inovation/'+id_inovation+'/3').then((response) => {
            data_temps = response.data
            // console.log(data_temps)
            refs.inovation = response.data.inovation
            refs.inovations = response.data.inovations
            
          }).catch(errors => {
              console.log('Error')
          }).finally(() => {
              
          });
          
          this.overlay_inovation = false;
        },
        async getUser(){
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/user-permission').then(response => {
              this.currentUser = response.data.user;
              this.$store.commit('updateRBAC', response.data.permissions)
          }).catch(errors => {
              console.log(errors);
          }).finally(() => {
              this.permissions = this.$store.getters.rbac
          })
        },
        async changeLinkGIF(){
          var xmlHttp = new XMLHttpRequest();
          await xmlHttp.open( "GET", "https://api.giphy.com/v1/gifs/random?api_key=0UTRbFtkMxAplrohufYco5IY74U8hOes&tag=cute%20animal&rating=pg-13&limit=1&lang=en", false ); // false for synchronous request
          await xmlHttp.send( null );
          var datum =  JSON.parse(xmlHttp.responseText);
          // console.log(datum)
          if(datum.data.length != 0){
            // this.link_gif = datum.data.images.preview_gif.url
            this.link_gif = datum.data.images.downsized_small.mp4
          }
          // console.log(this.link_gif)
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
      await AOS.init();
      await this.initData();
      await this.getUser();
      this.overlay = false;

    },
    async created(){
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      // console.log(this.jenis_inovation != null ? this.jenis_inovation : "tidak ada jenis inovation");
    },
  }
</script>
<style scoped>
  #inovation{
    background: url('/other/sayagata.png') !important;
    background-size: cover;
    background-color: rgba(216, 177, 47, 0.4);
  }
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
  .news article img{width:100%;height:250px;-o-object-fit:cover;object-fit:cover;-webkit-box-reflect:below 0 linear-gradient(0deg,rgba(0,0,0,.5),transparent 50%)}
  .news article .text{padding:20px;color:#333}.news article .text h1{margin:0 0 .5em;font-weight:500}
  .news article .text p{margin:0 0 1em}.news article .text p:last-child{margin:0}@media (max-width:600px){.news{padding:0}
  .news article{display:block;max-width:100%;margin:0 0 20px}.news article:last-child{margin:0}}
  .section-dropdown{width:300px;display:block;margin:50px auto;text-align:center}
  .select,select{height:40px;width:240px}
  .select{border:1px solid #cacaca;overflow:hidden;position:relative;display:block}
  #loading,.select:after{height:100%;text-align:center}
  select{padding:5px;border:0;font-size:16px;-webkit-appearance:none;-moz-appearance:none;appearance:none}
  .select:after{content:"\f0dc";font-family:FontAwesome;color:#000;padding:12px 8px;position:absolute;right:0;top:0;background:#e3f2fd;z-index:1;width:10%;pointer-events:none}#loading{background-color:#fff;width:100%;position:fixed;z-index:9999}
  .select-header{text-align:left;padding-left:54px}
</style>