import Vue from 'vue';
import Router from 'vue-router';
import guest from './services/middleware/guest';
import auth from './services/middleware/auth';

Vue.use(Router);

const routes = [
  {
    path: '/',
    name: 'brand',
    beforeEnter: auth,
    component: () => import('@/views/pages/brand')
  },
  {
    path: '/brand',
    name: 'brand',
    beforeEnter: auth,
    component: () => import('@/views/pages/brand')
  },
  {
    path: '/key-in',
    name: 'key-in',
    beforeEnter: auth,
    component: () => import('@/views/pages/key-in')
  },
  {
    path: '/login',
    name: 'login',
    beforeEnter: guest,
    component: () => import('@/views/pages/login')
  },
  {
    path: '/register',
    name: 'register',
    beforeEnter: guest,
    component: () => import('@/views/pages/register')
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
