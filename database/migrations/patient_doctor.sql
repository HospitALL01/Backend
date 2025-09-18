-- Create database if not exists
CREATE DATABASE IF NOT EXISTS hospital DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Select database
USE hospital;

-- Drop tables if exist
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS doctors;

-- Create patients table
CREATE TABLE patients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    p_name VARCHAR(255) NOT NULL,
    p_email VARCHAR(255) UNIQUE NOT NULL,
    p_phone VARCHAR(15) UNIQUE NOT NULL,   -- এখানে INT থেকে VARCHAR করা হয়েছে
    p_password VARCHAR(255) NOT NULL, -- hashed password will be stored here
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create doctors table
CREATE TABLE doctors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    d_name VARCHAR(255) NOT NULL,
    d_email VARCHAR(255) UNIQUE NOT NULL,
    d_phone VARCHAR(15) UNIQUE NOT NULL,   -- এখানে INT থেকে VARCHAR করা হয়েছে
    d_password VARCHAR(255) NOT NULL, -- hashed password will be stored here
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
