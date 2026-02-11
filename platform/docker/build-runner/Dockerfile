FROM php:8.2-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    postgresql-dev \
    sqlite-dev \
    && docker-php-ext-install \
    pdo_pgsql \
    pdo_sqlite \
    pdo_mysql \
    zip \
    intl \
    bcmath \
    && rm -rf /var/cache/apk/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy json-schema package
COPY JSONSchema/ /packages/json-schema/src/
COPY platform/docker/build-runner/packages/json-schema/composer.json /packages/json-schema/composer.json

# Copy openapi package
COPY oooapi/ /packages/openapi/src/
COPY platform/docker/build-runner/packages/openapi/composer.json /packages/openapi/composer.json

# Copy laravel-openapi package
COPY src/ /packages/laravel-openapi/src/
COPY config/openapi.php /packages/laravel-openapi/config/openapi.php
COPY platform/docker/build-runner/packages/laravel-openapi/composer.json /packages/laravel-openapi/composer.json

# Copy laravel-rules-to-schema package (config/ is included in the copy, provider resolves via __DIR__/../config/)
COPY laravel-rules-to-schema/ /packages/laravel-rules-to-schema/src/
COPY platform/docker/build-runner/packages/laravel-rules-to-schema/composer.json /packages/laravel-rules-to-schema/composer.json

# Copy laragen package
COPY laragen/ /packages/laragen/src/
COPY config/laragen.php /packages/laragen/config/laragen.php
COPY platform/docker/build-runner/packages/laragen/composer.json /packages/laragen/composer.json

# Copy build script
COPY platform/docker/build-runner/build.sh /usr/local/bin/build.sh
RUN chmod +x /usr/local/bin/build.sh

WORKDIR /workspace

ENTRYPOINT ["/usr/local/bin/build.sh"]
