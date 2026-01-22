CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('note', 'call', 'email', 'task', 'reminder') NOT NULL,
    subject VARCHAR(255),
    content TEXT,
    due_date TIMESTAMP NULL,
    is_completed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
) ENGINE=InnoDB;
