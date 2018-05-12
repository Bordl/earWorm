<template>
    <div>
        <li v-if="notifications.length" id="notifications" class="dropdown">
            <a href="#" class="dropdow-toggle green" data-toggle="dropdown">
                <i class="fas fa-bell"></i>
                <span class="notification-number text-center semiBold" v-text="notifications.length"></span>
            </a>

            <ul class="dropdown-menu f-xs">
                <li v-for="notification in notifications" :key="notification.id">
                    <div class="card">
                        <div class="card-body p-3 bg-green">
                            <div v-text="notification.data.message" @click="markAsRead(notification)"></div>
                        </div>
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
        this.init()
        this.fetch()
    },

    methods: {
        init(){
            setInterval(() => this.fetch(), 3*60*1000)
            // window.onclick = () => this.fetch();
        },

        fetch() {
            axios.get('/profiles/' + App.user.slug + '/notifications')
                .then(({data}) => {
                    this.notifications = data
                })
                .catch(err => {
                    flash('Oops! something went wrong.', 'danger')

                    console.log(err);                    
                })
        },

        markAsRead(notification){
            axios.delete('/profiles/' + App.user.slug + '/notifications/' + notification.id)
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
    display: block;
    width: 95vw;
    border-radius: 0;
    padding: 0;
    border: none;
}

.notification-number {
    position: absolute;
    top: 7px;
    left: 7px;
    z-index: 2001;
    width: 10px;
    height: 10px;
    background: #8e44ad;
    border-radius: 100%;
    color: white;
    font-size: .5rem;
}

</style>
