import Vue from 'vue';
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import IndexMain from './../components/main/IndexMain'
// import Welcome from './../components/main/Welcome'
import PageNotFound from './../components/main/PageNotFound'
import Login from './../components/auth/Login'
import Register from './../components/auth/Register'
import Profile from './../components/main/AccountData'
import ListNews from './../components/main/ListNews'
import ListNewsVerifikator from './../components/main/ListNewsVerifikator'
import ListArtikelPublik from './../components/main/ListArtikelPublik'
import ArtikelPublik from './../components/main/ArtikelPublik'
import ManajemenUser from './../components/main/ManajemenUser'
import ListInfografis from './../components/main/ListInfografis'
import ListInfografisPublik from './../components/main/ListInfografisPublik'
import InfografisPublik from './../components/main/InfografisPublik'
import ListInovation from './../components/main/ListInovation'
import ListInovationPublik from './../components/main/ListInovationPublik'
import ListInovationVerifikator from './../components/main/ListInovationVerifikator'
import InovationPublik from './../components/main/InovationPublik'
import InovationDetail from './../components/main/InovationDetail'
import Landing from './../components/main/Landing'
import ManajemenOrganisasi from './../components/main/ManajemenOrganisasi'

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
			component: ListArtikelPublik,
		},
		{
			path: "/list-inovation/:jenis_inovation?",
			name: "list-inovation",
			component: ListInovationPublik,
		},
		{
			path: "/list-infografis/:jenis_infografis?",
			name: "list-infografis",
			component: ListInfografisPublik,
		},
		{
			path: "/artikel/:id_artikel",
			name: "artikel",
			component: ArtikelPublik,
		},
		{
			path: "/inovation/:id_inovation",
			name: "inovation-publik",
			component: InovationPublik,
		},
		{
			path: "/",
			name: "Main",
			component: IndexMain,
			meta: { auth : true },
			children: [
	        {
	            path: '/profile',
	            name: 'profile',
	            meta: { auth : true },
	            components: {
	            	default: IndexMain,
	            	MainView: Profile
	            } 
	        },
	        {
	            path: '/list-news',
	            name: 'list-news',
	            meta: { auth : true },
	            components: {
	            	default: IndexMain,
	            	MainView: ListNews
	            } 
	        },
			{
	            path: '/list-news-verifikator',
	            name: 'list-news-verifikator',
	            meta: { auth : true },
	            components: {
	            	default: IndexMain,
	            	MainView: ListNewsVerifikator
	            } 
	        },
	        {
	            path: '/manajemen-user',
	            name: 'manajemen-user',
	            meta: { auth : true },
	            components: {
	            	default: IndexMain,
	            	MainView: ManajemenUser
	            } 
	        },
			{
	            path: '/manajemen-organisasi',
	            name: 'manajemen-organisasi',
	            meta: { auth : true },
	            components: {
	            	default: IndexMain,
	            	MainView: ManajemenOrganisasi
	            } 
	        },
	        {
	            path: '/list-infografis-crud',
	            name: 'list-infografis-crud',
	            meta: { auth : true },
	            components: {
	            	default: IndexMain,
	            	MainView: ListInfografis
	            } 
	        },
	        {
	            path: '/list-inovation-crud',
	            name: 'list-inovation-crud',
	            meta: { auth : true },
	            components: {
	            	default: IndexMain,
	            	MainView: ListInovation
	            } 
	        },
			{
	            path: '/list-inovation-verifikator',
	            name: 'list-inovation-verifikator',
	            meta: { auth : true },
	            components: {
	            	default: IndexMain,
	            	MainView: ListInovationVerifikator
	            } 
	        },
			{
	            path: '/inovation-detail/:id_inovation',
	            name: 'inovation-detail',
	            meta: { auth : true },
	            components: {
	            	default: IndexMain,
	            	MainView: InovationDetail
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