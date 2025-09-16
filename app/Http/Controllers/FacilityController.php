<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Display a listing of facilities
     */
    public function index()
    {
        $facilities = Facility::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('facilities.index', compact('facilities'));
    }

    /**
     * Display the specified facility
     */
    public function show(Facility $facility)
    {
        if (!$facility->is_active) {
            abort(404);
        }

        return view('facilities.show', compact('facility'));
    }
}
