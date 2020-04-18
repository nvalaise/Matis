<template>
    <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center"
            v-for="playlist in playlists.data"
            v-bind:key="playlist.id">
            {{ playlist.title }}
        <span class="badge badge-primary badge-pill">{{ playlist.nb_tracks }}</span>
        </li>
    </ul>
</template>

<script>
    export default {

        data() {
            return {
                playlists: {}
            }
        },

        created() {
            axios.get(window.location.origin + '/api/deezer/playlists')
                .then( response => console.log(response.data))
                //.then( response => this.playlists = response.data )
                .catch( error => { console.log(error) });

        },

        mounted() {
            console.log('Component mounted.');
        }
    }
</script>