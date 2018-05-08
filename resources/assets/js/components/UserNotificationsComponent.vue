<template>
    <div>
        <li v-if="notifications.length" id="notifications" class="dropdown">
            <a href="#" class="dropdow-toggle green" data-toggle="dropdown">
                <i class="fas fa-bell"></i>
                <span class="notification-number text-center semiBold" v-text="notifications.length"></span>
            </a>

            <ul class="dropdown-menu f-xs">
                <li v-for="notification in notifications" :key="notification.id" class="border-bottom p-2">
                    <div class="green" v-text="notification.data.message" @click="markAsRead(notification)">

                    </div>
                </li>
            </ul>
        </li>

        <div v-else>
            <i class="fas fa-bell-slash"></i>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return{
            notifications: false,
        };
    },
    
    created() {
        this.fetch()
    },

    methods: {
        fetch() {
            axios.get('/profiles/' + App.user.id + '/notifications')
                .then(({data}) => {
                    this.notifications = data
                })
                .catch(err => {
                    flash('Oops! something went wrong.', 'danger')

                    console.log(err);                    
                })
        },

        markAsRead(notification){
            axios.delete('/profiles/' + App.user.id + '/notifications/' + notification.id)
                .then( response => {
                    this.fetch()

                    this.$router.push('/posts/' + notification.data.postID)

                })
                .catch(err => {
                    flash('Oops! something went wrong.', 'danger')

                    console.log(err);                    
                })
        }
    }
}
</script>

<style>
.dropdown-menu.show {
    position: fixed;
    transform: translate3d(-342px, 29px, 0px) !important;
    top: 50px;
    left: -1px;
    display: block;
    width: 102vw;
    border-radius: 0;
    padding: 15px 25px;
    background-color: #eee;
}

.notification-number {
    position: absolute;
    top: 7px;
    left: 7px;
    z-index: 3003;
    width: 10px;
    height: 10px;
    background: #8e44ad;
    border-radius: 100%;
    color: white;
    font-size: .5rem;
}

</style>
