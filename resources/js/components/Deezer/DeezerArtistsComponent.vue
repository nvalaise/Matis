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
            <span v-if="error.message != null">{{ error.message }}</span>
            <span v-else>{{ error }}</span>
        </p>
        <p v-if="error.code == 403">
            You can get connected <a href="/auth/deezer/login">here</a>.
        </p>
    </blockquote>
    <div v-else-if="artists != null">
        <div v-if="artists.error != null" class="alert alert-danger" role="alert">
            <p>
                <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
                </svg>
                <b>Oups!</b> {{ artists.error.message }}
            </p>
            <p v-if="artists.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">here</a>.</p>
        </div>
        <div v-else-if="artists.data != null">
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
                    <p><i>{{ artists.total }} artists</i></p>

                    <paginate v-if="(!loadingArtist || artists != null) && pageCountArtists > 0"
                          :force-page="pageArtists"
                          :page-count="pageCountArtists"
                          :click-handler="clickPaginationArtists"
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

                    <div v-if="loadingArtist" class="card card-primary">
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
                        v-for="artist in artists.data"
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

                    <div v-if="artistSelected != null" class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ artistSelected.name }}</h3>
                        </div>

                        <div class="card-body">
                            <div v-if="loadingArtistDetails">
                                Loading...
                            </div>
                            <div v-else>
                                <p><a :href="artistSelected.link" target="_blank">Artist profile</a></p>

                                <p><i>Top {{ artistTop.data.length }} tracks</i></p>
                                <ul v-if="artistTop.data.length > 0" id="artist-content" class="list-group">
                                    <li class="list-group-item" 
                                        v-for="(track, index) in artistTop.data"
                                        v-bind:key="index">
                                        #{{ index+1 }} {{ track.title }} | {{ timeTrack(track.duration) }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div v-if="loadingArtistDetails" class="overlay">
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
        methods: {
            followedAt: function (seconds) {
                return moment.unix(seconds).format("ddd D MMM Y HH:mm");
            },
            timeTrack: function (seconds) {
                return moment("1900-01-01 00:00:00").add(seconds, 'seconds').format("mm:ss");
            },

            // return the list of the artist
            clickPaginationArtists: function (pageNum) {

                console.log(this.pageArtists, pageNum);

                this.loadingArtist = true;
                var prev = this.pageArtists;
                this.pageArtists = pageNum;

                axios.get("/api/deezer/artists/" + pageNum * 25)
                    .then((response)  =>  {
                        if (response.status === 200) {
                            this.artists = response.data;

                            this.loadingArtist = false;
                        }
                    }, (error)  =>  {
                        this.pageArtists = prev;
                        this.error = error.response.data;
                        console.log(error);

                        this.loadingArtist = false;
                    });
            },

            // when click on "See"
            clickSee: function (id) {
                if(this.artists != null) {
                    this.artistSelected = this.artists.data.find(item => {
                        return item.id == id;
                    })
                }

                this.getArtistsDetails(id);
            },
            // return the artist details
            getArtistsDetails: function (id) {

                this.loadingArtistDetails = true;

                var element = document.getElementById("artist");
                element.scrollIntoView({behavior: "smooth"});

                axios.get("/api/deezer/artist/" + id)
                    .then((response)  =>  {
                        if (response.status === 200) {
                            this.artistTop = response.data;   
                            this.loadingArtistDetails = false;
                        }
                    }, (error)  =>  {
                        this.error = error.response.data;
                        console.log(error);
                        this.loadingArtistDetails = false;
                    });
            },
        },
        data() {
            return {
                artists: null,
                pageCountArtists: 1,
                pageArtists: 1,

                artistSelected: null, // return the object of the selected artist
                artistTop: null, // return the artist top details

                error: null,
                loadingPage: false,
                loadingArtist: false,
                loadingArtistDetails: false
            }
        },

        created() {
            this.loadingPage = true;            
            axios.get(window.location.origin + '/api/deezer/artists')
                .then((response)  =>  {
                    this.loadingPage = false;

                    console.log(response.data);
                    this.pageCountArtists = Math.ceil(response.data.total/25);   

                    if (response.status === 200) {
                        this.artists = response.data;
                    } else {
                        console.log(response);
                    }
                }, (error)  =>  {
                    this.loadingPage = false;
                    this.error = error.response.data;
                });
        },

        mounted() {
            // console.log('Component mounted.');
        }
    }
</script>