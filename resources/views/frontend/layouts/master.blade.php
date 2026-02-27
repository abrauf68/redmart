<!doctype html>
<html lang="en">

<head>
    <title>@yield('title') - {{ \App\Helpers\Helper::getCompanyName() }}</title>
    @include('frontend.layouts.meta')
    @include('frontend.layouts.css')
    @yield('css')

    <style>
        main {
            background: linear-gradient(180deg, #1F2E3A, #17232D);
        }
    </style>
</head>

<body class="body-scroll theme-pink" data-page="shop">

    <!-- loader section -->
    <div class="container-fluid loader-wrap">
        <div class="row h-100">
            <div class="col-10 col-md-6 col-lg-5 col-xl-3 mx-auto text-center align-self-center">
                <div class="circular-loader">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <p class="mt-4"><span class="text-secondary">Shopping Experience Unlimited</span><br><strong>Please
                        wait...</strong></p>
            </div>
        </div>
    </div>
    <!-- loader section ends -->

    @include('frontend.layouts.sidebar')

    <!-- Begin page -->
    <main class="h-100">

        @include('frontend.layouts.header')

        <!-- main page content -->
        <div class="main-container container">

            @yield('content')

        </div>
        <!-- main page content ends -->


    </main>
    <!-- Page ends-->

    @include('frontend.layouts.footer')

    <!-- PWA app install toast message -->
    {{-- <div class="position-fixed bottom-0 start-50 translate-middle-x  z-index-99">
        <div class="toast mb-3" role="alert" aria-live="assertive" aria-atomic="true" id="toastinstall" data-bs-animation="true">
            <div class="toast-header">
                <img style="height: 20px;" src="{{ asset(\App\Helpers\Helper::getFavicon()) }}" class="rounded me-2" alt="...">
                <strong class="me-auto">Start Grabbing Your Orders</strong>
                <small>now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <div class="row">
                    <div class="col">
                        Click "Start" to grab orders and earn commision.
                    </div>
                    <div class="col-auto align-self-center ps-0">
                        <button class="btn-default btn btn-sm btn-rounded" id="addtohome">Start</button>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    @include('frontend.layouts.script')
    @yield('script')
</body>

</html>
