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
│   └── Support/
│       └── Theme.php           # Theme helper class
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
   - Verify changes in navigation

## 🔧 Customization Guide

### Adding New Block Types
1. Create a new Blade component in `resources/views/components/blocks/`
2. Define the block configuration in the Block model
### Custom CSS Modifications
```css
/* In resources/css/app.css */
    @apply bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-4 rounded;
  }
}
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

This project is open-sourced software licensed under the [MIT License](LICENSE).
- 📚 **Documentation**: This README covers most use cases
- 🐛 **Bug Reports**: Open an issue on GitHub
-  **Community**: Join our Discord server for discussions
### Professional Support
- Custom theme development
- Training for non-technical staff


**Built with ❤️ for schools worldwide**

## 🧪 Live Preview & Draft Workflow

The CMS includes a WordPress‑style live preview system plus a draft layer for safe iteration.

### 🔍 Live Preview (Non-Destructive)
Use this when you want to see an installed (but not active) template applied to the frontend without affecting visitors.

Flow:
1. Admin → Template System → My Templates
2. Klik tombol Preview pada template yang belum aktif
3. Frontend terbuka dengan `?preview=1` + banner kuning “Preview Mode”
4. Jelajahi halaman; sistem mencocokkan slug/route otomatis
5. Klik Keluar Preview untuk kembali normal
6. Klik Activate jika ingin menerapkan

### ✏️ Draft Editing (Safe Iteration)
- `template_data` = versi LIVE (ditampilkan publik)
- `draft_template_data` = versi DRAFT (belum diterapkan)
- Preview Draft = render draft di frontend (banner amber) tanpa publish

Tindakan:
- Preview Draft → Lihat hasil perubahan
- Publish Draft → Terapkan ke live & hapus draft
### 🧠 Internal Flow
| Feature | Mechanism |
|---------|-----------|
| Preview Template | Session: `preview_user_template_id` |
| Preview Draft | Session tambahan: `preview_use_draft` |
| Banner | Variabel view: `$isPreviewMode`, `$isDraftPreview` |
| Rendering | Build in-memory (tanpa insert DB) |
| SEO Safety | `<meta name="robots" content="noindex,nofollow">` selama preview |

### 🪪 Session Keys
| Key | Meaning |
|-----|---------|
| `preview_user_template_id` | ID template yang dipreview |
| `preview_use_draft` | Gunakan draft vs live saat preview |

### 🚩 Edge Cases
- Tidak ada draft → tombol Preview Draft error ramah
- Publish tanpa draft → diblokir
- Template aktif publish draft → langsung rebuild struktur live
- Stop preview menghapus kedua session preview

### API Draft Save (Builder)
Kirim payload JSON dengan flag:
```json
{
   "name": "Modern School Theme",
   "description": "Update hero & stats",
   "template_data": { "templates": [] },
   "save_as_draft": true
}
```

### Rekomendasi Pengembangan Lanjut
- Revisions (snapshot sebelum publish)
- Signed public preview link (kepala sekolah)
- Child template inheritance
- UI mapping route → template eksplisit

## 🗂 Template Revisions

Setiap kali Anda:
- Activate template
- Publish Draft
- Restore revision

Sistem otomatis membuat snapshot (revision) yang bisa dipulihkan.

Fitur utama:
| Aksi | Revisions yang Dibuat |
|------|------------------------|
| Activate | `activate` + `after_activate` |
| Publish Draft | `publish_draft` + `after_publish_draft` |
| Restore | `pre_restore` + `post_restore` |

Cara pakai:
1. Buka: Admin → Template System → My Templates → pilih template
2. Scroll ke bagian Revisions
3. Klik Restore pada revision yang ingin dikembalikan
4. Snapshot state sekarang akan otomatis tersimpan sebelum restore

Catatan:
- Maksimal 20 revisi terbaru ditampilkan (bisa diperluas nanti)
- Draft ikut tersimpan dalam snapshot (`draft_template_data`)
- Revisions tidak memblokir jika proses gagal (fail-silent logging)


*Transforming education through technology, one school website at a time.*

**Powered by [Laravel](https://laravel.com) • [Tailwind CSS](https://tailwindcss.com) • [SQLite](https://sqlite.org)**

## ⚡ Template Quick Start Enhancements

Untuk mempercepat proses "tinggal pakai", sistem kini memiliki beberapa fitur tambahan:

### 1. Install & Activate Sekali Klik
Di halaman Gallery (grid & detail), setiap template yang belum terpasang memiliki tombol:
- Install → hanya menyalin ke koleksi Anda
- Install & Aktifkan → langsung menjadi template aktif (swap theme)

### 2. Bulk Install (Install Semua Starter)
Tombol "Install Semua Starter" di bagian atas halaman Gallery:
- Mengkloning semua template aktif di gallery ke My Templates
- Mengaktifkan satu (pertama) secara otomatis jika belum ada yang aktif

### 3. Quick Template Switcher
Dropdown di header admin (kanan atas) menampilkan semua template Anda:
- Template aktif diberi label Active
- Klik tombol Aktifkan pada template lain untuk instant swap (tanpa membuka halaman detail)

### 4. Seeder Starter Templates
Jika ingin memulai dari kondisi kosong dan mengisi gallery dengan template dasar:
```powershell
php artisan db:seed --class=StarterTemplatesSeeder
```
Setelah itu buka: Admin → Template System → Template Gallery → Install & Aktifkan.

### 5. Alur Paling Cepat (Baru Install Project)
```text
php artisan migrate
php artisan db:seed --class=DefaultThemeSeeder
php artisan db:seed --class=StarterTemplatesSeeder   # (opsional, isi gallery)
Masuk admin → Template Gallery → Install & Aktifkan salah satu
Atau klik Install Semua Starter → pilih tema via Quick Switch Dropdown
```

### 6. FAQ Singkat
| Pertanyaan | Jawaban |
|------------|---------|
| Apakah gallery langsung tampil ke publik? | Tidak, harus di-install dulu ke My Templates. |
| Bisa hapus template yang sudah diinstall? | Bisa, kecuali yang sedang aktif. |
| Apa bedanya draft & preview? | Draft menyimpan perubahan belum publish; preview hanya menampilkan tanpa mengubah live. |
| Apakah bulk install menimpa yang sudah ada? | Tidak, hanya menambah yang belum terpasang. |

### 7. Gallery Live Preview (Seperti WordPress Theme Preview)
Sekarang setiap template di Gallery bisa dibuka dalam mode "Live Preview" sebelum diinstall.

Cara pakai:
1. Buka: Admin → Template System → Template Gallery
2. Klik tombol "Live" (ikon petir) di kartu template ATAU tombol "Live Preview" di halaman detail
3. Anda akan melihat halaman preview komposisi section & block dari `template_data` (belum mempengaruhi situs)
4. Klik "Install & Aktifkan" langsung dari halaman preview jika cocok

Catatan:
- Preview ini tidak memakai session preview user template (berbeda dari My Templates preview)
- Hanya menampilkan struktur block dasar (tanpa dynamic query ke konten nyata)
- Aman: tidak memodifikasi database user templates sampai Anda klik install

Enhanced Preview:
- Mendukung sebagian besar block melalui auto-include `components.blocks.{type}`
- Viewport switcher: Mobile / Tablet / Desktop
- Navigasi cepat Next / Prev antar template gallery
- Auto sample data injeksi untuk block kosong (card-grid, stats)

## 🌐 Real-Time External Template Discovery

The CMS now features **WordPress-like external template discovery** that automatically finds compatible templates from external sources in real-time.

### ✨ Key Features
- **Real-Time Discovery**: Automatically searches GitHub and other sources for school/education templates
- **WordPress-Like Preview**: Preview external templates before installation with live preview  
- **One-Click Install**: Seamless installation that converts external templates to CMS format
- **Template Conversion**: Automatically maps external structures to CMS blocks/sections
- **Smart Filtering**: Only shows school/education-related templates
- **Performance Optimized**: 1-hour caching, graceful error handling

### 🎯 Supported Sources
| Source | Description | Template Types |
|--------|-------------|----------------|
| **GitHub Repos** | School/education templates from open-source repositories | HTML, CSS, JS templates with school keywords |
| **Free CSS** | Curated school website templates | Professional education themes |
| *Future: WordPress.org* | WordPress school themes (planned) | WP themes converted to CMS format |

### 🚀 Usage

#### View External Templates
Visit: **Admin → Template System → Template Gallery**  
External templates appear in "External Templates Discovery" section with live discovery badge.

#### Preview External Template  
- Click **"Preview"** on any external template
- Opens WordPress-like live preview in new tab
- See how template would look with your content/branding

#### Install External Template
- Click **"Install"** button (green ✚)
- Template automatically converted to CMS format  
- Installed to "My Templates" and ready for activation

#### Test Discovery
```powershell
php artisan cms:test-external-templates
```

### 🛠 Technical Implementation

**Discovery Service:**
```php
$service = new ExternalTemplateService();
$templates = $service->discoverTemplates('all', 20);
```

**Template Conversion:**
External templates converted to CMS `template_data` format with automatic block mapping:
```json
{
  "templates": [{
    "name": "External Template Name", 
    "sections": [{
      "name": "Hero Section",
      "blocks": [{"type": "hero", "content": {...}}]
    }]
  }]
}
```

**Performance:**
- External API results cached for 1 hour
- GitHub API: 60 requests/hour limit (unauthenticated)
- Errors logged but don't break gallery functionality
- Discovery runs only on gallery page load

### 🎨 WordPress-Like Experience
The system mimics WordPress.org template browsing experience:
- Live discovery of new templates from multiple sources
- Preview before install capability with realistic rendering
- One-click installation process with automatic conversion
- Seamless integration with existing CMS template system
- Real-time badge indicators and source attribution

This brings the "tinggal pakai" (ready-to-use) experience you requested - templates are discovered automatically and can be previewed/swapped in real-time just like WordPress!



## 🔗 Signed Public Preview Links

Bagikan tampilan template (termasuk draft opsional) ke pihak eksternal (kepala sekolah, stakeholder) tanpa perlu login.

### Cara Membuat
1. Admin → Template System → My Templates → pilih template
2. Form "Signed Public Preview Link" → pilih durasi expired + path awal (opsional)
3. Centang "Include Draft" bila ingin menampilkan versi draft
4. Klik Generate Link dan salin URL yang muncul

### Karakteristik
| Fitur | Detail |
|-------|--------|
| Auth | Tidak perlu login (akses via URL bertanda tangan) |
| Keamanan | Laravel signed URL + expirasi otomatis |
| Draft Support | Tambahkan param `include_draft` saat generate |
| Session Keys | `preview_user_template_id`, `preview_use_draft`, `preview_shared_link` |
| Banner | Warna indigo: "Shared Preview" (jelas bukan live) |
| SEO | Tetap `noindex,nofollow` (mode preview) |

### Parameter Tambahan
| Field | Fungsi |
|-------|--------|
| expires_minutes | Durasi aktif link (default 120 menit) |
| path | Path awal redirect (misal `/ppdb`) |
| include_draft | `1` untuk tampilkan draft (jika ada) |

### Alur Teknis
1. Generate: controller membuat signed route `public.template-preview`
2. User membuka URL → middleware `signed` verifikasi hash & expiry
3. Controller set session preview + opsi draft + flag shared
4. Redirect ke path tujuan dengan query `?preview=1`
5. Middleware preview memuat struktur template in-memory
6. Banner tampil (Shared Preview) + meta noindex

### Kapan Digunakan
- Review desain sebelum aktivasi
- Validasi perubahan draft oleh manajemen
- Kolaborasi remote tanpa membuat akun tambahan

### Best Practice
- Batasi durasi (≤ 2 jam) untuk keamanan
- Regenerate link setelah perubahan besar
- Jangan sebarkan ke publik luas (tidak untuk produksi permanen)

### Roadmap Peningkatan (Opsional)
- Single-use token (sekali klik hangus)
- Audit log siapa mengakses (ip, user-agent)
- Password-protected preview (lapisan kedua)
- Revocation manual (blacklist signature hash)

> Signed preview memastikan proses persetujuan visual berjalan cepat tanpa mengorbankan keamanan atau data live.
