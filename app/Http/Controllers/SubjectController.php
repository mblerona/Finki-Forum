<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Tag;

class SubjectController extends Controller
{
    public function index()
    {
        $semesters       = Semester::orderBy('name')->get();
        $majors          = Major::orderBy('code')->get();
        $selectedSemester = request('semester');
        $selectedMajor   = request('major');
        $search          = request('search');

        $subjects = Subject::with(['semester', 'majors'])
            ->withCount('threads')
            ->when($search, function ($query) use ($search) {
                // Split on whitespace and require every word to appear somewhere in the name
                $words = preg_split('/\s+/', trim($search), -1, PREG_SPLIT_NO_EMPTY);
                foreach ($words as $word) {
                    $query->where('name', 'like', '%' . $word . '%');
                }
            })
            ->when($selectedSemester, function ($query) use ($selectedSemester) {
                $query->whereHas('semester', function ($q) use ($selectedSemester) {
                    $q->where('name', $selectedSemester);
                });
            })
            ->when($selectedMajor, function ($query) use ($selectedMajor) {
                $query->whereHas('majors', function ($q) use ($selectedMajor) {
                    $q->where('majors.id', $selectedMajor);
                });
            })
            ->get();

        return view('subjects.index', compact(
            'subjects', 'semesters', 'majors',
            'selectedSemester', 'selectedMajor', 'search'
        ));
    }

    public function show($id)
    {
        $subject = Subject::with([
            'semester',
            'threads.user',
            'threads.likes',
            'threads.dislikes',
            'threads.comments',
            'threads.tags',
        ])->findOrFail($id);

        $tags        = Tag::all();
        $selectedTag = request('tag');

        $threads = $subject->threads;

        if ($selectedTag) {
            $threads = $threads->filter(function ($thread) use ($selectedTag) {
                return $thread->tags->contains('name', $selectedTag);
            });
        }

        $threads->loadCount(['likes', 'dislikes', 'comments']);

        return view('subjects.show', compact('subject', 'tags', 'threads', 'selectedTag'));
    }
}
