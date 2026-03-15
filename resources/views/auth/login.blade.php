@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="auth-page">
        <div class="auth-container">
            <div class="card">
                <div class="auth-header">
                    <h1>Welcome back</h1>
                    <p>Sign in to your FINKI Forum account</p>
                </div>

                <div class="auth-body">
                    @if($errors->any())
                        <div class="alert-error">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="input"
                                value="{{ old('email') }}"
                                placeholder="you@finki.edu.mk"
                                required
                                autofocus
                            >
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="input"
                                placeholder="••••••••"
                                required
                            >
                        </div>

                        <div class="checkbox-wrapper" style="margin-bottom: 1.25rem;">
                            <input type="checkbox" id="remember" name="remember" class="checkbox">
                            <label for="remember" style="margin-bottom: 0;">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            Sign In
                        </button>
                    </form>
                </div>

                <div class="auth-footer">
                    <p style="text-align:center; font-size: 0.875rem; color: var(--muted-fg);">
                        Don't have an account?
                        <a href="{{ route('register') }}" style="color: var(--primary);">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
