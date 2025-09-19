-- Create database if not exists
CREATE DATABASE IF NOT EXISTS hospital DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Select database
USE hospital;

-- Drop bookings table if it exists
DROP TABLE IF EXISTS bookings;

-- Create bookings table
CREATE TABLE bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,    -- auto-increment ID for each booking
    doctor_id BIGINT UNSIGNED NOT NULL,               -- doctor's ID (foreign key referencing doctors table)
    doctor_name VARCHAR(255) NOT NULL,               -- doctor's name
    patient_id BIGINT UNSIGNED NOT NULL,              -- patient's ID (foreign key referencing patients table)
    patient_name VARCHAR(255) NOT NULL,              -- patient's name
    appointment_date DATE NOT NULL,                  -- appointment date
    payment_method VARCHAR(255) NOT NULL,            -- payment method (e.g., bKash, Rocket)
    fees DECIMAL(10, 2) NOT NULL,                    -- fees for the appointment
    status ENUM('pending', 'confirmed', 'completed') DEFAULT 'pending',  -- booking status
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,  -- timestamp when the booking was created
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- timestamp when the booking was last updated
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,  -- foreign key referencing doctors table (doctor_id)
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE   -- foreign key referencing patients table (patient_id)
);
