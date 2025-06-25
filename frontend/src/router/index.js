import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
import SensorDashboard from '../components/SensorDashboard.vue';
import HistoricalData from '../components/HistoricalData.vue';
import Dashboard from '../components/Dashboard.vue';

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true },
  },
  {
    path: '/sensordashboard',
    name: 'SensorDashboard',
    component: SensorDashboard,
    meta: { requiresAuth: true },
  },
  {
    path: '/historicaldata',
    name: 'HistoricalData',
    component: HistoricalData,
    meta: { requiresAuth: true },
  },
  {
    path: '/',
    redirect: (to) => {
      const isAuthenticated = !!localStorage.getItem('token');
      return isAuthenticated ? '/dashboard' : '/login';
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('token');
  
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login');
  } else {
    next();
  }
});

export default router;