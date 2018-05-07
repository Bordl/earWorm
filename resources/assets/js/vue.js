/**
 * We'll load VueJS. Vue (pronounced /vjuː/, like view) is a progressive 
 * framework for building user interfaces. Unlike other monolithic 
 * frameworks, Vue is designed from the ground up to be adoptable.
 */

window.Vue = require('vue');

Vue.prototype.authorise = handler => {
    let user = window.App.user

    return user ? handler(user) : flase
}

window.events = new Vue();
window.flash = function (message, level = "success") {
	window.events.$emit('flash', { message, level });
};
window.reload = function (filter) {
    window.events.$emit('reload', filter)
}

import router from './router.js';
Vue.config.devtools = true;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import App from './App.vue';
const app = new Vue({
    router,
    render: h => h(App),
}).$mount('#app');


