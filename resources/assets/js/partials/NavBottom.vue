<template>
  <div id="navbar-bottom" class="shadow-bottom pt-3">
      <div class="row justify-content-center">
            <div @click="emit('/home')" class="col-3 border-right text-center purple f-reg">
                <i class="fas fa-home"></i>
            </div>

            <div @click="emit('/home?popular=1')" class="col-3 border-right text-center purple f-reg">
                <i class="fas fa-fire"></i>
            </div>

            <div @click="emit('/home?unanswered=1')" class="col-3 border-right text-center purple f-reg">
                <i class="fas fa-comment-slash"></i>
            </div>

            <div @click="emit('/home?by=' + name)" class="col-3 text-center purple f-reg">
                <i class="fas fa-user"></i>
            </div>

      </div>
  </div>
</template>

<script>
export default {
    computed: {
        name() {
            return App.user.name
        }
    },

    methods: {
        emit(filter) {
            reload(filter)
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
