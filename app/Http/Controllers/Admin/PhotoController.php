<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use Illuminate\Http\RedirectResponse;

class PhotoController extends Controller
{
    /**
     * Remove the specified photo from storage.
     */
    public function destroy(Photo $photo): RedirectResponse
    {
        $gallery = $photo->gallery; // keep reference for redirect
        $photo->delete();

        return redirect()
            ->route('admin.galleries.edit', $gallery)
            ->with('success', 'Foto berhasil dihapus.');
    }
}
