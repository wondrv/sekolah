<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserTemplate;
use Illuminate\Support\Facades\Auth;

class TemplatePreviewMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hasPreviewSession = $request->session()->has('preview_user_template_id');
        $isShared = $request->session()->get('preview_shared_link');
        $authOk = Auth::check();

        if ($request->query('preview') && $hasPreviewSession && ($authOk || $isShared)) {
            $id = $request->session()->get('preview_user_template_id');
            $userTemplate = UserTemplate::with('templates')->find($id);
            if ($userTemplate) {
                // If draft preview requested and draft exists, temporarily substitute template_data
                if ($request->session()->get('preview_use_draft') && $userTemplate->draft_template_data) {
                    // Clone object to avoid mutating original instance accidentally
                    $clone = clone $userTemplate;
                    $clone->template_data = $userTemplate->draft_template_data;
                    $userTemplate = $clone;
                    view()->share('isDraftPreview', true);
                }
                // Register the preview template instance in the container
                app()->instance('preview.user_template', $userTemplate);
                // Share flag with views
                view()->share('isPreviewMode', true);
                if ($isShared) {
                    view()->share('isSharedPreview', true);
                }
                view()->share('previewUserTemplate', $userTemplate);
            }
        }

        return $next($request);
    }
}
