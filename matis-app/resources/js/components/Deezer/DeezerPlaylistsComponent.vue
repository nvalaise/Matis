<template>
    <div v-if="error != null" class="alert alert-danger" role="alert">
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
    <div v-else-if="playlists.error != null" class="alert alert-danger" role="alert">
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
                    <a href="#" class="btn btn-primary">Save</a> This action will save or update your data in <i>Matis</i> database.               
                </p>
            </div>    
        </div>
        <div class="row">            
            <div class="col-12">            
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between"
                        v-for="playlist in playlists.data"
                        v-bind:key="playlist.id">

                        <div class="col-2">
                            <p>
                                <img class="rounded" v-bind:src="playlist.picture">
                            </p>
                        </div>

                        <div class="col-2">
                            <span>
                            <svg v-if="playlist.is_loved_track === true" class="text-danger" id="i-heart" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M4 16 C1 12 2 6 7 4 12 2 15 6 16 8 17 6 21 2 26 4 31 6 31 12 28 16 25 20 16 28 16 28 16 28 7 20 4 16 Z" />
                            </svg>
                            
                            {{ playlist.title }}
                        </span>
                        </div>

                        <div class="col-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Data</h5>
                                </div>
                                <div class="card-body">
                                    <div class="col-6">
                                        <p>
                                            <span class="badge badge-primary badge-pill">{{ playlist.nb_tracks }} tracks</span>
                                        </p>
                                        <p>
                                            <span class="badge badge-primary badge-pill">{{ playlist.fans }} fans</span>
                                        </p>
                                        <p>
                                            {{  formattt(playlist.duration) }}
                                        </p>
                                        <p>
                                            {{ playlist.creator.name }}
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <p>
                                            {{ playlist.description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
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
            formattt: function (seconds) {
                return moment("1900-01-01 00:00:00").add(seconds, 'seconds').format("HH:mm");
            }
        },

        data() {
            return {
                playlists: null,
                error: null
            }
        },

        created() {
            axios.get(window.location.origin + '/ws/deezer/playlists')
                .then( response => this.playlists = response.data )
                .catch( error => this.error = error.response.data );
        },

        mounted() {
            console.log('Component mounted.');
        }
    }
</script>