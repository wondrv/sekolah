<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserTemplate;
use App\Models\TemplateExport;

class UserTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageContent();
    }

    public function view(User $user, UserTemplate $userTemplate): bool
    {
        return $user->canManageContent() && $user->id === $userTemplate->user_id;
    }

    public function create(User $user): bool
    {
        return $user->canManageContent();
    }

    public function update(User $user, UserTemplate $userTemplate): bool
    {
        return $user->canManageContent() && $user->id === $userTemplate->user_id;
    }

    public function delete(User $user, UserTemplate $userTemplate): bool
    {
        return $user->canManageContent() && $user->id === $userTemplate->user_id;
    }

    public function restore(User $user, UserTemplate $userTemplate): bool
    {
        return $user->canManageContent() && $user->id === $userTemplate->user_id;
    }

    public function forceDelete(User $user, UserTemplate $userTemplate): bool
    {
        return $user->canManageSettings() && $user->id === $userTemplate->user_id;
    }
}

class TemplateExportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManageContent();
    }

    public function view(User $user, TemplateExport $export): bool
    {
        return $user->canManageContent() && $user->id === $export->user_id;
    }

    public function delete(User $user, TemplateExport $export): bool
    {
        return $user->canManageContent() && $user->id === $export->user_id;
    }
}
