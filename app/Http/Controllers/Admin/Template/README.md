# Template Controllers

This directory contains all template-related admin controllers.

## Controllers

### Core Template Management
- **TemplateBuilderController**: Visual drag-and-drop template builder
- **MyTemplatesController**: User template management (CRUD operations)
- **TemplateGalleryController**: Browse and select predefined templates

### Template Import/Export
- **TemplateExportController**: Export templates to JSON/ZIP
- **SmartImportController**: AI-enhanced template import
- **LiveImportController**: Real-time template import preview
- **FullTemplateImportController**: Complete project template import
- **CompleteProjectImportController**: Full project structure import

## Routes

All template routes are prefixed with `/admin/templates/`:

```
/admin/templates/builder     - Template builder
/admin/templates/my          - My templates
/admin/templates/gallery     - Template gallery
/admin/templates/import      - Import templates
/admin/templates/export      - Export templates
```

See `routes/admin.php` for complete route definitions.
