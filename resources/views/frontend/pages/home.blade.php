@extends('frontend.layouts.master')

@section('title', 'Home')

@section('css')
@endsection

@section('content')
    <!-- search -->
    <div class="row mb-3">
        <div class="col-12 overflow-hidden">
            <!-- input -->
            <div class="row">
                <div class="col position-relative align-self-center">
                    <div class="form-group form-floating mb-3 is-valid">
                        <input type="text" class="form-control" value="" id="searchmain" placeholder="Search">
                        <label class="form-control-label" for="searchmain">Search</label>
                    </div>
                </div>
                <div class="col-auto align-self-center">
                    <button class="btn btn-light btn-44 filter-btn">
                        <i class="bi bi-filter size-22"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!--categories -->
    <div class="row mb-3">
        <div class="col-12 px-0">
            <!-- swiper categories -->
            <div class="swiper-container connectionwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide text-center">
                        <a href="products.html" class="card text-center mb-2">
                            <div class="card-body p-1">
                                <figure class="avatar avatar-80 rounded-18">
                                    <img src="{{ asset('frontAssets/img/categories1.png') }}" alt="">
                                </figure>
                            </div>
                        </a>
                        <p class="size-10 text-secondary">Electronics</p>
                    </div>

                    <div class="swiper-slide text-center">
                        <a href="products.html" class="card text-center mb-2">
                            <div class="card-body p-1">
                                <figure class="avatar avatar-80 rounded-20">
                                    <img src="{{ asset('frontAssets/img/categories2.png') }}" alt="">
                                </figure>
                            </div>
                        </a>
                        <p class="size-10 text-secondary">Clothing</p>
                    </div>

                    <div class="swiper-slide text-center">
                        <a href="products.html" class="card text-center mb-2">
                            <div class="card-body p-1">
                                <figure class="avatar avatar-80 rounded-20">
                                    <img src="{{ asset('frontAssets/img/categories3.png') }}" alt="">
                                </figure>
                            </div>
                        </a>
                        <p class="size-10 text-secondary">Appliences</p>
                    </div>

                    <div class="swiper-slide text-center">
                        <a href="products.html" class="card text-center mb-2">
                            <div class="card-body p-1">
                                <figure class="avatar avatar-80 rounded-20">
                                    <img src="{{ asset('frontAssets/img/categories4.png') }}" alt="">
                                </figure>
                            </div>
                        </a>
                        <p class="size-10 text-secondary">Mobiles</p>
                    </div>
                    <div class="swiper-slide text-center">
                        <a href="products.html" class="card text-center mb-2">
                            <div class="card-body p-1">
                                <figure class="avatar avatar-80 rounded-20">
                                    <img src="{{ asset('frontAssets/img/categories5.png') }}" alt="">
                                </figure>
                            </div>
                        </a>
                        <p class="size-10 text-secondary">iPhone</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--high light -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card theme-bg">
                <img src="{{ asset('frontAssets/img/apple-watch.png') }}" alt="" class="iwatchposition">
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-auto align-self-center">
                            <h4><span class="fw-light">15% OFF</span><br>iWatch</h4>
                        </div>
                        <div class="col-auto align-self-center ms-auto text-end">
                            <img src="{{ asset('frontAssets/img/visa.png') }}" alt="">
                            <p class="size-10"><span class="text-muted">Buy at 1450.00</span><br>Using Credit
                                Card</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--products -->
    <div class="row mb-3">
        <div class="col-12 px-0">
            <!-- swiper categories -->
            <div class="swiper-container connectionwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide text-center">
                        <a href="product.html" class="card text-center bg-theme text-white">
                            <div class="card-body p-1">
                                <figure class="avatar avatar-90 rounded-15 mb-1">
                                    <img src="{{ asset('frontAssets/img/categories3.jpg') }}" alt="">
                                </figure>
                                <p class="text-center size-12"><small class="text-muted">LAVAA
                                        3500</small><br>$ 459.00</p>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide text-center">
                        <a href="product.html" class="card text-center bg-theme text-white">
                            <div class="card-body p-1">
                                <figure class="avatar avatar-90 rounded-15 mb-1">
                                    <img src="{{ asset('frontAssets/img/categories4.png') }}" alt="">
                                </figure>
                                <p class="text-center size-12"><small class="text-muted">Galaxy
                                        S20</small><br>$ 459.00</p>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide text-center">
                        <a href="product.html" class="card text-center bg-theme text-white">
                            <div class="card-body p-1">
                                <figure class="avatar avatar-90 rounded-15 mb-1">
                                    <img src="{{ asset('frontAssets/img/categories5.png') }}" alt="">
                                </figure>
                                <p class="text-center size-12"><small class="text-muted">iPhone
                                        12SR</small><br>$ 1012.00</p>
                            </div>
                        </a>
                    </div>

                    <div class="swiper-slide text-center">
                        <a href="product.html" class="card text-center bg-theme text-white">
                            <div class="card-body p-1">
                                <figure class="avatar avatar-90 rounded-15 mb-1">
                                    <img src="{{ asset('frontAssets/img/categories3.jpg') }}" alt="">
                                </figure>
                                <p class="text-center size-12"><small class="text-muted">LAVAA
                                        3500</small><br>$ 459.00</p>
                            </div>
                        </a>
                    </div>
                    <div class="swiper-slide text-center">
                        <a href="product.html" class="card text-center bg-theme text-white">
                            <div class="card-body p-1">
                                <figure class="avatar avatar-90 rounded-15 mb-1">
                                    <img src="{{ asset('frontAssets/img/categories4.png') }}" alt="">
                                </figure>
                                <p class="text-center size-12"><small class="text-muted">Galaxy
                                        S20</small><br>$ 459.00</p>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- product medsize box  -->
    <div class="row mb-2">
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card mb-3">
                <div class="card-body p-1 position-relative">
                    <div class="position-absolute start-0 top-0 m-2 z-index-1">
                        <div class="tag tag-small bg-theme text-white">
                            26% OFF
                        </div>
                    </div>
                    <div class="position-absolute end-0 top-0 p-2 z-index-1 ">
                        <button class="btn btn-sm btn-26 roudned-circle shadow-sm shadow-danger text-white bg-danger">
                            <i class="bi bi-heart size-10 vm"></i>
                        </button>
                    </div>
                    <figure class="avatar w-100 rounded-15 border">
                        <img src="{{ asset('frontAssets/img/categories4.png') }}" alt="" class="w-100">
                    </figure>
                </div>
                <div class="card-body pt-2">
                    <div class="row">
                        <div class="col">
                            <p class="small"><small class="text-muted size-12">LAVA B500</small><br>$ 265.00
                            </p>
                        </div>
                        <div class="col-auto px-0">
                            <button class="btn btn-sm btn-link text-color-theme">
                                <i class="bi bi-bag-plus"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card mb-3">
                <div class="card-body p-1 position-relative">
                    <div class="position-absolute start-0 top-0 m-2 z-index-1">
                        <div class="tag tag-small bg-theme text-white">
                            30% OFF
                        </div>
                    </div>
                    <div class="position-absolute end-0 top-0 p-2 z-index-1 ">
                        <button class="btn btn-sm btn-26 roudned-circle shadow-sm shadow-danger text-danger">
                            <i class="bi bi-heart size-10 vm"></i>
                        </button>
                    </div>
                    <figure class="avatar w-100 rounded-15 border">
                        <img src="{{ asset('frontAssets/img/categories1.png') }}" alt="" class="w-100">
                    </figure>
                </div>
                <div class="card-body pt-2">
                    <div class="row">
                        <div class="col">
                            <p class="small"><small class="text-muted size-12">Smasung S500</small><br>$
                                459.00</p>
                        </div>
                        <div class="col-auto px-0">
                            <button class="btn btn-sm btn-link text-color-theme">
                                <i class="bi bi-bag-plus"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card mb-3">
                <div class="card-body p-1 position-relative">
                    <div class="position-absolute start-0 top-0 m-2 z-index-1">
                        <div class="tag tag-small bg-danger text-white">
                            SALE
                        </div>
                    </div>
                    <div class="position-absolute end-0 top-0 p-2 z-index-1 ">
                        <button class="btn btn-sm btn-26 roudned-circle shadow-sm shadow-danger text-white bg-danger">
                            <i class="bi bi-heart size-10 vm"></i>
                        </button>
                    </div>
                    <figure class="avatar w-100 rounded-15 border">
                        <img src="{{ asset('frontAssets/img/categories2.png') }}" alt="" class="w-100">
                    </figure>
                </div>
                <div class="card-body pt-2">
                    <div class="row">
                        <div class="col">
                            <p class="small"><small class="text-muted size-12">Clothing</small><br>$ 265.00
                            </p>
                        </div>
                        <div class="col-auto px-0">
                            <button class="btn btn-sm btn-link text-color-theme">
                                <i class="bi bi-bag-plus"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card mb-3">
                <div class="card-body p-1 position-relative">
                    <div class="position-absolute start-0 top-0 m-2 z-index-1">
                        <div class="tag tag-small bg-warning text-white">
                            1 Offer
                        </div>
                    </div>
                    <div class="position-absolute end-0 top-0 p-2 z-index-1 ">
                        <button class="btn btn-sm btn-26 roudned-circle shadow-sm shadow-danger text-danger">
                            <i class="bi bi-heart size-10 vm"></i>
                        </button>
                    </div>
                    <figure class="avatar w-100 rounded-15 border">
                        <img src="{{ asset('frontAssets/img/categories3.png') }}" alt="" class="w-100">
                    </figure>
                </div>
                <div class="card-body pt-2">
                    <div class="row">
                        <div class="col">
                            <p class="small"><small class="text-muted size-12">Wash.Machine</small><br>$
                                254.00</p>
                        </div>
                        <div class="col-auto px-0">
                            <button class="btn btn-sm btn-link text-color-theme">
                                <i class="bi bi-bag-plus"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- offer banner-->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card theme-bg overflow-hidden">
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-10 align-self-center">
                            <h1 class="mb-3"><span class="fw-light">15% OFF</span><br>GiftCard</h1>
                            <p>Thank you for spending with us, You have received Gift Card.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- product medsize box no gap -->
    <div class="row mb-4 g-0 ">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row mx-0">
                        <div class="col-6 col-md-4 col-lg-3 border-end p-3">
                            <div class="position-relative">
                                <div class="position-absolute start-0 top-0 z-index-1">
                                    <div class="tag tag-small bg-theme text-white">
                                        26% OFF
                                    </div>
                                </div>
                                <div class="position-absolute end-0 top-0 z-index-1 ">
                                    <button class="btn btn-sm btn-26 roudned-circle shadow-sm shadow-danger text-danger">
                                        <i class="bi bi-heart size-10 vm"></i>
                                    </button>
                                </div>
                                <figure class="avatar w-100 rounded-15">
                                    <img src="{{ asset('frontAssets/img/categories4.png') }}" alt="" class="w-100">
                                </figure>

                                <div class="row">
                                    <div class="col">
                                        <p class="small"><small class="text-muted size-12">LAVA
                                                B500</small><br>$
                                            265.00</p>
                                    </div>
                                    <div class="col-auto px-0">
                                        <button class="btn btn-sm btn-link text-color-theme">
                                            <i class="bi bi-bag-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 p-3">
                            <div class="position-relative">
                                <div class="position-absolute start-0 top-0  z-index-1">
                                    <div class="tag tag-small bg-theme text-white">
                                        30% OFF
                                    </div>
                                </div>
                                <div class="position-absolute end-0 top-0  z-index-1 ">
                                    <button class="btn btn-sm btn-26 roudned-circle shadow-sm shadow-danger text-danger">
                                        <i class="bi bi-heart size-10 vm"></i>
                                    </button>
                                </div>
                                <figure class="avatar w-100 rounded-15">
                                    <img src="{{ asset('frontAssets/img/categories1.png') }}" alt="" class="w-100">
                                </figure>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="small"><small class="text-muted size-12">Smasung
                                            S500</small><br>$ 459.00</p>
                                </div>
                                <div class="col-auto px-0">
                                    <button class="btn btn-sm btn-link text-color-theme">
                                        <i class="bi bi-bag-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-0 border-top">
                        <div class="col-6 col-md-4 col-lg-3 border-end p-3">
                            <div class="position-relative">
                                <div class="position-absolute start-0 top-0 z-index-1">
                                    <div class="tag tag-small bg-danger text-white">
                                        SALE
                                    </div>
                                </div>
                                <div class="position-absolute end-0 top-0 z-index-1 ">
                                    <button
                                        class="btn btn-sm btn-26 roudned-circle shadow-sm shadow-danger text-white bg-danger">
                                        <i class="bi bi-heart size-10 vm"></i>
                                    </button>
                                </div>
                                <figure class="avatar w-100 rounded-15">
                                    <img src="{{ asset('frontAssets/img/categories2.png') }}" alt="" class="w-100">
                                </figure>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="small"><small class="text-muted size-12">Clothing</small><br>$
                                        65.00</p>
                                </div>
                                <div class="col-auto px-0">
                                    <button class="btn btn-sm btn-link text-color-theme">
                                        <i class="bi bi-bag-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-3 p-3">
                            <div class="position-relative">
                                <div class="position-absolute start-0 top-0 z-index-1">
                                    <div class="tag tag-small bg-warning text-white">
                                        1 Offer
                                    </div>
                                </div>
                                <div class="position-absolute end-0 top-0 z-index-1 ">
                                    <button class="btn btn-sm btn-26 roudned-circle shadow-sm shadow-danger text-danger">
                                        <i class="bi bi-heart size-10 vm"></i>
                                    </button>
                                </div>
                                <figure class="avatar w-100 rounded-15">
                                    <img src="{{ asset('frontAssets/img/categories3.png') }}" alt="" class="w-100">
                                </figure>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p class="small"><small class="text-muted size-12">Wash.Machine</small><br>$
                                        254.00</p>
                                </div>
                                <div class="col-auto px-0">
                                    <button class="btn btn-sm btn-link text-color-theme">
                                        <i class="bi bi-bag-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- popular shopping  -->
    <div class="row mb-3 gap-0">
        <div class="col">
            <h6 class="title">Popular</h6>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush bg-none">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="avatar avatar-50 border rounded-15">
                                        <img src="{{ asset('frontAssets/img/categories2.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col align-self-center ps-0">
                                    <p class="text-secondary size-10 mb-0">Clothing</p>
                                    <p>ZIVACA SK10</p>
                                </div>
                                <div class="col align-self-center text-end">
                                    <p class="text-secondary text-muted size-10 mb-0">On Sale</p>
                                    <p>80.00</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="avatar avatar-50 border rounded-15">
                                        <img src="{{ asset('frontAssets/img/categories4.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col align-self-center ps-0">
                                    <p class="text-secondary size-10 mb-0">Mobiles</p>
                                    <p>LAVAA 3005</p>
                                </div>
                                <div class="col align-self-center text-end">
                                    <p class="text-secondary text-muted size-10 mb-0">Best Exchange</p>
                                    <p>250.00</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="avatar avatar-50 border rounded-15">
                                        <img src="{{ asset('frontAssets/img/categories1.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col align-self-center ps-0">
                                    <p class="text-muted size-10 mb-0">Electronics</p>
                                    <p>Samsung CT30</p>
                                </div>
                                <div class="col align-self-center text-end">
                                    <p class="text-secondary text-muted size-10 mb-0">20% OFF</p>
                                    <p>355.00</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
