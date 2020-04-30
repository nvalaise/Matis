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
import SpotifyArtist from './components/Spotify/SpotifyArtistsComponent.vue';
import SpotifyHistory from './components/Spotify/SpotifyHistoryComponent.vue';
//import DeezerSocial from './components/Deezer/DeezerSocialComponent.vue';


const routes = [
	{
		path: '/spotify/', name: 'home', component: SpotifyHome
	},
	{
		path: '/spotify/playlists', name: 'playlists', component: SpotifyPlaylist
	},
	/*{
		path: '/spotify/social', name: 'social', component: DeezerSocial
	},*/
	{
		path: '/spotify/history', name: 'history', component: SpotifyHistory
	},
	{
		path: '/spotify/artists', name: 'artists', component: SpotifyArtist
	}
];

const router = new VueRouter({routes, mode: 'history'});

const app = new Vue({
  router,
  linkExactActiveClass: 'active',
}).$mount('#app');