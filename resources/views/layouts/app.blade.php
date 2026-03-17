<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FINKI Forum')</title>
    <meta name="description" content="A forum for FINKI students to discuss subjects, threads, experiences, and resources">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
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
                <div class="input-with-icon">
                    <i data-lucide="search" class="input-icon icon"></i>
                    <input
                        type="search"
                        class="input"
                        placeholder="Search threads, subjects..."
                        style="background:var(--secondary);border-color:transparent;"
                    >
                </div>
            </div>

            <div class="header-actions">
                @auth
                    <span style="font-size: 0.875rem; color: var(--muted-fg);">
                        {{ auth()->user()->name }}
                    </span>
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
