<template>
  <div>
      <button v-cloak @click="toggle" class="btn btn-sm btn-block f-xxs" :class="isActive ? 'btn-success' : 'btn-default' ">
          <div :class="isActive ? 'block' : 'hidden'"><i class="fas fa-star"></i><span class="pl-1" v-show="long">Following</span></div>
          <div :class="isActive ? 'hidden' : 'block'"><i class="far fa-star"></i><span class="pl-1" v-show="long">Not following</span></div>
      </button>
  </div>
</template>

<script>
export default {
    props: ['post', 'long'],

    data() {
        return {
            isActive: false
        };
    },

    computed: {
        endpoint() {
            return this.post ? this.post.creator.slug : this.$route.params.slug
        },

        name() {
            return this.post ? this.post.creator.name : this.formatSlug(this.$route.params.slug)
        }
    },

    created() {
        this.isFollowing()
    },

    methods: {
        isFollowing(){
            axios.get('/users/' + this.endpoint)
                .then(({data}) => this.isActive = data)
                .catch(err => console.log(err))
        },

        toggle() {
            this.isActive == true ? this.unfollow() : this.follow()
        },

        follow() {
            axios.post('/users/' + this.endpoint + '/follow')
                .then(flash('You are now following ' + this.name + '. You will be notified when they post new Earworms.'))
                .catch(err => {
                    flash('Oops! something went wrong', 'danger')

                    console.log(err)
                })

            this.isActive = !this.isActive
            this.$emit('updated')
        },

        unfollow() {
            axios.delete('/users/' + this.endpoint + '/follow')
                .then(flash('You are not following ' + this.name + ' anymore. You will no longer be notified.'))
                .catch(err => {
                    flash('Oops! something went wrong', 'danger')

                    console.log(err)
                })

            this.isActive = !this.isActive
            this.$emit('updated')
        },

        formatSlug(slug) {
            slug = slug.split('-').join(' ')
            return slug.replace(/\w\S*/g, function(txt){
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
            })
        }
    }
}
</script>

<style>

</style>
