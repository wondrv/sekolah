# Security Policy

## 🔒 Supported Versions

Currently supported versions for security updates:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |
| < 1.0   | :x:                |

## 🐛 Reporting a Vulnerability

**Please do NOT report security vulnerabilities through public GitHub issues.**

### Reporting Process

1. **Email**: Send details to `security@yourproject.com`
2. **Include**:
   - Description of the vulnerability
   - Steps to reproduce
   - Potential impact assessment
   - Suggested fix (if available)
   - Your contact information

### What to Expect

- **Initial Response**: Within 48 hours
- **Status Updates**: Every 3-5 days
- **Resolution Timeline**: Varies by severity
  - Critical: 1-7 days
  - High: 1-2 weeks
  - Medium: 2-4 weeks
  - Low: 4-8 weeks

### Disclosure Policy

- We practice **responsible disclosure**
- Security patches released ASAP
- Public disclosure after patch is available
- Credit given to reporter (unless anonymous requested)

## 🛡️ Security Best Practices

### For Developers

1. **Never commit sensitive data**:
   ```bash
   # ✅ Good - use .env
   DB_PASSWORD=your-secret-password
   
   # ❌ Bad - hardcoded in config
   'password' => 'my-secret-123'
   ```

2. **Validate all input**:
   ```php
   // Use Form Requests
   public function store(CreateTemplateRequest $request) {
       // Input is already validated
   }
   ```

3. **Sanitize output**:
   ```blade
   {{-- ✅ Good - auto-escaped --}}
   {{ $user->name }}
   
   {{-- ❌ Bad - unescaped --}}
   {!! $user->name !!}
   ```

4. **Use CSRF protection**:
   ```blade
   <form method="POST">
       @csrf
       <!-- form fields -->
   </form>
   ```

5. **Implement proper authentication**:
   ```php
   // Use middleware
   Route::middleware(['auth', 'admin'])->group(function () {
       // Protected routes
   });
   ```

### For Administrators

1. **Change default credentials immediately**
2. **Use strong passwords** (12+ characters, mixed case, numbers, symbols)
3. **Keep Laravel and dependencies updated**:
   ```bash
   composer update
   php artisan migrate
   ```
4. **Enable production optimizations**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   ```
5. **Use HTTPS in production**
6. **Regular backups** of database and files
7. **Monitor logs** for suspicious activity

### Environment Configuration

Production `.env` should have:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Strong, unique key
APP_KEY=base64:your-generated-key

# Secure database credentials
DB_PASSWORD=strong-unique-password

# Secure session/cache settings
SESSION_DRIVER=database
CACHE_DRIVER=redis
```

## 🚨 Known Security Considerations

### File Uploads
- Template files are validated before import
- Only allowed file types: `.blade.php`, `.css`, `.js`, `.json`
- Files stored outside web root when possible
- Filenames sanitized to prevent path traversal

### SQL Injection
- All queries use Eloquent ORM or parameterized queries
- No raw SQL with user input

### XSS Protection
- Blade auto-escapes output by default
- HTML validation for rich text content
- Content Security Policy headers recommended

### CSRF Protection
- Enabled on all POST/PUT/DELETE routes
- Token validation on every form submission

### Authentication
- Passwords hashed with bcrypt
- Session security enabled
- Remember tokens secured
- Rate limiting on login attempts

## 📋 Security Checklist for Production

- [ ] Change default admin credentials
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`
- [ ] Generate new `APP_KEY`
- [ ] Use HTTPS (SSL certificate)
- [ ] Configure firewall rules
- [ ] Disable directory listing
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Enable rate limiting
- [ ] Configure CORS properly
- [ ] Set up regular backups
- [ ] Monitor error logs
- [ ] Keep dependencies updated

## 🔧 Security Headers

Recommended security headers for production:

```apache
# Apache (.htaccess)
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"
Header set Referrer-Policy "strict-origin-when-cross-origin"
Header set Content-Security-Policy "default-src 'self'"
```

```nginx
# Nginx
add_header X-Content-Type-Options "nosniff" always;
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Content-Security-Policy "default-src 'self'" always;
```

## 📚 Additional Resources

- [Laravel Security Documentation](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)

## 🏆 Security Hall of Fame

We recognize security researchers who responsibly disclose vulnerabilities:

*(No entries yet)*

---

**Your security is our priority. Thank you for helping keep School CMS secure!**
