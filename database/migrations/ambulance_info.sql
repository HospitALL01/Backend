-- Select the database to use
-- Make sure to replace 'hospitall' with your actual database name if it's different.
USE hospital;

-- Create the ambulances table if it does not exist
CREATE TABLE IF NOT EXISTS ambulances (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hospital_name VARCHAR(255) NOT NULL,
    latitude DECIMAL(10, 7) NOT NULL,
    longitude DECIMAL(10, 7) NOT NULL,
    driver_name VARCHAR(255) NOT NULL,
    driver_phone VARCHAR(255) NOT NULL,
    `status` ENUM('Available', 'Busy', 'On-Trip') NOT NULL DEFAULT 'Available',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);