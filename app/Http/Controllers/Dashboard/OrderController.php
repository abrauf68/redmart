<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view order');
        try {
            $orders = Order::latest()->get();
            return view('dashboard.orders.index', compact('orders'));
        } catch (\Throwable $th) {
            Log::error("Order Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
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
        $this->authorize('view order');

        try {
            $order = Order::with('product', 'user')->findOrFail($id);
            return view('dashboard.orders.show', compact('order'));
        } catch (\Throwable $th) {
            Log::error("Order Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong!");
        }
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
        $this->authorize('update order');
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($request->order_id);

            $user = User::where('id', $order->user_id)->first();

            $wallet = Wallet::where('user_id', $order->user_id)->first();

            if (!$wallet || $wallet->balance < $order->total) {

                Log::info([
                    'wallet_balance' => $wallet->balance ?? 0,
                    'order_total' => $order->total
                ]);

                return redirect()->back()
                    ->with('error', 'Insufficient funds in user wallet to process this order!');
            }

            $order->description_rating = 5;
            $order->logistics_rating = 5;
            $order->service_rating = 5;
            $order->status = $request->status;
            $order->save();

            if($request->status == 'completed')
            {
                $wallet->balance += $order->commission;
                $wallet->save();

                Transaction::create([
                    'transaction_id' => Str::uuid(),
                    'user_id' => $order->user_id,
                    'money_flow' => 'in',
                    'transaction_type' => 'reward',
                    'amount' => $order->commission,
                    'description' => 'Commission for Order #' . $order->order_no,
                    'status' => 'completed',
                ]);

                app('notificationService')->notifyUsers(
                    [$user],
                    'Order Completed',
                    'Your order #' . $order->order_no . ' has been completed. Commission added successfully.',
                    'orders',
                    $order->id,
                    'orders'
                );
            }

            DB::commit();
            return redirect()->route('dashboard.orders.index')->with('success', 'Order status updated successfully');
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            Log::error('Order status update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete order');
        try {
            $order = Order::with('user', 'product')->findOrFail($id);
            $order->delete();
            return redirect()->back()->with('success', 'Order Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Order Deletion Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}

