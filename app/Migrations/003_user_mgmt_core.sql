-- Add is_active to users
ALTER TABLE users ADD COLUMN is_active TINYINT(1) DEFAULT 1;

-- Password Resets table
CREATE TABLE IF NOT EXISTS password_resets (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (email),
    INDEX (token)
);
