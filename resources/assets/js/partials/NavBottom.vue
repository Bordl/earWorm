<template>
  <div id="navbar-bottom" class="shadow-bottom pt-3">
      <div v-if="isHome" class="row justify-content-center">
            <div @click="emit('/home', 'Latest Posts')" class="col-3 border-right text-center purple f-reg">
                <i class="fas fa-home"></i>
            </div>

            <div @click="emit('/home?popular=1', 'Popular Posts')" class="col-3 border-right text-center purple f-reg">
                <i class="fas fa-fire"></i>
            </div>

            <div @click="emit('/home?unanswered=1', 'Unanswered Posts')" class="col-3 border-right text-center purple f-reg">
                <i class="fas fa-comment-slash"></i>
            </div>

            <div @click="emit('/home?by=' + name, 'Posts by me')" class="col-3 text-center purple f-reg">
                <i class="fas fa-user"></i>
            </div>
      </div>

      <div v-else class="row justify-content-center">
            <div @click="linkTo('/home', 'Latest Posts')" class="col-3 border-right text-center purple f-reg">
                <i class="fas fa-home"></i>
            </div>

            <div @click="linkTo('/home?popular=1', 'Popular Posts')" class="col-3 border-right text-center purple f-reg">
                <i class="fas fa-fire"></i>
            </div>

            <div @click="linkTo('/home?unanswered=1', 'Unanswered Posts')" class="col-3 border-right text-center purple f-reg">
                <i class="fas fa-comment-slash"></i>
            </div>

            <div @click="linkTo('/home?by=' + name, 'Posts by me')" class="col-3 text-center purple f-reg">
                <i class="fas fa-user"></i>
            </div>
      </div>
  </div>
</template>

<script>
export default {
    props: ['home'],

    computed: {
        name() {
            return App.user.name
        },

        isHome() {
            return this.home == false ? false : true
        }
    },

    methods: {
        emit(filter, title) {
            reload(filter, title)
        },

        linkTo(filter, title) {
            this.$router.push('/home')
            setTimeout(() => reload(filter, title), 10)
        }
    }

}
</script>

<style>
#navbar-bottom {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100vw;
    height: 60px;
    background-color: white;
    z-index: 2001;
}

</style>
