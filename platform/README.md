# Laragen Platform

The Laragen platform is a SAAS web application that provides 1-click OpenAPI specification generation from Laravel repositories. Users authenticate via GitHub, select a repository, and the platform builds an OpenAPI spec using the Laragen packages.

## Architecture

The platform involves three separate Docker concerns:

```
┌─────────────────────────────────────────────────────────────────┐
│  DDEV (Development)                                             │
│  .ddev/config.yaml                                              │
│                                                                 │
│  Provides: PostgreSQL, Nginx, PHP-FPM, Node.js                  │
│  Ports:                                                         │
│    - :443  → workbench (main package dev)                       │
│    - :8443 → platform (php artisan serve on :8000)              │
│    - :5174 → Vite HMR (dev server on :5173)                    │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  Build Runner (docker/build-runner/)                            │
│  Dockerfile + build.sh                                          │
│                                                                 │
│  Purpose: Runs inside the platform to generate OpenAPI specs    │
│  from user repositories. Bundles all Laragen packages.          │
│  Invoked by: platform queue workers                             │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  Production (platform/Dockerfile)                               │
│  Multi-stage: Node → Composer → PHP-FPM + Nginx                 │
│                                                                 │
│  Purpose: Self-contained production image of the platform.      │
│  Includes: Nginx, PHP-FPM, Supervisor, Docker CLI               │
└─────────────────────────────────────────────────────────────────┘
```

## Prerequisites

- [DDEV](https://ddev.readthedocs.io/en/stable/) (recommended) **or** PHP 8.2+, PostgreSQL 16, Node.js, and Composer
- [Docker](https://docs.docker.com/get-docker/) (required for the build runner)
- A GitHub OAuth app for authentication (see [GitHub OAuth](#github-oauth) below)

## Setup with DDEV

```bash
# From the repository root
ddev start

# Install dependencies and run migrations
ddev exec "cd platform && composer setup"

# Start the dev server
ddev exec "cd platform && composer dev"
```

The platform is available at **https://laravel-openapi.ddev.site:8443**

Vite HMR runs at **https://laravel-openapi.ddev.site:5174**

## Setup without DDEV (Native)

```bash
cd platform

# Install PHP dependencies
composer install

# Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# Configure .env with your local database credentials:
#   DB_CONNECTION=pgsql
#   DB_HOST=127.0.0.1
#   DB_PORT=5432
#   DB_DATABASE=laragen_platform
#   DB_USERNAME=your_user
#   DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Install frontend dependencies and build
npm install
npm run build

# Start the dev server
composer dev
```

The platform is available at **http://localhost:8000**

## Daily Development

```bash
# DDEV
ddev exec "cd platform && composer dev"

# Native
cd platform && composer dev
```

This starts four processes concurrently:

| Process | What it does |
|---------|-------------|
| **server** | `php artisan serve` (HTTP on port 8000) |
| **queue** | `php artisan queue:listen` (processes build jobs) |
| **logs** | `php artisan pail` (real-time log tailing) |
| **vite** | `npm run dev` (frontend HMR) |

## Build Runner

The build runner is a Docker image that generates OpenAPI specs from user repositories. It bundles all Laragen packages and is invoked by the platform's queue workers.

```bash
# Build the image (from platform directory)
composer build-runner

# Or manually from the repo root
docker build -t laragen-build-runner -f platform/docker/build-runner/Dockerfile .
```

## Testing

```bash
# DDEV
ddev exec "cd platform && composer test"

# Native
cd platform && composer test
```

## GitHub OAuth

The platform uses GitHub OAuth for authentication. To set up:

1. Go to **GitHub > Settings > Developer settings > OAuth Apps > New OAuth App**
2. Set the **Authorization callback URL** to your `APP_URL` + `/auth/github/callback`
   - DDEV: `https://laravel-openapi.ddev.site:8443/auth/github/callback`
   - Native: `http://localhost:8000/auth/github/callback`
3. Copy the Client ID and Client Secret into your `.env`:

```
GITHUB_CLIENT_ID=your_client_id
GITHUB_CLIENT_SECRET=your_client_secret
```

## Production

The production Dockerfile (`platform/Dockerfile`) builds a self-contained image with Nginx, PHP-FPM, and Supervisor. It requires Docker CLI access for running build-runner containers.

```bash
docker build -t laragen-platform -f platform/Dockerfile platform/
```
