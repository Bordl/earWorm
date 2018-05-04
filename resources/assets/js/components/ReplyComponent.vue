<template>
    <div>
        <div class="card f-xs">
            <div class="card-header">
                <div class="level">
                    <a href="" class="flex">
                        <p class="mb-0">Reply by <span>User ID to Name</span></p>
                    </a>

                    <div>{time} ago (moment)</div>
                </div>
            </div>

            <div class="card-body">
                <div v-if="editing">
                    <div class="form-group">
                        <label>Link:</label>
                        <input v-model="link" class="form-control f-xs main-grey " />

                        <label class="mt-2">Description:</label>
                        <textarea rows="2" v-model="body" class="form-control f-xs main-grey"></textarea>

                        <div class="mt-2 float-right">
                            <button  class="p-2 btn btn-outline-purple btn-sm" @click="editing = false">Cancel</button>
                            <button class="p-2 btn btn-outline-success btn-sm" @click="update">Update</button>
                        </div>
                    </div>
                </div>

                <div v-else>
                    <div v-if="link" class="mb-0 level">
                        <div class="f-md">
                            <i class="fab fa-youtube"></i>
                        </div>

                        <a class="flex ml-3" :href="link">
                            <div><em v-html="link.replace(/(^\w+:|^)\/\//, '')"></em></div>
                        </a>
                    </div>
                        

                    <div v-if="body" v-text="body"></div>

                    <div v-if="authorise" class="float-right mt-2">
                        <div v-if="!editing">
                            <button class="p-2 btn btn-outline-danger btn-sm" @click="destroy">Delete</button>
                            <button class="p-2 btn btn-outline-success btn-sm" @click="editing = true">Edit reply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['reply'],

    data() {
        return{
            editing: false,
            body: this.reply.body,
            link: this.reply.link
        };
    },

    computed: {
        signedIn() {
            return window.App.signedIn;
        },

        userID() {
            return window.App.user.id
        },

        authorise() {
            return this.userID == this.reply.user_id ? true : false
        }
    },

    methods: {
        update() {
            axios.patch('/replies/' + this.reply.id, {
                body: this.body,
                link: this.link,
            })
                .then( response => {
                    flash('Reply successfully updated!')

                    this.editing = false
                })
                .catch(err => {
                    flash('Oops! Something went wrong', 'danger')

                    console.log(err)
                })
        },

        destroy() {
            axios.delete('/replies/' + this.reply.id)
                .then( response => {
                    flash('Reply successfully deleted!')

                    this.$emit('destroyed')
                })
                .catch(err => {
                    flash('Oops! Something went wrong', 'danger')

                    console.log(err)
                })
        }
    }

}
</script>

<style>

</style>
