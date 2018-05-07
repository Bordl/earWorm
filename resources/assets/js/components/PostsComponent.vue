<template>
    <div>
        <div v-cloak v-for="post in dataSet" :key="post.id">
            <!-- <div class="card mb-2 mt-2">
                <div class="card-body">
                    <div class="level">
                        <a href="#" class="flex semiBold">
                            <div v-text="post.creator.name"></div>
                        </a>

                        <div class="f-xs"><em v-text="moment(post.created_at)"></em>...</div>
                    </div>
                    
                    <hr>

                    <div class="row level mb-3">
                        <div class="col-8">
                            <h5>What's this song?</h5>
                            <p v-if="post.description" class="f-xs m-0">
                                {{ post.description }}
                            </p>

                            <p v-else class="f-xs m-0">This post has no description.</p>
                        </div>

                        <div class="col-4">
                            <player-component :path="post.recording.path" :postID="post.id"></player-component>
                        </div>
                    </div>

                    <hr class="mt-1 mb-2">

                    <div>
                        <router-link :to="`/posts/${post.id}`">
                            <p class="mb-1">
                                <span><i class="far fa-comment-alt"></i></span>
                                &nbsp;<span v-text="this.comments"></span>  
                            </p>
                        </router-link>
                    </div>
                </div>
            </div> -->




            <post-component :data="post"></post-component>
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
