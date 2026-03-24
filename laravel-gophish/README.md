# Laravel Gophish

A Laravel + Vue (Inertia) port of the Gophish phishing framework.

## Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- PostgreSQL (recommended) or MySQL/SQLite
- Redis (for queues)

## Installation

1. Clone the repository.
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Update `.env` with your database credentials.

4. Run migrations:
   ```bash
   php artisan migrate
   ```

5. Build assets:
   ```bash
   npm run build
   ```

## Running the Application

1. Start the server:
   ```bash
   php artisan serve
   ```

2. Start the queue worker (for sending emails):
   ```bash
   php artisan queue:work
   ```

## Phishing Server Separation

The phishing server logic is contained in `App\Http\Controllers\PhishingController`.
Routes are defined in `routes/phishing.php`.
To separate the phishing server:
1. Create a new deployment of this app.
2. Use `routes/phishing.php` instead of `routes/web.php` for the public-facing server.
3. Ensure both apps share the same database.

## Multi-Tenancy

Users belong to a `Client`. All resources (Campaigns, Templates, etc.) are scoped to the user's Client automatically via the `BelongsToClient` trait.
