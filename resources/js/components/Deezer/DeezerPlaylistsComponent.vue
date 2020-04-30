<template>
    <div v-if="d_loading_page" class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Loading...</h3>
        </div>
        <div class="card-body">
            We are getting the data...
        </div>
        <div class="overlay">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>
    </div>
    <blockquote v-else-if="error != null" class="quote-danger">
        <h5>Oups!</h5>
        <p>         
            <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
            </svg>
            <span v-if="error.message != null">{{ error.message }}</span>
            <span v-else>{{ error }}</span>
        </p>
        <p v-if="error.code == 403">
            You can get connected <a href="/auth/deezer/login">here</a>.
        </p>
    </blockquote>
    <div v-else-if="d_playlists != null">
        <blockquote v-if="d_playlists.error != null" class="quote-danger">
            <h5>Oups!</h5>
            <p>
                <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
                </svg>
                {{ d_playlists.error.message }}
            </p>
            <p v-if="d_playlists.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">here</a>.</p>
        </blockquote>
        <div v-else-if="d_playlists.data != null">
            <div class="row">
                <div class="col-12">
                    <p>                
                        <a href="#" class="btn btn-warning mx-2">Save</a> This action will save or update your data in <i>Matis</i> database.               
                    </p>
                    <p>                
                        <a href="#" class="btn btn-success mx-2">Save</a> The button in green means that you have already saved the playlist in <i>Matis</i> database.               
                    </p>
                </div>    
            </div>
            <div class="row">            
                <div class="col-4">
                    <p class="font-italic"><i>{{ d_playlists.total }} playlists</i> <span v-if="d_playlists_count_page > 1" class="small"> (grouped by 10)</span></p>

                    <paginate v-if="(!d_loading_playlist || d_playlists != null) && d_playlists_count_page > 1"
                          :force-page="d_playlists_page"
                          :page-count="d_playlists_count_page"
                          :click-handler="getPlaylistsList"
                          :prev-text="'Prev'"
                          :next-text="'Next'"
                          :container-class="'pagination d-flex justify-content-center mt-4'"
                          :page-class="'page-item'"
                          :page-link-class="'page-link'"
                          :prev-class="'page-item'"
                          :next-class="'page-item'"
                          :prev-link-class="'page-link'"
                          :next-link-class="'page-link'"
                          :active-class="'active'">
                        </paginate>

                    <div v-if="d_loading_playlist" class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Loading...</h3>
                        </div>
                        <div class="card-body">
                            We are getting the data...
                        </div>
                        <div class="overlay">
                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                        </div>
                    </div>

                    <div class="card card-outline card-primary"
                        v-else
                        v-for="playlist in d_playlists.data"
                        v-bind:key="playlist.id">

                        <div class="card-header">
                            {{ playlist.title }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <img v-bind:src="playlist.picture" alt="picture playlist" class="img-fluid img-circle">
                                </div>
                                <div class="col-9">
                                    <div></div>
                                    <p>{{ playlist.nb_tracks }} tracks</p>
                                    <p>Duration: {{ timePlaylist(playlist.duration) }}</p>
                                </div>
                            </div>                                
                            <div class="row d-flex justify-content-between">
                                <button v-on:click="clickSee(playlist.id)" class="btn btn-primary" name="action">See</button>

                                <button class="btn btn-warning" name="action">Save</button>                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <h3 class="text-right" id="playlist">Playlist content</h3>
                    <hr>

                    <div v-if="d_playlistsSelected != null" class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ d_playlistsSelected.title }}</h3>
                        </div>

                        <div class="card-body">
                            <div v-if="d_loading_content_playlist">
                                Loading...
                            </div>
                            <div v-else>
                                <p v-if="d_playlistsContent.data.length > 0"><a :href="d_playlistsSelected.link" target="_blank">Playlist link</a></p>
                                <p><i>{{ d_playlistsContent.total }} tracks</i></p>
                                <ul v-if="d_playlistsContent.data.length > 0" id="playlist-content" class="list-group">
                                    <li class="list-group-item" 
                                        v-for="(track, index) in d_playlistsContent.data"
                                        v-bind:key="index">
                                        <div class="row">
                                            <div class="col-2">
                                                <img v-bind:src="track.album.cover_small" alt="picture playlist" class="img-fluid img-circle">
                                            </div>
                                            <div class="col-10">
                                                <p><b>#{{ index+1 + (d_playlist_page-1)*20 }}</b> <a :href="track.artist.link" target="_blank">{{ track.artist.name }}</a>
                                                <br>{{ timeTrack(track.duration) }} | <a :href="track.link" target="_blank">{{ track.title }}</a></p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <paginate v-if="(!d_loading_content_playlist || d_playlistsContent != null) && d_playlist_count_page > 0"
                                  :force-page="d_playlist_page"
                                  :page-count="d_playlist_count_page"
                                  :click-handler="clickPagination"
                                  :prev-text="'Prev'"
                                  :next-text="'Next'"
                                  :container-class="'pagination d-flex justify-content-center mt-4'"
                                  :page-class="'page-item'"
                                  :page-link-class="'page-link'"
                                  :prev-class="'page-item'"
                                  :next-class="'page-item'"
                                  :prev-link-class="'page-link'"
                                  :next-link-class="'page-link'"
                                  :active-class="'active'">
                                </paginate>

                        <div v-if="d_loading_content_playlist" class="overlay">
                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                        </div>
                    </div>

                    <blockquote v-else class="quote-warning">
                        <p>         
                            <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
                            </svg>
                            No playlist loaded
                        </p>
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
    <blockquote v-else class="quote-danger">
        <h5>Oups!</h5>
        <p>         
            <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
            </svg>
            Something bad happened...
        </p>
    </blockquote>
</template>

<script>
    export default {
        data() {
            return {
                d_playlists: null,
                d_playlists_count_page: 1, // return the number of page
                d_playlists_page: 1, // return the selected page

                d_playlistsSelected: null, // return the object of the seletec playlist
                d_playlistsContent: null, // return the playlist content
                d_playlist_count_page: 1, // return the number of page
                d_playlist_page: 1, // return the selected page

                error: null,
                d_loading_page: false,
                d_loading_playlist: false,
                d_loading_content_playlist: false
            }
        },
        created() {
            this.d_loading_page = true;
            this.getPlaylistsList(1);
        },
        methods: {
            timePlaylist: function (seconds) {
                var calcul = moment("1900-01-01 00:00:00").add(seconds, 'seconds');
                var hours = calcul.format("H");
                var min = calcul.format("mm");
                var sec = calcul.format("ss");

                return (hours > 0 ? hours + " h " : "") + min + " min " + sec;
            },
            timeTrack: function (seconds) {
                return moment("1900-01-01 00:00:00").add(seconds, 'seconds').format("mm:ss");
            },
            getPlaylistsList: function(page) {

                this.d_loading_playlist = true;
                var prev = this.d_playlists_page;
                this.d_playlists_page = page;

                axios.get(window.location.origin + '/api/deezer/playlists/' + (page-1)*10)
                    .then((response)  =>  {
                        if (response.status === 200) {
                            this.d_playlists = response.data;
                            this.d_playlists_count_page = Math.ceil(this.d_playlists.total/10);
                        }
                        this.d_loading_playlist = this.d_loading_page = false;
                    }, (error)  =>  {
                        this.d_playlist_page = prev;                                                
                        this.error = error.response.data;
                        console.log(error);

                        this.d_loading_playlist = this.d_loading_page = false;
                    });
            },
            getPlaylistContent: function (id, page = 1) {

                this.d_loading_content_playlist = true;
                var prev = this.d_playlist_page;
                this.d_playlist_page = page;

                var element = document.getElementById("playlist");
                element.scrollIntoView({behavior: "smooth"});

                axios.get("/api/deezer/playlist/" + id + "/" + (page-1)*20)
                    .then((response)  =>  {
                        if (response.status === 200) {
                            this.d_playlistsContent = response.data;
                            this.d_playlist_count_page = Math.ceil(this.d_playlistsContent.total/20);   
                        }
                        this.d_loading_content_playlist = false;
                    }, (error)  =>  {
                        this.d_playlist_page = prev;                        
                        this.error = error.response.data;
                        console.log(error);
                        this.d_loading_content_playlist = false;
                    });
            },
            clickSee: function (id) {
                if(this.d_playlists != null) {
                    this.d_playlistsSelected = this.d_playlists.data.find(item => {
                        return item.id == id;
                    })
                    this.getPlaylistContent(id, 1);
                }
            },
            clickPagination: function (pageNum) {
                this.getPlaylistContent(this.d_playlistsSelected.id, pageNum);
            }
        }
    }
</script>