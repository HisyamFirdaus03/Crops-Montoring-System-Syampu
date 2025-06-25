<template>
  <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
    <p class="mb-4">Welcome to the Crops Monitoring System Dashboard!</p>
    <div class="space-y-4">
      <router-link to="/sensordashboard" class="block bg-blue-500 text-white p-2 rounded text-center">
        View Sensor Dashboard
      </router-link>
      <router-link to="/historicaldata" class="block bg-blue-500 text-white p-2 rounded text-center">
        View Historical Data
      </router-link>
      <button @click="logout" class="w-full bg-red-500 text-white p-2 rounded">
        Logout
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Dashboard',
  methods: {
    logout() {
      localStorage.removeItem('token');
      this.$router.push('/login');
    }
  }
};
</script>