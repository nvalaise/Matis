/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter);

//import VueCalendarHeatmap from 'vue-calendar-heatmap'
//Vue.use(VueCalendarHeatmap)

import DeezerHome from './components/Deezer/DeezerHomeComponent.vue';
import DeezerPlaylist from './components/Deezer/DeezerPlaylistsComponent.vue';
import DeezerHistory from './components/Deezer/DeezerHistoryComponent.vue';
import DeezerSocial from './components/Deezer/DeezerSocialComponent.vue';


const routes = [
	{
		path: '/deezer', name: 'home', component: DeezerHome
	},
	{
		path: '/deezer/playlists', name: 'playlists', component: DeezerPlaylist
	},
	{
		path: '/deezer/social', name: 'social', component: DeezerSocial
	},
	{
		path: '/deezer/history', name: 'history', component: DeezerHistory
	}
];

const router = new VueRouter({routes, mode: 'history'});

const app = new Vue({
  router
}).$mount('#app')