<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Docker Setup

This project is configured to run in Docker with its own MySQL database. Follow these steps to get started:

### Prerequisites

- Docker
- Docker Compose

### Environment Variables

Before running the application, make sure your `.env` file contains the following variables:

#### Database Configuration
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=password
```

#### Application Configuration
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:YOUR_APP_KEY
APP_DEBUG=true
APP_URL=http://localhost
API_KEY=secunda_api_key_12345
```

These environment variables are essential for the Docker setup to work correctly.

### Setup Instructions

1. Clone this repository
2. Run the setup script:
   ```
   ./docker-start.sh
   ```

   This script will:
   - Build and start the Docker containers
   - Install Composer dependencies
   - Run database migrations and seeders
   - Generate an application key if needed

3. Access the application at http://localhost:8000

### Manual Setup

If you prefer to run the commands manually:

1. Start the Docker containers:
   ```
   docker-compose up -d
   ```

2. Install Composer dependencies:
   ```
   docker-compose exec app composer install
   ```

3. Run migrations and seeders:
   ```
   docker-compose exec app php artisan migrate --seed
   ```

4. Generate application key (if needed):
   ```
   docker-compose exec app php artisan key:generate
   ```

### Stopping the Environment

To stop the Docker environment:
```
docker-compose down
```
