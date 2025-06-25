<template>
  <div class="min-h-screen bg-gray-100 p-4">
    <h1 class="text-3xl font-bold text-center mb-6">Crops Monitoring System</h1>
    <Login v-if="!isAuthenticated" @login-success="handleLogin" />
    <SensorDashboard v-else />
  </div>
</template>

<script>
import Login from './components/Login.vue';
import SensorDashboard from './components/SensorDashboard.vue';

export default {
  components: { Login, SensorDashboard },
  data() {
    return {
      isAuthenticated: !!localStorage.getItem('token')
    };
  },
  methods: {
    handleLogin() {
      this.isAuthenticated = true;
    }
  }
};
</script>