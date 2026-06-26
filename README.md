# 1Langkah — Laravel 12 Port

A fully-functional Laravel 12 port of the original single-page `index.html` showcase
for the **1Langkah — AI-Powered Learning Experience Platform**.

The original SPA used a vanilla-JS hash router that swapped HTML strings into a
single `#app` div. This port rebuilds the same 15 screens as proper Laravel
routes + Blade views, with reusable Blade components in place of the JS
component functions.

## Stack

- PHP 8.4 (static binary)
- Laravel 12.x (skeleton via `composer create-project`)
- Blade for templating + anonymous Blade components (`resources/views/components/*.blade.php`)
- Alpine.js 3.x (loaded from CDN) for tiny UI interactions (chip filters, payment method picker, tabs)
- No build step — original CSS is shipped as `public/css/app.css`

## Routes (named, used by every link)

| Method | Path                          | Name                       | View                       |
|--------|-------------------------------|----------------------------|----------------------------|
| GET    | `/`                           | `landing`                  | `pages.landing`            |
| GET    | `/login`                      | `login`                    | `pages.login`              |
| POST   | `/login`                      | `login.submit`             | redirects to `dashboard`   |
| GET    | `/signup`                     | `signup`                   | `pages.signup`             |
| POST   | `/signup`                     | `signup.submit`            | redirects to `dashboard`   |
| GET    | `/dashboard`                  | `dashboard`                | `pages.dashboard`          |
| GET    | `/kursus`                     | `kursus`                   | `pages.kursus`             |
| GET    | `/kursus/{id}`                | `detail-kursus`            | `pages.detail-kursus`      |
| GET    | `/kursus-saya`                | `kursus-saya`              | `pages.kursus-saya`        |
| GET    | `/bootcamp/online`            | `online-bootcamp`          | `pages.online-bootcamp`    |
| GET    | `/bootcamp/online/{id}`       | `detail-online-bootcamp`   | `pages.detail-online-bootcamp` |
| GET    | `/bootcamp/offline`           | `offline-bootcamp`         | `pages.offline-bootcamp`   |
| GET    | `/bootcamp/offline/{id}`      | `detail-offline-bootcamp`  | `pages.detail-offline-bootcamp` |
| GET    | `/mentor`                     | `mentor`                   | `pages.mentor`             |
| GET    | `/mentor/{id}`                | `profil-mentor`            | `pages.profil-mentor`      |
| GET    | `/kalender`                   | `kalender`                 | `pages.kalender`           |
| GET    | `/pembayaran/{id?}`           | `pembayaran`               | `pages.pembayaran`         |

All "back" buttons use `javascript:history.back()` (browser-native), so deep
navigation between pages works as expected.

## Project structure

```
satu-langkah/
├── app/
│   ├── Http/Controllers/Pages/PageController.php   # one controller, one method per page
│   └── Services/CatalogService.php                 # mirrors the JS `DATA` object
├── routes/web.php                                  # all named routes
├── resources/views/
│   ├── layouts/
│   │   ├── guest.blade.php                         # landing/auth shell (no chrome)
│   │   └── app.blade.php                           # sidebar + topbar shell
│   ├── components/                                 # ~12 reusable Blade components
│   │   ├── icon.blade.php                          # 40 inline SVG icons
│   │   ├── badge.blade.php
│   │   ├── button.blade.php
│   │   ├── avatar.blade.php
│   │   ├── stars.blade.php
│   │   ├── progress-bar.blade.php
│   │   ├── course-card.blade.php
│   │   ├── mentor-card.blade.php
│   │   ├── stat-card.blade.php
│   │   ├── section-header.blade.php
│   │   ├── sidebar.blade.php                       # full sidebar w/ 4 nav groups
│   │   └── topbar.blade.php                        # search + XP + bell + avatar
│   └── pages/                                      # 15 page views
│       ├── landing.blade.php
│       ├── login.blade.php
│       ├── signup.blade.php
│       ├── dashboard.blade.php
│       ├── kursus.blade.php
│       ├── detail-kursus.blade.php
│       ├── kursus-saya.blade.php
│       ├── online-bootcamp.blade.php
│       ├── detail-online-bootcamp.blade.php
│       ├── offline-bootcamp.blade.php
│       ├── detail-offline-bootcamp.blade.php
│       ├── mentor.blade.php
│       ├── profil-mentor.blade.php
│       ├── kalender.blade.php
│       └── pembayaran.blade.php
└── public/css/app.css                              # ported CSS (design tokens + components)
```

## Running

```bash
php artisan serve --host=0.0.0.0 --port=8000 --no-reload
```

Open <http://127.0.0.1:8000/> — the landing page is the entry point.

> Note: this port was developed against a statically-compiled PHP 8.4 binary.
> On a "normal" PHP install (e.g. `apt install php-cli`), `php artisan serve`
> without `--no-reload` works fine — `--no-reload` was only needed because the
> static binary has issues with Laravel's auto-reload file-watcher.
