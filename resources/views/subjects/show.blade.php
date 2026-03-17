@extends('layouts.app')

@section('title', $subject->name)

@section('content')

    <section class="section">
        <div class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <div>
                    <div class="thread-badges" style="margin-bottom: 0.75rem;">
                        <span class="badge badge-outline">{{ $subject->semester->name }}</span>
                    </div>

                    <h1 style="margin-bottom: 0.5rem;">{{ $subject->name }}</h1>
                    <p style="color: var(--muted-foreground);">
                        Browse discussions, experiences, and shared resources for this subject.
                    </p>
                </div>

                <div>
                    <a href="{{ route('subjects.index') }}" class="btn btn-outline btn-sm">
                        <i data-lucide="arrow-left" class="icon-sm"></i>
                        Back to Subjects
                    </a>
                </div>
            </div>
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;gap:1rem;flex-wrap:wrap;">
            <h2>Threads</h2>

            @auth
                <a href="{{ route('threads.create') }}" class="btn btn-primary btn-sm">
                    <i data-lucide="plus" class="icon-sm"></i>
                    New Thread
                </a>
            @endauth
        </div>

        {{-- Tag filter --}}
        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1.5rem;align-items:center;">
            <span style="font-size:0.875rem;color:var(--muted-fg);">Filter by tag:</span>

            <a href="{{ route('subjects.show', $subject->id) }}"
               class="badge {{ !$selectedTag ? 'badge-primary' : 'badge-secondary' }}"
               style="cursor:pointer;padding:0.375rem 0.75rem;text-decoration:none;">
                All
            </a>

            @foreach($tags as $tag)
                <a href="{{ route('subjects.show', $subject->id) }}?tag={{ $tag->name }}"
                   class="badge {{ $selectedTag === $tag->name ? 'badge-primary' : 'badge-secondary' }}"
                   style="cursor:pointer;padding:0.375rem 0.75rem;text-decoration:none;">
                    {{ $tag->name }}
                </a>
            @endforeach
        </div>

        @if($threads->isEmpty())
            <div class="card" style="padding: 1.5rem;">
                <h3>No threads yet</h3>
                <p>
                    {{ $selectedTag ? 'No threads with the tag "' . $selectedTag . '".' : 'This subject does not have any discussions yet.' }}
                </p>
            </div>
        @else
            <div class="stack-sm">
                @foreach($threads as $thread)
                    <div class="card thread-card" style="padding: 1rem;">
                        <div class="thread-content">
                            <div class="thread-badges">
                                <span class="badge badge-outline">{{ $subject->name }}</span>
                            </div>

                            @if($thread->tags->isNotEmpty())
                                <div class="thread-tags" style="margin-bottom: 0.5rem;">
                                    @foreach($thread->tags as $tag)
                                        <span class="badge badge-primary">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <h3 style="margin-bottom: 0.5rem;">
                                <a href="{{ route('threads.show', $thread->id) }}" style="text-decoration:none;color:inherit;">
                                    {{ $thread->title }}
                                </a>
                            </h3>

                            <p class="thread-excerpt line-clamp-2">
                                {{ $thread->content }}
                            </p>

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
                                <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                                    <span class="post-stat">
                                        <i data-lucide="heart" class="icon"></i>
                                        {{ $thread->likes->count() }} likes
                                    </span>

                                    <span class="post-stat">
                                        <i data-lucide="message-square" class="icon"></i>
                                        {{ $thread->replies->count() }} comments
                                    </span>
                                </div>

                                <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                                    @auth
                                        @php
                                            $isLiked = $thread->likes->contains('user_id', auth()->id());
                                        @endphp

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
                                    @endauth

                                    <a href="{{ route('threads.show', $thread->id) }}#reply-form-wrapper" class="btn btn-outline btn-sm">
                                        <i data-lucide="message-circle" class="icon-sm"></i>
                                        Comment
                                    </a>

                                    <a href="{{ route('threads.show', $thread->id) }}" class="btn btn-primary btn-sm">
                                        Open
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

@endsection
