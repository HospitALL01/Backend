-- Create database if not exists
CREATE DATABASE IF NOT EXISTS hospital DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Select database
USE hospital;

-- Drop ambulances table if it exists
DROP TABLE IF EXISTS ambulances;

-- Create ambulances table
CREATE TABLE ambulances (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,       -- auto-increment ID for each ambulance
    hospital_name VARCHAR(255) NOT NULL,                 -- hospitalName
    latitude DECIMAL(10, 7) NOT NULL,                    -- latitude
    longitude DECIMAL(10, 7) NOT NULL,                   -- longitude
    driver_name VARCHAR(255) NOT NULL,                   -- driverName
    driver_phone VARCHAR(20) NOT NULL,                   -- driverPhone
    status ENUM('Available', 'Busy', 'On-Trip') NOT NULL DEFAULT 'Available', -- status
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, -- Created timestamp (auto-generated)
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Updated timestamp
);

-- Insert ambulance data
INSERT INTO ambulances (hospital_name, latitude, longitude, driver_name, driver_phone, status, created_at, updated_at) VALUES
('City General Hospital', 23.7540901, 90.3931698, 'Rahim Khan', '01711223344', 'Available', NOW(), NOW()),
('Metropolitan Medical Center', 23.7605432, 90.4032145, 'Karim Sheikh', '01822334455', 'Available', NOW(), NOW()),
('Community Health Clinic', 23.7499876, 90.3855432, 'Salma Begum', '01933445566', 'Available', NOW(), NOW()),
('Central Emergency Care', 23.7551234, 90.3987654, 'Jamal Uddin', '01544556677', 'Available', NOW(), NOW()),
('My Nearby Hospital', 23.7541900, 90.3929600, 'Test Driver', '01234567890', 'Available', NOW(), NOW()),
('Green Valley Hospital', 23.7698765, 90.4105432, 'Musa Khan', '01844556677', 'Available', NOW(), NOW()),
('Sunrise Medical Center', 23.7523456, 90.3809876, 'Fatima Banu', '01798765432', 'Available', NOW(), NOW()),
('Hope Hospital', 23.7456789, 90.3902345, 'Abdullah Al Amin', '01611223344', 'Available', NOW(), NOW()),
('Eastern Medical Clinic', 23.7654321, 90.3976543, 'Nusrat Jahan', '01987654321', 'Available', NOW(), NOW()),
('Metro Health Clinic', 23.7583212, 90.4021654, 'Sajjad Hossain', '01855667788', 'Available', NOW(), NOW()),
('City Care Ambulance', 23.7409876, 90.3812345, 'Rina Begum', '01766554433', 'Available', NOW(), NOW()),
('South City Hospital', 23.7534567, 90.3965432, 'Shafiqul Islam', '01698765432', 'Available', NOW(), NOW()),
('River View Medical Center', 23.7623456, 90.4054321, 'Khadiza Sultana', '01833445566', 'Available', NOW(), NOW()),
('Life Support Clinic', 23.7491234, 90.3866543, 'Rohim Ali', '01744332211', 'Available', NOW(), NOW()),
('Heartland Hospital', 23.7665432, 90.4009876, 'Mokbul Hossain', '01891234567', 'Available', NOW(), NOW());
