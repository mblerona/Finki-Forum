@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

    @php
        /** @var \App\Models\User $user */
    @endphp

    <div style="max-width:56rem;margin:0 auto;">

        {{-- Flash success --}}
        @if(session('success'))
            <div style="
            background:rgba(14,165,160,0.1);
            border:1px solid rgba(14,165,160,0.3);
            color:var(--accent);
            border-radius:var(--radius);
            padding:0.75rem 1rem;
            margin-bottom:1.5rem;
            display:flex;
            align-items:center;
            gap:0.5rem;
            font-size:0.875rem;
            font-weight:500;
        ">
                <i data-lucide="check-circle" class="icon-sm"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Profile header --}}
        <div class="card" style="padding:2rem;margin-bottom:1.5rem;">
            <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
                <div style="
                width:5rem;height:5rem;
                border-radius:9999px;
                background:rgba(59,108,245,0.12);
                color:var(--primary);
                display:flex;align-items:center;justify-content:center;
                font-size:1.75rem;font-weight:700;
                flex-shrink:0;
            ">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>

                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;margin-bottom:0.25rem;">
                        <h1 style="font-size:1.5rem;margin:0;">{{ $user->name }}</h1>
                        <span class="badge {{ $user->role === 'admin' ? 'badge-destructive' : 'badge-secondary' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    </div>
                    <p style="color:var(--muted-fg);font-size:0.875rem;margin:0;">{{ $user->email }}</p>
                    <p style="color:var(--muted-fg);font-size:0.8125rem;margin-top:0.25rem;">
                        <i data-lucide="calendar" class="icon-sm" style="display:inline;vertical-align:middle;"></i>
                        Member since {{ $user->created_at->format('F Y') }}
                    </p>
                </div>
            </div>

            {{-- Stats --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--border);">
                <div style="text-align:center;background:var(--secondary);border-radius:var(--radius);padding:1rem;">
                    <div style="font-size:1.75rem;font-weight:700;color:var(--primary);">{{ $threads->count() }}</div>
                    <div style="font-size:0.8125rem;color:var(--muted-fg);margin-top:0.125rem;">Threads</div>
                </div>
                <div style="text-align:center;background:var(--secondary);border-radius:var(--radius);padding:1rem;">
                    <div style="font-size:1.75rem;font-weight:700;color:var(--primary);">{{ $comments->count() }}</div>
                    <div style="font-size:0.8125rem;color:var(--muted-fg);margin-top:0.125rem;">Comments</div>
                </div>
                <div style="text-align:center;background:var(--secondary);border-radius:var(--radius);padding:1rem;">
                    <div style="font-size:1.75rem;font-weight:700;color:var(--primary);">
                        {{ $threads->sum('likes_count') }}
                    </div>
                    <div style="font-size:0.8125rem;color:var(--muted-fg);margin-top:0.125rem;">Likes received</div>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">

            {{-- LEFT: Edit form --}}
            <div class="card" style="padding:1.5rem;">
                <h2 style="margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem;">
                    <i data-lucide="settings" class="icon-md" style="color:var(--primary);"></i>
                    Edit Profile
                </h2>

                @if($errors->any())
                    <div style="
                    background:rgba(229,62,62,0.08);
                    border:1px solid rgba(229,62,62,0.2);
                    border-radius:var(--radius);
                    padding:0.75rem 1rem;
                    margin-bottom:1.25rem;
                    font-size:0.875rem;
                    color:var(--destructive);
                ">
                        @foreach($errors->all() as $error)
                            <div style="display:flex;align-items:center;gap:0.375rem;">
                                <i data-lucide="alert-circle" class="icon-sm"></i> {{ $error }}
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="input"
                               value="{{ old('name', $user->name) }}" required>
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="input"
                               value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div style="border-top:1px solid var(--border);padding-top:1.25rem;margin-bottom:1.25rem;">
                        <p style="font-size:0.8125rem;color:var(--muted-fg);margin-bottom:1rem;">
                            Leave the password fields empty if you don't want to change it.
                        </p>

                        {{-- Current password --}}
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password"
                                   class="input" placeholder="Required only when changing password"
                                   autocomplete="current-password">
                        </div>

                        {{-- New password --}}
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password"
                                   class="input" placeholder="Min. 8 characters"
                                   autocomplete="new-password">
                        </div>

                        {{-- Confirm password --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="input" placeholder="Repeat new password"
                                   autocomplete="new-password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;">
                        <i data-lucide="save" class="icon-sm"></i> Save Changes
                    </button>
                </form>
            </div>

            {{-- RIGHT: Activity --}}
            <div style="display:flex;flex-direction:column;gap:1.5rem;">

                {{-- Recent threads --}}
                <div class="card" style="padding:1.5rem;">
                    <h2 style="margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
                        <i data-lucide="message-square" class="icon-md" style="color:var(--primary);"></i>
                        My Threads
                        <span class="badge badge-secondary" style="margin-left:auto;">{{ $threads->count() }}</span>
                    </h2>

                    @if($threads->isEmpty())
                        <p style="color:var(--muted-fg);font-size:0.875rem;">You haven't posted any threads yet.</p>
                    @else
                        <div style="display:flex;flex-direction:column;gap:0.625rem;">
                            @foreach($threads->take(5) as $thread)
                                <a href="{{ route('threads.show', $thread) }}"
                                   style="display:block;padding:0.75rem;border-radius:var(--radius);background:var(--secondary);text-decoration:none;transition:background 150ms ease;">
                                    <div style="font-size:0.875rem;font-weight:600;color:var(--fg);margin-bottom:0.25rem;
                                     overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        {{ $thread->title }}
                                    </div>
                                    <div style="display:flex;align-items:center;gap:0.75rem;font-size:0.75rem;color:var(--muted-fg);">
                                    <span style="display:flex;align-items:center;gap:0.25rem;">
                                        <i data-lucide="book-open" class="icon-sm"></i>
                                        {{ $thread->subject->name }}
                                    </span>
                                        <span style="display:flex;align-items:center;gap:0.25rem;">
                                        <i data-lucide="heart" class="icon-sm"></i>
                                        {{ $thread->likes_count }}
                                    </span>
                                        <span style="display:flex;align-items:center;gap:0.25rem;">
                                        <i data-lucide="message-circle" class="icon-sm"></i>
                                        {{ $thread->comments_count }}
                                    </span>
                                        <span style="margin-left:auto;">{{ $thread->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @endforeach

                            @if($threads->count() > 5)
                                <p style="font-size:0.8125rem;color:var(--muted-fg);text-align:center;margin-top:0.25rem;">
                                    + {{ $threads->count() - 5 }} more threads
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Recent comments --}}
                <div class="card" style="padding:1.5rem;">
                    <h2 style="margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
                        <i data-lucide="message-circle" class="icon-md" style="color:var(--primary);"></i>
                        My Comments
                        <span class="badge badge-secondary" style="margin-left:auto;">{{ $comments->count() }}</span>
                    </h2>

                    @if($comments->isEmpty())
                        <p style="color:var(--muted-fg);font-size:0.875rem;">You haven't commented yet.</p>
                    @else
                        <div style="display:flex;flex-direction:column;gap:0.625rem;">
                            @foreach($comments->take(5) as $comment)
                                <a href="{{ route('threads.show', $comment->thread) }}"
                                   style="display:block;padding:0.75rem;border-radius:var(--radius);background:var(--secondary);text-decoration:none;transition:background 150ms ease;">
                                    <div style="font-size:0.8125rem;color:var(--fg);margin-bottom:0.375rem;
                                     display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                        {{ $comment->content }}
                                    </div>
                                    <div style="display:flex;align-items:center;gap:0.75rem;font-size:0.75rem;color:var(--muted-fg);">
                                    <span style="display:flex;align-items:center;gap:0.25rem;">
                                        <i data-lucide="corner-up-right" class="icon-sm"></i>
                                        {{ $comment->thread->title }}
                                    </span>
                                        <span style="margin-left:auto;">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @endforeach

                            @if($comments->count() > 5)
                                <p style="font-size:0.8125rem;color:var(--muted-fg);text-align:center;margin-top:0.25rem;">
                                    + {{ $comments->count() - 5 }} more comments
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

@endsection
