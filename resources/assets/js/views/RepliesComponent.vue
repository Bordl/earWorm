<template>
    <div>
        <nav-top :back="true" title="What's this song"></nav-top>

        <div class="margin-nav">
            <div v-if="post" class="row justify-content-center">
                <div class="col-12 mb-3">
                    <button v-if="owner" @click="destroy" class="btn btn-outline-danger btn-block mb-3 p-2 f-reg">Delete my post</button>    
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="level col-12">
                                    <div class="flex pr-1">
                                        <div class="mb-0 light level">
                                            <!-- <div class="level"> -->
                                                <div class="flex">
                                                    <div class="level">
                                                        <avatar-component :profile="post.creator.profile" :width="25" :height="25"></avatar-component>
                                                        <p class="flex pl-2 mb-0 f-xs">
                                                            <router-link :to="`/profiles/${post.creator.slug}`" class="purple">
                                                                <em><span v-text="post.creator.name"></span></em>
                                                            </router-link>
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- <div class="f-xs regular pl-3"><em v-text="moment(post.created_at)"></em> ago...</div> -->
                                            <!-- </div> -->
                                        </div>
                                    </div>

                                    <follow-component :long="false" v-show="!owner" :post="post"></follow-component>
                                    <subscribe-component class="pl-1 pr-1" v-show="!owner" :post="post"></subscribe-component>
                                </div>

                                <hr class="col-10">

                                <div class="col-8">
                                    <p class="mb-0 semiBold">Post description:</p>
                                    <p v-if="post.description" class="f-xs m-0" v-html="post.description"></p>

                                    <p v-else class="f-xs m-0">This post has no description.</p>
                                </div>

                                <div class="col-4">
                                    <player-component :path="post.recording.path" :postID="post.id"></player-component>
                                </div>
                            </div>
                        </div>
                    </div>             
                </div>

                <!-- If validated -->
                <div v-if="reply.validate == 1" v-for="reply in replies" :key="reply.id" class=" col-12 mb-3">
                    <div class="alert alert-success">
                        <a :href="'#reply-' + reply.id">
                            <p class="semiBold text-center f-reg green mb-0">
                                This post has been answered <br>
                                <em class="regular f-xs"><u>See answer</u></em>
                            </p>
                        </a>
                    </div>
                </div>

                
                <add-reply-component @create="add" class="col-12"></add-reply-component>

                <div class="col-12" v-if="replies.length > 0">
                    <p class="text-center f-reg semiBold mb-2">All replies</p>

                    <div v-for="(reply, index) in replies" :key="reply.id" class="mb-3">
                        <reply-component :post="post" :id="'reply-' + reply.id" @destroyed="remove(index)" @answered="add" :reply="reply" @isValid="fetch"></reply-component>
                    </div>
                </div>

                <div v-else>
                    <p class="mb-0">Be the first one to answer this post!</p>
                </div>
            </div>
        </div>

        <nav-bottom :home="false"></nav-bottom>
    </div>
</template>

<script>
import PlayerComponent from '../components/PlayerComponent'
import ReplyComponent from '../components/ReplyComponent'
import SubscribeComponent from '../components/SubscribeComponent'
import FollowComponent from '../components/FollowComponent'
import AddReplyComponent from '../components/AddReplyComponent'
import AvatarComponent from '../components/AvatarComponent'
import NavTop from '../partials/NavTop'
import NavBottom from '../partials/NavBottom'

export default {
    components: {PlayerComponent, ReplyComponent, AddReplyComponent, SubscribeComponent, FollowComponent, NavTop, NavBottom, AvatarComponent},

    data() {
        return {
            post: false,
            replies: false,
            validate: false,
        };
    },

    computed: {
        owner() {
            return this.authorise(user => this.post.user_id == user.id)
        },
    },

    watch: {
        post() {
            return this.post
        }
    },

    created() {        
        this.fetch()
    },

    methods: {
        fetch() {
            axios.get('/replies/' + this.$route.params.id)
                .then(({data}) => {
                    
                    this.post = data.post
                    this.replies = data.replies
                })
        },

        add(reply) {            
            // this.replies.unshift(reply)
            this.fetch()
        },

        remove(index) {
            this.fetch()

            flash('Reply succesffully deleted.')
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
            return moment.distanceInWordsStrict(time, new Date(new Date().getTime() - (1*1000*60*60)))
        }
    }
}
</script>

<style>

</style>
