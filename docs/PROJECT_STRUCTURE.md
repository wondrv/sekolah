# Project Structure Summary

This document provides a quick reference to the School CMS project structure for new contributors.

## 📁 Top-Level Directories

```
sekolah/
├── app/                    # Application core (models, controllers, services)
├── bootstrap/              # Laravel bootstrap files
├── config/                 # Configuration files
├── database/               # Migrations, seeders, factories
├── docs/                   # Additional documentation
├── public/                 # Public web root (assets, index.php)
├── resources/              # Views, CSS, JS source files
├── routes/                 # Route definitions
├── storage/                # Logs, cache, uploaded files
├── tests/                  # Automated tests
└── vendor/                 # Composer dependencies
```

## 🎯 Key Application Directories

### `app/Http/Controllers/`
```
Controllers/
├── Admin/                  # Admin panel controllers
│   ├── Template/          # Template system controllers
│   │   ├── TemplateBuilderController.php
│   │   ├── TemplateGalleryController.php
│   │   ├── MyTemplatesController.php
│   │   ├── SmartImportController.php
│   │   └── ...
│   ├── DashboardController.php
│   ├── PostController.php
│   └── ...
├── Auth/                   # Authentication controllers
├── HomeController.php      # Public homepage
└── PureCMSController.php   # Dynamic page routing
```

### `app/Models/`
```
Models/
├── UserTemplate.php        # User-created templates (main template model)
├── TemplateCategory.php    # Template categorization
├── TemplateRevision.php    # Template version history
├── Template.php            # Base template structure
├── Section.php             # Template sections
├── Block.php               # Content blocks
├── Page.php                # Static pages
├── Post.php                # News/blog posts
├── Event.php               # Academic calendar
├── Gallery.php             # Photo galleries
└── ...
```

### `app/Services/`
Business logic services (see `app/Services/README.md` for details):
```
Services/
├── SmartTemplateImporterService.php    # AI-enhanced import
├── ExternalTemplateService.php         # External template discovery
├── AutoTranslationService.php          # Content translation
├── PageBuilderService.php              # Page content rendering
├── ThemeService.php                    # Theme management
└── ...
```

### `app/Support/`
Helper classes:
```
Support/
└── Theme.php               # Theme helper with caching
```

## 🌐 Frontend Structure

### `resources/views/`
```
views/
├── admin/                  # Admin panel views
│   ├── dashboard.blade.php
│   ├── templates/         # Template management UI
│   │   ├── builder/       # Visual builder
│   │   ├── gallery/       # Template gallery
│   │   ├── my-templates/  # User templates
│   │   └── smart-import/  # Smart import UI
│   ├── posts/             # Posts management
│   ├── pages/             # Pages management
│   └── ...
├── components/             # Reusable Blade components
│   ├── blocks/            # Template block components
│   │   ├── hero.blade.php
│   │   ├── card-grid.blade.php
│   │   ├── stats.blade.php
│   │   └── ...
│   └── navigation/        # Menu components
├── layouts/
│   ├── admin.blade.php    # Admin layout
│   └── guest.blade.php    # Public layout
└── themes/
    └── default/           # Default theme templates
```

### `resources/css/` & `public/assets/`
```
resources/css/
└── app.css                # Tailwind CSS source

public/assets/
├── css/
│   └── app.css           # Compiled CSS
└── js/                   # JavaScript files
```

## 🗄️ Database Structure

### Migrations Location
`database/migrations/`

### Key Tables
| Table | Purpose |
|-------|---------|
| `user_templates` | User-created templates with JSON data |
| `template_categories` | Template organization |
| `template_revisions` | Version history |
| `settings` | Site configuration (JSON) |
| `menus` / `menu_items` | Navigation system |
| `posts` | News/blog articles |
| `pages` | Static pages |
| `events` | Academic calendar |
| `galleries` / `photos` | Image galleries |

### Seeders
```
database/seeders/
├── DefaultThemeSeeder.php      # CMS initialization
├── SampleContentSeeder.php     # Sample content
└── StarterTemplatesSeeder.php  # Gallery templates
```

## 🛣️ Routes Structure

### `routes/web.php`
Public-facing routes (homepage, posts, pages, etc.)

### `routes/admin.php`
Admin panel routes with authentication middleware:
```php
/admin/dashboard
/admin/templates/*
/admin/posts/*
/admin/pages/*
/admin/events/*
// ... etc
```

### `routes/auth.php`
Authentication routes (login, logout, register, etc.)

## 📦 Configuration Files

### Important Config Files
| File | Purpose |
|------|---------|
| `.env` | Environment configuration (NOT in git) |
| `.env.example` | Example environment file (in git) |
| `config/app.php` | Application settings |
| `config/database.php` | Database connections |
| `composer.json` | PHP dependencies |
| `package.json` | Node.js dependencies |
| `tailwind.config.js` | Tailwind CSS configuration |

## 📚 Documentation Files

| File | Purpose |
|------|---------|
| `README.md` | Main project documentation |
| `ARCHITECTURE.md` | Technical architecture details |
| `CONTRIBUTING.md` | Contribution guidelines |
| `SECURITY.md` | Security policies |
| `CHANGELOG.md` | Version history |
| `LICENSE` | MIT License |

## 🔧 Build & Development

### Commands
```bash
# Install dependencies
composer install
npm install

# Database
php artisan migrate
php artisan db:seed --class=DefaultThemeSeeder

# Assets
npm run dev      # Watch mode
npm run build    # Production build

# Server
php artisan serve
```

### Task Files
VS Code tasks in `.vscode/tasks.json`:
- Laravel Development Server
- Laravel Clear Caches
- Laravel Optimize

## 🎨 Template System Architecture

### Template Data Flow
```
UserTemplate (database)
    ↓
template_data (JSON)
    ↓
Templates → Sections → Blocks
    ↓
Blade Components (resources/views/components/blocks/)
    ↓
Rendered HTML
```

### Block Types
Each block type has a corresponding Blade component:
- `hero` → `components/blocks/hero.blade.php`
- `card-grid` → `components/blocks/card-grid.blade.php`
- `stats` → `components/blocks/stats.blade.php`
- etc.

## 🔐 Security Notes

### Files NEVER to Commit
- `.env` (contains secrets)
- `/vendor/` (composer packages)
- `/node_modules/` (npm packages)
- `database/*.sqlite` (local database)
- `/storage/logs/*.log` (log files)

### Protected by .gitignore
All sensitive files are already in `.gitignore`. Always check before committing!

## 🚀 Deployment Checklist

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Run `composer install --no-dev --optimize-autoloader`
4. Run `npm run build`
5. Run `php artisan config:cache`
6. Run `php artisan route:cache`
7. Run `php artisan view:cache`
8. Set proper file permissions (755/644)
9. Configure HTTPS/SSL
10. Set up backups

## 📞 Getting Help

- Check `README.md` for usage instructions
- Read `ARCHITECTURE.md` for technical details
- See `CONTRIBUTING.md` for contribution guidelines
- Review inline code comments
- Ask in GitHub Discussions

---

**Last Updated**: 2025-10-29  
**Version**: 1.0.0
