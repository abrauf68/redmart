@extends('layouts.master')

@section('title', __('Edit Agent'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.agents.index') }}">{{ __('Agents') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.agents.update', $agent->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row p-5">
                        <h3>{{ __('Add New Agent') }}</h3>
                        <div class="mb-4 col-md-6">
                            <label for="name" class="form-label">{{ __('Name') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                                name="name" required placeholder="{{ __('Enter name') }}" autofocus
                                value="{{ old('name', $agent->name) }}" />
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="username" class="form-label">{{ __('Invite Code') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('username') is-invalid @enderror" type="text"
                                id="username" name="username" placeholder="{{ __('Enter username') }}"
                                value="{{ old('username', $agent->username) }}" required/>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="phone" class="form-label">{{ __('Phone') }}</label>
                            <input class="form-control @error('phone') is-invalid @enderror" type="text" id="phone"
                                name="phone" placeholder="{{ __('Enter phone') }}"
                                value="{{ old('phone', $agent->phone) }}" />
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="password" class="form-label">{{ __('Password') }}</label><small>(Leave blank if you
                                don't want to change it)</small>
                            <input class="form-control @error('password') is-invalid @enderror" type="text"
                                id="password" name="password" placeholder="{{ __('Enter password') }}"
                                value="{{ old('password') }}" />
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Add Agent') }}</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const usernameInput = document.getElementById('username');

            usernameInput.addEventListener('input', function() {

                // Remove everything except letters and numbers
                this.value = this.value
                    .replace(/\s+/g, '') // remove spaces
                    .replace(/[^a-zA-Z0-9]/g, '') // remove special chars
                    .toLowerCase(); // optional: force lowercase
            });

        });
    </script>
@endsection
