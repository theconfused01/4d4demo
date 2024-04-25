CREATE DATABASE IF NOT EXISTS user_management;
USE user_management;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL
);

-- Insert sample data
INSERT INTO users (username, email) VALUES
    ('Alan', 'alan@cstj.qc.ca'),
    ('Bob', 'bob@cstj.qc.ca'),
    ('Carl', 'carl@cstj.qc.ca');