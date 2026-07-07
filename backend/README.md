# CESD Backend (Laravel + Filament)

Laravel API + Filament admin dashboard for the CESD website & service platform.

See the [project root README](../README.md) for full setup instructions, environment
variables, demo accounts, and architecture notes. Quick start:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan filament:assets
php artisan serve
```

API: `http://localhost:8000/api/v1` · Admin: `http://localhost:8000/admin`
