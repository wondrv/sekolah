<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TemplateRenderService;
use Symfony\Component\HttpFoundation\Response;

class TemplateRenderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Share template render service with all views
        $templateRenderer = app(TemplateRenderService::class);
        
        view()->share([
            'templateRenderer' => $templateRenderer,
            'themeSettings' => $templateRenderer->getThemeSettings(),
        ]);

        return $next($request);
    }
}
