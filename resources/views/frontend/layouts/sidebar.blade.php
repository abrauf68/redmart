<!-- Sidebar main menu -->
<div class="sidebar-wrap  sidebar-fullmenu">
    <!-- Add pushcontent or fullmenu instead overlay -->
    <div class="closemenu text-secondary">Close Menu</div>
    <div class="sidebar ">
        <!-- user information -->
        <div class="row">
            <div class="col-12 profile-sidebar">
                <div class="row">
                    <div class="col-auto">
                        <figure class="avatar avatar-100 rounded-20 shadow-sm">
                            <img src="{{ asset(auth()->user()->image ?? 'assets/img/default/user.png') }}" alt="">
                        </figure>
                    </div>
                    <div class="col px-0 align-self-center">
                        <h5 class="mb-2">{{ auth()->user()->name }}</h5>
                        <p class="text-muted size-12">{{ auth()->user()->username }}<br>{{ auth()->user()->phone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- user emnu navigation -->
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}" href="{{ route('frontend.home') }}" aria-current="page">
                            <div class="avatar avatar-40 icon"><i class="bi bi-house-door"></i></div>
                            <div class="col">Home</div>
                            <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="" tabindex="-1">
                            <div class="avatar avatar-40 icon"><i class="bi bi-wallet2"></i></div>
                            <div class="col">Recharge</div>
                            <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="" tabindex="-1">
                            <div class="avatar avatar-40 icon"><i class="bi bi-bag-check"></i></div>
                            <div class="col">Orders</div>
                            <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="" tabindex="-1">
                            <div class="avatar avatar-40 icon"><i class="bi bi-person-circle"></i></div>
                            <div class="col">Profile</div>
                            <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="" tabindex="-1">
                            <div class="avatar avatar-40 icon"><i class="bi bi-cash-coin"></i></div>
                            <div class="col">Withdraw</div>
                            <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="" tabindex="-1">
                            <div class="avatar avatar-40 icon"><i class="bi bi-headset"></i></div>
                            <div class="col">Support</div>
                            <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javscript:void();" tabindex="-1" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <div class="avatar avatar-40 icon"><i class="bi bi-box-arrow-right"></i></div>
                            <div class="col">Logout</div>
                            <div class="arrow"><i class="bi bi-chevron-right"></i></div>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Sidebar main menu ends -->
