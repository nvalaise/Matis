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
	<div v-else-if="account != null">
		<blockquote v-if="account.error != null" class="quote-danger">
	        <h5>Oups!</h5>
	        <p>
	            <svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
	                <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
	            </svg>
	            {{ account.error.message }}
	        </p>
	        <p v-if="account.error.code === 300"> Your session has expired. Refresh your token <a href="/auth/deezer/login">here</a>.</p>
        </blockquote>
		<div v-else-if="account != null">
			<div class="row">
				<div class="col-2">
					<p><img :src="account.picture" class="rounded img-fluid"></p>		
				</div>
				<div class="col-10">
					<h4>{{ account.name }} <small>(#{{ account.id }})</small></h4>
					<p><a :href="account.link" target="_blank">{{ account.linkgit }}</a></p>
					<p>Flow (API): <a :href="account.tracklist" target="_blank">tracklist</a></p>
				</div>
			</div>

			<form>
				<div class="form-group row">
					<label for="InputAccessToken" class="col-2 col-form-label">Access Token</label>
					<div class="col-10">
						<input type="email" class="form-control" id="InputAccessToken" aria-describedby="tokenHelp" :value="access_token">
						<small id="tokenHelp" class="form-text text-muted">Try it out right here: <a href="https://developers.deezer.com/api/explorer" target="_blank">Deezer Explorer</a></small>
					</div>
				</div>
				<div class="form-group row">
					<label for="InputMail" class="col-2 col-form-label">Email</label>
					<div class="col-10">
						<input type="text" readonly class="form-control-plaintext" id="InputMail" :value="account.email" disabled>
					</div>
				</div>
				<div class="form-group row">
					<label for="InputName" class="col-2 col-form-label">Name</label>
					<div class="col-5">
						<input type="text" class="form-control" id="InputName" :value="account.firstname" disabled>
					</div>
					<div class="col-5">
						<input type="text" class="form-control" id="InputName" :value="account.lastname" disabled>
					</div>
				</div>
				<div class="form-group row">
					<label for="InputDate" class="col-2 col-form-label">Registration</label>
					<div class="col-10">
						<input type="text" readonly class="form-control-plaintext" id="InputDate" :value="account.inscription_date" disabled>
					</div>
				</div>
			</form>
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
        mounted() {
            // console.log('Component mounted.');
        },

        data() {
            return {
                account: null,
                access_token: null,
                error: null,
                loadingPage: false
            }
        },

        created() {
        	this.loadingPage = true;
            axios.get(window.location.origin + '/api/deezer/account')
            	.then((response)  =>  {
                    if (response.status === 200) {
                    	this.account = response.data.response;
                    	this.access_token = response.data.access_token;
                    }
            		this.loadingPage = false;

                }, (error)  =>  {
                	this.loadingPage = false;
                	console.log(error);
                	this.error = error.response.data;
                });
        },
    }
</script>