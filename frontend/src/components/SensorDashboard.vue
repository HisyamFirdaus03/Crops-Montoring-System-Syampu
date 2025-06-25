<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 p-6">
    <!-- Header -->
    <div class="mb-8 text-center">
      <h1 class="text-4xl font-bold text-gray-800 mb-2">🌱 Smart Agriculture Dashboard</h1>
      <p class="text-gray-600">Real-time monitoring and control system</p>
    </div>

    <!-- Sensor Data Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
      <!-- Temperature Card -->
      <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-400 to-red-500 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
        <div class="relative p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-orange-400 to-red-500 rounded-xl text-white">
              🌡️
            </div>
            <div class="text-right">
              <div class="text-3xl font-bold text-gray-800">{{ latestData.temperature || '--' }}</div>
              <div class="text-sm text-gray-500">°C</div>
            </div>
          </div>
          <h3 class="text-lg font-semibold text-gray-700">Temperature</h3>
          <div class="mt-2 h-1 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-orange-400 to-red-500 rounded-full transform transition-all duration-500" 
                 :style="`width: ${Math.min((latestData.temperature || 0) / 40 * 100, 100)}%`"></div>
          </div>
        </div>
      </div>

      <!-- Soil Moisture Card -->
      <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-indigo-500 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
        <div class="relative p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl text-white">
              💧
            </div>
            <div class="text-right">
              <div class="text-3xl font-bold text-gray-800">{{ latestData.soil_moisture || '--' }}</div>
              <div class="text-sm text-gray-500">%</div>
            </div>
          </div>
          <h3 class="text-lg font-semibold text-gray-700">Soil Moisture</h3>
          <div class="mt-2 h-1 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full transform transition-all duration-500" 
                 :style="`width: ${latestData.soil_moisture || 0}%`"></div>
          </div>
        </div>
      </div>

      <!-- Humidity Card -->
      <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-teal-400 to-cyan-500 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
        <div class="relative p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-xl text-white">
              🌊
            </div>
            <div class="text-right">
              <div class="text-3xl font-bold text-gray-800">{{ latestData.humidity || '--' }}</div>
              <div class="text-sm text-gray-500">%</div>
            </div>
          </div>
          <h3 class="text-lg font-semibold text-gray-700">Humidity</h3>
          <div class="mt-2 h-1 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-teal-400 to-cyan-500 rounded-full transform transition-all duration-500" 
                 :style="`width: ${latestData.humidity || 0}%`"></div>
          </div>
        </div>
      </div>

      <!-- Light Intensity Card -->
      <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-yellow-400 to-orange-500 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
        <div class="relative p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl text-white">
              ☀️
            </div>
            <div class="text-right">
              <div class="text-3xl font-bold text-gray-800">{{ formatNumber(latestData.light_intensity) || '--' }}</div>
              <div class="text-sm text-gray-500">lux</div>
            </div>
          </div>
          <h3 class="text-lg font-semibold text-gray-700">Light Intensity</h3>
          <div class="mt-2 h-1 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full transform transition-all duration-500" 
                 :style="`width: ${Math.min((latestData.light_intensity || 0) / 100000 * 100, 100)}%`"></div>
          </div>
        </div>
      </div>

      <!-- CO₂ Level Card -->
      <div class="group relative overflow-hidden bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-400 to-pink-500 opacity-10 group-hover:opacity-20 transition-opacity duration-300"></div>
        <div class="relative p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-gradient-to-br from-purple-400 to-pink-500 rounded-xl text-white">
              🫧
            </div>
            <div class="text-right">
              <div class="text-3xl font-bold text-gray-800">{{ formatNumber(latestData.co2_level) || '--' }}</div>
              <div class="text-sm text-gray-500">ppm</div>
            </div>
          </div>
          <h3 class="text-lg font-semibold text-gray-700">CO₂ Level</h3>
          <div class="mt-2 h-1 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-purple-400 to-pink-500 rounded-full transform transition-all duration-500" 
                 :style="`width: ${Math.min((latestData.co2_level || 0) / 2000 * 100, 100)}%`"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Control Panel -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
      <div class="flex items-center mb-6">
        <div class="p-3 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl text-white mr-4">
          🎛️
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Actuator Controls</h2>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Irrigation Control -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
          <div class="flex items-center mb-4">
            <div class="text-2xl mr-3">💧</div>
            <h3 class="text-xl font-semibold text-gray-800">Irrigation System</h3>
          </div>
          <div class="flex space-x-4">
            <button
              @click="sendControlCommand('irrigation', 'on')"
              :disabled="isLoading"
              class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span class="flex items-center justify-center">
                <span class="mr-2">🟢</span>
                Turn On
              </span>
            </button>
            <button
              @click="sendControlCommand('irrigation', 'off')"
              :disabled="isLoading"
              class="flex-1 bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span class="flex items-center justify-center">
                <span class="mr-2">🔴</span>
                Turn Off
              </span>
            </button>
          </div>
        </div>

        <!-- Fan Control -->
        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-xl p-6 border border-cyan-100">
          <div class="flex items-center mb-4">
            <div class="text-2xl mr-3">🌀</div>
            <h3 class="text-xl font-semibold text-gray-800">Ventilation Fan</h3>
          </div>
          <div class="flex space-x-4">
            <button
              @click="sendControlCommand('fan', 'on')"
              :disabled="isLoading"
              class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span class="flex items-center justify-center">
                <span class="mr-2">🟢</span>
                Turn On
              </span>
            </button>
            <button
              @click="sendControlCommand('fan', 'off')"
              :disabled="isLoading"
              class="flex-1 bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span class="flex items-center justify-center">
                <span class="mr-2">🔴</span>
                Turn Off
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Alerts Panel -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
      <div class="flex items-center mb-6">
        <div class="p-3 bg-gradient-to-br from-red-400 to-pink-500 rounded-xl text-white mr-4">
          🚨
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Active Alerts</h2>
        <div v-if="alerts.length > 0" class="ml-auto">
          <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">
            {{ alerts.length }} alert{{ alerts.length > 1 ? 's' : '' }}
          </span>
        </div>
      </div>
      
      <div v-if="alerts.length === 0" class="text-center py-12">
        <div class="text-6xl mb-4">✅</div>
        <p class="text-gray-500 text-lg">No active alerts - all systems normal</p>
      </div>
      
      <div v-else class="space-y-4">
        <div 
          v-for="alert in alerts" 
          :key="alert.id" 
          class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200"
        >
          <div class="flex items-start">
            <div class="text-red-500 mr-3 mt-1">⚠️</div>
            <div class="flex-1">
              <p class="text-red-800 font-medium">{{ alert.message }}</p>
              <div class="mt-2 text-sm text-red-600">
                <span class="font-medium">Current:</span> {{ alert.actual_value }} | 
                <span class="font-medium">Threshold:</span> {{ alert.threshold_value }} | 
                <span class="font-medium">Time:</span> {{ formatTimestamp(alert.timestamp) }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="isLoading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-2xl p-8 flex items-center space-x-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500"></div>
        <span class="text-gray-700 font-medium">Processing command...</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      latestData: {},
      alerts: [],
      isLoading: false
    };
  },
  methods: {
    async fetchLatestData() {
      try {
        const response = await fetch('http://localhost:3000/api/sensor-data/latest', {
          headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
        });
        if (response.ok) {
          this.latestData = await response.json();
        }
      } catch (error) {
        console.error('Error fetching latest data:', error);
      }
    },
    async fetchAlerts() {
      try {
        const response = await fetch('http://localhost:3000/api/alerts', {
          headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
        });
        if (response.ok) {
          this.alerts = await response.json();
        }
      } catch (error) {
        console.error('Error fetching alerts:', error);
      }
    },
    async sendControlCommand(actuatorType, command) {
      this.isLoading = true;
      try {
        const response = await fetch('http://localhost:3000/api/control-command', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
          body: JSON.stringify({
            actuator_type: actuatorType,
            command: command
          })
        });
        if (response.ok) {
          console.log(`Command sent: ${actuatorType} ${command}`);
          // Show success feedback
          this.showNotification(`${actuatorType} turned ${command} successfully!`, 'success');
        } else {
          console.error('Error sending command:', await response.text());
          this.showNotification('Failed to send command. Please try again.', 'error');
        }
      } catch (error) {
        console.error('Error sending command:', error);
        this.showNotification('Network error. Please check your connection.', 'error');
      } finally {
        this.isLoading = false;
      }
    },
    formatNumber(value) {
      if (!value) return null;
      return new Intl.NumberFormat().format(value);
    },
    formatTimestamp(timestamp) {
      if (!timestamp) return 'Unknown';
      return new Date(timestamp).toLocaleString();
    },
    showNotification(message, type) {
      // You can implement a toast notification system here
      // For now, we'll just use console.log
      console.log(`${type.toUpperCase()}: ${message}`);
    }
  },
  mounted() {
    this.fetchLatestData();
    this.fetchAlerts();
    setInterval(() => {
      this.fetchLatestData();
      this.fetchAlerts();
    }, 5000); // Refresh every 5 seconds
  }
};
</script>

<style scoped>
/* Additional custom animations */
@keyframes pulse-glow {
  0%, 100% {
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.3);
  }
  50% {
    box-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
  }
}

.pulse-glow {
  animation: pulse-glow 2s infinite;
}
</style>