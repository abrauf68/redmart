@extends('layouts.authentication.master2')
@section('title', 'Login')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <img style="height: 55px;" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="{{ env('APP_NAME') }}">
        </div>

        <div class="auth-title">Login</div>
        <div class="auth-subtitle">Access your account</div>

        @if ($errors->any())
            <div class="auth-alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            {{-- <div class="auth-input-group">
                <input type="text" name="email_username" value="{{ old('email_username') }}" placeholder="Enter user name" required>
            </div> --}}

            <div class="auth-input-group">
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter phone" required>
            </div>

            <div class="auth-input-group">
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            {{-- <p class="text-end mb-3" style="text-align: end; margin-bottom: 10px;">
                <a class="auth-link" href="{{ route('password.request') }}">Forgot Password?</a>
            </p> --}}

            <button type="submit" class="auth-btn">Login</button>
        </form>

        <div class="auth-footer-text">
            Don’t have an account? <a class="auth-link" href="{{ route('register') }}">Register</a>
        </div>
    </div>
</div>
@endsection
