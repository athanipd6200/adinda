 <template>
    <v-app id="inspire">
        <v-overlay :value="overlay_logout" style="z-index:11000;">
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
            style="z-index:2010;min-width:400px;"
            color="tertiary lighten-2"
        >

            <v-list>
                <v-list-item two-line>
                    <v-list-item-avatar>
                        <!-- <img src="https://randomuser.me/api/portraits/women/81.jpg"> -->

                        <v-avatar color="primary darken-3">
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

                <v-list-item link to="/">
                    <v-list-item-action>
                        <v-icon>mdi-earth</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Halaman Muka Publik</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

                <!-- GROUPING MENU untuk PEMBINAAN -->
                <v-list-group
                    :value="false"
                    prepend-icon="mdi-cast-education"
                >
                    <template v-slot:activator>
                        <v-list-item-title>INOVASI</v-list-item-title>
                    </template>

                    <v-list-item style="margin-left:25px;" link to="/list-inovation">
                        <v-list-item-action>
                            <v-icon>mdi-monitor-dashboard</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Halaman Inovasi Publik</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item style="margin-left:25px;" link to="/list-inovation-crud" v-if="permissions.includes('inovations.create')">
                        <v-list-item-action>
                            <v-icon>mdi-cog-transfer-outline</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Pengaturan Inovasi</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item style="margin-left:25px;" link to="/list-inovation-verifikator" v-if="permissions.includes('inovations.verification')">
                        <v-list-item-action>
                            <v-icon>mdi-check-decagram</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Verifikasi Inovasi</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                </v-list-group>

                <!-- GROUPING MENU untuk MATERI -->
                <v-list-group
                    :value="false"
                    prepend-icon="mdi-newspaper-variant-multiple"
                >
                    <template v-slot:activator>
                        <v-list-item-title>ARTIKEL</v-list-item-title>
                    </template>

                    <v-list-item style="margin-left:25px;" link to="/list-artikel">
                        <v-list-item-action>
                            <v-icon>mdi-monitor-dashboard</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Halaman Artikel</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item style="margin-left:25px;" link to="/list-news" v-if="permissions.includes('articles.create')">
                        <v-list-item-action>
                            <v-icon>mdi-cog-transfer-outline</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Pengaturan Artikel</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item style="margin-left:25px;" link to="/list-news-verifikator" v-if="permissions.includes('articles.create')">
                        <v-list-item-action>
                            <v-icon>mdi-check-decagram</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Verifikasi Artikel</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>

                <!-- GROUPING MENU untuk INFOGRAFIS -->
                <v-list-group
                    :value="false"
                    prepend-icon="mdi-folder-multiple-image"
                >
                    <template v-slot:activator>
                        <v-list-item-title>INFOGRAFIS</v-list-item-title>
                    </template>

                    <v-list-item style="margin-left:25px;" link to="/list-infografis">
                        <v-list-item-action>
                            <v-icon>mdi-monitor-dashboard</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Halaman Infografis</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item style="margin-left:25px;" link to="/list-infografis-crud" v-if="permissions.includes('articles.create')">
                        <v-list-item-action>
                            <v-icon>mdi-cog-transfer-outline</v-icon>
                        </v-list-item-action>
                        <v-list-item-content>
                            <v-list-item-title>Pengaturan Infografis</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>

                <v-divider></v-divider>
                <v-list-item link to="/manajemen-user" v-if="permissions.includes('users.create')">
                    <v-list-item-action>
                        <v-icon>mdi-account-group</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Manajemen User</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="/manajemen-organisasi" v-if="roles.includes('SuperAdmin') || permissions.includes('users.update')">
                    <v-list-item-action>
                        <v-icon>mdi-domain</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Manajemen Organisasi</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link to="/profile">
                    <v-list-item-action>
                        <v-icon>mdi-account</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title>Profile</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-divider></v-divider>
                <v-list-item link @click="downloadPanduan">
                    <v-list-item-action>
                        <v-icon>mdi-book</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="black--text">Panduan</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item link @click="dialogLogout = true">
                    <v-list-item-action>
                        <v-icon color="red">mdi-power</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-list-item-title class="red--text">Log out</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
<!--             <v-footer class="justify-center pl-0 pb-0" app>
              <span>Anscorps © {{ new Date().getFullYear() }}</span>
            </v-footer>  -->
        </v-navigation-drawer>

        <v-app-bar
            app
            clipped-left
            color="secondary"
            style="z-index:2003"
        >
            <v-app-bar-nav-icon color="primary" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title color="primary" style="cursor: pointer" @click="$router.push('/')"><h3 class="black--text lighten-1--text">ADINDA - Aplikasi Dokumentasi Inovasi dan Artikel</h3></v-toolbar-title>

        </v-app-bar>
        <v-dialog v-model="dialogLogout" transition="dialog-bottom-transition" scrollable max-width="80vw" max-height="80vh" style="z-index:2099">
          <v-card
              color="red"
              dark
            >
            <v-card-title class="text-h5">
              Logout
            </v-card-title>

            <v-card-subtitle>Apakah yakin ingin logout?</v-card-subtitle>

            <v-card-actions class="justify-end">
              <v-btn
                text
                color="white"
                @click="logout"
              >Logout</v-btn>
              <v-btn
                text
                color="tertiary darken--4"
                @click="dialogLogout = false"
              >Close</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

        <v-main>
            <!-- <v-container> -->
                <router-view class="main-view" name="MainView"></router-view>
            <!-- </v-container> -->
        </v-main>

        <v-footer
        app
        style="z-index:2000">
            <span>Athan © {{ new Date().getFullYear() }}</span>
        </v-footer>
    </v-app>
</template>

<script>
    import axios from 'axios'
    export default {
        props: {
            source: String,
        },
        data(){
            return {
                drawer: false,
                dialogLogout: false,
                currentUser: {},
                isLoggedIn: this.$store.getters.isLogged,
                token: this.$store.getters.token,
                url_base: this.$store.getters.url_base,
                permissions: [],
                roles: [],
                overlay_logout:false,
            }
        },
        methods: {
            async getUser(){
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                await axios.get('/api/user-permission').then(response => {
                    this.currentUser = response.data.user;
                    this.roles = response.data.roles;
                    this.$store.commit('updateRBAC', response.data.permissions)
                }).catch(errors => {
                    console.log(errors);
                }).finally(() => {
                    this.permissions = this.$store.getters.rbac
                })
            },
            async logout(){
                // fungsi logout
                this.overlay_logout = true
                await axios.post('/api/logout').then(response => {
                    localStorage.removeItem('token');
                    this.$router.push('/login');
                }).catch( errors => {
                    console.log(errors);
                })
                this.overlay_logout = false
            },
            async downloadPanduan(){
                this.overlay_logout = true
                var link = '/api/other/panduan.pdf'
                var file_name = 'panduan.pdf'
                var tipe_file = file_name.split('.').slice(-1)[0]
                await axios(link, {
                    method: 'GET',
                    responseType: 'blob' //Force to receive data in a Blob Format
                })
                .then(response => {
                //Create a Blob from the PDF Stream
                    var file;
                    if(tipe_file == 'pdf'){
                    file = new Blob([response.data], {type: 'application/pdf'});
                    }else if(tipe_file == 'jpg' || tipe_file == 'jpeg' || tipe_file == 'png'){
                        file = new Blob([response.data], {type: 'image/jpeg'});
                    }
                    //Build a URL from the file
                    const fileURL = URL.createObjectURL(file);
                    //Open the URL on new Window
                    window.open(fileURL);
                })
                .catch(error => {
                  console.log(error);
                });
                this.overlay_logout = false
            },
        },
        async created () {
            this.$vuetify.theme.light = true;
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            await this.getUser();

        },
    }
</script>
<style scoped>
#inspire {
  background: url('/other/pattern.png') !important;
  background-size: cover;
}
</style>