@extends('frontend.layouts.master')

@section('title', 'Home')

@section('css')
    <style>
        .quick-actions {
            background: linear-gradient(180deg, #1F2E3A, #17232D);
            border-radius: 20px;
        }

        .action-box {
            display: block;
            padding: 15px 5px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #C8B68A;
            color: #C8B68A;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .action-box i {
            font-size: 22px;
            display: block;
            margin-bottom: 5px;
        }

        .action-box p {
            font-size: 12px;
            margin: 0;
        }

        .action-box:hover,
        .action-box.active {
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            color: #17232D;
            box-shadow: 0 0 15px rgba(200, 182, 138, 0.6);
        }

        .title {
            color: #fff;
        }
    </style>
    <style>
        .live-box {
            background: linear-gradient(180deg, #1F2E3A, #17232D);
            color: #fff;
        }

        .live-list {
            max-height: 160px;
            overflow: hidden;
            position: relative;
        }

        .withdraw-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 14px;
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.5s forwards;
        }

        .withdraw-left {
            color: #cfcfcf;
        }

        .withdraw-amount {
            color: #D8C79A;
            font-weight: 600;
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .banner-img {
            width: 100%;
            aspect-ratio: 16 / 7;
            /* ya 16/6 try kar sakte ho */
            object-fit: cover;
        }

        .popular-card {
            background: #1F2E3A;
            border-radius: 22px;
            padding: 12px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            transition: 0.3s ease;
        }

        .popular-card:hover {
            transform: translateY(-3px);
        }

        .popular-img-wrapper {
            width: 75px;
            height: 75px;
            background: #101820;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .popular-img-wrapper img {
            max-height: 60px;
            object-fit: contain;
        }

        .popular-badge {
            position: absolute;
            top: 4px;
            left: 4px;
            background: #ffc107;
            color: #17232D;
            font-size: 8px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 6px;
        }

        .popular-details {
            flex: 1;
            padding-left: 12px;
        }

        .popular-category {
            font-size: 10px;
            color: #aaa;
        }

        .popular-title {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .popular-sku {
            font-size: 10px;
            color: #888;
        }

        .popular-price {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            background: linear-gradient(180deg, #D8C79A, #B8A06F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
@endsection

@section('content')

    <!-- Top Banner Slider -->
    <div class="row mb-3">
        <div class="col-12 px-0">
            <div class="swiper-container bannerSwiper">
                <div class="swiper-wrapper">

                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <div class="card border-0 rounded-20 overflow-hidden mx-3">
                            <img src="{{ asset('frontAssets/img/banners/banner1.webp') }}" class="w-100 banner-img">
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <div class="card border-0 rounded-20 overflow-hidden mx-3">
                            <img src="{{ asset('frontAssets/img/banners/banner2.webp') }}" class="w-100 banner-img">
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <div class="card border-0 rounded-20 overflow-hidden mx-3">
                            <img src="{{ asset('frontAssets/img/banners/banner3.webp') }}" class="w-100 banner-img">
                        </div>
                    </div>

                </div>

                <!-- Pagination Dots -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>

    <!-- Quick Action Section -->
    <div class="row mb-4">
        <div class="col-12 px-3">
            <div class="quick-actions p-3 rounded-20">
                <div class="row text-center g-3">

                    <div class="col-3">
                        <a href="{{ route('frontend.home') }}" class="action-box">
                            <i class="bi bi-house-door-fill"></i>
                            <p>Home</p>
                        </a>
                    </div>

                    <div class="col-3">
                        <a href="{{ route('frontend.recharge') }}" class="action-box">
                            <i class="bi bi-wallet2"></i>
                            <p>Recharge</p>
                        </a>
                    </div>

                    <div class="col-3">
                        <a href="{{ route('frontend.start') }}" class="action-box">
                            <i class="bi bi-play-circle-fill"></i>
                            <p>Start</p>
                        </a>
                    </div>

                    <div class="col-3">
                        <a href="{{ route('frontend.orders') }}" class="action-box">
                            <i class="bi bi-receipt"></i>
                            <p>Order</p>
                        </a>
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

    @if (isset($randomProducts) && count($randomProducts) > 0)
        <!--products -->
        <div class="row mb-3">
            <div class="col-12 px-0">
                <!-- swiper categories -->
                <div class="swiper-container connectionwiper">
                    <div class="swiper-wrapper">
                        @foreach ($randomProducts as $randomProduct)
                            <div class="swiper-slide text-center">
                                <a href="{{ route('frontend.products.details', $randomProduct->sku) }}"
                                    class="card text-center bg-theme text-white">
                                    <div class="card-body p-1">
                                        <figure class="avatar avatar-90 rounded-15 mb-1">
                                            <img src="{{ asset($randomProduct->main_image) }}"
                                                alt="{{ $randomProduct->name }}">
                                        </figure>
                                        <p class="text-center size-12">
                                            <small
                                                class="text-muted">{{ $randomProduct->name }}</small><br>{{ \App\Helpers\Helper::formatCurrency($randomProduct->price) }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif


    <!-- Live Withdrawals Section -->
    <div class="row mb-4">
        <div class="col-12 px-3">
            <div class="live-box p-3 rounded-20">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-white">LIVE WITHDRAWALS</h6>
                    <span class="badge bg-light text-dark small">REAL-TIME</span>
                </div>

                <div class="live-list" id="liveWithdrawals">
                    <!-- JS entries here -->
                </div>
            </div>
        </div>
    </div>

    @if (isset($popularProducts) && count($popularProducts) > 0)

        <div class="row mb-3">
            <div class="col">
                <h6 class="title text-white">🔥 Popular Products</h6>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">

                @foreach ($popularProducts as $popularProduct)
                    <a href="{{ route('frontend.products.details', $popularProduct->sku) }}" class="text-decoration-none">

                        <div class="popular-card">

                            <!-- Left Image -->
                            <div class="popular-img-wrapper">

                                <span class="popular-badge">Popular</span>

                                <img src="{{ $popularProduct->main_image ? asset($popularProduct->main_image) : asset('assets/img/default/product.png') }}"
                                    alt="{{ $popularProduct->name }}">
                            </div>

                            <!-- Middle Details -->
                            <div class="popular-details">
                                <div class="popular-category">
                                    {{ $popularProduct->category }}
                                </div>

                                <div class="popular-title">
                                    {{ $popularProduct->name }}
                                </div>

                                <div class="popular-sku">
                                    SKU: {{ $popularProduct->sku }}
                                </div>
                            </div>

                            <!-- Right Price -->
                            <div class="popular-price">
                                {{ \App\Helpers\Helper::formatCurrency($popularProduct->price) }}
                            </div>

                        </div>

                    </a>
                @endforeach

            </div>
        </div>

    @endif

@endsection

@section('script')
    <script>
        var productSwiper = new Swiper(".connectionwiper", {
            slidesPerView: 3,
            spaceBetween: 10,
            freeMode: true,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            breakpoints: {
                576: {
                    slidesPerView: 3.5
                },
                768: {
                    slidesPerView: 4.5
                }
            }
        });
    </script>
    <script>
        var bannerSwiper = new Swiper(".bannerSwiper", {
            slidesPerView: 1.1,
            spaceBetween: 10,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    </script>
    <script>
        const list = document.getElementById("liveWithdrawals");

        // Generate random masked number
        function randomNumber() {
            const countryCodes = ["+1", "+44", "+91", "+92", "+61"];
            let code = countryCodes[Math.floor(Math.random() * countryCodes.length)];
            let lastTwo = Math.floor(Math.random() * 90 + 10);
            return `${code}****${lastTwo}`;
        }

        // Generate random amount
        function randomAmount() {
            return (Math.random() * 400 + 50).toFixed(2);
        }

        // Generate random time text
        function randomTime() {
            let sec = Math.floor(Math.random() * 59 + 1);
            return sec + " sec ago";
        }

        // Create new withdrawal entry
        function createEntry() {
            const div = document.createElement("div");
            div.className = "withdraw-item";

            div.innerHTML = `
                <div class="withdraw-left">
                    ${randomNumber()}
                    <span class="ms-2 text-muted">${randomTime()}</span>
                </div>
                <div class="withdraw-amount">
                    $${randomAmount()}
                </div>
            `;

            // Add to top
            list.prepend(div);

            // Keep max 6 items visible
            if (list.children.length > 6) {
                list.removeChild(list.lastChild);
            }
        }

        // Start with minimum 5 entries
        for (let i = 0; i < 5; i++) {
            createEntry();
        }

        // Random interval between 1 to 8 seconds
        function randomInterval() {
            const time = Math.floor(Math.random() * 7000) + 1000; // 1s - 8s
            setTimeout(() => {
                createEntry();
                randomInterval();
            }, time);
        }

        randomInterval();
    </script>
@endsection
