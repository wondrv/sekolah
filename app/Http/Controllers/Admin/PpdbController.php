<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbSetting;
use App\Models\PpdbCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PpdbController extends Controller
{
    /**
     * Display PPDB settings dashboard
     */
    public function index()
    {
        $settings = [
            'brochure_enabled' => PpdbSetting::get('brochure_enabled', true),
            'brochure_title' => PpdbSetting::get('brochure_title', 'Download Brosur PPDB'),
            'brochure_description' => PpdbSetting::get('brochure_description', 'Unduh brosur resmi Penerimaan Peserta Didik Baru untuk informasi lengkap.'),
            'brochure_file' => PpdbSetting::get('brochure_file'),
            'brochure_size' => PpdbSetting::get('brochure_size', '2.5 MB'),
            'brochure_format' => PpdbSetting::get('brochure_format', 'PDF'),
            'costs_enabled' => PpdbSetting::get('costs_enabled', true),
            'costs_title' => PpdbSetting::get('costs_title', 'Rincian Biaya PPDB'),
            'costs_description' => PpdbSetting::get('costs_description', 'Berikut adalah rincian biaya untuk Penerimaan Peserta Didik Baru.'),
        ];

        $costs = PpdbCost::active()->orderBy('category')->orderBy('sort_order')->orderBy('item_name')->get();
        $academic_year = PpdbCost::getCurrentAcademicYear();

        return view('admin.ppdb.index', compact('settings', 'costs', 'academic_year'));
    }

    /**
     * Update PPDB settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'brochure_enabled' => 'boolean',
            'brochure_title' => 'required|string|max:255',
            'brochure_description' => 'required|string',
            'brochure_file' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'brochure_size' => 'nullable|string|max:50',
            'brochure_format' => 'required|string|max:10',
            'costs_enabled' => 'boolean',
            'costs_title' => 'required|string|max:255',
            'costs_description' => 'required|string',
        ]);

        // Update basic settings
        PpdbSetting::set('brochure_enabled', $request->boolean('brochure_enabled'));
        PpdbSetting::set('brochure_title', $request->brochure_title);
        PpdbSetting::set('brochure_description', $request->brochure_description);
        PpdbSetting::set('brochure_format', $request->brochure_format);
        PpdbSetting::set('costs_enabled', $request->boolean('costs_enabled'));
        PpdbSetting::set('costs_title', $request->costs_title);
        PpdbSetting::set('costs_description', $request->costs_description);

        // Handle file upload
        if ($request->hasFile('brochure_file')) {
            // Delete old file if exists
            $oldFile = PpdbSetting::get('brochure_file');
            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            // Store new file
            $file = $request->file('brochure_file');
            $filename = 'ppdb/brochure_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('', $filename, 'public');
            
            PpdbSetting::set('brochure_file', $path);
            PpdbSetting::set('brochure_size', round($file->getSize() / 1024 / 1024, 1) . ' MB');
        } elseif ($request->filled('brochure_size')) {
            PpdbSetting::set('brochure_size', $request->brochure_size);
        }

        return redirect()->route('admin.ppdb.index')->with('success', 'Pengaturan PPDB berhasil diperbarui!');
    }

    /**
     * Display cost management page
     */
    public function costs()
    {
        $costs = PpdbCost::orderBy('category')->orderBy('sort_order')->orderBy('item_name')->get();
        $categories = PpdbCost::distinct('category')->pluck('category')->filter();
        $academic_year = PpdbCost::getCurrentAcademicYear();

        return view('admin.ppdb.costs', compact('costs', 'categories', 'academic_year'));
    }

    /**
     * Store new cost item
     */
    public function storeCost(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'academic_year' => 'nullable|string|max:10',
        ]);

        PpdbCost::create([
            'item_name' => $request->item_name,
            'description' => $request->description,
            'amount' => $request->amount,
            'category' => $request->category,
            'is_mandatory' => $request->boolean('is_mandatory'),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
            'academic_year' => $request->academic_year ?? PpdbCost::getCurrentAcademicYear(),
        ]);

        return redirect()->route('admin.ppdb.costs')->with('success', 'Item biaya berhasil ditambahkan!');
    }

    /**
     * Update cost item
     */
    public function updateCost(Request $request, PpdbCost $cost)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'academic_year' => 'nullable|string|max:10',
        ]);

        $cost->update([
            'item_name' => $request->item_name,
            'description' => $request->description,
            'amount' => $request->amount,
            'category' => $request->category,
            'is_mandatory' => $request->boolean('is_mandatory'),
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $request->sort_order ?? 0,
            'academic_year' => $request->academic_year ?? $cost->academic_year,
        ]);

        return redirect()->route('admin.ppdb.costs')->with('success', 'Item biaya berhasil diperbarui!');
    }

    /**
     * Delete cost item
     */
    public function destroyCost(PpdbCost $cost)
    {
        $cost->delete();
        return redirect()->route('admin.ppdb.costs')->with('success', 'Item biaya berhasil dihapus!');
    }
}
