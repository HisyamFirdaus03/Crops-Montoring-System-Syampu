<template>
  <div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold">Temperature</h2>
        <p class="text-2xl">{{ latestData.temperature || 'N/A' }} °C</p>
      </div>
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold">Soil Moisture</h2>
        <p class="text-2xl">{{ latestData.soil_moisture || 'N/A' }} %</p>
      </div>
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold">Humidity</h2>
        <p class="text-2xl">{{ latestData.humidity || 'N/A' }} %</p>
      </div>
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold">Light Intensity</h2>
        <p class="text-2xl">{{ latestData.light_intensity || 'N/A' }} lux</p>
      </div>
      <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold">CO₂ Level</h2>
        <p class="text-2xl">{{ latestData.co2_level || 'N/A' }} ppm</p>
      </div>
    </div>
    <div class="bg-white p-4 rounded shadow">
      <h2 class="text-xl font-semibold">Active Alerts</h2>
      <ul>
        <li v-for="alert in alerts" :key="alert.id" class="text-red-600">
          {{ alert.message }} ({{ alert.actual_value }} vs. {{ alert.threshold_value }} at {{ alert.timestamp }})
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      latestData: {},
      alerts: []
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