<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Template;
use App\Models\Section;
use App\Models\Block;
use App\Models\UserTemplate;

class SyncTemplateContent extends Command
{
    protected $signature = 'template:sync-content {template_id}';
    protected $description = 'Sync template content from UserTemplate to Template sections and blocks';

    public function handle()
    {
        $templateId = $this->argument('template_id');
        $template = Template::with('sections.blocks')->find($templateId);

        if (!$template) {
            $this->error("Template with ID {$templateId} not found.");
            return 1;
        }

        if (!$template->user_template_id) {
            $this->error("Template {$templateId} is not linked to a UserTemplate.");
            return 1;
        }

        $userTemplate = UserTemplate::find($template->user_template_id);
        if (!$userTemplate || !$userTemplate->template_data) {
            $this->error("UserTemplate data not found.");
            return 1;
        }

        $templateData = $userTemplate->template_data;
        if (!isset($templateData['templates']) || empty($templateData['templates'])) {
            $this->error("No template data found in UserTemplate.");
            return 1;
        }

        // Find matching template by slug
        $matchingTemplate = null;
        foreach ($templateData['templates'] as $tpl) {
            if (($tpl['slug'] ?? '') === $template->slug) {
                $matchingTemplate = $tpl;
                break;
            }
        }

        if (!$matchingTemplate) {
            $matchingTemplate = $templateData['templates'][0]; // Use first template
        }

        if (!isset($matchingTemplate['sections'])) {
            $this->error("No sections found in template data.");
            return 1;
        }

        $this->info("Syncing template content for: {$template->name}");

        // Delete existing sections and blocks
        foreach ($template->sections as $section) {
            $section->blocks()->delete();
        }
        $template->sections()->delete();

        // Create new sections and blocks from UserTemplate data
        foreach ($matchingTemplate['sections'] as $sectionIndex => $sectionData) {
            $section = Section::create([
                'template_id' => $template->id,
                'name' => $sectionData['name'] ?? "Section {$sectionIndex}",
                'order' => $sectionData['order'] ?? $sectionIndex,
                'active' => $sectionData['active'] ?? true,
                'settings' => isset($sectionData['settings']) ? json_encode($sectionData['settings']) : null,
            ]);

            if (isset($sectionData['blocks'])) {
                foreach ($sectionData['blocks'] as $blockIndex => $blockData) {
                    Block::create([
                        'section_id' => $section->id,
                        'type' => $blockData['type'] ?? 'rich_text',
                        'name' => $blockData['name'] ?? "Block {$blockIndex}",
                        'order' => $blockData['order'] ?? $blockIndex,
                        'data' => $blockData,  // Store all block data in 'data' field
                        'settings' => $blockData['settings'] ?? null,
                        'style_settings' => $blockData['style_settings'] ?? null,
                        'css_class' => $blockData['css_class'] ?? null,
                        'active' => $blockData['active'] ?? true,
                        'visible_desktop' => $blockData['visible_desktop'] ?? true,
                        'visible_tablet' => $blockData['visible_tablet'] ?? true,
                        'visible_mobile' => $blockData['visible_mobile'] ?? true,
                    ]);
                }
            }

            $this->info("Created section: {$section->name} with " . ($sectionData['blocks'] ? count($sectionData['blocks']) : 0) . " blocks");
        }

        $this->info("Template content synced successfully!");
        return 0;
    }
}
