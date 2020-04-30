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
    <div v-else-if="s_artists != null">
        <blockquote v-if="s_artists.error != null" class="quote-danger">
            <h5>Oups!</h5>
            <p>
                <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
                </svg>
                {{ s_artists.error.message }}
            </p>
            <p v-if="s_artists.error.status === 401"> Your session has expired. Refresh your token <a href="/auth/spotify/login">here</a>.</p>
        </blockquote>
        <div v-else-if="s_artists.items != null">
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
                    <p class="font-italic"><i>{{ s_artists.total }} artists</i><span v-if="s_page_count_artists > 1" class="small"> (grouped by 15)</span></p>

                    <paginate v-if="(!s_loading_artist || s_artists != null) && s_page_count_artists > 1"
                          :force-page="s_page_s_artists"
                          :page-count="s_page_count_artists"
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

                    <div v-if="s_loading_artist" class="card card-primary">
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
                        v-for="artist in s_artists.items"
                        v-bind:key="artist.id">


                        <img v-bind:src="artist.images[0].url" class="card-img-top" alt="picture artist">

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

                    <div v-if="s_artist_selected != null" class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ s_artist_selected.name }}</h3>
                        </div>

                        <div class="card-body">
                            <div v-if="s_loading_content_artist">
                                Loading...
                            </div>
                            <div v-else>
                                <p><a :href="s_artist_selected.external_urls.spotify" target="_blank">Artist's profile</a></p>

                                <p><i>Top {{ s_artist_top.length }} tracks</i></p>
                                <ul v-if="s_artist_top.length > 0" id="artist-content" class="list-group">
                                    <li class="list-group-item" 
                                        v-for="(track, index) in s_artist_top"
                                        v-bind:key="index">
                                        <div class="row">
                                            <div class="col-2">
                                                <img v-bind:src="track.album.images[0].url" alt="picture playlist" class="img-fluid img-circle">
                                            </div>
                                            <div class="col-10">
                                                <p><b>#{{ index+1 }}</b> <span v-for="artist in track.artists">| <a :href="artist.external_urls.spotify" target="_blank"> {{ artist.name }} </a></span>
                                                <br>{{ timeTrack(track.duration_ms) }} | <a :href="track.external_urls.spotify" target="_blank">{{ track.name }}</a></p>

                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div v-if="s_loading_content_artist" class="overlay">
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
                s_artists: null,
                s_page_count_artists: 1,
                s_page_s_artists: 1,

                s_artist_selected: null, // return the object of the selected artist
                s_artist_top: null, // return the artist top details

                error: null,
                s_loading_page: false,
                s_loading_artist: false,
                s_loading_content_artist: false
            }
        },
        created() {
            this.s_loading_page = true;    
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
                this.s_loading_artist = true;
                var prev = this.s_page_s_artists;
                this.s_page_s_artists = page;

                axios.get("/api/spotify/artists/" + (page-1)*15)
                    .then((response)  =>  {

                        if (response.status === 200) {
                            this.s_artists = response.data.error ? response.data : response.data.artists;
                            this.s_page_count_artists = Math.ceil(this.s_artists.total/15);
                            this.s_loading_artist= this.s_loading_page = false;

                            console.log(response);

                        }
                    }, (error)  =>  {
                        this.s_page_s_artists = prev;
                        this.error = error.response.data;
                        console.log(error);
                        this.s_loading_artist= this.s_loading_page = false;
                    });
            },

            // when click on "See"
            clickSee: function (id) {
                if(this.s_artists != null) {
                    this.s_artist_selected = this.s_artists.items.find(item => {
                        return item.id == id;
                    })

                    this.getArtistsContent(id);
                }
            },
            // return the artist details
            getArtistsContent: function (id) {

                this.s_loading_content_artist = true;

                var element = document.getElementById("artist");
                element.scrollIntoView({behavior: "smooth"});

                axios.get("/api/spotify/artist/" + id)
                    .then((response)  =>  {

                        if (response.status === 200) {
                            this.s_artist_top = response.data.error ? response.data : response.data.tracks;
                        } else {
                            console.log(response);
                        }
                        this.s_loading_content_artist = false;
                    }, (error)  =>  {
                        this.error = error.response.data;
                        console.log(error);
                        this.s_loading_content_artist = false;
                    });
            },
        }
    }
</script>