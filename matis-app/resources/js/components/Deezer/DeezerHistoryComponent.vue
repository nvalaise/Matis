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
    <div v-else-if="history != null && history.error != null" class="alert alert-danger" role="alert">
        <p>
            <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
            </svg>
            <b>Oups!</b> {{ history.error.message }}
        </p>
        <p v-if="history.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">here</a>.</p>
    </div>
    <div v-else-if="history.error" class="alert alert-danger" role="alert">
        <p><b>Oups!</b> {{ history.error.message }}</p>
        <p v-if="history.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">Here</a>.</p>
    </div>
    <div v-else-if="history != null">
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
                    <li class="list-group-item d-flex justify-content-between list-group-item-action"
                        v-for="track in history.data"
                        v-bind:key="track.id + track.timestamp">

                        <div class="col-8">
                            <p>
                                {{ track.timestamp }}
                            </p>
                        </div>

                        <div class="col-2">
                            {{ track.artist.name }} / 
                            {{ track.title }}
                        </div>
                        <div class="col-2">
                            {{ track.duration }}
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
                history: null,
                error: null
            }
        },

        created() {
            axios.get(window.location.origin + '/ws/deezer/history')
                .then( response => this.history = response.data )
                .catch( error => this.error = error.response.data );

        },

        mounted() {
            console.log('Component mounted.');
        }
    }
</script>