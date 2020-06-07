
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

- Copy and fill out env `cp .env.example .env` (DB_DATABASE, DB_USERNAME and DB_PASSWORD must match docker-compose config for mysql container.)

- Get database migrated and seeded

```bash
php artisan migrate
php artisan db:seed
php artisan key:generate
php artisan config:clear
```

- Start services:

```bash
## In one terminal tab
docker-compose up

## In another
php artisan serve

## And another
npm run watch
```

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

# Install Backpack

```
# install updated dependencies
composer install

# install backpack
php artisan backpack:install
```

Additionally, if there are DB-related errors, it would be a good idea to go ahead and run `php artisan migrate`

Finally, it's always a good idea to:
```
# clear config cache and cache new one
php artisan config:cache
```


Please raise issues as you have them, and we can add debug info if any of these commands fail.

# Access the Admin Panel
Once you have run your install, you can authenticate to the admin panel with your normal login credentials. Go to: `https://yoursite.local/admin` and enter your credentials, or register if you need to. You will then be able to access a basic CRUD interface for all current Models.

This is the base-level setup for the admin panel, and we can continue to add features from here as needed.

# Permissions Manager
Now that we have an admin panel, we need to have a permissions system in place to eventually guard it from normal users. Luckily, Backpack has integrated a good package for this. We now have a users, permissions and roles management system in place. When we are ready to push this version to Production, we will likely create a DB seeder to setup the admin role. It's very simple to do even from the admin panel, so either way we are covered.

That being said, the `/admin` route is not protected from normal users, to enable easy admin panel access on your *local* installs. I wanted to save us all the time of having to establish roles etc. every time, as you will be the only local user anyways. In addition to the setup of the admin role, we need to enable some middleware to protect this when pushing to Production. Easy enough once we're ready for that!
