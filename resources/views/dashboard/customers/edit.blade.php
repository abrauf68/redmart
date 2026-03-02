@extends('layouts.master')

@section('title', __('Edit Customer'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.customers.index') }}">{{ __('Customers') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.customers.update', $customer->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row p-5">
                        <h3>{{ __('Add New Customer') }}</h3>
                        <div class="mb-4 col-md-6">
                            <label for="name" class="form-label">{{ __('Name') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                                name="name" required placeholder="{{ __('Enter name') }}" autofocus
                                value="{{ old('name', $customer->name) }}" />
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label class="form-label" for="inviter_id">{{ __('Agent') }}</label><span
                                class="text-danger">*</span>
                            <select id="inviter_id" name="inviter_id"
                                class="select2 form-select @error('inviter_id') is-invalid @enderror">
                                <option value="" selected disabled>{{ __('Select agent') }}</option>
                                @if (isset($agents) && count($agents) > 0)
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}"
                                            {{ $agent->id == old('inviter_id', $customer->inviter_id) ? 'selected' : '' }}>
                                            {{ $agent->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('inviter_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="phone" class="form-label">{{ __('Phone') }}</label>
                            <input class="form-control @error('phone') is-invalid @enderror" type="text" id="phone"
                                name="phone" placeholder="{{ __('Enter phone') }}" value="{{ old('phone', $customer->phone) }}" />
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="password" class="form-label">{{ __('Password') }}</label><small>(Leave blank if you don't want to change it)</small>
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
                        <button type="submit" class="btn btn-primary me-3">{{ __('Update Customer') }}</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
@endsection

@section('script')
@endsection
