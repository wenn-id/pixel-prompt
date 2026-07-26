# PixelPrompt — Design Spec

## Overview
PixelPrompt is an open-source SaaS platform for AI image generation prompt management.  
Users bring their own API keys (BYOK) to generate images through multiple providers,  
with all results stored in a personal gallery with full prompt metadata.

## Problem
AI image enthusiasts generate hundreds of images but have no structured way to:
- Track which prompt produced which result
- Organize prompts by tag, collection, or provider
- Reuse and iterate on successful prompts
- Share individual results with a public link

## Architecture
- **Stack:** Laravel 12 + PostgreSQL 18 + Blade + Livewire 3 + Alpine.js + Tailwind CSS 4
- **Queue:** Database-driven queue for image generation jobs
- **Storage:** S3-compatible (Cloudflare R2) for image files
- **Auth:** Email + password, optional OAuth later

## Data Model

### users (Laravel default + extensions)
- Extended with: avatar, bio, is_public (toggle public profile)

### api_keys
- id, user_id, provider (openai|replicate|stability|9router), label, key_encrypted, is_active, created_at, updated_at
- Encrypted at rest using Laravel's encryption

### prompts
- id, user_id, title, prompt_text, negative_prompt, width, height, cfg_scale, steps, seed, style_preset
- provider, model, is_template (for shareable prompt templates)
- soft deletes

### images
- id, user_id, prompt_id, collection_id (nullable)
- file_path (R2 path), thumbnail_path (R2 path)
- width, height, file_size
- provider, model, parameters (JSON of all generation params)
- generation_time_ms, is_public, is_favorite
- soft deletes

### collections
- id, user_id, name, slug, description, cover_image_id (nullable), is_public, sort_order

### tags
- id, name, slug

### prompt_tag / image_tag (pivot)
- prompt_id/image_id, tag_id

## Features

### Phase 1 — MVP (this build)
1. **Auth** — Register/login, profile settings
2. **API Key Manager** — Add/edit/delete provider keys, per-provider model selection
3. **Prompt Composer** — Full prompt editor with negative prompt, parameter sliders, provider/model selector
4. **Generate Engine** — Queue-based generation with real-time notification
5. **Gallery** — Masonry grid with infinite scroll, search, filter by provider/model/tag
6. **Image Detail** — Full prompt metadata, generation params, download, share link
7. **Collections** — Create named albums, add/remove images
8. **Tags** — Add tags to prompts and images
9. **Public Profile** — Toggle public profile, public gallery view
10. **Share Links** — Per-image public share URL

### Phase 2 — Post-MVP
- Batch generate (multiple variants)
- Prompt template marketplace
- Embed API
- OAuth providers
- Team/workspace

## Design System
- **Color:** Dark-first theme. Background: slate-950/900, Surface: slate-900/800, Accent: indigo-500/400
- **Typography:** Inter font (sans), tabular figures for parameters
- **Animations:** Fade-in on scroll, subtle hover scale on gallery cards, smooth transitions
- **Responsive:** Mobile-first, single column → 2 col → 3 col → 4 col masonry

## Routes

### Web (authenticated)
| Method | URI | Component | Description |
|--------|-----|-----------|-------------|
| GET | /dashboard | dashboard | Stats, recent images |
| GET | /gallery | gallery.index | Masonry gallery |
| GET | /gallery/{image} | gallery.show | Image detail |
| GET|POST | /prompts/create | prompt.create | New prompt + generate |
| GET|POST | /prompts/{prompt}/edit | prompt.edit | Edit prompt |
| GET | /prompts | prompt.index | Saved prompts |
| GET|POST | /collections | collection.index | Manage collections |
| GET | /collections/{collection} | collection.show | Collection detail |
| GET|POST | /settings/keys | settings.keys | API key manager |
| GET|POST | /settings/profile | settings.profile | Profile settings |
| GET | /@{user} | profile.show | Public profile |

### API (internal, Livewire)
- /generate — Queue generation job (Livewire action)
- /images/{image}/favorite — Toggle favorite
- /images/{image}/public — Toggle public
