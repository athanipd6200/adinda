 <template>
    <v-app id="welcome">
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
            style="z-index:60000"
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
                <v-icon>mdi-google-classroom</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                <v-list-item-title>Halaman Inovation</v-list-item-title>
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
          <v-list-item link to="/list-infografis">
            <v-list-item-action>
                <v-icon>mdi-image-outline</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                <v-list-item-title>Halaman Infografis</v-list-item-title>
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
                    <v-icon>mdi-google-classroom</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Halaman Inovation</v-list-item-title>
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
              <v-list-item link to="/list-infografis">
                <v-list-item-action>
                    <v-icon>mdi-image-outline</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Halaman Infografis</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
              <v-divider class="my-4"></v-divider>
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
      <v-main >

        <!-- Hero -->
        <section class="et-hero-tabs">
          <v-sheet style="height:(100vh - 70px);">
            <v-container class="container" data-aos="fade-up" data-aos-duration="2000">

              <div class="intro" style="margin-right:auto;margin-left:auto;text-align:center;content-align:center;">
                  <v-img 
                  style="display:block;margin-left:auto;margin-right:auto;" 
                  @click.stop="drawer = !drawer" min-width="100px" min-height="100px" max-height="40vh" max-width="40vh" :src="url_base+'/api/other/logo.png'" >
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center"
                      >
                        <v-progress-circular
                          indeterminate
                          color="grey darken-3"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
                  <h1 class="header center primary--text mb-2"> ADINDA</h1> <p class="ml-2 mr-2"> Yuk Dokumentasikan segala ide, pikiran, dan pendapatmu! </p>
              </div>
            </v-container>
          </v-sheet>
          <div class="et-hero-tabs-container">
            <a class="et-hero-tab" href="#tab-es6">GALERI</a>
            <a class="et-hero-tab" href="#tab-flexbox">DESKRIPSI</a>
            <a class="et-hero-tab" href="#tab-react">INOVASI</a>
            <a class="et-hero-tab" href="#tab-angular">ARTIKEL</a>
            <a class="et-hero-tab" href="#tab-other">HUBUNGI KAMI</a>
            <span class="et-hero-tab-slider"></span>
          </div>
        </section>

        <!-- Main -->
        <main class="et-main">
          <section class="et-slide" id="tab-es6">      
            <v-carousel height="(100vh - 70px)" style="margin-top:70px;" hide-delimiters cycle show-arrows-on-hover>
              <v-carousel-item
                v-for="(item,i) in images_computed"
                :key="i"
                :src="item.src"
                reverse-transition="fade-transition"
                transition="fade-transition"
                style="height:(100vh - 70px);"
                eager
              >
                <h1 class="mt-5 ml-5 base--text" style="text-shadow: 1px 1px 2px purple;">{{ item.title }}</h1>
                <v-spacer></v-spacer>
                <div class="ml-5 base--text image-description" style="margin-top:65vh">{{ item.description }}</div>
              </v-carousel-item>
            </v-carousel>
          </section>

          <section class="et-slide" id="tab-flexbox" style="margin-top:70px;left:0;right:0;height:(100vh - 70px);">
            <v-container class="container" data-aos="fade-up" data-aos-duration="2000">
              <div class="intro" style="margin-right:auto;margin-left:auto;text-align:center;">
                  <h1 class="header center secondary--text mb-2"> SIDA <p> BPS Kabupaten Sukamara </p></h1>
                  <p>Dalam rangka mendukung Indonesia Maju, BPS Kabupaten Sukamara membuat suatu resolusi dan realisasi dalam mendukung data yang berkualitas, akurat, dan terpercaya.
                      <br>
                      <br> SIDA (Sistem Informasi Daerah) yang bangun ini digunakan sebagai wadah dalam keterbukaan informasi untuk mendukung hal tersebut.</p>
                  <p>SIDA digunakan sebagai wadah untuk inovation statistik sektora, pembelajaran terkait statistik dan informasi umum, atau penjabaran tentang data statistik sektoral kepada publik/masyarakat.
                      <br>
                      <br> <b>Profesional, Integritas, Amanah</b>
                      <br> Melangkah bersama untuk Indonesia Maju</p>
              </div>
            </v-container>
          </section>
          
          <section class="et-slide" id="tab-react">
            <v-container>
            <div id="news" style="margin-top:70px;height:(100vh - 70px);">
                <h1 style="margin-top:20px;margin-bottom:20px;">INOVASI TERSEDIA</h1>

                <inovation class="inovation" style="min-width:75vw;min-height:70vh" data-aos="fade-up">
                    <section class="card-lg__container">
                      <!-- <p class="card__text-sm">BPS Kabupaten Sukamara</p> -->
                      <div v-if="artikels_computed" class="text-center" >
                        <h1 class="card__text-lg">BELUM ADA INOVASI TERSEDIA</h1>
                      </div>
                      <div v-else class="text-center" >
                        <h1 class="card__text-lg">INOVASI</h1>
                        <p class="card__text-md">Inovasi dalam Pengembangan SDM dan Pelayanan Publik</p>
                        <v-btn rounded class="secondary--text" style="background-color:white;" to="/list-inovation">Lihat lainnya</v-btn>
                      </div>
                    </section>


                    <section class="card-sm__container" data-aos="zoom-in" data-aos-duration="1500">
                      <section v-for="item, idx in inovations" :key="'inovation_dua_'+idx" class="card-sm" :style="'background-image:url('+url_base+'/api/ikon_inovation/'+item.ikon_inovation+')'" @click="goTo('/inovation/'+item.id_inovation)">
                        <h2 class="card__text-lg text-left" style="text-shadow: 1px 1px 2px purple;">{{ item.nama_inovation }}</h2>
                        <p class="card__text-sm text-left" style="text-shadow: 1px 1px 2px black;">{{ (item.deskripsi_inovation).length < 60 ? item.deskripsi_inovation : (item.deskripsi_inovation).substr(0, 45) + '...' }}</p>
                        <!-- <v-btn text :to="'/artikel/'+item.id_artikel">Baca artikel lebih lanjut</v-btn> -->
                      </section>

                    </section>
                  </inovation>
                  <p>Gunakan tombol panah kiri atau panah kanan untuk melakukan pergeseran tampilan</p>
              </div>
            </v-container>
          </section>
          <section class="et-slide" id="tab-angular">
            <v-container>
            <div id="news" style="margin-top:70px;height:(100vh - 70px);">
                <h1 style="margin-top:20px;margin-bottom:20px;">ARTIKEL TERSEDIA</h1>

                <materi class="inovation" style="min-width:75vw;min-height:70vh" data-aos="fade-up">
                    <section class="card-lg__container">
                      <!-- <p class="card__text-sm">BPS Kabupaten Sukamara</p> -->
                      <div v-if="artikels_computed" class="text-center" >
                        <h1 class="card__text-lg">ARTIKEL TERSEDIA BELUM ADA</h1>
                      </div>
                      <div v-else class="text-center" >
                        <h1 class="card__text-lg">ARTIKEL</h1>
                        <p class="card__text-md">ARTIKEL Statistik Sektoral untuk mendukung Indonesia Maju</p>
                        <v-btn rounded class="secondary--text" style="background-color:white;" to="/list-artikel">Lihat lainnya</v-btn>
                      </div>
                    </section>


                    <section class="card-sm__container" data-aos="flip-up" data-aos-duration="2000">
                      <section v-for="item, idx in artikels" :key="'artikel_dua_'+idx" class="card-sm" :style="'background-image:url('+url_base+'/api/gambar_pembuka_artikel/'+item.gambar_pembuka_artikel+')'" @click="goTo('/artikel/'+item.id_artikel)">
                        <v-tooltip top>
                          <template v-slot:activator="{ on, attrs }">
                            <h2 v-bind="attrs" v-on="on" class="card__text-lg text-left" style="text-shadow: 1px 1px 2px purple;">{{ item.judul_artikel }}</h2>
                            <p style="text-shadow: 1px 1px 2px black;" class="card__text-sm text-left">{{ (item.teks_pembuka_artikel).length < 65 ? item.teks_pembuka_artikel : (item.teks_pembuka_artikel).substr(0, 45) + '...' }}</p>
                            <!-- <v-btn text :to="'/artikel/'+item.id_artikel">Baca artikel lebih lanjut</v-btn> -->
                          </template>
                          <span>{{ item.judul_artikel }}</span>
                        </v-tooltip>
                      </section>
                    </section>
                    
                  </materi>
                  <p>Gunakan tombol panah kiri atau panah kanan untuk melakukan pergeseran tampilan</p>
              </div>
            </v-container>
          </section>
          <section class="et-slide" id="tab-other">
            <v-card
                flat
                tile
                class="indigo lighten-1 white--text text-center"
                color="secondary"
                style="margin-top:70px;left:0;right:0;height:(100vh - 70px);"
                data-aos='zoom-in'
                data-aos-duration="2000"
              >

              <v-card-text>
                <v-img  class="mt-4" style="max-width:75vw;margin-right:auto;margin-left:auto;display:block;" src="https://scontent.fbdj2-1.fna.fbcdn.net/v/t1.6435-9/51449315_2626110537429546_8684778022630326272_n.jpg?_nc_cat=107&ccb=1-5&_nc_sid=6e5ad9&_nc_ohc=p0SwbckCT8EAX8LDnmc&_nc_ht=scontent.fbdj2-1.fna&oh=a6351fb2a64cd11202990ff6149d4574&oe=6176CC74" alt=""/>
              </v-card-text>

              <v-card-text>
                <h2 class="white--text"><b>Hubungi Kami : </b></h2>
                <v-btn
                  v-for="icon in icons"
                  :key="icon.icon"
                  class="mx-4 my-0 white--text"
                  icon
                  x-large
                >
                <v-icon :href="icon.link" color="white">{{ icon.icon }}</v-icon>
                </v-btn>
              </v-card-text>
              <v-divider></v-divider>

              <v-card-text class="white--text my-0">
                Jl. Tjilik Riwut Km. 8,5, Natai Sedawak, Sukamara Sub-District, Sukamara Regency, Central Kalimantan 74171
              </v-card-text>

              <!-- <v-divider></v-divider> -->

              <!-- <v-card-text class="white--text">
                {{ new Date().getFullYear() }} — <strong>BPS Kabupaten Sukamara</strong>
              </v-card-text> -->
            </v-card>
          </section>
        </main>
    </v-main>
  </v-app>
</template>

<script>
  import axios from 'axios'

  import { Chart, registerables } from 'chart.js';
  Chart.register(...registerables);

  import AOS from 'aos'
  import 'aos/dist/aos.css'
  import $ from 'jquery'
  // import * as d3 from "d3";

  class StickyNavigation {
	
	constructor() {
		this.currentId = null;
		this.currentTab = null;
		this.tabContainerHeight = 70;
		let self = this;
		$('.et-hero-tab').click(function() { 
			self.onTabClick(event, $(this)); 
		});
		$(window).scroll(() => { this.onScroll(); });
		$(window).resize(() => { this.onResize(); });
	}
	
	onTabClick(event, element) {
		event.preventDefault();
		let scrollTop = $(element.attr('href')).offset().top - this.tabContainerHeight + 1;
		$('html, body').animate({ scrollTop: scrollTop }, 600);
	}
	
	onScroll() {
		this.checkTabContainerPosition();
    this.findCurrentTabSelector();
	}
	
	onResize() {
		if(this.currentId) {
			this.setSliderCss();
		}
	}
	
	checkTabContainerPosition() {
		let offset = $('.et-hero-tabs').offset().top + $('.et-hero-tabs').height() - this.tabContainerHeight;
		if($(window).scrollTop() > offset) {
			$('.et-hero-tabs-container').addClass('et-hero-tabs-container--top');
		} 
		else {
			$('.et-hero-tabs-container').removeClass('et-hero-tabs-container--top');
		}
	}
	
	findCurrentTabSelector(element) {
		let newCurrentId;
		let newCurrentTab;
		let self = this;
		$('.et-hero-tab').each(function() {
			let id = $(this).attr('href');
			let offsetTop = $(id).offset().top - self.tabContainerHeight;
			let offsetBottom = $(id).offset().top + $(id).height() - self.tabContainerHeight;
			if($(window).scrollTop() > offsetTop && $(window).scrollTop() < offsetBottom) {
				newCurrentId = id;
				newCurrentTab = $(this);
			}
		});
		if(this.currentId != newCurrentId || this.currentId === null) {
			this.currentId = newCurrentId;
			this.currentTab = newCurrentTab;
			this.setSliderCss();
		}
	}
	
	setSliderCss() {
		let width = 0;
		let left = 0;
		if(this.currentTab) {
			width = this.currentTab.css('width');
			left = this.currentTab.offset().left;
		}
		$('.et-hero-tab-slider').css('width', width);
		$('.et-hero-tab-slider').css('left', left);
	}
	
}

  new StickyNavigation();

  export default {
    props: {
      source: String,
    },
    data () {
        return {
          currentUser: {},
          drawer: false,
          locations: null,
          loading:false,
          loadingGrafik:false,
          data_asets: [],
          center: [-2.207680, 113.916357],
          zoom: 15,
          map:null,
          overlay: true,
          artikels:[],
          inovations:[],
          data_diagram_raw: [],
          data_diagram_pertama: [],
          data_diagram_kedua: [],
          data_diagram_ketiga: [],
          images: [],
          icons: [
            {icon: 'mdi-facebook', link:'https://www.facebook.com/bpskalteng'},
            {icon: 'mdi-twitter', link:'https://twitter.com/bpskalteng'},
            {icon: 'mdi-youtube', link:'https://s.bps.go.id/youtubebpskalteng'},
            {icon: 'mdi-instagram', link:'https://www.instagram.com/bpskalteng'},
          ],
          timeline_icons : [
            {
              color: 'indigo',
              icon: 'mdi-star',
            },
            {
              color: 'indigo',
              icon: 'mdi-book-variant',
            },
            {
              color: 'indigo',
              icon: 'mdi-airballoon',
            },
            {
              color: 'indigo',
              icon: 'mdi-buffer',
            },
          ],
          isLoggedIn: this.$store.getters.isLogged,
          token: this.$store.getters.token,
          url_base: this.$store.getters.url_base,
          overlay: true,
          gdp: [
            {country: "2016", value: 3205097.4 },
            {country: "2017", value: 3579967.1 },
            {country: "2018", value: 3876161.3 },
            {country: "2019", value: 4193501.9 },
            {country: "2020", value: 4422285.1 }
          ],
          gini: [
            {country: "2018", value: 0.357 },
            {country: "2019", value: 0.330 },
            {country: "2020", value: 0.300 },
          ],
          miskin:[
            {country: "2018", value: 3.19 },
            {country: "2019", value: 3.16 },
            {country: "2020", value: 3.23 },
          ],
        }
      },
      methods: {
        goTo(link){
          this.$router.push(link);
        },
        async initData(){
          var data_temps = []
          this.loadingGrafik = true;
          var refs = this
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/ambil-artikel').then((response) => {
            data_temps = response.data
            refs.artikels = response.data

            // console.log(data_temps)
          }).catch(errors => {
              console.log('Error')
          }).finally(() => {
          });

           await axios.get('/api/ambil-inovation').then((response) => {
            data_temps = response.data
            refs.inovations = response.data

            // console.log(data_temps)
          }).catch(errors => {
              console.log('Error')
          }).finally(() => {
          });
          this.loadingGrafik = false;
        },
        async getUser(){
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/user-permission').then(response => {
              // console.log('User ada')
              if(response.data.status == true){
                this.currentUser = response.data.user
                console.log('Akun ada')
                this.isLoggedIn = true
              }else{
                this.isLoggedIn = false
                console.log('Akun kosong')
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
        isLarge(){
          console.log(window.innerWidth)
          return (window.innerWidth > 500)
        },
      },
    computed: {
      images_computed(){
        return this.images
      },
      artikels_computed(){
        return ((this.artikels).length == 0)
      },
      inovations_computed(){
        return ((this.inovations).length == 0)
      },
      
    },
    async mounted () {
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      AOS.init();
      // this.initMap();
      await this.getUser();
      // this.initGraph2();
      // this.initDiagramPertama();
      // this.initDiagramKedua();
      // this.generateArc();
      this.overlay = false;
    },
    async created(){
      var refs = this
      await axios.get('/api/check-infografis')
      .then(response => {
        // console.log(response.data);
        var status = response.data.status;
        var count = (response.data.infografis).length;
        if((status == true) && (count > 0)){
          var images_new = []
          var images_temp = response.data.infografis
          images_temp.forEach(function(datum){
            images_new.push({
              src : refs.url_base + '/api/infografis/'+datum.source,
              title : datum.name,
              description : datum.description,
            });
          });
          // console.log(images_new);
          this.images = images_new;
        }else{
          this.images = [
            {
              src: 'https://kalteng.kemenkumham.go.id/images/sukamaraok991.jpg',
              title : 'SP2020 Menjangkau Semua',
              description : 'No one left behind',
            },
            {
              src: 'https://www.djkn.kemenkeu.go.id/files/images/2021/03/IMG_4013-min.JPG',
              title : 'Pantai Tanjung Nipah',
              description : 'Permata tersembunyi yang membawa ketenangan dan ketentraman',
            },
            {
              src: 'https://sukamarakab.bps.go.id/publication/getImageCover.html?url=MjAyMS0wOC0xNSMjaHR0cHM6Ly9wb3J0YWxwdWJsaWthc2kuYnBzLmdvLmlkL2FwaS9nZXRLb3Zlci5waHA%2Fc2VsZWN0b3I9YTBmNzk1MDk3ZjE1YmU3ZmY4YTM0MWI4',
              title : 'Inkesra 2016',
              description : 'Publikasi terpercaya',
            },
          ];
        }
      }).catch( errors => {
          console.log(errors);
          this.images = [
            {
              src: 'https://kalteng.kemenkumham.go.id/images/sukamaraok991.jpg',
              title : 'SP2020 Menjangkau Semua',
              description : 'No one left behind',
            },
            {
              src: 'https://upload.wikimedia.org/wikipedia/commons/c/c0/Tugu_Sukarno_Palangkaraya.jpg',
              title : 'Tugu Soekarno',
              description : 'Pendiri bangsa yang menjadi panutan para pemuda/pemudi. Kita semua adalah pemuda/pemudi baginya.',
            },
            {
              src: 'https://sukamarakab.bps.go.id/publication/getImageCover.html?url=MjAyMS0wOC0xNSMjaHR0cHM6Ly9wb3J0YWxwdWJsaWthc2kuYnBzLmdvLmlkL2FwaS9nZXRLb3Zlci5waHA%2Fc2VsZWN0b3I9YTBmNzk1MDk3ZjE1YmU3ZmY4YTM0MWI4',
              title : 'Inkesra 2016',
              description : 'Publikasi terpercaya',
            },
          ];
      })
      await this.initData();
    },
  }
</script>
<style lang="scss" scoped>
body {
		font-family: "Century Gothic", 'Lato', sans-serif;
}

a {
	text-decoration: none;
}

.et-hero-tabs,
.et-slide {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    position: relative;
    background: #eee;
		text-align: center;
		// padding: 0 2em;
    h1 {
        font-size: 2rem;
        margin: 0;
        letter-spacing: 1rem;
    }
    h3 {
        font-size: 1rem;
        letter-spacing: 0.3rem;
        opacity: 0.6;
    }
}

.et-hero-tabs-container {
    display: flex;
    flex-direction: row;
    position: absolute;
    bottom: 0;
    width: 100%;
    height: 70px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    background: #fff;
    z-index: 10;
    &--top {
        position: fixed;
        top: 0;
    }
}

.et-hero-tab {
    display: flex;
    justify-content: center;
    align-items: center;
    flex: 1;
    color: #000;
    letter-spacing: 0.1rem;
		transition: all 0.5s ease;
		font-size: 0.8rem;
	  &:hover {
			color:white;
      background: rgba(10, 94, 7, 0.8);
			transition: all 0.5s ease;
    }
}

.et-hero-tab-slider {
    position: absolute;
    bottom: 0;
    width: 0;
    height: 6px;
    background: #1abd0c;
    transition: left 0.3s ease;
}

@media (min-width: 800px) {
	.et-hero-tabs,
	.et-slide {
    h1 {
        font-size: 3rem;
    }
    h3 {
        font-size: 1rem;
    }
	}
	.et-hero-tab {
		font-size: 1rem;
	}
}
</style>
<style lang="scss" scoped>
@import url(https://fonts.googleapis.com/css?family=Josefin+Sans:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic);

// http://www.colourlovers.com/palette/144655/Wine_at_the_picnic

// Variables

$menu-bar-height: 4em;
$article-height: 16em;

$ui-color-first: #333;
$ui-color-second: rgb(116, 230, 163);

$ui-selection-color: #3ab873;

$ui-color-light: #41af66;
$ui-color-dark: #411236;
$ui-article-shadow-colors: 0px 3px #2c9460, 3px 0px #84e911;
$ui-article-shadow-colors: 3px 3px #000;


$font-pf-d: 'Josefin Sans', Arial;
$font-j-s: 'Josefin Sans', Arial;

$three-s-transition: all .3s ease-in;

.intro {
	display: block;
	// padding: .5em 2.5%;
	// margin: 1em 0%;
	// width: 100% - 2.5% * 2;
}

.intro p {
	// padding: 1em 0;
	line-height: 120%;
	font-size: 1.2em;
	color: $ui-color-first;
	font-family: $font-j-s;
}

// AWAL MULA INOVATIOn
$primary-font: sans-serif;
$secondary-font:'Oswald', sans-serif;

body {
  background: rgb(30, 163, 18);
  min-height: calc(100vh - 32px);
  display: flex;
  align-items: center;
  text-align: center;
  // justify-content: center;
}
inovation {
  background: #ccc;
  background: url('https://www.djkn.kemenkeu.go.id/files/images/2021/03/IMG_3944-min.JPG') no-repeat center center;
  background-size: cover;
  box-shadow: 
    inset 0 0 100vw 100vw rgba(0,0,0,0.3),0 16px 8px -8px rgba(0, 0, 0, 0.4);
  // max-width: 800px;
  height: 440px;
  padding: 0 0 32px;
  display: flex;
  overflow-x: scroll;
  border-radius: 8px;
}

materi {
  background: #ccc;
  background: url('https://pbs.twimg.com/media/DslqUheVYAAX5Eq?format=jpg') no-repeat center center;
  background-size: cover;
  box-shadow: 
    inset 0 0 100vw 100vw rgba(0,0,0,0.3),0 16px 8px -8px rgba(0, 0, 0, 0.4);
  // max-width: 800px;
  height: 440px;
  padding: 0 0 32px;
  display: flex;
  overflow-x: scroll;
  border-radius: 8px;
}
/*CARD*/
.card__btn {
  color: #fff;
  border: 1px solid rgba(255,255,255,0.5);
  border-radius: 24px;
  font-family: $primary-font;
  font-size: 6px;
  text-align: center;
  text-decoration: none;
  text-transform: uppercase;
  width: 88px;
  margin: 8px 0 0;
  opacity: 0.5;
  padding: 8px;
}
.card__text-sm {
  color: #fff;
  font-family: $primary-font;
  font-size: 15px;
  margin: 0;
  padding: 16px 0 8px;
  position: relative;
}
.card__text-sm::before {
  content: "";
  background: #fff;
  height: 1px;
  width: 25px;
  position: absolute;
  top: 0;
}
.card__text-lg {
  color: #fff;
  font-family: $secondary-font;
  font-size: 20px;
  font-weight: 300;
  text-transform: uppercase;
  line-height: 1.2;
  width: 100%;
  margin: 1;
}
/*...large...*/
.card-lg__container {
  padding: 0 16px 40px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;

  .card__text-lg {
    font-family: $secondary-font;
    font-size: 40px;
    font-weight: 400;
    letter-spacing: 1.2px;
    text-align: center;
  } 
  .card__text-md {
    color: #fff;
    font-family: $primary-font;
    font-size: 10px;
    font-weight: 100;
    line-height: 1.5;
    padding: 0 32px 0 0;
    opacity: 0.5;
  }
  .card__text-sm {
    font-size: 25px;
  }
  .card__text-sm::before {
    width: 24px;
    height: 2px;
  }
}
/*...small...*/
.card-sm__container {
  height: 232px;
  // width: 320px;
  margin: 0 -88px 0 0;
  display: flex;
  align-self: flex-end;
}
.card-sm {
  background: #ccc;
  background-size: cover; 
  background-position: center center;
  box-shadow: inset 0 0 100vw 100vw rgba(0, 0, 0, 0.3),
    0 16px 8px -8px rgba(0,0,0,0.4);
  border-radius: 8px;
  width: 300px;
  height: calc(100% - 64px); 
  margin: 0 8px;
  padding: 24px 16px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  cursor: pointer;
  transition: 0.3s ease;
  // content-align: center;
  
  &:hover {
    margin: -8px 8px 0;
  }
  
  .card__text-sm { 
    opacity: 0.8;
  }
  
}


/*HIDE DEFAULT SCROLL BAR*/
.inovation::-webkit-scrollbar {
  display: none;
}

.inovation {
  -ms-overflow-style: none;
}
</style>