# 🏫 School CMS - Laravel 11.x with Admin-Editable Templates

A complete Content Management System for schools built with Laravel 11.x, featuring **admin-editable templates**, dynamic theming, and a comprehensive admin dashboard. Transform your school's web presence with a fully customizable, CMS-first approach.

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)

## 🚀 What Makes This Special

This isn't just another school website template. It's a **full-featured CMS** where **everything is editable** from the admin panel:

- 🎨 **Admin-Editable Homepage Templates** - Design your homepage layout with drag-and-drop blocks
- 🎯 **Dynamic Theme System** - Change colors, fonts, and branding from the admin panel
- 📱 **Responsive Block Components** - Pre-built sections that adapt to any device
- 🔧 **No Code Required** - Non-technical staff can manage everything through the admin
- ⚡ **Performance Optimized** - Built-in caching and optimized CSS pipeline

## ✨ CMS Features

### 🌐 Frontend Capabilities
- **Dynamic Homepage**: Completely configurable layout with sections and blocks
- **Theme Customization**: Colors, typography, and branding editable via admin
- **Smart Navigation**: Menus that adapt to your content structure
- **SEO Optimized**: Meta titles, descriptions, and Open Graph tags for all content
- **Mobile-First Design**: Responsive components that work on all devices
- **Content Types**: Posts, Pages, Events, Galleries, Programs, Facilities

### 🛠 Admin Dashboard
- **Visual Template Builder**: Design homepage layout with intuitive block system
- **Theme Manager**: Customize site appearance without touching code
- **Content Management**: Full CRUD for all content types with rich editing
- **Menu Builder**: Create and organize navigation menus
- **Settings Panel**: Site information, contact details, social media
- **User Management**: Role-based access (Admin/Editor permissions)
- **Media Library**: Upload and organize images and files
- **Dashboard Analytics**: Site statistics and content overview

### 🧩 Block Types Available
- **Hero Section**: Eye-catching banners with titles and call-to-action buttons
- **Card Grid**: Responsive grids for programs, facilities, or any content
- **Rich Text**: WYSIWYG content blocks with formatting options
- **Statistics Counter**: Number displays for student count, achievements, etc.
- **Call-to-Action**: Prominent sections for enrollment or contact prompts
- **Gallery Teaser**: Showcase recent photos with automatic linking
- **Events Teaser**: Display upcoming events with smart date filtering

## 🛠 Technology Stack

- **Backend**: Laravel 11.x (PHP ≥ 8.2) with strict MVC architecture
- **Frontend**: Blade Templates + Tailwind CSS (CLI, no Vite)
- **Database**: SQLite (default) with full MySQL/PostgreSQL support
- **Authentication**: Laravel Breeze with role-based access control
- **Caching**: File-based caching for optimal performance
- **Build**: PostCSS + Tailwind CLI for CSS compilation

## 📦 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM (for CSS compilation)
- SQLite support (included with PHP)

### Installation Steps

1. **Clone and Setup**
   ```powershell
   git clone https://github.com/wondrv/sekolah.git
   cd sekolah
   composer install
   npm install
   ```

2. **Environment Configuration**
   ```powershell
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**
   ```powershell
   # Create SQLite database
   touch database/database.sqlite  # On Windows: type nul > database\database.sqlite
   
   # Run migrations and seed with CMS data
   php artisan migrate
   php artisan db:seed --class=DefaultThemeSeeder
   ```

4. **Compile Assets**
   ```powershell
   npm run build
   ```

5. **Create Storage Link**
   ```powershell
   php artisan storage:link
   ```

6. **Start Development Server**
   ```powershell
   php artisan serve
   ```

🎉 Visit `http://localhost:8000` to see your CMS in action!

**Default Admin Access:**
- URL: `http://localhost:8000/admin/dashboard`
- Email: `admin@school.local`
- Password: `password`

## 🎨 Customizing Your School Website

### 1. **Theme & Branding Setup**
Navigate to **Admin → Settings → Theme** to customize:
- **Brand Colors**: Primary, secondary, accent colors
- **Typography**: Font families, sizes, and weights
- **Site Identity**: Logo, favicon, school name
- **Contact Information**: Address, phone, email, social media

### 2. **Homepage Design**
Go to **Admin → Templates → Homepage** to build your layout:
- **Add Sections**: Create distinct areas of your homepage
- **Choose Blocks**: Add hero banners, content cards, statistics, etc.
- **Configure Content**: Set titles, descriptions, images, and links
- **Preview Changes**: See how your design looks before publishing

### 3. **Navigation Menus**
Manage your site navigation at **Admin → Menus**:
- **Primary Menu**: Main site navigation
- **Footer Menu**: Links in the footer area
- **Drag & Drop**: Organize menu items intuitively
- **External Links**: Add links to external resources

### 4. **Content Management**
Create and manage all your content:
- **News & Announcements**: Keep your community informed
- **Academic Calendar**: Share important dates and events
- **Photo Galleries**: Showcase school life and activities
- **Static Pages**: About us, admission, policies, etc.

## 🗄 Database Architecture

### CMS Core Tables
```sql
settings        # Site configuration (JSON key-value store)
menus          # Navigation menu structure
menu_items     # Individual menu entries with hierarchy
templates      # Homepage layout templates
sections       # Template sections (header, content, footer areas)
blocks         # Individual content blocks within sections
widgets        # Reusable content components
```

### Content Tables
```sql
users          # Admin users with role-based permissions
categories     # Content categorization system
posts          # News articles and announcements
pages          # Static pages with SEO fields
events         # Academic calendar and activities
galleries      # Photo album collections
photos         # Individual images with metadata
```

## 🎯 SEO & Performance Features

### Built-in SEO
- **Meta Tags**: Custom titles and descriptions for every page
- **Open Graph**: Social media sharing optimization
- **Clean URLs**: SEO-friendly slug generation
- **Structured Data**: Rich snippets for better search visibility
- **XML Sitemaps**: Automatic sitemap generation

### Performance Optimization
- **Theme Caching**: Database settings cached for speed
- **Optimized CSS**: Tailwind's utility-first approach
- **Image Optimization**: Responsive images with proper sizing
- **Lazy Loading**: Images load as needed
- **Minimal JavaScript**: Focus on performance over flashy effects

## 🔐 User Roles & Security

### Admin Role
- Full system access including:
  - Site settings and theme customization
  - Template and menu management
  - User management and permissions
  - All content creation and editing

### Editor Role
- Content-focused access:
  - Create and edit posts, pages, events, galleries
  - Manage media uploads
  - Cannot access system settings or user management

### Security Features
- Role-based access control (RBAC)
- CSRF protection on all forms
- SQL injection prevention
- XSS protection
- Secure file upload validation

## 🚀 Production Deployment

### Optimization Commands
```powershell
# Install production dependencies
composer install --no-dev --optimize-autoloader

# Cache everything for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build optimized assets
npm run build
```

### Environment Configuration
Update your `.env` for production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-school-domain.com

# Database (switch to MySQL for production)
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

# Mail (for contact forms)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
```

## 📁 Project Structure

```
sekolah/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/              # Admin dashboard controllers
│   │   └── Public/             # Public-facing controllers
│   ├── Models/                 # Eloquent models
│   │   ├── Setting.php         # CMS settings
│   │   ├── Template.php        # Homepage templates
│   │   ├── Block.php           # Content blocks
│   │   └── [content models]    # Posts, Pages, Events, etc.
│   └── Support/
│       └── Theme.php           # Theme helper class
├── database/
│   ├── migrations/             # Database schema
│   └── seeders/
│       └── DefaultThemeSeeder.php # CMS setup
├── resources/
│   ├── views/
│   │   ├── components/         # Reusable Blade components
│   │   │   ├── blocks/         # Template block components
│   │   │   └── navigation/     # Menu components
│   │   ├── admin/              # Admin interface
│   │   └── public/             # Public website
│   └── css/
│       └── app.css             # Tailwind CSS source
└── routes/
    ├── web.php                 # Public routes
    └── admin.php               # Admin routes
```

## 🧪 Testing Your CMS

After installation, test these key features:

1. **Homepage Customization**
   - Access admin dashboard
   - Navigate to Templates → Homepage
   - Add/remove sections and blocks
   - Verify changes appear on homepage

2. **Theme Changes**
   - Go to Settings → Theme
   - Change primary color
   - Verify color updates across the site

3. **Content Management**
   - Create a test news post
   - Add an upcoming event
   - Upload photos to a gallery

4. **Menu Management**
   - Edit the primary menu
   - Add/remove menu items
   - Verify changes in navigation

## 🔧 Customization Guide

### Adding New Block Types
1. Create a new Blade component in `resources/views/components/blocks/`
2. Define the block configuration in the Block model
3. Add the block type to the template builder

### Custom CSS Modifications
```css
/* In resources/css/app.css */
@layer components {
  .custom-school-button {
    @apply bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded;
  }
}
```

### Adding New Content Types
1. Create migration: `php artisan make:migration create_new_content_table`
2. Create model: `php artisan make:model NewContent`
3. Create controller: `php artisan make:controller Admin/NewContentController`
4. Add routes to `routes/admin.php`

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Make your changes following our coding standards
4. Test your changes thoroughly
5. Commit: `git commit -m 'Add amazing feature'`
6. Push: `git push origin feature/amazing-feature`
7. Submit a Pull Request

## 📋 Roadmap

### Planned Features
- [ ] **Advanced Template Builder**: Visual drag-and-drop interface
- [ ] **Multi-language Support**: Indonesian and English content
- [ ] **Advanced SEO Tools**: Schema markup generator
- [ ] **Student Portal Integration**: Login area for students/parents
- [ ] **Online Enrollment System**: Application forms and processing
- [ ] **Email Newsletter System**: Automated communications
- [ ] **Advanced Analytics**: Detailed visitor and content insights
- [ ] **Progressive Web App**: Offline functionality and app-like experience

### Version 2.0 Goals
- Visual page builder for all pages (not just homepage)
- Custom field builder for content types
- Advanced user roles and permissions
- E-commerce integration for school merchandise
- Learning Management System (LMS) features

## 📄 License

This project is open-sourced software licensed under the [MIT License](LICENSE).

## 💬 Support & Community

### Getting Help
- 📚 **Documentation**: This README covers most use cases
- 🐛 **Bug Reports**: Open an issue on GitHub
- 💡 **Feature Requests**: Suggest improvements via GitHub issues
- 💬 **Community**: Join our Discord server for discussions

### Professional Support
For schools needing custom development or professional support:
- Custom theme development
- Advanced feature implementation
- Training for non-technical staff
- Hosting and maintenance services

---

**Built with ❤️ for schools worldwide**

*Transforming education through technology, one school website at a time.*

**Powered by [Laravel](https://laravel.com) • [Tailwind CSS](https://tailwindcss.com) • [SQLite](https://sqlite.org)**
