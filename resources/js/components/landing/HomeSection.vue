<template>
  <section id="hero">
    <v-parallax dark src="/assets/img/bgHero.jpg" height="750">
      <v-row align="center" justify="center">
        <v-col cols="10">
          <v-row align="center" justify="center">
            <v-col cols="12" md="6" xl="8">
              <h1 style="color:rgb(206, 186, 120);" class="display-2 font-weight-bold mb-4">ADINDA</h1>
              <h1 style="color:rgb(216, 206, 173);" class="font-weight-light">
                Aplikasi Dokumentasi Inovasi dan Artikel <br />
                Mari cari inovasi dan artikel yang sudah terdokumentasi <br />
                Yuk bergabung dengan kami!
              </h1>
              <v-btn
                rounded
                outlined
                large
                dark
                @click="$vuetify.goTo('#features')"
                class="mt-5"
                style="color:rgb(206, 186, 120);"
              >
                Yuk Berkeliling
                <v-icon style="color:rgb(206, 186, 120);" class="ml-2">mdi-arrow-down</v-icon>
              </v-btn>
              <div style="color:rgb(206, 186, 120);" class="video d-flex align-center py-4">
                <a @click.stop="dialog = true" class="playBut">
                  <svg
                    version="1.1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    xmlns:a="http://ns.adobe.com/AdobeSVGViewerExtensions/3.0/"
                    x="0px"
                    y="0px"
                    width="60px"
                    height="60px"
                    viewBox="0 0 213.7 213.7"
                    enable-background="new 0 0 213.7 213.7"
                    xml:space="preserve"
                    fill="color:rgb(25, 84, 88);"
                  >
                    <polygon
                      class="triangle"
                      id="XMLID_18_"
                      fill="none"
                      stroke-width="7"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-miterlimit="10"
                      points="73.5,62.5 148.5,105.8 73.5,149.1 "
                    />

                    <circle
                      class="circle"
                      id="XMLID_17_"
                      fill="none"
                      stroke-width="7"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-miterlimit="10"
                      cx="106.8"
                      cy="106.8"
                      r="103.3"
                      style="color:rgb(206, 186, 120);"
                    />
                  </svg>
                </a>
                <p class="subheading ml-2 mb-0">Video aplikasi</p>
              </div>
            </v-col>
            <v-col cols="12" md="6" xl="4" class="hidden-sm-and-down"> </v-col>
          </v-row>
        </v-col>
      </v-row>
      <div class="svg-border-waves text-white">
        <v-img src="/assets/img/borderWaves.svg" />
      </div>
    </v-parallax>
    <v-container fluid id="features" class="mt-2" style="min-height:500px;">
      <v-row align="center" justify="center">
        <v-col cols="10">
          <div v-if="features.length > 0">
          <v-row  align="center" justify="space-around">
            <v-col
              cols="12"
              sm="4"
              class="text-center"
              v-for="(feature, i) in features"
              :key="i"
            >
              <v-hover v-slot:default="{ hover }">
                <v-card
                  class="card"
                  shaped
                  :elevation="hover ? 10 : 4"
                  :class="{ up: hover }"
                >
                  <v-img
                    :src="feature.ikon_inovation"
                    max-width="150px"
                    contain
                    class="d-block ml-auto mr-auto"
                    :class="{ 'zoom-efect': hover }"
                  ></v-img>
                  <h1 class="font-weight-regular">{{ feature.nama_inovation }}</h1>
                  <h4 class="font-weight-regular subtitle-1">
                    {{ (feature.deskripsi_inovation).length < 75 ? feature.deskripsi_inovation : (feature.deskripsi_inovation).substr(0, 70) + '...' }}
                  </h4>
                  <v-btn
                  text
                  color="tertiary" 
                  :to="'/inovation/'+feature.id_inovation"
                  style="padding-top: 5px;">
                    Cek inovasi!
                  </v-btn>
                </v-card>
              </v-hover>
            </v-col>
          </v-row>
          <p style="text-align:center;">
            <v-btn rounded outlined to="/list-inovation" target="_blank" large color="black" class="mt-4">
              <v-icon class="mr-2">
                mdi-lightbulb-on
              </v-icon>
              Yuk Kenali Inovasi Yang Lain
            </v-btn>
          </p>
          </div>
          <v-row v-else align="center" justify="space-around">
            <v-col
              cols="12"
              class="text-center">
              <v-img src="/assets/img/inovasi_error.png" contain width="80vw" max-height="80vh">
              </v-img>
              <p style="text-align:center;">
                <v-btn text large color="black" class="mt-4">
                  <v-icon class="mr-2">
                    mdi-alert-circle-outline
                  </v-icon>
                  INOVASI BELUM ADA
                  <v-icon class="mr-2">
                    mdi-alert-circle-outline
                  </v-icon>
                </v-btn>
              </p>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-container>
    <v-dialog v-model="dialog" max-width="640px">
      <v-card>
        <youtube
          :video-id="videoId"
          @ready="ready"
          @playing="playing"
        ></youtube>
      </v-card>
    </v-dialog>
    <div class="svg-border-waves">
      <img src="/assets/img/wave2.svg" />
    </div>
  </section>
</template>

<script>
import axios from 'axios'
export default {
  data() {
    return {
      dialog: false,
      videoId: "F50ScF6Savg",
      overlay: false,
      loading_text: '',
      isLoggedIn: this.$store.getters.isLogged,
      token: this.$store.getters.token,
      url_base: this.$store.getters.url_base,
      features: [],
    };
  },
  watch: {
    dialog(value) {
      if (!value) {
        this.pause();
      }
    },
  },
  methods: {
    ready(event) {
      this.player = event.target;
    },
    playing(event) {
      // The player is playing a video.
    },
    async initData(){
      this.overlay = true;
      this.loading_text = 'Memulai mengambil inovasi untuk dirimu . . .'
      var refs = this
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      await axios.get('/api/inovations').then((response) => {
        var data_temps = response.data.data
        var data_baru = [];
        data_temps.forEach(inovation => {
          data_baru.push({
            ikon_inovation: this.url_base+'/api/ikon_inovation/'+inovation.ikon_inovation,
            nama_inovation: inovation.nama_inovation,
            deskripsi_inovation: inovation.deskripsi_inovation,
            id_inovation: inovation.id_inovation,
          })
        })
        // console.log(data_temps)
        this.features = data_baru
        this.loading_text = 'Berhasil mengambil data . . .'
      }).catch(errors => {
          console.log(errors)
      }).finally(() => {
      });
      // this.overlay_inovation = false;
    },
    change() {
      // when you change the value, the player will also change.
      // If you would like to change `playerVars`, please change it before you change `videoId`.
      // If `playerVars.autoplay` is 1, `loadVideoById` will be called.
      // If `playerVars.autoplay` is 0, `cueVideoById` will be called.
      this.videoId = "another video id";
    },
    stop() {
      this.player.stopVideo();
    },
    pause() {
      this.player.pauseVideo();
    },
  },
  async mounted() {
    await this.initData()
  },
};
</script>

<style lang="scss">
.circle {
  stroke: rgb(206, 186, 120);
  stroke-dasharray: 650;
  stroke-dashoffset: 650;
  -webkit-transition: all 0.5s ease-in-out;
  opacity: 0.7;
}

.playBut {
  /*  border: 1px solid red;*/
  display: inline-block;
  -webkit-transition: all 0.5s ease;

  .triangle {
    -webkit-transition: all 0.7s ease-in-out;
    stroke-dasharray: 240;
    stroke-dashoffset: 480;
    stroke: rgb(206, 186, 120);
    transform: translateY(0);
  }

  &:hover {
    .triangle {
      stroke-dashoffset: 0;
      opacity: 1;
      stroke: rgb(206, 186, 120);
      animation: nudge 0.7s ease-in-out;

      @keyframes nudge {
        0% {
          transform: translateX(0);
        }
        30% {
          transform: translateX(-5px);
        }
        50% {
          transform: translateX(5px);
        }
        70% {
          transform: translateX(-2px);
        }
        100% {
          transform: translateX(0);
        }
      }
    }

    .circle {
      stroke-dashoffset: 0;
      opacity: 1;
    }
  }
}
</style>

<style>
.btn-play {
  transition: 0.2s;
}

.svg-border-waves .v-image {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3rem;
  width: 100%;
  overflow: hidden;
}

#hero {
  z-index: 0;
}
.svg-border-waves img {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  margin-bottom: -2px;
  z-index: -1;
}

.card {
  min-height: 200px;
  padding: 10px;
  transition: 0.5s ease-out;
}

.card .v-image {
  margin-bottom: 15px;
  transition: 0.75s;
}

.card h1 {
  margin-bottom: 10px;
}

.zoom-efect {
  transform: scale(1.1);
}

.up {
  transform: translateY(-20px);
  transition: 0.5s ease-out;
}
</style>

<style>
section {
  position: relative;
}
</style>
