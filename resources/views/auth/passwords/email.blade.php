@extends('layouts.auth')

@section('content')
<div class="auth-header">
    <h2>Reset Password</h2>
    <p>Recover your account access</p>
</div>

<div class="auth-body">
    @if (session('status'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
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

        <button type="submit" class="btn btn-auth">
            {{ __('Send Password Reset Link') }}
        </button>
    </form>
</div>

<div class="auth-footer">
    Remembered your password? <a href="{{ route('login') }}">Log In</a>
</div>
@endsection
