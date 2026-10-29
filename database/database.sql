-- Database Schema: employee_management
-- Tugas 2 - Sistem Informasi Manajemen Data Karyawan (SIMDAK)

CREATE DATABASE IF NOT EXISTS `employee_management`
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `employee_management`;

-- 1. Table: users (Admin Authentication)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `createdAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Table: employees (Employee Records)
CREATE TABLE IF NOT EXISTS `employees` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `position` VARCHAR(100) NOT NULL,
    `department` VARCHAR(100) NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'Active',
    `employmentType` VARCHAR(30) NOT NULL DEFAULT 'Full-Time',
    `createdAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Seed: Default Admin User
-- Username: admin
-- Password: admin123
INSERT INTO `users` (`username`, `password`) 
VALUES ('admin', '$2y$10$89RPtluhyL7Cm3IiUKIRt.i8cww/NwNSVpdoyMNE07/KkuYVul8v.')
ON DUPLICATE KEY UPDATE `username` = `username`;

-- Seed: Sample Realistic Employee Data
INSERT INTO `employees` (`name`, `email`, `position`, `department`, `status`, `employmentType`) VALUES
('Fadil Pratama', 'fadil@company.com', 'Senior Software Engineer', 'Engineering', 'Active', 'Full-Time'),
('Sarah Jenkins', 'sarah.j@company.com', 'Lead Product Designer', 'Design', 'Active', 'Full-Time'),
('Budi Santoso', 'budi.s@company.com', 'Talent Acquisition Specialist', 'Human Resources', 'Probation', 'Contract'),
('Jessica Miller', 'jessica.m@company.com', 'Senior Financial Analyst', 'Finance', 'Active', 'Full-Time'),
('Rizky Ramadhan', 'rizky.r@company.com', 'Frontend Web Intern', 'Engineering', 'Active', 'Internship'),
('Clara Wijaya', 'clara.w@company.com', 'Digital Marketing Lead', 'Marketing', 'Inactive', 'Full-Time'),
('Dewi Anggraeni', 'dewi.a@company.com', 'People Operations Manager', 'Human Resources', 'Active', 'Full-Time'),
('Michael Chen', 'michael.c@company.com', 'DevOps & Cloud Engineer', 'Engineering', 'Active', 'Full-Time');
