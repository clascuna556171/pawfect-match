#!/bin/sh
set -e

echo "🐾 PawfectMatch - Starting deployment..."

# ---------- Storage directories ----------
echo "📁 Ensuring storage directories exist..."
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ---------- Storage link ----------
echo "🔗 Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

# ---------- Generate key if missing ----------
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# ---------- Cache configuration ----------
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ---------- Run migrations ----------
echo "🗄️ Running database migrations..."
php artisan migrate --force

# ---------- Seed database (only if empty) ----------
USERS_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | tail -1)
if [ "$USERS_COUNT" = "0" ] || [ -z "$USERS_COUNT" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force
fi

echo "✅ Deployment complete! Starting services..."

# ---------- Start supervisord (Nginx + PHP-FPM) ----------
exec /usr/bin/supervisord -c /etc/supervisord.conf
