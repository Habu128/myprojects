# Deployment checklist

This application is Laravel 13 and requires PHP 8.4.1 or later. The recommended deployment target is [Laravel Cloud](https://cloud.laravel.com/).

## 1. Prepare the repository

Run these locally before deploying:

```bash
php artisan test --compact
npm run build
git status --short
```

The repository currently has no Git remote. Create a private repository on GitHub, GitLab, or Bitbucket, then push:

```bash
git remote add origin https://github.com/YOUR_USERNAME/shani-lga.git
git branch -M main
git push -u origin main
```

Both `composer.lock` and `package-lock.json` are committed and must stay committed.

## 2. Create the Laravel Cloud application

1. Go to <https://cloud.laravel.com>.
2. Click **New application**.
3. Connect your Git provider and select this repository.
4. Choose a region close to Shani/Borno State users.
5. Create the application.

In **General Settings**, set the PHP runtime to **PHP 8.4.1 or later**.

## 3. Attach a database

SQLite is not supported in Laravel Cloud production because the filesystem resets on every deploy.

1. Open the environment infrastructure canvas.
2. Click **Add database**.
3. Choose **Laravel MySQL** or **Postgres**.
4. Attach it and redeploy.

Set these environment variables in Cloud:

```ini
APP_NAME=Shani Local Government Area
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

Use `DB_CONNECTION=pgsql` if you chose Postgres.

Generate a production app key locally and paste it into Cloud:

```bash
php artisan key:generate --show
```

```ini
APP_KEY=base64:...
```

## 4. Configure persistent media uploads

The admin media controller uses `MEDIA_DISK`, defined in `config/filesystems.php`, instead of a hardcoded local disk.

Local development defaults to the `public` disk. On Laravel Cloud:

1. Click **Add bucket** on the infrastructure canvas.
2. Choose **Laravel Object Storage**.
3. Make the bucket **public** so uploaded images can be viewed by visitors.
4. Name the disk `public` to keep the default configuration, or choose another name such as `r2`.
5. Attach the bucket to the environment and redeploy.

If you name the bucket `public`, no extra `MEDIA_DISK` value is needed. If you name it `r2`, add:

```ini
MEDIA_DISK=r2
```

The `league/flysystem-aws-s3-v3` package is already installed for S3-compatible storage.

Do not set per-object `visibility: 'public'` in code. Cloudflare R2, which powers Laravel Cloud Object Storage, manages visibility at the bucket level.

The seeded `public/images/*` files — the Borno State logo and chairman photographs — are tracked in Git and deploy with the application.

## 5. Build and deploy commands

In **Settings → Deployments**, set:

### Build command

```bash
composer install --no-dev --no-interaction --prefer-dist && npm ci --audit false && npm run build && php artisan optimize
```

### Deploy command

```bash
php artisan migrate --force
```

Do not run these during deployment:

```bash
php artisan storage:link
php artisan optimize:clear
php artisan queue:restart
```

Laravel Cloud does not persist filesystem changes made by deploy commands, and it manages queue workers automatically.

## 6. Seed content once

After the first successful deploy, open **Commands → Open console** in Laravel Cloud and run each seeder once:

```bash
php artisan db:seed --class=ContentPageSeeder --force
php artisan db:seed --class=NoticeSeeder --force
php artisan db:seed --class=SiteSettingSeeder --force
php artisan db:seed --class=MediaAssetSeeder --force
```

Do not add seeders to the deploy command. They use `updateOrCreate()` and would overwrite edits made through the admin panel.

Do not run `DatabaseSeeder` in production; it creates a local `test@example.com` user.

## 7. Create the production admin

In the Laravel Cloud console:

```php
$user = App\Models\User::create([
    'name' => 'Shani LGA Admin',
    'email' => 'admin@shanilga.gov.ng',
    'password' => 'USE-A-NEW-STRONG-PASSWORD',
    'email_verified_at' => now(),
]);

$user->forceFill(['is_council_staff' => true])->save();
```

The local `admin@shanilga.gov.ng` account is stored only in the local database and is not part of the repository.

## 8. Add the custom domain

1. Open **Settings → Domains**.
2. Add the public domain.
3. Add the DNS records shown by Laravel Cloud at your registrar.
4. Laravel Cloud verifies the domain and issues SSL automatically.

The free `*.laravel.cloud` domain can be used before DNS is ready.

## 9. Verify the deployment

Check these pages:

- `/`
- `/about`
- `/news`
- `/council`
- `/map`
- `/contact`
- `/council/staff/login`
- `/admin`

Then test:

1. Sign in at `/council/staff/login`; it should redirect to `/admin`.
2. Add a notice and confirm it appears at `/news`.
3. Edit site settings and confirm the public page updates.
4. Upload media and open the returned image URL directly.
5. Submit a resident report and review it at `/council/staff/reports`.
6. Click **Refresh map data** on the dashboard.
7. Review Laravel Cloud logs for errors.

## Known follow-ups

- The mapped-homes layer currently reports `mapped homes unavailable` because the Overpass source returns `406`. Boundaries, schools and health facilities still load.
- Contact phone, email, address and office hours still display as “Not published” until verified details are entered in `/admin/settings`.
- Chairman gallery labels still need confirmation against current council records.
