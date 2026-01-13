#!/bin/bash

# Setup Script for SaaS Platform Base
# Run this on your production server (Ubuntu/Debian recommended)

echo "Setting up SaaS Platform..."

# 1. Environment
if [ ! -f .env ]; then
    cp .env.example .env
    echo "Created .env from .env.example. Please edit it with your DB credentials."
    exit 1
fi

# 2. Permissions
echo "Setting permissions..."
chmod -R 775 storage
chmod +x scripts/*.sh 2>/dev/null || true
chmod +x scripts/*.php

# 3. Database Init
echo "Initializing Database..."
php scripts/init_db.php
php scripts/migrate.php
php scripts/seed.php

# 4. Cron Jobs
# Adds a daily job at midnight
CRON_JOB="0 0 * * * cd $(pwd) && php scripts/backup-full.php >> storage/logs/backup.log 2>&1"

# Check if cron job exists to avoid duplicate
(crontab -l 2>/dev/null | grep -F "scripts/backup-full.php") || (crontab -l 2>/dev/null; echo "$CRON_JOB") | crontab -

echo "Cron job added for daily backups."
echo "Setup complete! Point your web server to $(pwd)/public"
