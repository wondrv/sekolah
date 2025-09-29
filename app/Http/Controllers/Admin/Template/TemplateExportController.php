<?php

namespace App\Http\Controllers\Admin\Template;

use App\Http\Controllers\Controller;
use App\Models\TemplateExport;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TemplateExportController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = TemplateExport::with(['userTemplate'])
            ->where('user_id', Auth::id());

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'expired') {
                $query->expired();
            }
        }

        // Filter by format
        if ($request->has('format') && $request->get('format')) {
            $query->where('format', $request->get('format'));
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('filename', 'like', "%{$search}%")
                  ->orWhereHas('userTemplate', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $exports = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->all());

        $stats = [
            'total' => TemplateExport::where('user_id', Auth::id())->count(),
            'active' => TemplateExport::where('user_id', Auth::id())->active()->count(),
            'expired' => TemplateExport::where('user_id', Auth::id())->expired()->count(),
        ];

        return view('admin.templates.exports.index', compact('exports', 'stats', 'request'));
    }

    public function download(TemplateExport $export)
    {
        $this->authorize('view', $export);

        if ($export->isExpired()) {
            return redirect()->back()->with('error', 'Export sudah kadaluarsa.');
        }

        if (!Storage::exists($export->file_path)) {
            return redirect()->back()->with('error', 'File export tidak ditemukan.');
        }

        return Storage::download($export->file_path, $export->filename);
    }

    public function destroy(TemplateExport $export)
    {
        $this->authorize('delete', $export);

        try {
            $export->delete(); // File deletion handled in model boot method

            return redirect()->back()->with('success', 'Export berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus export: ' . $e->getMessage());
        }
    }

    public function cleanupExpired()
    {
        try {
            $deletedCount = 0;
            $expiredExports = TemplateExport::expired()
                ->where('user_id', Auth::id())
                ->get();

            foreach ($expiredExports as $export) {
                $export->delete();
                $deletedCount++;
            }

            return redirect()->back()->with('success', "Berhasil menghapus {$deletedCount} export yang kadaluarsa.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membersihkan export: ' . $e->getMessage());
        }
    }

    public function bulkDownload(Request $request)
    {
        $request->validate([
            'export_ids' => 'required|array',
            'export_ids.*' => 'exists:template_exports,id',
        ]);

        try {
            $exports = TemplateExport::whereIn('id', $request->export_ids)
                ->where('user_id', Auth::id())
                ->active()
                ->get();

            if ($exports->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada export yang valid dipilih.');
            }

            if ($exports->count() === 1) {
                return $this->download($exports->first());
            }

            // Create bulk download zip
            return $this->createBulkDownload($exports);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat bulk download: ' . $e->getMessage());
        }
    }

    protected function createBulkDownload($exports)
    {
        $zipFilename = 'template-exports-' . date('Y-m-d-H-i-s') . '.zip';
        $zipPath = 'temp/' . $zipFilename;
        $fullZipPath = Storage::path($zipPath);

        // Ensure temp directory exists
        Storage::makeDirectory('temp');

        $zip = new \ZipArchive();
        if ($zip->open($fullZipPath, \ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Cannot create bulk download zip');
        }

        foreach ($exports as $export) {
            if (Storage::exists($export->file_path) && !$export->isExpired()) {
                $zip->addFile(
                    Storage::path($export->file_path),
                    $export->filename
                );
            }
        }

        $zip->close();

        // Return download response and schedule cleanup
        return Response::download($fullZipPath, $zipFilename)->deleteFileAfterSend(true);
    }
}
