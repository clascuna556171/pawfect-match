#!/bin/bash
# =============================================================================
# PawfectMatch - Clever Cloud Post-Build Hook
# Runs after composer install on Clever Cloud
# =============================================================================

set -e

echo "🐾 PawfectMatch - Running post-build hooks..."

# Install Node.js dependencies and build frontend assets
if [ -f "package.json" ]; then
    echo "📦 Installing Node.js dependencies..."
    npm ci --no-audit --no-fund 2>/dev/null || npm install --no-audit --no-fund
    echo "🔨 Building frontend assets..."
    npm run build
fi

# Create storage directories
echo "📁 Setting up storage directories..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/app/public
mkdir -p storage/logs

# Storage link
echo "🔗 Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

# Cache configuration
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed if empty
USERS_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | tail -1)
if [ "$USERS_COUNT" = "0" ] || [ -z "$USERS_COUNT" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force
fi

echo "✅ Post-build complete!"
