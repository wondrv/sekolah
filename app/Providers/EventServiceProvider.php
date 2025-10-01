<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\TemplateImported;
use App\Listeners\LogTemplateImported;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TemplateImported::class => [
            LogTemplateImported::class,
        ],
    ];
}
