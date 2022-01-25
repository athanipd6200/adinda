<template>
  <div>
    <v-navigation-drawer
      v-model="drawer"
      app
      temporary
      dark
      src="/assets/img/bgDrawer.jpg"
    >
      <v-list>
        <v-list-item>
          <v-list-item-avatar>
            <img src="/assets/img/logo.png" alt="Logo" />
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title class="title">ADINDA</v-list-item-title>
            <v-list-item-subtitle>Aplikasi Dokumentasi Inovasi dan Artikel</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <v-divider />

      <v-list dense>
        <v-list-item
          v-for="([icon, text, link], i) in items"
          :key="i"
          link
          @click="$vuetify.goTo(link)"
        >
          <v-list-item-icon class="justify-center">
            <v-icon>{{ icon }}</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title class="subtitile-1">{{
              text
            }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      :color="color"
      :flat="flat"
      dark
      class="px-15"
      :class="{ expand: flat }"
    >
      <v-toolbar-title>
        <router-link to="/profile">
            <v-img src="/assets/img/logo.png" max-width="50px" />
        </router-link>
      </v-toolbar-title>
      <v-spacer />
      <v-app-bar-nav-icon
        @click.stop="drawer = !drawer"
        class="mr-4"
        v-if="isXs"
        style="color:rgb(247, 208, 199);"
      />
      <div v-else>
        <v-btn style="color:rgb(247, 208, 199);" text @click="$vuetify.goTo('#hero')">
          <span class="mr-2">Home</span>
        </v-btn>
        <v-btn style="color:rgb(247, 208, 199);" text @click="$vuetify.goTo('#features')">
          <span class="mr-2">Inovasi</span>
        </v-btn>
        <v-btn style="color:rgb(247, 208, 199);" text @click="$vuetify.goTo('#download')">
          <span class="mr-2">Artikel</span>
        </v-btn>
        <v-btn style="color:rgb(247, 208, 199);" text @click="$vuetify.goTo('#pricing')">
          <span class="mr-2">Infografis</span>
        </v-btn>
        <!-- <v-btn rounded outlined text @click="$vuetify.goTo('#contact')">
          <span class="mr-2">Kontak Kami</span>
        </v-btn> -->
      </div>
    </v-app-bar>
  </div>
</template>

<style scoped>
.v-toolbar {
  transition: 0.6s;
}

.expand {
  height: 80px !important;
  padding-top: 10px;
}
</style>

<script>
export default {
  data: () => ({
    drawer: null,
    isXs: false,
    items: [
      ["mdi-home-outline", "Home", "#hero"],
      ["mdi-lightbulb-outline", "Inovasi", "#features"],
      ["mdi-newspaper-variant-outline", "Artikel", "#download"],
      ["mdi-image-outline", "Infografis", "#pricing"],
      // ["mdi-email-outline", "Contatos", "#contact"],
    ],
  }),
  props: {
    color: String,
    flat: Boolean,
  },
  methods: {
    onResize() {
      this.isXs = window.innerWidth < 850;
    },
  },

  watch: {
    isXs(value) {
      if (!value) {
        if (this.drawer) {
          this.drawer = false;
        }
      }
    },
  },
  mounted() {
    this.onResize();
    window.addEventListener("resize", this.onResize, { passive: true });
  },
};
</script>
