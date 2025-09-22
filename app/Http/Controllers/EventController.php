<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\TemplateRenderService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $templateRenderer;

    public function __construct(TemplateRenderService $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * Display a listing of events
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('starts_at', $request->month);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('starts_at', $request->year);
        }

        $events = $query->orderBy('starts_at', 'asc')->paginate(20);

        // Try to render using template assignment system
        $templateView = $this->templateRenderer->renderForRequest('events.index', compact('events'));

        if ($templateView) {
            return $templateView;
        }

        return view('events.index', compact('events'));
    }

    /**
     * Display the specified event
     */
    public function show(Request $request, Event $event)
    {
        // Try to render using template assignment system
        $templateView = $this->templateRenderer->renderForRequest('events.show', compact('event'));

        if ($templateView) {
            return $templateView;
        }

        return view('events.show', compact('event'));
    }

    /**
     * Get upcoming events for homepage
     */
    public function upcoming($limit = 5)
    {
        return Event::where('starts_at', '>=', now())
            ->orderBy('starts_at', 'asc')
            ->limit($limit)
            ->get();
    }
}
