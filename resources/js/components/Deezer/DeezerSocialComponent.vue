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
    <div v-else-if="followings != null">
        <blockquote v-if="followings.error != null" class="quote-danger">
            <h5>Oups!</h5>
            <p>
                <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
                </svg>
                {{ followings.error.message }}
            </p>
            <p v-if="followings.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">here</a>.</p>
        </blockquote>
        <div v-else-if="followings.data != null">
            <div class="row">
                <div class="col-12">
                    <p>                
                        <a href="#" class="btn btn-primary">Save</a> This action will save or update your data in <i>Matis</i> database.               
                    </p>
                </div>    
            </div>
            <div class="row">            
                <div class="col-6">            
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between"
                            v-for="following in followings.data"
                            v-bind:key="following.id">

                            <div class="col-4">
                                <p>
                                    <img class="rounded" v-bind:src="following.picture">
                                </p>
                            </div>
                            <div class="col-8">
                                <p>
                                    {{ following.name }} <small>(#{{ following.id }})</small>
                                </p>
                            </span>
                            </div>
                        </li>
                    </ul>
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
            formattt: function (seconds) {
                return moment("1900-01-01 00:00:00").add(seconds, 'seconds').format("HH:mm");
            }
        },

        data() {
            return {
                followings: null,
                error: null,
                loadingPage: false
            }
        },

        created() {
            this.loadingPage = true;
            axios.get(window.location.origin + '/api/deezer/social')
                .then((response)  =>  {
                    if (response.status === 200) {
                        this.followings = response.data;
                    }
                    this.loadingPage = false;
                    
                }, (error)  =>  {
                    this.loadingPage = false;
                    console.log(error);
                    this.error = error.response.data;
                });
        },

        mounted() {
            // console.log('Component mounted.');
        }
    }
</script>