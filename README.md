# CESD Website & Service Platform — Phase 1 MVP

Center of Engineering for Sustainable Development, Faculty of Engineering, National University
of Laos. Bilingual (Lao/English) public website + service-request platform.

This is **Phase 1** only: public bilingual site, laboratory directory, equipment catalog with
search/filter, a basic service-request form, and an admin dashboard for managing laboratories,
equipment, services, news, and incoming requests. Payments, QR report verification, booking
calendars, and analytics are intentionally out of scope — see
[docs/planning/01-architecture-plan.md](docs/planning/01-architecture-plan.md) for the full
phased roadmap.

## Stack

| Layer | Technology |
|---|---|
| Frontend | Next.js 16 (App Router), TypeScript, Tailwind CSS 4, next-intl |
| Backend API | Laravel 13 |
| Admin dashboard | Filament v3 (mounted inside the Laravel app at `/admin`) |
| Auth | Laravel Sanctum |
| Database | SQLite by default for local dev (zero setup); MySQL/PostgreSQL supported |
| Roles/permissions | spatie/laravel-permission |

## Project layout

```
backend/    Laravel API + Filament admin (one codebase, two route surfaces)
frontend/   Next.js public site
docs/       SRS source material + architecture planning docs
assets/     Source documents (equipment inventory, building info) used to seed real data
```

## Prerequisites

- PHP 8.3+ with the `sqlite3`, `pdo_sqlite`, `mbstring`, `curl`, `zip`, `gd` extensions
- Composer 2.x
- Node.js 20+ and npm
- (Optional, production) MySQL 8+ or PostgreSQL 14+

## Backend setup (Laravel)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate

# Sanctum's token table + spatie/laravel-permission's role/permission tables
# are Laravel/package migrations, not hand-written ones — publish them once:
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# SQLite is the default DB (see .env: DB_CONNECTION=sqlite). Create the file:
touch database/database.sqlite

php artisan migrate
php artisan db:seed

# Lets Filament-uploaded files (equipment photos, manuals, news covers) be
# served from /storage/...
php artisan storage:link

# Publishes Filament's compiled CSS/JS into public/ — skip this and the
# admin panel loads as unstyled HTML.
php artisan filament:assets

php artisan serve
```

The API is now running at `http://localhost:8000`, with:
- Public JSON API under `http://localhost:8000/api/v1/...`
- Admin dashboard at `http://localhost:8000/admin`

### Demo accounts (seeded by `RoleAndUserSeeder`)

All demo accounts use the password **`password`**. These are for local development only —
change or remove them before deploying anywhere reachable outside your own machine.

| Role | Email |
|---|---|
| System Administrator | `admin@cesd.test` |
| CESD Director | `director@cesd.test` |
| Unit Head | `unithead@cesd.test` |
| Laboratory Staff | `labstaff@cesd.test` |
| Student / Researcher | `student@cesd.test` |
| Customer / Industry Partner | `customer@cesd.test` |

Only `admin`, `director`, `unit_head`, and `lab_staff` can sign in to `/admin`
(see `User::canAccessPanel()` in `app/Models/User.php`).

### Switching to MySQL/PostgreSQL

Edit `backend/.env`:

```env
DB_CONNECTION=mysql        # or pgsql
DB_HOST=127.0.0.1
DB_PORT=3306               # 5432 for pgsql
DB_DATABASE=cesd
DB_USERNAME=root
DB_PASSWORD=secret
```

Then re-run `php artisan migrate --seed`.

## Frontend setup (Next.js)

```bash
cd frontend
npm install
cp .env.example .env.local   # already points at http://localhost:8000
npm run dev
```

The site runs at `http://localhost:3000` and redirects to `/lo` (Lao, the default locale) or
`/en` (English). The language switcher is in the header.

## Environment variables

**`backend/.env`** (see `backend/.env.example` for the full list) — variables specific to this
project beyond the standard Laravel ones:

```env
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000        # used by config/cors.php
SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost
FILESYSTEM_DISK=public                    # so Filament uploads are web-reachable
```

**`frontend/.env.local`** (see `frontend/.env.example`):

```env
NEXT_PUBLIC_API_URL=http://localhost:8000
```

## Data notes

- **Laboratories**: the 11 CESD facilities (Chemical Analysis, Electron Microscope, X-ray
  Analysis, High-Voltage, Solid Forming, Computer Room, Physical Measurement, Electrical &
  Electronic Measurement, Meeting Room, Exhibition Room, Administration Office), seeded with
  real location/floor data from the JICA equipment inventory's location reference sheet.
- **Equipment**: seeded from the real inventory in
  `assets/equipments/List for Equipment Works as of 30 March 2026.xlsx` (26 parent equipment
  records — 24 from the spreadsheet plus CESD-13 and CESD-21, added from their own manufacturer
  datasheets since those two codes weren't in that particular sheet — some with accessory line
  items; see `database/seeders/data/equipment.json`). A handful of items ship to rooms outside
  CESD's own 11 labs (shared Faculty of Engineering labs); those are seeded without a laboratory
  link rather than invented.
- **Equipment photos & datasheets**: `assets/equipments/1. CESD/*.pdf` (29 real manufacturer
  datasheets) are copied into `storage/app/public/equipment/manuals/` and linked as each
  equipment's downloadable "Datasheet" on its detail page — this is the equipment catalog. 15 of
  the 26 also got a product photo (`storage/app/public/equipment/photos/`), extracted from each
  datasheet's cover page and manually reviewed; the rest had no usable image (decorative graphics
  or scanned pages with no clear product shot) so their card just shows a category icon instead.
- **Laboratory photos**: real site photography from `assets/laboratoies/` and `assets/Building/`,
  resized into `storage/app/public/laboratories/{code}.jpg`. The Administration Office has no
  dedicated interior photo available and reuses the building entrance shot as the closest stand-in.
- **Facility gallery**: a curated set of drone/exterior/entrance/exhibition photos on the About
  page, from `frontend/public/images/facility/`.
- **Icons**: hand-authored inline SVGs (`frontend/src/components/icons/index.tsx`) mapped per
  laboratory code and per service category — no external icon library or fetched assets.
- **National University of Laos logo**: not included — we don't have the official logo file and
  won't fabricate one. The header and footer are already wired to display it the moment you add
  a file at `frontend/public/images/nuol-logo.png` (any size, transparent PNG recommended); no
  code changes needed. Until then that slot silently renders nothing.
- **Lao translations** throughout (UI strings, laboratory/service/news copy) are placeholder
  professional-effort translations, not reviewed by a native Lao speaker or CESD staff. Treat
  all `*_lo` content and `frontend/messages/lo.json` as needing a content review pass before
  this goes in front of real users.

## What's intentionally not built yet (see architecture plan for phasing)

- Customer/portal login UI in the Next.js frontend (Sanctum auth endpoints exist in the API —
  `POST /api/v1/auth/register`, `login`, `logout`, `GET /auth/me` — for Phase 2 to build against)
- Equipment/facility booking calendar
- Quotations, payments
- Digital report upload/download + QR-code verification
- Training registration workflow (the Training page is read-only in Phase 1)
- Partnership/Joint R&D module
- Analytics dashboard beyond Filament's default resource counts

## Architecture & planning docs

- [docs/planning/01-architecture-plan.md](docs/planning/01-architecture-plan.md) — full
  pre-implementation architecture review (schema rationale, API plan, phased roadmap, open
  questions)
