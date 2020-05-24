
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
