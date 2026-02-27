@extends('layouts.authentication.master')
@section('title', 'Registration')

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
                <a href="{{ route('login') }}" target="_self">
                    Sign In
                </a>
            </div>
        </div>
    </header>
    <!-- Header ends -->
    <!-- Begin page content -->
    <main class="container-fluid h-100">
        <form action="{{ route('register.attempt') }}" method="POST">
            @csrf
            <div class="row h-100">
                <div class="col-11 col-sm-11 col-md-6 col-lg-5 col-xl-3 mx-auto align-self-center py-4">
                    <h2 class="mb-4"><span class="text-secondary fw-light">Create</span><br>new account</h2>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Enter Your Name" id="name" name="name"
                            required>
                        <label for="name">Name <span style="color: red;">*</span></label>
                        @error('name')
                            <button type="button" class="text-danger tooltip-btn" data-bs-toggle="tooltip"
                                data-bs-placement="left" title="{{ $message }}" id="nameerror">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Enter Your Email" id="email" name="email"
                            required>
                        <label for="email">Email <span style="color: red;">*</span></label>
                        @error('email')
                            <button type="button" class="text-danger tooltip-btn" data-bs-toggle="tooltip"
                                data-bs-placement="left" title="{{ $message }}" id="emailerror">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}" placeholder="phone" id="Enter Phone Number" name="phone" required>
                        <label for="phone">Phone <span style="color: red;">*</span></label>
                        @error('phone')
                            <button type="button" class="text-danger tooltip-btn" data-bs-toggle="tooltip"
                                data-bs-placement="left" title="{{ $message }}" id="phoneerror">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                            placeholder="Password" id="password">
                        <label for="password">Password <span style="color: red;">*</span></label>
                        @error('password')
                            <button type="button" class="text-danger tooltip-btn" data-bs-toggle="tooltip"
                                data-bs-placement="left" title="{{ $message }}" id="passworderror">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control  @error('confirm-password') is-invalid @enderror"
                            placeholder="Confirm Password" id="confirmpassword" name="confirm-password">
                        <label for="confirmpassword">Confirm Password <span style="color: red;">*</span></label>
                        @error('confirm-password')
                            <button type="button" class="text-danger tooltip-btn" data-bs-toggle="tooltip"
                                data-bs-placement="left" title="{{ $message }}" id="confirm-passworderror">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        @if (request()->inviter)
                            <input type="text"
                                class="form-control @error('invitation_code') is-invalid @else is-valid @enderror"
                                value="{{ request()->inviter }}" placeholder="Enter Invitation Code"
                                id="invitation_code" name="invitation_code" required readonly>
                        @else
                            <input type="text" class="form-control @error('invitation_code') is-invalid @enderror"
                                value="{{ old('invitation_code') }}" placeholder="Enter Invitation Code"
                                id="invitation_code" name="invitation_code" required>
                        @endif
                        <label for="invitation_code">Invitation Code <span style="color: red;">*</span></label>
                        @error('invitation_code')
                            <button type="button" class="text-danger tooltip-btn" data-bs-toggle="tooltip"
                                data-bs-placement="left" title="{{ $message }}" id="invitation_codeerror">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        @enderror
                    </div>
                    <p class="mb-3"><span class="text-muted">By clicking on Sign up button, you are agree to the our
                        </span>
                        <a href="">Terms and Conditions</a>
                    </p>
                </div>
                <div class="col-11 col-sm-11 mt-auto mx-auto py-4">
                    <div class="row ">
                        <div class="col-12 d-grid">
                            <button type="submit" class="btn btn-default btn-lg shadow-sm btn-rounded">Sign Up</button>
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
