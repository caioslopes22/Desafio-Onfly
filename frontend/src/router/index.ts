import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/pages/Login.vue';
import Dashboard from '@/pages/Dashboard.vue';
import { useAuth } from '@/stores/auth';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login', component: Login },
    { path: '/', component: Dashboard, meta: { requiresAuth: true } },
  ]
});

router.beforeEach((to) => {
  const auth = useAuth();
  if (to.meta.requiresAuth && !auth.token) return '/login';
  if (to.path === '/login' && auth.token) return '/';
});

export default router;
