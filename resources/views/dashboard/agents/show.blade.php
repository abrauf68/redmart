@extends('layouts.master')

@section('title', __('Agent Details'))

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.agents.index') }}">{{ __('Agents') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">

            <!-- Agent Profile Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">

                        <img src="{{ $agent->image ? asset($agent->image) : asset('assets/img/default/user.png') }}"
                            class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">

                        <h5 class="mb-1">{{ $agent->name }}</h5>
                        <p class="text-muted mb-1">{{ '@' . $agent->username }}</p>
                        <p class="text-muted">{{ $agent->email }}</p>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge bg-{{ $agent->is_active == 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($agent->is_active) }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span>Approved:</span>
                            <span class="badge bg-{{ $agent->is_approved == '1' ? 'success' : 'warning' }}">
                                {{ $agent->is_approved == '1' ? 'Yes' : 'No' }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Wallet + Stats -->
            <div class="col-md-8">
                <div class="row">

                    <!-- Referral Count -->
                    <div class="col-md-6 mb-4">
                        <div class="card bg-label-success">
                            <div class="card-body">
                                <h6 class="card-title">Total Referrals</h6>
                                <h4 class="mb-1">
                                    {{ $agent->referrals->count() }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    <!-- Referral Count -->
                    <div class="col-md-6 mb-4">
                        <div class="card bg-label-warning">
                            <div class="card-body">
                                <h6 class="card-title">Pending Referrals</h6>
                                <h4 class="mb-1">
                                    {{ $pendingReferralsCount }}
                                </h4>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Referral List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Referral Details</h5>
                    </div>
                    <div class="card-body p-0">

                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Balance</th>
                                        <th>Is Appr.</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($agent->referrals as $referral)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $referral->image ? asset($referral->image) : asset('assets/img/default/user.png') }}"
                                                        class="rounded-circle me-2" width="35" height="35"
                                                        style="object-fit: cover;">
                                                    <div>
                                                        <strong>{{ $referral->name }}</strong><br>
                                                        <small class="text-muted">{{ '@' . $referral->username }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $referral->email }}</td>
                                            <td>
                                                {{ number_format(optional($referral->wallet)->balance ?? 0, 2) }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $referral->is_approved == '1' ? 'success' : 'danger' }}">
                                                    {{ $referral->is_approved == '1' ? 'Approved' : 'Pending' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $referral->is_active == 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($referral->is_active) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                No referrals found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
