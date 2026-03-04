@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    @if (
        $data['pendingCustomers'] > 0 ||
            $data['pendingOrders']  > 0 ||
            $data['pendingWithdraws'] > 0 ||
            $data['pendingRecharge'] > 0)
        <div class="row mb-4">

            @if ($data['pendingCustomers'] > 0)
                <div class="col-12">
                    <a href="{{ route('dashboard.customers.index') }}" class="text-decoration-none">
                        <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm">
                            <div>
                                <i class="ti ti-user-exclamation me-2 fs-4"></i>
                                <strong>{{ $data['pendingCustomers'] }}</strong>
                                customer(s) pending approval.
                            </div>
                            <span class="fw-bold">View →</span>
                        </div>
                    </a>
                </div>
            @endif

            @if ($data['pendingOrders'] > 0)
                <div class="col-12">
                    <a href="{{ route('dashboard.orders.index') }}" class="text-decoration-none">
                        <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm">
                            <div>
                                <i class="ti ti-shopping-cart-pause me-2 fs-4"></i>
                                <strong>{{ $data['pendingOrders'] }}</strong>
                                order(s) pending.
                            </div>
                            <span class="fw-bold">View →</span>
                        </div>
                    </a>
                </div>
            @endif

            @if ($data['pendingWithdraws'] > 0)
                <div class="col-12">
                    <a href="{{ route('dashboard.withdraws.index') }}"
                        class="text-decoration-none">
                        <div class="alert alert-danger d-flex justify-content-between align-items-center shadow-sm">
                            <div>
                                <i class="ti ti-cash-banknote me-2 fs-4"></i>
                                <strong>{{ $data['pendingWithdraws'] }}</strong>
                                withdrawal request(s) pending.
                            </div>
                            <span class="fw-bold">View →</span>
                        </div>
                    </a>
                </div>
            @endif

            @if ($data['pendingRecharge'] > 0)
                <div class="col-12">
                    <a href="{{ route('dashboard.recharges.index') }}"
                        class="text-decoration-none">
                        <div class="alert alert-primary d-flex justify-content-between align-items-center shadow-sm">
                            <div>
                                <i class="ti ti-wallet me-2 fs-4"></i>
                                <strong>{{ $data['pendingRecharge'] }}</strong>
                                recharge request(s) pending.
                            </div>
                            <span class="fw-bold">View →</span>
                        </div>
                    </a>
                </div>
            @endif

        </div>
    @endif
    <div class="row">

        {{-- Role-based Stats --}}
        @php
            $cards = [];

            if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')) {
                $cards = [
                    [
                        'title' => 'Total Agents',
                        'value' => $data['totalAgents'],
                        'icon' => 'ti ti-users',
                        'color' => 'primary',
                    ],
                    [
                        'title' => 'Total Customers',
                        'value' => $data['totalCustomers'],
                        'icon' => 'ti ti-user',
                        'color' => 'success',
                    ],
                    [
                        'title' => 'Pending Customers',
                        'value' => $data['pendingCustomers'],
                        'icon' => 'ti ti-user-exclamation',
                        'color' => 'warning',
                    ],
                    [
                        'title' => 'Total Revenue',
                        'value' => '$' . number_format($data['totalRevenue'], 2),
                        'icon' => 'ti ti-currency-dollar',
                        'color' => 'secondary',
                    ],
                ];
            } else {
                $cards = [
                    [
                        'title' => 'My Customers',
                        'value' => $data['totalCustomers'],
                        'icon' => 'ti ti-users',
                        'color' => 'success',
                    ],
                    [
                        'title' => 'Pending Customers',
                        'value' => $data['pendingCustomers'],
                        'icon' => 'ti ti-user-pause',
                        'color' => 'warning',
                    ],
                    [
                        'title' => 'Completed Orders',
                        'value' => $data['completedOrders'],
                        'icon' => 'ti ti-shopping-cart',
                        'color' => 'primary',
                    ],
                    [
                        'title' => 'Total Commission',
                        'value' => '$' . number_format($data['totalCommission'], 2),
                        'icon' => 'ti ti-coins',
                        'color' => 'secondary',
                    ],
                ];
            }
        @endphp

        @foreach ($cards as $card)
            <div class="col-xl-3 col-md-6 col-12">
                <div class="card text-white bg-gradient-{{ $card['color'] }} h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-1">{{ $card['title'] }}</h6>
                            <h3 class="text-white mb-0">{{ $card['value'] }}</h3>
                        </div>
                        <i class="{{ $card['icon'] }} fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        @endforeach

    </div>

    {{-- Charts Row --}}
    <div class="row mt-4">

        <div class="col-xl-8 col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Orders Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="ordersChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Customer Status</h5>
                </div>
                <div class="card-body">
                    <canvas id="customerPie"></canvas>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Orders Line Chart
        new Chart(document.getElementById('ordersChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($data['months'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
                datasets: [{
                    label: 'Orders',
                    data: {!! json_encode($data['monthlyOrders'] ?? [5, 10, 8, 15, 7, 20]) !!},
                    borderColor: '#696cff',
                    backgroundColor: 'rgba(105,108,255,0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Customer Pie Chart
        new Chart(document.getElementById('customerPie'), {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending'],
                datasets: [{
                    data: [
                        {{ $data['totalCustomers'] - $data['pendingCustomers'] }},
                        {{ $data['pendingCustomers'] }}
                    ],
                    backgroundColor: ['#71dd37', '#ffab00']
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endsection
