<template>
  <v-container fluid>
    <!-- <v-card class="" :loading="loading"> -->
      <div id="map" class="mx-auto" style="width: 95vw; height: 80vh"></div>
    <!-- </v-card> -->
<!--     <v-sheet v-show='false' ref='foo'>
      <v-carousel style="max-width:960px;max-height:960px;">
        <v-carousel-item
          v-for="(item,i) in galeries"
          :key="i"
          :src="item.src"
          reverse-transition="fade-transition"
          transition="fade-transition"
        ></v-carousel-item>
      </v-carousel>
    </v-sheet> -->
  </v-container>
  
</template>
<script>
  import axios from 'axios'
  import 'leaflet/dist/leaflet.css'
  import L from 'leaflet'

  import 'leaflet-draw/dist/leaflet.draw.css'
  import 'leaflet-draw'
  import 'leaflet-easyprint'

  import $ from "jquery";

  export default {
    props: {
      source: String,
    },
    data () {
        return {
          drawer: false,
          locations: null,
          loading:false,
          center: [-2.207680, 113.916357],
          zoom: 15,
          map:null,
          isLoggedIn: this.$store.getters.isLogged,
          token: this.$store.getters.token,
          url_base: this.$store.getters.url_base,
          lorem: 'Lorem ipsum dolor sit amet, mel at clita quando. Te sit oratio vituperatoribus, nam ad ipsum posidonium mediocritatem, explicari dissentiunt cu mea. Repudiare disputationi vim in, mollis iriure nec cu, alienum argumentum ius ad. Pri eu justo aeque torquatos.',
          galeries: [],
          jenis_asetItems:[
            {teks: 'Mobil Dinas', value: 'mobil'},
            {teks: 'Motor Dinas', value: 'motor'},
            {teks: 'Tanah', value: 'tanah'},
            {teks: 'Gedung', value: 'gedung'},
            {teks: 'Rumah Dinas', value: 'rumah_dinas'},
            {teks: 'Fasilitas Umum', value: 'fasilitas_umum'},
            {teks: 'Elektronik', value: 'elektronik'},
          ],
          status_kepemilikan_tanah_map :{
            hak_milik : {teks: 'Hak Milik', value: 'hak_milik', color: '#fcc44c'},
            hak_guna_usaha : {teks: 'Hak Guna Usaha', value: 'hak_guna_usaha', color: '#cfcb6b'},
            hak_guna_bangunan : {teks: 'Hak Guna Bangunan', value: 'hak_guna_bangunan', color: '#bfd2e7'},
            hak_pakai : {teks: 'Hak Pakai', value: 'hak_pakai', color: '#73b966'},
            hak_pengelolaan : {teks: 'Hak Pengelolaan', value: 'hak_pengelolaan', color: '#ce70ae'},
            hak_wakaf : {teks: 'Hak Wakaf', value: 'hak_wakaf', color: '#657fc1'},
            tanah_adat : {teks: 'Tanah Adat', value: 'tanah_adat', color: '#7becf6'},
            skt_spt : {teks: 'Surat Keterangan Tanah', value: 'skt_spt', color: '#7ecdad'},
            belum_terdaftar : {teks: 'Belum Terdaftar', value: 'belum_terdaftar', color: '#959595'},
            lainnya : {teks: 'Lainnya', value: 'lainnya', color: '#d5d2cd'},
          },
          currentUser:{},
          permissions:[],
        }
      },
      methods: {
        submit () {

        },
        clear () {
          
        },
        initMap(){
          var data = []
          var tanah_map = this.status_kepemilikan_tanah_map
          this.loading = true;
          var map_temp;
          var refs = this;
          var locations_temp;
          var images = [];
          var slideshowContent = '';
          axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
          axios.get('/api/get-lokasi-featured')
          .then((response) => {
            //Then injecting the result to datatable parameters.
            locations_temp = response.data

            var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                  osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                  osm = L.tileLayer(osmUrl, { maxZoom: 19, attribution: osmAttrib }),
                  map_temp = new L.Map('map', { center: this.center, zoom: this.zoom }),
                  drawnItems = L.featureGroup().addTo(map_temp);
            L.control.layers({
                'osm': osm.addTo(map_temp),
                "google": L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', 
                {
                    attribution: 'google'
                }),
                "esri": L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', 
                {
                    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                }),
            }).addTo(map_temp);

            var car = L.icon({
              iconUrl: 'data:image/svg+xml;base64,PHN2ZyBpZD0iQ2FwYV8xIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA0OTYuNyA0OTYuNyIgaGVpZ2h0PSI1MTIiIHZpZXdCb3g9IjAgMCA0OTYuNyA0OTYuNyIgd2lkdGg9IjUxMiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Zz48cGF0aCBkPSJtNDM2LjI0NSAyMDguNTY2LTM3LjA0NS03My44NjNjLTQuMTA0LTguMTgzLTEyLjQ3NS0xMy4zNDktMjEuNjI5LTEzLjM0OWgtMTcxLjkzNWMtNy42NDMgMC0xNC44MzcgMy42MTEtMTkuNDAzIDkuNzRsLTU3LjcyMyA3Ny40NzItMzMuMjkxIDQyLjkzNGgzNjYuNjYxeiIgZmlsbD0iI2ZlZjBhZSIvPjxwYXRoIGQ9Im0zMDUuNTA2IDEzMS4wOTRjNC41NjYtNi4xMjkgMTEuNzYtOS43NCAxOS40MDMtOS43NGgtMTE5LjI3M2MtNy42NDMgMC0xNC44MzcgMy42MTEtMTkuNDAzIDkuNzRsLTU3LjcyMyA3Ny40NzItMzMuMjkxIDQyLjkzNGgxMTkuMjczbDMzLjI5MS00Mi45MzR6IiBmaWxsPSIjZmVlNDVhIi8+PHBhdGggZD0ibTQwMi42OCAyMDguNTctMjYuOTItNTMuNjhjLTEuMDktMi4xNy0zLjMtMy41NC01LjcyLTMuNTRoLTExLjc4bC0xOC4yMzcgMTQuOTI2djY1LjAzMWwxOC4yMzcgMTAuNDc0aDYyLjM1eiIgZmlsbD0iI2I4ZGRmZiIvPjxwYXRoIGQ9Im0zNTguMjYgMjA4LjU3djMzLjIxaC0yMTguNThsMjYuMjQtMzMuMjEgNDAuNzEtNTQuNjRjMS4yMS0xLjYyIDMuMTEtMi41OCA1LjEzLTIuNThoMTQ2LjV6IiBmaWxsPSIjZDVlYWZmIi8+PHBhdGggZD0ibTM4Ny44OSAyMDguNTdoLTEyMC42N2wtMjUuNjUxIDI1Ljcxdjc5LjA0NmwyNS42NTEgMTEuMzM0aDEyMC42N2wyNS4zMDMtMjYuODk3di01My4yMjNjMC0xLjc5MS0uNTg4LTMuNTMyLTEuNjczLTQuOTU2eiIgZmlsbD0iI2ZlZGYzMCIvPjxwYXRoIGQ9Im00ODMuNDUxIDIzOC42MTggNC44NDUtNS44NjNjLTEuNzA3LTEzLjYzMy0xMy4zNC0yNC4xODUtMjcuNDM2LTI0LjE4NWgtNzIuOTd2MTE2LjA5aDEwMC42M3YtNTQuNDFsLTUuMDY5LTIuNTc3eiIgZmlsbD0iI2ZlZTk3ZCIvPjxwYXRoIGQ9Im0xNTEuMTUgMjA4LjU3aC04OC4xNGMtMzEuNyAwLTU3LjQgMjUuNy01Ny40IDU3LjR2NDAuOTEgMTcuNzhoMTI2Ljk0bDQxLjQzLTEwLjM2M3YtODEuNTQ3eiIgZmlsbD0iI2ZlZTk3ZCIvPjxnIGlkPSJYTUxJRF8zNzZfIj48Zz48cGF0aCBkPSJtMjExLjc2IDE1MS4zNWMtMi4wMiAwLTMuOTIuOTYtNS4xMyAyLjU4bC00MC43MSA1NC42NC0yNi4yNCAzMy4yMWg4Mi4zNDlsMjUuNzU0LTMzLjIxNCA0Mi42MzEtNTcuMjE2eiIgZmlsbD0iI2I4ZGRmZiIvPjwvZz48L2c+PHBhdGggZD0ibTMzNy41MDQgMzI0LjY1N3YuMDAzaDUwLjM4NWwyNS4zMDMtMjYuODk3di00OC43OTRjLTQxLjczMyAwLTc1LjY4OCAzMy45NTMtNzUuNjg4IDc1LjY4OHoiIGZpbGw9IiNmZWQ0MDIiLz48ZyBmaWxsPSIjZmVlNDVhIj48cGF0aCBkPSJtNDg4LjUyIDMxNy4zMjNjLTMuNzAxLTM4LjMwNi0zNi4wNjctNjguMzU1LTc1LjMyNy02OC4zNTUtOC44NzIgMC0xNy4zODYgMS41NDgtMjUuMzAzIDQuMzY1djcxLjMyN2gxMDAuNjN6Ii8+PHBhdGggZD0ibTE3My45OCAzMTQuMjk3di0xNy42MDZjLTExLjE0OC0yNy45MjgtMzguNDUzLTQ3LjcyMy03MC4zMTItNDcuNzIzLTQxLjczNCAwLTc1LjY4OCAzMy45NTQtNzUuNjg4IDc1LjY4OHYuMDAzaDEwNC41N3oiLz48cGF0aCBkPSJtMjY3LjIyIDIwOC41N3YxMTYuMDloLTEzNC42N3YtNDQuMTktMzguMTFjMC0xLjQ3LjM5LTIuOSAxLjEzLTQuMTZsMTcuNDctMjkuNjN6Ii8+PC9nPjxwYXRoIGQ9Im0xMzIuNTUgMjU0LjcwOHYyNS43NjIgNDQuMTloNDYuODA4YzAtLjAwMSAwLS4wMDIgMC0uMDAzIDAtMzEuNTExLTE5LjM2MS01OC41NzUtNDYuODA4LTY5Ljk0OXoiIGZpbGw9IiNmZWRmMzAiLz48ZyBpZD0iWE1MSURfMzc1XyI+PGc+PHBhdGggZD0ibTQ5Ni43IDMxMy44MXYxNy43OGMwIDMuODItMy4xIDYuOTItNi45MyA2LjkyaC00ODQuMTZjLTMuMSAwLTUuNjEtMi41MS01LjYxLTUuNjF2LTIwLjRjMC0zLjEgMi41MS01LjYyIDUuNjEtNS42Mmg0ODQuMTZjMy44MyAwIDYuOTMgMy4xIDYuOTMgNi45M3oiIGZpbGw9IiNkN2QwZDYiLz48L2c+PC9nPjxnPjxwYXRoIGQ9Im0yNy45OCAzMjQuNjU3YzAgNC43MzMuNDU4IDkuMzU4IDEuMjkzIDEzLjg1M2gxNDguNzkyYy44MzUtNC40OTUgMS4yOTMtOS4xMiAxLjI5My0xMy44NTMgMC02LjEyMy0uNzUtMTIuMDcyLTIuMTI5LTE3Ljc3N2gtMTQ3LjEyYy0xLjM3OSA1LjcwNi0yLjEyOSAxMS42NTQtMi4xMjkgMTcuNzc3eiIgZmlsbD0iI2I4YjJiOCIvPjxjaXJjbGUgY3g9IjEwMy42NjkiIGN5PSIzMjQuNjU3IiBmaWxsPSIjNzM2NTcyIiByPSI1MC42ODkiLz48cGF0aCBkPSJtMTAzLjY2OSAzMjQuNjU3YzAtMTguNzU5IDEwLjIxMi0zNS4xMzQgMjUuMzU4LTQzLjg5OS03LjQ1Ny00LjMxNi0xNi4xMjItNi43ODktMjUuMzU4LTYuNzg5LTI3Ljk5NSAwLTUwLjY4OCAyMi42OTQtNTAuNjg4IDUwLjY4OXMyMi42OTQgNTAuNjg5IDUwLjY4OCA1MC42ODljOS4yMzYgMCAxNy45MDYtMi40NzYgMjUuMzYzLTYuNzkyLTE1LjE0Ni04Ljc2Ny0yNS4zNjMtMjUuMTM5LTI1LjM2My00My44OTh6IiBmaWxsPSIjNjE1MjYwIi8+PHBhdGggZD0ibTEwMy42NjkgMzQ1LjM0NmMtMTEuNDA3IDAtMjAuNjg4LTkuMjgxLTIwLjY4OC0yMC42ODhzOS4yODEtMjAuNjg4IDIwLjY4OC0yMC42ODhjMTEuNDA4IDAgMjAuNjg5IDkuMjgxIDIwLjY4OSAyMC42ODhzLTkuMjgxIDIwLjY4OC0yMC42ODkgMjAuNjg4eiIgZmlsbD0iI2Y5ZjdmOCIvPjxwYXRoIGQ9Im0zMzcuNTA0IDMyNC42NTdjMCA0LjczMy40NTggOS4zNTggMS4yOTMgMTMuODUzaDE0OC43OTFjLjgzNS00LjQ5NSAxLjI5My05LjEyIDEuMjkzLTEzLjg1MyAwLTYuMTIzLS43NS0xMi4wNzItMi4xMjktMTcuNzc3aC0xNDcuMTJjLTEuMzc4IDUuNzA2LTIuMTI4IDExLjY1NC0yLjEyOCAxNy43Nzd6IiBmaWxsPSIjYjhiMmI4Ii8+PGNpcmNsZSBjeD0iNDEzLjE5MyIgY3k9IjMyNC42NTciIGZpbGw9IiM3MzY1NzIiIHI9IjUwLjY4OSIvPjxwYXRoIGQ9Im0xMDMuNjY5IDMyNC42NTdjMC03LjIwMyAxLjUwOS0xNC4wNTEgNC4yMTktMjAuMjUzLTEuMzYzLS4yODQtMi43NzMtLjQzNS00LjIxOS0uNDM1LTExLjQwNyAwLTIwLjY4OCA5LjI4MS0yMC42ODggMjAuNjg4czkuMjgxIDIwLjY4OCAyMC42ODggMjAuNjg4YzEuNDQ2IDAgMi44NjctLjE1MSA0LjIzLS40MzUtMi43MTEtNi4yMDItNC4yMy0xMy4wNS00LjIzLTIwLjI1M3oiIGZpbGw9IiNkN2QwZDYiLz48cGF0aCBkPSJtNDEzLjE5MyAzMjQuNjU3YzAtMTguNzU5IDEwLjIxMy0zNS4xMzMgMjUuMzU5LTQzLjg5OS03LjQ1OC00LjMxNi0xNi4xMjMtNi43OS0yNS4zNTktNi43OS0yNy45OTUgMC01MC42ODggMjIuNjk0LTUwLjY4OCA1MC42ODlzMjIuNjk0IDUwLjY4OSA1MC42ODggNTAuNjg5YzkuMjM2IDAgMTcuOTA1LTIuNDc2IDI1LjM2My02Ljc5Mi0xNS4xNDctOC43NjUtMjUuMzYzLTI1LjEzOC0yNS4zNjMtNDMuODk3eiIgZmlsbD0iIzYxNTI2MCIvPjxwYXRoIGQ9Im00MTMuMTkyIDM0NS4zNDZjLTExLjQwNyAwLTIwLjY4OC05LjI4MS0yMC42ODgtMjAuNjg4czkuMjgxLTIwLjY4OCAyMC42ODgtMjAuNjg4YzExLjQwOCAwIDIwLjY4OSA5LjI4MSAyMC42ODkgMjAuNjg4cy05LjI4IDIwLjY4OC0yMC42ODkgMjAuNjg4eiIgZmlsbD0iI2Y5ZjdmOCIvPjxwYXRoIGQ9Im00MTMuMTkzIDMyNC42NTdjMC03LjIwMiAxLjUxLTE0LjA0OSA0LjIyMi0yMC4yNTItMS4zNjQtLjI4NC0yLjc3Ni0uNDM2LTQuMjIyLS40MzYtMTEuNDA3IDAtMjAuNjg4IDkuMjgxLTIwLjY4OCAyMC42ODhzOS4yODEgMjAuNjg4IDIwLjY4OCAyMC42ODhjMS40NDcgMCAyLjg1OS0uMTUyIDQuMjI0LS40MzYtMi43MTMtNi4yMDMtNC4yMjQtMTMuMDUtNC4yMjQtMjAuMjUyeiIgZmlsbD0iI2Q3ZDBkNiIvPjxnPjxwYXRoIGQ9Im0yMzQuMDY5IDI0OS4yOGgtNS4zODdjLTQuMTQzIDAtNy41LTMuMzU4LTcuNS03LjVzMy4zNTctNy41IDcuNS03LjVoNS4zODdjNC4xNDMgMCA3LjUgMy4zNTggNy41IDcuNXMtMy4zNTcgNy41LTcuNSA3LjV6IiBmaWxsPSIjZmVmMGFlIi8+PC9nPjxnIGlkPSJYTUxJRF8zNzdfIj48Zz48cGF0aCBkPSJtNDguMTMgMjEwLjUzdjMyLjU2YzAgMTAuMjctOC4zMyAxOC42LTE4LjYxIDE4LjZoLTIzLjc1YzEuODEtMjQuNjUgMTkuMi00NC45NyA0Mi4zNi01MS4xNnoiIGZpbGw9IiNmZWRmMzAiLz48cGF0aCBkPSJtNDg4LjUyIDIzNi4yMnYzNC4wM2gtMTguMDNjLTQuNzUgMC04LjYxLTMuODYtOC42MS04LjYxdi0yMC4yOGMwLTQuNzUgMy44Ni04LjYxIDguNjEtOC42MWgxNy44MWMuMTUgMS4xNC4yMiAyLjMuMjIgMy40N3oiIGZpbGw9IiNlOTQ0NDQiLz48L2c+PC9nPjwvZz48L2c+PC9zdmc+', 
              iconSize: [40, 40],
            });

            var motor = L.icon({
              iconUrl: 'data:image/svg+xml;base64,PHN2ZyBoZWlnaHQ9IjUxMiIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHdpZHRoPSI1MTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxsaW5lYXJHcmFkaWVudCBpZD0iTmV3X0dyYWRpZW50X1N3YXRjaF8xNCIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiIHgxPSIzNjgiIHgyPSI0OTYiIHkxPSIyODgiIHkyPSIyODgiPjxzdG9wIG9mZnNldD0iLjAxNSIgc3RvcC1jb2xvcj0iI2E2YTZiOSIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2NhY2FkNSIvPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJOZXdfR3JhZGllbnRfU3dhdGNoXzEzIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjQwOS4zODMiIHgyPSI0NTQuNjE3IiB5MT0iMzEwLjYxNyIgeTI9IjI2NS4zODMiPjxzdG9wIG9mZnNldD0iMCIgc3RvcC1jb2xvcj0iI2Q0NjY2OSIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2RkODg4YSIvPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJOZXdfR3JhZGllbnRfU3dhdGNoXzE0LTIiIHgxPSIzNC43NDUiIHgyPSIxMjUuMjU1IiB4bGluazpocmVmPSIjTmV3X0dyYWRpZW50X1N3YXRjaF8xNCIgeTE9IjMzMy4yNTUiIHkyPSIyNDIuNzQ1Ii8+PGxpbmVhckdyYWRpZW50IGlkPSJOZXdfR3JhZGllbnRfU3dhdGNoXzEzLTIiIHgxPSI1Ny4zODMiIHgyPSIxMDIuNjE3IiB4bGluazpocmVmPSIjTmV3X0dyYWRpZW50X1N3YXRjaF8xMyIgeTE9IjMxMC42MTciIHkyPSIyNjUuMzgzIi8+PGxpbmVhckdyYWRpZW50IGlkPSJOZXdfR3JhZGllbnRfU3dhdGNoXzIyIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjM3MS4wNzciIHgyPSI0MjkuMDA2IiB5MT0iMTgwLjk2NSIgeTI9IjEyMy4wMzUiPjxzdG9wIG9mZnNldD0iLjAxMSIgc3RvcC1jb2xvcj0iI2RjZGNlMyIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2VkZWRmMSIvPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJOZXdfR3JhZGllbnRfU3dhdGNoXzE4IiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjM2OS44MzQiIHgyPSI0MjUuMjc4IiB5MT0iMTc5LjcyMiIgeTI9IjEyNC4yNzgiPjxzdG9wIG9mZnNldD0iLjAxNSIgc3RvcC1jb2xvcj0iIzgzODM5ZSIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2I4YjhjNyIvPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJOZXdfR3JhZGllbnRfU3dhdGNoXzE1IiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjE1Mi4wNjciIHgyPSIyNzIuNjY1IiB5MT0iMzI1LjY2MiIgeTI9IjIwNS4wNjQiPjxzdG9wIG9mZnNldD0iLjAxNSIgc3RvcC1jb2xvcj0iI2NhY2FkNSIvPjxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iI2RjZGNlMyIvPjwvbGluZWFyR3JhZGllbnQ+PGxpbmVhckdyYWRpZW50IGlkPSJOZXdfR3JhZGllbnRfU3dhdGNoXzE4LTIiIHgxPSI4Ni44MzkiIHgyPSIxODQuMjk2IiB4bGluazpocmVmPSIjTmV3X0dyYWRpZW50X1N3YXRjaF8xOCIgeTE9IjIxOS43ODQiIHkyPSIxMjIuMzI2Ii8+PGxpbmVhckdyYWRpZW50IGlkPSJOZXdfR3JhZGllbnRfU3dhdGNoXzIiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIiB4MT0iMjQwLjE5MyIgeDI9IjMyNy40NCIgeTE9IjIxMi4xNjMiIHkyPSIxMjQuOTE2Ij48c3RvcCBvZmZzZXQ9Ii4wMTEiIHN0b3AtY29sb3I9IiNmOGM3M2YiLz48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNmYWRhN2YiLz48L2xpbmVhckdyYWRpZW50PjxnIGlkPSJfMDctbW90b3JfYmlrZSIgZGF0YS1uYW1lPSIwNy1tb3RvciBiaWtlIj48ZyBpZD0iZ3JhZGllbnQiPjxjaXJjbGUgY3g9IjQzMiIgY3k9IjI4OCIgZmlsbD0idXJsKCNOZXdfR3JhZGllbnRfU3dhdGNoXzE0KSIgcj0iNjQiLz48cGF0aCBkPSJtNDMyIDM2NGE3NiA3NiAwIDEgMSA3Ni03NiA3Ni4wODUgNzYuMDg1IDAgMCAxIC03NiA3NnptMC0xMjhhNTIgNTIgMCAxIDAgNTIgNTIgNTIuMDU5IDUyLjA1OSAwIDAgMCAtNTItNTJ6IiBmaWxsPSIjNjA2MDgyIi8+PGNpcmNsZSBjeD0iNDMyIiBjeT0iMjg4IiBmaWxsPSJ1cmwoI05ld19HcmFkaWVudF9Td2F0Y2hfMTMpIiByPSIzMiIvPjxjaXJjbGUgY3g9IjgwIiBjeT0iMjg4IiBmaWxsPSJ1cmwoI05ld19HcmFkaWVudF9Td2F0Y2hfMTQtMikiIHI9IjY0Ii8+PHBhdGggZD0ibTgwIDM2NGE3NiA3NiAwIDEgMSA3Ni03NiA3Ni4wODUgNzYuMDg1IDAgMCAxIC03NiA3NnptMC0xMjhhNTIgNTIgMCAxIDAgNTIgNTIgNTIuMDU5IDUyLjA1OSAwIDAgMCAtNTItNTJ6IiBmaWxsPSIjNjA2MDgyIi8+PGNpcmNsZSBjeD0iODAiIGN5PSIyODgiIGZpbGw9InVybCgjTmV3X0dyYWRpZW50X1N3YXRjaF8xMy0yKSIgcj0iMzIiLz48cGF0aCBkPSJtNDE3IDExNmgtMjRhMzYgMzYgMCAwIDAgMCA3MmgyNGExMiAxMiAwIDAgMCAxMi0xMnYtNDhhMTIgMTIgMCAwIDAgLTEyLTEyeiIgZmlsbD0idXJsKCNOZXdfR3JhZGllbnRfU3dhdGNoXzIyKSIvPjxwYXRoIGQ9Im00MTcgMTE2aC0yNGEzNiAzNiAwIDAgMCAwIDcyaDI0IiBmaWxsPSJ1cmwoI05ld19HcmFkaWVudF9Td2F0Y2hfMTgpIi8+PHBhdGggZD0ibTgwIDMwMGExMi4wMDggMTIuMDA4IDAgMCAxIC0xMS4zODUtMTUuOGwzMi05NmExMiAxMiAwIDAgMSAyMi43NjggNy41OWwtMzIgOTZhMTIgMTIgMCAwIDEgLTExLjM4MyA4LjIxeiIgZmlsbD0iI2RjZGNlMyIvPjxwYXRoIGQ9Im0zMDcuNCAyNDMuOTdjLS4yOTQtLjAzLS43MjgtLjA2Ny0xLjI4OS0uMTE2LTM1LjUyNC0zLjA1NC01NC4xMTEtMTIuMzAyLTU0LjExMS0xOS44NTR2LTMyYTEyIDEyIDAgMCAwIC0xMi0xMmgtOTZhMTIgMTIgMCAwIDAgLTUuMzY3IDIyLjczM2MuMjU0LjEyNyAyNS4zNjcgMTIuOTY3IDI1LjM2NyAzNy4yNjcgMCAxNi45LTIuNSAzMS41NTEtNi4zNzUgMzcuMzI4LTkuODc1IDE0LjczMS0xMS4wMzIgMzMuMjEtMy4wMiA0OC4yMjggNy40OTUgMTQuMDUzIDIxLjY5NSAyMi40NDQgMzcuOTc1IDIyLjQ0NGg4MC40MmMxMC41IDAgMjkuNDY2LTQuMiAzOC41NDQtMzIuMzEyYTEwMC42OCAxMDAuNjggMCAwIDAgNC40NTYtMjcuNjg4di0yNC41NzNhMTIuNSAxMi41IDAgMCAwIDIuNjA5LTYuOTQ0IDEyLjA4NyAxMi4wODcgMCAwIDAgLTExLjIwOS0xMi41MTN6IiBmaWxsPSJ1cmwoI05ld19HcmFkaWVudF9Td2F0Y2hfMTUpIi8+PHBhdGggZD0ibTMwNC45ODkgMjY4YTEyIDEyIDAgMCAxIC05Ljk3My0xOC42NTdsMzItNDhhMTIgMTIgMCAwIDEgMTkuOTY4IDEzLjMxMmwtMzIgNDhhMTEuOTg2IDExLjk4NiAwIDAgMSAtOS45OTUgNS4zNDV6IiBmaWxsPSIjOTU5NWFjIi8+PHBhdGggZD0ibTMzNyAyNjhhMTEuOTU4IDExLjk1OCAwIDAgMSAtOC40ODUtMy41MTVsLTMyLTMyYTEyIDEyIDAgMCAxIDE2Ljk3LTE2Ljk3bDMyIDMyYTEyIDEyIDAgMCAxIC04LjQ4NSAyMC40ODV6IiBmaWxsPSIjZDk3Nzc5Ii8+PHBhdGggZD0ibTM1MyAyMzZhMTEuOTU4IDExLjk1OCAwIDAgMSAtOC40ODUtMy41MTVsLTMyLTMyYTEyIDEyIDAgMCAxIDE2Ljk3LTE2Ljk3bDMyIDMyYTEyIDEyIDAgMCAxIC04LjQ4NSAyMC40ODV6IiBmaWxsPSIjZDk3Nzc5Ii8+PHBhdGggZD0ibTI0MCAzMDBoLTE5MmExMiAxMiAwIDAgMSAwLTI0aDE5MmM1MC4zMTQgMCA1Mi0xLjk3OSA1Mi0yMGExMiAxMiAwIDAgMSAyNCAwYzAgMTMuMDY3LTIuMzI0IDMxLjM0NS0yMi40MiAzOS4xOC0xMi4zNjIgNC44Mi0yOC43NTkgNC44Mi01My41OCA0LjgyeiIgZmlsbD0iIzk1OTVhYyIvPjxwYXRoIGQ9Im0yNTIgMjI0di04aC05MmExMS45NTUgMTEuOTU1IDAgMCAwIC00LjUuODhjNC42NzUgNS45MTcgOC41IDEzLjY0MiA4LjUgMjMuMTJoOTJhMTEuOTcxIDExLjk3MSAwIDAgMCA5LjM4NC00LjUzM2MtOC44NTEtMy42MDQtMTMuMzg0LTcuNzQxLTEzLjM4NC0xMS40Njd6IiBmaWxsPSIjODM4MzllIi8+PHBhdGggZD0ibTExNCAxNDRjLTMwLjQ2MyAwLTYxLjk3My0xLjQ5LTYyIDM1Ljk1MWExMi4wNDQgMTIuMDQ0IDAgMCAwIDEyIDEyLjA0OWgxMzdhMTIgMTIgMCAwIDAgMTItMTJ2LTI0YTEyIDEyIDAgMCAwIC0xMi0xMnoiIGZpbGw9InVybCgjTmV3X0dyYWRpZW50X1N3YXRjaF8xOC0yKSIvPjxwYXRoIGQ9Im0yNzMgMTIwYy0yNC4xMjggMC00Mi44NTYgMTAuODc5LTUyLjczMyAzMC42MzNhNzEuMSA3MS4xIDAgMCAwIC03LjI2NyAyOS4zNjcgMTIgMTIgMCAwIDAgMTIgMTJoMTE2YTEyIDEyIDAgMCAwIDEyLTEydi0xNmMwLTIxLjI3MS0yMS4wMTYtNDQtODAtNDR6IiBmaWxsPSJ1cmwoI05ld19HcmFkaWVudF9Td2F0Y2hfMikiLz48cGF0aCBkPSJtNDMyLjAxMSAzMDBhMTIgMTIgMCAwIDEgLTEwLjU0Ni02LjI1NmwtOTYtMTc2YTEyIDEyIDAgMCAxIDIxLjA3LTExLjQ5Mmw5NiAxNzZhMTIgMTIgMCAwIDEgLTEwLjUyNCAxNy43NDh6IiBmaWxsPSIjZGNkY2UzIi8+PC9nPjwvZz48L3N2Zz4=', 
              iconSize: [40, 40],
            });

            var building = L.icon({
              iconUrl: 'data:image/svg+xml;base64,PHN2ZyBoZWlnaHQ9IjUxMnB0IiB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgd2lkdGg9IjUxMnB0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Im0yMjQgMGgyODh2NTEyaC0yODh6bTAgMCIgZmlsbD0iI2ZmOGE2NSIvPjxwYXRoIGQ9Im0yMjQgMTkyaDIyNHYzMjBoLTIyNHptMCAwIiBmaWxsPSIjZmY3MDQzIi8+PHBhdGggZD0ibTE5MiAyMjRoMjI0djI4OGgtMjI0em0wIDAiIGZpbGw9IiNmOGU4NjgiLz48cGF0aCBkPSJtMjI0IDUxMmgtMjI0di0yODhsMTEyLTgwIDExMiA4MHptMCAwIiBmaWxsPSIjYWVkNTgxIi8+PHBhdGggZD0ibTY0IDQwMGg5NnYxMTJoLTk2em0wIDAiIGZpbGw9IiM3Y2IzNDIiLz48cGF0aCBkPSJtMjU2IDQwMGg5NnYxMTJoLTk2em0wIDAiIGZpbGw9IiNlMGM5MzgiLz48ZyBmaWxsPSIjZmZmIj48cGF0aCBkPSJtMjU2IDMzNmgzMnYzMmgtMzJ6bTAgMCIvPjxwYXRoIGQ9Im0zMjAgMzM2aDMydjMyaC0zMnptMCAwIi8+PHBhdGggZD0ibTI1NiAyNzJoMzJ2MzJoLTMyem0wIDAiLz48cGF0aCBkPSJtMzIwIDI3MmgzMnYzMmgtMzJ6bTAgMCIvPjxwYXRoIGQ9Im02NCAzMzZoMzJ2MzJoLTMyem0wIDAiLz48cGF0aCBkPSJtMTI4IDMzNmgzMnYzMmgtMzJ6bTAgMCIvPjxwYXRoIGQ9Im02NCAyNzJoMzJ2MzJoLTMyem0wIDAiLz48cGF0aCBkPSJtMTI4IDI3MmgzMnYzMmgtMzJ6bTAgMCIvPjxwYXRoIGQ9Im05NiAyMDhoMzJ2MzJoLTMyem0wIDAiLz48cGF0aCBkPSJtMjg4IDgwaDMydjMyaC0zMnptMCAwIi8+PHBhdGggZD0ibTM1MiA4MGgzMnYzMmgtMzJ6bTAgMCIvPjxwYXRoIGQ9Im00MTYgODBoMzJ2MzJoLTMyem0wIDAiLz48cGF0aCBkPSJtMjg4IDE0NGgzMnYzMmgtMzJ6bTAgMCIvPjxwYXRoIGQ9Im0zNTIgMTQ0aDMydjMyaC0zMnptMCAwIi8+PHBhdGggZD0ibTQxNiAxNDRoMzJ2MzJoLTMyem0wIDAiLz48L2c+PC9zdmc+', 
              iconSize: [40, 40],
            });

            var prasarana = L.icon({
              iconUrl: 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDUwMSA1MDEiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUwMSA1MDE7IiB4bWw6c3BhY2U9InByZXNlcnZlIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiI+CjxnPgoJPHBhdGggc3R5bGU9ImZpbGw6IzhBRThGNTsiIGQ9Ik0yNTAuNSwxMWMxMzUuMzEsMCwyNDUsMTA5LjY5LDI0NSwyNDVzLTEwOS42OSwyNDUtMjQ1LDI0NVM1LjUsMzkxLjMxLDUuNSwyNTZTMTE1LjE5LDExLDI1MC41LDExeiAgICIvPgoJPHBhdGggc3R5bGU9ImZpbGw6IzBFQkNENzsiIGQ9Ik00MTQuNSwxMjVjMCwxMy44MDctMTEuMTkzLDI1LTI1LDI1cy0yNS0xMS4xOTMtMjUtMjVjMC0xMS4zMjQsNy41MzEtMjAuODg0LDE3Ljg1Ni0yMy45NTggICBDMzc5LjA2Myw5NC41MDUsMzcyLjMwNSw5MCwzNjQuNSw5MGMtMTEuMDI4LDAtMjAsOC45NzItMjAsMjB2MzQwaC0yMFYxMTBjMC0xMS4wMjgtOC45NzItMjAtMjAtMjAgICBjLTcuODA1LDAtMTQuNTYzLDQuNTA1LTE3Ljg1NiwxMS4wNDJDMjk2Ljk2OSwxMDQuMTE2LDMwNC41LDExMy42NzYsMzA0LjUsMTI1YzAsMTMuODA3LTExLjE5MywyNS0yNSwyNXMtMjUtMTEuMTkzLTI1LTI1ICAgYzAtOC4zMzYsNC4wODQtMTUuNzE1LDEwLjM1Ni0yMC4yNTdDMjY3LjQ0LDg1LjE2NCwyODQuMjI3LDcwLDMwNC41LDcwYzExLjkzOCwwLDIyLjY2NSw1LjI2NCwzMCwxMy41ODUgICBDMzQxLjgzNSw3NS4yNjQsMzUyLjU2Miw3MCwzNjQuNSw3MGMyMC4yNzMsMCwzNy4wNiwxNS4xNjQsMzkuNjQ0LDM0Ljc0M0M0MTAuNDE2LDEwOS4yODUsNDE0LjUsMTE2LjY2NCw0MTQuNSwxMjV6Ii8+Cgk8cGF0aCBzdHlsZT0iZmlsbDojMjRCRDg3OyIgZD0iTTM1NS41NDIsNDc3LjM5Yy0xOS42NTctNS41MjItMzUuODEzLTE5LjQwNS00NC4zODEtMzcuNTc4QzMwNC4wODQsNDQ2LjE0NSwyOTQuNzQ0LDQ1MCwyODQuNSw0NTAgICBjLTExLjkxNiwwLTIyLjYwOS01LjIxNy0yOS45MzctMTMuNDg0QzI0NC42MTcsNDUwLjcxLDIyOC4xNDgsNDYwLDIwOS41LDQ2MGMtMzAuMzc2LDAtNTUtMjQuNjI1LTU1LTU1czI0LjYyNC01NSw1NS01NSAgIGMyMS4yNjcsMCwzOS43MDIsMTIuMDc5LDQ4Ljg1MywyOS43NDNjNy4wMTMtNi4wNjUsMTYuMTQ3LTkuNzQzLDI2LjE0Ny05Ljc0M2MxMC4yNDQsMCwxOS41ODQsMy44NTUsMjYuNjYsMTAuMTg4ICAgQzMyMi4zNTgsMzU2LjQ0LDM0Ni41MDQsMzQwLDM3NC41LDM0MGMzNy4yNDksMCw2Ny42OSwyOS4wOTcsNjkuODY0LDY1LjgwMUM0MjAuOTA4LDQzNi4xMTEsMzkwLjUwOCw0NjAuNzcsMzU1LjU0Miw0NzcuMzl6Ii8+Cgk8cGF0aCBzdHlsZT0iZmlsbDojNUNDODk5OyIgZD0iTTI1NC40ODksMzMzLjM3OGMwLjg3NSw2My4yNjItNTAuMjk2LDExNS44NDQtMTEzLjU2LDExNi42MTMgICBjLTIwLjYyOCwwLjI1MS00MC4wMjMtNC45MzYtNTYuODQ5LTE0LjIwOWMtMjQuNDYyLTIyLjY1NS00NC4yODktNTAuMjM3LTU3LjkwNy04MS4xNzRDMjUuMDc3LDM0OC4yMzUsMjQuNSwzNDEuNjg2LDI0LjUsMzM1ICAgYzAtMzUuODc2LDE2LjQzMS02Ny45MTIsNDIuMTc1LTg5LjAwMkM1Mi44MzcsMjI5LjQ5NSw0NC41LDIwOC4yMjIsNDQuNSwxODVjMC0xOC43NzYsNS40NDctMzYuMjgsMTQuODQ4LTUxLjAxOCAgIGMxMS45OTItMTguOCwxNy4zOTctNDAuOTI4LDE1LjQxMi02My4xMzlDNzQuNTg4LDY4LjkxOSw3NC41LDY2Ljk3LDc0LjUsNjVjMC0zNi4yMzcsMjkuNjU0LTY1LjU0OSw2Ni4wMTktNjQuOTkyICAgYzM0LjQ5NSwwLjUyOCw2Mi44NTcsMjguNDA4LDYzLjk0OCw2Mi44OWMwLjA4OCwyLjc3MywwLjAwMSw1LjUwOC0wLjI0OSw4LjE5NWMtMi4wNjIsMjIuMTE3LDMuNDg4LDQ0LjE2LDE1LjQzNCw2Mi44ODcgICBjOS40MDEsMTQuNzM4LDE0Ljg0OSwzMi4yNDMsMTQuODQ5LDUxLjAyYzAsMjMuMjIyLTguMzM3LDQ0LjQ5NS0yMi4xNzUsNjAuOTk4QzIzNy42ODEsMjY2Ljc3LDI1NC4wMDIsMjk4LjE1OSwyNTQuNDg5LDMzMy4zNzh6Ii8+Cgk8cGF0aCBzdHlsZT0iZmlsbDojMjRCRDg3OyIgZD0iTTEyNC4wOCw0MzUuNzgyYzEwLjg0Niw1Ljk3NywyMi43NjIsMTAuMjQ0LDM1LjM4NCwxMi40NjRjLTYuMDMyLDEuMDY3LTEyLjIyMiwxLjY2OC0xOC41MzUsMS43NDUgICBjLTIwLjYyOCwwLjI1MS00MC4wMjMtNC45MzYtNTYuODQ5LTE0LjIwOWMtMjQuNDYyLTIyLjY1NS00NC4yODktNTAuMjM3LTU3LjkwNy04MS4xNzRDMjUuMDc3LDM0OC4yMzUsMjQuNSwzNDEuNjg2LDI0LjUsMzM1ICAgYzAtMzUuODc2LDE2LjQzMS02Ny45MTIsNDIuMTc1LTg5LjAwMkM1Mi44MzcsMjI5LjQ5NSw0NC41LDIwOC4yMjIsNDQuNSwxODVjMC0xOC43NzYsNS40NDctMzYuMjgsMTQuODQ4LTUxLjAxOCAgIGMxMS45ODItMTguNzg2LDE3LjQyNC00MC44ODMsMTUuNDE4LTYzLjA3NEM3NC41OSw2OC45NjMsNzQuNSw2Ni45OTIsNzQuNSw2NWMwLTM2LjIzNywyOS42NTQtNjUuNTQ5LDY2LjAxOS02NC45OTIgICBjNi41OSwwLjEwMSwxMi45NTIsMS4yMDcsMTguOTQ3LDMuMTU5Yy0xOS4yMTgsNi4yMjMtMzQuNTE0LDIxLjE0Ny00MS4yNDEsNDAuMTI5Yy0xLjE1OCwzLjI2NywxLjI0Miw2LjcwNCw0LjcwOCw2LjcwNEgxMzQuNSAgIGM1Ljc0MywwLDEwLjM1NSw0Ljg0MSw5Ljk3OCwxMC42NjVDMTQ0LjEzNCw2NS45ODEsMTM5LjQ4NCw3MCwxMzQuMTU4LDcwSDEyMGMtMi44NjEsMC01LjEzNSwyLjM5NS00Ljk5NCw1LjI1MiAgIGMwLjA4NiwxLjc0OSwwLjE1NywzLjU1NCwwLjE1NSw0Ljc1M2MtMC4wMywxOS4wOC01LjQ3MywzNy43NjUtMTUuODE0LDUzLjk3N0M4OS45NDcsMTQ4LjcyLDg0LjUsMTY2LjIyNCw4NC41LDE4NSAgIGMwLDIzLjIyMiw4LjMzNyw0NC40OTUsMjIuMTc1LDYwLjk5OEM4MC45MzEsMjY3LjA4OCw2NC41LDI5OS4xMjQsNjQuNSwzMzVjMCw2LjY4NiwwLjU3NywxMy4yMzUsMS42NzIsMTkuNjA4ICAgQzc5Ljc5MSwzODUuNTQ1LDk5LjYxNyw0MTMuMTI3LDEyNC4wOCw0MzUuNzgyeiBNMTU0LjUsMTAwYzAsNS41MjMsNC40NzcsMTAsMTAsMTBoMTBjNS41MjMsMCwxMC00LjQ3NywxMC0xMHMtNC40NzctMTAtMTAtMTBoLTEwICAgQzE1OC45NzcsOTAsMTU0LjUsOTQuNDc3LDE1NC41LDEwMHogTTE3NC41LDE0MGgtMjBjLTUuNTIzLDAtMTAsNC40NzctMTAsMTBzNC40NzcsMTAsMTAsMTBoMjBjNS41MjMsMCwxMC00LjQ3NywxMC0xMCAgIFMxODAuMDIzLDE0MCwxNzQuNSwxNDB6IE0xMTQuNSwxNjBjLTUuNTIzLDAtMTAsNC40NzctMTAsMTBzNC40NzcsMTAsMTAsMTBoMTBjNS41MjMsMCwxMC00LjQ3NywxMC0xMHMtNC40NzctMTAtMTAtMTBIMTE0LjV6Ii8+Cgk8cGF0aCBzdHlsZT0iZmlsbDojMDI5QTY0OyIgZD0iTTExMC43MTEsMjUyLjEwNWMwLDAuNjk1LTAuMDM1LDEuMzgxLTAuMDUyLDIuMDcyYzQ0LjQyMiwxNy41OSw3NS44NDIsNjAuOTMxLDc1Ljg0MiwxMTEuNjEyICAgYzAsNDMuNTcyLTIzLjIyNCw4MS43MTgtNTcuOTY4LDEwMi43NDFDNTUuMDA4LDQyNi4yNDUsNS41LDM0Ni45MDQsNS41LDI1NmMwLTMwLjEyNCw1LjQ0OS01OC45NzIsMTUuMzk1LTg1LjYyOCAgIGMyLjU0LTAuMjM3LDUuMTA5LTAuMzcyLDcuNzExLTAuMzcyQzczLjk1MSwxNzAsMTEwLjcxMSwyMDYuNzYsMTEwLjcxMSwyNTIuMTA1eiIvPgoJPHBhdGggc3R5bGU9ImZpbGw6IzAxODY1NTsiIGQ9Ik03OC4wMzYsNDMwaDM0NC45MjhjLTQ0LjI3NCw0My44ODYtMTA1LjIsNzEtMTcyLjQ2NCw3MVMxMjIuMzEsNDczLjg4Niw3OC4wMzYsNDMweiIvPgoJPHBhdGggc3R5bGU9ImZpbGw6I0ZGQkQ2MTsiIGQ9Ik00NTUsMjY1djEyNS45NDNjLTEyLjAzMywxOC4xOTktMjYuNDI3LDM0LjY5Ni00Mi43NSw0OS4wNTdIMTQ1djM3LjE3NSAgIGMtMTEuMTU5LTUuMzMzLTIxLjg1Mi0xMS40ODMtMzItMTguMzc2VjI2NWMwLTguMjg0LDYuNzE2LTE1LDE1LTE1aDJjOC4yODQsMCwxNSw2LjcxNiwxNSwxNXYxNTVoMjc4VjI2NWMwLTguMjg0LDYuNzE2LTE1LDE1LTE1ICAgaDJDNDQ4LjI4NCwyNTAsNDU1LDI1Ni43MTYsNDU1LDI2NXogTTI4MywzODBoMmM4LjI4NCwwLDE1LTYuNzE2LDE1LTE1VjI2NWMwLTguMjg0LTYuNzE2LTE1LTE1LTE1aC0yYy04LjI4NCwwLTE1LDYuNzE2LTE1LDE1ICAgdjEwMEMyNjgsMzczLjI4NCwyNzQuNzE2LDM4MCwyODMsMzgweiIvPgoJPHBhdGggc3R5bGU9ImZpbGw6I0Q0MzE0NzsiIGQ9Ik00NTksMzIwSDEwOWMtOC4yODQsMC0xNS02LjcxNi0xNS0xNXYtOTBjMC04LjI4NCw2LjcxNi0xNSwxNS0xNWgzNTBjOC4yODQsMCwxNSw2LjcxNiwxNSwxNXY5MCAgIEM0NzQsMzEzLjI4NCw0NjcuMjg0LDMyMCw0NTksMzIweiBNNDUzLjUsMzg1di0xMGMwLTguMjg0LTYuNzE2LTE1LTE1LTE1aC0zNTBjLTguMjg0LDAtMTUsNi43MTYtMTUsMTV2MTBjMCw4LjI4NCw2LjcxNiwxNSwxNSwxNSAgIGgzNTBDNDQ2Ljc4NCw0MDAsNDUzLjUsMzkzLjI4NCw0NTMuNSwzODV6Ii8+Cgk8cGF0aCBzdHlsZT0iZmlsbDojRUYzQzU0OyIgZD0iTTQ3OS41LDQwMGgtMzY3Yy04LjI4NCwwLTE1LTYuNzE2LTE1LTE1di0xMGMwLTguMjg0LDYuNzE2LTE1LDE1LTE1aDM2N2M4LjI4NCwwLDE1LDYuNzE2LDE1LDE1djEwICAgQzQ5NC41LDM5My4yODQsNDg3Ljc4NCw0MDAsNDc5LjUsNDAweiBNNDU5LDIwMEgxMzNjLTguMjg0LDAtMTUsNi43MTYtMTUsMTV2MTVjMCwxMS4wNDYsOC45NTQsMjAsMjAsMjBoMjY2LjE1OCAgIGM1LjMyNiwwLDkuOTc3LDQuMDE5LDEwLjMyMSw5LjMzNWMwLjM3Nyw1LjgyNC00LjIzNSwxMC42NjUtOS45NzgsMTAuNjY1SDEzOGMtMTEuMDQ2LDAtMjAsOC45NTQtMjAsMjB2MTVjMCw4LjI4NCw2LjcxNiwxNSwxNSwxNSAgIGgzMjZjOC4yODQsMCwxNS02LjcxNiwxNS0xNXYtOTBDNDc0LDIwNi43MTYsNDY3LjI4NCwyMDAsNDU5LDIwMHoiLz4KCTxwYXRoIHN0eWxlPSJmaWxsOiNGRkUyQkQ7IiBkPSJNNDQ5LjUsMjEwYzguMjg0LDAsMTUsNi43MTYsMTUsMTVzLTYuNzE2LDE1LTE1LDE1cy0xNS02LjcxNi0xNS0xNVM0NDEuMjE2LDIxMCw0NDkuNSwyMTB6ICAgIE00MzQuNSwyOTVjMCw4LjI4NCw2LjcxNiwxNSwxNSwxNXMxNS02LjcxNiwxNS0xNXMtNi43MTYtMTUtMTUtMTVTNDM0LjUsMjg2LjcxNiw0MzQuNSwyOTV6IE0xMDQuNSwyMjVjMCw4LjI4NCw2LjcxNiwxNSwxNSwxNSAgIHMxNS02LjcxNiwxNS0xNXMtNi43MTYtMTUtMTUtMTVTMTA0LjUsMjE2LjcxNiwxMDQuNSwyMjV6IE0xMDQuNSwyOTVjMCw4LjI4NCw2LjcxNiwxNSwxNSwxNXMxNS02LjcxNiwxNS0xNXMtNi43MTYtMTUtMTUtMTUgICBTMTA0LjUsMjg2LjcxNiwxMDQuNSwyOTV6Ii8+CjwvZz4KCgoKCgoKCgoKCgoKCgoKPC9zdmc+Cg==', 
              iconSize: [40, 40],
            });

            var other_asset = L.icon({
              iconUrl: 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE5LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPg0KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB2aWV3Qm94PSIwIDAgNTEyLjAwMiA1MTIuMDAyIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MTIuMDAyIDUxMi4wMDI7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxwYXRoIHN0eWxlPSJmaWxsOiNGRUVCNzA7IiBkPSJNNDc1LjYxLDUxMi4wMDFjLTMuOTg4LDAtOC4wMi0wLjY1OS0xMS45NTQtMi4wMTFsLTEwMi4yOTMtMzUuMTQ5DQoJYy0zNC4xNzUsMTYuOTA2LTcyLjI2NiwyNS44NDctMTEwLjU5NCwyNS45MDdjLTAuMTI4LDAtMC4yNjUsMC0wLjM5NCwwYy02NS44NiwwLjAwMS0xMjguMTA4LTI1LjUyNy0xNzUuMzMyLTcxLjkxMQ0KCUMyNy43MTgsMzgyLjM1NSwxLjA4LDMyMC40MTYsMC4wMzUsMjU0LjQzMWMtMC41NDctMzQuNTU0LDUuODE2LTY4LjExNywxOC45MTMtOTkuNzU3YzEyLjYzMi0zMC41MTcsMzAuOTMxLTU3Ljg4NCw1NC4zODgtODEuMzQNCgljMjMuNDU2LTIzLjQ1Niw1MC44MjMtNDEuNzU1LDgxLjM0LTU0LjM4OGMzMS42NC0xMy4wOTcsNjUuMTg3LTE5LjQ2Niw5OS43NTctMTguOTEyYzY1Ljk4NSwxLjA0NiwxMjcuOTI0LDI3LjY4NSwxNzQuNDA1LDc1LjAxDQoJYzQ2LjQ3Myw0Ny4zMTYsNzIuMDExLDEwOS43MjQsNzEuOTA5LDE3NS43MjdjLTAuMDYsMzguMzI4LTkuMDAxLDc2LjQxOS0yNS45MDcsMTEwLjU5M2wzNS4xNDksMTAyLjI5M2wwLDANCgljNC41NjQsMTMuMjgzLDEuMjI5LDI3LjcwMy04LjcwMSwzNy42MzRDNDk0LjMwMSw1MDguMjc5LDQ4NS4wODQsNTEyLjAwMSw0NzUuNjEsNTEyLjAwMXoiLz4NCjxwYXRoIHN0eWxlPSJmaWxsOiNGQ0I2Mjk7IiBkPSJNNDc0Ljg0MSwzNjEuMzYzYzE2LjkwNi0zNC4xNzMsMjUuODQ3LTcyLjI2NSwyNS45MDctMTEwLjU5Mg0KCWMwLjEwMi02Ni4wMDItMjUuNDM1LTEyOC40MS03MS45MDktMTc1LjcyN0MzODIuMzU3LDI3LjcxOSwzMjAuNDE4LDEuMDgxLDI1NC40MzMsMC4wMzRjLTEuMzU1LTAuMDIxLTIuNzA2LTAuMDE0LTQuMDU5LTAuMDE0DQoJdjUwMC43MjljMC4xMjksMCwwLjI2NSwwLDAuMzk0LDBjMzguMzI4LTAuMDU5LDc2LjQxOS05LDExMC41OTQtMjUuOTA3bDEwMi4yOTMsMzUuMTQ5YzMuOTM0LDEuMzUyLDcuOTY2LDIuMDExLDExLjk1NCwyLjAxMQ0KCWM5LjQ3NSwwLDE4LjY5LTMuNzIzLDI1LjY3OS0xMC43MTJjOS45MzEtOS45MzEsMTMuMjY1LTI0LjM1MSw4LjcwMS0zNy42MzRMNDc0Ljg0MSwzNjEuMzYzeiIvPg0KPGc+DQoJPHBhdGggc3R5bGU9ImZpbGw6I0ZGNTJBODsiIGQ9Ik0yNDcuOTExLDM5My4xMDRjNy45MzksMCwxNS41NDUtNi45ODEsMTUuMTc4LTE1LjE3OGMtMC4zNjgtOC4yMjMtNi42NjktMTUuMTc4LTE1LjE3OC0xNS4xNzgNCgkJYy03LjkzOSwwLTE1LjU0NSw2Ljk4MS0xNS4xNzgsMTUuMTc4QzIzMy4xMDEsMzg2LjE0OSwyMzkuNDAyLDM5My4xMDQsMjQ3LjkxMSwzOTMuMTA0TDI0Ny45MTEsMzkzLjEwNHoiLz4NCgk8cGF0aCBzdHlsZT0iZmlsbDojRkY1MkE4OyIgZD0iTTI0Ny45MTEsMzQwLjYwOWMtOC4zODIsMC0xNS4xNzgtNi43OTYtMTUuMTc4LTE1LjE3OHYtNjEuOTA1YzAtOC4zODIsNi43OTYtMTUuMTc4LDE1LjE3OC0xNS4xNzgNCgkJYzI0LjksMCw0NS4xNTktMjAuMjU5LDQ1LjE1OS00NS4xNTlzLTIwLjI1OS00NS4xNTktNDUuMTU5LTQ1LjE1OXMtNDUuMTU5LDIwLjI1OS00NS4xNTksNDUuMTU5DQoJCWMwLDguMzgyLTYuNzk2LDE1LjE3OC0xNS4xNzgsMTUuMTc4cy0xNS4xNzgtNi43OTYtMTUuMTc4LTE1LjE3OGMwLTQxLjYzOSwzMy44NzYtNzUuNTE1LDc1LjUxNS03NS41MTUNCgkJczc1LjUxNSwzMy44NzYsNzUuNTE1LDc1LjUxNWMwLDM2LjQ0Mi0yNS45NDcsNjYuOTM3LTYwLjMzNyw3My45ODJ2NDguMjU5QzI2My4wODksMzMzLjgxNCwyNTYuMjkzLDM0MC42MDksMjQ3LjkxMSwzNDAuNjA5eiIvPg0KPC9nPg0KPGc+DQoJPHBhdGggc3R5bGU9ImZpbGw6I0U1MzA0NTsiIGQ9Ik0yOTMuMDcsMjAzLjE5YzAsMjQuOS0yMC4yNTksNDUuMTU5LTQ1LjE1OSw0NS4xNTl2OTIuMjYxYzguMzgyLDAsMTUuMTc4LTYuNzk2LDE1LjE3OC0xNS4xNzgNCgkJdi00OC4yNTljMzQuMzg5LTcuMDQ1LDYwLjMzNy0zNy41NCw2MC4zMzctNzMuOTgyYzAtNDEuNjM5LTMzLjg3Ni03NS41MTUtNzUuNTE1LTc1LjUxNXYzMC4zNTYNCgkJQzI3Mi44MTEsMTU4LjAzMSwyOTMuMDcsMTc4LjI4OSwyOTMuMDcsMjAzLjE5eiIvPg0KCTxwYXRoIHN0eWxlPSJmaWxsOiNFNTMwNDU7IiBkPSJNMjYzLjA4OSwzNzcuOTI2Yy0wLjM2OC04LjIyMy02LjY2OS0xNS4xNzgtMTUuMTc4LTE1LjE3OHYzMC4zNTYNCgkJQzI1NS44NSwzOTMuMTA0LDI2My40NTYsMzg2LjEyMiwyNjMuMDg5LDM3Ny45MjZ6Ii8+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8L3N2Zz4NCg==', 
              iconSize: [40, 40],
            });

            var legend = L.control({position: 'bottomleft'});
            legend.onAdd = function (map_temp) {
              var div = L.DomUtil.create('div', 'legend');
              var labels = ['<strong>Status Kepemilikan/Penggunaan Tanah</strong>'];
              for (const [key, value] of Object.entries(tanah_map)) {
                div.innerHTML += 
                labels.push( 
                    '<i class="circle" style="background:'+ value.color +'"></i> ' + value.teks);
                    // + (value.text ? value.text : '+'));
              }
              div.innerHTML = labels.join('<br>');
              // console.log(div);
              return div;
            };
            legend.addTo(map_temp);

            L.easyPrint({
              title: 'Download Peta Sebagai Gambar',
              position: 'topleft',
              sizeModes: ['Current', 'A4Landscape', 'A4Portrait'],
              filename: 'gambar_peta',
              exportOnly: true,
              hideControlContainer: true
            }).addTo(map_temp);

            locations_temp.forEach(function (location, index) {
              var layerGroup = L.geoJSON(JSON.parse(location.geolocation_aset),
                { 
                  onEachFeature: function (feature, layer) {
                    images = location.foto_aset != null && (location.foto_aset).split(',').length > 0 ? (location.foto_aset).split(',') : [];
                    slideshowContent = ''
                    
                    if(images.length > 0){
                      // var galeries_temp = [];
                      for(var i = 0; i < images.length; i++) {
                        var img = refs.url_base +'/api/foto_aset/'+ images[i];
                        // galeries_temp.push({src: img});

                        slideshowContent += '<div class="image' + (i == 0 ? ' active' : '') + '">' +
                                              '<img src="' + img + '" style="max-width:500px;max-height:500px;" />' +
                                              '<div class="caption">' + images[i] + '</div>' +
                                            '</div>';
                      }

                      // console.log(slideshowContent)

                      var popupContent =  '<div id="' + location.label_aset + '" class="popup">' +
                                            '<h4>'+ location.label_aset+'</h4>' +
                                              '<div class="slideshow">' +
                                                  slideshowContent +
                                              '</div>' +
                                              '<div class="cycle">' +
                                                  '<a href="#" class="prev">&laquo; Previous</a>' +
                                                  '<a href="#" class="next">Next &raquo;</a>' +
                                              '</div>'
                                          '</div>';
                      // refs.galeries = [];
                      // refs.galeries = galeries_temp;
                      // var template = refs.$refs.foo.$el.innerHTML
                      // console.log(refs.galeries);
                      // layer.bindPopup(template);
                      $('#map').on('click', '.popup .cycle a', function() {
                        var $slideshow = $('.slideshow'),
                            $newSlide;

                        if ($(this).hasClass('prev')) {
                            $newSlide = $slideshow.find('.active').prev();
                            if ($newSlide.index() < 0) {
                                $newSlide = $('.image').last();
                            }
                        } else {
                            $newSlide = $slideshow.find('.active').next();
                            if ($newSlide.index() < 0) {
                                $newSlide = $('.image').first();
                            }
                        }

                        $slideshow.find('.active').removeClass('active').hide();
                        $newSlide.addClass('active').show();
                        return false;
                    });

                      layer.bindPopup(popupContent);
                    }else{
                      layer.bindPopup('<h3>'+location.label_aset+'</h3><p>Jenis Aset: '+location.jenis_aset+'</p></b><p>tahun: '+location.tahun_aset+'</p>');
                    }

                    if (feature.geometry.type == 'Polygon') {
                      layer.setStyle({
                        fillColor : tanah_map[(location.status_kepemilikan_tanah)].color,
                        fillOpacity: 0.5,
                        color: 'gray'
                      })
                    }else if(feature.geometry.type == 'LineString') {
                      layer.setStyle({
                        weight: 4,
                        dashArray: '5, 10',
                        lineCap: 'round', // Optional, just to avoid round borders.
                        color: '#ff3d00'
                      })
                      // console.log('ada polyline')
                    }else if(location.jenis_aset == 'mobil'){
                      layer.setIcon(car)
                    }else if(location.jenis_aset == 'motor'){
                      layer.setIcon(motor)
                    }else if(location.jenis_aset == 'public'){
                      layer.setIcon(prasarana)
                    }else if(location.jenis_aset == 'bangunan'){
                      layer.setIcon(building)
                    }else {
                      layer.setIcon(other_asset)
                    }
                  },
                  
                })
              layerGroup.addTo(map_temp);
            });
          });
          

        refs.map = map_temp;
        refs.loading = false;
      },
    },
    mounted () {
      this.initMap();
    },
    created (){
      axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      axios.get('/api/user-permission').then(response => {
          this.currentUser = response.data.user;
          this.$store.commit('updateRBAC', response.data.permissions)
          // if(!(response.data.permissions).includes('assets.read')){
          //   this.$router.push('/')
          // }
      }).catch(errors => {
          console.log(errors);
      }).finally(() => {
          this.permissions = this.$store.getters.rbac
      })
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

  >>>.popup {
   text-align:center;
  }
  >>>.popup .slideshow .image        { display:none; }
  >>>.popup .slideshow .image.active { display:block; }
  >>>.popup .slideshow img {
   width:100%;
  }
  >>>.popup .slideshow .caption {
    background:#eee;
    padding:5px;
  }
  >>>.popup .cycle {
    padding:10px 0 20px;
  }
  >>>.popup .cycle a.prev { float:left; }
  >>>.popup .cycle a.next { float:right; }
</style>