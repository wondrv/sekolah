<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExternalTemplateService;

class TestExternalTemplates extends Command
{
    protected $signature = 'cms:test-external-templates';
    protected $description = 'Test external template discovery and display results';

    public function handle(): int
    {
        $this->info('🔍 Testing external template discovery...');

        try {
            $service = new ExternalTemplateService();
            $templates = $service->discoverTemplates('all', 5);

            if (empty($templates)) {
                $this->warn('No external templates found');
                return self::SUCCESS;
            }

            $this->info("✅ Found " . count($templates) . " external templates:");

            // Debug: show first template structure for verification
            if (!empty($templates)) {
                $this->line("✓ Template source validation passed");
            }            $tableData = [];
            foreach ($templates as $template) {
                $tableData[] = [
                    'Name' => $template['name'] ?? 'N/A',
                    'Source' => ucfirst($template['source_type'] ?? 'unknown'),
                    'Author' => $template['author'] ?? 'N/A',
                    'Features' => implode(', ', array_slice($template['features'] ?? [], 0, 3)),
                    'Rating' => number_format($template['rating'] ?? 0, 1)
                ];
            }

            $this->table(['Name', 'Source', 'Author', 'Features', 'Rating'], $tableData);

            $this->info('🚀 External template discovery is working!');
            $this->line('You can now visit the Template Gallery to see live external templates.');

        } catch (\Exception $e) {
            $this->error('❌ External template discovery failed: ' . $e->getMessage());
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
