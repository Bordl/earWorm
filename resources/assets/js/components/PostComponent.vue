<template>
    <div>
        <div class="card mb-2 mt-2">
            <div class="card-body">
                <div class="level">
                    <div class="flex">
                        <p class="mb-0">
                            <router-link :to="`/profiles/${post.user_id}`">
                                <em><span class="light" v-text="post.creator.name"></span>:</em>
                            </router-link>
                            <span class="f-xs regular"><em v-text="moment(post.created_at)"></em>...</span>
                        </p>

                    </div>

                    <follow-component v-show="!owner" :post="post">></follow-component>
                    <!-- <subscribe-component v-show="!owner" :post="post"></subscribe-component> -->
                    <!-- <favorite-component v-show="!owner" :post="post"></favorite-component> -->
                </div>
                
                <hr>

                <div class="row level mb-3">
                    <div class="col-8">
                        <router-link :post="post" :to="`/posts/${post.id}`" class="main-grey">
                            <h5>What's this song?</h5>
                            <p v-if="post.description" class="f-xs m-0">
                                {{ post.description }}
                            </p>

                            <p v-else class="f-xs m-0">This post has no description.</p>
                        </router-link>
                    </div>

                    <div class="col-4">
                        <player-component :path="post.recording.path" :postID="post.id"></player-component>
                    </div>
                </div>

                <hr class="mt-1 mb-2">

                <div>
                    <router-link :post="post" :to="`/posts/${post.id}`" class="level f-xs">
                        <p class="mb-0 flex">
                            <span><i class="far fa-comment-alt"></i></span>
                            &nbsp;<span v-text="post.replies_count"></span>  
                        </p>

                        <div class="semiBold">
                            <p v-if="post.answered == 0" class="red mb-0"><em>Not Answered</em></p>
                            <p v-else class="green mb-0">
                                Answered!&nbsp;
                                <i class="fas fa-check"></i>
                            </p>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PlayerComponent from './PlayerComponent'
import FollowComponent from './FollowComponent'
// import SubscribeComponent from './SubscribeComponent'
// import FavoriteComponent from './FavoriteComponent'

export default {
    components: {PlayerComponent, FollowComponent},

    props: ['data'],

    data() {
        return {
            post: this.data,
        }
    },

    computed: {
        owner() {
            return App.user.id == this.post.user_id ? true : false
        },
    },

    methods: {
        moment() {
            return moment(this.post.created_at).fromNow()
        }
    }

}
</script>

<style>

</style>
