CREATE DATABASE Portfolio_Database;

USE Portfolio_Database;

CREATE TABLE contact_submissions (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
