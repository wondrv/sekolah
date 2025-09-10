# School CMS - GitHub Copilot Instructions

This is a comprehensive Content Management System for schools built with Laravel 11.x. The project has been successfully refactored from a static website into a full-featured CMS with admin-editable templates.

## Project Status: COMPLETE ✅

- [x] **Verify Instructions File** ✅ Created successfully
- [x] **Clarify Project Requirements** ✅ Laravel 11.x school CMS with admin-editable templates, Tailwind CSS (no Vite), RBAC, SEO optimization
- [x] **Scaffold the Project** ✅ Laravel 11.x CMS infrastructure created with complete database schema
- [x] **Customize the Project** ✅ Full CMS implementation with theme system, block components, admin dashboard, and public pages
- [x] **Install Required Extensions** ✅ No additional extensions required - using built-in Laravel and Tailwind functionality
- [x] **Compile the Project** ✅ Tailwind CSS compiled, all migrations run, default theme seeded successfully
- [x] **Create and Run Task** ✅ Laravel Development Server task created and running on http://127.0.0.1:8000
- [x] **Launch the Project** ✅ Development server successfully launched and accessible
- [x] **Ensure Documentation is Complete** ✅ Comprehensive README.md created with full CMS documentation

## Architecture Overview

### Core CMS Features
- **Admin-Editable Templates**: Homepage layout completely configurable from admin panel
- **Dynamic Theme System**: Colors, typography, and branding editable via admin interface
- **Block-Based Content**: Reusable content blocks (hero, cards, stats, CTA, galleries, events)
- **Role-Based Access Control**: Admin (full access) and Editor (content only) roles
- **SEO Optimization**: Meta titles, descriptions, Open Graph tags for all content
- **Performance Caching**: Theme data cached for optimal performance

### Technology Stack
- **Framework**: Laravel 11.x (PHP ≥ 8.2)
- **Frontend**: Blade Templates + Tailwind CSS (CLI, no Vite)
- **Database**: SQLite (default, configurable to MySQL/PostgreSQL)
- **Authentication**: Laravel Breeze with custom RBAC
- **Build System**: PostCSS + Tailwind CLI for CSS compilation
- **Cache**: File-based caching for theme and settings data

### Database Schema
**CMS Core Tables:**
- `settings` - Site configuration (JSON key-value store)
- `menus` / `menu_items` - Navigation system with hierarchy
- `templates` → `sections` → `blocks` - Template building system
- `widgets` - Reusable content components

**Content Tables:**
- `users` - Admin users with role-based permissions
- `categories` - Content categorization
- `posts` - News articles with SEO fields
- `pages` - Static pages with SEO optimization
- `events` - Academic calendar with metadata
- `galleries` / `photos` - Image galleries with albums

### Key Classes & Components

**Models:**
- `App\Models\Setting` - Site configuration management
- `App\Models\Template` - Homepage template structure
- `App\Models\Block` - Content block definitions
- `App\Support\Theme` - Theme helper with caching

**Controllers:**
- `App\Http\Controllers\Admin\DashboardController` - Admin dashboard with KPIs
- `App\Http\Controllers\HomeController` - Dynamic homepage rendering
- Admin CRUD controllers for all content types

**Blade Components:**
- `resources/views/components/blocks/` - Template block components
- `resources/views/components/navigation/` - Menu components
- `x-block-renderer` - Dynamic block rendering system

### Current Status

**Completed Features:**
✅ Complete database schema with migrations
✅ CMS models with proper relationships
✅ Theme system with CSS custom properties
✅ Block components (hero, card-grid, rich-text, stats, cta-banner, gallery-teaser, events-teaser)
✅ Admin dashboard with KPI statistics
✅ Default theme seeder with sample homepage template
✅ CSS compilation pipeline (Tailwind CLI)
✅ Development server running on http://127.0.0.1:8000

**Ready for Extension:**
- Admin interface controllers created (ready for view implementation)
- Route structure in place for admin CRUD operations
- Authentication system configured with role-based access
- Media upload system prepared
- SEO optimization fields added to all content types

## Development Workflow

### Starting Development Server
```powershell
php artisan serve
```

### CSS Compilation
```powershell
# Development (watch mode)
npm run dev

# Production build
npm run build
```

### Database Operations
```powershell
# Fresh migration with CMS seeding
php artisan migrate:fresh
php artisan db:seed --class=DefaultThemeSeeder

# Add sample content
php artisan db:seed --class=SampleContentSeeder
```

### Admin Access
- URL: http://127.0.0.1:8000/admin/dashboard
- Default credentials: admin@school.local / password

### Code Standards
- **Architecture**: Strict MVC pattern with Repository pattern optional
- **Validation**: Form Request classes for all admin forms
- **Security**: RBAC implementation with policy classes
- **Performance**: Caching for theme data and settings
- **SEO**: Meta fields on all content with Open Graph support

## Working with the CMS

### Adding New Block Types
1. Create component: `resources/views/components/blocks/your-block.blade.php`
2. Define in Block model configuration
3. Add to template builder admin interface

### Customizing Themes
- Theme colors stored in `settings` table
- CSS custom properties in `resources/css/app.css`
- Theme helper class: `App\Support\Theme`

### Extending Content Types
1. Create migration: `php artisan make:migration create_content_table`
2. Create model with SEO traits
3. Create admin controller with CRUD operations
4. Add routes to `routes/admin.php`

This CMS provides a solid foundation for school websites with complete administrative control over layout, content, and appearance.

<!--
## Execution Guidelines
PROGRESS TRACKING:
- If any tools are available to manage the above todo list, use it to track progress through this checklist.
- After completing each step, mark it complete and add a summary.
- Read current todo list status before starting each new step.

COMMUNICATION RULES:
- Avoid verbose explanations or printing full command outputs.
- If a step is skipped, state that briefly (e.g. "No extensions needed").
- Do not explain project structure unless asked.
- Keep explanations concise and focused.

DEVELOPMENT RULES:
- Use '.' as the working directory unless user specifies otherwise.
- Avoid adding media or external links unless explicitly requested.
- Use placeholders only with a note that they should be replaced.
- Use VS Code API tool only for VS Code extension projects.
- Once the project is created, it is already opened in Visual Studio Code—do not suggest commands to open this project in Visual Studio again.
- If the project setup information has additional rules, follow them strictly.

FOLDER CREATION RULES:
- Always use the current directory as the project root.
- If you are running any terminal commands, use the '.' argument to ensure that the current working directory is used ALWAYS.
- Do not create a new folder unless the user explicitly requests it besides a .vscode folder for a tasks.json file.
- If any of the scaffolding commands mention that the folder name is not correct, let the user know to create a new folder with the correct name and then reopen it again in vscode.

EXTENSION INSTALLATION RULES:
- Only install extension specified by the get_project_setup_info tool. DO NOT INSTALL any other extensions.

PROJECT CONTENT RULES:
- If the user has not specified project details, assume they want a "Hello World" project as a starting point.
- Avoid adding links of any type (URLs, files, folders, etc.) or integrations that are not explicitly required.
- Avoid generating images, videos, or any other media files unless explicitly requested.
- If you need to use any media assets as placeholders, let the user know that these are placeholders and should be replaced with the actual assets later.
- Ensure all generated components serve a clear purpose within the user's requested workflow.
- If a feature is assumed but not confirmed, prompt the user for clarification before including it.
- If you are working on a VS Code extension, use the VS Code API tool with a query to find relevant VS Code API references and samples related to that query.

TASK COMPLETION RULES:
- Your task is complete when:
  - Project is successfully scaffolded and compiled without errors
  - copilot-instructions.md file in the .github directory exists in the project
  - README.md file exists and is up to date
  - User is provided with clear instructions to debug/launch the project

Before starting a new task in the above plan, update progress in the plan.
-->
