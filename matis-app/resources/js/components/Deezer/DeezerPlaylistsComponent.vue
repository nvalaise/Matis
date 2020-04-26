<template>
    <div v-if="loadingPage" class="card card-primary">
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
            {{ error.message }}
        </p>
        <p v-if="error.code == 403">
            You can get connected <a href="/auth/deezer/login">here</a>.
        </p>
    </blockquote>
    <div v-else-if="playlists != null">
        <div v-if="playlists.error != null" class="alert alert-danger" role="alert">
            <p>
                <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
                </svg>
                <b>Oups!</b> {{ playlists.error.message }}
            </p>
            <p v-if="playlists.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">here</a>.</p>
        </div>
        <div v-else-if="playlists.data != null">
            <div class="row">
                <div class="col-12">
                    <p>                
                        <a href="#" class="btn btn-primary mx-2">Save</a> This action will save or update your data in <i>Matis</i> database.               
                    </p>
                </div>    
            </div>
            <div class="row">            
                <div class="col-4">
                    <p><i>{{ playlists.data.length }} playlists</i></p>
                    <div class="card card-outline card-primary"
                        v-for="playlist in playlists.data"
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
                                    <p>{{ playlist.nb_tracks }} tracks</p>
                                    <p>Duration: {{ timePlaylist(playlist.duration) }}</p>
                                    <p><br>
                                    <span class="d-flex justify-content-end">
                                        <button v-on:click="clickSee(playlist.id)" class="btn btn-primary" name="action">See</button>
                                    </span>
                                    </p>
                                </div>
                            </div>                        
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <h3 class="text-right" id="playlist">Playlist content</h3>
                    <hr>

                    <div v-if="playlistsSelected != null" class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ playlistsSelected.title }}</h3>
                        </div>

                        <div class="card-body">
                            <div v-if="loadingPlaylist">
                                Loading...
                            </div>
                            <div v-else>
                                <p><i>{{ playlistsContent.total }} tracks</i></p>
                                <ul v-if="playlistsContent.data.length > 0" id="playlist-content" class="list-group">
                                    <li class="list-group-item" 
                                        v-for="(track, index) in playlistsContent.data"
                                        v-bind:key="index">
                                        #{{ index+1 + (playlistPage-1)*20 }} {{ track.title }} | <a :href="track.artist.link">{{ track.artist.name }}</a> | {{ timeTrack(track.duration) }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <paginate v-if="(!loadingPlaylist || playlistsContent != null) && pageCount > 0"
                                  :force-page="playlistPage"
                                  :page-count="pageCount"
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

                        <div v-if="loadingPlaylist" class="overlay">
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
            getPlaylistContent: function (id, page = 1) {

                this.loadingPlaylist = true;
                var prev = this.playlistPage;
                this.playlistPage = page;

var element = document.getElementById("playlist");
element.scrollIntoView({behavior: "smooth"});

                axios.get("/api/deezer/playlist/" + id + "/" + (page-1)*20)
                    .then((response)  =>  {
                        if (response.status === 200) {
                            this.loadingPlaylist = false;

                            this.playlistsContent = response.data;
                            this.pageCount = Math.ceil(this.playlistsContent.total/20);   

                        }
                    }, (error)  =>  {
                        this.loadingPlaylist = false;
                        this.playlistPage = prev;
                        
                        this.error = error.response.data;
                        console.log(error);
                    });
            },
            clickSee: function (id) {
                if(this.playlists != null) {
                    this.playlistsSelected = this.playlists.data.find(item => {
                        return item.id == id;
                    })
                }

                this.getPlaylistContent(id, 1);
            },
            clickPagination: function (pageNum) {
                this.getPlaylistContent(this.playlistsSelected.id, pageNum);
            }
        },
        data() {
            return {
                playlists: null,

                playlistsSelected: null, // return the object of the seletec playlist
                playlistsContent: null, // return the playlist content
                pageCount: 1, // return the number of page
                playlistPage: 1, // return the selected page

                error: null,
                loadingPage: false,
                loadingPlaylist: false
            }
        },

        created() {
            this.loadingPage = true;
            axios.get(window.location.origin + '/api/deezer/playlists')
                .then((response)  =>  {
                    this.loadingPage = false;

                    if (response.status === 200) {
                        this.playlists = response.data;

                    } else {
                        console.log(response);
                    }
                }, (error)  =>  {
                    this.loadingPage = false;
                    this.error = error.response.data;
                    console.log(error);
                });
        },

        mounted() {
            console.log('Component mounted.');
        }
    }
</script>