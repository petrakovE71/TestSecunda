#!/bin/bash

# Build and start the Docker containers
docker-compose up -d

# Wait for the database to be ready
echo "Waiting for database to be ready..."
sleep 10

# Install Composer dependencies
docker-compose exec app composer install

# Run migrations and seeders
docker-compose exec app php artisan migrate --seed

# Generate application key if not already set
docker-compose exec app php artisan key:generate --no-interaction

echo "Docker environment is ready!"
echo "You can access the application at http://localhost:8000"
