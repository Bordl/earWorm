<template>
  <div class="margin-nav">
        <div @click="$router.go(-1)" class="back">
            <i class="fas fa-chevron-left"></i>
            &nbsp;Back
        </div>

        <div v-if="post" class="row justify-content-center">
            <div class="col-11 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h5 class="col-12 text-center f-lg mb-3">What's this song?</h5>
                            <div class="col-7">
                                <p class="mb-0 semiBold">Post description:</p>
                                <p v-if="post.description" class="f-xs m-0">
                                    {{ post.description }}
                                </p>

                                <p v-else class="f-xs m-0">This post has no description.</p>
                            </div>

                            <div class="col-5">
                                <player-component :path="post.recording.path" :postID="post.id"></player-component>
                            </div>
                        </div>

                        <hr> 

                        <div class="level mt-3">
                            <div class="flex">
                                <p class="mb-0 semiBold">By:
                                    <router-link :to="`/profiles/${post.user_id}`">
                                        <em><span class="light" v-text="post.creator.name"></span></em>
                                    </router-link>
                                </p>
                            </div>

                            <div class="f-xs"><em v-text="moment(post.created_at)"></em>...</div>
                        </div>
                    </div>
                </div>             
            </div>
            
            <add-reply-component @create="fetch" class="col-11"></add-reply-component>

            <div class="col-11" v-if="replies.length > 0">
                <p class="text-center f-reg semiBold mb-2">All replies</p>

                <div v-for="reply in replies" :key="reply.id">
                    <reply-component @destroyed="fetch" :reply="reply"></reply-component>
                </div>
            </div>

            <div v-else>
                <p class="mb-0">Be the first one to answer this post!</p>
            </div>
        </div>


  </div>
</template>

<script>
import PlayerComponent from './PlayerComponent'
import ReplyComponent from './ReplyComponent'
import AddReplyComponent from './AddReplyComponent'

export default {
    components: {PlayerComponent, ReplyComponent, AddReplyComponent},

    data() {
        return {
            post: false,
            replies: false
        };
    },

    created() {        
        this.fetch()
    },

    methods: {
        fetch() {
            axios.get('/posts/' + this.$route.params.id)
                .then(({data}) => {
                    this.post = data
                    this.replies = data.replies
                })
        },

        moment() {
            return moment(this.post.created_at).fromNow()
        }
    }
}
</script>

<style>

</style>
