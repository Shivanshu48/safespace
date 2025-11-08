CREATE DATABASE IF NOT EXISTS safespace;
USE safespace;

-- User Table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reports Table
CREATE TABLE reports (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_email VARCHAR(100),
  location VARCHAR(255),
  type VARCHAR(100),
  description TEXT,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact Messages Table
CREATE TABLE contact (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100),
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- SOS Logs Table
CREATE TABLE sos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_email VARCHAR(100),
  message VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
