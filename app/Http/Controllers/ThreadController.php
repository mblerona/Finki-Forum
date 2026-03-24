<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Thread;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    use AuthorizesRequests;
    public function show(Thread $thread)
    {
        $thread->load(['user', 'subject', 'likes', 'dislikes', 'tags']);

        $isLiked = auth()->check()
            ? $thread->likes->contains('user_id', auth()->id())
            : false;

        $isDisliked = auth()->check()
            ? $thread->dislikes->contains('user_id', auth()->id())
            : false;

        $comments = $thread->comments()
            ->with([
                'user',
                'likes',
                'dislikes',
                'replies.user',
                'replies.likes',
                'replies.dislikes',
            ])
            ->withCount(['likes', 'dislikes', 'replies'])
            ->latest()
            ->get();

        return view('threads.show', compact('thread', 'comments', 'isLiked', 'isDisliked'));
    }

    public function create()
    {
        $subjects             = Subject::all();
        $tags                 = \App\Models\Tag::all();
        $preselectedSubjectId = request('subject_id');

        return view('threads.create', compact('subjects', 'tags', 'preselectedSubjectId'));
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

    public function edit(Thread $thread)
    {
        $this->authorize('update', $thread);

        $subjects = Subject::all();
        $tags     = \App\Models\Tag::all();

        return view('threads.edit', compact('thread', 'subjects', 'tags'));
    }

    public function update(Request $request, Thread $thread)
    {
        $this->authorize('update', $thread);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'subject_id'   => ['required', 'exists:subjects,id'],
            'is_anonymous' => ['nullable', 'boolean'],
            'tags'         => ['nullable', 'array'],
            'tags.*'       => ['exists:tags,id'],
        ]);

        $thread->update([
            'title'        => $validated['title'],
            'content'      => $validated['content'],
            'subject_id'   => $validated['subject_id'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
        ]);

        $thread->tags()->sync($request->input('tags', []));

        return redirect()->route('threads.show', $thread)
            ->with('success', 'Thread updated successfully.');
    }

    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);

        $subjectId = $thread->subject_id;
        $thread->delete();

        return redirect()->route('subjects.show', $subjectId)
            ->with('success', 'Thread deleted successfully.');
    }

}
