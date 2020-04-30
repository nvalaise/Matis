<template>
    <div v-if="s_loading_page" class="card card-primary">
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
            You can get connected <a href="/auth/spotify/login">here</a>.
        </p>
    </blockquote>
    <div v-else-if="s_playlists != null">
        <blockquote v-if="s_playlists.error != null" class="quote-danger">
            <h5>Oups!</h5>
            <p>
                <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
                </svg>
                {{ s_playlists.error.message }}
            </p>
            <p v-if="s_playlists.error.status === 401"> Your session has expired. Refresh your token <a href="/auth/spotify/login">here</a>.</p>
        </blockquote>
        <div v-else-if="s_playlists.items != null">
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
                    <p class="font-italic"><i>{{ s_playlists.total }} playlists</i> <span v-if="s_playlists_count_page > 1" class="small"> (grouped by 10)</span></p>

                    <paginate v-if="(!s_loading_playlist || s_playlists != null) && s_playlists_count_page > 1"
                          :force-page="s_playlists_page"
                          :page-count="s_playlists_count_page"
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

                    <div v-if="s_loading_playlist" class="card card-primary">
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
                        v-for="playlist in s_playlists.items"
                        v-bind:key="playlist.id">

                        <div class="card-header">
                            {{ playlist.name }}
                        </div>
                        <div class="card-body">
                            <div class="row my-1">
                                <div class="col-3">
                                    <img v-bind:src="playlist.images[0].url" alt="picture playlist" class="img-fluid img-circle">
                                </div>
                                <div class="col-9">
                                    <p>{{ playlist.tracks.total }} tracks</p>
                                    <p v-if="playlist.description == ''"><i>No description</i></p>
                                    <p v-else><span v-html="playlist.description"></span></p>
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

                    <div v-if="s_playlistsSelected != null" class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ s_playlistsSelected.name }}</h3>
                        </div>

                        <div class="card-body">
                            <div v-if="s_loading_content_playlist">
                                Loading...
                            </div>
                            <div v-else>
                                <p><a :href="s_playlistsSelected.external_urls.spotify" target="_blank">Playlist link</a></p>
                                <p><i>{{ s_playlistsContent.total }} tracks</i></p>
                                <ul v-if="s_playlistsContent.items.length > 0" id="playlist-content" class="list-group">
                                    <li class="list-group-item" 
                                        v-for="(item, index) in s_playlistsContent.items"
                                        v-bind:key="index">
                                        <div class="row">
                                            <div class="col-2">
                                                <img v-bind:src="item.track.album.images[0].url" alt="picture playlist" class="img-fluid img-circle">
                                            </div>
                                            <div class="col-10">
                                                <p><b>#{{ index+1 + (s_playlist_page-1)*20 }}</b> <span v-for="artist in item.track.artists">| <a :href="artist.external_urls.spotify" target="_blank"> {{ artist.name }} </a></span>
                                                <br>{{ timeTrack(item.track.duration_ms) }} | <a :href="item.track.external_urls.spotify" target="_blank">{{ item.track.name }}</a></p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <paginate v-if="(!s_loading_content_playlist || s_playlistsContent != null) && s_playlist_count_page > 0"
                                  :force-page="s_playlist_page"
                                  :page-count="s_playlist_count_page"
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

                        <div v-if="s_loading_content_playlist" class="overlay">
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
                s_playlists: null,
                s_playlists_count_page: 1, // return the number of page
                s_playlists_page: 1, // return the selected page

                s_playlistsSelected: null, // return the object of the seletec playlist
                s_playlistsContent: null, // return the playlist content
                s_playlist_count_page: 1, // return the number of page
                s_playlist_page: 1, // return the selected page

                error: null,
                s_loading_page: false,
                s_loading_playlist: false,
                s_loading_content_playlist: false
            }
        },
        created() {
            this.s_loading_page = true;
            this.getPlaylistsList(1);
        },
        methods: {
            timeTrack: function (seconds) {
                return moment("1900-01-01 00:00:00").add(seconds, 'milliseconds').format("mm:ss");
            },
            getPlaylistsList: function(page) {

                this.s_loading_playlist = true;
                var prev = this.s_playlists_page;
                this.s_playlists_page = page;

                axios.get(window.location.origin + '/api/spotify/playlists/' + (page-1)*10)
                    .then((response)  =>  {
                        if (response.status === 200) {
                            this.s_playlists = response.data;
                            this.s_playlists_count_page = Math.ceil(this.s_playlists.total/10);
                        } else {
                            console.log(response);
                        }
                        this.s_loading_playlist = this.s_loading_page = false;
                    }, (error)  =>  {
                        this.s_playlist_page = prev;                                                
                        this.error = error.response.data;
                        this.s_loading_playlist = this.s_loading_page = false;
                    });

                return;
            },
            getPlaylistContent: function (id, page = 1) {
                console.log(this.s_playlistsSelected);

                this.s_loading_content_playlist = true;
                var prev = this.s_playlist_page;
                this.s_playlist_page = page;

                var element = document.getElementById("playlist");
                element.scrollIntoView({behavior: "smooth"});

                console.log(2);
                axios.get("/api/spotify/playlist/" + id + "/" + (page-1)*20)
                    .then((response)  =>  {
                        if (response.status === 200) {
                            this.s_playlistsContent = response.data;
                            this.s_playlist_count_page = Math.ceil(this.s_playlistsContent.total/20);
                        } else {
                            console.log(response);
                        }
                        this.s_loading_content_playlist = false;
                    }, (error)  =>  {
                        this.s_playlist_page = prev;                        
                        this.error = error.response.data;
                        console.log(error);
                        this.s_loading_content_playlist = false;
                    });
            },
            clickSee: function (id) {
                if(this.s_playlists != null) {
                    this.s_playlistsSelected = this.s_playlists.items.find(item => {
                        return item.id == id;
                    })
                    this.getPlaylistContent(id, 1);
                }
            },
            clickPagination: function (pageNum) {
                this.getPlaylistContent(this.s_playlistsSelected.id, pageNum);
            }
        }
    }
</script>