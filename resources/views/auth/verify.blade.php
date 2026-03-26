@extends('layouts.auth')

@section('content')
<div class="auth-header">
    <h2>Verify Email</h2>
    <p>Check your inbox for a verification link</p>
</div>

<div class="auth-body text-center">
    @if (session('resent'))
        <div class="alert alert-success mb-4" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif

    <div class="mb-4" style="color: #4a5568;">
        {{ __('Before proceeding, please check your email for a verification link.') }}
        {{ __('If you did not receive the email, you can request another one below.') }}
    </div>

    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="btn btn-auth">{{ __('Request Another Link') }}</button>
    </form>
</div>

<div class="auth-footer">
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</div>
@endsection
