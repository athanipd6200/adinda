 <template>
    <v-app id="infografis">
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
          style="z-index:6000"
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

       <v-main >
          <!-- <v-container> -->
            <v-container v-if="infografis_length"  style="content-align:center;text-align:center;min-height:80vh">
              <h1 style="margin-top:30vh;">{{ overlay == false ? 'TIDAK ADA INFOGRAFIS' : 'LOADING INFOGRAFIS . . .'}}</h1>
            </v-container>
            <div id="news" style="margin-top:10px;min-height:60vh;margin-right:10px;margin-left:10px;">
              <section class="news">
                <article v-for="datum in this.infografis" :key="'infografis_gambar_'+datum.id_entri" data-aos="fade-up" data-aos-delay="500" style="border-radius: 10px;">
                  <v-img class="img" :src="url_base+'/api/infografis/'+datum.source" alt="">
                    <!-- <p class="articles__article-card__top__article-cat letter-spacing" style="text-transform:uppercase;">{{datum.description}}</p> -->
                  </v-img>
                  <div class="text">
                    <v-chip
                        v-for="(filename, index) in (datum.tags_infografis != null ? datum.tags_infografis.split(',') : [])"
                        :key="'tag_'+datum.id_entri+'_'+index"
                        color="secondary darken--2"
                        small
                        style="margin-right:5px;text-transform:lowercase;"
                      >
                      <span class="pr-2">
                        {{ filename }}
                      </span>
                    </v-chip>
                    <h1 class="mb-0" style="font-weight:600;">{{ datum.name}}</h1>
                    <p style="font-size: 14px">
                      {{ datum.description }}
                    </p>
                    <v-icon class="" color='grey' @click='lihatInfografis(datum)'>
                      mdi-eye
                    </v-icon>
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
          infografis:[],
          dialogView: false,
          viewGambarLink: '',
          viewGambarNama: '',
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
        }
      },
      computed:{
        infografis_length(){
          return this.infografis.length == null || this.infografis.length == 0
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
          await axios.get('/api/pagination-infografis?page='+this.pagination.current).then((response) => {
            data_temps = response.data
            // console.log(data_temps)
            refs.infografis = response.data.infografis.data
            refs.pagination.current = response.data.infografis.current_page;
            refs.pagination.total = response.data.infografis.last_page;
            
          }).catch(errors => {
              console.log('Error')
          }).finally(() => {
              
          });
          
          // this.overlay_artikel = false;
        },
        lihatInfografis(item){
          this.dialogView = true
          this.viewGambarNama = item.name
          this.viewGambarLink = this.url_base+'/api/infografis/'+item.source
        },
        onPageChange() {
            this.initData();
        },
        async changeArtikel(id_artikel){
          var data_temps = []
          this.overlay= true;
          var refs = this
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/ambil-artikel/'+id_artikel+'/3').then((response) => {
            data_temps = response.data
            // console.log(data_temps)
            refs.artikel = response.data.artikel
            refs.infografis = response.data.infografis
            
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
      await AOS.init();
      await this.getUser();
      this.overlay = false;

    },
    async created(){
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      console.log(this.jenis_artikel != null ? this.jenis_artikel : "tidak ada jenis artikel");
      await this.initData();
      
    },
  }
</script>
<style scoped>
  #infografis{
    background: url('/other/moroccan.png') !important;
    background-size: cover;
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