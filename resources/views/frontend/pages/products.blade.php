@extends('frontend.layouts.master')

@section('title', 'Products')

@section('css')
<style>
    .products-wrapper {
        margin-top: 60px;
        margin-bottom: 40px;
        color: #fff;
    }

    /* Horizontal Product Card */
    .product-card {
        background: #1F2E3A;
        border-radius: 22px;
        padding: 12px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        transition: 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-3px);
    }

    .product-image-wrapper {
        width: 95px;
        height: 95px;
        background: #101820;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .product-img {
        max-height: 80px;
        object-fit: contain;
    }

    .badge-popular {
        position: absolute;
        top: 6px;
        left: 6px;
        background: #ffc107;
        color: #17232D;
        font-size: 9px;
        font-weight: bold;
        padding: 2px 6px;
        border-radius: 8px;
    }

    .product-details {
        flex: 1;
        padding-left: 12px;
    }

    .product-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .product-category {
        font-size: 11px;
        color: #aaa;
        margin-bottom: 4px;
    }

    .rating-stars {
        font-size: 11px;
        color: #ffc107;
    }

    .product-price {
        font-size: 15px;
        font-weight: bold;
        margin-top: 5px;
        background: linear-gradient(180deg, #D8C79A, #B8A06F);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Premium Pagination */
    .custom-pagination {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 25px;
    }

    .custom-pagination a,
    .custom-pagination span {
        min-width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .custom-pagination a {
        background: #101820;
        color: #D8C79A;
    }

    .custom-pagination a:hover {
        background: #2a3c4d;
    }

    .custom-pagination .active span {
        background: linear-gradient(180deg, #D8C79A, #B8A06F);
        color: #17232D;
    }

    .empty-state {
        text-align: center;
        margin-top: 60px;
        color: #aaa;
    }
</style>
@endsection

@section('content')

<div class="container products-wrapper">
    <div class="row">
        <div class="col-12 px-3">

            @forelse($products as $product)

                <a href="{{ route('frontend.products.details', $product->sku) }}"
                   class="text-decoration-none text-white">

                    <div class="product-card">

                        <!-- Left Image -->
                        <div class="product-image-wrapper">

                            @if($product->is_popular == '1')
                                <span class="badge-popular">Popular</span>
                            @endif

                            <img src="{{ $product->main_image
                                ? asset($product->main_image)
                                : asset('assets/img/default/product.png') }}"
                                class="product-img"
                                alt="{{ $product->name }}">
                        </div>

                        <!-- Right Details -->
                        <div class="product-details">

                            <div class="product-title">
                                {{ $product->name }}
                            </div>

                            <div class="product-category">
                                {{ $product->category }}
                            </div>

                            <div class="rating-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $product->rating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                                <span class="text-muted ms-1">
                                    ({{ $product->reviews_count }})
                                </span>
                            </div>

                            <div class="product-price">
                                {{ \App\Helpers\Helper::formatCurrency($product->price) }}
                            </div>

                        </div>

                    </div>

                </a>

            @empty
                <div class="empty-state">
                    No products available.
                </div>
            @endforelse


            <!-- Premium Pagination -->
            @if ($products->hasPages())
                <div class="custom-pagination">

                    {{-- Previous --}}
                    @if ($products->onFirstPage())
                        <span>«</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}">«</a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page == $products->currentPage())
                            <span class="active"><span>{{ $page }}</span></span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}">»</a>
                    @else
                        <span>»</span>
                    @endif

                </div>
            @endif

        </div>
    </div>
</div>

@endsection
