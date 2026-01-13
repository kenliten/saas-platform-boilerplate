-- Plans table
CREATE TABLE IF NOT EXISTS plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    description TEXT,
    features JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Notifications table
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL, -- Nullable for global notifications
    type VARCHAR(50) NOT NULL DEFAULT 'info',
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Add profile fields to users if they don't exist
-- We use a stored procedure-like check or just run ALTER IGNORE equivalents.
-- Since MySQL/MariaDB syntax for "ADD COLUMN IF NOT EXISTS" varies or isn't standard in older versions,
-- we'll use a simple block. For this lightweight runner, we just run ALTERs.
-- If columns exist, it might error, but the runner tracks files. This is a NEW file.

ALTER TABLE users ADD COLUMN fullname VARCHAR(100) NULL;
ALTER TABLE users ADD COLUMN phone VARCHAR(20) NULL;
ALTER TABLE users ADD COLUMN bio TEXT NULL;
ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL;
