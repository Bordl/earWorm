<template>
	<div id="flash" class="alert alert-flash" :class="'alert-'+level" role="alert" v-show="show" v-text="body"></div>

</template>

<script>
	export default {
		props: ['message'],

		data() {
			return {
				body: this.message,
				level: 'success',
				show: false,
				translateValue: 0,
			}
		},

		created() {
			if (this.message) {
				this.flash()
			}

			window.events.$on('flash', data => this.flash(data))
		},

		methods: {
			flash(data) {
				if (data) {
					this.body = data.message
					this.level = data.level
				}
				this.show = true
				this.translateValue = 0

				setTimeout(() => this.toggle(), 200)
				setTimeout(() => this.hide(), 3000)
				
			},

			toggle() {
				document.getElementById('flash').style.transform = "translateX(" + this.translateValue + "vw)"
			},

			hide() {
				this.translateValue = 100
				setTimeout(() => this.toggle(), 200)
				setTimeout(() => this.show = false, 3000)
			}
		}


	}
</script>

<style>
	#flash {
		position: fixed;
		right: 25px;
		bottom: 25px;
		transform: translateX(100vw);
		transition: transform 0.3s;
	}
</style>
