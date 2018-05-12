import Vue from 'vue';
import Router from 'vue-router';
import Home from './views/Home.vue';
import ProfileComponent from './views/ProfileComponent.vue';
import RepliesComponent from './views/RepliesComponent.vue';
import EarWormComponent from './views/EarWormComponent.vue';

Vue.use(Router);

export default new Router({
    routes: [
      { path: '/', redirect: '/home' },
      {
        path: '/home',
        name: 'Home',
        component: Home,
      },
      {
        path: '/posts/:id',
        name: 'RepliesComponent',
        component: RepliesComponent,
      },
      {
        path: '/profiles/:slug',
        name: 'ProfileComponent',
        component: ProfileComponent,
      },
      {
        path: '/earworm',
        name: 'EarWormComponent',
        component: EarWormComponent,
      },
    ],
    scrollBehavior (to, from, savedPosition) {
      if (to.hash) {
        return {selector: to.hash}
      } else {
          return { x: 0, y: 0 }
      }
    }
  });