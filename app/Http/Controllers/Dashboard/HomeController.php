<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::findOrFail(auth()->user()->id);

        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            // Admin Dashboard
            $data = [
                'totalAgents' => User::role('agent')->count(),
                'totalCustomers' => User::role('user')->count(),
                'pendingCustomers' => User::role('user')->where('is_approved', '0')->count(),
                'totalOrders' => Order::count(),
                'completedOrders' => Order::where('status', 'completed')->count(),
                'totalRevenue' => Order::where('status', 'completed')->sum('total'),
                'totalWithdraws' => Withdraw::count(),
                'pendingWithdraws' => Withdraw::where('status', 'pending')->count(),
            ];

            // Monthly Orders for Admin (all orders)
            $monthlyOrdersQuery = Order::query();
        } else {
            // Agent Dashboard
            $customerIds = User::where('inviter_id', $user->id)->pluck('id');

            $data = [
                'totalCustomers' => $customerIds->count(),
                'pendingCustomers' => User::whereIn('id', $customerIds)->where('is_approved', '0')->count(),
                'totalOrders' => Order::whereIn('user_id', $customerIds)->count(),
                'completedOrders' => Order::whereIn('user_id', $customerIds)->where('status', 'completed')->count(),
                'totalCommission' => Order::whereIn('user_id', $customerIds)->where('status', 'completed')->sum('commission'),
                'totalWithdraws' => Withdraw::where('user_id', $user->id)->count(),
            ];

            // Monthly Orders only for agent's customers
            $monthlyOrdersQuery = Order::whereIn('user_id', $customerIds);
        }

        // Monthly Orders Calculation (last 6 months)
        $months = [];
        $monthlyOrders = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');

            $monthlyOrders[] = (clone $monthlyOrdersQuery)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        $data['months'] = $months;
        $data['monthlyOrders'] = $monthlyOrders;

        return view('dashboard.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
