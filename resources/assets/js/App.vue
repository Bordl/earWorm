<template>
  <div v-cloak id="app">
        <div class="container mt-3">
            <router-view />

            <flash-component message=""></flash-component>
        </div>
    </div>
</template>

<script>
import FlashComponent from './components/FlashComponent'

export default {
    components: {FlashComponent},
}
</script>

<style>

</style>