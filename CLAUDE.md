# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Launchory is a product launch + directory platform (similar to TinyLaunch.com) built with Laravel 12. Makers submit products, the community votes, top 3 winners get badges + dofollow backlinks. It includes a permanent searchable product directory.

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+, MySQL
- **Frontend:** Livewire 3 + Volt (Class-based), Alpine.js, Tailwind CSS 4
- **Auth:** Laravel Breeze (Livewire Volt Class starter kit)
- **Build:** Vite 7, PostCSS
- **Testing:** Pest 4 (PHPUnit-based), SQLite in-memory for tests

## Common Commands

```bash
# Full dev environment (server + queue + logs + vite)
composer dev

# Run tests
composer test
# Or directly:
php artisan test

# Run a single test file
php artisan test --filter=ExampleTest

# Lint/format PHP
./vendor/bin/pint

# Build frontend assets
npm run build

# Dev server for frontend only
npm run dev

# Initial setup
composer setup
```

## Architecture

### Livewire Volt (Class-based)
Pages live in `resources/views/livewire/pages/` as Blade files with embedded Volt class components. Auth pages are under `resources/views/livewire/pages/auth/`. Livewire actions and form objects are in `app/Livewire/Actions/` and `app/Livewire/Forms/`.

### Layouts
- `resources/views/layouts/app.blade.php` — authenticated layout
- `resources/views/layouts/guest.blade.php` — guest/auth layout
- View components in `app/View/Components/`

### Routing
- `routes/web.php` — main routes (currently just welcome, dashboard, profile)
- `routes/auth.php` — authentication routes (managed by Breeze)
- Livewire Volt pages auto-register routes via their page directive

### Testing
- Tests use Pest syntax (not PHPUnit class style)
- Feature tests in `tests/Feature/`, unit tests in `tests/Unit/`
- Tests run against SQLite `:memory:` (configured in `phpunit.xml`)

## Important Constraints (from prompt.md)

- Do NOT modify Breeze auth pages, the existing User model/migration, or Vite config
- The project plan in `prompt.md` outlines the full feature roadmap including: Spatie packages (permissions, sluggable, medialibrary, sitemap), Filament admin panel, Laravel Cashier for payments, and SEOTools
- When building new features, follow the step-by-step plan in `prompt.md`
