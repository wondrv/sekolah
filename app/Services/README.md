# Services Directory

This directory contains service classes that encapsulate business logic for the CMS.

## Service Classes

### Template Services
- **TemplateImporterService**: Base template import functionality
- **SmartTemplateImporterService**: AI-enhanced template import with translation
- **FullTemplateImporterService**: Complete project template import
- **AdvancedTemplateImporterService**: Advanced template processing
- **ExternalTemplateService**: Fetch and import external templates

### Content Services
- **PageBuilderService**: Page builder and content rendering
- **TemplateRenderService**: Template rendering engine
- **ThemeService**: Theme management and customization

### Utility Services
- **HtmlValidatorService**: HTML validation and sanitization
- **AutoTranslationService**: Automatic content translation
- **LanguageDetectionService**: Language detection for content
- **PreviewImageService**: Template preview generation

## Usage

Services are registered in `app/Providers/AppServiceProvider.php` and can be injected via dependency injection:

```php
public function __construct(PageBuilderService $pageBuilder)
{
    $this->pageBuilder = $pageBuilder;
}
```
