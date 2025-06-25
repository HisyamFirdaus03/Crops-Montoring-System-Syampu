CREATE DATABASE crops_monitoring;
USE crops_monitoring;

CREATE TABLE sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    temperature FLOAT NOT NULL,
    soil_moisture FLOAT NOT NULL,
    humidity FLOAT NOT NULL,
    light_intensity FLOAT NOT NULL,
    co2_level FLOAT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL, -- e.g., 'temperature_spike'
    message VARCHAR(255) NOT NULL,
    threshold_value FLOAT NOT NULL,
    actual_value FLOAT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE actuator_commands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    actuator_type VARCHAR(50) NOT NULL, -- e.g., 'irrigation', 'fan'
    command VARCHAR(50) NOT NULL, -- e.g., 'on', 'off'
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Hashed password
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);