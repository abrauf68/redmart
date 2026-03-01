@extends('layouts.authentication.master2')
@section('title', 'Register')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <img style="height: 55px;" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="{{ env('APP_NAME') }}">
        </div>

        <div class="auth-title">Create Account</div>
        <div class="auth-subtitle">Register a new account</div>

        @if ($errors->any())
            <div class="auth-alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.attempt') }}">
            @csrf

            <div class="auth-input-group">
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name" required>
            </div>

            <div class="auth-input-group">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
            </div>

            <div class="auth-input-group">
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone" required>
            </div>

            <div class="auth-input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="auth-input-group">
                <input type="password" name="confirm-password" placeholder="Confirm Password" required>
            </div>

            <div class="auth-input-group">
                <input type="text" name="invitation_code" value="{{ old('invitation_code', request()->inviter) }}" {{ request()->inviter ? 'readonly' : '' }} placeholder="Invitation Code" required>
            </div>

            <div class="auth-checkbox" style="text-align: center;">
                <span>By clicking on signup button you agree to the <a class="auth-link" href="#">Terms & Conditions</a></span>
            </div>

            <button type="submit" class="auth-btn">Sign Up</button>
        </form>

        <div class="auth-footer-text">
            Already have an account? <a class="auth-link" href="{{ route('login') }}">Sign In</a>
        </div>
    </div>
</div>
@endsection
