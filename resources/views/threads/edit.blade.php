@extends('layouts.app')

@section('title', 'Edit Thread')

@section('content')

    <main style="max-width:48rem;margin:0 auto;padding:2rem 1rem;">

        <a href="{{ route('threads.show', $thread) }}" class="breadcrumb">
            <i data-lucide="arrow-left" class="icon"></i> Back to Thread
        </a>

        <div class="card">
            <div style="padding:1.5rem 1.5rem 0;">
                <h1 style="font-size:1.5rem;">Edit Thread</h1>
            </div>

            <div style="padding:1.5rem;">
                <form method="POST" action="{{ route('threads.update', $thread) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="subject_id">
                            Subject <span style="color:var(--destructive);">*</span>
                        </label>
                        <select name="subject_id" id="subject_id" class="select-trigger" style="width:100%;" required>
                            <option value="">Select a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id', $thread->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                        <p class="form-hint" style="color:var(--destructive);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="title">
                            Title <span style="color:var(--destructive);">*</span>
                        </label>
                        <input type="text" name="title" id="title" class="input"
                               placeholder="What's your question or topic?"
                               maxlength="200"
                               value="{{ old('title', $thread->title) }}"
                               required>
                        <p class="form-hint" style="text-align:right;">Max 200 characters</p>
                        @error('title')
                        <p class="form-hint" style="color:var(--destructive);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">
                            Content <span style="color:var(--destructive);">*</span>
                        </label>
                        <textarea name="content" id="content" class="textarea" style="min-height:200px;"
                                  placeholder="Provide details..." required>{{ old('content', $thread->content) }}</textarea>
                        @error('content')
                        <p class="form-hint" style="color:var(--destructive);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Tags (optional)</label>
                        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-top:0.25rem;">
                            @foreach($tags as $tag)
                                @php
                                    $checked = old('tags')
                                        ? in_array($tag->id, (array) old('tags'))
                                        : $thread->tags->contains('id', $tag->id);
                                @endphp
                                <label style="cursor:pointer;">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                           class="tag-checkbox" style="display:none;" {{ $checked ? 'checked' : '' }}>
                                    <span class="badge badge-secondary tag-label" style="
                                        padding:0.375rem 0.75rem;
                                        cursor:pointer;
                                        transition:all 150ms ease;
                                        user-select:none;
                                        {{ $checked ? 'background-color:var(--primary);color:var(--primary-fg);' : '' }}
                                    ">+ {{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <p class="form-hint">Click to toggle tags</p>
                    </div>

                    <div style="display:flex;align-items:center;gap:0.75rem;background:rgba(237,238,243,0.5);padding:1rem;border-radius:var(--radius);margin-bottom:1.5rem;">
                        <input type="checkbox" class="checkbox" id="is_anonymous" name="is_anonymous" value="1"
                            {{ old('is_anonymous', $thread->is_anonymous) ? 'checked' : '' }}>
                        <div>
                            <label for="is_anonymous" style="margin-bottom:0;cursor:pointer;">Post anonymously</label>
                            <p style="font-size:0.75rem;color:var(--muted-fg);margin-top:0.125rem;">
                                Your name will not be shown on this thread.
                            </p>
                        </div>
                    </div>

                    @if($thread->file_path)
                        <div style="background:var(--secondary);border-radius:var(--radius);padding:0.75rem 1rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;">
                            <i data-lucide="paperclip" class="icon-sm" style="color:var(--muted-fg);flex-shrink:0;"></i>
                            <span style="color:var(--muted-fg);">Existing attachment:</span>
                            <span style="font-weight:500;">{{ $thread->file_name }}</span>
                            <span style="color:var(--muted-fg);font-size:0.75rem;">(cannot be changed when editing)</span>
                        </div>
                    @endif

                    <div style="display:flex;gap:0.75rem;padding-top:1rem;border-top:1px solid var(--border);">
                        <a href="{{ route('threads.show', $thread) }}" class="btn btn-outline" style="flex:1;text-align:center;">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" style="flex:1;">
                            <i data-lucide="save" class="icon-sm"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.querySelectorAll('.tag-checkbox').forEach(function (cb) {
            const label = cb.nextElementSibling;
            function sync() {
                label.style.backgroundColor = cb.checked ? 'var(--primary)' : '';
                label.style.color           = cb.checked ? 'var(--primary-fg)' : '';
            }
            sync();
            cb.addEventListener('change', sync);
        });
    </script>

@endsection
