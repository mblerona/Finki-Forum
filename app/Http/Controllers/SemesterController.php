<?php

namespace App\Http\Controllers;

use App\Models\Semester;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::with(['subjects' => function($query) {
            $query->with('majors')->withCount('threads');
        }])
            ->orderBy('name')
            ->get();

        return view('semesters.index', compact('semesters'));
    }
}
