@extends('frontend.layouts.master')

@section('title', $product->name)

@section('css')
    <style>
        .product-wrapper {
            margin-top: 60px;
            margin-bottom: 40px;
            color: #fff;
        }

        .product-image-card {
            background: #1F2E3A;
            border-radius: 25px;
            padding: 20px;
            text-align: center;
        }

        .product-image-card img {
            max-height: 220px;
            object-fit: contain;
        }

        .product-info-card {
            background: #101820;
            border-radius: 25px;
            padding: 20px;
            margin-top: 20px;
        }

        .product-title {
            font-size: 20px;
            font-weight: 600;
        }

        .product-category {
            font-size: 13px;
            color: #ccc;
        }

        .product-price {
            font-size: 26px;
            font-weight: bold;
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .rating-stars {
            color: #ffc107;
            font-size: 14px;
        }

        .buy-btn {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            font-weight: bold;
            border-radius: 18px;
            padding: 14px;
            width: 100%;
            border: none;
            margin-top: 15px;
        }

        .description-card {
            background: #1F2E3A;
            border-radius: 25px;
            padding: 20px;
            margin-top: 20px;
            font-size: 14px;
            line-height: 1.6;
            color: #ccc;
        }

        .badge-popular {
            background: #ffc107;
            color: #17232D;
            font-weight: bold;
            border-radius: 15px;
            padding: 4px 10px;
            font-size: 11px;
        }

        /* Seller Modal */
        .seller-modal-content {
            background: #1F2E3A;
            border-radius: 20px;
            border: 1px solid #2a3b47;
        }

        .seller-icon {
            font-size: 30px;
        }

        .seller-btn {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            font-weight: bold;
            border-radius: 15px;
        }
    </style>
@endsection

@section('content')

    <div class="container product-wrapper">
        <div class="row">
            <div class="col-12 px-3">

                <!-- PRODUCT IMAGE -->
                <div class="product-image-card">
                    <img src="{{ $product->main_image ? asset($product->main_image) : asset('assets/img/default/product.png') }}"
                        class="img-fluid" alt="{{ $product->name }}">
                </div>

                <!-- PRODUCT INFO -->
                <div class="product-info-card">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="product-category">
                            {{ $product->category }}
                        </span>

                        @if ($product->is_popular == '1')
                            <span class="badge-popular">
                                Popular
                            </span>
                        @endif
                    </div>

                    <div class="product-title mb-2">
                        {{ $product->name }}
                    </div>

                    <!-- Rating -->
                    <div class="rating-stars mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $product->rating)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                        <span class="text-muted ms-2">
                            ({{ $product->reviews_count }} Reviews)
                        </span>
                    </div>

                    <!-- Price -->
                    <div class="product-price mb-3">
                        {{ \App\Helpers\Helper::formatCurrency($product->price) }}
                    </div>

                    <!-- BUY BUTTON -->
                    <button type="button" class="buy-btn" id="sellerBuyBtn">
                        Buy Now
                    </button>
                </div>

                <!-- DESCRIPTION -->
                <div class="description-card">
                    <h6 class="text-white mb-3">Product Description</h6>
                    {!! nl2br(e($product->description)) !!}
                </div>

            </div>
        </div>
    </div>

    <!-- Seller Restriction Modal -->
    <div class="modal fade" id="sellerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content seller-modal-content">

                <div class="modal-body text-center p-4">
                    <div class="seller-icon mb-3">
                        ⚠️
                    </div>

                    <h6 class="mb-2 text-white">
                        Purchase Not Allowed
                    </h6>

                    <p class="small text-danger mb-3">
                        You cannot buy this product because you are logged in with a seller account.
                    </p>

                    <button class="btn seller-btn w-100" data-bs-dismiss="modal">
                        OK
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    document.getElementById('sellerBuyBtn').addEventListener('click', function() {
        let sellerModal = new bootstrap.Modal(document.getElementById('sellerModal'));
        sellerModal.show();
    });
</script>
@endsection
