# Shani Local Government Area

Public website and council admin panel for Shani Local Government Area, Borno State, Nigeria.

The site publishes sourced information about Shani LGA, its wards, services, public records and notices. Authorized council staff can manage pages, notices, projects, media and resident reports from one dashboard.

## Features

- Public pages for about, history, services, transparency, news, contact, council directory and ward map
- Database-backed page content, notices, projects, site settings and media assets
- Resident enquiries with tracking codes and staff review
- Council admin dashboard at `/admin`
- Media uploads with configurable storage for production
- Server-side cached ward map data for boundaries, schools and health facilities
- Nigeria flag-inspired green/white/green visual theme

## Tech stack

- PHP 8.4
- Laravel 13
- Livewire 4
- Tailwind CSS 4 / Vite
- SQLite locally, MySQL or Postgres in production
- Pest for tests
- Laravel Pint for formatting

## Local setup

```bash
composer install
```

Create the environment file and app key:

```bash
# Windows PowerShell
Copy-Item .env.example .env

# macOS / Linux
cp .env.example .env

php artisan key:generate
```

Create the SQLite database and load the starter content:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --seed
```

Install frontend dependencies and build assets:

```bash
npm install
npm run build
php artisan storage:link
```

If the project is served by Laravel Herd, open:

```text
http://shani-lga.test
```

Otherwise start the development server:

```bash
composer run dev
```

## Create an admin account

The public seeder creates a non-staff test user only. Create or promote a council staff account with:

```bash
php artisan app:create-council-staff
```

The command prompts for a name, email and password. Passwords must be at least 12 characters.

Sign in at:

```text
/council/staff/login
```

Successful sign-in redirects to:

```text
/admin
```

## Useful routes

| Route | Purpose |
| --- | --- |
| `/` | Homepage |
| `/about` | About Shani LGA |
| `/news` | Public notices and updates |
| `/council` | Council directory |
| `/map` | Ward map |
| `/contact` | Contact information |
| `/council/staff/login` | Staff sign-in |
| `/admin` | Admin dashboard |
| `/admin/media` | Media uploads |
| `/admin/settings` | Site settings |

## Testing and formatting

```bash
php artisan test --compact
vendor/bin/pint --dirty --format agent
```

## Media storage

Admin uploads use the disk configured by `MEDIA_DISK`. Local development defaults to the `public` disk.

For production, point `MEDIA_DISK` at persistent object storage. See [DEPLOYMENT.md](DEPLOYMENT.md) for the full Laravel Cloud deployment checklist.

## Deployment

The recommended deployment target is Laravel Cloud. The complete checklist covers:

- PHP runtime and database setup
- Persistent object storage for uploads
- Build and deploy commands
- One-time content seeding
- Production admin creation
- Domain and SSL setup
- Post-deploy verification

See [DEPLOYMENT.md](DEPLOYMENT.md).

## Known follow-ups

- The mapped-homes layer currently reports `mapped homes unavailable` because the Overpass source returns `406`. Boundaries, schools and health facilities still load.
- Contact phone, email, address and office hours display as “Not published” until verified details are entered in `/admin/settings`.
- Chairman gallery labels still need confirmation against current council records.
