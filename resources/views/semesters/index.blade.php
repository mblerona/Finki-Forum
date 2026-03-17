@extends('layouts.app')

@section('title', 'Semesters')

@section('content')

    <section class="section">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <h1>Semesters</h1>
        </div>

        @foreach($semesters as $semester)
            @if($semester->subjects->isNotEmpty())
                <div style="margin-bottom: 2.5rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:2px solid var(--border);">
                        <h2 style="margin:0;">{{ $semester->name }}</h2>
                        <span class="badge badge-secondary">{{ $semester->subjects->count() }} subjects</span>
                    </div>

                    <div class="grid-4">
                        @foreach($semester->subjects as $subject)
                            <a href="{{ route('subjects.show', $subject->id) }}"
                               class="card category-card"
                               style="padding:1.6rem;display:flex;flex-direction:column;gap:0.8rem;">

                                <div style="display:flex;align-items:center;gap:0.9rem;">
                                    <div class="category-icon">
                                        <i data-lucide="book-open" class="icon-md"></i>
                                    </div>

                                    <h3 style="margin:0;font-size:1rem;font-weight:600;color:#1e293b;">
                                        {{ $subject->name }}
                                    </h3>
                                </div>

                                @if($subject->majors->isNotEmpty())
                                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                                        @foreach($subject->majors as $major)
                                            <span style="
                                                font-size:0.75rem;
                                                padding:3px 8px;
                                                border-radius:20px;
                                                background:#eef2ff;
                                                color:#3730a3;
                                                font-weight:600;
                                                border:1px solid #c7d2fe;
                                            ">
                                                {{ $major->code }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div style="
                                    margin-top:auto;
                                    padding-top:0.6rem;
                                    border-top:1px solid #e5e7eb;
                                    font-size:0.875rem;
                                    color:#334155;
                                    font-weight:500;
                                ">
                                    <span style="color:#1e293b;font-weight:600;">
                                        {{ $subject->threads_count ?? 0 }}
                                        <span style="font-weight:400;">threads</span>
                                    </span>
                                </div>

                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </section>

@endsection
