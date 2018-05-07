<template>
    <div id="navbar-top" class="shadow-top p-3">
        <div class="row flex-center">
            <div v-if="!home" class="col-3 pr-0 f-xs">
                <div @click="$router.push('/home')">
                    <i class="fas fa-chevron-left"></i>
                    &nbsp;Back
                </div>
            </div>

            <div v-else class="col-3 pr-0 f-xs"></div>

            <div class="col-6 text-center semiBold" v-text="pageName"></div>

            <user-notifications-component class="col-1 flex-center pl-2 pr-2 f-xs"></user-notifications-component>
            
            <div id="hamburger" class="col-1 flex-center pl-2 pr-2 f-xs" @click="toggle">
                <div :class="isActive ? 'hidden' : 'block'">
                    <i class="fas fa-bars"></i>
                </div>

                <div :class="this.isActive ? 'block' : 'hidden'">
                    <i class="fas fa-times"></i>
                </div>
            </div>
        </div>

        <div id="menu" class="flex-center container f-reg">
            <div class="col-12 justify-content-center">
                <ul>
                    <li>
                        <a href="/signout" class="purple">
                            <i class="fas fa-sign-out-alt"></i>
                            &nbsp;Sign out
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div id="menu" :class="menuClass">
            <div class="container full-height flex-center">
                <div class="row">
                    <div class="col-12">
                        <ul>
                            <li class="f-reg semiBold pt-1 pb-1 main-grey">
                                <a href="/signout">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
  </div>
</template>

<script>
import UserNotificationsComponent from '../components/UserNotificationsComponent'

export default {
    props: ['title', 'home'],

    components: {UserNotificationsComponent},

    data() {
        return {
            isActive: false,
            pageName: this.title
        };
    },


    computed: {
        menuClass() {
            return this.isActive ? "menu-active" : 'menu-toggled'
        },

        translateValue() {
            return this.isActive ? "0" : '100'
        },
    },

    created() {
        window.events.$on('reload', payload => this.pageName = payload.title)
    },

    methods: {
        toggle() {
            this.isActive = ! this.isActive
        
            document.getElementById('menu').style.transform = "translateX(" + this.translateValue + "vw)"
        },
    }


}
</script>

<style>
#navbar-top {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 60px;
    background-color: white;
    z-index: 2001;
}

#menu {
    min-height: 100vh;
    position: fixed;
    top: 0;
    z-index: 2;
    transition: transform 0.3s;
    transform: translateX(100vw);
    background-color: white;
    z-index: 2002;
    opacity: .95;
    width: 100vw;
    margin-left: -16px;
}

#menu ul {
    list-style-type: none
}

#hamburger {
    z-index: 3003;
}



</style>
