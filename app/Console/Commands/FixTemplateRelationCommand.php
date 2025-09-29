<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserTemplate;
use App\Models\Template;

class FixTemplateRelationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:template-relation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix template relation with user template';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userTemplate = UserTemplate::first();
        $template = Template::first();

        if ($userTemplate && $template) {
            $template->user_template_id = $userTemplate->id;
            $template->save();

            $this->info('Template relation fixed successfully!');
            $this->info("Template '{$template->name}' now connected to UserTemplate '{$userTemplate->name}'");
        } else {
            $this->error('UserTemplate or Template not found');
        }

        return 0;
    }
}
