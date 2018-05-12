<template>
    <div v-if="dataSet" class="row justify-content-center">
        <nav-top :back="true" :title="title"></nav-top>

        <div class="margin-nav col-11">
            <div class="row mb-2">
                <follow-component class="col-12 p-1 mb-2" :long="true" v-show="!owner" :post="false" @updated="fetch"></follow-component>
                <avatar-component v-if="dataSet" :profile="dataSet.profile" :isProfilePage="true" class="col-4" :width="100" :height="100"></avatar-component>

                <div class="col-8">
                    <div class="row">
                        <div class="col-6 p-1">
                            <div class="btn btn-sm btn-block btn-outline-purple">
                                <p class="mb-0">Following <span v-text="dataSet.following.length"></span></p>
                            </div>
                        </div>
                        <div class="col-6 p-1">
                            <div class="btn btn-sm btn-block btn-outline-purple">
                                <p class="mb-0">Followers <span v-text="dataSet.followers.length"></span></p>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <div v-if="dataSet.profile.biog" v-text="dataSet.profile.biog"></div>
                            <div v-else>This earWormer has no biographie yet.</div>
                        </div>

                        <div v-if="dataSet.profile.spotify" class="btn btn-link f-xs mt-2">
                            <i class="fab fa-spotify"></i>&nbsp;earWormer's spotify playlist
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-2 pt-2">
                <div class="col-8 btn btn-block btn-sm btn-success">
                    <div>{{ title }} has <span v-text="dataSet.profile.xp"></span> xp</div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12 mb-2">
                    <div class="text-center semiBold f-md"><span v-text="title"></span>'s posts</div>
                </div>

                <div class="col-12 text-center" v-if="dataSet.posts.length == 0">This earWormer hasn't posted anything yet.</div>

                <div class="row mt-2" v-else v-for="post in dataSet.posts" :key="post.id">
                    <div class="col-4">
                        <div class="row">
                            <div class="col-12">
                                <player-component :path="post.recording.path" :postID="post.id"></player-component>
                            </div>
                            <div class="col-12 text-center mt-1">
                                <router-link :post="post" :to="`/posts/${post.id}`" class="green f-xs">
                                    <em>See post</em>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <nav-bottom :home="false"></nav-bottom>
    </div>
</template>

<script>
import NavTop from '../partials/NavTop'
import NavBottom from '../partials/NavBottom'
import AvatarComponent from '../components/AvatarComponent'
import FollowComponent from '../components/FollowComponent'
import PlayerComponent from '../components/PlayerComponent'

export default {
    components: {NavTop, NavBottom, AvatarComponent, FollowComponent, PlayerComponent},

    data() {
        return {
            title: false,
            dataSet: false,
        };
    },

    computed: {
        editable() {
            return App.user.id == this.dataSet.profile.user_id ? true : false
        },

        owner() {
            return App.user.id == this.dataSet.profile.user_id ? true : false
        },
    },

    created() {
        this.fetch()
    },

    methods: {
        fetch() {
            axios.get('/profiles/' + this.$route.params.slug)
                .then(({data}) => {
                    this.title = data.user.name
                    this.dataSet = data
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
