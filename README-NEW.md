# 🏫 School CMS - WordPress-like Page Builder

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=flat&logo=tailwind-css)](https://tailwindcss.com)
[![React](https://img.shields.io/badge/React-61DAFB?style=flat&logo=react)](https://reactjs.org)

A comprehensive Content Management System for schools built with Laravel 11.x, featuring a **WordPress-like page builder** with drag-and-drop functionality, theme system, and modular architecture.

## 🚀 Key Features

### 🎨 WordPress-like Theme System
- **Swappable Themes**: Change entire site appearance via admin panel
- **Theme Configuration**: Customizable colors, typography, and layout settings
- **Fallback System**: Automatic fallback to default theme for missing views
- **Theme Development**: Easy theme creation with helper functions

### 🛠️ Advanced Page Builder
- **Drag & Drop Interface**: React-based builder with intuitive UI
- **Block System**: Reusable content blocks with configurable settings
- **JSON Storage**: Content stored as JSON in database for flexibility
- **Live Preview**: Real-time preview of changes
- **Responsive Design**: All blocks are mobile-responsive

### 📦 Available Blocks

#### Layout & Content Blocks
- **Hero Section**: Full-width hero with background image, title, subtitle, and CTA
- **Rich Text**: WYSIWYG editor for formatted content
- **Statistics**: Animated counters with icons
- **Gallery**: Photo galleries with customizable columns
- **Contact Form**: Customizable contact forms with field builder

#### Dynamic Content Blocks
- **Latest Posts**: News/blog posts with grid/list layouts
- **Event List**: Upcoming events with date/time display
- **PPDB Table**: Student enrollment results table
- **PPDB Brochure**: Downloadable enrollment brochures

### 🏗️ Modular Architecture

#### PPDB (Student Enrollment) Module
```php
// Auto-generated registration numbers
$student = Student::create([
    'name' => 'John Doe',
    'birth_date' => '2010-05-15',
    // registration_number auto-generated: PPDB20250001
]);

// Display approved students in page builder
{{-- PPDB Table Block --}}
@php
    $students = \App\Models\Student::approved()
        ->orderBy('final_score', 'desc')
        ->take($settings['limit'] ?? 50)
        ->get();
@endphp
```

## 🛠️ Installation

### Requirements
- PHP 8.2+
- Node.js 16+
- Composer
- SQLite/MySQL/PostgreSQL

### Quick Setup

```bash
# 1. Clone and install dependencies
git clone <repository-url> sekolah-cms
cd sekolah-cms
composer install
npm install

# 2. Environment setup
cp .env.example .env
php artisan key:generate

# 3. Database setup
php artisan migrate
php artisan db:seed

# 4. Compile assets (includes React page builder)
npm run build

# 5. Start development
php artisan serve
```

**Admin Access:** http://127.0.0.1:8000/admin/dashboard  
**Default Login:** `admin@school.local` / `password`

## 🎨 Theme Development

### Creating a New Theme

1. **Create Theme Structure**
   ```
   resources/views/themes/my-theme/
   ├── theme.json              # Theme configuration
   ├── layouts/
   │   └── app.blade.php       # Main layout
   ├── partials/
   │   ├── navigation.blade.php
   │   ├── footer.blade.php
   │   └── page-header.blade.php
   └── pages/
       └── show.blade.php      # Page template
   ```

2. **Theme Configuration** (`theme.json`)
   ```json
   {
     "title": "My School Theme",
     "description": "Custom theme for our school",
     "version": "1.0.0",
     "author": "Your Name",
     "settings": {
       "colors": {
         "primary": "#3b82f6",
         "secondary": "#64748b",
         "accent": "#f59e0b"
       },
       "typography": {
         "heading_font": "Inter",
         "body_font": "Inter"
       }
     }
   }
   ```

3. **Using Theme Helpers**
   ```php
   // Get active theme
   $activeTheme = theme()->getActiveTheme();
   
   // Get theme setting with fallback
   $primaryColor = theme_setting('colors.primary', '#3b82f6');
   
   // Render theme-specific view
   return theme()->view('pages.home', $data);
   ```

## 🧩 Block Development

### Creating Custom Blocks

1. **Create Block View** (`resources/views/blocks/testimonials.blade.php`)
   ```blade
   @php
       $testimonials = $settings['testimonials'] ?? [];
   @endphp

   <div class="py-12 bg-gray-50" id="{{ $blockId }}">
       <div class="max-w-7xl mx-auto px-4">
           @if(isset($settings['title']))
               <h2 class="text-3xl font-bold text-center mb-12">
                   {{ $settings['title'] }}
               </h2>
           @endif

           <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
               @foreach($testimonials as $testimonial)
                   <div class="bg-white p-6 rounded-lg shadow">
                       <p class="text-gray-600 mb-4">{{ $testimonial['quote'] }}</p>
                       <div class="font-semibold">{{ $testimonial['name'] }}</div>
                       <div class="text-sm text-gray-500">{{ $testimonial['title'] }}</div>
                   </div>
               @endforeach
           </div>
       </div>
   </div>
   ```

2. **Register Block** (in `PageBuilderService`)
   ```php
   protected function registerDefaultBlocks(): void
   {
       $this->registeredBlocks['testimonials'] = [
           'name' => 'Testimonials',
           'category' => 'content',
           'icon' => 'fas fa-quote-left',
           'view' => 'blocks.testimonials',
           'settings' => [
               'title' => ['type' => 'text', 'label' => 'Section Title'],
               'testimonials' => ['type' => 'repeater', 'label' => 'Testimonials']
           ]
       ];
   }
   ```

### Block Setting Types
- **text**: Single line text input
- **textarea**: Multi-line text
- **editor**: WYSIWYG editor
- **select**: Dropdown with options
- **checkbox**: Boolean toggle
- **image**: Image upload/URL
- **url**: URL input
- **number**: Numeric input
- **repeater**: Array of items
- **gallery_select**: Choose from existing galleries
- **category_select**: Choose from post categories

## 📊 Page Builder JSON Structure

The page builder stores content as JSON, making it flexible and portable:

```json
[
  {
    "id": "block_hero_001",
    "type": "hero",
    "settings": {
      "title": "Welcome to Our School",
      "subtitle": "Excellence in Education",
      "description": "Providing quality education since 1990.",
      "image": "/images/hero-bg.jpg",
      "button_text": "Learn More",
      "button_url": "/about",
      "alignment": "center"
    }
  },
  {
    "id": "block_stats_001",
    "type": "stats",
    "settings": {
      "title": "Our Achievements",
      "stats": [
        {
          "number": "1000+",
          "label": "Students",
          "icon": "fas fa-users"
        },
        {
          "number": "50+",
          "label": "Teachers",
          "icon": "fas fa-chalkboard-teacher"
        }
      ]
    }
  }
]
```

## 🎯 Usage Examples

### 1. JSON Content Renderer
```php
// Automatic rendering in Page model
class Page extends Model
{
    public function getRenderedContentAttribute()
    {
        if ($this->use_page_builder && $this->content_json) {
            return app(PageBuilderService::class)->renderPageContent($this);
        }
        return $this->content ?? '';
    }
}

// Usage in theme views
{!! $page->rendered_content !!}
```

### 2. PPDB Module Integration
```php
// Student model with auto-generation
class Student extends Model
{
    public static function generateRegistrationNumber(): string
    {
        $year = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return sprintf('PPDB%s%04d', $year, $count);
    }
}

// PPDB Table Block usage
@php
    $students = \App\Models\Student::approved()
        ->orderBy('final_score', 'desc')
        ->take($settings['limit'] ?? 50)
        ->get();
@endphp

<table class="min-w-full">
    @foreach($students as $student)
        <tr>
            <td>{{ $student->registration_number }}</td>
            <td>{{ $student->name }}</td>
            <td>{{ number_format($student->final_score, 2) }}</td>
        </tr>
    @endforeach
</table>
```

### 3. Theme Integration
```blade
{{-- resources/views/themes/default/pages/show.blade.php --}}
@extends('themes.default.layouts.app')

@section('content')
<div class="min-h-screen">
    @if($page->use_page_builder)
        {{-- Page Builder Content --}}
        {!! $page->rendered_content !!}
    @else
        {{-- Traditional Content --}}
        <div class="max-w-4xl mx-auto py-12 px-4">
            <div class="prose prose-lg max-w-none">
                {!! $page->content !!}
            </div>
        </div>
    @endif
</div>
@endsection
```

## 🔧 API Endpoints

### Page Builder API
- `GET /admin/page-builder/blocks` - Get available blocks
- `GET /admin/page-builder/blocks/{type}/config` - Get block configuration  
- `POST /admin/page-builder/preview` - Preview block rendering
- `POST /admin/pages/{page}/builder/save` - Save page content

### Theme Management
- `GET /admin/themes` - List available themes
- `POST /admin/themes/{theme}/activate` - Activate theme
- `GET /admin/themes/{theme}/config` - Get theme configuration

## 🏛️ Architecture

### Core Services
```php
// Theme management
class ThemeService
{
    public function getActiveTheme(): string
    public function setActiveTheme(string $theme): bool
    public function view(string $view, array $data = [])
}

// Page builder
class PageBuilderService  
{
    public function registerBlock(string $type, array $config): void
    public function renderPageContent(Page $page): string
    public function getRegisteredBlocks(): array
}
```

### Database Schema
```sql
-- Enhanced pages table
ALTER TABLE pages ADD COLUMN content_json JSON;
ALTER TABLE pages ADD COLUMN use_page_builder BOOLEAN DEFAULT FALSE;

-- Students for PPDB module
CREATE TABLE students (
    id BIGINT PRIMARY KEY,
    registration_number VARCHAR(255) UNIQUE,
    name VARCHAR(255) NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'enrolled'),
    final_score DECIMAL(5,2),
    -- ... other fields
);

-- Theme settings
INSERT INTO settings VALUES 
('active_theme', 'default'),
('theme_config', '{}');
```

## 🚀 Deployment

### Production Checklist
```bash
# 1. Optimize autoloader
composer install --optimize-autoloader --no-dev

# 2. Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Compile production assets
npm run build

# 4. Set proper permissions
chmod -R 755 storage bootstrap/cache
```

### Performance Tips
- Use Redis for session and cache storage
- Enable Gzip compression on web server
- Optimize images (WebP format recommended)
- Use CDN for static assets
- Enable OPcache for PHP

## 📁 Project Structure

```
app/
├── Services/
│   ├── ThemeService.php          # Theme management
│   └── PageBuilderService.php    # Block system
├── Models/
│   ├── Student.php               # PPDB module
│   └── Page.php                  # Enhanced with page builder
└── Http/Controllers/Admin/
    └── PageBuilderController.php # Page builder API

resources/views/
├── themes/
│   └── default/                  # Default theme
│       ├── layouts/app.blade.php
│       └── partials/
├── blocks/                       # Block components
│   ├── hero.blade.php
│   ├── ppdb-table.blade.php
│   └── latest-posts.blade.php
└── admin/pages/
    └── builder.blade.php         # Page builder UI

resources/js/
└── components/
    └── PageBuilder.tsx           # React page builder

docs/
└── page-builder-example.json    # Example JSON structure
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License.

---

**Transform your school website with a professional, WordPress-like CMS experience! 🎓**