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
    <div v-else-if="d_artists != null">
        <div v-if="d_artists.error != null" class="alert alert-danger" role="alert">
            <p>
                <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
                </svg>
                <b>Oups!</b> {{ d_artists.error.message }}
            </p>
            <p v-if="d_artists.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">here</a>.</p>
        </div>
        <div v-else-if="d_artists.data != null">
            <div class="row">
                <div class="col-12">
                    <p>                
                        <a href="#" class="btn btn-warning mx-2">Save</a> This action will save or update the artist data in <i>Matis</i> database.               
                    </p>
                    <p>                
                        <a href="#" class="btn btn-success mx-2">Save</a> The button in green means that you have already saved the artist in <i>Matis</i> database.               
                    </p>
                </div>    
            </div>
            <div class="row">            
                <div class="col-4">
                    <p><i>{{ d_artists.total }} artists</i></p>

                    <paginate v-if="(!d_loading_artist || d_artists != null) && d_page_count_artists > 0"
                          :force-page="d_page_d_artists"
                          :page-count="d_page_count_artists"
                          :click-handler="getArtistsList"
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

                    <div v-if="d_loading_artist" class="card card-primary">
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
                        v-for="artist in d_artists.data"
                        v-bind:key="artist.id">


                        <img v-bind:src="artist.picture_big" class="card-img-top" alt="picture artist">

                        <div class="card-body">
                            <h5 class="card-title">{{ artist.name }}</h5>
                            <p class="card-text font-weight-lighter">Followed since {{ followedAt(artist.time_add) }}.</p>
                            <div class="d-flex justify-content-between">
                                <button v-on:click="clickSee(artist.id)" class="btn btn-primary" name="action">See</button>
                                <button class="btn btn-warning" name="action">Save</button>                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <h3 class="text-right" id="artist">Artist details</h3>
                    <hr>

                    <div v-if="d_artist_selected != null" class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ d_artist_selected.name }}</h3>
                        </div>

                        <div class="card-body">
                            <div v-if="d_loading_content_artist">
                                Loading...
                            </div>
                            <div v-else>
                                <p><a :href="d_artist_selected.link" target="_blank">Artist profile</a></p>

                                <p><i>Top {{ d_artist_top.data.length }} tracks</i></p>
                                <ul v-if="d_artist_top.data.length > 0" id="artist-content" class="list-group">
                                    <li class="list-group-item" 
                                        v-for="(track, index) in d_artist_top.data"
                                        v-bind:key="index">
                                        <div class="row">
                                            <div class="col-2">
                                                <img v-bind:src="track.album.cover_small" alt="picture playlist" class="img-fluid img-circle">
                                            </div>
                                            <div class="col-10">
                                                #{{ index+1 }} {{ track.title }} | {{ timeTrack(track.duration) }}
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div v-if="d_loading_content_artist" class="overlay">
                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                        </div>
                    </div>

                    <blockquote v-else class="quote-warning">
                        <p>         
                            <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
                            </svg>
                            No artist selected
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
                d_artists: null,
                d_page_count_artists: 1,
                d_page_d_artists: 1,

                d_artist_selected: null, // return the object of the selected artist
                d_artist_top: null, // return the artist top details

                error: null,
                d_loading_page: false,
                d_loading_artist: false,
                d_loading_content_artist: false
            }
        },
        created() {
            this.d_loading_page = true;    
            this.getArtistsList(1);
            
        },
        methods: {
            followedAt: function (seconds) {
                return moment.unix(seconds).format("ddd D MMM Y HH:mm");
            },
            timeTrack: function (seconds) {
                return moment("1900-01-01 00:00:00").add(seconds, 'seconds').format("mm:ss");
            },

            // return the list of the artist
            getArtistsList: function (page) {
                this.d_loading_artist = true;
                var prev = this.d_page_d_artists;
                this.d_page_d_artists = page;

                axios.get("/api/deezer/artists/" + (page-1)*15)
                    .then((response)  =>  {
                        if (response.status === 200) {
                            this.d_artists = response.data;
                            this.d_page_count_artists = Math.ceil(this.d_artists.total/10);
                            this.d_loading_artist= this.d_loading_page = false;
                        }
                    }, (error)  =>  {
                        this.d_page_d_artists = prev;
                        this.error = error.response.data;
                        console.log(error);
                        this.d_loading_artist= this.d_loading_page = false;
                    });
            },

            // when click on "See"
            clickSee: function (id) {
                if(this.d_artists != null) {
                    this.d_artist_selected = this.d_artists.data.find(item => {
                        return item.id == id;
                    })
                }

                this.getArtistsContent(id);
            },
            // return the artist details
            getArtistsContent: function (id) {

                this.d_loading_content_artist = true;

                var element = document.getElementById("artist");
                element.scrollIntoView({behavior: "smooth"});

                axios.get("/api/deezer/artist/" + id)
                    .then((response)  =>  {
                        if (response.status === 200) {
                            this.d_artist_top = response.data;   
                            this.d_loading_content_artist = false;
                        }
                    }, (error)  =>  {
                        this.error = error.response.data;
                        console.log(error);
                        this.d_loading_content_artist = false;
                    });
            },
        }
    }
</script>