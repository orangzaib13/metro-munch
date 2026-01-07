@extends('layouts.customer.app')
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/auth.css') }}">
    @endpush
@section('content')
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-header">
                <div class="auth-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
                </div>
                <h1 class="auth-title">Restaurant Manager</h1>
                <p class="auth-subtitle">Sign in to your account</p>
            </div>

            <form onsubmit="handleLogin(event)" method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input
                    type="email"
                    class="form-control"
                    name="email"
                    placeholder="your@email.com"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    class="form-control"
                    name="password"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                >
            </div>

            <div class="remember-me">
                <input type="checkbox" id="rememberMe" name="remember">
                <label for="rememberMe" style="margin: 0; cursor: pointer;">Remember me</label>
            </div>

            <button type="submit" class="auth-btn">Sign In</button>
            @if ($errors->any())
                <div class="error-message mt-2 text-danger">
                     @error('email') <p>{{ $message }}</p> @enderror
                    @error('branch') <p>{{ $message }}</p> @enderror
                    @error('role') <p>{{ $message }}</p> @enderror
                </div>
            @endif
        </form>

        </div>
    </div>
@endsection
