#!/usr/bin/env bash
# PORMIN – bootstrap script. Idempotent. Run after each pod restart if services are missing.
# Location: /app/scripts/setup.sh   (persistent under /app)
set -euo pipefail

log() { echo -e "\e[1;32m[pormin-setup]\e[0m $*"; }

# 1. PHP 8.3 via sury.org (persistent packages live outside /app, so re-install if missing)
if ! command -v php >/dev/null 2>&1; then
    log "Installing PHP 8.3 + MariaDB server..."
    mkdir -p /etc/apt/keyrings
    if [ ! -f /etc/apt/keyrings/php.gpg ]; then
        curl -sSLo /etc/apt/keyrings/php.gpg https://packages.sury.org/php/apt.gpg
    fi
    echo "deb [signed-by=/etc/apt/keyrings/php.gpg] https://packages.sury.org/php/ bookworm main" \
        > /etc/apt/sources.list.d/php.list
    apt-get update -qq
    DEBIAN_FRONTEND=noninteractive apt-get install -y -qq \
        php8.3-cli php8.3-mysql php8.3-zip php8.3-gd php8.3-mbstring \
        php8.3-curl php8.3-xml php8.3-bcmath mariadb-server unzip >/dev/null
fi

# 2. Composer
if ! command -v composer >/dev/null 2>&1; then
    log "Installing Composer..."
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer >/dev/null
fi

# 3. MySQL data dir (persistent under /app/pormin-data)
if [ ! -d /app/pormin-data/mysql ]; then
    log "Initialising MariaDB datadir..."
    mkdir -p /app/pormin-data
    mysql_install_db --user=mysql --datadir=/app/pormin-data >/dev/null 2>&1 || true
fi
chown -R mysql:mysql /app/pormin-data
mkdir -p /var/run/mysqld && chown mysql:mysql /var/run/mysqld

# 4. Supervisor programs
cat > /etc/supervisor/conf.d/pormin.conf <<'EOF'
[program:pormin]
command=/usr/bin/php artisan serve --host=0.0.0.0 --port=3000
directory=/app/pormin
user=root
autostart=true
autorestart=true
stdout_logfile=/var/log/supervisor/pormin.out.log
stderr_logfile=/var/log/supervisor/pormin.err.log

[program:mariadb]
command=/usr/bin/mysqld_safe --user=mysql --datadir=/app/pormin-data --socket=/var/run/mysqld/mysqld.sock
autostart=true
autorestart=true
stdout_logfile=/var/log/supervisor/mariadb.out.log
stderr_logfile=/var/log/supervisor/mariadb.err.log
EOF

# 5. Composer vendor (persists in /app/pormin/vendor). Only reinstall if missing.
if [ ! -d /app/pormin/vendor ]; then
    log "Installing composer dependencies..."
    cd /app/pormin && composer install --no-interaction --no-progress
fi

# 6. Start services
supervisorctl reread
supervisorctl update
supervisorctl restart mariadb pormin || true
sleep 4

# 7. Ensure DB user (idempotent) and run migrations if the DB is empty
mysql --socket=/var/run/mysqld/mysqld.sock <<'SQL'
CREATE DATABASE IF NOT EXISTS pormin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'pormin'@'localhost' IDENTIFIED BY 'pormin_password';
GRANT ALL PRIVILEGES ON pormin.* TO 'pormin'@'localhost';
FLUSH PRIVILEGES;
SQL

TABLES=$(mysql --socket=/var/run/mysqld/mysqld.sock -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='pormin';")
if [ "$TABLES" -lt 5 ]; then
    log "Seeding fresh database..."
    cd /app/pormin && php artisan migrate:fresh --seed --force
fi

log "PORMIN ready → http://localhost:3000"
