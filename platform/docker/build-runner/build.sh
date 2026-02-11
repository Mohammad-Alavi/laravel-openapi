#!/bin/sh
set -e

git config --global --add safe.directory /workspace/repo

cd /workspace/repo

# Add path repositories for all 5 packages
composer config repositories.json-schema path /packages/json-schema --no-interaction
composer config repositories.openapi path /packages/openapi --no-interaction
composer config repositories.laravel-openapi path /packages/laravel-openapi --no-interaction
composer config repositories.laravel-rules-to-schema path /packages/laravel-rules-to-schema --no-interaction
composer config repositories.laragen path /packages/laragen --no-interaction

# Allow dev packages from path repositories
composer config minimum-stability dev --no-interaction
composer config prefer-stable true --no-interaction

# Install user's dependencies
composer install --no-interaction --no-progress

# Require laragen (resolved via path repositories)
composer require mohammad-alavi/laragen:"*" --no-interaction --no-progress

# Configure minimal environment for artisan
export DB_CONNECTION=sqlite
export DB_DATABASE=:memory:

# Create .env if missing (key:generate needs it)
if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        echo "APP_KEY=" > .env
    fi
fi

php artisan key:generate --no-interaction 2>/dev/null || true

# Generate OpenAPI spec
php artisan laragen:generate --no-interaction

# Copy output
mkdir -p /workspace/output
cp .laragen/openapi.json /workspace/output/openapi.json
