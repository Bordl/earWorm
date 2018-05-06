<template>
    <button class="btn btn-sm f-xxs" :class="favoriteClass" @click="toggle">
        <i class="far fa-heart"></i>&nbsp;<span v-text="favoriteText"></span>
    </button>
</template>

<script>
export default {
    props: ['post'],

    data() {
        return{
            active: this.post.isFavorited
        };
    },

    computed: {
        favoriteClass() {
            return this.active == false ? 'btn-default' : 'btn-success'
        },

        favoriteText() {
            return this.active == false ? 'Not Favorited' : 'Favorited'
        },

        endpoint() {
            return '/posts/' + this.post.id + '/favorites'
        }
    },

    methods: {
        toggle() {
            this.active ? this.destroy() : this.create()
        },

        create() {
            axios.post(this.endpoint)
                .then(() => flash('Successfully subscribed to this post'))
                .catch((err) => {
                    flash('Oops! Something went wrong.')

                    console.log(err)
                })
            
            this.active = true
        },

        destroy() {
            axios.delete(this.endpoint)
                .then(() => flash('Successfully unsubscribed to this post'))
                .catch((err) => {
                    flash('Oops! Something went wrong.')

                    console.log(err)
                })

            this.active = false
        }
    }

}
</script>

<style>

</style>
