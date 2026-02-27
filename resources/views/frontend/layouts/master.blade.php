<!doctype html>
<html lang="en">

<head>
    <title>@yield('title') - {{ \App\Helpers\Helper::getCompanyName() }}</title>
    @include('frontend.layouts.meta')
    @include('frontend.layouts.css')
    @yield('css')
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

    <!-- filter menu -->
    <div class="filter">
        <div class="card shadow h-100">
            <div class="card-header">
                <div class="row">
                    <div class="col align-self-center">
                        <h6 class="mb-0">Filter Criteria</h6>
                        <p class="text-secondary small">2154 products</p>
                    </div>
                    <div class="col-auto px-0">
                        <button class="btn btn-link text-danger filter-close">
                            <i class="bi bi-x size-22"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body overflow-auto">
                <div class="mb-4">
                    <h6>Select Range</h6>
                    <div id="rangeslider" class="mt-4"></div>
                </div>

                <div class="row mb-4">
                    <div class="col">
                        <div class="form-floating">
                            <input type="number" class="form-control" min="0" max="500" value="100" step="1" id="input-select">
                            <label for="input-select">Minimum</label>
                        </div>
                    </div>
                    <div class="col-auto align-self-center"> to </div>
                    <div class="col">
                        <div class="form-floating">
                            <input type="number" class="form-control" min="0" max="500" value="200" step="1" id="input-number">
                            <label for="input-number">Maximum</label>
                        </div>
                    </div>
                </div>

                <div class="form-floating mb-4">
                    <select class="form-control" id="filtertype">
                        <option selected="">Cloths</option>
                        <option>Electronics</option>
                        <option>Furniture</option>
                    </select>
                    <label for="filtertype">Select Shopping Type</label>
                </div>

                <div class="form-group floating-form-group active mb-4">
                    <h6 class="mb-3">Select Facilities</h6>

                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" class="form-check-input" id="men" checked="">
                        <label class="form-check-label" for="men">Men</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" class="form-check-input" id="woman" checked="">
                        <label class="form-check-label" for="woman">Women</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" class="form-check-input" id="Sport">
                        <label class="form-check-label" for="Sport">Sport</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" class="form-check-input" id="homedecor">
                        <label class="form-check-label" for="homedecor">Home Decor</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input type="checkbox" class="form-check-input" id="kidsplay">
                        <label class="form-check-label" for="kidsplay">Kid's Play</label>
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" placeholder="Keyword">
                    <label for="input-select">Keyword</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-control" id="discount">
                        <option>10% </option>
                        <option>30%</option>
                        <option>50%</option>
                        <option>80%</option>
                    </select>
                    <label for="discount">Offer Discount</label>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-default w-100 shadow-sm shadow-success btn-rounded">Search</button>
            </div>
        </div>
    </div>
    <!-- filter menu ends-->

    <!-- PWA app install toast message -->
    <div class="position-fixed bottom-0 start-50 translate-middle-x  z-index-99">
        <div class="toast mb-3" role="alert" aria-live="assertive" aria-atomic="true" id="toastinstall" data-bs-animation="true">
            <div class="toast-header">
                <img src="assets/img/favicon32.png" class="rounded me-2" alt="...">
                <strong class="me-auto">Install PWA App</strong>
                <small>now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <div class="row">
                    <div class="col">
                        Click "Install" to install PWA app & experience indepedent.
                    </div>
                    <div class="col-auto align-self-center ps-0">
                        <button class="btn-default btn btn-sm btn-rounded" id="addtohome">Install</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.layouts.script')
    @yield('script')
</body>

</html>
