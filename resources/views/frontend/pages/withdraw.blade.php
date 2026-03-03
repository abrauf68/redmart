@extends('frontend.layouts.master')

@section('title', 'Withdraw')

@section('css')
    <style>
        .balance-card {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            border-radius: 25px;
            padding: 20px;
            color: #17232D;
        }

        .balance-amount {
            font-size: 26px;
            font-weight: bold;
        }

        .withdraw-card {
            background: #1F2E3A;
            border-radius: 20px;
            padding: 20px;
            color: #fff;
        }

        .form-control {
            border-radius: 12px;
            background: #101820;
            border: none;
            color: #fff;
        }

        .form-control:focus {
            background: #101820;
            color: #fff;
            box-shadow: none;
            border: 1px solid #D8C79A;
        }

        .btn-withdraw {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            font-weight: bold;
            border-radius: 15px;
        }

        .withdraw-history-card {
            background: #101820;
            border-radius: 18px;
            padding: 15px;
            color: #fff;
        }

        .amount-text {
            font-weight: bold;
            color: #ffc107;
        }

        .no-withdraw {
            background: #101820;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            color: #ccc;
        }

        .bank-warning {
            background: #2a1f1f;
            border: 1px solid #dc3545;
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="row mt-5 mb-4">
        <div class="col-12 px-3">

            <!-- BALANCE CARD -->
            <div class="balance-card mb-4">
                <p class="mb-1">Available Balance</p>
                <div class="balance-amount">
                    {{ \App\Helpers\Helper::formatCurrency($wallet->balance) }}
                </div>
            </div>


            {{-- CHECK BANK DETAILS --}}
            @php
                $bankDetails = $user->bankDetails ?? null;

                $isIncomplete = false;

                if (!$bankDetails) {
                    $isIncomplete = true;
                } else {
                    if ($bankDetails->method === 'crypto') {
                        // Crypto fields check
                        if (empty($bankDetails->crypto_type) || empty($bankDetails->crypto_address)) {
                            $isIncomplete = true;
                        }
                    } else {
                        // Bank fields check
                        if (
                            empty($bankDetails->bank_name) ||
                            empty($bankDetails->beneficiary_name) ||
                            empty($bankDetails->account_number) ||
                            empty($bankDetails->account_type) ||
                            empty($bankDetails->ifsc_code) ||
                            empty($bankDetails->branch)
                        ) {
                            $isIncomplete = true;
                        }
                    }
                }
            @endphp

            @if ($isIncomplete)
                <!-- BANK DETAILS NOT FILLED -->
                <div class="bank-warning mb-4">
                    <h5 class="text-danger mb-3">Receiving Bank Information Incomplete</h5>
                    <p class="mb-3">
                        Please complete your bank details before requesting a withdrawal.
                    </p>
                    <a href="{{ route('frontend.profile') }}" class="btn btn-danger rounded-15 px-4">
                        Complete Bank Details
                    </a>
                </div>
            @else
                <!-- WITHDRAW FORM -->
                <div class="withdraw-card mb-4">
                    <h5 class="mb-3">Request Withdraw</h5>

                    <!-- CREDIT SCORE FLAG (Always Visible) -->
                    <div class="mb-3 d-flex align-items-center p-2 rounded-15"
                        style="background: #1F2E3A; border: 1px solid #D8C79A; color: #fff;">
                        <span class="me-2" style="font-size: 14px; font-weight: bold;">Credit Score:</span>
                        <span>{{ $user->credit_score }}</span>
                        @if ($user->credit_score < 100)
                            <span class="ms-2 small text-danger">Too low to withdraw</span>
                        @else
                            <span class="ms-2 small text-success">Good to withdraw</span>
                        @endif
                    </div>

                    <form action="{{ route('frontend.withdraw.submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" name="amount" step="0.01" class="form-control"
                                placeholder="Enter amount" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Note (Optional)</label>
                            <textarea name="user_note" class="form-control" rows="2" placeholder="Add note (optional)"></textarea>
                        </div>

                        <button type="submit" class="btn btn-withdraw w-100">
                            Submit Withdraw
                        </button>
                    </form>
                </div>
            @endif


            <!-- RECENT WITHDRAWS -->
            <h5 class="text-white mb-3">Recent Withdraws</h5>

            @if ($withdraws->isEmpty())
                <div class="no-withdraw">
                    <p class="mb-0">No withdraw requests yet</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach ($withdraws as $withdraw)
                        <div class="col-12">
                            <div class="withdraw-history-card d-flex justify-content-between align-items-center">

                                <div>
                                    <p class="mb-1 amount-text">
                                        {{ \App\Helpers\Helper::formatCurrency($withdraw->amount) }}
                                    </p>

                                    <small class="text-muted">
                                        {{ $withdraw->created_at->format('d M Y, h:i A') }}
                                    </small>

                                    <div>
                                        <span
                                            class="badge
                                        @if ($withdraw->status == 'approved') bg-success
                                        @elseif($withdraw->status == 'pending') bg-warning
                                        @else bg-danger @endif">
                                            {{ ucfirst($withdraw->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <small class="text-muted">
                                        {{ ucfirst($withdraw->status) }}
                                    </small>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <!-- Dynamic Alert Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-20" style="background:#1F2E3A;color:#fff;">

                <div class="modal-header border-0">
                    <h5 class="modal-title" id="alertModalTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center" id="alertModalBody">
                    <!-- Dynamic message -->
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-success rounded-15 w-100" data-bs-dismiss="modal">
                        OK
                    </button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        window.addEventListener('load', function() {

            // Check if session has success or error
            @if (session('success') || session('error'))
                // Hide the loader immediately
                let loader = document.querySelector('.loader-wrap');
                if (loader) loader.style.display = 'none';
            @endif

            @if (session('success'))
                let successModal = new bootstrap.Modal(document.getElementById('alertModal'));
                document.getElementById('alertModalTitle').innerText = 'Success';
                document.getElementById('alertModalBody').innerText = "{{ session('success') }}";
                successModal.show();
            @endif

            @if (session('error'))
                let errorModal = new bootstrap.Modal(document.getElementById('alertModal'));
                document.getElementById('alertModalTitle').innerText = 'Error';
                document.getElementById('alertModalBody').innerText = "{{ session('error') }}";
                errorModal.show();
            @endif

        });
    </script>
@endsection
