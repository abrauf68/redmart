<!-- Header -->
<header class="header position-fixed header-filled">
    <div class="row">
        <div class="col-auto">
            <button type="button" class="btn btn-light btn-44 btn-rounded menu-btn">
                <i class="bi bi-list"></i>
            </button>
        </div>
        <div class="col">
            <div class="logo-small">
                <img src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="{{ env('APP_NAME') }}">
                <span>Red<span class="text-secondary fw-light">Mart</span></apan>
            </div>
        </div>
        <div class="col-auto">
            <a href="notifications.html" target="_self" class="btn btn-light btn-44 btn-rounded">
                <i class="bi bi-bell"></i>
                <span class="count-indicator"></span>
            </a>
            <a href="profile.html" target="_self" class="btn btn-light btn-44 btn-rounded ms-2">
                <i class="bi bi-person-circle"></i>
            </a>
        </div>
    </div>
</header>
<!-- Header ends -->
