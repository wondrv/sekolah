# 🏫 School Website - Laravel 11.x

A comprehensive school management website built with Laravel 11.x and Tailwind CSS. Features a professional public-facing website and a powerful admin dashboard for content management.

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)

## ✨ Features

### 🌐 Public Website
- **Homepage** - Modern landing page with hero section and featured content
- **News & Articles** - Blog-style news system with categories and search
- **Events Calendar** - Academic calendar and event management
- **Photo Gallery** - Image galleries with lightbox functionality
- **Dynamic Pages** - School profile, facilities, programs, etc.
- **Contact Page** - Contact information and inquiry form
- **Responsive Design** - Mobile-first responsive design

### 🛠 Admin Dashboard
- **Content Management** - Full CRUD for all content types
- **User Management** - Admin and editor roles with permissions
- **Media Library** - Image upload and management
- **Analytics Dashboard** - Site statistics and KPIs
- **Settings Panel** - Site configuration and customization

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite (default) or MySQL

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/school-website.git
   cd school-website
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   # For SQLite (default)
   touch database/database.sqlite
   
   # Run migrations
   php artisan migrate
   
   # Seed with sample data
   php artisan db:seed --class=SampleContentSeeder
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Create storage link**
   ```bash
   php artisan storage:link
   ```

7. **Create admin user**
   ```bash
   php artisan make:admin admin@example.com
   ```

8. **Start development server**
   ```bash
   php artisan serve
   ```

🎉 Visit `http://localhost:8000` to see your website!

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/     # Public and Admin controllers
│   ├── Models/              # Eloquent models
│   └── Http/Middleware/     # Custom middleware
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/            # Sample data
├── resources/
│   ├── views/              # Blade templates
│   └── css/                # Tailwind CSS source
└── public/
    └── assets/             # Compiled CSS and images
```

## 🗄 Database Schema

The application includes the following main entities:

- **Users** - Admin users with role-based access
- **Categories** - Content categorization
- **Posts** - News articles and blog posts
- **Events** - Academic calendar and events
- **Pages** - Static pages (About, Facilities, etc.)
- **Galleries** - Photo albums
- **Photos** - Individual images within galleries

## 🎨 Styling & UI

This project uses **Tailwind CSS** without Vite for maximum compatibility:

```bash
# Development (watch mode)
npm run dev

# Production build
npm run build
```

### UI Components
- Responsive navigation with mobile menu
- Card-based layouts
- Image lightbox for galleries
- Pagination components
- Form validation styles
- Loading states and transitions

## 🔐 Authentication & Admin Access

### Admin Login
- Access the admin panel at `/admin/dashboard`
- Default credentials can be created using: `php artisan make:admin`

### Permissions
- **Admin**: Full access to all features
- **Editor**: Content management only (posts, events, galleries)

## 🛣 Available Routes

### Public Routes
```
GET  /                    # Homepage
GET  /berita             # News listing
GET  /berita/{slug}      # Individual news article
GET  /agenda             # Events calendar
GET  /agenda/{event}     # Event details
GET  /galeri             # Photo galleries
GET  /galeri/{gallery}   # Gallery photos
GET  /profil/{slug?}     # Dynamic pages
GET  /kontak             # Contact page
```

### Admin Routes (Protected)
```
GET  /admin/dashboard    # Admin dashboard
CRUD /admin/posts        # News management
CRUD /admin/events       # Events management
CRUD /admin/galleries    # Gallery management
CRUD /admin/pages        # Pages management
CRUD /admin/facilities   # Facilities management
CRUD /admin/programs     # Programs management
```

## 🚢 Deployment

### Production Optimization

1. **Install production dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

2. **Cache configuration**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Build assets**
   ```bash
   npm run build
   ```

4. **Set environment**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

### Deployment Platforms
- ✅ Shared hosting (cPanel)
- ✅ VPS (Ubuntu/CentOS)
- ✅ Cloud platforms (AWS, DigitalOcean)
- ✅ Container platforms (Docker)

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run with coverage
php artisan test --coverage
```

## 📝 Sample Content

The application includes a comprehensive seeder with:
- Sample news articles
- Upcoming events
- School profile pages
- Photo gallery examples
- Admin user account

Run the seeder: `php artisan db:seed --class=SampleContentSeeder`

## 🔧 Configuration

### Environment Variables
```env
# App
APP_NAME="School Website"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database/database.sqlite

# Mail (for contact forms)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
```

### Customization
- Update school information in the seeder
- Modify Tailwind configuration in `tailwind.config.js`
- Customize views in `resources/views/`
- Add new routes in `routes/web.php`

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📋 Roadmap

- [ ] Multi-language support (Indonesian/English)
- [ ] Advanced search functionality
- [ ] Email newsletter system
- [ ] Social media integration
- [ ] SEO optimization tools
- [ ] Progressive Web App (PWA)
- [ ] Student portal integration
- [ ] Online enrollment system

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 💬 Support

If you have any questions or need help with the setup, please feel free to:
- Open an issue on GitHub
- Contact the maintainers
- Check the documentation

---

**Built with ❤️ for schools worldwide**

Made possible by [Laravel](https://laravel.com) and [Tailwind CSS](https://tailwindcss.com)
