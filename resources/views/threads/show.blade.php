@extends('layouts.app')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', $thread->title)

@section('content')

    <section class="section">
        <div style="margin-bottom: 1rem;">
            <a href="{{ route('subjects.show', $thread->subject->id) }}" class="btn btn-ghost btn-sm">
                <i data-lucide="arrow-left" class="icon-sm"></i>
                Back to {{ $thread->subject->name }}
            </a>
        </div>

        <div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
            <div class="thread-badges" style="margin-bottom: 1rem;">
                <span class="badge badge-outline">{{ $thread->subject->name }}</span>
                <span class="badge badge-secondary">{{ $thread->subject->semester->name }}</span>
            </div>

            <h1 style="margin-bottom: 1rem;">{{ $thread->title }}</h1>

            @if($thread->tags->isNotEmpty())
                <div class="thread-tags" style="margin-bottom: 1rem;">
                    @foreach($thread->tags as $tag)
                        <span class="badge badge-primary">{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif

            <p style="margin-bottom: 1.5rem; white-space: pre-line;">
                {{ $thread->content }}
            </p>

            @if($thread->file_path)
                <div class="post-attachments">
                    <h4>
                        <i data-lucide="paperclip" class="icon-sm"></i>
                        Attachment
                    </h4>

                    @php
                        $ext = strtolower(pathinfo($thread->file_name, PATHINFO_EXTENSION));
                        $icon = match(true) {
                            $ext === 'pdf'                               => 'file-text',
                            in_array($ext, ['jpg','jpeg','png','gif'])   => 'image',
                            in_array($ext, ['doc','docx'])               => 'file',
                            in_array($ext, ['xls','xlsx'])               => 'file-spreadsheet',
                            default                                      => 'paperclip',
                        };
                    @endphp

                    <a href="{{ Storage::url($thread->file_path) }}" target="_blank" class="attachment-item">
                        <i data-lucide="{{ $icon }}" class="icon-sm"></i>
                        {{ $thread->file_name }}
                    </a>
                </div>
            @endif

            <div class="thread-meta" style="margin-bottom: 1rem;">
                <span class="thread-meta-item">
                    @if($thread->is_anonymous)
                        <span class="avatar avatar-sm avatar-primary">AN</span>
                        Anonymous
                    @else
                        <span class="avatar avatar-sm avatar-primary">
                            {{ strtoupper(substr($thread->user->name, 0, 2)) }}
                        </span>
                        {{ $thread->user->name }}
                    @endif
                </span>

                <span class="thread-meta-item">
                    <i data-lucide="clock" class="icon-sm"></i>
                    {{ $thread->created_at->diffForHumans() }}
                </span>
            </div>

            <div class="post-actions" style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;border-top:1px solid var(--border);padding-top:1rem;">
                <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                    @auth
                        <form action="{{ route('threads.like', $thread) }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-sm">
                                <i
                                    data-lucide="heart"
                                    class="icon-sm"
                                    style="{{ $isLiked ? 'fill: currentColor; color: #e11d48;' : '' }}"
                                ></i>
                                {{ $isLiked ? 'Liked' : 'Like' }}
                            </button>
                        </form>

                        <button
                            type="button"
                            id="toggle-reply-form"
                            class="btn btn-primary btn-sm"
                        >
                            <i data-lucide="message-circle" class="icon-sm"></i>
                            Comment
                        </button>
                    @endauth
                </div>

                <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                    <span class="post-stat">
                        <i data-lucide="heart" class="icon"></i>
                        {{ $thread->likes->count() }} likes
                    </span>

                    <span class="post-stat">
                        <i data-lucide="message-square" class="icon"></i>
                        {{ $thread->replies->count() }} replies
                    </span>
                </div>
            </div>
        </div>

        @auth
            <div id="reply-form-wrapper" class="card" style="padding: 1.5rem; margin-bottom: 1.5rem; display: none;">
                <h2 style="margin-bottom: 1rem;">Add a Comment</h2>

                <form action="{{ route('replies.store', $thread) }}" method="POST">
                    @csrf

                    <textarea
                        name="content"
                        class="textarea"
                        rows="4"
                        placeholder="Write your comment here..."
                        required
                    >{{ old('content') }}</textarea>

                    @error('content')
                    <p style="color: var(--destructive); margin-top: 0.5rem;">{{ $message }}</p>
                    @enderror

                    <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:1rem;">
                        <button type="button" id="cancel-reply-form" class="btn btn-outline btn-sm">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-primary btn-sm">
                            <i data-lucide="send" class="icon-sm"></i>
                            Add Comment
                        </button>
                    </div>
                </form>
            </div>
        @endauth

        <div class="card" style="padding: 1.5rem;">
            <h2 style="margin-bottom: 1rem;">Comments ({{ $thread->replies->count() }})</h2>

            @if($thread->replies->isEmpty())
                <p>No comments yet.</p>
            @else
                <div class="stack-sm">
                    @foreach($thread->replies as $reply)
                        <div class="card" style="padding: 1rem;">
                            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
                                <span class="avatar avatar-sm avatar-primary">
                                    {{ strtoupper(substr($reply->user->name, 0, 2)) }}
                                </span>

                                <div>
                                    <strong>{{ $reply->user->name }}</strong>
                                    <div style="font-size:0.875rem;color:var(--muted-fg);">
                                        {{ $reply->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            <p style="white-space: pre-line; margin: 0;">
                                {{ $reply->content }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </section>

    @auth
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toggleButton = document.getElementById('toggle-reply-form');
                const cancelButton = document.getElementById('cancel-reply-form');
                const formWrapper  = document.getElementById('reply-form-wrapper');

                if (toggleButton && formWrapper) {
                    toggleButton.addEventListener('click', function () {
                        formWrapper.style.display = 'block';
                        const textarea = formWrapper.querySelector('textarea');
                        if (textarea) textarea.focus();
                    });
                }

                if (cancelButton && formWrapper) {
                    cancelButton.addEventListener('click', function () {
                        formWrapper.style.display = 'none';
                    });
                }
            });
        </script>
    @endauth

@endsection
