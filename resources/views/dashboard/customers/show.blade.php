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

                    <img
                        src="{{ $customer->image ? asset($customer->image) : asset('assets/img/default/user.png') }}"
                        class="rounded-circle mb-3"
                        width="120"
                        height="120"
                        style="object-fit:cover;"
                    >

                    <h5 class="mb-1">{{ $customer->name }}</h5>
                    <p class="text-muted mb-1">{{ '@'.$customer->username }}</p>
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

                    @if($customer->is_approved == '0')
                        <div class="mt-3">
                            <form action="{{ route('dashboard.customers.approve',$customer->id) }}" method="POST">
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
            @if($customer->inviter)
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
                <div class="card-header">
                    <h6 class="mb-0">Bank / Payment Details</h6>
                </div>
                <div class="card-body">

                    @if($customer->bankDetails)
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Method:</strong> {{ ucfirst($customer->bankDetails->method) }}</p>
                                <p><strong>Bank:</strong> {{ $customer->bankDetails->bank_name ?? '-' }}</p>
                                <p><strong>Account #:</strong> {{ $customer->bankDetails->account_number ?? '-' }}</p>
                                <p><strong>IFSC:</strong> {{ $customer->bankDetails->ifsc_code ?? '-' }}</p>
                            </div>

                            <div class="col-md-6">
                                <p><strong>Crypto:</strong> {{ $customer->bankDetails->crypto_type ?? '-' }}</p>
                                <p><strong>Crypto Address:</strong> {{ $customer->bankDetails->crypto_address ?? '-' }}</p>
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
                                        <span class="badge bg-{{
                                            $order->status == 'completed' ? 'success' :
                                            ($order->status == 'pending' ? 'warning' : 'danger') }}">
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $index => $trx)
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
                            @foreach($withdraws as $withdraw)
                                <tr>
                                    <td>{{ \App\Helpers\Helper::formatCurrency($withdraw->amount) }}</td>
                                    <td>{{ $withdraw->user_note ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{
                                            $withdraw->status == 'approved' ? 'success' :
                                            ($withdraw->status == 'pending' ? 'warning' : 'danger') }}">
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

<!-- WALLET UPDATE MODAL -->
<div class="modal fade" id="walletModal">
    <div class="modal-dialog">
        <form action="{{ route('dashboard.customers.wallet.update',$customer->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Wallet Balance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label>Balance</label>
                        <input type="number" step="0.01" name="balance" value="{{ $customer->wallet->balance }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Freeze</label>
                        <input type="number" step="0.01" name="freeze_balance" value="{{ $customer->wallet->freeze_balance }}" class="form-control" required>
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
        <form action="{{ route('dashboard.customers.score.update',$customer->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Credit Score</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label>Credit Score</label>
                        <input type="number" max="100" min="0" name="credit_score" value="{{ $customer->credit_score }}" class="form-control" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary w-100">Update Score</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
