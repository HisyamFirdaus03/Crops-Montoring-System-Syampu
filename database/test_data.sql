-- Insert test users
INSERT INTO users (username, password) VALUES
('test', '$2y$10$3z7T6J8z9k1m2n3b4p5q6r7s8t9u0v1w2x3y4z5A6B7C8D9E0F1G2H'), -- Password: test
('admin', '$2y$10$9a8b7c6d5e4f3g2h1i0j9k8l7m6n5o4p3q2r1s0t9u8v7w6x5y4z3'), -- Password: admin
('user1', '$2y$10$1a2b3c4d5e6f7g8h9i0j1k2l3m4n5o6p7q8r9s0t1u2v3w4x5y6z7'); -- Password: user1

-- Insert test sensor data
INSERT INTO sensor_data (temperature, soil_moisture, humidity, light_intensity, co2_level, timestamp) VALUES
(28.5, 65.0, 70.0, 500.0, 400.0, '2025-06-25 08:00:00'),
(32.1, 60.0, 68.0, 450.0, 420.0, '2025-06-25 09:00:00'), -- Triggers alert (temp > 30)
(29.8, 62.5, 72.0, 480.0, 410.0, '2025-06-25 10:00:00'),
(33.4, 58.0, 69.0, 460.0, 430.0, '2025-06-25 11:00:00'), -- Triggers alert (temp > 30)
(27.9, 66.0, 71.0, 490.0, 405.0, '2025-06-25 12:00:00');

-- Insert test alerts
INSERT INTO alerts (type, message, threshold_value, actual_value, is_active, timestamp) VALUES
('temperature_spike', 'High temperature detected', 30.0, 32.1, 1, '2025-06-25 09:00:00'),
('temperature_spike', 'High temperature detected', 30.0, 33.4, 1, '2025-06-25 11:00:00'),
('low_soil_moisture', 'Low soil moisture detected', 60.0, 58.0, 1, '2025-06-25 11:00:00'),
('high_co2', 'High CO2 level detected', 420.0, 430.0, 0, '2025-06-25 11:00:00'); -- Inactive alert

-- Insert test actuator commands
INSERT INTO actuator_commands (actuator_type, command, timestamp) VALUES
('fan', 'turn_on', '2025-06-25 09:15:00'),
('irrigation', 'turn_off', '2025-06-25 11:05:00'),
('heater', 'set_temperature_25', '2025-06-25 12:00:00');