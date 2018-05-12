<template>
	<div>

		<form v-if="canUpdate && isProfilePage" method="POST" enctype="multipart/form-data" class="mb-2 mt-1">
			<image-upload-component name="avatar" @loaded="onLoad"></image-upload-component>
		</form>

        <img v-cloak src="img/pixel.png" :style="image" :width="width" :height="height" class="avatar-img img-fluid">

	</div>
	
</template>

<script>
import ImageUploadComponent from "./ImageUploadComponent.vue";

export default {
    props: ['profile', 'isProfilePage', 'width', 'height'],

	components: { ImageUploadComponent },

	data() {
		return {
            image: 'background: url(\'storage/' + this.profile.avatar_path + '\') center center no-repeat; background-size: cover;',
		};
    },

	computed: {
		canUpdate() {
			return App.user.id === this.profile.user_id
		}
	},

	methods: {
		onLoad(avatar) {
			this.image = 'background: url(\'' + avatar.src + '\') center center no-repeat; background-size: contain;';

			this.persist(avatar.file);
		},

		persist(avatar) {
			let data = new FormData();

			data.append('avatar', avatar)

			axios.post(`/api/users/${App.user.slug}/avatar`, data)
            .then(response => {
                console.log(response);
                flash('Avatar uploaded!')
            })
		}
	}
}
</script>

<style>
.avatar-img {
    border-radius: 100%;
}
</style>
