# Blue Dine Cuisines

A website for **Blue Dine Cuisines**, a Port Harcourt-based private chef and
meal prep service owned by Eureka Francis. The site is being built in eight
phases; this repository is currently at the end of **Phase 1: Foundation**.

## Stack

- **Laravel 11** (PHP 8.2+)
- **Inertia.js** with **Vue 3** (Composition API)
- **Tailwind CSS** with a custom Blue Dine palette
- **MySQL 8**
- **Vite** for asset bundling
- **Laravel Breeze** for auth scaffolding (reused for admin login later)

Target deployment: Contabo VPS running HestiaCP, PHP 8.2+, MySQL 8. Full
deployment instructions ship in Phase 8.

### Brand palette

| Token      | Hex       | Usage                         |
| ---------- | --------- | ----------------------------- |
| `primary`  | `#1F3B2D` | Deep forest green             |
| `accent`   | `#C9A24B` | Warm gold                     |
| `cream`    | `#F7F1E5` | Page background               |
| `charcoal` | `#1A1A1A` | Body text                     |

Typography: **Playfair Display** for headings (`font-serif`), **Inter** for
body text (`font-sans`), both loaded from Google Fonts.

## Local development

### Prerequisites

- PHP 8.2 or newer with the usual Laravel extensions
  (`mbstring`, `xml`, `curl`, `pdo_mysql`, `openssl`, `tokenizer`, `fileinfo`,
  `ctype`, `bcmath`, `zip`)
- Composer 2.x
- Node.js 18+ and npm
- MySQL 8 (locally or in Docker)

### Setup

```bash
# 1. Clone and install dependencies
git clone <repo-url> oil-and-gas-
cd oil-and-gas-
composer install
npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# Edit .env and set at minimum:
#   DB_DATABASE, DB_USERNAME, DB_PASSWORD
#   VITE_WHATSAPP_NUMBER, WHATSAPP_NUMBER

# 3. Create the database, then migrate
php artisan migrate

# 4. Run the dev servers (in two terminals)
php artisan serve
npm run dev
```

Visit `http://localhost:8000` for the public site. The Breeze auth routes
(`/login`, `/register`, `/dashboard`) are scaffolded and will be reused as the
admin area in a later phase.

### Useful scripts

```bash
npm run dev          # Vite dev server
npm run build        # Production asset build
php artisan test     # Run the PHPUnit suite
```

## Project phases

1. **Foundation** &mdash; Laravel + Breeze + Tailwind + public layout + home
   page placeholder. (You are here.)
2. Public marketing pages (About, Services, Gallery, Contact)
3. Menu module
4. Bookings & deposits (Paystack / Flutterwave)
5. Blog & recipes
6. Admin dashboard
7. Notifications & email
8. Deployment to Contabo / HestiaCP

## License

Proprietary &mdash; &copy; Blue Dine Cuisines.
