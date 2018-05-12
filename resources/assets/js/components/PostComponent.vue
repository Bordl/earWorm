<template>
    <div>
        <div class="card mb-2 mt-2">
            <div class="card-body">
                <div class="level">
                    <div class="flex mr-3">
                        <div class="mb-0 light level">
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

                            <div class="f-xs regular float-right"><em v-text="moment(post.created_at)"></em> ago...</div>
                        </div>
                    </div>

                    <follow-component :long="false" v-show="!owner" :post="post"></follow-component>
                    <subscribe-component class="pl-1 pr-1" v-show="!owner" :post="post"></subscribe-component>
                </div>
                
                <hr>

                <div class="row level mb-3">
                    <div class="col-8">
                        <router-link :post="post" :to="`/posts/${post.id}`" class="main-grey">
                            <h5>What's this song?</h5>
                            <p v-if="post.description" class="f-xs m-0" v-html="post.description"></p>

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
import SubscribeComponent from './SubscribeComponent'
import AvatarComponent from './AvatarComponent'


export default {
    components: {PlayerComponent, FollowComponent, SubscribeComponent, AvatarComponent},

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
        moment(time) {
            return moment.distanceInWordsStrict(time, new Date(new Date().getTime() - (1*1000*60*60)))
        }
    }

}
</script>

<style>

</style>
