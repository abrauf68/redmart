@extends('layouts.authentication.master2')
@section('title', 'Reset Password')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <img style="height: 55px;" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="{{ env('APP_NAME') }}">
        </div>

        <div class="auth-title">Reset Password</div>
        <div class="auth-subtitle mb-3">Enter your email to receive a password reset link</div>

        @if (session('status'))
            <div class="auth-alert text-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="auth-alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="auth-input-group">
                <input type="text" name="email" value="{{ old('email') }}" placeholder="Enter Your Email" required>
            </div>

            <button type="submit" class="auth-btn">Send Reset Link</button>
        </form>

        <div class="auth-footer-text mt-3">
            Remember your password? <a class="auth-link" href="{{ route('login') }}">Sign In</a>
        </div>
    </div>
</div>
@endsection
