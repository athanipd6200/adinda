 <template>
    <v-app id="artikel">
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
              label="Kolom pencarian artikel"
              append-icon="mdi-file-search"
              v-model="searchText"
              @keyup.enter="updatingPencarian"
              @click:append="updatingPencarian"
            ></v-text-field>
          </div>
          <!-- <v-container> -->
            <v-container v-if="artikels_length"  style="content-align:center;text-align:center;min-height:80vh">
              <div v-if="overlay">
                <img style="margin-top:30vh;" id="walk" src="https://i.imgur.com/ArYIIjU.gif"/>
                <h2>LOADING . . .</h2>
              </div>
              <div v-else>
                <h2 style="margin-top:10vh;margin-bottom:10px;">ARTIKEL TERKAIT BELUM ADA</h2>
                <!-- <img style="width:300px;" id="walk" :src="link_gif" /> -->
                <video width="320" autoplay  loop muted>
                  <source :src="link_gif" type="video/mp4">
                  <source :src="link_gif" type="video/ogg">
                  <source :src="link_gif" type="video/webm">
                  Your browser does not support the video tag.
                </video>
              </div>
            </v-container>
            
            <div id="news" style="margin-top:10px;min-height:60vh;margin-right:10px;margin-left:10px;">
              <section class="news">
                <article v-for="datum in this.artikels" :key="datum.id_artikel" data-aos="fade-up" data-aos-delay="500" style="border-radius: 10px;">
                  <v-img class="img" :src="url_base+'/api/gambar_pembuka_artikel/'+datum.gambar_pembuka_artikel" alt="">
                    <p class="articles__article-card__top__article-cat letter-spacing" style="text-transform:uppercase;">{{datum.jenis_artikel}}</p>
                  </v-img>
                  
                  <div class="text">
                    <v-chip
                        v-for="(filename, index) in (datum.tags_artike != null ? datum.tags_artikel.split(',') : [])"
                        :key="'tag_'+datum.id_artikel+'_'+index"
                        color="secondary darken--2"
                        small
                        style="margin-right:5px;text-transform:lowercase;"
                      >
                      <span class="pr-2">
                        {{ filename }}
                      </span>
                    </v-chip>
                    <div style="font-size: 12px">
                      Tanggal Artikel: {{ datum.tanggal_artikel }}
                    </div>
                    <h1 class="mb-0" style="font-weight:600;">{{ datum.judul_artikel}}</h1>
                    <div style="padding-top: 5px;font-size: 12px">
                      Oleh: {{ datum.penulis_artikel }}
                    </div>
                    <p style="font-size: 14px">
                      {{ datum.teks_pembuka_artikel }}
                    </p>
                    <div style="padding-top: 5px;text-align:center;content-align:center;">
                      <v-btn
                        color="secondary" 
                        :to="'/artikel/'+datum.id_artikel" text>
                        Baca lebih lanjut 
                        <v-icon
                          right
                          dark
                        >
                          mdi-arrow-right-circle
                        </v-icon>
                      </v-btn>
                    </div>
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
          overlay_artikel:false,
          data_asets: [],
          center: [-2.207680, 113.916357],
          zoom: 15,
          map:null,
          artikels:[],
          data_diagram_pertama: [],
          data_diagram_kedua: [],
          data_diagram_ketiga: [],
          jenis_artikel: this.$route.params.jenis_artikel,
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
          link_gif: "https://i.imgur.com/sTv6xCb.gif",
        }
      },
      computed:{
        artikels_length(){
          return this.artikels.length == 0 || this.artikels.length == null
        },
      },
      methods: {
        submit () {
        },
        clear () {          
        },
        async initData(){
          var data_temps = []
          this.overlay = true;
          var refs = this
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/artikels?page='+this.pagination.current+'&jenis_artikel='+this.jenis_artikel).then((response) => {
            data_temps = response.data
            // console.log(data_temps)
            refs.artikels = response.data.data
            refs.pagination.current = response.data.current_page;
            refs.pagination.total = response.data.last_page;
          }).catch(errors => {
              console.log('Error')
          }).finally(() => {
          });
          // this.overlay_artikel = false;
        },
        onPageChange() {
            this.initData();
        },
        async changeLinkGIF(){
          var xmlHttp = new XMLHttpRequest();
          await xmlHttp.open( "GET", "https://api.giphy.com/v1/gifs/random?api_key=0UTRbFtkMxAplrohufYco5IY74U8hOes&tag=statistic&rating=pg-13&limit=1&lang=en", false ); // false for synchronous request
          await xmlHttp.send( null );
          var datum =  JSON.parse(xmlHttp.responseText);
          // console.log(datum)
          if(datum.data.length != 0){
            // this.link_gif = datum.data.images.preview_gif.url
            this.link_gif = datum.data.images.downsized_small.mp4
          }
          // console.log(this.link_gif)
        },
        async updatingPencarian(){
          if(this.searchText == null || this.searchText == ''){
            this.overlay = true
            this.loading_pencarian = false
            var refs = this
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            await axios.get('/api/artikels?page='+this.pagination.current).then((response) => {
              // var data_temps = response.data
              // console.log(data_temps)
              refs.artikels = response.data.data
              refs.pagination.current = response.data.current_page;
              refs.pagination.total = response.data.last_page;
              this.status_artikel_kosong = false
            }).catch(errors => {
                console.log(errors)
            }).finally(() => {
                console.log('Berhasil')
            });
            this.overlay = false
          }else{
            this.overlay = true
            this.artikels = []
            this.loading_pencarian = true
            var refs = this
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            await axios.get('/api/artikels?pencarian='+this.searchText.toString()).then((response) => {
              // var data_temps = response.data
              // console.log(data_temps)
              refs.artikels = response.data
              if(refs.artikels.length == 0){
                this.changeLinkGIF()
                this.status_artikel_kosong = true
              }else{
                this.status_artikel_kosong = false
              }
            }).catch(errors => {
              console.log(errors)
            }).finally(() => {
              this.loading_pencarian = false
              console.log('Berhasil')
            });
            this.overlay = false
          }
        },
        async changeArtikel(id_artikel){
          var data_temps = []
          this.overlay = true;
          var refs = this
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/ambil-artikel/'+iy+'/3').then((response) => {
            data_temps = response.data
            // console.log(data_temps)
            refs.artikel = response.data.artikel
            refs.artikels = response.data.artikels
          }).catch(errors => {
              console.log('Error')
          }).finally(() => {
          });
          this.overlay = false;
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
      AOS.init();
      await this.getUser();
      await this.initData();
      this.overlay = false;

    },
    async created(){
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      console.log(this.jenis_artikel != null ? this.jenis_artikel : "tidak ada jenis artikel");
    },
  }
</script>
<style scoped>
  #artikel{
    background-color: rgba(216, 177, 47, 0.4);
  }

  .articles__article-card__top__article-cat{
    background: white;
    padding: 5px 10px;
    border-radius: 6px;
    color: #72156a;
    position: absolute;
    top: 5px;
    right: 5px;  
    font-family: 'Libre Franklin', sans-serif;   
    font-weight: 500;    
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
  .news article .img{width: 500px;height:auto;-o-object-fit:cover;object-fit:cover;-webkit-box-reflect:below 0 linear-gradient(0deg,rgba(0,0,0,.5),transparent 35%)}
  .news article .text{padding:20px;}
  .news article .text h1{font-weight:500}
  .news article .text p{margin:0 0 1em}
  .news article .text p:last-child{margin:0}@media (max-width:600px){.news{padding:0}
  .news article{display:block;max-width:100%;margin:0 0 20px}.news article:last-child{margin:0}}
  .section-dropdown{width:300px;display:block;margin:50px auto;text-align:center}
  .select,select{height:40px;width:240px}
  .select{border:1px solid #cacaca;overflow:hidden;position:relative;display:block}
  #loading,.select:after{height:100%;text-align:center}
  select{padding:5px;border:0;font-size:16px;-webkit-appearance:none;-moz-appearance:none;appearance:none}
  .select:after{content:"\f0dc";font-family:FontAwesome;color:#000;padding:12px 8px;position:absolute;right:0;top:0;background:#e3f2fd;z-index:1;width:10%;pointer-events:none}#loading{background-color:#fff;width:100%;position:fixed;z-index:9999}
  .select-header{text-align:left;padding-left:54px}
</style>