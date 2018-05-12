<template>
    <div v-if="dataSet" class="row justify-content-center">
        <nav-top :back="true" :title="title"></nav-top>

        <div class="margin-nav col-11">
            <div class="row mb-2">
                <follow-component class="col-12 p-1 mb-2" :long="true" v-show="!owner" :post="false" @updated="fetch"></follow-component>
                <avatar-component v-if="dataSet" :profile="dataSet.profile" :isProfilePage="true" class="col-4" :width="100" :height="100"></avatar-component>

                <div v-if="dataSet" class="col-8">
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

                        <div v-if="!edit">
                            <div class="col-12 mt-1">
                                <label class="f-xs semiBold mb-1">About {{ title }}</label>
                                <div v-if="biog" v-text="biog"></div>
                                <div v-else>This earWormer has no biographie yet.</div>
                            </div>

                            <div v-if="spotify" class="col-12 f-xs mt-2">
                                <a :href="spotify" class="green">
                                    <i class="fab fa-spotify"></i>&nbsp;earWormer's spotify playlist
                                </a>
                            </div>
                        </div>

                        <div v-else class="col-12 mt-2">
                            <form @submit.prevent>
                                <div class="form-group">
                                    <label class="f-xxs">Let other earWormers what you all about</label>
                                    <textarea rows="1" type="text" class="form-control" v-model="biog" placeholder="I am all about..."></textarea>

                                    <label class="mt-2 f-xxs">Share your latest spotify playlist</label>
                                    <input type="url" class="form-control" v-model="spotify">
                                </div>
                            </form>

                            <div class="level float-right">
                                <button class="btn mr-1 pl-3 pr-3 btn-sm btn-outline-danger" @click="toggle">Cancel</button>
                                <button class="btn ml-1 pl-3 pr-3 btn-sm btn-outline-success" @click="update">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-2 pt-2">
                <div class="col-8 btn btn-block btn-sm btn-success">
                    <div>{{ title }} has <span v-text="dataSet.profile.xp"></span> xp</div>
                </div>

                <button v-if="owner && !edit" @click="toggle" class="col-8 btn btn-block btn-sm btn-outline-purple">
                    Edit my profile
                </button>
            </div>

            <hr>

            <div class="row">
                <div class="col-12 mb-2">
                    <div class="text-center semiBold f-md"><span v-text="title"></span>'s posts</div>
                </div>

                <div class="col-12 text-center" v-if="dataSet.posts.length == 0">This earWormer hasn't posted anything yet.</div>

                <div class="col-3 mt-2 pt-2 pr-1 pl-1" v-else v-for="post in dataSet.posts" :key="post.id">
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
            edit: false,
            biog: '',
            spotify: '',
        };
    },

    computed: {
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
                    this.biog = data.profile.biog
                    this.spotify = data.profile.spotify
                })
                .catch(err => {
                    flash('Oops! Something went wrong.', 'danger')

                    console.log(err)
                })
        },

        toggle() {
            this.edit = !this.edit
        },

        update() {
            axios.patch('/profiles/' + this.$route.params.slug, {
                    'biog': this.biog,
                    'spotify': this.spotify,
                })
                .then(response => {
                    flash('Your informations are now up to date.')
                    this.toggle()
                })
                .catch(err => {
                    flash('Oops! Something went wrong', 'danger')
                    this.toggle()
                    console.log(err);
                })

        }
    }


}
</script>

<style>

</style>
