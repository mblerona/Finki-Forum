@extends('layouts.app')

@section('title', 'Subjects')

@section('content')

    <section class="section">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <h1>Subjects</h1>
        </div>

        {{-- Search and Filter Bar --}}
        <div class="card" style="padding:1.25rem;margin-bottom:1.5rem;">
            <form method="GET" action="{{ route('subjects.index') }}">
                <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">

                    {{-- Search --}}
                    <div style="flex:1;min-width:200px;">
                        <label style="font-size:0.875rem;font-weight:500;margin-bottom:0.375rem;display:block;">
                            Search
                        </label>
                        <div class="input-with-icon">
                            <i data-lucide="search" class="input-icon icon"></i>
                            <input
                                type="text"
                                id="subject-search"
                                name="search"
                                class="input"
                                placeholder="Search subjects..."
                                value="{{ $search ?? '' }}"
                                autocomplete="off"
                                style="padding-left:2.25rem;"
                            >
                        </div>
                    </div>

                    {{-- Semester Filter --}}
                    <div style="min-width:180px;">
                        <label style="font-size:0.875rem;font-weight:500;margin-bottom:0.375rem;display:block;">
                            Semester
                        </label>
                        <select id="subject-semester" name="semester" class="input" style="cursor:pointer;">
                            <option value="">All Semesters</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->name }}" {{ $selectedSemester === $semester->name ? 'selected' : '' }}>
                                    {{ $semester->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Major Filter --}}
                    <div style="min-width:180px;">
                        <label style="font-size:0.875rem;font-weight:500;margin-bottom:0.375rem;display:block;">
                            Major
                        </label>
                        <select id="subject-major" name="major" class="input" style="cursor:pointer;">
                            <option value="">All Majors</option>
                            @foreach($majors as $major)
                                <option value="{{ $major->id }}" {{ ($selectedMajor ?? '') == $major->id ? 'selected' : '' }}>
                                    {{ $major->code }}{{ isset($major->name) ? ' — ' . $major->name : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div style="display:flex;gap:0.5rem;">
{{--                        <button type="submit" class="btn btn-primary">--}}
{{--                            <i data-lucide="filter" class="icon-sm"></i>--}}
{{--                            Filter--}}
{{--                        </button>--}}

                        @if($search || $selectedSemester || ($selectedMajor ?? ''))
                            <a href="{{ route('subjects.index') }}" class="btn btn-outline">
                                <i data-lucide="x" class="icon-sm"></i>
                                Clear
                            </a>
                        @endif
                    </div>

                </div>

                {{-- Active filters display --}}
                @if($search || $selectedSemester || ($selectedMajor ?? ''))
                    <div style="margin-top:0.75rem;display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
                        <span style="font-size:0.8rem;color:var(--muted-fg);">Active filters:</span>

                        @if($search)
                            <span class="badge badge-primary">
                                Search: "{{ $search }}"
                            </span>
                        @endif

                        @if($selectedSemester)
                            <span class="badge badge-primary">
                                {{ $selectedSemester }}
                            </span>
                        @endif

                        @if($selectedMajor ?? '')
                            @php $activeMajor = $majors->firstWhere('id', $selectedMajor); @endphp
                            @if($activeMajor)
                                <span class="badge badge-primary">{{ $activeMajor->code }}</span>
                            @endif
                        @endif

                        <span style="font-size:0.8rem;color:var(--muted-fg);">
                            — {{ $subjects->count() }} result(s)
                        </span>
                    </div>
                @endif

                <div id="filter-status" style="margin-top:0.75rem;font-size:0.8rem;color:var(--muted-fg);display:none;"></div>
            </form>
        </div>

        {{-- Results --}}
        @if($subjects->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i data-lucide="search-x" class="icon-lg"></i>
                </div>
                <h3>No subjects found</h3>
                <p>Try adjusting your search or filter.</p>
                <a href="{{ route('subjects.index') }}" class="btn btn-outline" style="margin-top:1rem;">
                    Clear filters
                </a>
            </div>
        @else
            <div class="grid-4">
                @foreach($subjects as $subject)
                    <a href="{{ route('subjects.show', $subject->id) }}"
                       class="card category-card subject-card"
                       data-name="{{ strtolower($subject->name) }}"
                       data-semester="{{ $subject->semester->name }}"
                       data-majors="{{ $subject->majors->pluck('id')->join(',') }}"
                       style="padding:1.6rem;display:flex;flex-direction:column;gap:0.8rem;">

                        <div style="display:flex;align-items:center;gap:0.9rem;">
                            <div class="category-icon">
                                <i data-lucide="book-open" class="icon-md"></i>
                            </div>

                            <h3 style="margin:0;font-size:1rem;font-weight:600;color:#1e293b;">
                                {{ $subject->name }}
                            </h3>
                        </div>

                        <div style="font-size:0.875rem;color:#334155;font-weight:500;">
                            {{ $subject->semester->name }}
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

            <div id="no-results" style="display:none;" class="empty-state">
                <div class="empty-state-icon">
                    <i data-lucide="search-x" class="icon-lg"></i>
                </div>
                <h3>No subjects found</h3>
                <p>Try a different search or filter.</p>
            </div>
        @endif
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput    = document.getElementById('subject-search');
            const semesterSelect = document.getElementById('subject-semester');
            const majorSelect    = document.getElementById('subject-major');
            const statusEl       = document.getElementById('filter-status');
            const noResults      = document.getElementById('no-results');
            const cards          = document.querySelectorAll('.subject-card');

            function applyFilters() {
                const words    = searchInput.value.trim().toLowerCase().split(/\s+/).filter(Boolean);
                const semester = semesterSelect.value;
                const major    = majorSelect.value;
                const isActive = words.length > 0 || semester || major;

                let visible = 0;

                cards.forEach(function (card) {
                    const name         = card.dataset.name;
                    const cardSemester = card.dataset.semester;
                    const cardMajors   = card.dataset.majors ? card.dataset.majors.split(',') : [];

                    const matchesWords    = words.every(function (w) { return name.includes(w); });
                    const matchesSemester = !semester || cardSemester === semester;
                    const matchesMajor    = !major    || cardMajors.includes(major);

                    const show = matchesWords && matchesSemester && matchesMajor;
                    card.style.display = show ? '' : 'none';
                    if (show) visible++;
                });

                if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';

                if (statusEl) {
                    statusEl.style.display = isActive ? 'block' : 'none';
                    statusEl.textContent   = isActive ? '— ' + visible + ' result' + (visible !== 1 ? 's' : '') : '';
                }
            }

            searchInput.addEventListener('input', applyFilters);
            semesterSelect.addEventListener('change', applyFilters);
            majorSelect.addEventListener('change', applyFilters);
        });
    </script>

@endsection
