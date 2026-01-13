#!/bin/bash

# Script to containerize and run the application using Docker

echo "Starting SaaS Platform in Docker..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "Error: Docker is not running or not accessible."
    exit 1
fi

# Build and Start
docker-compose -f docker/docker-compose.yml up -d --build

echo ""
echo "Containers are up!"
echo "App running at: http://localhost:8000"
echo ""
echo "To initialize the database inside Docker, run:"
echo "docker-compose -f docker/docker-compose.yml exec app php scripts/init_db.php"
echo "docker-compose -f docker/docker-compose.yml exec app php scripts/migrate.php"
echo "docker-compose -f docker/docker-compose.yml exec app php scripts/seed.php"
