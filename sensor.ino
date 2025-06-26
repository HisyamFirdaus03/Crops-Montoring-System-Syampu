#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <ArduinoJson.h>

// Wi-Fi credentials
const char* ssid = "Your WiFi SSID"; // Your Wi-Fi SSID
const char* password = "Your WiFi Password"; // Your Wi-Fi password

// Backend API endpoints
const char* sensorUrl = "http://[Your Laptop IP]:[Host Port]/api/sensor-data"; //Replace Your Laptop IP with your laptop IP (check ipconfig in cmd)
const char* actuatorUrl = "http://[Your Laptop IP]:[Host Port]/api/actuator-status"; //Replace also Host Port with your host port (Port: 3000 (default))

// JWT token from /api/login
const char* jwtToken = "Your JWT Token"; // Replace with fresh token from Postman or Browser

// Simulate sensor readings (replace with actual sensor code if available)
float readTemperature() {
  return random(25, 35) + random(0, 100) / 100.0; // Simulate 25.00–34.99°C
}

float readSoilMoisture() {
  return random(50, 70) + random(0, 100) / 100.0; // Simulate 50.00–69.99%
}

float readHumidity() {
  return random(60, 80) + random(0, 100) / 100.0; // Simulate 60.00–79.99%
}

float readLightIntensity() {
  return random(400, 600) + random(0, 100) / 100.0; // Simulate 400.00–599.99 lux
}

float readCO2Level() {
  return random(400, 450) + random(0, 100) / 100.0; // Simulate 400.00–449.99 ppm
}

void setup() {
  Serial.begin(115200);
  delay(1000);
  
  // Connect to Wi-Fi
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
    yield(); // Allow system tasks
  }
  
  Serial.println("\nWiFi connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  static unsigned long lastSensorTime = 0;
  static unsigned long lastActuatorTime = 0;
  unsigned long currentTime = millis();

  // Ensure Wi-Fi is connected
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi disconnected, attempting to reconnect...");
    WiFi.reconnect();
    int retry = 0;
    while (WiFi.status() != WL_CONNECTED && retry < 20) {
      delay(500);
      Serial.print(".");
      yield();
      retry++;
    }
    if (WiFi.status() == WL_CONNECTED) {
      Serial.println("\nWiFi reconnected");
      Serial.print("IP address: ");
      Serial.println(WiFi.localIP());
    } else {
      Serial.println("\nWiFi reconnection failed");
      delay(5000);
      return;
    }
  }

  // 1. Send sensor data (every 10 seconds)
  if (currentTime - lastSensorTime >= 10000) {
    WiFiClient client;
    HTTPClient http;

    StaticJsonDocument<128> sensorDoc; // Reduced size
    sensorDoc["temperature"] = readTemperature();
    sensorDoc["soil_moisture"] = readSoilMoisture();
    sensorDoc["humidity"] = readHumidity();
    sensorDoc["light_intensity"] = readLightIntensity();
    sensorDoc["co2_level"] = readCO2Level();
    
    String sensorPayload;
    serializeJson(sensorDoc, sensorPayload);
    Serial.println("Sending sensor payload: " + sensorPayload);
    
    if (http.begin(client, sensorUrl)) {
      http.addHeader("Content-Type", "application/json");
      http.addHeader("Authorization", String("Bearer ") + jwtToken);
      
      int sensorHttpCode = http.POST(sensorPayload);
      if (sensorHttpCode > 0) {
        String sensorResponse = http.getString();
        Serial.print("Sensor HTTP code: ");
        Serial.println(sensorHttpCode);
        Serial.print("Sensor Response: ");
        Serial.println(sensorResponse);
      } else {
        Serial.print("Sensor HTTP error: ");
        Serial.println(http.errorToString(sensorHttpCode));
      }
      http.end();
    } else {
      Serial.println("Sensor HTTP begin failed");
    }
    
    lastSensorTime = currentTime;
    yield(); // Allow system tasks
  }

  // 2. Fetch actuator status (every 10 seconds, offset by 5 seconds)
  if (currentTime - lastActuatorTime >= 10000) {
    WiFiClient client;
    HTTPClient http;

    if (http.begin(client, actuatorUrl)) {
      http.addHeader("Authorization", String("Bearer ") + jwtToken);
      
      int actuatorHttpCode = http.GET();
      if (actuatorHttpCode > 0) {
        String actuatorResponse = http.getString();
        Serial.print("Actuator HTTP code: ");
        Serial.println(actuatorHttpCode);
        Serial.print("Actuator Response: ");
        Serial.println(actuatorResponse);
        
        StaticJsonDocument<128> actuatorDoc; // Reduced size
        DeserializationError error = deserializeJson(actuatorDoc, actuatorResponse);
        if (!error) {
          const char* fanStatus = actuatorDoc["fan"];
          const char* irrigationStatus = actuatorDoc["irrigation"];
          Serial.print("Fan: ");
          Serial.println(fanStatus);
          Serial.print("Irrigation: ");
          Serial.println(irrigationStatus);
        } else {
          Serial.println("Failed to parse actuator response");
        }
      } else {
        Serial.print("Actuator HTTP error: ");
        Serial.println(http.errorToString(actuatorHttpCode));
      }
      http.end();
    } else {
      Serial.println("Actuator HTTP begin failed");
    }
    
    lastActuatorTime = currentTime;
    yield(); // Allow system tasks
  }

  delay(100); // Small delay to prevent tight loop
  yield(); // Allow system tasks
}