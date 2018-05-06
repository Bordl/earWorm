<template>
  <div class="margin-nav">
        <div @click="$router.push('/home')" class="back">
            <i class="fas fa-chevron-left"></i>
            &nbsp;Back
        </div>

        <div v-if="post" class="row justify-content-center">
            <div class="col-11 mb-3">
                <button v-if="owner" @click="destroy" class="btn btn-outline-danger btn-block mb-3 p-2 f-reg">Delete my post</button>    
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

            <!-- If validated -->
            <div v-if="reply.validate == 1" v-for="reply in replies" :key="reply.id" class=" col-11 mb-3">
                <div class="alert alert-success">
                    <a :href="'#reply-' + reply.id">
                        <p class="semiBold text-center f-reg green mb-0">
                            This post has been answered <br>
                            <em class="regular f-xs"><u>See answer</u></em>
                        </p>
                    </a>
                </div>
            </div>

            
            <add-reply-component @create="fetch" class="col-11"></add-reply-component>

            <div class="col-11" v-if="replies.length > 0">
                <p class="text-center f-reg semiBold mb-2">All replies</p>

                <div v-for="reply in replies" :key="reply.id" class="mb-3">
                    <reply-component :post="post" :id="'reply-' + reply.id" @destroyed="fetch" @answered="fetch" :reply="reply"></reply-component>
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

    computed: {
        owner() {
            return App.user.id == this.post.user_id ? true : false
        }
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

        destroy() {
            axios.delete('/posts/' + this.$route.params.id)
                .then(({data}) => {
                    flash('Post succesfully deleted!')

                    this.$router.push('/home')
                    }
                )
                .catch(err => {
                    flash('Oops! Something went wrong', 'danger')
                    console.log(err)
                })
        },

        moment(time) {
            return moment(time).fromNow()
        }
    }
}
</script>

<style>

</style>
