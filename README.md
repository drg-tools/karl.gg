
## DRG Builds
[![StyleCI](https://github.styleci.io/repos/260825558/shield?branch=master)](https://github.styleci.io/repos/260825558)
[![CI](https://github.com/drg-tools/karl.gg/actions/workflows/ci.yml/badge.svg)](https://github.com/drg-tools/karl.gg/actions/workflows/ci.yml)

### Requirements

- Docker
- PHP 8.4+ with the Laravel extensions, PDO MySQL/SQLite, Intl, and Zip
- Node / NPM (production build verified with Node 24)

### Local Development

- Install dependencies: 

```bash
composer install && \
  npm ci
```

- Copy and fill out env `cp .env.example .env`

- Start services:

```bash
## In one terminal tab
docker-compose up

## In another: runs the web server, queue worker and Vite together
composer dev
```

- Get database migrated and seeded

```bash
php artisan migrate --seed
```

_Note: If you want the advanced weapon stats, you will need to import a raw sql file by exporting for our standalone [weapons calculator](https://github.com/drg-tools/drg-weapons-calculator)._

- Default admin user is `admin@test.com` / `adminadmin` (local seeded data only)

### Lint / Styles

We use the `laravel` code style which enforces things like where to put new lines, spaces, etc.

We use [StyleCI](https://styleci.io/) to help keep us in check, but you can also run the style fixer locally.

To check if you are in compliance run:

```bash
composer check-style
```

To automatically fix the violations, run:

```bash
composer fix-style
```

Worst case scenario, StyleCI will make a PR against yours to fix any style issues and mark the PR as failed.

### Frontend Components

Public pages render with Blade. The loadout builder mounts Vue 3 only on the build page, using `resources/js/builder.js` and `resources/js/components/LoadoutBuilder.vue`. It keeps selections locally and calls session-authenticated Laravel web routes with CSRF protection. Assets are bundled with Vite (`vite.config.js`) and Tailwind CSS v4 (configured in `resources/css/app.css`). Run `npm run dev` (or `composer dev`) for development and `npm run build` to build assets. Static images live in `public/assets`.

GraphQL and Passport are retired; OAuth keys and clients are no longer needed. Existing OAuth tables are retained without dropping data. The comments package still has its own separate frontend bundle.

### Backend Components

## Admin Panel

The admin panel uses Filament 5 at `/admin`. Existing users and Spatie permission assignments are preserved. The session guard retains the name `backpack` for compatibility; Backpack itself is removed.

- Panel access: `view-admin`.
- Game data and loadouts: `manage-stats`.
- Posts: `manage-posts`.
- Users, roles, and permissions: `manage-users`.
- Existing comments moderation: `/comments/admin`, requiring `manage-comments`.

The local database seeder creates `admin@test.com` with password `adminadmin` and the `super-admin` role. Run seeders only in local/test environments.

### Laravel Boost

Boost is installed as a development dependency. With PHP 8.4+ selected in your shell:

```sh
php artisan boost:install
```

Select your coding assistant to generate its local MCP configuration. Codex guidance and skills are committed; `.codex/config.toml` is local because PHP executable paths vary between developers. Restart/reconnect your assistant after installation. This checkout's local MCP configuration uses its installed PHP 8.5 binary.

### Testing and deployment

```sh
APP_ENV=testing DB_CONNECTION=sqlite DB_DATABASE=:memory: vendor/bin/phpunit
```

The full seed data includes MySQL-specific SQL imports. Use the disposable MySQL service for full seeding. GitHub Actions runs the regression suite on SQLite and MySQL.

See [the upgrade and deployment notes](docs/laravel-upgrade.md) before upgrading an existing database, particularly the Passport client schema and secret migration.
