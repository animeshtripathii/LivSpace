# 🚀 Deployment Guide: Yadav Interior

This guide provides end-to-end instructions for deploying the **Yadav Interior** Laravel application to **Render** (Backend Web Service / Docker) and **Vercel** (Serverless Frontend / Blade App).

---

## 📋 Table of Contents
1. [Prerequisites & Git Setup](#1-prerequisites--git-setup)
2. [Deploying Backend to Render](#2-deploying-backend-to-render)
   - [Option A: 1-Click Blueprint (Recommended)](#option-a-render-blueprint-1-click)
   - [Option B: Manual Web Service Setup](#option-b-manual-docker-web-service)
3. [Deploying to Vercel](#3-deploying-to-vercel)
   - [Option A: Vercel Dashboard](#option-a-vercel-dashboard-import)
   - [Option B: Vercel CLI](#option-b-vercel-cli)
4. [Database Setup (PostgreSQL / MySQL)](#4-database-setup)
5. [Environment Variables Reference](#5-environment-variables-reference)
6. [Troubleshooting & FAQ](#6-troubleshooting--faq)

---

## 1. Prerequisites & Git Setup

Before deploying, ensure your code is committed and pushed to a GitHub/GitLab repository:

```bash
# Initialize and push to GitHub (if not already done)
git init
git add .
git commit -m "feat: configure Render and Vercel deployment setup"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
git push -u origin main
```

---

## 2. Deploying Backend to Render

### Option A: Render Blueprint (1-Click)
This repository includes a [`render.yaml`](render.yaml) file that automatically configures the Web Service and creates a free PostgreSQL database.

1. Log in to [Render Dashboard](https://dashboard.render.com/).
2. Click **New +** → **Blueprint**.
3. Connect your GitHub repository.
4. Render will detect `render.yaml` and show:
   - Web Service: `interior-design-app` (Docker runtime)
   - Database: `interior-db` (PostgreSQL)
5. Click **Apply**. Render will automatically:
   - Build the multi-stage Docker container (Composer + Node Vite + PHP 8.3).
   - Provision the PostgreSQL database.
   - Run database migrations on startup (`php artisan migrate --force`).
   - Start the web service.

---

### Option B: Manual Docker Web Service

If you prefer to configure the service manually on Render:

1. Click **New +** → **Web Service**.
2. Select **Build and deploy from a Git repository** and pick your repo.
3. Configure settings:
   - **Name**: `yadav-interior-backend`
   - **Language / Runtime**: `Docker`
   - **Dockerfile Path**: `./Dockerfile`
   - **Region**: Choose closest to your users (e.g. `Singapore`, `Frankfurt`, `Oregon`)
   - **Instance Type**: `Free` or `Starter`
   - **Health Check Path**: `/up`
4. Add the following **Environment Variables** in the Environment tab:

| Variable | Value | Description |
| :--- | :--- | :--- |
| `APP_ENV` | `production` | Production mode |
| `APP_DEBUG` | `false` | Disable debug traces in production |
| `APP_KEY` | `base64:...` | Generate using `php artisan key:generate --show` |
| `APP_URL` | `https://your-service.onrender.com` | Your Render URL |
| `LOG_CHANNEL` | `stderr` | Stream logs to Render web dashboard |
| `DB_CONNECTION` | `pgsql` *(or `mysql`)* | Database driver |
| `DATABASE_URL` | *`postgres://...`* | Connection string from Render DB |
| `SESSION_DRIVER` | `database` | Persistent database session |
| `CACHE_STORE` | `database` | Cache store |
| `QUEUE_CONNECTION` | `database` | Background queue worker driver |
| `AUTO_MIGRATE` | `true` | Automatically runs migrations on start |

5. Click **Create Web Service**.

---

## 3. Deploying to Vercel

The project is pre-configured with [`vercel.json`](vercel.json) and [`api/index.php`](api/index.php) to run seamlessly on Vercel Serverless with static Vite asset routing.

### Option A: Vercel Dashboard Import

1. Log in to [Vercel Dashboard](https://vercel.com/dashboard).
2. Click **Add New...** → **Project**.
3. Import your GitHub repository.
4. Set the Build and Output settings:
   - **Framework Preset**: `Other`
   - **Build Command**: `npm run build`
   - **Output Directory**: `public`
5. Under **Environment Variables**, add:

| Key | Value |
| :--- | :--- |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | `base64:your_app_key_here` |
| `APP_URL` | `https://your-project.vercel.app` |
| `LOG_CHANNEL` | `stderr` |
| `SESSION_DRIVER` | `cookie` |
| `CACHE_STORE` | `array` |
| `DB_CONNECTION` | `pgsql` *(or `mysql`)* |
| `DATABASE_URL` | `postgres://user:password@host:port/database` |

6. Click **Deploy**.

---

### Option B: Vercel CLI

```bash
# Install Vercel CLI globally
npm i -g vercel

# Deploy to preview
vercel

# Deploy to production
vercel --prod
```

---

## 4. Database Setup

### Option 1: Render PostgreSQL (Recommended with Render)
- When using `render.yaml`, Render automatically creates the Postgres instance and binds `DATABASE_URL`.
- The entrypoint script will automatically run migrations upon startup.

### Option 2: Cloud Database (Neon / Supabase / PlanetScale / Aiven)
- Create a free database on [Neon.tech](https://neon.tech) (Postgres) or [Supabase](https://supabase.com) or [Aiven](https://aiven.io).
- Copy the `DATABASE_URL` connection URI and paste it as an environment variable in Render or Vercel.

---

## 5. First-Time Database Seeding (Admin & Sample Data)

To seed initial categories and sample designers into your deployed database:

### On Render:
1. In the Render Dashboard, go to your Web Service → **Shell**.
2. Run:
   ```bash
   php artisan db:seed --force
   ```
*(Or set `DB_SEED=true` temporarily in Render environment variables).*

### Default Seeded Accounts:
- **Designer 1**: `designer@example.com` / Password: `password`
- **Designer 2**: `lumen@example.com` / Password: `password`

---

## 6. Troubleshooting & FAQ

### Q: Why do I see a 500 Server Error on first load?
- Ensure `APP_KEY` is set in your environment variables. You can generate one with `php artisan key:generate --show`.
- Verify database connectivity: check that `DATABASE_URL` or DB credentials are correct and accessible.

### Q: CSS or JS styles are missing on Vercel?
- Ensure `vercel.json` contains routing for `/build/(.*)` to `/public/build/$1`.
- Verify that `npm run build` ran during deployment to generate the `public/build` manifest and asset files.

### Q: How do uploads work in production?
- The Docker container creates and mounts `storage/app/public` automatically with `php artisan storage:link`.
- For persistent serverless uploads (Vercel), configure AWS S3 / Cloudflare R2 / Supabase Storage in `.env` by setting `FILESYSTEM_DISK=s3`.
