<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\UserTemplate;

class TemplateImported
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public UserTemplate $userTemplate,
        public string $sourceType, // json|html|zip
        public bool $activated,
        public array $meta = []
    ) {}
}
