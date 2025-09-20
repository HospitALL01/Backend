-- Create database if not exists
CREATE DATABASE IF NOT EXISTS hospital DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Select database
USE hospital;

-- Drop the patient_ambulance_bookings table if it exists
DROP TABLE IF EXISTS patient_ambulance_bookings;

-- Create the patient_ambulance_bookings table
CREATE TABLE patient_ambulance_bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,       -- Auto-increment ID for each booking
    patient_name VARCHAR(255) NOT NULL,                   -- Patient's name
    hospital_name VARCHAR(255) NOT NULL,                  -- Name of the hospital
    driver_name VARCHAR(255) NOT NULL,                    -- Driver's name
    driver_phone VARCHAR(20) NOT NULL,                    -- Driver's phone number
    ambulance_id BIGINT UNSIGNED,                         -- Foreign key referencing the ambulance
    patient_id BIGINT UNSIGNED,                           -- Foreign key referencing the patient (added field)
    status ENUM('Booked', 'Cancelled', 'Completed') NOT NULL DEFAULT 'Booked', -- Booking status
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,  -- Created timestamp (auto-generated)
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Updated timestamp
    FOREIGN KEY (ambulance_id) REFERENCES ambulances(id), -- Foreign key relationship to ambulances table
    FOREIGN KEY (patient_id) REFERENCES patients(id)     -- Foreign key relationship to patients table (added constraint)
);
