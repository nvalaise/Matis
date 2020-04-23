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
    <div v-else-if="history != null">
        <div v-if="history.error != null" class="alert alert-danger" role="alert">
            <p>
                <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
                </svg>
                <b>Oups!</b> {{ history.error.message }}
            </p>
            <p v-if="history.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">here</a>.</p>
        </div>
        <div v-else-if="history.data != null">
            <div class="row">
                <calendar-heatmap :values="historyValues" :endDate="endDateValue" :tooltipUnit="tooltipUnitValue"/>
            </div>
            <div class="row">
                <div class="col-12">
                    <p>                
                        <a href="#" class="btn btn-primary">Save</a> This action will save or update your data in <i>Matis</i> database.               
                    </p>
                </div>    
            </div>
            <div class="row">            
                <div class="col-6">
                    <h4>Latest history</h4>            
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between list-group-item-action"
                            v-for="(track, index) in history.data"
                            v-bind:key="track.id + track.timestamp">

                            <div class="col-3">
                                <img v-if="track.album.cover" v-bind:src="track.album.cover" alt="picture track history" class="circle img-responsive">
                            </div>

                            <div class="col-7">
                                <p>#{{ index+1 }} | {{ playedAt(track.timestamp) }}</p>
                                <p>                             
                                    <a :href="track.link" target="_blank">{{ track.title }}</a> |
                                    {{ track.artist.name }}
                                </p>
                            </div>
                            <div class="col-2">
                                <p>{{ timeTrack(track.duration) }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-6">
                    <h4>Saved history</h4>
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
    <div v-else class="alert alert-danger" role="alert">
        <p><b>Oups!</b> Something bad happened...</p>
    </div>
</template>

<script>
    import { CalendarHeatmap } from 'vue-calendar-heatmap'

    export default {
        components: {
            CalendarHeatmap
        },
        methods: {
            playedAt: function (seconds) {
                return moment.unix(seconds).format("ddd D MMM HH:mm");
            },
            timeTrack: function (seconds) {
                return moment("1900-01-01 00:00:00").add(seconds, 'seconds').format("mm:ss");
            },
        },

        data() {
            return {
                loadingPage: null,

                // data
                history: null,
                error: null,

                // graph
                historyValues: null,
                endDateValue: moment().format("YYYY-M-d"),
                tooltipUnitValue: 'listenings'
            }
        },

        created() {
            this.loadingPage = true;
            axios.get(window.location.origin + '/ws/deezer/history')
                .then((response)  =>  {
                    this.loadingPage = false;

                    if (response.status === 200) {
                        this.history = response.data;
                    } else {
                        console.log(response);
                    }
                }, (error)  =>  {
                    this.loadingPage = false;
                    this.error = error.response.data;
                });

            this.historyValues = [{ 
                date: '2020-3-20', count: 5 },{
                date: '2020-3-21', count: 11 },{
                date: '2020-3-23', count: 2 },{
                date: '2020-3-24', count: 6 
            }];
        },

        mounted() {
            console.log('Component mounted.');
        }
    }
</script>