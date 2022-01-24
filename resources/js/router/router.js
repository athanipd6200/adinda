import Vue from 'vue';
import VueRouter from 'vue-router'

Vue.use(VueRouter)

// import Welcome from './../components/main/Welcome'
import Landing from './../components/main/Landing'
import Login from './../components/auth/Login'
import Register from './../components/auth/Register'
import AuthLayout from './../components/main/AuthLayout'
import Profile from './../components/main/Profil'
import ArtikelAdminCRUD from './../components/main/ArtikelAdminCRUD'
import ArtikelAdminVerifikator from './../components/main/ArtikelAdminVerifikator'
import ArtikelPublikMajemuk from './../components/main/ArtikelPublikMajemuk'
import ArtikelPublikTunggal from './../components/main/ArtikelPublikTunggal'
import InfografisAdminCRUD from './../components/main/InfografisAdminCRUD'
import InfografisPublikMajemuk from './../components/main/InfografisPublikMajemuk'
import InovasiAdminCRUD from './../components/main/InovasiAdminCRUD'
import InovasiAdminVerifikator from './../components/main/InovasiAdminVerifikator'
import InovasiPublikMajemuk from './../components/main/InovasiPublikMajemuk'
import InovasiPublikTunggal from './../components/main/InovasiPublikTunggal'
import ManajemenUser from './../components/main/ManajemenUser'
import ManajemenOrganisasi from './../components/main/ManajemenOrganisasi'
import PageNotFound from './../components/main/PageNotFound'
const routes = [
		{
			path: "/login",
			name: "Login",
			component: Login,
			meta: { auth : false, },
		},
        {
			path: "/register",
			name: "Register",
			component: Register,
			meta: { auth : true },
		},
		// {
		// 	path: "/",
		// 	name: "Welcome",
		// 	component: Welcome,
		// },
		{
			path: "/",
			name: "landing",
			component: Landing,
		},
		{
			path: "/list-artikel/:jenis_artikel?",
			name: "list-artikel",
			component: ArtikelPublikMajemuk,
		},
		{
			path: "/list-inovation/:jenis_inovation?",
			name: "list-inovation",
			component: InovasiPublikMajemuk,
		},
		{
			path: "/list-infografis/:jenis_infografis?",
			name: "list-infografis",
			component: InfografisPublikMajemuk,
		},
		{
			path: "/artikel/:id_artikel",
			name: "artikel",
			component: ArtikelPublikTunggal,
		},
		{
			path: "/inovation/:id_inovation",
			name: "inovation-publik",
			component: InovasiPublikTunggal,
		},
		{
			path: "/",
			name: "Main",
			component: AuthLayout,
			meta: { auth : true },
			children: [
	        {
	            path: '/profile',
	            name: 'profile',
	            meta: { auth : true },
	            components: {
	            	default: AuthLayout,
	            	MainView: Profile
	            } 
	        },
	        {
	            path: '/list-news',
	            name: 'list-news',
	            meta: { auth : true },
	            components: {
	            	default: AuthLayout,
	            	MainView: ArtikelAdminCRUD
	            } 
	        },
			{
	            path: '/list-news-verifikator',
	            name: 'list-news-verifikator',
	            meta: { auth : true },
	            components: {
	            	default: AuthLayout,
	            	MainView: ArtikelAdminVerifikator
	            } 
	        },
	        {
	            path: '/manajemen-user',
	            name: 'manajemen-user',
	            meta: { auth : true },
	            components: {
	            	default: AuthLayout,
	            	MainView: ManajemenUser
	            } 
	        },
			{
	            path: '/manajemen-organisasi',
	            name: 'manajemen-organisasi',
	            meta: { auth : true },
	            components: {
	            	default: AuthLayout,
	            	MainView: ManajemenOrganisasi
	            } 
	        },
	        {
	            path: '/list-infografis-crud',
	            name: 'list-infografis-crud',
	            meta: { auth : true },
	            components: {
	            	default: AuthLayout,
	            	MainView: InfografisAdminCRUD
	            } 
	        },
	        {
	            path: '/list-inovation-crud',
	            name: 'list-inovation-crud',
	            meta: { auth : true },
	            components: {
	            	default: AuthLayout,
	            	MainView: InovasiAdminCRUD
	            } 
	        },
			{
	            path: '/list-inovation-verifikator',
	            name: 'list-inovation-verifikator',
	            meta: { auth : true },
	            components: {
	            	default: AuthLayout,
	            	MainView: InovasiAdminVerifikator
	            } 
	        },
			]
		},
		{ path: '*', component: PageNotFound },
		
    ]

const router = new VueRouter({
	mode: "history",
	base: process.env.BASE_URL,
	routes
})

router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.getItem('token')

  if (to.matched.some(record => record.meta.auth) && !loggedIn) {
    next('/login')
    return
  }
  next()
})

export default router;