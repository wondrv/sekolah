<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTemplate;
use Illuminate\Support\Facades\URL;

class PublicTemplatePreviewController extends Controller
{
    /**
     * Handle a signed public preview link.
     * Sets session flags so subsequent requests render in preview mode, then redirects.
     */
    public function show(Request $request, UserTemplate $userTemplate)
    {
        // Basic guard: only allow owner template or (future) shared templates. For now ensure template belongs to current authenticated user OR skip (public review) as requirement states external reviewers.
        // We don't require auth, but we ensure the template actually exists and belongs to someone.

        // Prevent using inactive template if not its own preview? Actually preview can be any template regardless of active state.

        // Store session flags
        session([
            'preview_user_template_id' => $userTemplate->id,
            'preview_use_draft' => $request->boolean('draft'),
            'preview_shared_link' => true,
        ]);

        // Optional specific path inside site
        $path = $request->get('path', '/');
        // Ensure leading slash
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }
        $separator = str_contains($path, '?') ? '&' : '?';

        return redirect($path . $separator . 'preview=1');
    }
}
