<template>
	<div>
		<label for="file" class="btn btn-block btn-sm btn-outline-success p-1">New Image</label>
		<input id="file" class="hidden" style="visibility:hidden;" type="file" accept="image/*" @change="onChange" />
	</div>
</template>

<script>
	export default {
		methods: {
			onChange(e) {
				if (! e.target.files.length) return;

				let file = e.target.files[0];

				let reader = new FileReader();

				reader.readAsDataURL(file);

				reader.onload = e => {
					let src = e.target.result;
					
					this.$emit('loaded', { src, file});
				};
			}
		}
	}
</script>