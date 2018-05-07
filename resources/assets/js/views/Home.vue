<template>
    <div>
        <nav-top :home="true" title="Latest Posts"></nav-top>

        <posts-component class="margin-nav"></posts-component>

        <router-link :to="`/earworm`">
            <button class="btn btn-create f-xl">+</button>
        </router-link>

        <nav-bottom></nav-bottom>
  </div>
</template>

<script>
import PostsComponent from '../components/PostsComponent'
import NavTop from '../partials/NavTop'
import NavBottom from '../partials/NavBottom'

export default {
    components: {PostsComponent, NavTop, NavBottom},
}
</script>

<style>

</style>
