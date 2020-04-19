<template>
    <div v-if="playlists.error" class="alert alert-danger" role="alert">
        <p><b>Oups!</b> {{ playlists.error.message }}</p>
        <p v-if="playlists.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">Here</a>.</p>
    </div>
    <div v-else>
        <div class="row">
            <div class="col-12">
                <p>                
                    <a href="#" class="btn btn-primary">Save</a>                
                </p>
            </div>    
        </div>
        <div class="row">            
            <div class="col-12">            
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center"
                        v-for="playlist in playlists.data"
                        v-bind:key="playlist.id">

                        <img v-bind:src="playlist.picture">

                        <span>
                            <svg v-if="playlist.is_loved_track === true" class="text-danger" id="i-heart" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                <path d="M4 16 C1 12 2 6 7 4 12 2 15 6 16 8 17 6 21 2 26 4 31 6 31 12 28 16 25 20 16 28 16 28 16 28 7 20 4 16 Z" />
                            </svg>
                            
                            {{ playlist.title }}
                        </span>

                    <span class="badge badge-primary badge-pill">{{ playlist.nb_tracks }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                playlists: {}
            }
        },

        created() {
            axios.get(window.location.origin + '/ws/deezer/playlists')
                .then( response => this.playlists = response.data )
                //.then( response =>  console.log(response.data) );
                .catch( error => { console.log(error.message) });
        },

        mounted() {
            console.log('Component mounted.');
        }
    }
</script>