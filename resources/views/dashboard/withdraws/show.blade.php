@extends('layouts.master')

@section('title', __('Withdraw Details'))

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.withdraws.index') }}">{{ __('Withdraws') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">

            <!-- LEFT SIDE -->
            <div class="col-lg-8">

                <!-- Withdraw Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-cash me-2"></i>
                            Withdraw Information
                        </h5>
                    </div>
                    <div class="card-body">

                        <div class="row mb-3">

                            <div class="col-md-6 mb-3">
                                <strong>Withdraw ID:</strong><br>
                                #{{ $withdraw->id }}
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Status:</strong><br>
                                @if ($withdraw->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($withdraw->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Amount:</strong><br>
                                <span class="fw-bold text-success">
                                    ${{ number_format($withdraw->amount, 2) }}
                                </span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Wallet Address:</strong><br>
                                <span class="text-muted">
                                    {{ $withdraw->wallet_address }}
                                </span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Requested At:</strong><br>
                                {{ $withdraw->created_at->format('d M Y, h:i A') }}
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Last Updated:</strong><br>
                                {{ $withdraw->updated_at->format('d M Y, h:i A') }}
                            </div>

                        </div>

                    </div>
                </div>

                <!-- Transaction Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-receipt-2 me-2"></i>
                            Transaction Information
                        </h5>
                    </div>
                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <strong>Transaction ID:</strong><br>
                                {{ $withdraw->transaction->transaction_id }}
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Money Flow:</strong><br>
                                @if ($withdraw->transaction->money_flow == 'out')
                                    <span class="badge bg-danger">Out</span>
                                @else
                                    <span class="badge bg-success">In</span>
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Transaction Type:</strong><br>
                                <span class="text-capitalize">
                                    {{ str_replace('_', ' ', $withdraw->transaction->transaction_type) }}
                                </span>
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Transaction Status:</strong><br>
                                @if ($withdraw->transaction->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($withdraw->transaction->status == 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @elseif($withdraw->transaction->status == 'cancelled')
                                    <span class="badge bg-secondary">Cancelled</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </div>

                            <div class="col-md-12 mb-3">
                                <strong>Description:</strong><br>
                                <span class="text-muted">
                                    {{ $withdraw->transaction->description ?? 'N/A' }}
                                </span>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- Notes -->
                @if ($withdraw->admin_note || $withdraw->user_note)
                    <div class="card">
                        <div class="card-header">
                            <i class="ti ti-notes me-2"></i>
                            Notes
                        </div>
                        <div class="card-body">

                            @if ($withdraw->user_note)
                                <div class="mb-3">
                                    <strong>User Note:</strong>
                                    <p class="text-muted mb-0">
                                        {{ $withdraw->user_note }}
                                    </p>
                                </div>
                            @endif

                            @if ($withdraw->admin_note)
                                <div>
                                    <strong>Admin Note:</strong>
                                    <p class="text-muted mb-0">
                                        {{ $withdraw->admin_note }}
                                    </p>
                                </div>
                            @endif

                        </div>
                    </div>
                @endif

            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-4">

                <!-- User Info -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="ti ti-user me-2"></i>
                            User Information
                        </h6>
                    </div>
                    <div class="card-body text-center">

                        @if ($withdraw->user->image)
                            <img src="{{ asset($withdraw->user->image) }}" class="rounded-circle mb-3" width="80"
                                height="80">
                        @else
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width:80px;height:80px;">
                                <i class="ti ti-user"></i>
                            </div>
                        @endif

                        <h6 class="mb-1">{{ $withdraw->user->name }}</h6>
                        <small class="text-muted d-block">{{ $withdraw->user->email }}</small>
                        <small class="text-muted d-block">{{ $withdraw->user->phone }}</small>

                        <hr>

                        <small class="text-muted d-block">
                            Username: {{ $withdraw->user->username }}
                        </small>

                        <small class="text-muted d-block">
                            Credit Score: {{ $withdraw->user->credit_score }}
                        </small>

                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
