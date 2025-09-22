<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Services\TemplateRenderService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    protected $templateRenderer;

    public function __construct(TemplateRenderService $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * Display the contact form
     */
    public function show(Request $request)
    {
        // Try to render using template assignment system
        $templateView = $this->templateRenderer->renderForRequest('pages.kontak', []);

        if ($templateView) {
            return $templateView;
        }

        return view('pages.kontak');
    }

    /**
     * Handle contact form submission
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'phone' => 'nullable|string|max:20',
        ]);

        Message::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'type' => 'contact',
            'status' => 'unread',
        ]);

        return redirect()->back()->with('success', 'Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.');
    }
}
