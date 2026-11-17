# Laravel 13 and Filament migration

Implemented November 16, 2026. The application is now on Laravel 13.42.0 with Filament 5 and Laravel Boost 2.10.4. Deployment has not been performed.

## Runtime and dependencies

The project requires PHP 8.4+, including Zip and Intl. Laravel itself requires PHP 8.3, but the Laravel 13-compatible backup package requires PHP 8.4. Composer resolves against PHP 8.4.0 to prevent accidental PHP 8.5-only dependencies. Local verification used the installed PHP 8.5.12 binary; the default shell PHP was 8.2 and must be changed before running the application with plain `php`.

Framework checkpoints were resolved and checked sequentially: Laravel 8 baseline, 9, 10, 11, 12, and 13. The Laravel 9 and 10 checkpoints were development-only: public regression tests passed while the admin awaited Filament. Laravel 11 restored and verified the admin panel. Intermediate unsupported versions were not deployed.

Major integration changes:

- Replaced Backpack, permission manager, chart integration, controllers, views, config and generators with Filament resources, permissions and activity widgets.
- Replaced proxy/CORS packages with Laravel middleware and upgraded Flysystem's S3 adapter to v3.
- Upgraded Passport to v13 while retaining integer client IDs, the token-management JSON API and password grant support.
- Upgraded Lighthouse to v6, rebuilt its configuration with API-guard authentication, and migrated loadout authorization to `@canFind`. GraphQL create/update/delete authorization is covered by tests.
- Replaced the abandoned GraphQL playground with the development-only GraphiQL package.
- Preserved `nagy/laravel-rating` v2.2 as a local MIT-licensed fork with updated framework constraints; its namespaces and tables remain unchanged. The existing local comments package also has updated framework constraints. Both require ongoing maintenance.
- Updated backup configuration, test tooling and CI. Laravel Pint replaces the obsolete style package.
- Explicitly retained PHP session serialization and the existing session guard name `backpack` to preserve compatibility with session and permission data.

## Admin behavior

Filament serves `/admin`. Users must have `view-admin`; resources enforce the following additional permissions on direct page access and record actions, not merely navigation visibility:

| Resources | Permission |
| --- | --- |
| Characters, guns, mods, overclocks, equipment, equipment mods, throwables, patches, loadouts | `manage-stats` |
| Posts | `manage-posts` |
| Users, roles, permissions | `manage-users` |

Forms preserve game statistics, JSON data, relationships and costs. Tables include search, ordering and relationship filters. Dashboard widgets show new users, new loadouts and updated loadouts over the existing reporting period. Comments retain their existing `/comments/admin` workflow and `manage-comments` permission.

Existing users, hashes, roles and permission assignments are retained. Spatie's models replace Backpack permission models. The old routes registered users twice under conflicting permission groups; Filament assigns user management to `manage-users`.

## Validation

- Local PHPUnit suite: 21 tests, 112 assertions pass on PHP 8.5 / SQLite. Coverage includes public login/logout/reset, loadout ownership, favorites/votes, GraphQL reads and mutations, comments visibility, admin authorization, every resource page, character CRUD through Livewire, real Passport bearer-token issuance/revocation and legacy client migration.
- Production Vue/Mix build passes on Node 24. The pre-existing modified asset manifest was preserved exactly.
- Composer validation and actual platform requirements pass. Composer reports no known security advisories in the final dependency set. A warning about the deliberately pinned local rating fork is expected.
- Artisan boots, routes load, configuration caching and Blade compilation pass.
- Boost's MCP initialize and tools/list requests succeed. Project-local Codex MCP configuration points at the compatible installed PHP binary; its machine-specific file is ignored. Agent guidance and skills are committed.
- Fresh SQLite migrations succeed. Full seeding reaches the existing MySQL-specific overclock SQL dump and fails on SQLite syntax; that import is not portable to SQLite.
- CircleCI now targets PHP 8.4 and runs SQLite and MySQL regression suites, production asset build and Dusk builder tests. CI has not been run from this workstation. Local MySQL and Dusk execution were unavailable because MySQL/Docker and Chrome are not installed here.

## Existing-database deployment

1. Rehearse against a sanitized restored production database with the production PHP/extensions, storage and queues. Verify all admin forms/filters, existing permission assignments, comments moderation, builder, public login, API clients, mail, backups and scheduled jobs. Require green MySQL and browser CI checks before release.
2. Back up the database, application artifact, uploads, APP_KEY and existing OAuth keys. Test restoring that backup. The OAuth schema conversion intentionally cannot be reversed with `migrate:rollback`.
3. Select PHP 8.4+ for CLI, web and workers. Install the committed lockfile (`composer install --no-dev --optimize-autoloader`), build assets, and confirm `composer check-platform-reqs` passes. Composer publishes Filament assets through `filament:upgrade`.
4. Pause writes and workers during the migration. Run `php artisan migrate --force`. Published Passport migrations support fresh installations; the new legacy-client migration upgrades existing client ownership, redirect and grant columns while keeping IDs unchanged.
5. Run `php artisan passport:hash --force` to convert existing plaintext OAuth client secrets. This is idempotent and tested. Keep the original client secrets with their consumers; the server now stores hashes. Do not regenerate OAuth keys or recreate clients on an existing installation. Ensure key files have the permissions required by Passport (private 0600; public 0660 or 0600).
6. Configure `BACKUP_NOTIFICATION_MAIL_TO` and a valid `MAIL_FROM_ADDRESS` for production backup alerts. An empty backup recipient list is accepted for local/test boot.
7. Rebuild config/view caches, restart workers and perform the application smoke checks before resuming traffic.

Rollback after client-secret hashing or schema migration requires the rehearsed database backup plus the previous artifact/runtime. Account for writes since the backup; do not blindly roll back the application against the changed OAuth schema.

## References

- https://laravel.com/docs/9.x/upgrade
- https://laravel.com/docs/10.x/upgrade
- https://laravel.com/docs/11.x/upgrade
- https://laravel.com/docs/12.x/upgrade
- https://laravel.com/docs/13.x/upgrade
- https://filamentphp.com/docs/5.x/introduction/installation
- https://github.com/laravel/passport/blob/13.x/UPGRADE.md
- https://github.com/laravel/boost
