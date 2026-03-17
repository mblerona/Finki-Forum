@extends('layouts.app')

@section('title', 'FINKI Forum - Home')

@section('content')

    <section class="hero section">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:2rem;flex-wrap:wrap;">
            <div style="flex:1;min-width:280px;">
                <h1 class="text-balance">Welcome to FINKI Forum</h1>
                <p class="text-pretty">
                    Connect with fellow students, discuss coursework, share resources, and get help with your studies. Join the conversation!
                </p>

                <div class="hero-actions">
                    <a href="{{ route('subjects.index') }}" class="btn btn-primary">
                        Browse Subjects <i data-lucide="arrow-right" class="icon"></i>
                    </a>
                    <a href="{{ route('threads.create') }}" class="btn btn-outline">Start a Discussion</a>
                </div>
            </div>

            <div style="flex:0 0 280px;display:flex;justify-content:center;">
                <img
                    src="{{ asset('images/forum-logo.png') }}"
                    alt="FINKI Forum Logo"
                    style="max-width:280px;width:100%;height:auto;display:block;"
                >
            </div>
        </div>
    </section>

    <section class="section">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <h2>Categories</h2>
        </div>

        <div class="grid-4">
            <a href="{{ route('subjects.index') }}" class="card category-card">
                <div class="category-icon"><i data-lucide="book-open" class="icon-md"></i></div>
                <h3>Subjects</h3>
                <p>Discuss course materials, assignments, and exams</p>
                <div class="category-stats">
                    <span>Browse all</span>
                    <span>Academic discussions</span>
                </div>
            </a>

            <a href="{{ route('majors.index') }}" class="card category-card">
                <div class="category-icon"><i data-lucide="graduation-cap" class="icon-md"></i></div>
                <h3>Majors</h3>
                <p>Explore subjects and discussions by study program</p>
                <div class="category-stats">
                    <span>6 programs</span>
                    <span>FINKI structure</span>
                </div>
            </a>

            <a href="{{ route('semesters.index') }}" class="card category-card">
                <div class="category-icon"><i data-lucide="calendar" class="icon-md"></i></div>
                <h3>Semesters</h3>
                <p>Find subjects organized by semester</p>
                <div class="category-stats">
                    <span>1–8</span>
                    <span>Structured browsing</span>
                </div>
            </a>

            <a href="{{ route('threads.create') }}" class="card category-card">
                <div class="category-icon"><i data-lucide="users" class="icon-md"></i></div>
                <h3>Threads</h3>
                <p>Read discussions, like posts, and comment on topics</p>
                <div class="category-stats">
                    <span>Interactive forum</span>
                    <span>Student community</span>
                </div>
            </a>
        </div>
    </section>

@endsection
