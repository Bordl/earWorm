<template>
  <div>
      <button @click="toggle" class="btn btn-sm btn-block f-xxs" :class="isActive ? 'btn-success' : 'btn-default' ">
          <div :class="isActive ? 'block' : 'hidden'"><i class="fas fa-bell"></i><span class="pl-1">Subscribed</span></div>
          <div :class="isActive ? 'hidden' : 'block'"><i class="fas fa-bell-slash"></i><span class="pl-1">Unsubscribed</span></div>
      </button>
  </div>
</template>

<script>
export default {
    props: ['post'],

    data() {
        return {
            isActive: this.post.isSubscribedTo
        };
    },

    watch: {
        post(){
            return this.isActive = this.post.isSubscribedTo
        }
    },

    methods: {
        toggle() {
            this.isActive == true ? this.unsubscribe() : this.subscribe()
        },

        subscribe() {
            axios.post('/posts/' + this.post.id + '/subscriptions')
                .then(flash('You have subscribed to this post. You will be notified if this post is updated.'))
                .catch(err => {
                    flash('Oops! something went wrong', 'danger')

                    console.log(err)
                })
            this.isActive = !this.isActive
        },

        unsubscribe() {
            axios.delete('/posts/' + this.post.id + '/subscriptions')
                .then(flash('You have successfully unsubscribed from this post. You will no longer be notified.'))
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
