<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Thread;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return redirect()->route('home');
        }

        $words = preg_split('/\s+/', trim($query), -1, PREG_SPLIT_NO_EMPTY);

        $subjects = Subject::with(['semester', 'majors'])
            ->withCount('threads')
            ->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->where('name', 'like', '%' . $word . '%');
                }
            })
            ->get();

        $threads = Thread::with(['subject', 'user', 'likes', 'replies', 'tags'])
            ->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->where(function ($inner) use ($word) {
                        $inner->where('title', 'like', '%' . $word . '%')
                            ->orWhere('content', 'like', '%' . $word . '%');
                    });
                }
            })
            ->latest()
            ->get();

        return view('search.results', compact('query', 'subjects', 'threads'));
    }
}
