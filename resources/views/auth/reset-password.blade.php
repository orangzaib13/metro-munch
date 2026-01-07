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
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle">Set Your New Password</p>
        </div>
                    
    @if(session('status'))
        <p style="color:green">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

         <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ $email }}"
                   required
                   autofocus>

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

         <!-- New Password  -->
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

         <!-- Confirm Password  -->
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="auth-btn">
            Reset Password
        </button>
</form>

        </div>
    </div>
@endsection
