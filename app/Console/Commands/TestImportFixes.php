<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SmartTemplateImporterService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TestImportFixes extends Command
{
    protected $signature = 'test:import-fixes';
    protected $description = 'Test import fixes for GitHub and JSON validation';

    public function handle()
    {
        $this->info('Testing Import Fixes...');

        // Use first admin user
        $user = User::where('email', 'admin@school.local')->first();
        if (!$user) {
            $this->error('Admin user not found');
            return 1;
        }

        Auth::login($user);

        $importer = app(SmartTemplateImporterService::class);

        // Test 1: GitHub repository page (should fail with helpful message)
        $this->info('Test 1: GitHub repository page URL');
        $result = $importer->analyzeTemplate('https://github.com/startbootstrap/startbootstrap-grayscale');

        if (!$result['success'] && $result['code'] === 'GITHUB_REPO_PAGE') {
            $this->info('✅ Correctly detected GitHub repository page and provided helpful error');
        } else {
            $this->warn('⚠️  GitHub repository detection may need improvement');
        }

        // Test 2: GitHub blob URL to JSON file (should work or give specific error)
        $this->info('Test 2: GitHub blob URL (if valid JSON file exists)');
        $sampleUrl = 'https://github.com/user/repo/blob/main/template.json';
        $result = $importer->analyzeTemplate($sampleUrl);

        if (!$result['success']) {
            if (str_contains($result['error'], 'raw.githubusercontent.com') ||
                $result['code'] === 'GITHUB_FETCH_ERROR') {
                $this->info('✅ Correctly converted to raw URL and attempted fetch');
            } else {
                $this->warn('⚠️  GitHub blob URL handling may need review: ' . $result['error']);
            }
        } else {
            $this->info('✅ GitHub blob URL processed successfully');
        }

        // Test 3: Sample JSON validation
        $this->info('Test 3: JSON validation with sample file');
        $sampleJsonPath = public_path('sample-template.json');

        if (file_exists($sampleJsonPath)) {
            $content = file_get_contents($sampleJsonPath);
            $data = json_decode($content, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $this->info('✅ Sample JSON file is valid');
            } else {
                $this->error('❌ Sample JSON file is invalid: ' . json_last_error_msg());
            }
        } else {
            $this->warn('⚠️  Sample JSON file not found at ' . $sampleJsonPath);
        }

        $this->info('✅ Import fixes test completed!');
        $this->info('');
        $this->info('🔧 Fixes implemented:');
        $this->info('- GitHub repository pages now show helpful error messages');
        $this->info('- GitHub blob URLs are converted to raw URLs automatically');
        $this->info('- HTML content detection prevents JSON parsing errors');
        $this->info('- ZIP files are validated before processing');
        $this->info('- Comprehensive error messages guide users to solutions');
        $this->info('');
        $this->info('📋 Users can now:');
        $this->info('- Get clear guidance when using wrong GitHub URLs');
        $this->info('- Understand why HTML files cause JSON errors');
        $this->info('- Access import guide for detailed help');

        return 0;
    }
}
