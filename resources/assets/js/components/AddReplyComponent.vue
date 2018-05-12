<template>
	<div>
		<div v-if="signedIn">
			<p class="mb-2 text-center semiBold">Leave a reply</p>
            <form @submit.prevent="addReply">
                <div class="form-group">
                    <label class="f-xs">Please provide a link to YouTube</label>
                    <input type="url" class="form-control" v-model="url">
                </div>

                <div class="form-group">
                    <label class="f-xs">Add a description or ask for more details</label>
                    <textarea name="body" 
                        id="description" 
                        class="form-control"
                        rows="2"
						placeholder="Use the @ symbol to mention other users"
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
	import $ from 'jquery';
	import 'jquery.caret'
	import 'at.js'
	import 'at.js/dist/css/jquery.atwho.css'

	window.$ = window.jQuery = $;

	export default {
		data() {
			return {
                body: '',
                url: '',
				toggled: false,
			};
		},

		mounted(){
			$('#description').atwho({
				at: "@",
				delay: 750,
				callbacks: {
					remoteFilter: function(query, callback) {
						$.getJSON('/api/users', {q:query}, function(usernames) {
							callback(usernames)
						})
					}
				}
			})
		},

		computed: {
			signedIn() {
				return window.App.signedIn;
			},
		},

		watch: {
			url() {
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
					flash('Oops! Something went wrong.', 'danger');

					console.log(error);
					
				})
				.then(({data}) => {					
					this.body = '';
					this.url = ''

					flash('Your reply has been posted');

					this.$emit('create', data)
				})
			}

		}

	}
</script>

<style>
.atwho-view .cur {
    background: #16a085;
    color: white;
    width: 78vw;
}
</style>