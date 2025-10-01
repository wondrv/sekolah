<?php

namespace App\Listeners;

use App\Events\TemplateImported;
use Illuminate\Support\Facades\Log;

class LogTemplateImported
{
    public function handle(TemplateImported $event): void
    {
        $data = [
            'user_template_id' => $event->userTemplate->id,
            'user_id' => $event->userTemplate->user_id,
            'slug' => $event->userTemplate->slug,
            'source_type' => $event->sourceType,
            'activated' => $event->activated,
        ] + $event->meta;
        Log::info('TemplateImported event', $data);
    }
}
