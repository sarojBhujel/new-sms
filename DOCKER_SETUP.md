# Docker Setup for School Management System

This guide helps you set up and run the Laravel School Management System using Docker and Docker Compose.

## Prerequisites

- Docker installed on your system ([Download Docker](https://www.docker.com/products/docker-desktop))
- Docker Compose installed (included with Docker Desktop)
- Basic knowledge of Docker and terminal commands

## Quick Start

### 1. Clone or Navigate to Project Directory

```bash
cd /path/to/school-management-system
```

### 2. Configure Environment Variables

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key (if not already set)
# This will be done automatically when starting containers
```

**Important:** Update your `.env` file with Docker database credentials:

```env
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:your-key-here

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=school_management
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password
```

### 3. Build and Start Containers

```bash
# Build images and start all services
docker-compose up -d

# Watch logs (optional)
docker-compose logs -f app
```

### 4. Install Dependencies and Set Up Database

```bash
# Access the Laravel container
docker-compose exec app bash

# Inside the container, run:
composer install
php artisan key:generate
php artisan migrate
php artisan seed  # if you have seeders
php artisan storage:link

# Exit the container
exit
```

### 5. Access Your Application

- **Laravel App:** http://localhost
- **PHPMyAdmin:** http://localhost:8080
  - Username: `laravel_user`
  - Password: `laravel_password`
  - Server: `db`

## Available Services

### App Service
- **Container:** `laravel_app`
- **Port:** 80 (HTTP), 443 (HTTPS)
- **Base Image:** `php:8.1-apache`
- **Mounts:** Entire project directory for live development

### Database Service
- **Container:** `laravel_db`
- **Image:** `mysql:8.0`
- **Port:** 3306
- **Default Credentials:**
  - Database: `school_management`
  - User: `laravel_user`
  - Password: `laravel_password`
  - Root Password: `root_password`

### Redis Service
- **Container:** `laravel_redis`
- **Image:** `redis:7-alpine`
- **Port:** 6379
- **Use:** Cache, sessions, queue

### PHPMyAdmin Service
- **Container:** `laravel_phpmyadmin`
- **Port:** 8080
- **Access:** Web-based MySQL management

## Common Commands

### Container Management

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f [service_name]

# Restart services
docker-compose restart [service_name]

# Remove containers and volumes
docker-compose down -v
```

### Laravel Artisan Commands

```bash
# Execute artisan commands in the container
docker-compose exec app php artisan [command]

# Examples:
docker-compose exec app php artisan tinker
docker-compose exec app php artisan queue:work
docker-compose exec app php artisan schedule:work
docker-compose exec app php artisan migrate:fresh --seed
```

### Database Management

```bash
# Access MySQL container
docker-compose exec db mysql -u laravel_user -p school_management

# Create database backup
docker-compose exec db mysqldump -u laravel_user -p school_management > backup.sql

# Restore from backup
docker-compose exec -T db mysql -u laravel_user -p school_management < backup.sql
```

### Interactive Shell

```bash
# Access app container bash
docker-compose exec app bash

# Run commands inside container
composer install
npm install
npm run dev
```

## Development Workflow

### Making Code Changes

The project directory is mounted as a volume, so changes to your code are reflected immediately:

```bash
# Edit files in your IDE/editor
# Changes appear in container automatically
# No restart needed for PHP changes
```

### Running Tests

```bash
# Run PHPUnit tests
docker-compose exec app php artisan test

# Run specific test file
docker-compose exec app php artisan test tests/Feature/YourTest.php

# Run with coverage
docker-compose exec app php artisan test --coverage
```

### Database Migrations

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Roll back migrations
docker-compose exec app php artisan migrate:rollback

# Fresh migration with seeding
docker-compose exec app php artisan migrate:fresh --seed

# Create new migration
docker-compose exec app php artisan make:migration create_table_name
```

## Troubleshooting

### Issue: Permission Denied Errors

**Solution:** Ensure proper permissions in the container:

```bash
docker-compose exec app chown -R www-data:www-data /var/www/html
docker-compose exec app chmod -R 755 storage bootstrap/cache
```

### Issue: Database Connection Failed

**Solution:** Ensure database service is running and healthy:

```bash
docker-compose ps

# Check database logs
docker-compose logs db

# Verify connectivity
docker-compose exec app php artisan tinker
# Run in tinker: DB::connection()->getPdo()
```

### Issue: Port Already in Use

**Solution:** Change port mappings in docker-compose.yml:

```yaml
services:
  app:
    ports:
      - "8080:80"  # Changed from 80:80
      - "8443:443" # Changed from 443:443
```

### Issue: Laravel Logs Not Showing

**Solution:** Ensure storage directory has proper permissions:

```bash
docker-compose exec app chmod -R 777 storage
```

## Production Considerations

For production deployment:

1. **Update Environment Variables:**
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Use strong `APP_KEY`

2. **Use Environment Files:**
   - Don't commit `.env` file to version control
   - Use Docker secrets or environment variable files

3. **Database Backups:**
   - Regular automated backups
   - Test restore procedures

4. **SSL/TLS Certificates:**
   - Replace snake oil certificates with real ones
   - Configure Apache for HTTPS

5. **Update Compose File:**
   - Remove PHPMyAdmin from production
   - Use stronger database passwords
   - Configure proper resource limits

## Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel in Docker - Best Practices](https://laravel.com/docs/deployment)

## Cleanup

To completely remove all containers, volumes, and networks:

```bash
docker-compose down -v --remove-orphans
```

## Support

For issues or questions, refer to:
- Docker Compose logs: `docker-compose logs [service]`
- Laravel logs: `storage/logs/laravel.log`
- Database logs: `docker-compose logs db`
