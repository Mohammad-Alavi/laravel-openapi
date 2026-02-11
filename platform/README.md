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
- A GitHub OAuth app (see [GitHub OAuth](#github-oauth) below)

## Setup with DDEV

### 1. Start DDEV

```bash
# From the repository root
ddev start
```

DDEV provides PostgreSQL, PHP, and Node.js. The Docker socket is mounted into the web container automatically (via `.ddev/docker-compose.docker-socket.yaml`) so the platform can run build-runner containers.

### 2. Configure environment

```bash
ddev exec "cd platform && cp .env.example .env"
```

Edit `platform/.env` and set these values:

```
APP_URL=https://laravel-openapi.ddev.site:8443

GITHUB_CLIENT_ID=your_client_id
GITHUB_CLIENT_SECRET=your_client_secret
```

The database settings (`DB_HOST=db`, `DB_USERNAME=db`, `DB_PASSWORD=db`, `DB_DATABASE=laragen_platform`) are pre-configured for DDEV. You need to create the `laragen_platform` database since DDEV's default database is named `db`:

```bash
ddev exec "echo 'CREATE DATABASE laragen_platform;' | PGPASSWORD=db psql -U db -h db postgres" 2>/dev/null || true
```

### 3. Install dependencies and migrate

```bash
ddev exec "cd platform && composer setup"
```

This runs `composer install`, generates an app key, runs migrations, installs npm packages, and builds frontend assets.

### 4. Build the build-runner image

```bash
ddev exec "cd platform && composer build-runner"
```

This builds the Docker image that generates OpenAPI specs from user repositories. Without it, builds will fail.

### 5. Start the dev server

```bash
ddev exec "cd platform && composer dev"
```

The platform is available at **https://laravel-openapi.ddev.site:8443**

Vite HMR runs at **https://laravel-openapi.ddev.site:5174**

## Setup without DDEV (Native)

### 1. Create the database

```bash
createdb laragen_platform
```

Or via psql:

```bash
psql -c "CREATE DATABASE laragen_platform;"
```

### 2. Configure environment

```bash
cd platform
cp .env.example .env
```

Edit `.env` and set:

```
APP_URL=http://localhost:8000

DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laragen_platform
DB_USERNAME=your_user
DB_PASSWORD=your_password

GITHUB_CLIENT_ID=your_client_id
GITHUB_CLIENT_SECRET=your_client_secret
```

### 3. Install dependencies and migrate

```bash
composer install
php artisan key:generate
php artisan migrate
npm install
npm run build
```

### 4. Build the build-runner image

```bash
composer build-runner
```

### 5. Start the dev server

```bash
composer dev
```

The platform is available at **http://localhost:8000**

## GitHub OAuth

The platform requires a GitHub OAuth app for user authentication and repository access.

### Create the OAuth app

1. Go to [GitHub > Settings > Developer settings > OAuth Apps > New OAuth App](https://github.com/settings/developers)
2. Fill in:
   - **Application name**: `Laragen (dev)` (or anything you like)
   - **Homepage URL**: your `APP_URL`
   - **Authorization callback URL**: your `APP_URL` + `/auth/github/callback`

| Environment | Callback URL |
|-------------|-------------|
| DDEV | `https://laravel-openapi.ddev.site:8443/auth/github/callback` |
| Native | `http://localhost:8000/auth/github/callback` |

3. After creating, copy the **Client ID** and generate a **Client Secret**
4. Add both to your `platform/.env`:

```
GITHUB_CLIENT_ID=Iv1.abc123...
GITHUB_CLIENT_SECRET=abc123def456...
```

The redirect URI is auto-derived from `APP_URL` via the `GITHUB_REDIRECT_URI` env var — you don't need to set it manually.

### What the OAuth token is used for

- **Authentication** — users sign in via GitHub
- **Repository cloning** — the build runner clones private repos using the user's OAuth token
- **Webhook creation** — when a project is created, the platform registers a push webhook on the repo

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

The platform needs Docker socket access to spawn build-runner containers. In DDEV, this is handled automatically. For native setups, ensure the current user can run `docker` commands.

```bash
# Build the image (from platform directory)
composer build-runner

# Or manually from the repo root
docker build -t laragen-build-runner -f platform/docker/build-runner/Dockerfile .
```

The image must be named `laragen-build-runner` — this is the name the `BuildRunner` service looks for.

## Testing

```bash
# DDEV
ddev exec "cd platform && composer test"

# Native
cd platform && composer test
```

## Production

The production Dockerfile (`platform/Dockerfile`) builds a self-contained image with Nginx, PHP-FPM, and Supervisor. It requires Docker CLI access for running build-runner containers.

```bash
docker build -t laragen-platform -f platform/Dockerfile platform/
```

Required environment variables for production (set in your hosting provider):

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...
APP_URL=https://your-domain.com
DB_HOST=your-db-host
DB_PASSWORD=your-db-password
GITHUB_CLIENT_ID=your-client-id
GITHUB_CLIENT_SECRET=your-client-secret
```
