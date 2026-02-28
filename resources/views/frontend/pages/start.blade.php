@extends('frontend.layouts.master')

@section('title', 'Start')

@section('css')
    <style>
        .spinme {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12 px-3">

            <!-- Wallet Card -->
            <div class="card border-0 rounded-20 p-4 mb-4 text-white"
                style="background: linear-gradient(180deg,#1F2E3A,#17232D);">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">My Wallet</h6>
                    <i class="bi bi-wallet2" style="font-size:20px;color:#D8C79A;"></i>
                </div>

                <h3 class="fw-bold mb-1">{{ \App\Helpers\Helper::formatCurrency($wallet->balance) }}</h3>
                <small class="text-muted">Available Balance</small>

                <hr style="border-color: rgba(255,255,255,0.1);">

                <div class="d-flex justify-content-between">
                    <div>
                        <small class="text-muted">Pending</small>
                        <p class="mb-0">{{ \App\Helpers\Helper::formatCurrency($pendingCommission) }}</p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">Commission</small>
                        <p class="mb-0 text-warning">{{ \App\Helpers\Helper::formatCurrency($earnedCommission) }}</p>
                    </div>
                </div>

            </div>

            <!-- Stats Grid -->
            <div class="row g-3 mb-4">

                <div class="col-6">
                    <div class="card border-0 rounded-20 p-3 text-center text-white" style="background:#101820;">
                        <i class="bi bi-bag-fill mb-2" style="font-size:22px;color:#D8C79A;"></i>
                        <h6 class="mb-0">{{ $totalOrders }}</h6>
                        <small class="text-muted">Total Orders</small>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card border-0 rounded-20 p-3 text-center text-white" style="background:#101820;">
                        <i class="bi bi-hourglass-split mb-2" style="font-size:22px;color:#ffc107;"></i>
                        <h6 class="mb-0">{{ $pendingOrders }}</h6>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card border-0 rounded-20 p-3 text-center text-white" style="background:#101820;">
                        <i class="bi bi-check-circle-fill mb-2" style="font-size:22px;color:#28a745;"></i>
                        <h6 class="mb-0">{{ $completedOrders }}</h6>
                        <small class="text-muted">Completed</small>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card border-0 rounded-20 p-3 text-center text-white" style="background:#101820;">
                        <i class="bi bi-cash-stack mb-2" style="font-size:22px;color:#D8C79A;"></i>
                        <h6 class="mb-0">{{ \App\Helpers\Helper::formatCurrency($earnedCommission + $pendingCommission) }}
                        </h6>
                        <small class="text-muted">Total Commission</small>
                    </div>
                </div>

            </div>

            <!-- Grab Order Button -->
            <a href="javascript:void(0)" id="grabOrderBtn" class="btn btn-lg rounded-20 fw-bold w-100"
                style="background: linear-gradient(180deg,#D8C79A,#B8A06F); color:#17232D;">
                <span id="grabBtnText">
                    <i class="bi bi-lightning-charge-fill me-2"></i>
                    GRAB ORDER
                </span>
                <span id="grabBtnSpinner" class="d-none">
                    <i class="bi bi-gear-fill spinme"></i> Grabbing...
                </span>
            </a>

        </div>
    </div>

    <div class="modal fade" id="orderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm"> <!-- modal-sm for mobile -->
            <div class="modal-content rounded-20" style="background:#1F2E3A;color:#fff;">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <figure class="mb-3">
                        <img id="modalProductImage" src="" alt="Product Image" class="img-fluid rounded-15"
                            style="max-height:120px;">
                    </figure>
                    <p><strong>Product:</strong> <span id="modalProduct"></span></p>
                    <p><strong>Price:</strong> <span id="modalPrice"></span></p>
                    <p><strong>Quantity:</strong> <span id="modalQuantity"></span></p>
                    <p><strong>Subtotal:</strong> <span id="modalSubtotal"></span></p>
                    <p><strong>Total:</strong> <span id="modalTotal"></span></p>
                    <hr>
                    <p class="text-warning"><strong>Commission:</strong> <span id="modalCommission"></span></p>
                </div>
                <button id="modalProceedBtn" class="btn btn-success rounded-15 w-100" data-id="">
                    Proceed
                </button>
            </div>
        </div>
    </div>

    <!-- Pending Order Modal -->
    <div class="modal fade" id="pendingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-20" style="background:#1F2E3A;color:#fff;">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Pending Order</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-exclamation-circle-fill mb-3" style="font-size:40px;color:#ffc107;"></i>
                    <p class="mb-0">You have a pending order. Please proceed with it first.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-warning rounded-15 w-100" data-bs-dismiss="modal">
                        OK
                    </button>
                </div>
            </div>
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
        document.getElementById('grabOrderBtn').addEventListener('click', function() {

            const btnText = document.getElementById('grabBtnText');
            const btnSpinner = document.getElementById('grabBtnSpinner');

            // Show spinner
            btnText.classList.add('d-none');
            btnSpinner.classList.remove('d-none');

            fetch("{{ route('frontend.grab.order') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(res => res.json())
                .then(data => {

                    // Hide spinner after 3 seconds
                    setTimeout(() => {
                        btnText.classList.remove('d-none');
                        btnSpinner.classList.add('d-none');

                        if (data.status) {
                            document.getElementById('modalProductImage').src = "{{ asset('') }}" +
                                data.product_image;
                            document.getElementById('modalProduct').innerText = data.product_name;
                            document.getElementById('modalPrice').innerText = data.price;
                            document.getElementById('modalQuantity').innerText = data.quantity || 1;
                            document.getElementById('modalSubtotal').innerText = data.subtotal;
                            document.getElementById('modalTotal').innerText = data.total;
                            document.getElementById('modalCommission').innerText = data.commission;
                            document.getElementById('modalProceedBtn').setAttribute('data-id', data
                                .order_id);

                            // Show order modal
                            var myModal = new bootstrap.Modal(document.getElementById('orderModal'));
                            myModal.show();

                        } else {
                            // Show pending modal
                            var pendingModal = new bootstrap.Modal(document.getElementById(
                                'pendingModal'));
                            pendingModal.show();
                        }

                    }, 3000); // 3 sec delay
                });
        });
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'modalProceedBtn') {
                let orderId = e.target.dataset.id;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`{{ route('frontend.order.proceed', '') }}`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        credentials: "same-origin",
                        body: JSON.stringify({
                            order_id: orderId
                        }) // send ID in request body
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            // Close order modal
                            let orderModalEl = document.getElementById('orderModal');
                            let orderModal = bootstrap.Modal.getInstance(orderModalEl);
                            if (orderModal) orderModal.hide();

                            // Show success modal
                            let successModal = new bootstrap.Modal(
                                document.getElementById('successModal')
                            );
                            successModal.show();

                            setTimeout(() => location.reload(), 2500);
                        } else if (data.type === 'insufficient') {
                            let orderModalEl = document.getElementById('orderModal');
                            let orderModal = bootstrap.Modal.getInstance(orderModalEl);
                            if (orderModal) orderModal.hide();

                            setTimeout(() => {
                                let insufficientModal = new bootstrap.Modal(
                                    document.getElementById('insufficientModal')
                                );
                                insufficientModal.show();
                            }, 300);
                        } else {
                            alert("Something went wrong.");
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Something went wrong.");
                    });
            }
        });
    </script>
@endsection
