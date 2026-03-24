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

            <p style="margin-bottom: 1.5rem; white-space: pre-line;">{{ $thread->content }}</p>

            @if($thread->file_path)
                <div class="post-attachments" style="margin-bottom: 1.5rem;">
                    <h4><i data-lucide="paperclip" class="icon-sm"></i> Attachment</h4>
                    @php
                        $ext  = strtolower(pathinfo($thread->file_name, PATHINFO_EXTENSION));
                        $icon = match(true) {
                            $ext === 'pdf'                              => 'file-text',
                            in_array($ext, ['jpg','jpeg','png','gif']) => 'image',
                            in_array($ext, ['doc','docx'])             => 'file',
                            in_array($ext, ['xls','xlsx'])             => 'file-spreadsheet',
                            default                                    => 'paperclip',
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
                        <span class="avatar avatar-sm avatar-primary">AN</span> Anonymous
                    @else
                        <span class="avatar avatar-sm avatar-primary">{{ strtoupper(substr($thread->user->name, 0, 2)) }}</span>
                        {{ $thread->user->name }}
                    @endif
                </span>
                <span class="thread-meta-item">
                    <i data-lucide="clock" class="icon-sm"></i>
                    {{ $thread->created_at->diffForHumans() }}
                </span>
            </div>

            {{-- Edit / Delete buttons --}}
            @auth
                @php
                    $isOwner = auth()->id() === $thread->user_id;
                    $isAdmin = auth()->user()->role === 'admin';
                @endphp
                @if($isOwner || $isAdmin)
                    <div style="display:flex;justify-content:flex-end;gap:0.5rem;margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid var(--border);">
                        @if($isOwner)
                            <a href="{{ route('threads.edit', $thread) }}" class="btn btn-outline btn-sm">
                                <i data-lucide="pencil" class="icon-sm"></i> Edit
                            </a>
                        @endif
                        <form
                            action="{{ route('threads.destroy', $thread) }}"
                            method="POST"
                            style="margin:0;"
                            onsubmit="return confirm('Delete this thread? This cannot be undone.');"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-destructive btn-sm">
                                <i data-lucide="trash-2" class="icon-sm"></i> Delete
                            </button>
                        </form>
                    </div>
                @endif
            @endauth

            {{-- Like / Dislike / Comment count --}}
            <div class="post-actions" style="display:flex;gap:0.75rem;flex-wrap:wrap;border-top:1px solid var(--border);padding-top:1rem;">
                @auth
                    <button type="button" class="btn btn-outline btn-sm reaction-btn"
                            data-url="/threads/{{ $thread->id }}/like"
                            data-kind="like"
                            data-group="thread-{{ $thread->id }}">
                        <i data-lucide="heart" class="icon-sm reaction-icon reaction-like-icon"
                           style="{{ $isLiked ? 'fill:currentColor;color:#e11d48;' : '' }}"></i>
                        <span class="reaction-count reaction-like-count">{{ $thread->likes->count() }}</span>
                    </button>

                    <button type="button" class="btn btn-outline btn-sm reaction-btn"
                            data-url="/threads/{{ $thread->id }}/dislike"
                            data-kind="dislike"
                            data-group="thread-{{ $thread->id }}">
                        <i data-lucide="thumbs-down" class="icon-sm reaction-icon reaction-dislike-icon"
                           style="{{ $isDisliked ? 'color:#2563eb;' : '' }}"></i>
                        <span class="reaction-count reaction-dislike-count">{{ $thread->dislikes->count() }}</span>
                    </button>

                    <button type="button" id="toggle-comment-form" class="btn btn-outline btn-sm">
                        <i data-lucide="message-circle" class="icon-sm"></i>
                        {{ $comments->count() }}
                    </button>
                @endauth
            </div>
        </div>

        {{-- Add comment form --}}
        @auth
            <div id="comment-form-wrapper" class="card" style="padding:1.5rem;margin-bottom:1.5rem;display:none;">
                <h2 style="margin-bottom:1rem;">Add a Comment</h2>
                <form action="{{ route('comments.store', $thread) }}" method="POST">
                    @csrf
                    <textarea name="content" class="textarea" rows="4"
                              placeholder="Write your comment here..." required>{{ old('content') }}</textarea>
                    @error('content')
                    <p style="color:var(--destructive);margin-top:0.5rem;">{{ $message }}</p>
                    @enderror
                    <div style="margin-top:1rem;">
                        <label style="display:flex;align-items:center;gap:0.5rem;">
                            <input type="checkbox" name="is_anonymous" value="1" {{ old('is_anonymous') ? 'checked' : '' }}>
                            Post anonymously
                        </label>
                    </div>
                    <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:1rem;">
                        <button type="button" id="cancel-comment-form" class="btn btn-outline btn-sm">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i data-lucide="send" class="icon-sm"></i> Add Comment
                        </button>
                    </div>
                </form>
            </div>
        @endauth

        {{-- Comments list --}}
        <div class="card" style="padding:1.5rem;">
            <h2 style="margin-bottom:1rem;">Comments ({{ $comments->count() }})</h2>

            @if($comments->isEmpty())
                <p>No comments yet.</p>
            @else
                <div class="stack-sm">
                    @foreach($comments as $comment)
                        @php
                            $commentLiked    = auth()->check() && $comment->likes->contains('user_id', auth()->id());
                            $commentDisliked = auth()->check() && $comment->dislikes->contains('user_id', auth()->id());
                            $canEditComment  = auth()->check() && auth()->id() === $comment->user_id;
                            $canDeleteComment= auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->role === 'admin');
                        @endphp

                        <div class="card" style="padding:1rem;">
                            {{-- Comment header --}}
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:0.75rem;flex-wrap:wrap;">
                                <div style="display:flex;align-items:center;gap:0.75rem;">
                                    @if($comment->is_anonymous)
                                        <span class="avatar avatar-sm avatar-primary">AN</span>
                                        <div>
                                            <strong>Anonymous</strong>
                                            <div style="font-size:0.875rem;color:var(--muted-fg);">{{ $comment->created_at->diffForHumans() }}</div>
                                        </div>
                                    @else
                                        <span class="avatar avatar-sm avatar-primary">{{ strtoupper(substr($comment->user->name, 0, 2)) }}</span>
                                        <div>
                                            <strong>{{ $comment->user->name }}</strong>
                                            <div style="font-size:0.875rem;color:var(--muted-fg);">{{ $comment->created_at->diffForHumans() }}</div>
                                        </div>
                                    @endif
                                </div>

                                <div style="display:flex;align-items:center;gap:0.5rem;">
                                    @if($canEditComment)
                                        <button type="button"
                                                class="btn btn-ghost btn-sm comment-edit-toggle-btn"
                                                data-comment-id="{{ $comment->id }}"
                                                title="Edit comment">
                                            <i data-lucide="pencil" class="icon-sm"></i>
                                        </button>
                                    @endif
                                    @if($canDeleteComment)
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                              style="margin:0;" onsubmit="return confirm('Delete this comment?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-sm" title="Delete comment">
                                                <i data-lucide="trash-2" class="icon-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            {{-- Comment body --}}
                            <div id="comment-content-{{ $comment->id }}">
                                <p style="white-space:pre-line;margin-bottom:1rem;">{{ $comment->content }}</p>
                            </div>

                            {{-- Comment edit form --}}
                            @if($canEditComment)
                                <div id="comment-edit-form-{{ $comment->id }}" style="display:none;margin-bottom:1rem;">
                                    <form action="{{ route('comments.update', $comment) }}" method="POST">
                                        @csrf @method('PUT')
                                        <textarea name="content" class="textarea" rows="3" required>{{ $comment->content }}</textarea>
                                        <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.75rem;">
                                            <button type="button" class="btn btn-outline btn-sm comment-edit-cancel-btn"
                                                    data-comment-id="{{ $comment->id }}">Cancel</button>
                                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            {{-- Comment reactions + reply toggle --}}
                            @auth
                                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                                    <button type="button" class="btn btn-outline btn-sm reaction-btn"
                                            data-url="/comments/{{ $comment->id }}/like"
                                            data-kind="like"
                                            data-group="comment-{{ $comment->id }}">
                                        <i data-lucide="heart" class="icon-sm reaction-icon reaction-like-icon"
                                           style="{{ $commentLiked ? 'fill:currentColor;color:#e11d48;' : '' }}"></i>
                                        <span class="reaction-count reaction-like-count">{{ $comment->likes_count }}</span>
                                    </button>

                                    <button type="button" class="btn btn-outline btn-sm reaction-btn"
                                            data-url="/comments/{{ $comment->id }}/dislike"
                                            data-kind="dislike"
                                            data-group="comment-{{ $comment->id }}">
                                        <i data-lucide="thumbs-down" class="icon-sm reaction-icon reaction-dislike-icon"
                                           style="{{ $commentDisliked ? 'color:#2563eb;' : '' }}"></i>
                                        <span class="reaction-count reaction-dislike-count">{{ $comment->dislikes_count ?? 0 }}</span>
                                    </button>

                                    <button type="button" class="btn btn-outline btn-sm reply-toggle-btn"
                                            data-comment-id="{{ $comment->id }}">
                                        <i data-lucide="message-circle" class="icon-sm"></i>
                                        {{ $comment->replies_count }}
                                    </button>
                                </div>

                                {{-- Reply form --}}
                                <div id="reply-form-{{ $comment->id }}" style="display:none;margin-top:1rem;">
                                    <form action="{{ route('comments.reply', $comment) }}" method="POST">
                                        @csrf
                                        <textarea name="content" class="textarea" rows="3"
                                                  placeholder="Write your reply..." required></textarea>
                                        <div style="margin-top:0.75rem;">
                                            <label style="display:flex;align-items:center;gap:0.5rem;">
                                                <input type="checkbox" name="is_anonymous" value="1"> Reply anonymously
                                            </label>
                                        </div>
                                        <div style="display:flex;justify-content:flex-end;margin-top:0.75rem;">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i data-lucide="send" class="icon-sm"></i> Send Reply
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endauth

                            {{-- Replies --}}
                            @if($comment->replies->isNotEmpty())
                                <div style="margin-top:1rem;padding-left:1rem;border-left:2px solid var(--border);">
                                    @foreach($comment->replies as $reply)
                                        @php
                                            $replyLiked     = auth()->check() && $reply->likes->contains('user_id', auth()->id());
                                            $replyDisliked  = auth()->check() && $reply->dislikes->contains('user_id', auth()->id());
                                            $canEditReply   = auth()->check() && auth()->id() === $reply->user_id;
                                            $canDeleteReply = auth()->check() && (auth()->id() === $reply->user_id || auth()->user()->role === 'admin');
                                        @endphp
                                        <div style="padding:0.75rem 0;border-top:1px solid var(--border);">
                                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:0.5rem;">
                                                <div>
                                                    <strong>{{ $reply->is_anonymous ? 'Anonymous' : $reply->user->name }}</strong>
                                                    <div style="font-size:0.875rem;color:var(--muted-fg);">{{ $reply->created_at->diffForHumans() }}</div>
                                                </div>
                                                <div style="display:flex;gap:0.5rem;">
                                                    @if($canEditReply)
                                                        <button type="button"
                                                                class="btn btn-ghost btn-sm reply-edit-toggle-btn"
                                                                data-reply-id="{{ $reply->id }}" title="Edit reply">
                                                            <i data-lucide="pencil" class="icon-sm"></i>
                                                        </button>
                                                    @endif
                                                    @if($canDeleteReply)
                                                        <form action="{{ route('comments.destroy', $reply) }}" method="POST"
                                                              style="margin:0;" onsubmit="return confirm('Delete this reply?');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn btn-ghost btn-sm" title="Delete reply">
                                                                <i data-lucide="trash-2" class="icon-sm"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>

                                            <div id="reply-content-{{ $reply->id }}">
                                                <p style="white-space:pre-line;margin:0 0 0.75rem;">{{ $reply->content }}</p>
                                            </div>

                                            @if($canEditReply)
                                                <div id="reply-edit-form-{{ $reply->id }}" style="display:none;margin-bottom:0.75rem;">
                                                    <form action="{{ route('comments.update', $reply) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <textarea name="content" class="textarea" rows="3" required>{{ $reply->content }}</textarea>
                                                        <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:0.75rem;">
                                                            <button type="button" class="btn btn-outline btn-sm reply-edit-cancel-btn"
                                                                    data-reply-id="{{ $reply->id }}">Cancel</button>
                                                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif

                                            @auth
                                                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                                                    <button type="button" class="btn btn-outline btn-sm reaction-btn"
                                                            data-url="/comments/{{ $reply->id }}/like"
                                                            data-kind="like"
                                                            data-group="reply-{{ $reply->id }}">
                                                        <i data-lucide="heart" class="icon-sm reaction-icon reaction-like-icon"
                                                           style="{{ $replyLiked ? 'fill:currentColor;color:#e11d48;' : '' }}"></i>
                                                        <span class="reaction-count reaction-like-count">{{ $reply->likes->count() }}</span>
                                                    </button>
                                                    <button type="button" class="btn btn-outline btn-sm reaction-btn"
                                                            data-url="/comments/{{ $reply->id }}/dislike"
                                                            data-kind="dislike"
                                                            data-group="reply-{{ $reply->id }}">
                                                        <i data-lucide="thumbs-down" class="icon-sm reaction-icon reaction-dislike-icon"
                                                           style="{{ $replyDisliked ? 'color:#2563eb;' : '' }}"></i>
                                                        <span class="reaction-count reaction-dislike-count">{{ $reply->dislikes->count() }}</span>
                                                    </button>
                                                </div>
                                            @endauth
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @auth
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                /* ── Reactions (like / dislike) ─────────────────────────────── */
                function setIcon(icon, liked, disliked) {
                    if (!icon) return;
                    const isLike = icon.classList.contains('reaction-like-icon');
                    if (isLike) {
                        icon.style.fill  = liked ? 'currentColor' : '';
                        icon.style.color = liked ? '#e11d48' : '';
                    } else {
                        icon.style.fill  = '';
                        icon.style.color = disliked ? '#2563eb' : '';
                    }
                }

                async function handleReaction(btn) {
                    const { url, kind, group } = btn.dataset;
                    const siblings   = document.querySelectorAll(`.reaction-btn[data-group="${group}"]`);
                    const likeBtn    = [...siblings].find(b => b.dataset.kind === 'like');
                    const dislikeBtn = [...siblings].find(b => b.dataset.kind === 'dislike');

                    try {
                        const res  = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        });
                        if (!res.ok) return;
                        const data = await res.json();

                        if (likeBtn) {
                            likeBtn.querySelector('.reaction-like-count').textContent = data.likes_count;
                            setIcon(likeBtn.querySelector('.reaction-like-icon'), !!data.liked, false);
                        }
                        if (dislikeBtn) {
                            dislikeBtn.querySelector('.reaction-dislike-count').textContent = data.dislikes_count;
                            setIcon(dislikeBtn.querySelector('.reaction-dislike-icon'), false, !!data.disliked);
                        }
                        // clear opposing icon when one is activated
                        if (kind === 'like'    && data.liked    && dislikeBtn) setIcon(dislikeBtn.querySelector('.reaction-dislike-icon'), false, false);
                        if (kind === 'dislike' && data.disliked && likeBtn)    setIcon(likeBtn.querySelector('.reaction-like-icon'), false, false);
                    } catch (e) { console.error(e); }
                }

                document.querySelectorAll('.reaction-btn').forEach(btn =>
                    btn.addEventListener('click', () => handleReaction(btn))
                );

                /* ── Comment form show / hide ───────────────────────────────── */
                const commentFormWrapper = document.getElementById('comment-form-wrapper');

                document.getElementById('toggle-comment-form')?.addEventListener('click', () => {
                    commentFormWrapper.style.display = 'block';
                    commentFormWrapper.querySelector('textarea')?.focus();
                });

                document.getElementById('cancel-comment-form')?.addEventListener('click', () => {
                    commentFormWrapper.style.display = 'none';
                });

                /* ── Reply form show / hide ─────────────────────────────────── */
                document.querySelectorAll('.reply-toggle-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const form = document.getElementById(`reply-form-${this.dataset.commentId}`);
                        if (!form) return;
                        const isHidden = form.style.display === 'none' || form.style.display === '';
                        form.style.display = isHidden ? 'block' : 'none';
                        if (isHidden) form.querySelector('textarea')?.focus();
                    });
                });

                /* ── Comment edit show / hide ───────────────────────────────── */
                document.querySelectorAll('.comment-edit-toggle-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const id = this.dataset.commentId;
                        document.getElementById(`comment-content-${id}`).style.display = 'none';
                        document.getElementById(`comment-edit-form-${id}`).style.display = 'block';
                    });
                });

                document.querySelectorAll('.comment-edit-cancel-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const id = this.dataset.commentId;
                        document.getElementById(`comment-content-${id}`).style.display = 'block';
                        document.getElementById(`comment-edit-form-${id}`).style.display = 'none';
                    });
                });

                /* ── Reply edit show / hide ─────────────────────────────────── */
                document.querySelectorAll('.reply-edit-toggle-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const id = this.dataset.replyId;
                        document.getElementById(`reply-content-${id}`).style.display = 'none';
                        document.getElementById(`reply-edit-form-${id}`).style.display = 'block';
                    });
                });

                document.querySelectorAll('.reply-edit-cancel-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const id = this.dataset.replyId;
                        document.getElementById(`reply-content-${id}`).style.display = 'block';
                        document.getElementById(`reply-edit-form-${id}`).style.display = 'none';
                    });
                });
            });
        </script>
    @endauth

@endsection
