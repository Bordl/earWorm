<template>
  <div>
      <button v-cloak @click="toggle" class="btn btn-sm btn-block f-xxs" :class="isActive ? 'btn-success' : 'btn-default' ">
          <div :class="isActive ? 'block' : 'hidden'"><i class="fas fa-star"></i><span class="pl-1">Following</span></div>
          <div :class="isActive ? 'hidden' : 'block'"><i class="far fa-star"></i><span class="pl-1">Unfollowed</span></div>
      </button>
  </div>
</template>

<script>
export default {
    props: ['post'],

    data() {
        return {
            isActive: false
        };
    },

    created() {
        this.isFollowing()
    },

    methods: {
        isFollowing(){
            axios.get('/users/' + this.post.creator.name)
                .then(({data}) => this.isActive = data)
                .catch(err => console.log(err))
        },

        toggle() {
            this.isActive == true ? this.unfollow() : this.follow()
        },

        follow() {
            axios.post('/users/' + this.post.creator.name + '/follow')
                .then(flash('You are now following ' + this.post.creator.name + '. You will be notified when they post new Earworms.'))
                .catch(err => {
                    flash('Oops! something went wrong', 'danger')

                    console.log(err)
                })
            this.isActive = !this.isActive
        },

        unfollow() {
            axios.delete('/users/' + this.post.creator.name + '/follow')
                .then(flash('You are not following ' + this.post.creator.name + ' anymore. You will no longer be notified.'))
                .catch(err => {
                    flash('Oops! something went wrong', 'danger')

                    console.log(err)
                })
            this.isActive = !this.isActive
        }
    }
}
</script>

<style>

</style>
