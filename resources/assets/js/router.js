import Vue from 'vue';
import Router from 'vue-router';
import Home from './views/Home.vue';
import RepliesComponent from './components/RepliesComponent.vue';
import EarWormComponent from './components/EarWormComponent.vue';

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
        path: '/earworm',
        name: 'EarWormComponent',
        component: EarWormComponent,
      },
    ],
  });