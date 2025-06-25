<template>
  <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 p-6">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section -->
      <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-purple-400 to-pink-500 rounded-2xl mb-6 shadow-lg">
          <span class="text-4xl text-white">📈</span>
        </div>
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Historical Sensor Data</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
          Analyze trends and patterns in your agricultural data over time. Track environmental changes and optimize your farming operations.
        </p>
      </div>

      <!-- Controls Section -->
      <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center">
            <div class="p-3 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl text-white mr-4">
              🔄
            </div>
            <div>
              <h2 class="text-xl font-semibold text-gray-800">Data Management</h2>
              <p class="text-gray-600 text-sm">Refresh and filter your historical data</p>
            </div>
          </div>
          
          <div class="flex items-center space-x-4">
            <div class="text-right">
              <div class="text-2xl font-bold text-gray-800">{{ historicalData.length }}</div>
              <div class="text-sm text-gray-500">Total Records</div>
            </div>
            <button 
              @click="fetchHistoricalData" 
              :disabled="isLoading"
              class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
            >
              <div v-if="isLoading" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
              <svg v-else class="w-5 h-5 mr-2 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
              {{ isLoading ? 'Loading...' : 'Refresh Data' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Data Display Section -->
      <div v-if="historicalData.length > 0" class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
          <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl text-white">
                📊
              </div>
              <div class="text-right">
                <div class="text-2xl font-bold text-gray-800">{{ getDateRange().days }}</div>
                <div class="text-sm text-gray-500">Days of Data</div>
              </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-700">Data Coverage</h3>
            <p class="text-sm text-gray-600 mt-1">{{ getDateRange().range }}</p>
          </div>

          <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-xl text-white">
                🌡️
              </div>
              <div class="text-right">
                <div class="text-2xl font-bold text-gray-800">{{ getAverageTemperature() }}°C</div>
                <div class="text-sm text-gray-500">Average</div>
              </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-700">Temperature</h3>
            <p class="text-sm text-gray-600 mt-1">Range: {{ getTemperatureRange() }}</p>
          </div>

          <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-xl text-white">
                💧
              </div>
              <div class="text-right">
                <div class="text-2xl font-bold text-gray-800">{{ getAverageMoisture() }}%</div>
                <div class="text-sm text-gray-500">Average</div>
              </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-700">Soil Moisture</h3>
            <p class="text-sm text-gray-600 mt-1">Range: {{ getMoistureRange() }}</p>
          </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="p-2 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg text-white mr-3">
                  📋
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Historical Records</h2>
              </div>
              <div class="text-sm text-gray-600">
                Showing {{ Math.min(displayLimit, historicalData.length) }} of {{ historicalData.length }} records
              </div>
            </div>
          </div>
          
          <div class="max-h-96 overflow-y-auto">
            <div class="space-y-1 p-6">
              <div 
                v-for="(data, index) in paginatedData" 
                :key="data.id" 
                class="group bg-gradient-to-r from-gray-50 to-white hover:from-blue-50 hover:to-indigo-50 rounded-xl p-6 border border-gray-100 hover:border-blue-200 transition-all duration-300 hover:shadow-md"
                :class="{ 'animate-pulse': isLoading }"
              >
                <div class="flex items-center justify-between mb-4">
                  <div class="flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center text-white text-sm font-bold mr-4">
                      {{ index + 1 }}
                    </div>
                    <div class="text-lg font-semibold text-gray-800">
                      Record #{{ data.id }}
                    </div>
                  </div>
                  <div class="text-sm text-gray-500">
                    {{ formatTimestamp(data.timestamp) }}
                  </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                  <div class="flex items-center bg-white rounded-lg p-3 shadow-sm group-hover:shadow-md transition-shadow duration-200">
                    <span class="text-2xl mr-3">🌡️</span>
                    <div>
                      <div class="text-sm text-gray-600">Temperature</div>
                      <div class="font-semibold text-gray-800">
                        {{ data.temperature !== null ? data.temperature + ' °C' : 'N/A' }}
                      </div>
                    </div>
                  </div>
                  
                  <div class="flex items-center bg-white rounded-lg p-3 shadow-sm group-hover:shadow-md transition-shadow duration-200">
                    <span class="text-2xl mr-3">💧</span>
                    <div>
                      <div class="text-sm text-gray-600">Soil Moisture</div>
                      <div class="font-semibold text-gray-800">
                        {{ data.soil_moisture !== null ? data.soil_moisture + ' %' : 'N/A' }}
                      </div>
                    </div>
                  </div>
                  
                  <div class="flex items-center bg-white rounded-lg p-3 shadow-sm group-hover:shadow-md transition-shadow duration-200">
                    <span class="text-2xl mr-3">🌊</span>
                    <div>
                      <div class="text-sm text-gray-600">Humidity</div>
                      <div class="font-semibold text-gray-800">
                        {{ data.humidity !== null ? data.humidity + ' %' : 'N/A' }}
                      </div>
                    </div>
                  </div>
                  
                  <div class="flex items-center bg-white rounded-lg p-3 shadow-sm group-hover:shadow-md transition-shadow duration-200">
                    <span class="text-2xl mr-3">☀️</span>
                    <div>
                      <div class="text-sm text-gray-600">Light Intensity</div>
                      <div class="font-semibold text-gray-800">
                        {{ data.light_intensity !== null ? formatNumber(data.light_intensity) + ' lux' : 'N/A' }}
                      </div>
                    </div>
                  </div>
                  
                  <div class="flex items-center bg-white rounded-lg p-3 shadow-sm group-hover:shadow-md transition-shadow duration-200">
                    <span class="text-2xl mr-3">🫧</span>
                    <div>
                      <div class="text-sm text-gray-600">CO₂ Level</div>
                      <div class="font-semibold text-gray-800">
                        {{ data.co2_level !== null ? formatNumber(data.co2_level) + ' ppm' : 'N/A' }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Load More Button -->
          <div v-if="historicalData.length > displayLimit" class="bg-gray-50 px-8 py-4 border-t border-gray-200">
            <button 
              @click="loadMore"
              class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold py-3 px-6 rounded-xl hover:from-emerald-600 hover:to-teal-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl"
            >
              Load More Records ({{ historicalData.length - displayLimit }} remaining)
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="!isLoading" class="bg-white rounded-2xl shadow-lg p-12 text-center">
        <div class="text-6xl mb-6">📊</div>
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">No Historical Data Available</h3>
        <p class="text-gray-600 mb-8 max-w-md mx-auto">
          It looks like there's no historical sensor data to display. Try refreshing the data or check back later when more data has been collected.
        </p>
        <button 
          @click="fetchHistoricalData"
          class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold py-3 px-8 rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 transform hover:-translate-y-1 shadow-lg hover:shadow-xl"
        >
          Try Refreshing Data
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading && historicalData.length === 0" class="bg-white rounded-2xl shadow-lg p-12 text-center">
        <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-500 mx-auto mb-6"></div>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Loading Historical Data</h3>
        <p class="text-gray-600">Please wait while we fetch your sensor data...</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      historicalData: [],
      isLoading: false,
      displayLimit: 10
    };
  },
  computed: {
    paginatedData() {
      return this.historicalData.slice(0, this.displayLimit);
    }
  },
  methods: {
    async fetchHistoricalData() {
      this.isLoading = true;
      try {
        const response = await fetch('http://localhost:3000/api/sensor-data/historical', {
          headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
        });
        if (response.ok) {
          this.historicalData = await response.json();
          // Sort by timestamp descending (newest first)
          this.historicalData.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
        } else {
          console.error('Failed to fetch historical data');
        }
      } catch (error) {
        console.error('Error fetching historical data:', error);
      } finally {
        this.isLoading = false;
      }
    },
    loadMore() {
      this.displayLimit += 10;
    },
    formatTimestamp(timestamp) {
      if (!timestamp) return 'Unknown';
      return new Date(timestamp).toLocaleString();
    },
    formatNumber(value) {
      if (!value && value !== 0) return null;
      return new Intl.NumberFormat().format(value);
    },
    getDateRange() {
      if (this.historicalData.length === 0) return { days: 0, range: 'No data' };
      
      const timestamps = this.historicalData.map(d => new Date(d.timestamp)).filter(d => !isNaN(d));
      if (timestamps.length === 0) return { days: 0, range: 'No valid dates' };
      
      const oldest = new Date(Math.min(...timestamps));
      const newest = new Date(Math.max(...timestamps));
      const days = Math.ceil((newest - oldest) / (1000 * 60 * 60 * 24)) + 1;
      
      return {
        days,
        range: `${oldest.toLocaleDateString()} - ${newest.toLocaleDateString()}`
      };
    },
    getAverageTemperature() {
      const temps = this.historicalData.filter(d => d.temperature !== null).map(d => d.temperature);
      if (temps.length === 0) return 'N/A';
      const avg = temps.reduce((sum, temp) => sum + temp, 0) / temps.length;
      return avg.toFixed(1);
    },
    getTemperatureRange() {
      const temps = this.historicalData.filter(d => d.temperature !== null).map(d => d.temperature);
      if (temps.length === 0) return 'N/A';
      const min = Math.min(...temps);
      const max = Math.max(...temps);
      return `${min.toFixed(1)}°C - ${max.toFixed(1)}°C`;
    },
    getAverageMoisture() {
      const moisture = this.historicalData.filter(d => d.soil_moisture !== null).map(d => d.soil_moisture);
      if (moisture.length === 0) return 'N/A';
      const avg = moisture.reduce((sum, m) => sum + m, 0) / moisture.length;
      return avg.toFixed(1);
    },
    getMoistureRange() {
      const moisture = this.historicalData.filter(d => d.soil_moisture !== null).map(d => d.soil_moisture);
      if (moisture.length === 0) return 'N/A';
      const min = Math.min(...moisture);
      const max = Math.max(...moisture);
      return `${min.toFixed(1)}% - ${max.toFixed(1)}%`;
    }
  },
  mounted() {
    this.fetchHistoricalData();
  }
};
</script>

<style scoped>
/* Smooth scrollbar styling */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Custom animation for data loading */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.group {
  animation: fadeInUp 0.3s ease-out;
}
</style>