<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 p-6">
    <div class="max-w-6xl mx-auto">
      <!-- Header Section -->
      <div class="text-center mb-12">
        <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl mb-6 shadow-lg">
          <span class="text-4xl text-white">🌱</span>
        </div>
        <h1 class="text-4xl font-bold text-gray-800 mb-3">Crops Monitoring System</h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
          Welcome to your comprehensive agricultural monitoring dashboard. Track, analyze, and control your farming operations with precision.
        </p>
      </div>

      <!-- Quick Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-center">
            <div class="p-3 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl text-white text-2xl mr-4">
              📊
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-800">Real-time Monitoring</h3>
              <p class="text-gray-600 text-sm">Live sensor data updates</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-center">
            <div class="p-3 bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl text-white text-2xl mr-4">
              📈
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-800">Historical Analysis</h3>
              <p class="text-gray-600 text-sm">Trend analysis & insights</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-center">
            <div class="p-3 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl text-white text-2xl mr-4">
              🎛️
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-800">Smart Controls</h3>
              <p class="text-gray-600 text-sm">Automated system management</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Navigation Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <!-- Sensor Dashboard Card -->
        <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
          <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-indigo-500 opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
          <div class="relative p-8">
            <div class="flex items-center mb-6">
              <div class="p-4 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-2xl text-white text-3xl mr-6 group-hover:scale-110 transition-transform duration-300">
                📊
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Sensor Dashboard</h3>
                <p class="text-gray-600">Monitor real-time environmental conditions</p>
              </div>
            </div>
            
            <div class="space-y-3 mb-8">
              <div class="flex items-center text-gray-700">
                <span class="text-orange-500 mr-3">🌡️</span>
                <span class="text-sm">Temperature monitoring</span>
              </div>
              <div class="flex items-center text-gray-700">
                <span class="text-blue-500 mr-3">💧</span>
                <span class="text-sm">Soil moisture tracking</span>
              </div>
              <div class="flex items-center text-gray-700">
                <span class="text-yellow-500 mr-3">☀️</span>
                <span class="text-sm">Light intensity measurement</span>
              </div>
              <div class="flex items-center text-gray-700">
                <span class="text-purple-500 mr-3">🫧</span>
                <span class="text-sm">CO₂ level monitoring</span>
              </div>
            </div>
            
            <router-link 
              to="/sensordashboard" 
              class="group/btn relative inline-flex items-center justify-center w-full px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 overflow-hidden"
            >
              <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-700 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-200"></div>
              <span class="relative flex items-center">
                View Live Data
                <svg class="w-5 h-5 ml-2 group-hover/btn:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
              </span>
            </router-link>
          </div>
        </div>

        <!-- Historical Data Card -->
        <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
          <div class="absolute inset-0 bg-gradient-to-br from-purple-400 to-pink-500 opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
          <div class="relative p-8">
            <div class="flex items-center mb-6">
              <div class="p-4 bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl text-white text-3xl mr-6 group-hover:scale-110 transition-transform duration-300">
                📈
              </div>
              <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Historical Data</h3>
                <p class="text-gray-600">Analyze trends and historical patterns</p>
              </div>
            </div>
            
            <div class="space-y-3 mb-8">
              <div class="flex items-center text-gray-700">
                <span class="text-green-500 mr-3">📊</span>
                <span class="text-sm">Data visualization & charts</span>
              </div>
              <div class="flex items-center text-gray-700">
                <span class="text-blue-500 mr-3">🔍</span>
                <span class="text-sm">Trend analysis tools</span>
              </div>
              <div class="flex items-center text-gray-700">
                <span class="text-orange-500 mr-3">📋</span>
                <span class="text-sm">Custom date range reports</span>
              </div>
              <div class="flex items-center text-gray-700">
                <span class="text-purple-500 mr-3">💾</span>
                <span class="text-sm">Data export capabilities</span>
              </div>
            </div>
            
            <router-link 
              to="/historicaldata" 
              class="group/btn relative inline-flex items-center justify-center w-full px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 overflow-hidden"
            >
              <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-700 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-200"></div>
              <span class="relative flex items-center">
                Analyze Trends
                <svg class="w-5 h-5 ml-2 group-hover/btn:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
              </span>
            </router-link>
          </div>
        </div>
      </div>

      <!-- System Status Banner -->
      <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <div class="p-3 bg-white bg-opacity-20 rounded-xl mr-4">
              <span class="text-2xl">✅</span>
            </div>
            <div>
              <h3 class="text-xl font-semibold mb-1">System Status: Online</h3>
              <p class="text-emerald-100">All monitoring systems are operational</p>
            </div>
          </div>
          <div class="text-right">
            <div class="text-2xl font-bold">{{ getCurrentTime() }}</div>
            <div class="text-emerald-200 text-sm">Last updated</div>
          </div>
        </div>
      </div>

      <!-- User Actions Panel -->
      <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center">
            <div class="p-3 bg-gradient-to-br from-gray-400 to-gray-600 rounded-xl text-white mr-4">
              👤
            </div>
            <div>
              <h3 class="text-2xl font-semibold text-gray-800">Account Settings</h3>
              <p class="text-gray-600">Manage your profile and preferences</p>
            </div>
          </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
            <div class="flex items-center mb-2">
              <span class="text-lg mr-2">🔧</span>
              <span class="font-medium text-gray-800">System Settings</span>
            </div>
            <p class="text-sm text-gray-600 mb-4">Configure alerts and thresholds</p>
            <button class="w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg font-medium hover:from-gray-600 hover:to-gray-700 transition-all duration-200 transform hover:-translate-y-0.5">
              Configure
            </button>
          </div>
          
          <div class="flex-1 bg-gradient-to-br from-red-50 to-rose-50 rounded-xl p-4 border border-red-200">
            <div class="flex items-center mb-2">
              <span class="text-lg mr-2">🚪</span>
              <span class="font-medium text-gray-800">Session Management</span>
            </div>
            <p class="text-sm text-gray-600 mb-4">Securely end your current session</p>
            <button 
              @click="logout" 
              class="w-full bg-gradient-to-r from-red-500 to-rose-600 text-white px-4 py-2 rounded-lg font-medium hover:from-red-600 hover:to-rose-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl"
            >
              <span class="flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout
              </span>
            </button>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="text-center mt-12 text-gray-500">
        <p class="text-sm">© 2025 Smart Agriculture System. Built for precision farming.</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Dashboard',
  data() {
    return {
      currentTime: new Date()
    };
  },
  methods: {
    logout() {
      // Add confirmation dialog for better UX
      if (confirm('Are you sure you want to logout?')) {
        localStorage.removeItem('token');
        this.$router.push('/login');
      }
    },
    getCurrentTime() {
      return this.currentTime.toLocaleTimeString();
    },
    updateTime() {
      this.currentTime = new Date();
    }
  },
  mounted() {
    // Update time every second
    this.timeInterval = setInterval(this.updateTime, 1000);
  },
  beforeUnmount() {
    if (this.timeInterval) {
      clearInterval(this.timeInterval);
    }
  }
};
</script>

<style scoped>
/* Additional hover effects */
.group:hover .group-hover\:scale-110 {
  transform: scale(1.1);
}

/* Custom animations for smooth interactions */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}
</style>