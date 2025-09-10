<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments
     */
    public function index(Request $request)
    {
        $query = Enrollment::with('processedBy')->orderBy('submitted_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_name', 'LIKE', "%{$search}%")
                  ->orWhere('registration_number', 'LIKE', "%{$search}%")
                  ->orWhere('student_nik', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $enrollments = $query->paginate(20);

        // Get unique academic years for filter
        $academicYears = Enrollment::distinct()->pluck('academic_year')->sort();

        return view('admin.enrollments.index', compact('enrollments', 'academicYears'));
    }

    /**
     * Display the specified enrollment
     */
    public function show(Enrollment $enrollment)
    {
        return view('admin.enrollments.show', compact('enrollment'));
    }

    /**
     * Approve an enrollment
     */
    public function approve(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'notes' => 'nullable|string'
        ]);

        $enrollment->approve(Auth::id(), $request->notes);

        return redirect()->route('admin.enrollments.show', $enrollment)
                        ->with('success', 'Enrollment approved successfully!');
    }

    /**
     * Reject an enrollment
     */
    public function reject(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'notes' => 'required|string'
        ]);

        $enrollment->reject(Auth::id(), $request->notes);

        return redirect()->route('admin.enrollments.show', $enrollment)
                        ->with('success', 'Enrollment rejected successfully!');
    }

    /**
     * Update the specified enrollment
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,enrolled',
            'notes' => 'nullable|string'
        ]);

        $enrollment->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'processed_at' => now(),
            'processed_by' => Auth::id()
        ]);

        return redirect()->route('admin.enrollments.index')
                        ->with('success', 'Enrollment updated successfully!');
    }

    /**
     * Remove the specified enrollment
     */
    public function destroy(Enrollment $enrollment)
    {
        // Delete associated files
        $files = [
            $enrollment->birth_certificate_path,
            $enrollment->family_card_path,
            $enrollment->report_card_path,
            $enrollment->photo_path
        ];

        foreach ($files as $file) {
            if ($file && Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
        }

        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')
                        ->with('success', 'Enrollment deleted successfully!');
    }
}
