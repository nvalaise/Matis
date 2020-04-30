/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('vue-calendar-heatmap');

import Vue from 'vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter);

import Paginate from 'vuejs-paginate'
Vue.component('paginate', Paginate)

import VueCalendarHeatmap from 'vue-calendar-heatmap'
Vue.use(VueCalendarHeatmap)

import SpotifyHome from './components/Spotify/SpotifyHomeComponent.vue';
import SpotifyPlaylist from './components/Spotify/SpotifyPlaylistsComponent.vue';
/*import DeezerArtist from './components/Deezer/DeezerArtistsComponent.vue';
import DeezerHistory from './components/Deezer/DeezerHistoryComponent.vue';
import DeezerSocial from './components/Deezer/DeezerSocialComponent.vue';*/


const routes = [
	{
		path: '/spotify/', name: 'home', component: SpotifyHome
	},
	{
		path: '/spotify/playlists', name: 'playlists', component: SpotifyPlaylist
	}/*,
	{
		path: '/spotify/social', name: 'social', component: DeezerSocial
	},
	{
		path: '/spotify/history', name: 'history', component: DeezerHistory
	},
	{
		path: '/spotify/artists', name: 'artists', component: DeezerArtist
	}*/
];

const router = new VueRouter({routes, mode: 'history'});

const app = new Vue({
  router,
  linkExactActiveClass: 'active',
}).$mount('#app');