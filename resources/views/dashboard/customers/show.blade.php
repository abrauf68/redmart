@extends('layouts.master')

@section('title', __('Customer Details'))

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.customers.index') }}">{{ __('Customers') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">

            <!-- LEFT SIDE PROFILE -->
            <div class="col-md-4">

                <div class="card">
                    <div class="card-body text-center">

                        <img src="{{ $customer->image ? asset($customer->image) : asset('assets/img/default/user.png') }}"
                            class="rounded-circle mb-3" width="120" height="120" style="object-fit:cover;">

                        <h5 class="mb-1">{{ $customer->name }}</h5>
                        <p class="text-muted mb-1">{{ '@' . $customer->username }}</p>
                        <p class="text-muted">{{ $customer->email }}</p>
                        <p class="text-muted">{{ $customer->phone ?? '-' }}</p>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span>Status:</span>
                            <span class="badge bg-{{ $customer->is_active == 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($customer->is_active) }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span>Approved:</span>
                            <span class="badge bg-{{ $customer->is_approved == '1' ? 'success' : 'warning' }}">
                                {{ $customer->is_approved == '1' ? 'Approved' : 'Pending' }}
                            </span>
                        </div>

                        @if ($customer->is_approved == '0')
                            <div class="mt-3">
                                <form action="{{ route('dashboard.customers.approve', $customer->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-success btn-sm w-100">
                                        Approve Customer
                                    </button>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- WALLET -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0">Wallet</h6>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#walletModal">
                            Update
                        </button>
                    </div>
                    <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <span>Balance:</span>
                            <strong>{{ \App\Helpers\Helper::formatCurrency(optional($customer->wallet)->balance ?? 0) }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span>Freeze:</span>
                            <strong>{{ \App\Helpers\Helper::formatCurrency(optional($customer->wallet)->freeze_balance ?? 0) }}</strong>
                        </div>

                    </div>
                </div>

                <!-- Special Order -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0">Special Order</h6>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#specialOrderModal">
                            Update
                        </button>
                    </div>
                    <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <span>Order No:</span>
                            <strong>{{ $customer->special_order_number }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Multiplier:</span>
                            <strong>x{{ $customer->special_multiplier }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Commission Percentage:</span>
                            <strong>{{ $customer->special_commission_percentage }}</strong>
                        </div>

                    </div>
                </div>

                <!-- Credit Score -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0">Credit Score</h6>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#scoreModal">
                            Update
                        </button>
                    </div>
                    <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <span>Score:</span>
                            <strong>{{ $customer->credit_score }}</strong>
                        </div>

                    </div>
                </div>

                <!-- INVITER -->
                @if ($customer->inviter)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0">Agent Info</h6>
                        </div>
                        <div class="card-body">
                            <strong>{{ $customer->inviter->name }}</strong>
                            <p class="mb-0 text-muted">{{ $customer->inviter->email }}</p>
                        </div>
                    </div>
                @endif

            </div>

            <!-- RIGHT SIDE DETAILS -->
            <div class="col-md-8">

                <!-- BANK DETAILS -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="mb-0">Bank / Payment Details</h6>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bankDetailsModal">
                            Update
                        </button>
                    </div>
                    <div class="card-body">

                        @if (optional($customer->bankDetails))
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Method:</strong> {{ ucfirst(optional($customer->bankDetails)->method) }}</p>
                                    <p><strong>Bank:</strong> {{ optional($customer->bankDetails)->bank_name ?? '-' }}</p>
                                    <p><strong>Account #:</strong>
                                        {{ optional($customer->bankDetails)->account_number ?? '-' }}</p>
                                    <p><strong>IFSC:</strong> {{ optional($customer->bankDetails)->ifsc_code ?? '-' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <p><strong>Crypto:</strong> {{ optional($customer->bankDetails)->crypto_type ?? '-' }}
                                    </p>
                                    <p><strong>Crypto Address:</strong>
                                        {{ optional($customer->bankDetails)->crypto_address ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">No bank details found.</p>
                        @endif

                    </div>
                </div>

                <!-- ORDERS -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Orders</h6>
                    </div>
                    <div class="card-body table-responsive">

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Product</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_no }}</td>
                                        <td>{{ $order->product->name ?? '-' }}</td>
                                        <td>{{ \App\Helpers\Helper::formatCurrency($order->total) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Orders</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>

                <!-- TRANSACTIONS -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Recent Transactions</h6>
                    </div>
                    <div class="card-body table-responsive">

                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Type</th>
                                    <th>Flow</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $index => $trx)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ ucfirst($trx->transaction_type) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $trx->money_flow == 'in' ? 'success' : 'danger' }}">
                                                {{ $trx->money_flow }}
                                            </span>
                                        </td>
                                        <td>{{ \App\Helpers\Helper::formatCurrency($trx->amount) }}</td>
                                        <td>{{ ucfirst($trx->status) }}</td>
                                        <td>
                                            <a href="{{ route('dashboard.transactions.receipt', $trx->id) }}"
                                                class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Receipt') }}">
                                                <i class="ti ti-receipt ti-md"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>

                <!-- WITHDRAW REQUESTS -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Withdraw Requests</h6>
                    </div>
                    <div class="card-body table-responsive">

                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>User Note</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($withdraws as $withdraw)
                                    <tr>
                                        <td>{{ \App\Helpers\Helper::formatCurrency($withdraw->amount) }}</td>
                                        <td>{{ $withdraw->user_note ?? 'N/A' }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $withdraw->status == 'approved' ? 'success' : ($withdraw->status == 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($withdraw->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $withdraw->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- BANK DETAILS UPDATE MODAL -->
    <div class="modal fade" id="bankDetailsModal">
        <div class="modal-dialog">
            <form action="{{ route('dashboard.customers.bank-details.update', $customer->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Bank Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="method" class="form-label">{{ __('Method') }}</label>
                            <select name="method" id="method" class="form-select select2" required>
                                <option value="bank"
                                    {{ optional($customer->bankDetails)->method == 'bank' ? 'selected' : '' }}>
                                    {{ __('Bank') }}</option>
                                <option value="crypto"
                                    {{ optional($customer->bankDetails)->method == 'crypto' ? 'selected' : '' }}>
                                    {{ __('Crypto') }}</option>
                            </select>
                        </div>

                        <h6 class="italic text-muted">Bank Details</h6>
                        <hr>

                        <div class="mb-3">
                            <label for="bank_name">Bank Name</label>
                            <select name="bank_name" class="form-select select2">
                                <option value="">Select Bank</option>
                                @php $bank = optional(optional($customer->bankDetails))->bank_name; @endphp

                                @foreach (['State Bank of India', 'HDFC Bank', 'ICICI Bank', 'Axis Bank', 'Punjab National Bank', 'Bank of Baroda', 'Kotak Mahindra Bank', 'IndusInd Bank', 'Yes Bank', 'Union Bank of India', 'Other'] as $b)
                                    <option value="{{ $b }}" {{ $bank == $b ? 'selected' : '' }}>
                                        {{ $b }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="beneficiary_name">Beneficiary Name</label>
                            <input type="text" name="beneficiary_name"
                                value="{{ optional($customer->bankDetails)->beneficiary_name }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="account_number">Account Number</label>
                            <input type="text" name="account_number"
                                value="{{ optional($customer->bankDetails)->account_number }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="account_type">Account Type</label>
                            @php $accountType = optional(optional($customer->bankDetails))->account_type; @endphp
                            <select name="account_type" class="form-select select2">
                                <option value="">Select Account Type</option>
                                @foreach ([
            'savings' => 'Savings',
            'current' => 'Current',
            'salary' => 'Salary',
            'fixed_deposit' => 'Fixed Deposit',
            'nri' => 'NRI',
            'recurring_deposit' => 'Recurring Deposit',
            'demat' => 'Demat',
            'others' => 'Others',
        ] as $value => $label)
                                    <option value="{{ $value }}" {{ $accountType == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="ifsc_code">IFSC Code</label>
                            <input type="text" name="ifsc_code"
                                value="{{ optional($customer->bankDetails)->ifsc_code }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="branch">Branch</label>
                            <input type="text" name="branch" value="{{ optional($customer->bankDetails)->branch }}"
                                class="form-control">
                        </div>

                        <h6 class="italic text-muted">Crpto Details</h6>
                        <hr>

                        <div class="mb-3">
                            <label for="crypto_type">Crypto Type</label>
                            @php $crypto = optional(optional($customer->bankDetails))->crypto_type; @endphp
                            <select name="crypto_type" class="form-select select2">
                                <option value="">Select Crypto Type</option>
                                @foreach (['BTC', 'ETH', 'USDT', 'BUSD', 'BNB', 'Other'] as $c)
                                    <option value="{{ $c }}" {{ $crypto == $c ? 'selected' : '' }}>
                                        {{ $c }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="crypto_address">Crypto Address</label>
                            <input type="text" name="crypto_address"
                                value="{{ optional($customer->bankDetails)->crypto_address }}" class="form-control">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary w-100">Update Bank Details</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- WALLET UPDATE MODAL -->
    <div class="modal fade" id="walletModal">
        <div class="modal-dialog">
            <form action="{{ route('dashboard.customers.wallet.update', $customer->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Wallet Balance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Balance</label>
                            <input type="number" step="0.01" name="balance"
                                value="{{ $customer->wallet->balance }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Freeze</label>
                            <input type="number" step="0.01" name="freeze_balance"
                                value="{{ $customer->wallet->freeze_balance }}" class="form-control" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary w-100">Update Wallet</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Credit Score UPDATE MODAL -->
    <div class="modal fade" id="scoreModal">
        <div class="modal-dialog">
            <form action="{{ route('dashboard.customers.score.update', $customer->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Credit Score</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Credit Score</label>
                            <input type="number" max="100" min="0" name="credit_score"
                                value="{{ $customer->credit_score }}" class="form-control" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary w-100">Update Score</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Special Order Modal UPDATE MODAL -->
    <div class="modal fade" id="specialOrderModal">
        <div class="modal-dialog">
            <form action="{{ route('dashboard.customers.special-order.update', $customer->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Special Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Order No</label>
                            <input type="number" min="1" name="special_order_number"
                                value="{{ $customer->special_order_number }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>
                                Multiplier
                                <small id="multiplierPreview">
                                    ~
                                    {{ \App\Helpers\Helper::formatCurrency($customer->special_multiplier * $customer->wallet->balance) }}
                                </small>
                            </label>

                            <input type="number" min="0" step="0.01" id="special_multiplier"
                                name="special_multiplier" value="{{ $customer->special_multiplier }}"
                                class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>
                                Commission Rate (%)
                                <small id="commissionPreview">
                                    ~
                                    {{ \App\Helpers\Helper::formatCurrency(
                                        ($customer->special_commission_percentage / 100) * ($customer->special_multiplier * $customer->wallet->balance),
                                    ) }}
                                </small>
                            </label>

                            <input type="number" max="100" min="0" id="special_commission_percentage"
                                name="special_commission_percentage"
                                value="{{ $customer->special_commission_percentage }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Order Limit</label>
                            <input type="number" min="1" name="order_limit"
                                value="{{ $customer->order_limit }}" class="form-control" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary w-100">Update Special Order</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let balance = {{ $customer->wallet->balance }};
            let multiplierInput = document.getElementById('special_multiplier');
            let commissionInput = document.getElementById('special_commission_percentage');

            let multiplierPreview = document.getElementById('multiplierPreview');
            let commissionPreview = document.getElementById('commissionPreview');

            function formatCurrency(amount) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD' // change if needed
                }).format(amount);
            }

            function updatePreview() {
                let multiplier = parseFloat(multiplierInput.value) || 0;
                let commissionPercent = parseFloat(commissionInput.value) || 0;

                let subtotal = multiplier * balance;
                let commission = (commissionPercent / 100) * subtotal;

                multiplierPreview.innerText = "~ " + formatCurrency(subtotal);
                commissionPreview.innerText = "~ " + formatCurrency(commission);
            }

            multiplierInput.addEventListener('input', updatePreview);
            commissionInput.addEventListener('input', updatePreview);

        });
    </script>
@endsection
