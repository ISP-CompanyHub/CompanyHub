# Company Hub

A modern company management platform built with Laravel 12 and Blade templates.

---

## Introduction

**Company Hub** is a comprehensive platform for managing your company operations, built on Laravel 12 with a clean Blade + AlpineJS stack. This application provides a solid foundation for company management with authentication, user profiles, and an extensible dashboard.

### Key Features

- 🎨 **Clean UI**: CoreUI/AdminLTE-inspired design with Tailwind CSS
- ⚡ **Modern Stack**: Laravel 12, AlpineJS, Vite
- 🔒 **Secure Authentication**: Complete auth system with email verification
- 📱 **Responsive Design**: Mobile-first approach
- 🧪 **Tested**: Pest testing framework included
- 🚀 **Fast Setup**: Works seamlessly with Laravel Herd and SQLite

---

## Requirements

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18+ and npm
- **Laravel Herd**: Recommended for local development
- **SQLite**: Default database (or MySQL/PostgreSQL)

---

## Quick Start with Laravel Herd

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/isp-company-hub.git
cd isp-company-hub
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure SQLite Database

Edit your `.env` file and set the database configuration:

```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

Create the SQLite database file:

```bash
touch database/database.sqlite
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Build Frontend Assets

```bash
# For development (with hot reload)
npm run dev

# For production
npm run build
```

### 7. Access the Application

If you're using **Laravel Herd**, the application will be automatically available at:

```
http://isp-company-hub.test
```

Otherwise, start the development server:

```bash
php artisan serve
```

Then visit: `http://localhost:8000`

---

## Features

### Authentication System

- ✅ User Registration
- ✅ Login / Logout
- ✅ Password Reset Flow
- ✅ Email Verification
- ✅ Remember Me Functionality

### User Management

- ✅ Profile Information Management
- ✅ Password Updates
- ✅ Appearance Preferences
- ✅ Account Deletion

### Dashboard

- ✅ Authenticated User Dashboard
- ✅ Quick Access to Settings
- ✅ User Activity Overview

---

## Development

### Code Quality Tools

```bash
# Format code with Laravel Pint
./vendor/bin/pint

# Run static analysis with Larastan
./vendor/bin/phpstan analyse

# Run tests with Pest
./vendor/bin/pest

# Watch application logs
php artisan pail
```

### Running Tests

```bash
# Run all tests
./vendor/bin/pest

# Run specific test file
./vendor/bin/pest tests/Feature/DashboardTest.php

# Run tests with coverage
./vendor/bin/pest --coverage
```

### Database Management

```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Refresh database and seed
php artisan migrate:refresh --seed

# Reset database
php artisan migrate:reset
```

---

## Tech Stack

### Backend

- **Laravel 12**: PHP framework
- **SQLite**: Default database
- **Pest**: Testing framework

### Frontend

- **Blade**: Template engine
- **AlpineJS**: JavaScript framework
- **Tailwind CSS v4**: Utility-first CSS
- **Vite**: Build tool

### Tools & Packages

- **Blade Icons**: Icon components
- **Blade FontAwesome**: FontAwesome icons
- **Laravel Pint**: Code formatter
- **Larastan**: Static analysis
- **Laravel Pail**: Log viewer

---

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Auth/              # Authentication controllers
│   └── Settings/          # User settings controllers
├── Models/                # Eloquent models
└── Providers/             # Service providers

resources/
├── views/
│   ├── auth/              # Authentication views
│   ├── settings/          # Settings views
│   └── components/        # Blade components
├── css/                   # Tailwind CSS
└── js/                    # AlpineJS & JavaScript

routes/
├── web.php                # Web routes
└── auth.php               # Authentication routes

tests/
├── Feature/               # Feature tests
└── Unit/                  # Unit tests
```

---

## Configuration

### Environment Variables

Key environment variables to configure:

```env
APP_NAME="Company Hub"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://isp-company-hub.test

DB_CONNECTION=sqlite

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@companyhub.test"
MAIL_FROM_NAME="${APP_NAME}"
```

### Laravel Herd

Company Hub works seamlessly with Laravel Herd. Simply:

1. Park your directory containing this project
2. Access via `http://isp-company-hub.test`
3. Use `herd open` to open in your browser

<div align="center">

### Download Laravel Herd

[![Download Laravel Herd](https://img.shields.io/badge/Download-Laravel%20Herd-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://herd.laravel.com/windows)

**[Get Laravel Herd for Windows →](https://herd.laravel.com/windows)**

</div>

---

## License

Company Hub is open-sourced software licensed under the [MIT license](LICENSE).

---

## Support

For support, please open an issue on GitHub or contact the development team.

---

**Built with ❤️ using Laravel and Blade**
