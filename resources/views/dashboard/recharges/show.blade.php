@extends('layouts.master')

@section('title', __('Recharge Details'))

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.recharges.index') }}">{{ __('Recharges') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">

            {{-- LEFT SIDE - RECHARGE INFO --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-wallet me-2 text-primary"></i>
                            {{ __('Recharge Information') }}
                        </h5>

                        {{-- Status Badge --}}
                        @php
                            $statusClass = match ($recharge->status) {
                                'pending' => 'badge-warning',
                                'completed' => 'badge-success',
                                'failed' => 'badge-danger',
                                'cancelled' => 'badge-secondary',
                                default => 'badge-dark',
                            };
                        @endphp

                        <span class="badge rounded-pill {{ $statusClass }}">
                            {{ ucfirst($recharge->status) }}
                        </span>
                    </div>

                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>{{ __('Transaction ID') }}:</strong>
                                <div>{{ $recharge->transaction_id }}</div>
                            </div>

                            <div class="col-md-6">
                                <strong>{{ __('Amount') }}:</strong>
                                <div class="text-success fw-bold">
                                    {{ \App\Helpers\Helper::formatCurrency($recharge->amount) }}
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>{{ __('Transaction Type') }}:</strong>
                                <div>{{ ucfirst($recharge->transaction_type) }}</div>
                            </div>

                            <div class="col-md-6">
                                <strong>{{ __('Money Flow') }}:</strong>
                                <div>
                                    @if ($recharge->money_flow == 'in')
                                        <span class="badge rounded-pill badge-success">IN</span>
                                    @else
                                        <span class="badge rounded-pill badge-danger">OUT</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>{{ __('Description') }}:</strong>
                            <div>
                                {{ $recharge->description ?? 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <strong>{{ __('Date') }}:</strong>
                            <div>{{ $recharge->created_at->format('d M Y, h:i A') }}</div>
                        </div>

                    </div>
                </div>
            </div>


            {{-- RIGHT SIDE - USER INFO --}}
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-user me-2 text-info"></i>
                            {{ __('User Information') }}
                        </h5>
                    </div>

                    <div class="card-body text-center">

                        <img src="{{ $recharge->user->image ? asset($recharge->user->image) : asset('assets/img/avatars/1.png') }}"
                            class="rounded-circle mb-3" width="90" height="90">

                        <h6 class="mb-1">{{ $recharge->user->name }}</h6>
                        <small class="text-muted d-block mb-2">
                            {{ '@' . $recharge->user->username }}
                        </small>

                        <div class="text-start mt-3">
                            <p class="mb-1">
                                <i class="ti ti-mail me-2"></i>
                                {{ $recharge->user->email }}
                            </p>
                            <p class="mb-1">
                                <i class="ti ti-phone me-2"></i>
                                {{ $recharge->user->phone ?? 'N/A' }}
                            </p>
                            <p class="mb-0">
                                <i class="ti ti-activity me-2"></i>
                                <span
                                    class="badge me-4 bg-label-{{ $recharge->user->is_active == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($recharge->user->is_active) }}
                                </span>
                            </p>
                        </div>

                    </div>
                </div>

                {{-- BANK / CRYPTO DETAILS --}}
                @php
                    $bank = \App\Models\UserBankDetail::where('user_id', $recharge->user_id)->first();
                @endphp

                @if ($bank)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ti ti-building-bank me-2 text-warning"></i>
                                {{ __('Payment Method Details') }}
                            </h5>
                        </div>

                        <div class="card-body">

                            @if ($bank->method == 'bank')
                                <p class="mb-1"><strong>Bank:</strong> {{ $bank->bank_name }}</p>
                                <p class="mb-1"><strong>Beneficiary:</strong> {{ $bank->beneficiary_name }}</p>
                                <p class="mb-1"><strong>Account #:</strong> {{ $bank->account_number }}</p>
                                <p class="mb-1"><strong>Type:</strong> {{ ucfirst($bank->account_type) }}</p>
                                <p class="mb-1"><strong>IFSC:</strong> {{ $bank->ifsc_code }}</p>
                                <p class="mb-0"><strong>Branch:</strong> {{ $bank->branch }}</p>
                            @else
                                <p class="mb-1"><strong>Crypto Type:</strong> {{ $bank->crypto_type }}</p>
                                <p class="mb-0"><strong>Address:</strong> {{ $bank->crypto_address }}</p>
                            @endif

                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>
@endsection
