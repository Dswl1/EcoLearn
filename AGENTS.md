# EcoLearn — Agent Guide

## Stack

- **Laravel 11** + **Breeze** (Blade + Alpine.js + Tailwind CSS 3)
- **Pest** for testing (not PHPUnit directly)
- **Vite** for frontend build
- **SQLite** default per `.env.example`; actual `.env` uses MySQL (`ecolearn` db)
- Session, cache, and queue all use `database` driver

## Key Commands

```bash
# Dev servers (runs artisan serve + queue:listen + pail + Vite concurrently)
composer dev

# Frontend
npm run dev      # Vite dev
npm run build    # Vite production build

# Backend
php artisan serve
php artisan queue:listen --tries=1
php artisan pail --timeout=0   # log viewer

# Testing (Pest, not phpunit)
./vendor/bin/pest
./vendor/bin/pest --filter testName
./vendor/bin/pest tests/Feature/ExampleTest.php

# Linting
./vendor/bin/pint
```

## Project Structure

```
app/
  Http/Controllers/
    Admin/ContentController.php  # Full CRUD for educational content
  Http/Middleware/
    AdminMiddleware.php          # Checks is_admin flag
    SetLocale.php                # Reads locale cookie, sets App locale
  Models/
    User.php                     # is_admin boolean cast
    Content.php                  # Content model with HasFactory, slug, user relation
  View/Components/               # AppLayout, GuestLayout
bootstrap/
  app.php                        # AdminMiddleware alias, SetLocale prepended to web
config/
database/
  migrations/
    0001_01_01_000000_create_users_table.php   # includes is_admin column
    0001_01_01_000001_create_cache_table.php
    0001_01_01_000002_create_jobs_table.php
    2025_06_07_000001_create_contents_table.php
  factories/
    UserFactory.php              # default is_admin=false
    ContentFactory.php
  seeders/DatabaseSeeder.php     # admin123@gmail.com (is_admin=true), user@example.com
resources/
  views/
    app.blade.php                # Main layout with sidebar + header (@yield('content'))
    admin/content/               # index, create, edit, show — all translated & responsive
    auth/                        # login, register, forgot-password, reset-password, verify-email, confirm-password — all translated
    dashboard/                   # index (auth), guest/index (unauth) — translated
    profile/                     # edit + partials (update-profile, update-password, delete-user) — translated
    layouts/                     # app, guest, auth, navigation — translated
    partials/                    # header (with lang switcher), sidebar — translated
    welcome.blade.php            # Fully translated
  css/app.css
  js/app.js
routes/
  web.php                        # dashboard, admin.content CRUD, lang.switch, profile
  auth.php                       # Breeze auth routes
  console.php
tests/
  Pest.php                       # RefreshDatabase auto-applied to Feature tests
  Feature/
    Admin/ContentTest.php        # 9 tests covering full CRUD + authz
  Unit/
  Feature/auth/                  # Breeze auth tests
```

## Testing

- Feature tests auto-use `RefreshDatabase` (defined in `tests/Pest.php`)
- No `phpunit.xml` overrides needed — Pest handles config
- SQLite in-memory for tests (env vars in `phpunit.xml`)

## Frontend Conventions

- Tailwind with custom dark theme — colors use CSS variables defined in `tailwind.config.js` (e.g., `bg-surface`, `text-primary-container`)
- Fonts: Space Grotesk (headings), Geist (body), JetBrains Mono (labels)
- Icons: Material Symbols (`material-symbols-outlined`)
- Custom utility classes in `resources/css/app.css`: `.glass-card`, `.neon-text`, `.neon-button`, `.neon-shadow`, `.grid-background`, `.scanline`
- Vite entry points: `resources/css/app.css` + `resources/js/app.js`

## Environment

- Copy `.env.example` → `.env`, then `php artisan key:generate`
- `.env` currently uses MySQL; tests always use SQLite in-memory
- `database/database.sqlite` exists on disk (from default setup)
- `php artisan migrate` to set up tables
- SMTP configured for Gmail in `.env`; mail driver is `log` in tests

## Notable

- Educational platform focused on UN SDGs with complete learning flow
- **Models**: `User`, `Course`, `Flashcard`, `Quiz`, `UserProgress`, `Submission`
- **User Roles**: Admin (`is_admin` boolean) + regular users
- **Learning Flow**: Browse → Enroll → Read → Flashcards → Quiz → Progress tracked
- **Submission Workflow**: User uploads material → Admin reviews → Approved/Rejected
- **Admin Routes** protected by `admin` middleware at `bootstrap/app.php`
- Admin can manage all courses; regular users only their own
- 5 seeded SDG courses with flashcards & quizzes (`php artisan db:seed`)
- Demo accounts: `admin123@gmail.com` / `admin123` (admin) and `user@example.com` / `password`
- All layouts use custom dark theme (no Breeze default light theme remnants)
- Auth pages use 3 layout files: `layouts/auth.blade.php` (login/register/forgot-password via `@yield`), `layouts/guest.blade.php` (reset/verify/confirm via `{{ $slot }}`), `layouts/app.blade.php` (dashboard/profile)
- `layouts/auth.blade.php` has inline particle-container + scanline + grid-background; JS particles/toast handled by `resources/js/app.js`
- `welcome.blade.php` has its own inline Alpine mobile menu (`x-data="mobileMenuOpen"`)
- `composer dev` uses `concurrently` with labeled output for server/queue/logs/vite
- `.editorconfig`: 4-space indent, LF line endings, final newline
- No frontend test setup exists yet
