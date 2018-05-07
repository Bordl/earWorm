<template>
    <div>
        <div class="card f-xs" :class="isRightAnswer">
            <div class="card-header">
                <div class="level">
                    <div class="flex">
                        <p class="mb-0">
                            <a :href="'/profiles/' + reply.user_id">
                                <span><em v-text="reply.owner.name"></em></span>&nbsp;
                            </a>

                            <span v-text="moment(reply.created_at)"></span>
                        </p>
                    </div>
                    <div v-if="creator && reply.validate == 0" class="float-right">
                        <button class="btn btn-sm btn-outline-success f-xxs" @click="validated">
                            <i class="fas fa-check"></i> Right answer
                        </button>
                    </div>

                    <div v-show="reply.validate == 1" class="green">
                        <div v-if="creator">
                            <button class="btn btn-sm btn-outline-danger f-xxs" @click="unValidated">
                            <i class="fas fa-times"></i> Wrong answer
                        </button>
                        </div>

                        <div v-else>
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
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
                            <button  class="p-2 btn btn-outline-purple btn-sm" @click="dismiss">Cancel</button>
                            <button class="p-2 btn btn-outline-success btn-sm" @click="update">Update</button>
                        </div>
                    </div>
                </div>

                <div v-else>
                    <div v-if="link" class="mb-0">
                        <div class="level">
                            <div class="f-md red">
                                <i class="fab fa-youtube"></i>
                            </div>

                            <a class="flex ml-3" :href="link">
                                <div><em v-html="link.replace(/(^\w+:|^)\/\//, '').substr(0, 30) + '...'"></em></div>
                            </a>
                        </div>

                        <hr>
                    </div>
                        

                    <div v-if="body" v-text="body"></div>

                    <div v-if="owner" class="float-right mt-2">
                        <div v-if="!editing && validate == 0">
                            <button class="pl-3 pr-3 btn btn-outline-danger btn-sm" @click="destroy">Delete</button>
                            <button class="pl-3 pr-3 btn btn-outline-success btn-sm" @click="editing = true">Edit reply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['post', 'reply'],

    data() {
        return{
            editing: false,
            body: this.reply.body,
            link: this.reply.link,
            validate: this.reply.validate,
        };
    },

    computed: {
        signedIn() {
            return window.App.signedIn;
        },

        userID() {
            return window.App.user.id
        },

        owner() {
            return this.authorise(user => this.reply.user_id == user.id)
        },

        creator() {
            return this.userID == this.post.user_id ? true : false
        },

        isRightAnswer() {
            return this.reply.validate == 0 ? '' : 'right-answer'
        }
    },

    methods: {
        dismiss() {
            editing = false
            body = this.reply.body
            link = this.reply.link
            validate = this.reply.validate
        },

        validated() {
            this.validate = 1

            axios.patch('/replies/validate/' + this.reply.id, {
                    user_id: this.post.user_id,
                    validate: this.validate,
                    answered: 1
                })
                .then( response => {
                    flash('You have succesffully validated this answer')

                    this.$emit('answered')
                })
                .catch(err => {
                    flash('Oops! Something went wrong', 'danger')

                    console.log(err)
                })
        },

        unValidated() {
            this.validate = 0

            axios.patch('/replies/validate/' + this.reply.id, {
                    user_id: this.post.user_id,
                    validate: this.validate,
                    answered: 0
                })
                .then( response => {
                    flash('You succesfully invalidated this answer')

                    this.$emit('answered')
                })
                .catch(err => {
                    flash('Oops! Something went wrong', 'danger')

                    console.log(err)
                })
        },

        update() {
            axios.patch('/replies/' + this.reply.id, {
                body: this.body,
                link: this.link,
                validate: this.validate
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
                    this.$emit('destroyed', this.reply.id)
                })
                .catch(err => {
                    flash('Oops! Something went wrong', 'danger')

                    console.log(err)
                })
        },

        moment(time) {
            return moment(time).fromNow()
        }
    }

}
</script>

<style>
.right-answer {
    border: solid 2px #16a085;
}

.right-answer .card-header {
    background-color: rgba(22, 160, 133, 0.25)
}

.right-answer .card-body {
    background-color: rgba(22, 160, 133, 0.125)
}

</style>
