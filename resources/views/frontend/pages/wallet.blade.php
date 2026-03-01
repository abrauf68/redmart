@extends('frontend.layouts.master')

@section('title', 'Wallet')

@section('css')
    <style>
        .wallet-card {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            border-radius: 25px;
            padding: 25px;
            color: #17232D;
        }

        .wallet-balance {
            font-size: 28px;
            font-weight: bold;
        }

        .wallet-address {
            font-size: 12px;
            word-break: break-all;
            background: rgba(0, 0, 0, 0.1);
            padding: 8px;
            border-radius: 10px;
        }

        .action-btn {
            border-radius: 15px;
            font-weight: 600;
        }

        .transaction-card {
            background: #1F2E3A;
            color: #fff;
            border-radius: 18px;
            padding: 15px;
        }

        .transaction-amount.in {
            color: #28a745;
            font-weight: bold;
        }

        .transaction-amount.out {
            color: #dc3545;
            font-weight: bold;
        }

        .no-transactions {
            background: #101820;
            padding: 40px 20px;
            border-radius: 20px;
            text-align: center;
            color: #ccc;
        }

        .badge-status {
            font-size: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="row mt-5 mb-4">
        <div class="col-12 px-3">

            <!-- WALLET CARD -->
            <div class="wallet-card mb-4">
                <div>
                    <p class="mb-1">Available Balance</p>
                    <div class="wallet-balance">
                        {{ \App\Helpers\Helper::formatCurrency($wallet->balance) }}
                    </div>

                    <!-- INFO TAGS -->
                    <div class="mt-2 d-flex flex-wrap gap-2">

                        <!-- Credit Score Tag -->
                        <div class="px-3 py-1 rounded-pill"
                            style="background: #000; color: #fff; font-size: 13px; font-weight: 500;">
                            Credit Score: <span
                                class="@if (Auth::user()->credit_score < 100) text-danger @else text-success @endif">
                                {{ Auth::user()->credit_score }}
                            </span>
                        </div>

                        <!-- Frozen Amount Tag -->
                        <div class="px-3 py-1 rounded-pill"
                            style="background: red; color: #fff; font-size: 13px; font-weight: 500;">
                            Frozen Amount: {{ \App\Helpers\Helper::formatCurrency($totalFreeze ?? 0) }}
                        </div>

                    </div>
                </div>

                <div class="mt-3">
                    <small>Wallet Address</small>
                    <div class="wallet-address mt-1">
                        {{ $wallet->wallet_address }}
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-6">
                        <a href="{{ route('frontend.recharge') }}" class="btn btn-dark w-100 action-btn">
                            Deposit
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('frontend.withdraw') }}" class="btn btn-outline-dark w-100 action-btn">
                            Withdraw
                        </a>
                    </div>
                </div>
            </div>


            <!-- TRANSACTION HISTORY -->
            <h5 class="text-white mb-3">Transaction History</h5>

            @if ($transactions->isEmpty())
                <div class="no-transactions">
                    <p class="mb-2">No transactions yet</p>
                    <small>Your wallet activity will appear here</small>
                </div>
            @else
                <div class="row g-3">
                    @foreach ($transactions as $transaction)
                        <div class="col-12">
                            <div class="transaction-card d-flex justify-content-between align-items-center">

                                <div>
                                    <p class="mb-1 fw-bold">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}
                                    </p>

                                    <small class="text-muted">
                                        {{ $transaction->created_at->format('d M Y, h:i A') }}
                                    </small>

                                    <div>
                                        <span
                                            class="badge
                                        @if ($transaction->status == 'completed') bg-success
                                        @elseif($transaction->status == 'pending') bg-warning
                                        @elseif($transaction->status == 'failed') bg-danger
                                        @else bg-secondary @endif badge-status">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <div class="transaction-amount {{ $transaction->money_flow }}">
                                        @if ($transaction->money_flow == 'in')
                                            +
                                        @else
                                            -
                                        @endif
                                        {{ \App\Helpers\Helper::formatCurrency($transaction->amount) }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endsection

@section('script')
@endsection
