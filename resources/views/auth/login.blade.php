@extends('layouts.authentication.master')
@section('title', 'Login')

@section('css')
@endsection

@section('content')
    <!-- Header -->
    <header class="header position-fixed header-filled">
        <div class="row">
            <div class="col">
                <div class="logo-small">
                    <img style="width: 100px;" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="{{ env('APP_NAME') }}">
                    <span>Red<span class="text-secondary fw-light">Mart</span></apan>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('register') }}" target="_self">
                    Sign up
                </a>
            </div>
        </div>
    </header>
    <!-- Header ends -->

    <!-- Begin page content -->
    <main class="container-fluid h-100 ">
        <form action="{{ route('login.attempt') }}" method="POST">
            @csrf
            <div class="row h-100">
                <div class="col-11 col-sm-11 col-md-6 col-lg-5 col-xl-3 mx-auto align-self-center py-4">
                    <h2 class="mb-4"><span class="text-secondary fw-light">Sign in to</span><br>your account</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group form-floating mb-3 @error('email_username') is-invalid @enderror">
                        <input type="text" class="form-control" value="{{ old('email_username') }}" id="email_username" name="email_username" placeholder="Username" required>
                        <label class="form-control-label" for="email_username">Username / Email</label>
                        @error('email_username')
                            <button type="button" class="text-danger tooltip-btn" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $message }}" id="usernameerror">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        @enderror
                    </div>

                    <div class="form-group form-floating @error('password') is-invalid @enderror mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label class="form-control-label" for="password">Password</label>
                        @error('password')
                            <button type="button" class="text-danger tooltip-btn" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $message }}" id="usernameerror">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        @enderror
                    </div>
                    @if (Route::has('password.request'))
                        <p class="mb-3 text-end">
                            <a href="{{ route('password.request') }}" class="">
                                {{ __('Forgot Password?') }}
                            </a>
                        </p>
                    @endif
                </div>
                <div class="col-11 col-sm-11 mt-auto mx-auto py-4">
                    <div class="row ">
                        <div class="col-12 d-grid">
                            <button type="submit" class="btn btn-default btn-lg btn-rounded shadow-sm">Sign In</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection

@section('script')
    {!! NoCaptcha::renderJs() !!}
@endsection
