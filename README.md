
## DRG Builds

### Requirements

- Docker
- PHP (if not running in a container)
- Node / NPM

### Local Development

- Install dependencies: 

```bash
composer install && \
  npm install
```

- Copy and fill out env `cp .env.example .env`

- Start services:

```bash
## In one terminal tab
docker-compose up

## In another
php artisan serve

## And another
npm run watch
```

- Get database migrated and seeded

```bash
php artisan migrate --seed
```

- Install passport keys (run once)

```bash
php artisan passport:install
```

- Default admin user is `admin@admin.com` / `adminadmin`

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

All of the frontend components live in `resources/js/components`. All of these components are automatically
registered and compiled. To add into a rendered view, simply open the view and add the component. For example, if
you wanted to add a component to the create build screen, simply open `resources/views/builds/create.blade.php`. If
your component was called `CharacterBuilderComponent.vue`, you would just pop in
`<character-builder-component></character-builder-component>`

As long as you are running the frontend build process via `npm run watch`, you will have realtime compilation.

### Backend Components

## Admin Panel
We have implemented an admin panel, which includes CRUD interfaces for all models (excluding the incoming Build Statistics model). We are using a framework called [Backpack](https://backpackforlaravel.com/docs/4.1/introduction) to run our admin panel. In order to get this installed locally, you will have to run a couple of commands. This should be a set-and-forget situation, so once you run this you won't have to run again -- excluding sitations where you destroy your local environment.

### Install Backpack

```bash
## install updated dependencies
composer install

## install backpack
php artisan backpack:install

## run migrations
php artisan migrate

## you may have to delete existing user for this
## run db seeders
php artisan db:seed
## if you have errors, you can try individually:
php artisan db:seed --class=RolesAndPermissionsSeeder

php artisan db:seed --class=UserSeeder
```

Finally, it's always a good idea to:
```bash
## clear config cache and cache new one
php artisan config:cache
```


Please raise issues as you have them, and we can add debug info if any of these commands fail.

### Access the Admin Panel
Once you have run your install, you can authenticate to the admin panel with the newly seeded DB credentials. Go to: `https://yoursite.local/admin` and enter your credentials, Email: `admin@test.com` Pass: `adminadmin`. You will then be able to access a basic CRUD interface for all current Models.

This is the base-level setup for the admin panel, and we can continue to add features from here as needed.

### Permissions Manager
Now that we have an admin panel, we need to have a permissions system in place to eventually guard it from normal users. Luckily, Backpack has integrated a good package for this. We now have a users, permissions and roles management system in place. 

We have implemented a basic admin role, as well as basic checks on the admin panel to see if you are the proper role. Make sure to login and give yourself access to the admin panel on a new account if you want.

You can find all these settings by clicking on "Authentication" in the admin panel side-nav.
