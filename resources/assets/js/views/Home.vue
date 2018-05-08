<template>
    <div>
        <nav-top :back="false" :home="true" :title="title"></nav-top>

        <posts-component class="margin-nav"></posts-component>

        <router-link :to="`/earworm`">
            <button class="btn btn-create f-xl">+</button>
        </router-link>

        <nav-bottom :home="true"></nav-bottom>
  </div>
</template>

<script>
import PostsComponent from '../components/PostsComponent'
import NavTop from '../partials/NavTop'
import NavBottom from '../partials/NavBottom'

export default {
    components: {PostsComponent, NavTop, NavBottom},

    data() {
        return {
            title: 'Latest Posts'
        };
    },

    created() {   
        window.events.$on('reload', payload => this.title = payload.title)
    },

}
</script>

<style>

</style>
