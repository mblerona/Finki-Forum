@php
    /** @var \App\Models\User|null $authUser */
    $authUser = auth()->user();
@endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FINKI Forum')</title>
    <meta name="description" content="A forum for FINKI students to discuss subjects, threads, experiences, and resources">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/lucide.js') }}"></script>
</head>
<body>

<header class="header">
    <div class="container">
        <div class="header-inner">

            <a href="{{ route('home') }}" class="logo" style="display:flex;align-items:center;gap:0.75rem;text-decoration:none;">
                <img
                    src="{{ asset('images/forum-logo.png') }}"
                    alt="FINKI Forum Logo"
                    style="height:48px;width:auto;display:block;"
                >
                <span class="logo-text">FINKI Forum</span>
            </a>

            <nav class="nav-desktop">
                <a href="{{ route('home') }}" class="nav-link">
                    <i data-lucide="home" class="icon"></i> Home
                </a>
                <a href="{{ route('subjects.index') }}" class="nav-link">
                    <i data-lucide="book-open" class="icon"></i> Subjects
                </a>
                <a href="{{ route('majors.index') }}" class="nav-link">
                    <i data-lucide="graduation-cap" class="icon"></i> Majors
                </a>
                <a href="{{ route('semesters.index') }}" class="nav-link">
                    <i data-lucide="calendar" class="icon"></i> Semesters
                </a>
            </nav>

            <div class="header-search">
                <form method="GET" action="{{ route('search') }}">
                    <div class="input-with-icon">
                        <i data-lucide="search" class="input-icon icon"></i>
                        <input
                            type="search"
                            name="q"
                            class="input"
                            placeholder="Search threads, subjects..."
                            value="{{ request('q') }}"
                            style="background:var(--secondary);border-color:transparent;"
                        >
                    </div>
                </form>
            </div>

            <div class="header-actions">
                @auth
                    <a href="{{ route('profile.show') }}" style="
                        display:inline-flex;align-items:center;gap:0.5rem;
                        font-size:0.875rem;font-weight:500;
                        color:var(--fg);text-decoration:none;
                        padding:0.375rem 0.625rem;
                        border-radius:var(--radius);
                        transition:background 150ms ease;
                    "
                       onmouseover="this.style.background='var(--secondary)'"
                       onmouseout="this.style.background='transparent'">
                        <span style="
                            width:1.75rem;height:1.75rem;
                            border-radius:9999px;
                            background:rgba(59,108,245,0.12);
                            color:var(--primary);
                            display:inline-flex;align-items:center;justify-content:center;
                            font-size:0.625rem;font-weight:700;flex-shrink:0;
                        ">
                            {{ strtoupper(substr($authUser->name, 0, 2)) }}
                        </span>
                        {{ $authUser->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                @endauth
            </div>

        </div>
    </div>
</header>

<main class="container" style="padding-top:2rem;padding-bottom:2rem;">
    @yield('content')
</main>

<footer class="footer">
    <div class="container">
        <div class="footer-inner">
            <p>FINKI Forum - Faculty of Computer Science and Engineering</p>
        </div>
    </div>
</footer>

<script>
    lucide.createIcons();
</script>

</body>
</html>
