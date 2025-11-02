# GitHub Copilot Instructions

## Project Overview

This is a **Laravel 12 + Blade Starter Kit** project that provides authentication and user management functionality with a CoreUI/AdminLTE-inspired design. It's a Blade-only starter kit using AlpineJS for interactivity, avoiding the complexity of Vue/Livewire/React frameworks.

## Tech Stack

### Backend

- **PHP**: ^8.2
- **Laravel Framework**: ^12.0
- **Database**: MySQL/PostgreSQL (configurable)
- **Testing**: Pest (PHPUnit-based)

### Frontend

- **Template Engine**: Blade
- **JavaScript**: AlpineJS ^3.14.9
- **CSS Framework**: Tailwind CSS ^4.1.7
- **Build Tool**: Vite ^6.2.4

### Additional Libraries

- **Icons**: Blade Icons, Blade FontAwesome
- **HTTP Client**: Axios
- **Development Tools**: Laravel Pail, Larastan, Laravel Pint

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           # Authentication controllers
│   │   ├── Settings/       # User settings controllers
│   │   └── Controller.php
│   └── Models/
│       └── User.php
config/                     # Configuration files
database/
├── factories/              # Model factories
├── migrations/             # Database migrations
└── seeders/               # Database seeders
resources/
├── css/
│   └── app.css            # Tailwind CSS
├── js/
│   ├── app.js
│   └── bootstrap.js       # Alpine.js setup
└── views/
    ├── auth/              # Authentication views
    ├── settings/          # Settings views
    ├── components/        # Blade components
    ├── dashboard.blade.php
    └── welcome.blade.php
routes/
├── web.php                # Web routes
├── auth.php               # Authentication routes
└── console.php            # Console routes
tests/
├── Feature/               # Feature tests
├── Unit/                  # Unit tests
└── Pest.php              # Pest configuration
```

## Coding Standards & Conventions

### PHP/Laravel

1. **Code Style**: Follow Laravel conventions and use Laravel Pint for formatting

    - Run `./vendor/bin/pint` before committing

2. **Type Hints**: Use strict types and PHPDoc annotations

    ```php
    /**
     * @var list<string>
     */
    protected $fillable = [];
    ```

3. **Namespaces**: Follow PSR-4 autoloading

    - Controllers: `App\Http\Controllers`
    - Models: `App\Models`
    - Tests: `Tests\Feature` or `Tests\Unit`

4. **Route Naming**: Use named routes consistently

    ```php
    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])
        ->name('settings.profile.edit');
    ```

5. **Static Analysis**: Run Larastan for type checking
    - Configuration: `phpstan.neon`
    - Run: `./vendor/bin/phpstan analyse`

### Blade Templates

1. **Components**: Use Blade components for reusable UI elements

    - Store in `resources/views/components/`

2. **AlpineJS Integration**: Use `x-data`, `x-show`, `x-on` directives

    ```blade
    <div x-data="{ open: false }">
        <button @click="open = !open">Toggle</button>
    </div>
    ```

3. **Icons**: Use Blade Icon components
    ```blade
    <x-icon-brand-github />
    <x-fas-user />
    ```

### JavaScript

1. **AlpineJS**: Primary framework for interactivity

    - Keep logic in Blade templates when possible
    - Extract complex components to separate files in `resources/js/`

2. **Axios**: For AJAX requests
    - Pre-configured in `bootstrap.js`

### CSS/Tailwind

1. **Tailwind CSS v4**: Use utility-first approach

    - Custom styles in `resources/css/app.css`
    - Use `@apply` sparingly

2. **Prettier**: Format Blade templates
    - Plugin: `@shufo/prettier-plugin-blade`
    - Run: `npm run format` (if configured)

## Development Workflow

### Setup Commands

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Build assets
npm run build

# Start development server
php artisan serve
npm run dev
```

### Testing

1. **Framework**: Pest (PHPUnit-based)
2. **Configuration**: `tests/Pest.php`, `phpunit.xml`
3. **Database**: Use `RefreshDatabase` trait for feature tests

```php
// Feature Test Example
test('users can access dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});
```

4. **Run Tests**:
    ```bash
    ./vendor/bin/pest
    ./vendor/bin/pest --filter DashboardTest
    ```

### Code Quality Tools

```bash
# Format code
./vendor/bin/pint

# Static analysis
./vendor/bin/phpstan analyse

# Run tests
./vendor/bin/pest

# Watch logs
php artisan pail
```

## Key Features

### Authentication System

- Login / Registration
- Password Reset Flow
- Email Confirmation Flow
- Located in: `routes/auth.php`, `app/Http/Controllers/Auth/`

### User Settings

- Profile Information Management
- Password Update
- Appearance Preferences (theme, etc.)
- Located in: `routes/web.php`, `app/Http/Controllers/Settings/`

### Dashboard

- Main authenticated landing page
- View: `resources/views/dashboard.blade.php`

## Design Patterns

1. **Controllers**: Single action or resource controllers
2. **Middleware**: Use route middleware groups (`auth`, `verified`)
3. **Form Requests**: Create dedicated request classes for validation
4. **Models**: Keep business logic in models, use Eloquent features
5. **Services**: Extract complex logic to service classes

## When Writing Code

### Controllers

- Keep controllers thin
- Use form requests for validation
- Return views or redirect responses
- Example structure:

    ```php
    public function edit()
    {
        return view('settings.profile.edit');
    }

    public function update(Request $request)
    {
        // Validation and logic
        return redirect()->route('settings.profile.edit');
    }
    ```

### Views

- Use Blade components for reusability
- Keep AlpineJS logic simple and inline
- Follow the existing layout structure
- Use named routes: `route('dashboard')`

### Models

- Define relationships
- Use proper casts
- Define fillable/guarded properties
- Add accessor/mutator methods when needed

### Migrations

- Use descriptive names
- Include both `up()` and `down()` methods
- Use foreign key constraints appropriately

### Tests

- Write feature tests for user flows
- Write unit tests for complex logic
- Use factories for test data
- Follow Pest's expressive syntax

## Environment

- **Development**: Use `php artisan serve` and `npm run dev`
- **Production**: Build assets with `npm run build`
- **Database**: Configure in `.env` file
- **Mail**: Configure mail driver in `.env`

## Important Notes

1. This is a **Blade-only** starter kit - avoid suggesting Livewire, Inertia, or Vue
2. Use **AlpineJS** for client-side interactivity
3. Follow **Laravel 12** conventions and features
4. Maintain the **CoreUI/AdminLTE-inspired** design aesthetic
5. Write **Pest tests** for all new features
6. Run **Pint** and **Larastan** before committing

## Documentation References

- Laravel 12: https://laravel.com/docs/12.x
- AlpineJS: https://alpinejs.dev
- Tailwind CSS: https://tailwindcss.com
- Pest: https://pestphp.com
- Blade Icons: https://blade-ui-kit.com/blade-icons
