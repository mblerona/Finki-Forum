<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Semester;
use App\Models\Tag;

class SubjectController extends Controller
{
    public function index()
    {
        $semesters = Semester::orderBy('name')->get();
        $selectedSemester = request('semester');
        $search = request('search');

        $subjects = Subject::with(['semester', 'majors'])
            ->withCount('threads')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($selectedSemester, function($query) use ($selectedSemester) {
                $query->whereHas('semester', function($q) use ($selectedSemester) {
                    $q->where('name', $selectedSemester);
                });
            })
            ->get();

        return view('subjects.index', compact('subjects', 'semesters', 'selectedSemester', 'search'));
    }

    public function show($id)
    {
        $subject = Subject::with([
            'semester',
            'threads.user',
            'threads.likes',
            'threads.replies',
            'threads.tags',
        ])->findOrFail($id);

        $tags = Tag::all();
        $selectedTag = request('tag');
        $threads = $subject->threads;

        if ($selectedTag) {
            $threads = $threads->filter(function ($thread) use ($selectedTag) {
                return $thread->tags->contains('name', $selectedTag);
            });
        }

        return view('subjects.show', compact('subject', 'tags', 'threads', 'selectedTag'));
    }
}
