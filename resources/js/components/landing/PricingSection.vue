<template>
  <section id="pricing" class="pb-8">
    <v-container fluid>
      <v-row align="center" justify="center">
        <v-col cols="12">
          <v-card style="width: 100%">
            <h1 class="text-center pt-3 font-weight-light display-2">INFOGRAFIS</h1>
            <v-divider class="my-3"></v-divider>
            <v-row class="text-center">
              <v-col class="col-12">
                <div class="flex-center">
                  <v-card-text>
                    <div class="flex-center">
                      <v-carousel hide-delimiters height="auto" width="100%">
                        <!-- <v-carousel-item
                          v-for="(infografis, index) in daftar_infografis"
                          :key="index"
                          :src="infografis.src"
                        ></v-carousel-item> -->
                        <v-carousel-item
                          v-for="(item,i) in daftar_infografis"
                          :key="i"
                          :src="item.src"
                          reverse-transition="fade-transition"
                          transition="fade-transition"
                          eager
                        >
                        <h1 class="mt-5 ml-5 base--text" style="text-shadow: 1px 1px 2px purple;">{{ item.title }}</h1>
                        <div class="ml-5 base--text image-description" style="margin-top:15px">{{ item.link }}</div>
                        <div class="ml-5 base--text image-description" style="margin-top:20px">{{ item.description }}</div>
                      </v-carousel-item>
                      </v-carousel>
                    </div>
                  </v-card-text>
                  <!-- <v-divider style="margin-right: -23px" vertical v-if="this.$vuetify.breakpoint.smAndUp"></v-divider> -->
                </div>
                <v-divider class="mx-4" v-if="!this.$vuetify.breakpoint.smAndUp"></v-divider>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    <div class="svg-border-rounded text-light">
      <!-- <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 144.54 17.34"
        preserveAspectRatio="none"
        fill="currentColor"
      >
        <path
          d="M144.54,17.34H0V0H144.54ZM0,0S32.36,17.34,72.27,17.34,144.54,0,144.54,0"
        />
      </svg> -->
      <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 1440 320"
          preserveAspectRatio="none"
          fill="currentColor"
      >
        <path
            d="M0,64L80,90.7C160,117,320,171,480,181.3C640,192,800,160,960,138.7C1120,117,1280,107,1360,101.3L1440,96L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"
        ></path>
      </svg>
    </div>
    <p style="text-align:center;">
        <v-btn rounded outlined to="/list-infografis" target="_blank" large color="black" class="mt-4">
          <v-icon class="mr-2">
            mdi-image-album
          </v-icon>
          Cek infografis lainnya!
        </v-btn>
      </p>
  </section>
</template>

<style lang="scss" scoped>
$main_color: #283e79;

ul {
  font-size: 13px;
  line-height: 1.5em;
  margin: 5px 0 15px;
  padding: 0;

  li {
    list-style: none;
    position: relative;
    padding: 0 0 0 20px;
  }

  li {
    &::before {
      content: "";
      position: absolute;
      left: 0;
      top: 5px;
      width: 10px;
      height: 10px;
      background-color: $main_color;
      border-radius: 50%;
      -moz-border-radius: 50%;
      -webkit-eeborder-radius: 50%;
    }
  }
}
</style>

<style scoped>
.header {
  background-color: #283e79;
  color: white;
}

.circle1 {
  border-radius: 50%;
  width: 150px;
  height: 150px;
  background-color: #f0f8ff;
  display: flex;
  align-items: center;
  justify-content: center;
}

.circle2 {
  border-radius: 50%;
  width: 100px;
  height: 100px;
  background-color: #e0effc;
  display: flex;
  align-items: center;
  justify-content: center;
}

.flex-center {
  display: flex;
  align-items: center;
  justify-content: center;
}

.svg-border-rounded svg {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  color: #f4f7f5;
  z-index: -1;
}

#pricing {
  z-index: 0;
}

.content {
  z-index: 1;
}

svg {
  overflow: hidden;
}

section {
  position: relative;
}
</style>

<script>
import axios from 'axios'
export default {
  data () { 
    return {
      overlay: false,
      loading_text: '',
      isLoggedIn: this.$store.getters.isLogged,
      token: this.$store.getters.token,
      url_base: this.$store.getters.url_base,
      daftar_infografis: [
        {
          src: 'https://kalteng.kemenkumham.go.id/images/sukamaraok991.jpg',
          title : 'SP2020 Menjangkau Semua',
          description : 'No one left behind',
          link: '',
        },
        {
          src: 'https://www.djkn.kemenkeu.go.id/files/images/2021/03/IMG_4013-min.JPG',
          title : 'Pantai Tanjung Nipah',
          description : 'Permata tersembunyi yang membawa ketenangan dan ketentraman',
          link: '',
        },
        {
          src: 'http://ditjenppi.menlhk.go.id/reddplus/images/galeri/ozon%20-%20lomba%20info%20grafis%20Bintang%203%20(24)%20(Penjurian)/10092018_Septian%20Agam_Ayo%20bersama%20lindungi%20ozon.jpg',
          title : 'Perubahan Iklim',
          description : 'Publikasi terpercaya',
          link: '',
        },
      ],
    }
  },
  computed: {
    size() {
      const size = {md: "large", xl: "x-large"}[
          this.$vuetify.breakpoint.name
          ];
      return size ? {[size]: true} : {};
    }
  },
  methods: {
    async initData(){
      console.log("BISA BLAH")
      this.overlay = true;
      this.loading_text = 'Memulai mengambil inovasi untuk dirimu . . .'
      var refs = this
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      await axios.get('/api/pagination-infografis').then((response) => {
        var data_temps = response.data.data
        var data_baru = [];
        if(data_temps.length > 0){
          data_temps.forEach(infografis => {
            data_baru.push({
              src: this.url_base+'/api/infografis/'+infografis.ssource,
              title: infografis.name,
              description: infografis.description,
              link: infografis.tautan_infografis,
            })
          })
          // console.log(data_temps)
          this.daftar_infografis = data_baru
        }
        this.loading_text = 'Berhasil mengambil data . . .'
      }).catch(errors => {
          console.log(errors)
      }).finally(() => {
      });
      // this.overlay_inovation = false;
    },
  },
  async mounted() {
    await this.initData()
  },
};
</script>