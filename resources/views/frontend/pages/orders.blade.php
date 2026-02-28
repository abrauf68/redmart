@extends('frontend.layouts.master')

@section('title', 'Orders')

@section('css')
    <style>
        .order-card {
            background: #1F2E3A;
            color: #fff;
            border-radius: 20px;
            border: none;
        }

        .order-card img {
            max-width: 80px;
            max-height: 80px;
            border-radius: 15px;
        }

        .no-orders {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            padding: 40px 20px;
            border-radius: 20px;
            text-align: center;
        }

        .btn-proceed {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            font-weight: bold;
        }

        .stat-card {
            background: #101820;
            color: #fff;
            border-radius: 20px;
            text-align: center;
            padding: 15px;
        }

        .stat-card i {
            font-size: 22px;
            margin-bottom: 5px;
            display: block;
        }

        .stat-card small {
            color: #cfcfcf;
        }
    </style>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12 px-3">

            <!-- Stats Grid -->
            <div class="row g-3 mb-3 mt-5">
                <div class="col-4">
                    <div class="stat-card">
                        <i class="bi bi-bag-fill" style="color:#D8C79A;"></i>
                        <h6 class="mb-0">{{ $totalOrders }}</h6>
                        <small>Total Orders</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-card">
                        <i class="bi bi-hourglass-split" style="color:#ffc107;"></i>
                        <h6 class="mb-0">{{ $pendingOrders }}</h6>
                        <small>Pending Orders</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-card">
                        <i class="bi bi-check-circle-fill" style="color:#28a745;"></i>
                        <h6 class="mb-0">{{ $completedOrders }}</h6>
                        <small>Completed Orders</small>
                    </div>
                </div>
            </div>

            @if ($orders->isEmpty())
                <div class="no-orders">
                    <h5>No orders available</h5>
                    <p>Please grab an order to get started!</p>
                    <a href="{{ route('frontend.start') }}" class="btn btn-lg rounded-20 mt-3 fw-bold">
                        Grab Order
                    </a>
                </div>
            @else
                <h5 class="mb-3">My Orders</h5>
                <div class="row g-3">
                    @foreach ($orders as $order)
                        <div class="col-12">
                            <div class="card order-card p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <!-- LEFT SIDE (Image + Details) -->
                                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                                        <img src="{{ asset($order->product->main_image) }}"
                                            alt="{{ $order->product->name }}"
                                            style="width:70px;height:70px;object-fit:cover;border-radius:15px;">

                                        <div>
                                            <p class="mb-1 fw-bold text-white">
                                                {{ $order->product->name }}
                                            </p>

                                            <p class="mb-0 text-muted size-12">
                                                Qty: {{ $order->quantity }} |
                                                Subtotal: {{ \App\Helpers\Helper::formatCurrency($order->subtotal) }}
                                            </p>

                                            <p class="mb-0 text-muted size-12">
                                                Total: {{ \App\Helpers\Helper::formatCurrency($order->total) }} |
                                                Commission: {{ \App\Helpers\Helper::formatCurrency($order->commission) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- RIGHT SIDE (Button / Badge) -->
                                    <div class="ms-3 text-end">
                                        @if ($order->status == 'pending')
                                            <button class="btn btn-proceed rounded-15 px-3 proceedBtn"
                                                data-id="{{ $order->id }}">
                                                Proceed
                                            </button>
                                        @else
                                            <span class="badge bg-success px-3 py-2">
                                                Completed
                                            </span>
                                        @endif
                                    </div>

                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <div class="modal fade" id="insufficientModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-20 text-center p-3" style="background:#1F2E3A;color:#fff;">
                <h6 class="text-warning">Insufficient Funds</h6>
                <p class="small">
                    Please recharge your account to proceed this order.
                </p>
                <a href="{{ route('frontend.recharge') }}" class="btn btn-warning rounded-15 w-100">
                    Recharge Now
                </a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-20 text-center p-4" style="background:#1F2E3A;color:#fff;">

                <div class="mb-3">
                    <i class="bi bi-check-circle-fill" style="font-size:50px;color:#28a745;"></i>
                </div>

                <h5 class="text-success mb-2">Success!</h5>
                <p class="small mb-3">
                    Order completed successfully 🎉
                </p>

                <button type="button" class="btn btn-success rounded-15 w-100" data-bs-dismiss="modal">
                    OK
                </button>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('.proceedBtn').forEach(button => {

            button.addEventListener('click', function() {

                let orderId = this.dataset.id;

                fetch(`/order/proceed/${orderId}`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {

                        if (data.status) {
                            // Show success modal
                            let successModal = new bootstrap.Modal(
                                document.getElementById('successModal')
                            );
                            successModal.show();

                            // Auto reload after 2.5 sec
                            setTimeout(() => {
                                location.reload();
                            }, 2500);
                        } else if (data.type === 'insufficient') {
                            let modal = new bootstrap.Modal(
                                document.getElementById('insufficientModal')
                            );
                            modal.show();
                        } else {
                            alert("Something went wrong.");
                        }

                    });

            });

        });
    </script>
@endsection
