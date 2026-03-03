@extends('layouts.master')

@section('title', __('Product Details'))

@section('breadcrumb-items')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.products.index') }}">{{ __('Products') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card shadow-sm">
            <div class="row g-0">

                <!-- Product Image -->
                <div class="col-md-4 text-center p-4 border-end">
                    @if ($product->main_image)
                        <img src="{{ asset($product->main_image) }}" class="img-fluid rounded"
                            style="max-height:300px; object-fit:contain;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded"
                            style="height:300px;">
                            <span class="text-muted">No Image</span>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="col-md-8 p-4">

                    <h3 class="fw-bold mb-3">{{ $product->name }}</h3>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <strong>Category:</strong>
                            <span class="badge bg-label-primary">
                                {{ $product->category }}
                            </span>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>SKU:</strong>
                            <span class="text-muted">{{ $product->sku }}</span>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Price:</strong>
                            <span class="fw-bold text-success">
                                ${{ $product->price }}
                            </span>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Status:</strong>
                            @if ($product->is_active == 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Popular:</strong>
                            @if ($product->is_popular == '1')
                                <span class="badge bg-warning text-dark">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Rating:</strong>
                            <span class="text-warning">
                                ⭐ {{ $product->rating }}
                            </span>
                            <small class="text-muted">
                                ({{ $product->reviews_count }} Reviews)
                            </small>
                        </div>

                        <div class="col-md-12 mb-2">
                            <strong>Slug:</strong>
                            <span class="text-muted">{{ $product->slug }}</span>
                        </div>
                    </div>

                    <hr>

                    <div>
                        <h6 class="fw-bold">Description</h6>
                        <p class="text-muted">
                            {{ $product->description }}
                        </p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('dashboard.products.index') }}" class="btn btn-secondary">
                            Back
                        </a>

                        <a href="{{ route('dashboard.products.edit', $product->id) }}" class="btn btn-primary">
                            Edit Product
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
