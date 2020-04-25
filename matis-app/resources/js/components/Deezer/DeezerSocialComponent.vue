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
            You can get connected <a href="/auth/deezer/login">ere</a>.
        </p>
    </div>
    <div v-else-if="followings != null">
        <div v-if="followings.error != null" class="alert alert-danger" role="alert">
        <p>
            <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
            </svg>
            <b>Oups!</b> {{ followings.error.message }}
        </p>
        <p v-if="followings.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">here</a>.</p>
        </div>
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
                followings: null,
                error: null,
                loadingPage: false
            }
        },

        created() {
            this.loadingPage = true;
            axios.get(window.location.origin + '/api/deezer/social')
                .then((response)  =>  {
                    this.loadingPage = false;

                    if (response.status === 200) {
                        this.followings = response.data;
                    } else {
                        console.log(response);
                    }
                }, (error)  =>  {
                    this.loadingPage = false;
                    this.error = error.response.data;
                });
        },

        mounted() {
            console.log('Component mounted.');
        }
    }
</script>