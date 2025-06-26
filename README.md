# Crops Monitoring System

The Crops Monitoring System is a web-based application with an ESP8266 integration for real-time monitoring of environmental conditions (temperature, soil moisture, humidity, light intensity, and CO2 levels) to support agricultural crop management. The system includes a PHP backend (Slim framework), a Vue.js frontend, a MySQL database, and an ESP8266 module for sensor data collection. It features user authentication, real-time sensor data display, alerts for critical conditions (e.g., temperature > 30°C), and actuator command logging.

**Note**: The project directory is named `Crops-Montoring-System` due to a typo ("Montoring"). You can optionally rename it to `Crops-Monitoring-System-Syampu` for clarity.

## Features
- User authentication (login/register) with JWT-based security.
- Real-time sensor data collection via ESP8266.
- Dashboard displaying latest sensor readings and active alerts.
- Alerts for high temperature (> 30°C) and other conditions.
- Actuator command logging for irrigation, fans, etc.
- RESTful API for data interaction.

## Prerequisites
- **XAMPP**: For Apache, PHP (8.2.12), and MySQL.
- **Node.js**: Version 16+ with npm for the Vue.js frontend.
- **Arduino IDE**: For programming the ESP8266 (e.g., NodeMCU).
- **ESP8266 Board**: With sensors (or simulated data).
- **Wi-Fi Network**: Laptop and ESP8266 must be on the same network.
- **Composer**: For PHP dependency management.
- **Postman**: For testing API endpoints (optional).

## Project Structure
```
Crops-Montoring-System-Syampu/
├── backend/                    # PHP Slim backend
│   ├── public/
│   │   └── index.php           # API entry point
│   ├── src/
│   │   └── Database.php        # Database connection
│   └── vendor/                 # Composer dependencies
├── frontend/                   # Vue.js frontend
│   ├── src/
│   │   ├── components/
│   │   │   ├── Login.vue       # Login/register component
│   │   │   └── SensorDashboard.vue # Sensor data and alerts display
│   └── package.json            # Node.js dependencies
├── esp8266/
│   └── sensor.ino              # ESP8266 Arduino code
├── sql/
│   ├── schema.sql              # Database schema
│   └── test_data.sql           # Test data for tables
└── README.md                   # This file
```

## Setup Instructions

### 1. Clone the Project
- Place the project in XAMPP’s `htdocs`:
  ```powershell
  cd C:\xampp\htdocs
  git clone <repository-url> Crops-Montoring-System
  ```
- **Optional**: Rename the directory to fix the typo:
  ```powershell
  ren Crops-Montoring-System-Syampu Crops-Monitoring-System
  ```

### 2. Set Up the Backend
1. **Install PHP Dependencies**:
   ```powershell
   cd backend
   composer install
   ```
2. **Configure MySQL**:
   - Start MySQL in XAMPP Control Panel.
   - Create the database and tables:
     ```powershell
     mysql -u root -p < sql\schema.sql
     ```
     - Default credentials: `username: root`, `password: ''` (empty).
   - Load test data:
     ```powershell
     mysql -u root -p < sql\test_data.sql
     ```
3. **Run the Backend**:
   - Start the PHP development server:
     ```powershell
     cd backend
     php -S 0.0.0.0:3000 -t public
     ```
   - Ensure port 3000 is open in Windows Firewall:
     - Control Panel > Windows Defender Firewall > Advanced Settings > Inbound Rules.
     - Create a new rule for TCP port 3000, allow all profiles.

### 3. Set Up the Frontend
1. **Install Node.js Dependencies**:
   ```powershell
   cd frontend
   npm install
   ```
2. **Run the Frontend**:
   ```powershell
   npm run serve
   ```
   - Open `http://localhost:5173` in a browser.
   - Log in with `test`/`test` (or register a new user).

### 4. Set Up the ESP8266
1. **Install Arduino IDE and Libraries**:
   - Add ESP8266 board support: `http://arduino.esp8266.com/stable/package_esp8266com_index.json` in Arduino IDE Preferences.
   - Install libraries: `ESP8266WiFi`, `ESP8266HTTPClient`, `ArduinoJson` (v6.x) via Library Manager.
2. **Configure `sensor.ino`**:
   - Open `esp8266/sensor.ino` in Arduino IDE.
   - Update Wi-Fi credentials and server URL:
     ```cpp
     const char* ssid = "your-wifi-ssid";
     const char* password = "your-wifi-password";
     const char* sensorUrl = "http://[Your Laptop IP]:[Host Port]/api/sensor-data"; //Replace Your Laptop IP with your laptop IP (check ipconfig in cmd)
     const char* actuatorUrl = "http://[Your Laptop IP]:[Host Port]/api/actuator-status"; //Replace also Host Port with your host port (Port: 3000 (default))
     ```
   - Get a JWT token:
     - In Postman, send `POST http://192.168.178.209:3000/api/login`:
       ```json
       {
         "username": "test",
         "password": "test"
       }
       ```
     - Copy the `token` and update:
       ```cpp
       const char* jwtToken = "your-jwt-token-here";
       ```
3. **Upload Code**:
   - Select `NodeMCU 1.0` and the correct port in Arduino IDE.
   - Upload and open Serial Monitor (115200 baud).
   - Verify:
     - `WiFi connected` and an IP address (e.g., `192.168.178.x`).
     - `HTTP code: 200` and `Response: {"message":"Data saved successfully"}`.

### 5. Verify Database
- Check sensor data:
  ```sql
  USE crops_monitoring;
  SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 5;
  ```
- Check alerts:
  ```sql
  SELECT * FROM alerts WHERE is_active = 1 ORDER BY timestamp DESC;
  ```

## Usage
1. **Access the Web App**:
   - Open `http://localhost:5173`.
   - Log in with `test`/`test` or register a new user.
   - View sensor data and alerts on the dashboard.
2. **API Endpoints**:
   - `POST /api/login`: Authenticate and get a JWT token.
   - `POST /api/register`: Register a new user.
   - `POST /api/sensor-data`: Submit sensor data (JWT required).
   - `GET /api/sensor-data/latest`: Get latest sensor data.
   - `GET /api/sensor-data/historical`: Get historical sensor data.
   - `GET /api/alerts`: Get active alerts (JWT required).
   - `POST /api/control-command`: Send actuator commands.
3. **ESP8266**:
   - Sends sensor data every 30 seconds to `/api/sensor-data`.
   - Ensure a valid JWT token (refresh hourly).

## Troubleshooting
- **ESP8266 Connection Issues**:
  - Verify laptop IP (`ipconfig`) and update `serverUrl`.
  - Ensure PHP server runs on `0.0.0.0:3000`.
  - Check firewall allows port 3000.
  - Refresh JWT token if `401 Unauthorized` occurs.
- **Backend Errors**:
  - Check `C:\xampp\apache\logs\error.log` for PHP errors.
  - Verify MySQL is running and `crops_monitoring` database exists.
- **Frontend Issues**:
  - Check browser console (F12) for errors.
  - Ensure CORS headers allow `http://localhost:5173`.
- **Database Issues**:
  - Confirm tables (`users`, `sensor_data`, `alerts`, `actuator_commands`) exist:
    ```sql
    SHOW TABLES;
    ```

## Notes
- **Directory Typo**: Rename `Crops-Montoring-System` to `Crops-Monitoring-System` for clarity:
  ```powershell
  cd C:\xampp\htdocs
  ren Crops-Montoring-System-Syampu Crops-Monitoring-System
  ```
- **Security**: Replace `your-secret-key` in `index.php` with a secure key (e.g., `php -r "echo bin2hex(random_bytes(32));"`).
- **Token Expiry**: JWT tokens expire after 1 hour. Refresh via `/api/login`.
- **Real Sensors**: Replace simulated data in `sensor.ino` with actual sensor code (e.g., DHT11 for temperature/humidity).

## License
MIT License. See `LICENSE` file (create if needed).
