# PixelPrompt

**AI Image Prompt Manager — Generate, organize, and share.**

PixelPrompt is an open-source SaaS platform for managing AI image generation prompts. Bring your own API key (BYOK) to generate images through multiple providers, then organize everything in a beautiful gallery with tags, collections, and sharing.

## Features

- **Multi-Provider Generation** — OpenAI DALL-E, Replicate (Flux, SD3), Stability AI, 9Router
- **BYOK (Bring Your Own Key)** — Your API keys, encrypted at rest. No usage costs for the platform.
- **Prompt Composer** — Full parameter control: model, size, CFG scale, steps, seed, style preset
- **Gallery** — Masonry grid with search, filter by provider/tag, sort, favorites
- **Collections** — Group images into albums with cover images
- **Public Sharing** — Per-image public links & public profile page
- **Queue-Based Generation** — Laravel queue handles generation asynchronously

## Tech Stack

| Layer | Tech |
|-------|------|
| Backend | PHP 8.5+, Laravel 12 |
| Database | PostgreSQL 18 |
| Frontend | Livewire 3 + Alpine.js + Tailwind CSS 3 |
| Auth | Laravel Breeze (dark mode) |
| Queue | Database driver (upgrade to Redis for production) |
| Storage | Local / S3 / R2 (configurable) |

## Quick Start

```bash
git clone https://github.com/alwan-juliawan/pixel-prompt
cd pixel-prompt

cp .env.example .env
# Edit .env: database credentials, APP_URL

composer install
npm install && npm run build

php artisan key:generate
php artisan migrate
php artisan storage:link

php artisan serve
```

Visit `http://localhost:8000` and register your account.

## Environment Variables

```env
# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_DATABASE=pixel_prompt
DB_USERNAME=
DB_PASSWORD=

# Image Storage (R2/S3)
R2_ACCESS_KEY_ID=
R2_SECRET_ACCESS_KEY=
R2_BUCKET=
R2_ENDPOINT=
R2_URL=
```

## License

MIT
