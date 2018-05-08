<template>
    <div>
        <nav-top v-if="data" :title="title"></nav-top>

        <nav-bottom :home="false"></nav-bottom>
    </div>
</template>

<script>
import NavTop from '../partials/NavTop'
import NavBottom from '../partials/NavBottom'

export default {
    components: {NavTop, NavBottom},

    data() {
        return {
            title: false,
            data: false,
        };
    },

    computed: {
        editable() {
            return App.user.id == this.$route.params.name ? true : false
        },
    },

    created() {
        this.fetch()
    },

    methods: {
        fetch() {
            axios.get('/profiles/' + this.$route.params.name)
                .then(({data}) => {
                    this.title = data.user.name
                    this.data = data
                })
                .catch(err => {
                    flash('Oops! Something went wrong.', 'danger')

                    console.log(err)
                })
        }
    }


}
</script>

<style>

</style>
