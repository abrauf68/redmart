@extends('layouts.authentication.master2')
@section('title', 'Reset Password')

@section('content')
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-logo">
                <img style="height: 55px;" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="{{ env('APP_NAME') }}">
            </div>

            <div class="auth-title">Set New Password</div>
            <div class="auth-subtitle mb-3">Enter your new password below</div>

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

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <div class="auth-input-group">
                    <input type="password" name="password" placeholder="New Password" required>
                </div>

                <div class="auth-input-group">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                </div>

                <button type="submit" class="auth-btn">Reset Password</button>
            </form>

            <div class="auth-footer-text mt-3">
                Remember your password? <a class="auth-link" href="{{ route('login') }}">Sign In</a>
            </div>
        </div>
    </div>
@endsection
