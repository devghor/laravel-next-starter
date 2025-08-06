# Laravel & Next.js Starter Kit

**Laravel & Next.js Starter Kit** is a modern, full-stack boilerplate designed to speed up development by combining the power of **Laravel** for the backend API with the flexibility and performance of **Next.js** for the frontend.

It provides a clean, scalable foundation for building production-ready web applications with authentication, authorization, API integration, and a robust architecture out of the box.

> ⚠️ **Note:** This project is in **active development**. Features are incomplete and may change as the project evolves.

---

## 🚀 Getting Started

### Installation

1. **Clone the repository**
    ```bash
    git clone https://github.com/devghor/laravel-next-starter.git
    cd laravel-next-starter
    ```

## Setup Backend (Laravel)

### Install dependencies

```bash
composer install
```

### Copy .env file and configure environment variables

```bash
cp .env.example .env
```

### Generate application key

```bash
php artisan key:generate
```

### Run migrations and seeders

```bash
php artisan migrate --seed
```

## Setup Frontend (Next.js)

### Navigate to the frontend directory

```bash
cd ../frontend
```

### Install dependencies

```bash
pnpm install
```

### Copy .env.local.example to .env.local and configure

```bash
cp .env.local.example .env.local
```

### Start the development server

```bash
npm run dev
```

## Access the App

-   **Laravel API:** http://localhost:8000
-   **Next.js App:** http://localhost:3000

## ✨ Features (Planned & In Progress)

-   🔐 Authentication & Authorization (Laravel Sanctum + CASL)
-   🗂️ API-First Architecture
-   📦 Modular & Scalable Codebase
-   📊 Server-side & Client-side Rendering with Next.js
-   ⚡ Optimized Developer Experience
