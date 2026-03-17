<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
//    public function show($id)
//    {
//        $thread = Thread::with(['user', 'subject.semester'])->findOrFail($id);
//
//        return view('threads.show', compact('thread'));
//    }
    public function show(Thread $thread)
    {
        $thread->load(['replies.user', 'likes', 'tags']);

        $isLiked = auth()->check()
            ? $thread->likes->contains('user_id', auth()->id())
            : false;

        return view('threads.show', compact('thread', 'isLiked'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $tags = \App\Models\Tag::all();
        return view('threads.create', compact('subjects', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|max:255',
            'content'    => 'required',
            'subject_id' => 'required|exists:subjects,id',
            'file'       => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,doc,docx,xls,xlsx|max:10240',
            'tags'       => 'nullable|array',
            'tags.*'     => 'exists:tags,id',
        ]);

        $filePath = null;
        $fileName = null;

        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('thread-files', 'public');
        }

        $thread = Thread::create([
            'title'        => $request->input('title'),
            'content'      => $request->input('content'),
            'subject_id'   => $request->input('subject_id'),
            'user_id'      => auth()->id(),
            'file_path'    => $filePath,
            'file_name'    => $fileName,
            'is_anonymous' => $request->boolean('anonymous'),
        ]);

        if ($request->has('tags')) {
            $thread->tags()->attach($request->input('tags'));
        }

        return redirect()->route('threads.show', $thread->id);
    }


}
