# School Management System + Docker Deployment Documentation

## 1) Before setup

### Check server basics

```bash
whoami
```

Expected:

```text
midas
```

```bash
sudo -v
```

Expected:

* No output if sudo is available and password is accepted.

```bash
free -h
```

Expected:

* Shows available RAM.
* Minimum: 2 GB
* Better: 4 GB or more

```bash
nproc
```

Expected:

* Number of CPU cores, for example `2`, `4`, or `8`.

```bash
df -h
```

Expected:

* Enough free disk space.
* For a Laravel app, at least 10 GB free is comfortable.

```bash
sudo ss -tulpn | grep -E ':80|:443|:3306|:6379|:8080'
```

Expected:

* Either no output, or only services you intentionally want running.
* If port 80, 443, 3306, 6379, or 8080 is already in use, containers may fail to start.

### Check Docker readiness

```bash
sudo systemctl status docker
```

Expected:

```text
active (running)
```

```bash
sudo docker --version
```

Expected:

* Docker version output.

```bash
sudo docker compose version
```

Expected:

* Docker Compose v2 version output.

```bash
sudo docker ps -a
```

Expected:

* Existing containers list, or none.
* If permission is denied without sudo, use `sudo` or add the user to the `docker` group.

```bash
sudo docker volume ls
```

Expected:

* Existing volumes list, or none.

```bash
sudo docker network ls
```

Expected:

* Default networks such as `bridge`, `host`, and `none`.

### Check project location

Use any stable directory. Common choices:

* `/var/www/school-management-system`
* `/opt/school-management-system`
* `/home/midas/school-management-system`

The folder path is not special; consistency matters more than location.

Navigate to the folder:

```bash
cd /home/midas/laravel/AI-vibe\ coding/school-management-system
pwd
```

Expected:

```text
/home/midas/laravel/AI-vibe coding/school-management-system
```

---

## 2) Check eligibility before deployment

### Check project files

```bash
ls -la
```

Expected:

* `Dockerfile`
* `docker-compose.yml`
* `.env`
* `.env.example`
* `artisan`
* `composer.json`
* `docker/` directory with `apache.conf`
* `.dockerignore`

```bash
find . -maxdepth 2 \( -name "Dockerfile" -o -name "*.yml" -o -name ".env*" -o -name "*.conf" \)
```

Expected:

* The real deployment files should appear.
* If `docker-compose.yml` is missing, `docker compose` will not work.

### Validate compose file

```bash
sudo docker compose config
```

Expected:

* No YAML error.
* It prints the merged Compose configuration.
* If there is a problem, you may see something like `did not find expected key`.

```bash
sudo docker compose config --services
```

Expected:

* Service names such as:

  * `app`
  * `db`
  * `redis`
  * `phpmyadmin`

### Check `.env`

For this MySQL-based Laravel app, verify:

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
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Check with:

```bash
grep -E "APP_ENV|APP_DEBUG|DB_CONNECTION|DB_HOST|DB_PORT|DB_DATABASE|DB_USERNAME|DB_PASSWORD|REDIS_HOST" .env
```

Expected:

* The values above, or your own production values.
* `DB_HOST` should be `db` (the Compose service name), not `127.0.0.1`.
* `REDIS_HOST` should be `redis` (the Compose service name).

### Check whether the app key exists

```bash
grep APP_KEY .env
```

Expected:

* Either an existing `APP_KEY=base64:...`
* Or an empty value if the app has not been initialized yet

If it is empty, generate it later inside the app container.

---

## 3) During Docker build and startup

### Build and start the stack

```bash
sudo docker compose up -d --build
```

Expected:

* Docker builds the images.
* Containers are created and started.
* You may see lines like:

  * `Creating laravel_db`
  * `Creating laravel_redis`
  * `Creating laravel_app`
  * `Creating laravel_phpmyadmin`

If the build fails, the error usually points to one Dockerfile step, such as `composer install`, `apt-get install`, or a copied file path.

### Check running containers

```bash
sudo docker ps
```

Expected:

* All services should appear.
* Status should be `Up`.
* For MySQL, you should see `0.0.0.0:3306->3306/tcp` if you exposed the port.
* For Redis, you should see `0.0.0.0:6379->6379/tcp`.
* For Apache, you should see `0.0.0.0:80->80/tcp` and `0.0.0.0:443->443/tcp`.

```bash
sudo docker compose ps -a
```

Expected:

* Shows created containers.
* If nothing appears, the stack did not create containers successfully.

### Check logs

```bash
sudo docker compose logs --tail=100
```

Expected:

* No fatal errors.
* Web server, app, database, and Redis should start cleanly.

Useful service-level logs:

```bash
sudo docker compose logs --tail=100 app
sudo docker compose logs --tail=100 db
sudo docker compose logs --tail=100 redis
sudo docker compose logs --tail=100 phpmyadmin
```

Expected:

* App container should not show PHP fatal errors.
* DB container should show normal MySQL startup.
* Apache should not show critical errors.

---

## 4) After build: Laravel setup inside the app container

### Enter the app container

```bash
sudo docker exec -it laravel_app bash
```

Expected:

* Shell prompt inside the container.
* Example: `/var/www/html` depending on your Dockerfile.

### Verify PHP and Composer

```bash
php -v
composer -V
```

Expected:

* PHP 8.1 output.
* Composer version output.

### Install dependencies

If `vendor/` is not present or you want to refresh it:

```bash
composer install --no-dev --optimize-autoloader
```

Expected:

* Composer installs packages from `composer.lock`.
* You may see `Installing dependencies from lock file`.
* If the lock file is out of sync, Composer may warn that `composer.json` and `composer.lock` differ.

If a package is not needed in this deployment, remove it properly:

```bash
composer remove livewire/livewire
```

Expected:

* `composer.json` and `composer.lock` are updated together.
* This is better than deleting the line manually.

### Generate app key

```bash
php artisan key:generate
```

Expected:

* `APP_KEY` is written into `.env`.
* Output usually says the application key has been set.

### Database setup

If the database container is new, MySQL usually creates the database from these environment values on first startup:

* `MYSQL_DATABASE=school_management`
* `MYSQL_USER=laravel_user`
* `MYSQL_PASSWORD=laravel_password`

To verify the database exists:

```bash
sudo docker exec -it laravel_db mysql -u laravel_user -p school_management -e "SHOW TABLES;"
```

Expected:

* Either a list of tables (if imported), or empty (if fresh setup).

### Laravel migrations and seeding

Run these inside the app container after the DB is ready:

```bash
php artisan migrate
```

Expected:

* Migrations are applied.
* Tables are created in the database.
* If migrations are already applied, it may say `Nothing to migrate.`

```bash
php artisan seed
```

Expected:

* Seeders populate the database (if seeders exist).
* If no seeders are defined, you may see an error—this is OK.

```bash
php artisan storage:link
```

Expected:

* Creates the public storage symlink.
* If the link already exists, Laravel says so.

### Clear Laravel caches

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize
```

Expected:

* Caches are rebuilt cleanly.
* No configuration errors.

### Import SQL dump (if you have a backup)

If you have an existing database backup at `/tmp/school_management_backup.sql`:

From the host:

```bash
sudo docker exec -i laravel_db mysql -u laravel_user -p school_management < /tmp/school_management_backup.sql
```

When prompted for password, enter: `laravel_password`

Expected:

* SQL imports into the container database.
* No connection errors.
* Use `-i` (not `-it`) when redirecting SQL input.

Alternative: Copy file to container first:

```bash
sudo docker cp /tmp/school_management_backup.sql laravel_db:/tmp/school_management_backup.sql
sudo docker exec -it laravel_db mysql -u laravel_user -p school_management < /tmp/school_management_backup.sql
```

### Check import result

```bash
sudo docker exec -it laravel_db mysql -u laravel_user -p school_management
```

When prompted for password, enter: `laravel_password`

Inside MySQL:

```sql
SHOW TABLES;
SELECT COUNT(*) FROM students;
\q
```

Expected:

* Table list appears.
* If empty, the import did not load tables.

---

## 5) After build: verification and checks

### Test the app container

```bash
php artisan about
```

Expected:

* Laravel version is shown (should be 9.x).
* Environment should be `local` (or `production` if configured).
* PHP version should be 8.1.

### Test the database connection from Laravel

```bash
php artisan tinker
```

Inside tinker:

```php
DB::connection()->getPdo()
```

Expected:

* A PDO object is returned, indicating successful connection.
* If the DB connection fails, check `.env` values, DB service name, and container status.

Exit tinker:

```php
exit
```

### Test HTTP access

From the host:

```bash
curl -I http://localhost
```

Expected:

* `HTTP/1.1 200 OK`, or another valid response like `302 Found`.
* `Connection refused` means Apache is not running or port 80 is blocked.

### Check Apache logs if needed

```bash
sudo docker compose logs app --tail=100
```

Expected:

* No fatal PHP errors.
* Normal startup messages.

### Check MySQL logs if needed

```bash
sudo docker compose logs db --tail=100
```

Expected:

* Normal startup and connection logs.
* No authentication failures unless credentials are wrong.

### Check Redis if used

```bash
sudo docker compose logs redis --tail=100
```

Expected:

* Redis starts cleanly.
* No crash loop.

### Access PHPMyAdmin

From your browser:

```
http://localhost:8080
```

Expected:

* PHPMyAdmin login page appears.
* Username: `laravel_user`
* Password: `laravel_password`
* Server: `db`

---

## 6) Common mistakes and what they mean

### `permission denied while trying to connect to the Docker API`

Meaning:

* Your user is not allowed to access Docker without `sudo`.

Fix:

```bash
sudo usermod -aG docker youruser
newgrp docker
```

Expected:

* `docker ps` works without `sudo` after re-login.

### `no configuration file provided: not found`

Meaning:

* You are not in the directory containing `docker-compose.yml`.

Fix:

* `cd` into the project folder.

### `failed to solve` during build

Meaning:

* The problem is usually in a Dockerfile command.

Check:

* package names
* file paths
* `composer install`
* custom build steps

### `cannot attach stdin to a TTY-enabled container because stdin is not a terminal`

Meaning:

* You used `docker exec -it` with input redirection.

Fix:

```bash
sudo docker exec -i laravel_db mysql -u laravel_user -p school_management < /tmp/backup.sql
```

### `port is already in use`

Meaning:

* Another service is already using port 80, 443, 3306, 6379, or 8080.

Fix:

* Stop the conflicting service, or change the port mapping in `docker-compose.yml`:

```yaml
ports:
  - "8000:80"   # Use 8000 instead of 80
  - "8443:443"  # Use 8443 instead of 443
```

Then access the app at `http://localhost:8000`.

### `Connection refused` when accessing http://localhost

Meaning:

* Apache is not running, or port 80 is not forwarded correctly.

Check:

```bash
sudo docker compose ps
sudo docker compose logs app --tail=50
```

### `SQLSTATE[HY000] [2002] No such file or directory`

Meaning:

* Laravel cannot connect to MySQL.

Check:

* `.env` has `DB_HOST=db` (not `127.0.0.1`)
* MySQL service is running: `sudo docker compose ps db`
* Database container is healthy: `sudo docker compose logs db --tail=20`

---

## 7) Recommended deployment order

1. Verify server readiness.
2. Verify Docker and project files.
3. Validate `docker-compose.yml`.
4. Build and start containers.
5. Check container logs.
6. Enter the app container.
7. Run `composer install` if needed.
8. Generate `APP_KEY` if missing.
9. Prepare or reset MySQL database.
10. Import the SQL dump (if you have a backup).
11. Run migrations if required.
12. Run seeders if available.
13. Create the storage link.
14. Clear and rebuild Laravel caches.
15. Test browser and HTTP access.
16. Test database and Redis connectivity.
17. Access PHPMyAdmin for manual DB checks.

---

## 8) Fast command summary

```bash
cd /home/midas/laravel/AI-vibe\ coding/school-management-system
sudo docker compose config
sudo docker compose up -d --build
sudo docker ps
sudo docker compose logs --tail=100
sudo docker exec -it laravel_app bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate
php artisan seed
php artisan storage:link
php artisan optimize
exit
curl -I http://localhost
sudo docker compose logs --tail=50
```

Expected result:

* App container is up.
* Database container is up.
* Laravel connects to MySQL.
* Tables are created and optionally seeded.
* Apache serves the app at `http://localhost`.
* PHPMyAdmin is accessible at `http://localhost:8080`.

---

## 9) Service Details

### App Service (laravel_app)
- **Image:** PHP 8.1 with Apache
- **Port:** 80 (HTTP), 443 (HTTPS)
- **Working Directory:** `/var/www/html`
- **Mounts:** Entire project directory for live development
- **Depends on:** db, redis

### Database Service (laravel_db)
- **Image:** MySQL 8.0
- **Port:** 3306
- **Credentials:**
  - Database: `school_management`
  - User: `laravel_user`
  - Password: `laravel_password`
  - Root Password: `root_password`
- **Volume:** `dbdata` for persistent storage

### Redis Service (laravel_redis)
- **Image:** Redis 7 Alpine
- **Port:** 6379
- **Use:** Cache, sessions, queue
- **Volume:** `redisdata` for persistent storage

### PHPMyAdmin Service (laravel_phpmyadmin)
- **Image:** PHPMyAdmin latest
- **Port:** 8080
- **Use:** Web-based MySQL management
- **Credentials:** Same as MySQL user

---

## 10) Production Considerations

### Before deploying to production:

1. **Update `.env` for production:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:your-very-long-secret-key-here
   ```

2. **Use strong passwords:**
   - Generate new `MYSQL_PASSWORD`, `MYSQL_ROOT_PASSWORD`
   - Use environment variable files instead of hardcoding

3. **Disable PHPMyAdmin:**
   - Remove from `docker-compose.yml` to reduce attack surface

4. **Configure SSL/TLS:**
   - Replace snake oil certificates with real ones from Let's Encrypt
   - Configure proper HTTPS in Apache

5. **Database backups:**
   - Set up automated daily backups
   - Test restore procedures

6. **Resource limits:**
   - Add memory and CPU limits to services in `docker-compose.yml`
   - Example:
     ```yaml
     services:
       app:
         deploy:
           resources:
             limits:
               cpus: '1'
               memory: 512M
     ```

7. **Logging and monitoring:**
   - Centralize logs with tools like ELK, Splunk, or Datadog
   - Monitor container health

---

## 11) Backup and Restore

### Create a MySQL backup

```bash
sudo docker exec laravel_db mysqldump -u laravel_user -p school_management > ~/backups/school_management_$(date +%Y%m%d_%H%M%S).sql
```

When prompted, enter password: `laravel_password`

Expected:

* Backup file is created with timestamp.

### Restore from backup

```bash
sudo docker exec -i laravel_db mysql -u laravel_user -p school_management < ~/backups/school_management_20240604_120000.sql
```

When prompted, enter password: `laravel_password`

Expected:

* Data is restored into the database.

---

## 12) Useful Docker Compose Commands

```bash
# Build and start services
sudo docker compose up -d --build

# Start services (without rebuild)
sudo docker compose up -d

# Stop services
sudo docker compose down

# Stop services and remove volumes
sudo docker compose down -v

# View logs
sudo docker compose logs -f

# View logs for specific service
sudo docker compose logs -f app

# See running containers
sudo docker compose ps

# Execute command in container
sudo docker exec -it laravel_app php artisan tinker

# Copy file from host to container
sudo docker cp ./script.php laravel_app:/var/www/html/

# Copy file from container to host
sudo docker cp laravel_app:/var/www/html/file.txt ./

# Restart a service
sudo docker compose restart app

# Rebuild a service
sudo docker compose up -d --build app
```

---

## 13) Support and Troubleshooting

For issues or questions, refer to:
- Docker Compose logs: `sudo docker compose logs [service]`
- Laravel logs: `storage/logs/laravel.log`
- MySQL logs: `sudo docker compose logs db`
- Apache error logs: Available in container at `/var/log/apache2/error.log`

Common resources:
- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Laravel Documentation](https://laravel.com/docs)
- [Apache Documentation](https://httpd.apache.org/docs/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
