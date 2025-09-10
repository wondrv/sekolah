<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class EnrollmentController extends Controller
{
    /**
     * Display the enrollment form
     */
    public function show(): View
    {
        $programs = Program::where('is_active', true)->get();

        return view('pages.pendaftaran', compact('programs'));
    }

    /**
     * Handle enrollment form submission
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'address' => 'required|string|max:500',
            'program' => 'required|string|max:100',
            'grade_level' => 'required|string|max:50',
            'previous_school' => 'nullable|string|max:255',
            'parent_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'additional_info' => 'nullable|string|max:1000',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $enrollmentData = $request->except(['documents']);
        $enrollmentData['status'] = 'pending';

        // Handle document uploads
        if ($request->hasFile('documents')) {
            $documentPaths = [];

            foreach ($request->file('documents') as $document) {
                $path = $document->store('enrollments/documents', 'public');
                $documentPaths[] = $path;
            }

            $enrollmentData['documents'] = json_encode($documentPaths);
        }

        Enrollment::create($enrollmentData);

        return redirect()->back()->with('success', 'Pendaftaran Anda telah berhasil dikirim. Kami akan segera menghubungi Anda untuk proses selanjutnya.');
    }
}
