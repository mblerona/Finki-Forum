@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="auth-page">
        <div class="auth-container">
            <div class="card">
                <div class="auth-header">
                    <h1>Create account</h1>
                    <p>Join the FINKI Forum community</p>
                </div>

                <div class="auth-body">
                    @if($errors->any())
                        <div class="alert-error">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="input"
                                value="{{ old('name') }}"
                                placeholder="Your name"
                                required
                                autofocus
                            >
                        </div>

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
                            >
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="input"
                                placeholder="Min. 8 characters"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="input"
                                placeholder="Repeat password"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            Create Account
                        </button>
                    </form>
                </div>

                <div class="auth-footer">
                    <p style="text-align:center; font-size: 0.875rem; color: var(--muted-fg);">
                        Already have an account?
                        <a href="{{ route('login') }}" style="color: var(--primary);">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
