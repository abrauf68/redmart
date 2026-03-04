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

        .star {
            font-size: 26px;
            cursor: pointer;
            color: #555;
            margin: 0 3px;
            transition: 0.2s;
        }

        .star.active {
            color: #ffc107;
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
                    <a href="{{ route('frontend.orders') }}">
                        <div class="card border-0 rounded-20 p-3 text-center text-white" style="background:#101820;">
                            <i class="bi bi-bag-fill mb-2" style="font-size:22px;color:#D8C79A;"></i>
                            <h6 class="mb-0">{{ $totalOrders }}</h6>
                            <small class="text-muted">Total Orders</small>
                        </div>
                    </a>
                </div>

                <div class="col-6">
                    <a href="{{ route('frontend.orders') }}">
                        <div class="card border-0 rounded-20 p-3 text-center text-white" style="background:#101820;">
                            <i class="bi bi-hourglass-split mb-2" style="font-size:22px;color:#ffc107;"></i>
                            <h6 class="mb-0">{{ $pendingOrders }}</h6>
                            <small class="text-muted">Pending</small>
                        </div>
                    </a>
                </div>

                <div class="col-6">
                    <a href="{{ route('frontend.orders') }}">
                        <div class="card border-0 rounded-20 p-3 text-center text-white" style="background:#101820;">
                            <i class="bi bi-check-circle-fill mb-2" style="font-size:22px;color:#28a745;"></i>
                            <h6 class="mb-0">{{ $completedOrders }}</h6>
                            <small class="text-muted">Completed</small>
                        </div>
                    </a>
                </div>

                <div class="col-6">
                    <a href="{{ route('frontend.wallet') }}">
                        <div class="card border-0 rounded-20 p-3 text-center text-white" style="background:#101820;">
                            <i class="bi bi-cash-stack mb-2" style="font-size:22px;color:#D8C79A;"></i>
                            <h6 class="mb-0">
                                {{ \App\Helpers\Helper::formatCurrency($earnedCommission + $pendingCommission) }}
                            </h6>
                            <small class="text-muted">Total Commission</small>
                        </div>
                    </a>
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

    <!-- Limit Order Modal -->
    <div class="modal fade" id="limitModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-20" style="background:#1F2E3A;color:#fff;">
                <div class="modal-header border-0">
                    <h5 class="modal-title">No Order Found</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-exclamation-circle-fill mb-3" style="font-size:40px;color:#ffc107;"></i>
                    <p class="mb-0">There is no orders available at the moment please try again later.</p>
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

    <div class="modal fade" id="ratingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-20 p-4" style="background:#1F2E3A;color:#fff;">

                <div class="text-center mb-3">
                    <img id="ratingProductImage" src=""
                        style="width:90px;height:90px;object-fit:cover;border-radius:15px;">
                    <h6 class="mt-2 mb-0" id="ratingProductName"></h6>
                </div>

                <input type="hidden" id="ratingOrderId">

                <div class="mb-3 text-center">
                    <label class="small d-block mb-2">Product Description</label>
                    <div class="star-group" data-type="description_rating">
                        <i class="bi bi-star-fill star" data-value="1"></i>
                        <i class="bi bi-star-fill star" data-value="2"></i>
                        <i class="bi bi-star-fill star" data-value="3"></i>
                        <i class="bi bi-star-fill star" data-value="4"></i>
                        <i class="bi bi-star-fill star" data-value="5"></i>
                    </div>
                </div>

                <div class="mb-3 text-center">
                    <label class="small d-block mb-2">Logistics Service</label>
                    <div class="star-group" data-type="logistics_rating">
                        <i class="bi bi-star-fill star" data-value="1"></i>
                        <i class="bi bi-star-fill star" data-value="2"></i>
                        <i class="bi bi-star-fill star" data-value="3"></i>
                        <i class="bi bi-star-fill star" data-value="4"></i>
                        <i class="bi bi-star-fill star" data-value="5"></i>
                    </div>
                </div>

                <div class="mb-3 text-center">
                    <label class="small d-block mb-2">Customer Service</label>
                    <div class="star-group" data-type="service_rating">
                        <i class="bi bi-star-fill star" data-value="1"></i>
                        <i class="bi bi-star-fill star" data-value="2"></i>
                        <i class="bi bi-star-fill star" data-value="3"></i>
                        <i class="bi bi-star-fill star" data-value="4"></i>
                        <i class="bi bi-star-fill star" data-value="5"></i>
                    </div>
                </div>

                <button class="btn btn-success rounded-15 w-100 mt-2" id="submitOrderBtn">
                    Submit Order
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
                            if(data.is_limit_reached){
                                // Show limit modal
                                var limitModal = new bootstrap.Modal(document.getElementById(
                                    'limitModal'));
                                limitModal.show();
                            }else{
                                // Show pending modal
                                var pendingModal = new bootstrap.Modal(document.getElementById(
                                    'pendingModal'));
                                pendingModal.show();
                            }
                        }

                    }, 3000); // 3 sec delay
                });
        });
        let ratings = {
            description_rating: 1,
            logistics_rating: 1,
            service_rating: 1
        };

        // When Proceed button clicked inside Order Modal
        document.addEventListener('click', function(e) {

            if (e.target && e.target.id === 'modalProceedBtn') {

                let orderId = e.target.dataset.id;

                // close order modal
                let orderModalEl = document.getElementById('orderModal');
                let orderModal = bootstrap.Modal.getInstance(orderModalEl);
                if (orderModal) orderModal.hide();

                // set rating modal data
                document.getElementById('ratingOrderId').value = orderId;
                document.getElementById('ratingProductImage').src =
                    document.getElementById('modalProductImage').src;
                document.getElementById('ratingProductName').innerText =
                    document.getElementById('modalProduct').innerText;

                // reset stars to default 1
                document.querySelectorAll('.star-group').forEach(group => {
                    let type = group.dataset.type;
                    ratings[type] = 1;

                    group.querySelectorAll('.star').forEach(star => {
                        star.classList.remove('active');
                        if (star.dataset.value <= 1) {
                            star.classList.add('active');
                        }
                    });
                });

                new bootstrap.Modal(document.getElementById('ratingModal')).show();
            }
        });


        // Star click logic
        document.querySelectorAll('.star-group').forEach(group => {
            group.querySelectorAll('.star').forEach(star => {

                star.addEventListener('click', function() {

                    let value = this.dataset.value;
                    let type = group.dataset.type;

                    ratings[type] = value;

                    group.querySelectorAll('.star').forEach(s => {
                        s.classList.remove('active');
                        if (s.dataset.value <= value) {
                            s.classList.add('active');
                        }
                    });

                });

            });
        });


        // Submit order with ratings
        document.getElementById('submitOrderBtn').addEventListener('click', function() {

            let orderId = document.getElementById('ratingOrderId').value;

            fetch(`{{ route('frontend.order.proceed') }}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        description_rating: ratings.description_rating,
                        logistics_rating: ratings.logistics_rating,
                        service_rating: ratings.service_rating
                    })
                })
                .then(res => res.json())
                .then(data => {

                    bootstrap.Modal.getInstance(
                        document.getElementById('ratingModal')
                    ).hide();

                    if (data.status) {

                        new bootstrap.Modal(
                            document.getElementById('successModal')
                        ).show();

                        setTimeout(() => location.reload(), 2500);

                    } else if (data.type === 'insufficient') {

                        new bootstrap.Modal(
                            document.getElementById('insufficientModal')
                        ).show();

                    } else {
                        alert("Something went wrong.");
                    }

                })
                .catch(() => alert("Something went wrong."));
        });
    </script>
@endsection
