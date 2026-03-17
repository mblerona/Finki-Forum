@extends('layouts.app')

@section('title', 'Create New Thread')

@section('content')

    <main style="max-width:48rem;margin:0 auto;padding:2rem 1rem;">

        <a href="{{ route('subjects.index') }}" class="breadcrumb">
            <i data-lucide="arrow-left" class="icon"></i> Back to Subjects
        </a>

        <div class="card">
            <div style="padding:1.5rem 1.5rem 0;">
                <h1 style="font-size:1.5rem;">Create New Thread</h1>
            </div>

            <div style="padding:1.5rem;">
                <form method="POST" action="{{ route('threads.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="subject_id">
                            Subject <span style="color:var(--destructive);">*</span>
                        </label>

                        <select name="subject_id" id="subject_id" class="select-trigger" style="width:100%;" required>
                            <option value="">Select a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
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

                        <input
                            type="text"
                            name="title"
                            id="title"
                            class="input"
                            placeholder="What's your question or topic?"
                            maxlength="200"
                            value="{{ old('title') }}"
                            required
                        >

                        <p class="form-hint" style="text-align:right;">Max 200 characters</p>

                        @error('title')
                        <p class="form-hint" style="color:var(--destructive);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">
                            Content <span style="color:var(--destructive);">*</span>
                        </label>

                        <textarea
                            name="content"
                            id="content"
                            class="textarea"
                            style="min-height:200px;"
                            placeholder="Provide details about your question or topic. Be specific and include any relevant context..."
                            required
                        >{{ old('content') }}</textarea>

                        @error('content')
                        <p class="form-hint" style="color:var(--destructive);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Tags (optional)</label>
                        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-top:0.25rem;">
                            @foreach($tags as $tag)
                                <label style="cursor:pointer;">
                                    <input
                                        type="checkbox"
                                        name="tags[]"
                                        value="{{ $tag->id }}"
                                        class="checkbox"
                                        style="display:none;"
                                        {{ is_array(old('tags')) && in_array($tag->id, old('tags')) ? 'checked' : '' }}
                                    >
                                    <span class="tag-btn badge badge-secondary" style="
                                        padding: 0.375rem 0.75rem;
                                        cursor: pointer;
                                        transition: all 150ms ease;
                                        user-select: none;
                                    ">
                                        + {{ $tag->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <p class="form-hint">Click to toggle tags</p>
                    </div>

                    <div class="form-group">
                        <label for="file">Attachment (optional)</label>
                        <input
                            type="file"
                            name="file"
                            id="file"
                            class="input"
                            accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx"
                            style="padding: 0.375rem;"
                        >
                        <p class="form-hint">Allowed: PDF, images, Word, Excel — max 10MB</p>

                        @error('file')
                        <p class="form-hint" style="color:var(--destructive);">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display:flex;align-items:center;gap:0.75rem;background:rgba(237,238,243,0.5);padding:1rem;border-radius:var(--radius);margin-bottom:1.5rem;">
                        <input type="checkbox" class="checkbox" id="anonymous" name="anonymous" value="1"
                            {{ old('anonymous') ? 'checked' : '' }}
                        />
                        <div>
                            <label for="anonymous" style="margin-bottom:0;cursor:pointer;">Post anonymously</label>
                            <p style="font-size:0.75rem;color:var(--muted-fg);margin-top:0.125rem;">
                                Your name will not be shown on this thread.
                            </p>
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:0.75rem;padding-top:1rem;border-top:1px solid var(--border);">
                        <div style="display:flex;gap:0.75rem;">
                            <a href="{{ route('subjects.index') }}" class="btn btn-outline" style="flex:1;text-align:center;">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary" style="flex:1;">
                                <i data-lucide="send" class="icon-sm"></i> Create Thread
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.querySelectorAll('input[name="tags[]"]').forEach(function(checkbox) {
            const span = checkbox.nextElementSibling;

            function updateStyle() {
                if (checkbox.checked) {
                    span.style.backgroundColor = 'var(--primary)';
                    span.style.color = 'var(--primary-fg)';
                } else {
                    span.style.backgroundColor = '';
                    span.style.color = '';
                }
            }

            updateStyle();
            checkbox.addEventListener('change', updateStyle);
        });
    </script>

@endsection
