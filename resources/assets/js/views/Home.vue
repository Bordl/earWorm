<template>
  <div>
        <h1 class="text-center">Latest Posts</h1>
        
        <hr>
        <router-link :to="`/earworm`">
            <button class="btn btn-outline-success btn-lg btn-block">New earWorm</button>
        </router-link>

        <hr>

        <posts-component></posts-component>

        <router-link :to="`/earworm`">
            <button class="btn btn-create f-xl">+</button>
        </router-link>

        <a href="/signout">
            <button class="btn btn-signout f-lg p-0">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </a>
  </div>
</template>

<script>
import PostsComponent from '../components/PostsComponent'

export default {
    components: {PostsComponent},
}
</script>

<style>

</style>
