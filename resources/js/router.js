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
    component: () => import('@/views/pages/brand/index')
  },
  {
    path: '/brand',
    name: 'brand',
    beforeEnter: auth,
    component: () => import('@/views/pages/brand/index')
  },
  {
    path: '/key-in',
    name: 'key-in',
    beforeEnter: auth,
    component: () => import('@/views/pages/key-in/index')
  },
  {
    path: '/income-report',
    name: 'income-report',
    beforeEnter: auth,
    component: () => import('@/views/pages/income-report/index')
  },
  {
    path: '/login',
    name: 'login',
    beforeEnter: guest,
    component: () => import('@/views/pages/login/index')
  },
  {
    path: '/register',
    name: 'register',
    beforeEnter: guest,
    component: () => import('@/views/pages/register/index')
  },
  {
    path: '*',
    component: () => import('@/views/pages/error/index')
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
