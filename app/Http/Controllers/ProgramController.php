<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of programs
     */
    public function index()
    {
        $programs = Program::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('programs.index', compact('programs'));
    }

    /**
     * Display the specified program
     */
    public function show(Program $program)
    {
        if (!$program->is_active) {
            abort(404);
        }

        return view('programs.show', compact('program'));
    }
}
