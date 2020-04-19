<template>
    <div v-if="followings.error" class="alert alert-danger" role="alert">
        <p><b>Oups!</b> {{ followings.error.message }}</p>
        <p v-if="followings.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">Here</a>.</p>
    </div>
    <div v-else>
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
                    <li class="list-group-item d-flex justify-content-between align-items-center"
                        v-for="following in followings.data"
                        v-bind:key="following.id">

                        <div class="col-2">
                            <p>
                                <img class="rounded" v-bind:src="following.picture">
                            </p>
                        </div>

                        <div class="col-2">
                            {{ following.name }}
                        </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
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
                followings: {}
            }
        },

        created() {
            axios.get(window.location.origin + '/ws/deezer/social')
                .then( response => this.followings = response.data )
                //.then( response => { console.log(response.data) })
                .catch( error => { console.log(error.message) });
        },

        mounted() {
            console.log('Component mounted.');
        }
    }
</script>