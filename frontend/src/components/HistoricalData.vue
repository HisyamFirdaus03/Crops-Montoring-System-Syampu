<template>
  <div class="container mx-auto p-4">
    <div class="bg-white p-4 rounded shadow mb-6">
      <h1 class="text-2xl font-bold mb-4">Historical Sensor Data</h1>
      <button 
        @click="fetchHistoricalData" 
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4"
      >
        Refresh Historical Data
      </button>
      <div v-if="historicalData.length">
        <ul class="space-y-2">
          <li 
            v-for="data in historicalData" 
            :key="data.id" 
            class="p-2 bg-gray-50 rounded"
          >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
              <div>Temperature: {{ data.temperature || 'N/A' }} °C</div>
              <div>Soil Moisture: {{ data.soil_moisture || 'N/A' }} %</div>
              <div>Humidity: {{ data.humidity || 'N/A' }} %</div>
              <div>Light Intensity: {{ data.light_intensity || 'N/A' }} lux</div>
              <div>CO₂ Level: {{ data.co2_level || 'N/A' }} ppm</div>
              <div>Timestamp: {{ data.timestamp || 'N/A' }}</div>
            </div>
          </li>
        </ul>
      </div>
      <div v-else class="text-gray-500">
        No historical data available. Click "Refresh Historical Data" to load.
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      historicalData: []
    };
  },
  methods: {
    async fetchHistoricalData() {
      try {
        const response = await fetch('http://localhost:3000/api/sensor-data/historical', {
          headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
        });
        if (response.ok) {
          this.historicalData = await response.json();
        }
      } catch (error) {
        console.error('Error fetching historical data:', error);
      }
    }
  },
  mounted() {
    this.fetchHistoricalData();
  }
};
</script>