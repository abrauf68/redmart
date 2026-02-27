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
                            <img src="{{ asset('frontAssets/img/banners/banner1.webp') }}" class="w-100"
                                style="height:180px; object-fit:cover;">
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <div class="card border-0 rounded-20 overflow-hidden mx-3">
                            <img src="{{ asset('frontAssets/img/banners/banner2.webp') }}" class="w-100"
                                style="height:180px; object-fit:cover;">
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <div class="card border-0 rounded-20 overflow-hidden mx-3">
                            <img src="{{ asset('frontAssets/img/banners/banner3.webp') }}" class="w-100"
                                style="height:180px; object-fit:cover;">
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
                        <a href="#" class="action-box">
                            <i class="bi bi-house-door-fill"></i>
                            <p>Homepage</p>
                        </a>
                    </div>

                    <div class="col-3">
                        <a href="#" class="action-box">
                            <i class="bi bi-wallet2"></i>
                            <p>Recharge</p>
                        </a>
                    </div>

                    <div class="col-3">
                        <a href="#" class="action-box">
                            <i class="bi bi-play-circle-fill"></i>
                            <p>Start</p>
                        </a>
                    </div>

                    <div class="col-3">
                        <a href="#" class="action-box">
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
