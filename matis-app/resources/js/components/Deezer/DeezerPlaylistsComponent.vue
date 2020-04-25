<template>
    <div v-if="loadingPage"class="alert alert-warning" role="alert">
        <h4>Loading...</h4>
    </div>
    <div v-else-if="error != null" class="alert alert-danger" role="alert">
        <p>         
            <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
            </svg>
            <b>Oups!</b> {{ error.message }}
        </p>
        <p v-if="error.code == 403">
            You can get connected <a href="/auth/deezer/login">here</a>.
        </p>
    </div>
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
                <div class="col-5">
                    <p><i>{{ playlists.data.length }} playlists</i></p>

                    <ul class="collection">
                        <li class="collection-item avatar"
                            v-for="playlist in playlists.data"
                            v-bind:key="playlist.id">

                            <img v-bind:src="playlist.picture" alt="picture playlist" class="circle">
                            <span class="title">{{ playlist.title }}</span>
                            <p>{{ playlist.nb_tracks }} tracks<br>
                            Duration: {{ timePlaylist(playlist.duration) }}
                            </p>
                            
                            <button v-on:click="getPlaylistContent(playlist.id)" class="secondary-content btn btn-success" name="action">See</button>
                        </li>
                    </ul>
                </div>
                <div class="col-7">
                    <h3 class="text-right">Playlist content</h3>
                    <hr>
                    <div v-if="loadingPlaylist" class="alert alert-warning" role="alert">
                        <h4>Loading...</h4>
                    </div>
                    <div v-else-if="playlistsContent != null">
                        <p><i>{{ playlistsContent.total }} tracks</i></p>
                        <ul v-if="playlistsContent.data.length > 0" id="playlist-content" class="collection">
                            <li class="collection-item" 
                                v-for="(track, index) in playlistsContent.data"
                                v-bind:key="track.id">
                                #{{ index+1 + (playlistPage-1)*20 }} {{ track.title }} | <a :href="track.artist.link">{{ track.artist.name }}</a> | {{ timeTrack(track.duration) }}
                            </li>
                        </ul>
                    </div>
                    <div v-else class="alert alert-warning" role="alert">
                        <h4>No playlist loaded.</h4>                        
                    </div>

                    <paginate v-if="(loadingPlaylist || playlistsContent != null) && pageCount > 0"
                        class="text-center"
                        :force-page="playlistPage"
                        :page-count="pageCount"
                        :page-range="3"
                        :margin-pages="2"
                        :click-handler="clickCallback"
                        :prev-text="'Prev'"
                        :next-text="'Next'"
                        :container-class="'pagination'"
                        :page-class="'page-item'">
                    </paginate>    
                </div>
            </div>
        </div>
    </div>
    <div v-else class="alert alert-danger" role="alert">
        <p><b>Oups!</b> Something bad happened...</p>
    </div>
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
                this.playlistID = id;

                var prev = this.playlistPage;
                this.playlistPage = page;


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

                        console.log(error);
                    });
            },
            clickCallback: function (pageNum) {
                this.getPlaylistContent(this.playlistID, pageNum);
            }
        },
        data() {
            return {
                playlists: null,
                playlistsSelected: null,

                playlistsContent: null, // return the playlist content
                pageCount: 1, // return the number of page
                playlistPage: 1, // return the selected page
                playlistID: null, // return the selected playlist ID

                error: null,
                loadingPage: false,
                loadingPlaylist: false
            }
        },

        created() {
            this.playlistPage = 1;
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