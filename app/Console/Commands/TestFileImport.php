<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\Template\SmartImportController;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TestFileImport extends Command
{
    protected $signature = 'test:file-import {type=json}';
    protected $description = 'Test file import functionality';

    public function handle()
    {
        $type = $this->argument('type');

        // Use first admin user
        $user = User::where('email', 'admin@school.local')->first();
        if (!$user) {
            $this->error('Admin user not found');
            return 1;
        }

        Auth::login($user);

        try {
            $controller = app(SmartImportController::class);

            if ($type === 'json') {
                $this->testJsonImport($controller);
            } elseif ($type === 'html') {
                $this->testHtmlImport($controller);
            } else {
                $this->error('Invalid type. Use json or html');
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('Test failed: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }

        return 0;
    }

    protected function testJsonImport($controller)
    {
        $this->info('Testing JSON import...');

        $jsonPath = storage_path('app/test-template.json');
        if (!file_exists($jsonPath)) {
            $this->error('Test JSON file not found: ' . $jsonPath);
            return;
        }

        $file = new UploadedFile(
            $jsonPath,
            'test-template.json',
            'application/json',
            null,
            true
        );

        $request = new \Illuminate\Http\Request();
        $request->files->set('file', $file);
        $request->merge([
            'template_name' => 'CLI Test Template',
            'auto_activate' => false
        ]);

        $response = $controller->importFromFile($request);
        $result = $response->getData(true);

        if ($result['success']) {
            $this->info('✓ JSON import successful!');
            $this->info('Template ID: ' . $result['template']['id']);
            $this->info('Template Name: ' . $result['template']['name']);
        } else {
            $this->error('✗ JSON import failed: ' . $result['error']);
        }
    }

    protected function testHtmlImport($controller)
    {
        $this->info('Testing HTML import...');

        $htmlPath = storage_path('app/test-template.html');
        if (!file_exists($htmlPath)) {
            $this->error('Test HTML file not found: ' . $htmlPath);
            return;
        }

        $file = new UploadedFile(
            $htmlPath,
            'test-template.html',
            'text/html',
            null,
            true
        );

        $request = new \Illuminate\Http\Request();
        $request->files->set('file', $file);
        $request->merge([
            'template_name' => 'CLI Test HTML Template',
            'auto_activate' => false
        ]);

        $response = $controller->importFromFile($request);
        $result = $response->getData(true);

        if ($result['success']) {
            $this->info('✓ HTML import successful!');
            $this->info('Template ID: ' . $result['template']['id']);
            $this->info('Template Name: ' . $result['template']['name']);
        } else {
            $this->error('✗ HTML import failed: ' . $result['error']);
        }
    }
}
