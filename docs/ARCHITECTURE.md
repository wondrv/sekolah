# School CMS Architecture

## System Overview

This is a comprehensive Laravel 11.x-based Content Management System designed specifically for schools, featuring an admin-editable template system and modern UI components.

## Technology Stack

- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Blade Templates + Tailwind CSS + Alpine.js
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **Authentication**: Laravel Breeze with RBAC
- **Build Tools**: PostCSS + Tailwind CLI (no Vite)

## Directory Structure

```
app/
├── Console/Commands/       # Artisan commands
├── Events/                 # Event classes
├── Http/
│   ├── Controllers/
│   │   ├── Admin/         # Admin panel controllers
│   │   │   └── Template/  # Template system controllers
│   │   └── Auth/          # Authentication controllers
│   ├── Middleware/        # HTTP middleware
│   └── Requests/          # Form request validation
├── Listeners/             # Event listeners
├── Models/                # Eloquent models
├── Policies/              # Authorization policies
├── Providers/             # Service providers
├── Services/              # Business logic services
└── Support/               # Helper classes

resources/
├── css/                   # Tailwind CSS source
├── js/                    # JavaScript/Alpine.js
└── views/
    ├── admin/            # Admin panel views
    │   ├── templates/    # Template management UI
    │   └── ...           # Other admin views
    ├── components/       # Blade components
    ├── layouts/          # Layout templates
    └── themes/           # Theme templates

database/
├── migrations/           # Database migrations
└── seeders/             # Database seeders

public/
├── assets/              # Compiled CSS/JS
└── template-assets/     # User-uploaded templates
```

## Core Features

### 1. Template System
- Visual drag-and-drop builder
- Template gallery with predefined templates
- Import/export functionality
- Live preview
- Multi-language support

### 2. Content Management
- Pages (static content)
- Posts (news/blog articles)
- Events (academic calendar)
- Galleries (photo albums)
- Programs (academic programs)
- Facilities

### 3. User Management
- Role-based access control (Admin/Editor)
- User authentication
- Profile management

### 4. Theme Customization
- Color schemes
- Typography settings
- Logo/branding
- Navigation menus

### 5. SEO Optimization
- Meta titles/descriptions
- Open Graph tags
- Sitemap generation
- Structured data

## Database Schema

### Core CMS Tables
- `settings` - Site configuration (JSON key-value)
- `menus` / `menu_items` - Navigation system
- `templates` → `sections` → `blocks` - Template structure
- `user_templates` - User-created templates
- `template_categories` - Template organization
- `widgets` - Reusable components

### Content Tables
- `users` - Admin users with roles
- `categories` - Content categorization
- `posts` - News articles
- `pages` - Static pages
- `events` - Academic calendar
- `galleries` / `photos` - Image galleries
- `programs` - Academic programs
- `facilities` - School facilities

## Key Design Patterns

### Service Layer
Business logic is encapsulated in service classes under `app/Services/`:
- Keeps controllers thin
- Promotes code reuse
- Easier testing

### Repository Pattern (Optional)
Models serve as repositories with scopes and query methods.

### Policy-Based Authorization
Authorization logic is handled by policy classes in `app/Policies/`.

### Event-Driven Architecture
Important actions trigger events that can be logged or processed asynchronously.

## Performance Considerations

### Caching Strategy
- Theme data cached in `cache` store
- Settings cached with `remember()` helper
- Route caching for production
- View compilation caching

### Asset Optimization
- CSS compiled with Tailwind CLI (production mode purges unused classes)
- Images optimized and served from `public/storage`
- Lazy loading for galleries

## Security Best Practices

### Authentication & Authorization
- Laravel Breeze for authentication
- Role-based access control (RBAC)
- Policy classes for fine-grained permissions

### Input Validation
- Form Request classes for all user input
- CSRF protection on all forms
- XSS prevention via Blade escaping

### File Upload Security
- File type validation
- Secure storage paths
- Sanitized filenames

## Development Workflow

### Local Development
```bash
# Start development server
php artisan serve

# Watch and compile CSS
npm run dev

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed
```

### Production Deployment
```bash
# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build production assets
npm run build

# Set proper permissions
chmod -R 755 storage bootstrap/cache
```

## Testing Strategy

### Unit Tests
Test individual service classes and models.

### Feature Tests
Test complete user workflows and HTTP requests.

### Browser Tests (Dusk)
Test JavaScript-heavy features like template builder.

## Future Enhancements

- Multi-language content support
- Advanced analytics dashboard
- Email newsletter system
- Student portal integration
- Online payment gateway
- Mobile app API

## Contributing

When contributing to this project:
1. Follow PSR-12 coding standards
2. Write comprehensive tests
3. Update documentation
4. Use meaningful commit messages
5. Submit pull requests for review
