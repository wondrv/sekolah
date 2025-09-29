<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Category::class => \App\Policies\CategoryPolicy::class,
        \App\Models\UserTemplate::class => \App\Policies\UserTemplatePolicy::class,
        \App\Models\TemplateExport::class => \App\Policies\UserTemplatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('manage-settings', function (User $user) {
            return $user->canManageSettings();
        });

        Gate::define('manage-content', function (User $user) {
            return $user->canManageContent();
        });

        Gate::define('admin-access', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('editor-access', function (User $user) {
            return $user->isEditor() || $user->isAdmin();
        });
    }
}
