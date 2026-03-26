@extends('layouts.auth')

@section('content')
<div class="auth-header">
    <h2>Welcome Back!</h2>
    <p>Please log in to your account</p>
</div>

<div class="auth-body">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label for="password" class="form-label mb-0">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none" style="color: #667eea; font-weight: 500;" href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                @endif
            </div>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember" style="font-size: 0.9rem; color: #718096;">
                    {{ __('Remember Me') }}
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-auth">
            {{ __('Log In') }}
        </button>
    </form>
</div>

<div class="auth-footer">
    Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
</div>
@endsection
