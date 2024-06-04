import { createWebHistory, createRouter } from 'vue-router'
import { useToast } from 'primevue/usetoast';
import store from '@/store'
//this title is also set inside the html, in case the router is not working you'll still have a title
const routes = [
  {
    path: '/',
    name: 'mainshell',
    component: () => import('@/shell/mainShell.vue'),
    children: [
      {
        path: '',
        name: 'home',
        meta: { title: 'Home' },
        component: () => import('@/views/home.vue'),
      },
    ],
  },
  {
    path: '/dashboard',
    name: 'dashboardshell',
    component: () => import('@/shell/dashboardShell.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'dashboard',
        meta: { title: 'Dashboard'},
        component: () => import('@/views/dashboard/dashboard.vue'),
      },
      {
        path: '/dashboard/profile',
        name: 'profile',
        meta: { setTitleAsUsername: true },
        component: () => import('@/views/dashboard/profile.vue'),
      },
      {
        path: '/dashboard/items',
        name: 'dbitems',
        meta: { title: 'Events'},
        component: () => import('@/views/dashboard/items.vue'),
      },
      {
        path: '/dashboard/accounts',
        name: 'accounts',
        meta: { title: 'Accounts', requiresRole: 'administrator'},
        component: () => import('@/views/dashboard/accounts.vue'),
      },
      // transaction
      {
        path: '/dashboard/transactions',
        name: 'transactions',
        meta: { title: 'Transactions'},
        component: () => import('@/views/dashboard/transaction.vue'),
      },
      // customer
      {
        path: '/dashboard/customers',
        name: 'customers',
        meta: { title: 'Customers' },
        component: () => import('@/views/dashboard/customer.vue'),
      },
    ],
  },
  // {
  //   path: '/admin',
  //   name: 'adminshell',
  //   component: () => import('@/shell/adminShell.vue'),
  //   children: [
  //     {
  //       path: '',
  //       name: 'admin',
  //       meta: { title: 'admin', requiresAuth: true, requiresRole: 'admin' },
  //       component: () => import('@/views/admin/admin.vue'),
  //     },
  //   ],
  // },
  {
    path: '/:pathMatch(.*)*',
    name: 'Not Found',
    meta: { title: '404 Not Found' },
    component: () => import('@/views/404.vue'),
  },
  {
    path: '/login',
    name: 'login',
    meta: { title: 'Sign in', noAuth: true },
    component: () => import('@/views/login.vue'),
  },
  {
    path: '/register',
    name: 'register',
    meta: { title: 'Sign up', requiresAuth: true, requiresRole: 'administrator'},
    component: () => import('@/views/register.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  if (to.path === from.path) {
    return next();
  }
  let nextCalled = false;
  if (to.meta && to.meta.title) {
    document.title = to.meta.title;
  }
  if (to.meta && to.meta.requiresAuth && !store.getters['auth/authenticated']) {
    const toast = useToast();
    toast.add({ severity: 'error', summary: 'Unauthorized', detail: 'You must be logged in to access this page', life: 3000 });
    return next({ name: 'login' });
  }
  if (to.meta && to.meta.requiresAuth && to.meta.requiresRole) {
    if (store.getters['auth/user'].user.role !== to.meta.requiresRole) {
      const toast = useToast();
      toast.add({ severity: 'error', summary: 'Unauthorized', detail: 'You do not meet the requirements to access this page', life: 3000 });
      return next({ name: from.name });
    }
  }
  if (to.meta && to.meta.setTitleAsUsername) {
    document.title = 'Profile | ' + store.getters['auth/user'].user.username.charAt(0).toUpperCase() + store.getters['auth/user'].user.username.slice(1);
  }
  if (to.meta && to.meta.noAuth && store.getters['auth/authenticated']) {
    const toast = useToast();
      toast.add({ severity: 'error', summary: 'No Auth', detail: "You are already logged in", life: 3000 });
    return next({ name: 'home' });
  }
  next();
});

export default router
