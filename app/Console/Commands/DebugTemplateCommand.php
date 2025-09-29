<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserTemplate;
use App\Models\Template;
use App\Models\TemplateAssignment;
use App\Models\User;

class DebugTemplateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug template system data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Template System Debug ===');

        // Check admin user
        $adminUser = User::where('email', 'admin@school.local')->first();
        $this->info('Admin User: ' . ($adminUser ? 'Found' : 'Not Found'));

        // Check user templates
        $userTemplates = UserTemplate::with('galleryTemplate')->get();
        $this->info('User Templates Count: ' . $userTemplates->count());

        foreach ($userTemplates as $userTemplate) {
            $this->info("- {$userTemplate->name} (Active: " . ($userTemplate->is_active ? 'Yes' : 'No') . ")");
        }

        // Check templates
        $templates = Template::with('userTemplate')->get();
        $this->info('Templates Count: ' . $templates->count());

        foreach ($templates as $template) {
            $this->info("- {$template->name} (User Template: " . ($template->userTemplate ? $template->userTemplate->name : 'None') . ")");
        }

        // Check template assignments
        $assignments = TemplateAssignment::with('template')->get();
        $this->info('Template Assignments Count: ' . $assignments->count());

        foreach ($assignments as $assignment) {
            $this->info("- Route: {$assignment->route_pattern}, Template: {$assignment->template->name}, Active: " . ($assignment->active ? 'Yes' : 'No'));
        }

        return 0;
    }
}
