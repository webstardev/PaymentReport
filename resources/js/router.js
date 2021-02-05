import Vue from 'vue';
import Router from 'vue-router';
import guest from './services/middleware/guest';
import auth from './services/middleware/auth';

Vue.use(Router);

const routes = [
  {
    path: '/',
    name: 'brand',
    component: () => import('@/views/pages/brand')
  },
  {
    path: '/brand',
    name: 'brand',
    component: () => import('@/views/pages/brand')
  },
  {
    path: '*',
    component: () => import('@/views/pages/error')
  }
];

const router = new Router({
  mode: 'history',
  linkActiveClass: 'open',
  routes,
  scrollBehavior() {
    return { x: 0, y: 0 };
  }
});

export default router;
