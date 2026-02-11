#!/bin/sh
set -e

cd /workspace/repo

# Add path repositories for all 5 packages
composer config repositories.json-schema path /packages/json-schema --no-interaction
composer config repositories.openapi path /packages/openapi --no-interaction
composer config repositories.laravel-openapi path /packages/laravel-openapi --no-interaction
composer config repositories.laravel-rules-to-schema path /packages/laravel-rules-to-schema --no-interaction
composer config repositories.laragen path /packages/laragen --no-interaction

# Install user's dependencies
composer install --no-interaction --no-progress

# Require laragen (resolved via path repositories)
composer require mohammad-alavi/laragen:"*" --no-interaction --no-progress

# Configure minimal environment for artisan
export DB_CONNECTION=sqlite
export DB_DATABASE=:memory:
php artisan key:generate --no-interaction 2>/dev/null || true

# Generate OpenAPI spec
php artisan laragen:generate --no-interaction

# Copy output
mkdir -p /workspace/output
cp .laragen/openapi.json /workspace/output/openapi.json
