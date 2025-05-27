#!/bin/bash
set -e

# --- CONFIG ---
ADMIN_USER="${CIVICRM_ADMIN_USER:-admin}"
ADMIN_PASS="${CIVICRM_ADMIN_PASS:-password}"
SETTINGS_FILE="/var/www/html/private/civicrm.settings.php"
DB_HOST="${CIVICRM_DB_HOST:-db}"
DB_PORT="${CIVICRM_DB_PORT:-3306}"
CIVICRM_DB_PASSWORD="${CIVICRM_DB_PASSWORD:-INSECURE_PASSWORD}"

# --- Wait for DB ---
echo "Waiting for database at ${DB_HOST}:${DB_PORT}..."
until nc -z "$DB_HOST" "$DB_PORT"; do
  sleep 3
done
echo "Database is up."

# --- Install CiviCRM if not already installed ---
if [ ! -f "$SETTINGS_FILE" ]; then
  echo "Running CiviCRM installer..."
  su -s /bin/bash www-data -c "CIVICRM_ADMIN_USER=$ADMIN_USER CIVICRM_ADMIN_PASS=$ADMIN_PASS civicrm-docker-install"
else
  echo "CiviCRM already installed."
fi

# --- Start Apache ---
exec apache2-foreground

