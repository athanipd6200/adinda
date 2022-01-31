 <template>
    <v-app id="artikel">
      <v-overlay :value="overlay" style="z-index:6000;">
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
            :src="url_base+'/api/gambar_pembuka_artikel/'+artikel.gambar_pembuka_artikel"
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
              show-arrows
            >
            <v-slide-item>
              <v-img @click.stop="drawer = !drawer" max-height="40px" max-width="40px" :src="url_base+'/api/other/logo.png'" />
            </v-slide-item>

            <v-slide-item>
              <h3 style="cursor: pointer;" @click="$router.push('/')" class="white--text mx-4">ADINDA - Aplikasi Dokumentasi Inovasi dan Artikel</h3>
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
          <v-container v-if="status_artikel == true">
            <h1 style="text-align:center;margin:10px;" >
              {{ artikel.judul_artikel }}
            </h1>
            <p style="text-align:center;font-weight:200">
              Tanggal : {{ artikel.tanggal_artikel }} 
            </p>
            <ckeditor :editor="editor_read" v-model="artikel.teks_isi_artikel" :disabled="editorDisabled" :config="editorConfig_read"></ckeditor>
            
            <div v-if="filenames.length > 0">
              <h5 style="text-align:left;margin-top:10px;">
                Lampiran :
              </h5>
              <v-chip
                  v-for="(filename, index) in filenames_computed"
                  :key='"filenames_"+index'
                  color="primary darken--2"
                  @click="getPreviewFile(filename, 'lampiran_artikel')"
                  medium
                  style="margin:5px;"
                >
                <span class="pr-2">
                  {{ filename }}
                </span>
              </v-chip>
            </div>
            <h5 style="text-align:left;margin-top:10px;">
              Tag :
            </h5>
            <div v-if="tags.length > 0">
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
            </div>
            <h5 style="text-align:left;margin-top:10px;">
              Penulis : {{ artikel.penulis_artikel }}
            </h5>
            <h5 style="text-align:left;margin-top:10px;">
              Penyunting : {{ artikel.penyunting_artikel }}
            </h5>
            <div v-if="lokasi_tersedia">
              <h5 style="text-align:left;margin-top:10px;">
                Lokasi : 
              </h5>
              <div id="cover_map">
                <div id="map" ref="map" style="width: auto; height: 40vh"></div>
              </div>
            </div>

            <div id="news" style="margin-top:10px;">
              <section class="news">
                <article v-for="datum in this.artikels" :key="datum.id_entri">
                  <img :src="url_base+'/api/gambar_pembuka_artikel/'+datum.gambar_pembuka_artikel" alt=""/>
                  <div class="text">
                    <h1>{{ datum.judul_artikel}}</h1>

                    <p style="font-size: 14px">
                      {{ datum.teks_pembuka_artikel }}
                    </p>
                    <div style="padding-top: 5px;font-size: 12px">
                      Penulis: {{ datum.penulis_artikel }}
                    </div>
                    <div style="font-size: 12px">
                      Tanggal Publikasi: {{ datum.tanggal_artikel }}
                    </div>
                    <v-btn
                    color="secondary" 
                    @click='changeArtikel(datum.id_artikel)'
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
                color="secondary darken--3"
                large
                class="ma-2 white--text text-center"
                :style="{left: '50%', transform:'translateX(-50%)'}"
                to="/list-artikel"
              >
                Baca artikel lainnya
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
            <h2>Artikel tidak ada</h2>
          </v-container>
    </v-main>
    <v-footer
      dark
      padless>
      <v-card
        flat
        tile
        class="primary lighten-1 white--text text-center"
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
          drawer: false,
          locations: null,
          overlay:true,
          data_asets: [],
          center: [-2.207680, 113.916357],
          zoom: 15,
          map:null,
          artikels:[],
          artikel:[],
          editor_read: ClassicEditor,
          editorDisabled:true,
          editorConfig_read: {
            toolbar: [],
            // isReadOnly:true,
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
          id_artikel: this.$route.params.id_artikel,
          overlay: true, 
          status_artikel:true,
        }
      },
      // watch: {
      //   $route(to, from) {
      //     // react to route changes...
      //     // this.$router.push(from.path);
      //   }
      // },
      computed: {
        filenames_computed(){
          return this.filenames;
        },
        tags_computed(){
          return this.tags;
        },
      },
      methods: {
        submit () {
        },
        clear () {
        },
        getPreviewFile(file_name, jenis_file){
          this.overlay_list = true
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
          this.overlay_list = false
        },
        async downloadBlob(blob, name) {
          this.overlay_list = true
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
          await document.body.removeChild(link);
          this.overlay = false
        },
        async initData(){
          var data_temps = []
          this.overlay = true;
          var refs = this
          var data = []
          var map_temp;
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/ambil-artikel/'+this.$route.params.id_artikel+'/3').then((response) => {
            data_temps = response.data
            // console.log(response.data.artikel)
            if(response.data.artikel == null){
              refs.status_artikel = false
            }else{
              refs.lokasi_tersedia = true
              refs.artikel = response.data.artikel
              refs.artikels = response.data.artikels
              refs.filenames = response.data.artikel.lampiran_artikel == null ? [] : (response.data.artikel.lampiran_artikel).split(',')
              refs.tags = response.data.artikel.tags_artikel == null ? [] : (response.data.artikel.tags_artikel).split(',')
              refs.artikel.teks_isi_artikel = refs.artikel.teks_isi_artikel == null ? 'Tidak ada konten' : refs.artikel.teks_isi_artikel
              if(this.map != null){
                document.getElementById('cover_map').innerHTML = '<div id="map" ref="map" style="width: auto; height: 40vh"></div>';
              }

              if(!JSON.parse(response.data.artikel.geolocation_artikel)){
                // console.log("Kosong")
                var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    osm = L.tileLayer(osmUrl, { maxZoom: 19, attribution: osmAttrib }),
                    map_temp = new L.Map('map', { center: [-2.207680, 113.916357], zoom: 15 }),
                    drawnItems = L.featureGroup().addTo(map_temp);
                L.control.layers({
                    'osm': osm.addTo(map_temp),
                    "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', 
                    {
                        attribution: 'google'
                    })
                }).addTo(map_temp);
                refs.map = map_temp
                refs.lokasi_tersedia = false
              }else{
                refs.lokasi_tersedia = true
                // console.log("ada isi "+response.data.artikel.geolocation_artikel)
                var lokasi_temp = JSON.parse(response.data.artikel.geolocation_artikel);
                // console.log(lokasi_temp["geometry"]["coordinates"])
                var lotlat_temp = lokasi_temp['geometry']['type'] == 'Polygon' ? lokasi_temp["geometry"]["coordinates"][0][0] : (lokasi_temp['geometry']['type'] == 'LineString' ? lokasi_temp["geometry"]["coordinates"][0] : lokasi_temp["geometry"]["coordinates"])
                var lat_temp = lotlat_temp[1]
                var lot_temp = lotlat_temp[0]
                var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                        osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        osm = L.tileLayer(osmUrl, { maxZoom: 19, attribution: osmAttrib }),
                        map_temp = new L.Map('map', { center: [lat_temp, lot_temp], zoom: 15 }),
                        drawnItems = L.featureGroup().addTo(map_temp);
                L.control.layers({
                    'osm': osm.addTo(map_temp),
                    "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', 
                    {
                        attribution: 'google'
                    })
                }).addTo(map_temp);

                var layerGroup = L.geoJSON(JSON.parse(response.data.artikel.geolocation_artikel),
                  { 
                    //feature untuk data hasil parsing, layer untuk aplikasi
                    onEachFeature: function (feature, layer) {
                      layer.bindPopup('<h3>'+response.data.artikel.judul_artikel+'</h3>');
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
                      }else if(location.jenis_aset == 'mobil'){
                        // layer.setIcon(car)
                      }else if(location.jenis_aset == 'motor'){
                        // layer.setIcon(motor)
                      }else if(location.jenis_aset == 'public'){
                        // layer.setIcon(prasarana)
                      }else if(location.jenis_aset == 'building'){
                        // layer.setIcon(building)
                      }else {
                        console.log('green')
                      }
                    },
                  })
                layerGroup.addTo(map_temp);

                refs.map = map_temp

              }
            }
          }).catch(errors => {
              console.log(errors)
          }).finally(() => {
          });
          // this.overlay_artikel = false;
        },
        async changeArtikel(id_artikel){
          var data_temps = []
          this.overlay= true;
          var refs = this
          var data = []
          var map_temp;
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          await axios.get('/api/ambil-artikel/'+id_artikel+'/3').then((response) => {
            data_temps = response.data
            // console.log(data_temps)
            // refs.lokasi_tersedia = true
            refs.artikel = response.data.artikel
            refs.artikels = response.data.artikels
            refs.filenames = response.data.artikel.lampiran_artikel == null ? [] : (response.data.artikel.lampiran_artikel).split(',')
            refs.artikel.teks_isi_artikel = refs.artikel.teks_isi_artikel == null ? 'Tidak ada konten' : refs.artikel.teks_isi_artikel 
            if(this.map != null){
              document.getElementById('cover_map').innerHTML = '<div id="map" ref="map" style="width: 100%; height: 40vh"></div>';
            }

            // console.log(response.data.artikel.geolocation_artikel);

            if(!JSON.parse(response.data.artikel.geolocation_artikel)){
              console.log("Kosong")
              var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                  osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                  osm = L.tileLayer(osmUrl, { maxZoom: 19, attribution: osmAttrib }),
                  map_temp = new L.Map('map', { center: [-2.207680, 113.916357], zoom: 15 }),
                  drawnItems = L.featureGroup().addTo(map_temp);
              L.control.layers({
                  'osm': osm.addTo(map_temp),
                  "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', 
                  {
                      attribution: 'google'
                  })
              }).addTo(map_temp);
              refs.map = map_temp
              refs.lokasi_tersedia = false
            }else{
              refs.lokasi_tersedia = true
              // console.log("ada isi "+response.data.artikel.geolocation_artikel)
              var lokasi_temp = JSON.parse(response.data.artikel.geolocation_artikel);
              // console.log(lokasi_temp["geometry"]["coordinates"])
              var lotlat_temp = lokasi_temp['geometry']['type'] == 'Polygon' ? lokasi_temp["geometry"]["coordinates"][0][0] : (lokasi_temp['geometry']['type'] == 'LineString' ? lokasi_temp["geometry"]["coordinates"][0] : lokasi_temp["geometry"]["coordinates"])
              var lat_temp = lotlat_temp[1]
              var lot_temp = lotlat_temp[0]
              
              var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                      osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                      osm = L.tileLayer(osmUrl, { maxZoom: 19, attribution: osmAttrib }),
                      map_temp = new L.Map('map', { center: [lat_temp, lot_temp], zoom: 15 }),
                      drawnItems = L.featureGroup().addTo(map_temp);
              L.control.layers({
                  'osm': osm.addTo(map_temp),
                  "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', 
                  {
                      attribution: 'google'
                  })
              }).addTo(map_temp);

              var layerGroup = L.geoJSON(JSON.parse(response.data.artikel.geolocation_artikel),
                { 
                  //feature untuk data hasil parsing, layer untuk aplikasi
                  onEachFeature: function (feature, layer) {
                    layer.bindPopup('<h3>'+response.data.artikel.judul_artikel+'</h3>');
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
                    }else if(location.jenis_aset == 'mobil'){
                      // layer.setIcon(car)
                    }else if(location.jenis_aset == 'motor'){
                      // layer.setIcon(motor)
                    }else if(location.jenis_aset == 'public'){
                      // layer.setIcon(prasarana)
                    }else if(location.jenis_aset == 'building'){
                      // layer.setIcon(building)
                    }else {
                      console.log('green')
                    }
                  },
                })
              layerGroup.addTo(map_temp);

              refs.map = map_temp
            }
          }).catch(errors => {
              console.log(errors)
          }).finally(() => {
              // this.overlay= false;
          });
          this.chipKey += 1
          await this.$forceUpdate()
          this.overlay= false;

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
      this.overlay = true;
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