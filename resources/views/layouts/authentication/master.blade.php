<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title>@yield('title') - {{ \App\Helpers\Helper::getCompanyName() }}</title>

    <!-- manifest meta -->
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{asset(\App\Helpers\Helper::getFavicon())}}" sizes="180x180">
    <link rel="icon" href="{{asset(\App\Helpers\Helper::getFavicon())}}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{asset(\App\Helpers\Helper::getFavicon())}}" sizes="16x16" type="image/png">

    <!-- Google fonts-->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- bootstrap icons -->
    <link rel="stylesheet" href="{{ asset('npm/bootstrap-icons%401.5.0/font/bootstrap-icons.css') }}">

    <!-- style css for this template -->
    <link href="{{ asset('frontAssets/scss/style.css') }}" rel="stylesheet" id="style">
</head>

<body class="body-scroll d-flex flex-column h-100 theme-pink" data-page="signin">

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

    @yield('content')


    <!-- Required jquery and libraries -->
    <script src="{{ asset('frontAssets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('frontAssets/js/popper.min.js') }}"></script>
    <script src="{{ asset('frontAssets/vendor/bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Customized jquery file  -->
    <script src="{{ asset('frontAssets/js/main.js') }}"></script>
    <script src="{{ asset('frontAssets/js/color-scheme.js') }}"></script>

    <!-- PWA app service registration and works -->
    <script src="{{ asset('frontAssets/js/pwa-services.js') }}"></script>

    <!-- page level custom script -->
    <script src="{{ asset('frontAssets/js/app.js') }}"></script>

</body>

</html>
