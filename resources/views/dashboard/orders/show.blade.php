@extends('layouts.master')

@section('title', __('Order Details'))

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.orders.index') }}">{{ __('Orders') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">

            <!-- LEFT SIDE -->
            <div class="col-lg-8">

                <!-- Order Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-receipt me-2"></i>
                            Order Information
                        </h5>
                    </div>
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <strong>Order No:</strong><br>
                                <span class="text-muted">{{ $order->order_no ?? 'N/A' }}</span>
                            </div>

                            <div class="col-md-6 mb-2">
                                <strong>Status:</strong><br>
                                @if ($order->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </div>

                            <div class="col-md-6 mb-2">
                                <strong>Quantity:</strong><br>
                                {{ $order->quantity }}
                            </div>

                            <div class="col-md-6 mb-2">
                                <strong>Order Date:</strong><br>
                                {{ $order->created_at->format('d M Y, h:i A') }}
                            </div>
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-3">Payment Breakdown</h6>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                Subtotal: <span class="float-end">${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                Shipping: <span class="float-end">${{ number_format($order->shipping_cost, 2) }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                Discount: <span class="float-end text-danger">-
                                    ${{ number_format($order->discount, 2) }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                Commission: <span
                                    class="float-end text-primary">${{ number_format($order->commission, 2) }}</span>
                            </div>
                        </div>

                        <hr>

                        <h5 class="fw-bold">
                            Total:
                            <span class="float-end text-success">
                                ${{ number_format($order->total, 2) }}
                            </span>
                        </h5>

                    </div>
                </div>

                <!-- Ratings -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-star me-2"></i>
                            Ratings
                        </h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <strong>Description:</strong>
                            <span class="text-warning ms-2">
                                ⭐ {{ $order->description_rating }}/5
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>Logistics:</strong>
                            <span class="text-warning ms-2">
                                ⭐ {{ $order->logistics_rating }}/5
                            </span>
                        </div>

                        <div>
                            <strong>Service:</strong>
                            <span class="text-warning ms-2">
                                ⭐ {{ $order->service_rating }}/5
                            </span>
                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-4">

                <!-- Customer -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="ti ti-user me-2"></i>
                            Customer Info
                        </h6>
                    </div>
                    <div class="card-body text-center">

                        @if ($order->user->image)
                            <img src="{{ asset($order->user->image) }}" class="rounded-circle mb-3" width="80"
                                height="80">
                        @else
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width:80px;height:80px;">
                                <i class="ti ti-user"></i>
                            </div>
                        @endif

                        <h6 class="mb-1">{{ $order->user->name }}</h6>
                        <small class="text-muted d-block">{{ $order->user->email }}</small>
                        <small class="text-muted d-block">{{ $order->user->phone }}</small>

                    </div>
                </div>

                <!-- Product -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="ti ti-shopping-bag me-2"></i>
                            Product Info
                        </h6>
                    </div>
                    <div class="card-body text-center">

                        @if ($order->product->main_image)
                            <img src="{{ asset($order->product->main_image) }}" class="img-fluid rounded mb-3"
                                style="max-height:150px;object-fit:contain;">
                        @endif

                        <h6 class="mb-1">{{ $order->product->name }}</h6>
                        <small class="text-muted d-block">
                            SKU: {{ $order->product->sku }}
                        </small>

                        <small class="text-success fw-bold d-block mt-2">
                            ${{ $order->product->price }}
                        </small>

                    </div>
                </div>

            </div>

        </div>

        <!-- Notes -->
        @if ($order->notes)
            <div class="card mt-4">
                <div class="card-header">
                    <i class="ti ti-note me-2"></i>
                    Order Notes
                </div>
                <div class="card-body">
                    {{ $order->notes }}
                </div>
            </div>
        @endif

    </div>
@endsection
