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
            You can get connected <a href="/auth/spotify/login">here</a>.
        </p>
    </blockquote>
    <div v-else-if="history != null">
        <blockquote v-if="history.error != null" class="quote-danger">
            <h5>Oups!</h5>
            <p>
                <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
                </svg>
                {{ history.error.message }}
            </p>
            <p v-if="history.error.status === 401"> Your session has expired. Refresh your token <a href="/auth/spotify/login">here</a>.</p>
        </blockquote>
        <div v-else-if="history.items != null">     
            <calendar-heatmap :values="historyValues" :end-date="endDateValue" :max="maxActivity" :tooltipUnit="tooltipUnitValue"/>

            <div class="row">
                <div class="col-12">
                    <p>                
                        <a href="#" class="btn btn-primary mx-2">Save</a> This action will save or update your data in <i>Matis</i> database.               
                    </p>
                    <p><small>Spotify allows to retrieve only the last 50 tracks listened. By clicking on save, your history will be kept.</small></p>
                </div>    
            </div>
            <div class="row">            
                <div class="col-6">
                    <h4>Last 50 tracks listened</h4>            
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between list-group-item-action"
                            v-for="(s_track, index) in history.items"
                            v-bind:key="s_track.track.id + s_track.played_at">

                            <div class="col-3">
                                <img v-bind:src="s_track.track.album.images[0].url" alt="picture track history" class="circle img-fluid">
                            </div>

                            <div class="col-7">
                                <p>#{{ index+1 }} | {{ s_track.played_at }}</p>

                                <p><a :href="s_track.track.external_urls.spotify" target="_blank">{{ s_track.track.name }}</a> <span v-for="artist in s_track.track.artists">| <a :href="artist.external_urls.spotify" target="_blank"> {{ artist.name }} </a></span>
                                <br>{{ timeTrack(s_track.track.duration_ms) }}</p>
                            </div>
                            <div class="col-2">
                                <p>{{ timeTrack(s_track.track.duration_ms) }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-6">
                    <h4>Saved activity</h4>
                    <div class="alert alert-danger" role="alert">
                        <p>         
                            <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
                            </svg>
                            <b>Wait!</b> Not available today
                        </p>
                    </div>
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
                loadingPage: null,

                // data
                history: null,
                error: null,

                // graph
                historyValues: [],
                maxActivity: 0,
                endDateValue: moment().format("YYYY-MM-DD"),
                tooltipUnitValue: 'listenings'
            }
        },
        created() {
            this.loadingPage = true;
            axios.get(window.location.origin + '/api/spotify/history')
                .then((response)  =>  {

                    if (response.status === 200) {
                        this.history = response.data.response;
                        this.historyValues = JSON.parse(response.data.history);
                        this.maxActivity = response.data.max;
                    }
                    this.loadingPage = false;

                }, (error)  =>  {
                    this.error = error.response.data;
                    console.log(error);
                    this.loadingPage = false;
                });
        },
        methods: {
            playedAt: function (seconds) {
                return moment.unix(seconds).format("ddd D MMM HH:mm");
            },
            timeTrack: function (seconds) {
                return moment("1900-01-01 00:00:00").add(seconds, 'milliseconds').format("mm:ss");
            },
        }
    }
</script>