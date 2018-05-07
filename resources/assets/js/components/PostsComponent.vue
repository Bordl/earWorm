<template>
    <div>
        <div v-cloak v-if="dataSet.length !== 0" v-for="post in dataSet" :key="post.id">
            <post-component :data="post"></post-component>
        </div>

        <div v-else>
            <p class="f-reg">There are no posts yet.</p>
        </div>
    </div>
</template>

<script>
import PostComponent from './PostComponent'

export default {
    components: {PostComponent},

    data() {
        return {
            dataSet: false,
            user: false,
        };
    },
 
    created() {
        this.fetch()

        window.events.$on('reload', payload => this.fetch(payload))
    },

    methods: {
        fetch(payload = '') {           
            axios.get('/posts' + this.filter(payload))
                .then(({data}) => {
                    this.dataSet = data
                });

            window.scrollTo(0, 0);
        },

        filter(filter) {
            const match = filter.match(/(\?|\&)([^=]+)\=([^\&]+)/)

            return match == null ? '' : match[0]
        }
    }

}
</script>

<style>

</style>
