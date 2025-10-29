# Contributing to School CMS

Thank you for considering contributing to the School CMS project! This document provides guidelines and instructions for contributors.

## 🌟 Ways to Contribute

- **Bug Reports**: Submit detailed bug reports with steps to reproduce
- **Feature Requests**: Propose new features or improvements
- **Code Contributions**: Submit pull requests with bug fixes or new features
- **Documentation**: Improve README, add tutorials, or translate content
- **Testing**: Write tests or report edge cases
- **Design**: Contribute UI/UX improvements or themes

## 🚀 Getting Started

### 1. Fork & Clone
```bash
git clone https://github.com/wondrv/sekolah.git
cd sekolah
```

### 2. Setup Development Environment
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed --class=DefaultThemeSeeder
npm run dev
php artisan serve
```

### 3. Create a Feature Branch
```bash
git checkout -b feature/your-feature-name
# or
git checkout -b fix/bug-description
```

## 📝 Coding Standards

### PHP Code Style
We follow **PSR-12** coding standards:

```php
// ✅ Good
class TemplateController extends Controller
{
    public function index(): View
    {
        $templates = UserTemplate::where('is_active', true)->get();
        
        return view('admin.templates.index', compact('templates'));
    }
}

// ❌ Bad
class TemplateController extends Controller {
    function index() {
        $templates=UserTemplate::where('is_active',true)->get();
        return view('admin.templates.index',compact('templates'));
    }
}
```

### Blade Templates
- Use 4 spaces for indentation
- Keep components small and focused
- Use proper Blade directives (@if, @foreach, etc.)
- Avoid inline PHP where possible

```blade
{{-- ✅ Good --}}
@foreach($templates as $template)
    <x-template-card :template="$template" />
@endforeach

{{-- ❌ Bad --}}
@foreach($templates as $template)
<?php echo view('components.template-card', ['template' => $template]); ?>
@endforeach
```

### CSS (Tailwind)
- Use Tailwind utility classes
- Avoid custom CSS unless absolutely necessary
- Follow mobile-first approach

```html
<!-- ✅ Good -->
<div class="flex flex-col md:flex-row gap-4">
    <div class="w-full md:w-1/2">Content</div>
</div>

<!-- ❌ Bad -->
<div style="display: flex; gap: 1rem;">
    <div style="width: 50%;">Content</div>
</div>
```

### JavaScript/Alpine.js
- Use Alpine.js for simple interactions
- Keep x-data objects focused and minimal
- Comment complex logic

```html
<!-- ✅ Good -->
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open" x-transition>Content</div>
</div>
```

## 🧪 Testing

### Writing Tests
All new features should include tests:

```php
// tests/Feature/TemplateManagementTest.php
public function test_admin_can_create_template()
{
    $admin = User::factory()->admin()->create();
    
    $response = $this->actingAs($admin)->post('/admin/templates', [
        'name' => 'Test Template',
        'description' => 'A test template',
        'template_type' => 'blade_views',
    ]);
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('user_templates', ['name' => 'Test Template']);
}
```

### Running Tests
```bash
php artisan test
# or specific test
php artisan test --filter TemplateManagementTest
```

## 🔄 Pull Request Process

### Before Submitting
1. **Update from main**: `git pull origin main`
2. **Run tests**: `php artisan test`
3. **Check code style**: `./vendor/bin/pint`
4. **Test manually**: Verify your changes work as expected
5. **Update documentation**: If adding features, update README

### PR Title Format
```
[Type] Brief description

Types:
- feat: New feature
- fix: Bug fix
- docs: Documentation only
- style: Code style changes (formatting)
- refactor: Code refactoring
- test: Adding tests
- chore: Maintenance tasks
```

Examples:
- `feat: Add template export to JSON functionality`
- `fix: Resolve navigation menu ordering issue`
- `docs: Update installation instructions for Windows`

### PR Description Template
```markdown
## Description
Brief description of changes

## Motivation
Why is this change needed?

## Changes Made
- Added X feature
- Fixed Y bug
- Updated Z documentation

## Testing
- [ ] Manual testing completed
- [ ] Unit tests added/updated
- [ ] Feature tests added/updated

## Screenshots (if applicable)
[Add screenshots here]

## Related Issues
Fixes #123
```

## 🐛 Bug Reports

### Good Bug Report Template
```markdown
**Description**
Clear and concise description of the bug.

**Steps to Reproduce**
1. Go to '...'
2. Click on '...'
3. Scroll down to '...'
4. See error

**Expected Behavior**
What you expected to happen.

**Actual Behavior**
What actually happened.

**Screenshots**
If applicable, add screenshots.

**Environment**
- OS: [e.g., Windows 11]
- PHP Version: [e.g., 8.2.1]
- Laravel Version: [e.g., 11.31]
- Browser: [e.g., Chrome 120]

**Additional Context**
Any other relevant information.
```

## 💡 Feature Requests

### Good Feature Request Template
```markdown
**Is your feature request related to a problem?**
A clear description of the problem.

**Proposed Solution**
Describe the solution you'd like.

**Alternatives Considered**
Other solutions you've considered.

**Additional Context**
Screenshots, mockups, or examples.

**Impact**
Who will benefit from this feature?
```

## 📚 Documentation Contributions

### Areas Needing Documentation
- Video tutorials for common tasks
- Translation to other languages
- API documentation (if REST API is added)
- Deployment guides for different hosting providers
- Theme development tutorials

### Documentation Standards
- Use clear, simple language
- Include code examples
- Add screenshots for UI-related docs
- Test all commands/code snippets before submitting

## 🎨 Design Contributions

### UI/UX Improvements
- Follow existing design patterns
- Ensure accessibility (WCAG 2.1 AA)
- Test on mobile devices
- Use consistent spacing and colors
- Provide before/after screenshots

### Theme Contributions
If contributing a new template/theme:
- Must be responsive (mobile, tablet, desktop)
- Must include proper SEO structure
- Must follow accessibility guidelines
- Include screenshots and demo link
- Document any custom blocks needed

## 🔐 Security Issues

**Do NOT open public issues for security vulnerabilities.**

Instead, email security reports to: [security@yourproject.com]

Include:
- Description of the vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (if any)

We'll respond within 48 hours.

## 📋 Code Review Process

### What We Look For
1. **Functionality**: Does it work as intended?
2. **Code Quality**: Is it clean, readable, and maintainable?
3. **Tests**: Are there adequate tests?
4. **Documentation**: Is it properly documented?
5. **Performance**: Any performance implications?
6. **Security**: Any security concerns?

### Review Timeline
- Small fixes: 1-2 days
- New features: 3-7 days
- Major changes: 1-2 weeks

## 🏆 Recognition

Contributors will be:
- Listed in CONTRIBUTORS.md
- Mentioned in release notes
- Credited in commit history

Top contributors may be offered:
- Maintainer status
- Early access to new features
- Input on roadmap decisions

## 📞 Getting Help

- **Discord**: [Join our Discord server](#)
- **GitHub Discussions**: For questions and general discussion
- **GitHub Issues**: For bugs and feature requests
- **Email**: [support@yourproject.com]

## 📜 License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

**Thank you for making School CMS better! 🎓**

Every contribution, no matter how small, is valuable and appreciated.
