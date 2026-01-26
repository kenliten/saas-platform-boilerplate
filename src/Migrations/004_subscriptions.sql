ALTER TABLE accounts
ADD COLUMN subscription_id VARCHAR(100) DEFAULT NULL,
ADD COLUMN subscription_status ENUM('active', 'inactive', 'cancelled', 'suspended') DEFAULT 'inactive',
ADD COLUMN next_billing_date DATE DEFAULT NULL;
