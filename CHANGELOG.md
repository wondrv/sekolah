# Changelog

All notable changes to the School CMS project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Planned
- Multi-language content support
- Advanced analytics dashboard
- Email newsletter system
- Student portal integration
- Online payment gateway for enrollment
- Mobile app API

## [1.0.0] - 2025-10-29

### Added
- **Smart Template Import System**
  - Auto language detection (15+ languages supported)
  - Auto translation to Indonesian
  - Import from any URL (Bootstrap templates, GitHub repos, etc.)
  - Batch import capability
  - One-click activation

- **Template Management**
  - Visual drag-and-drop template builder
  - Template gallery with predefined templates
  - Live preview system (WordPress-like)
  - Draft editing workflow
  - Template revisions and restore
  - Signed public preview links
  - Quick template switcher in admin header
  - Bulk install functionality

- **External Template Discovery**
  - Real-time discovery from GitHub and other sources
  - WordPress-like preview before installation
  - One-click install with automatic conversion
  - Smart filtering for education templates
  - Performance optimized with caching

- **Content Management**
  - Posts (news/blog articles) with categories
  - Pages (static content) with page builder
  - Events (academic calendar) with metadata
  - Galleries (photo albums) with albums
  - Programs (academic programs)
  - Facilities (school facilities)
  - SEO optimization for all content types

- **Theme System**
  - Dynamic theme customization from admin
  - CSS custom properties for branding
  - Color scheme management
  - Typography settings
  - Logo and favicon upload

- **User Management**
  - Role-based access control (Admin/Editor)
  - Laravel Breeze authentication
  - User profiles
  - Policy-based authorization

- **Admin Dashboard**
  - KPI statistics and overview
  - Quick import widget
  - Recent activity feed
  - Content management shortcuts

- **Block Components**
  - Hero sections
  - Card grids (responsive)
  - Rich text blocks
  - Statistics counters
  - Call-to-action banners
  - Gallery teasers
  - Events teasers

- **Navigation System**
  - Menu builder with drag-and-drop
  - Hierarchical menu structure
  - Multiple menu locations
  - External link support

- **Performance Features**
  - Theme data caching
  - Route caching
  - View compilation
  - Optimized CSS pipeline (Tailwind CLI)
  - Lazy loading for images

- **SEO Features**
  - Meta titles and descriptions
  - Open Graph tags
  - Structured data support
  - Clean URL slugs
  - Automatic sitemap (planned)

- **Documentation**
  - Comprehensive README with quick start
  - ARCHITECTURE.md for technical details
  - CONTRIBUTING.md for contributors
  - SECURITY.md for security best practices
  - Service and controller documentation
  - Code examples and tutorials

### Security
- CSRF protection on all forms
- XSS protection with Blade escaping
- SQL injection prevention with Eloquent
- File upload validation
- Secure password hashing
- Session security
- Rate limiting (planned)

### Developer Experience
- PSR-12 coding standards
- Service layer architecture
- Repository pattern support
- Event-driven architecture
- Comprehensive .gitignore
- Professional project structure
- Clean separation of concerns

## [0.1.0] - 2025-09-01

### Added
- Initial project setup
- Basic Laravel 11.x structure
- Tailwind CSS integration
- SQLite database configuration
- Authentication with Laravel Breeze

---

## Version History Notes

### Versioning Scheme
We use Semantic Versioning (MAJOR.MINOR.PATCH):
- **MAJOR**: Incompatible API changes
- **MINOR**: New functionality (backward compatible)
- **PATCH**: Bug fixes (backward compatible)

### Release Schedule
- **Major releases**: Annually
- **Minor releases**: Quarterly
- **Patch releases**: As needed for critical bugs

### Support Policy
- **Current version (1.x)**: Full support
- **Previous major version**: Security fixes only (6 months)
- **Older versions**: No support

---

**For detailed commit history, see [GitHub Commits](https://github.com/wondrv/sekolah/commits/main)**
