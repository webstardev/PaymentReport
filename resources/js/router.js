import Vue from 'vue';
import Router from 'vue-router';
import guest from './services/middleware/guest';
import auth from './services/middleware/auth';

Vue.use(Router);

const routes = [
  {
    path: '/',
    name: 'income-key-in',
    beforeEnter: auth,
    component: () => import('@/views/pages/income-key-in/index')
  },
  {
    path: '/income-key-in',
    name: 'income-key-in',
    beforeEnter: auth,
    component: () => import('@/views/pages/income-key-in/index')
  },
  {
    path: '/expenses-key-in',
    name: 'expenses-key-in',
    beforeEnter: auth,
    component: () => import('@/views/pages/expenses-key-in/index')
  },
  {
    path: '/create-new-item',
    name: 'create-new-item',
    beforeEnter: auth,
    component: () => import('@/views/pages/brand/index')
  },
  {
    path: '/income-report',
    name: 'income-report',
    beforeEnter: auth,
    component: () => import('@/views/pages/income-report/index')
  },
  {
    path: '/expenses-report',
    name: 'expenses-report',
    beforeEnter: auth,
    component: () => import('@/views/pages/expenses-report/index')
  },
  {
    path: '/expenses-india',
    name: 'expenses-india',
    beforeEnter: auth,
    component: () => import('@/views/pages/expenses-india/index')
  },
  {
    path: '/cashflow-report',
    name: 'cashflow-report',
    beforeEnter: auth,
    component: () => import('@/views/pages/cashflow-report/index')
  },
  {
    path: '/login',
    name: 'login',
    beforeEnter: guest,
    component: () => import('@/views/pages/login/index')
  },
  {
    path: '/create-payment-method',
    name: 'create-payment-method',
    beforeEnter: auth,
    component: () => import('@/views/pages/create-payment-method/index')
  },
  {
    path: '/create-expenses-type',
    name: 'create-expenses-type',
    beforeEnter: auth,
    component: () => import('@/views/pages/create-expenses-type/index')
  },
  {
    path: '/create-user',
    name: 'create-user',
    beforeEnter: auth,
    component: () => import('@/views/pages/create-user/index')
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
