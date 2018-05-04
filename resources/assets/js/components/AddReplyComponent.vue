<template>
	<div>
		<div v-if="signedIn">
			<p class="mb-2 text-center semiBold">Leave a reply please</p>
            <form @submit.prevent="addReply">
                <div class="form-group">
                    <label class="f-xs">Please provide a link to YouTube</label>
                    <input type="url" class="form-control" v-model="url">
                </div>

                <div class="form-group">
                    <label class="f-xs">Add a description or ask for more details</label>
                    <textarea name="body" 
                        id="body" 
                        class="form-control"
                        rows="2"
                        v-model="body">
                        
                    </textarea>
                </div>

                <div v-show="toggled" class="form-group">
                    <button class="btn btn-outline-success btn-block">+ Add a reply</button>
                </div>
		    </form>
		</div>

		<p class="text-center" v-else>
			Please <a href="/login">sign in</a> to participate in this discussion.
		</p>

		<hr>
	</div>

	
</template>

<script>
	export default {
		data() {
			return {
                body: '',
                url: '',
				toggled: false,
			};
		},

		computed: {
			signedIn() {
				return window.App.signedIn;
			},
		},

		watch: {
			url() {
				console.log(this.url.length);
				
				return this.url.length === 0 && this.body.length === 0 ? this.toggled = false : this.toggled = true
			},

			body() {
				return this.url.length === 0 && this.body.length === 0 ? this.toggled = false : this.toggled = true
			}
		},

		methods: {
			addReply() {
				axios.post('/posts/' + this.$route.params.id + '/replies', {
                    body: this.body,
                    link: this.url
                })
				.catch(error => {
					flash(error.response.data, 'danger');
				})
					.then(({data}) => {
                        this.body = '';
                        this.url = ''

						flash('Your reply has been posted');

						this.$emit('create')
					})
			}

		}

	}
</script>