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
	<div v-else-if="account != null && account.error != null" class="alert alert-danger" role="alert">
		<p>
			<svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
    			<path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
			</svg>
			<b>Oups!</b> {{ account.error.message }}
		</p>
        <p v-if="account.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">here</a>.</p>
	</div>
	<div v-else-if="account != null">
		<p><img :src="account.picture"></p>
		<p>#{{ account.deezerId }}</p>
		<p>{{ account.email }}</p>
		<p>{{ account.firstname }}</p>
		<p>{{ account.lastname }}</p>
		<p>{{ account.status }}</p>
		<p>{{ account.inscriptionDate }}</p>
		<p>{{ account.profileLink }}</p>
		<p>{{ account.accessToken }}</p>
		<p>{{ account.country }} ({{ account.lang }})</p>
	</div>
	<div v-else class="alert alert-danger" role="alert">
		<p><b>Oups!</b> Something bad happened...</p>
	</div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.');
        },

        data() {
            return {
                account: null,
                error: null
            }
        },

        created() {
            axios.get(window.location.origin + '/ws/deezer/account')
                .then( response => this.account = response.data )
                .catch( error => this.error = error.response.data );
        },
    }
</script>