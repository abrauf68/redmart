<!-- Header -->
<header class="header position-fixed header-filled">
    <div class="row">
        <div class="col-auto">
            <button type="button" class="btn btn-light btn-44 btn-rounded menu-btn">
                <i class="bi bi-list"></i>
            </button>
        </div>
        <div class="col">
            <a href="{{ route('frontend.home') }}">
                <div class="logo-small">
                    <img src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="{{ env('APP_NAME') }}">
                    <span>Red<span class="text-secondary fw-light">Mart</span></apan>
                </div>
            </a>
        </div>
        <div class="col-auto">
            @php
                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                    ->whereNull('read_at')
                    ->count();
            @endphp

            <a href="{{ route('frontend.notifications') }}" class="btn btn-light btn-44 btn-rounded position-relative">

                <i class="bi bi-bell"></i>

                @if ($unreadCount > 0)
                    <span class="count-indicator">
                    </span>
                @endif

            </a>
            <a href="{{ route('frontend.profile') }}" target="_self" class="btn btn-light btn-44 btn-rounded ms-2">
                <i class="bi bi-person-circle"></i>
            </a>
        </div>
    </div>
</header>
<!-- Header ends -->
