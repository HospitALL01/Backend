-- Create database if not exists
CREATE DATABASE IF NOT EXISTS hospital DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Select the database
USE hospital;

-- Drop the reviews table if it exists
DROP TABLE IF EXISTS reviews;

-- Create reviews table
CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,  -- Review ID
    doctor_id BIGINT UNSIGNED NOT NULL,  -- Foreign key to doctors table
    patient_id BIGINT UNSIGNED NOT NULL,  -- Foreign key to patients table
    rating INT NOT NULL,  -- Rating given by the patient (1 to 5)
    comment TEXT NOT NULL,  -- Review comment by the patient
    patient_name VARCHAR(255) NOT NULL,  -- Name of the patient who gave the review
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,  -- Timestamp when the review was created
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  -- Timestamp for when the review was last updated
    -- Foreign key constraints
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,  -- Cascade delete reviews if doctor is deleted
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,  -- Cascade delete reviews if patient is deleted
    -- Optionally, add index for better performance on foreign key columns
    INDEX idx_doctor_id (doctor_id),  -- Index for doctor_id
    INDEX idx_patient_id (patient_id)  -- Index for patient_id
);
