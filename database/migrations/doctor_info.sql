-- Create database if not exists
CREATE DATABASE IF NOT EXISTS hospital DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Select database
USE hospital;

-- Drop doctor_info table if it exists
DROP TABLE IF EXISTS doctor_infos;

-- Create doctor_info table
CREATE TABLE doctor_info (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,   -- auto-increment ID for each doctor
    doctor_name VARCHAR(255) NOT NULL,                -- doctorName
    gender ENUM('male', 'female', 'other') NOT NULL, -- gender
    nationality VARCHAR(255) NOT NULL,               -- nationality
    specialization VARCHAR(255) NOT NULL,            -- specialization
    license_number VARCHAR(255) NOT NULL,            -- licenseNumber
    license_issue_date DATE NOT NULL,                -- licenseIssueDate
    hospital_name VARCHAR(255) NOT NULL,             -- hospitalName
    years_of_experience INT NOT NULL,                -- yearsOfExperience
    phone VARCHAR(15) NOT NULL,                      -- phone
    email VARCHAR(255) NOT NULL UNIQUE,              -- email (unique to avoid duplicates)
    current_position VARCHAR(255),                   -- currentPosition
    previous_positions TEXT,   
     profile_picture_url VARCHAR(255) NULL,                      -- previousPositions
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,  -- Created timestamp (auto-generated)
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Updated timestamp
);
