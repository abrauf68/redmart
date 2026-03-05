<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserBankDetail;
use App\Models\Wallet;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function home()
    {
        try {
            $user = Auth::user();
            $randomProducts = Product::where('is_active', 'active')->inRandomOrder()->take(6)->get();
            $popularProducts = Product::where('is_active', 'active')->where('is_popular', '1')->latest()->limit(5)->get();

            $totalBalance = $user->wallet->balance;
            $totalFreeze = $user->wallet->freeze_balance;
            $totalEarned = Order::where('user_id', $user->id)->where('status', 'completed')->sum('commission');

            return view('frontend.pages.home', compact('randomProducts', 'popularProducts', 'totalBalance', 'totalEarned', 'totalFreeze'));
        } catch (\Throwable $th) {
            Log::error('Error loading home page: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the home page.');
        }
    }

    public function support()
    {
        try {
            return view('frontend.pages.support');
        } catch (\Throwable $th) {
            Log::error('Error loading support page: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the home page.');
        }
    }

    public function recharge()
    {
        try {
            return view('frontend.pages.recharge');
        } catch (\Throwable $th) {
            Log::error('Error loading recharge page: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the home page.');
        }
    }

    public function submitRecharge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            Log::error('Recharge Submit Failed Validation', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $user = User::where('id', auth()->user()->id)->first();
            $transaction = Transaction::where('user_id', $user->id)->where('transaction_type', 'recharge')->where('status', 'pending')->first();
            if (!$transaction) {
                $transaction = Transaction::create([
                    'transaction_id' => Str::uuid(),
                    'user_id' => $user->id,
                    'money_flow' => 'in',
                    'transaction_type' => 'recharge',
                    'amount' => $request->amount,
                    'description' => 'Wallet Recharge',
                    'status' => 'pending',
                ]);

                app('notificationService')->notifyUsers(
                    [$user],
                    'Recharge Request',
                    'You have requested for recharge of ' . Helper::formatCurrency($request->amount),
                    'transactions',
                    $transaction->id,
                    'transactions'
                );
            } else {
                $oldAmount = $transaction->amount;
                $transaction->update([
                    'amount' => $request->amount
                ]);

                app('notificationService')->notifyUsers(
                    [$user],
                    'Recharge Amount Updated',
                    'You recharge amount updated from ' . Helper::formatCurrency($oldAmount) . ' to ' . Helper::formatCurrency($request->amount),
                    'transactions',
                    $transaction->id,
                    'transactions'
                );
            }

            DB::commit();
            return redirect()->route('frontend.support')->with('success', 'Recharge Requested Successfully');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            Log::error('Recharge Request Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function start()
    {
        try {
            $wallet = Wallet::where('user_id', auth()->id())->first();
            $orders = Order::where('user_id', auth()->id())->latest()->get();
            $totalOrders = $orders->count();
            $completedOrders = $orders->where('status', 'completed')->count();
            $pendingOrders = $orders->where('status', 'pending')->count();
            $earnedCommission = $orders->where('status', 'completed')->sum('commission');
            $pendingCommission = $orders->where('status', 'pending')->sum('commission');
            return view('frontend.pages.start', compact('wallet', 'totalOrders', 'completedOrders', 'pendingOrders', 'earnedCommission', 'pendingCommission'));
        } catch (\Throwable $th) {
            Log::error('Error loading start page: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the start page.');
        }
    }

    // public function grabOrder(Request $request)
    // {
    //     $user = $request->user();

    //     // 1️⃣ Check Pending Order
    //     $pendingOrder = Order::where('user_id', $user->id)
    //         ->where('status', 'pending')
    //         ->first();

    //     if ($pendingOrder) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'You have a pending order. Please complete it first.'
    //         ]);
    //     }

    //     // 2️⃣ Get Active Product
    //     $product = Product::where('is_active', 'active')
    //         ->inRandomOrder()
    //         ->first();

    //     if (!$product) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'No product available'
    //         ]);
    //     }

    //     $price = (float) $product->price;

    //     // 3️⃣ Count Completed Orders
    //     $orderCount = Order::where('user_id', $user->id)
    //         ->where('status', 'completed')
    //         ->count();

    //     $balance = $user->wallet->balance;
    //     $commissionRate = 0.02; // default 2%

    //     // 4️⃣ Quantity Logic
    //     if ($orderCount == 0) {

    //         // ✅ First Order (balance zero allowed)
    //         $quantity = rand(1, 5);
    //     } elseif ($orderCount >= 1 && $orderCount < 4) {

    //         if ($balance <= 0) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Please recharge to continue grabbing orders.'
    //             ]);
    //         }

    //         $maxQuantity = floor($balance / $price);

    //         if ($maxQuantity < 1) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Insufficient balance.'
    //             ]);
    //         }

    //         $quantity = rand(1, $maxQuantity);
    //     } else {

    //         // 🔥 5th Order Special Combo
    //         // Subtotal = balance + 60%

    //         if ($balance <= 0) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Please recharge to continue.'
    //             ]);
    //         }

    //         $targetSubtotal = $balance * 1.6; // 60% above balance
    //         $quantity = ceil($targetSubtotal / $price);

    //         $commissionRate = 0.10; // 🔥 10% commission
    //     }

    //     // 5️⃣ Calculate
    //     $subtotal = $price * $quantity;
    //     $commission = $subtotal * $commissionRate;

    //     // 6️⃣ Create Order
    //     $order = Order::create([
    //         'user_id' => $user->id,
    //         'product_id' => $product->id,
    //         'order_no' => 'ORD-' . strtoupper(Str::random(6)),
    //         'status' => 'pending',
    //         'quantity' => $quantity,
    //         'subtotal' => $subtotal,
    //         'total' => $subtotal,
    //         'commission' => $commission,
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'product_image' => $product->main_image,
    //         'product_name' => $product->name,
    //         'price' => Helper::formatCurrency($price),
    //         'quantity' => $quantity,
    //         'subtotal' => Helper::formatCurrency($subtotal),
    //         'total' => Helper::formatCurrency($subtotal),
    //         'commission' => Helper::formatCurrency($commission),
    //         'order_id' => $order->id
    //     ]);
    // }

    public function grabOrder(Request $request)
    {
        $user = $request->user();

        $totalOrders = Order::where('user_id', $user->id)->count();

        if($totalOrders >= $user->order_limit){
            return response()->json([
                'status' => false,
                'is_limit_reached' => true,
                'message' => 'Order Limit Reached! You cannot grab a new order now.'
            ]);
        }

        // 1️⃣ Check Pending Order
        $pendingOrder = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingOrder) {
            return response()->json([
                'status' => false,
                'is_order_pending' => true,
                'message' => 'You have a pending order. Please complete it first.'
            ]);
        }

        // 2️⃣ Get Used Product IDs (Completed Orders Only)
        $usedProductIds = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->pluck('product_id')
            ->toArray();

        // 3️⃣ Get Active Products excluding used ones
        $productQuery = Product::where('is_active', 'active');

        if (!empty($usedProductIds)) {
            $productQuery->whereNotIn('id', $usedProductIds);
        }

        $product = $productQuery->inRandomOrder()->first();

        // 4️⃣ If all products already used → reset rotation
        if (!$product) {
            $product = Product::where('is_active', 'active')
                ->inRandomOrder()
                ->first();
        }

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'No product available'
            ]);
        }

        $price = (float) $product->price;

        // 3️⃣ Count Completed Orders
        $orderCount = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $currentOrderNumber = $orderCount + 1;

        $balance = $user->wallet->balance;
        $commissionRate = 0.015; // default

        // 4️⃣ Quantity Logic
        if ($orderCount == 0) {

            do {
                $productQuery = Product::where('is_active', 'active');
                if (!empty($usedProductIds)) {
                    $productQuery->whereNotIn('id', $usedProductIds);
                }
                $product = $productQuery->inRandomOrder()->first();

                if (!$product) {
                    $product = Product::where('is_active', 'active')
                        ->inRandomOrder()
                        ->first();
                }

                $price = (float) $product->price;

                $allowedAmount = $balance > 0 ? $balance : 20;
                $maxQuantity = floor($allowedAmount / $price);

            } while ($maxQuantity < 1);

            $quantity = rand(1, $maxQuantity);
        } elseif (
            $user->special_order_number
            && $currentOrderNumber == $user->special_order_number
        ) {
            if ($balance <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please recharge to continue grabbing orders.'
                ]);
            }

            $multiplier = $user->special_multiplier ?? 1.6;
            $commissionRate = $user->special_commission_percentage / 100;

            $targetSubtotal = $balance * $multiplier;
            $quantity = ceil($targetSubtotal / $price);
        } else {

            if ($balance <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please recharge to continue grabbing orders.'
                ]);
            }

            do {
                $productQuery = Product::where('is_active', 'active');
                if (!empty($usedProductIds)) {
                    $productQuery->whereNotIn('id', $usedProductIds);
                }
                $product = $productQuery->inRandomOrder()->first();

                if (!$product) {
                    $product = Product::where('is_active', 'active')
                        ->inRandomOrder()
                        ->first();
                }

                $price = (float) $product->price;

                $allowedAmount = $balance > 0 ? $balance : 50;
                $maxQuantity = floor($allowedAmount / $price);

            } while ($maxQuantity < 1);

            $quantity = rand(1, $maxQuantity);
        }

        // 5️⃣ Calculate
        $subtotal = $price * $quantity;
        $commission = $subtotal * $commissionRate;

        // 6️⃣ Create Order
        $order = Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_no' => 'ORD-' . strtoupper(Str::random(6)),
            'status' => 'pending',
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'commission' => $commission,
        ]);

        return response()->json([
            'status' => true,
            'product_image' => $product->main_image,
            'product_name' => $product->name,
            'price' => Helper::formatCurrency($price),
            'quantity' => $quantity,
            'subtotal' => Helper::formatCurrency($subtotal),
            'total' => Helper::formatCurrency($subtotal),
            'commission' => Helper::formatCurrency($commission),
            'order_id' => $order->id
        ]);
    }

    public function orders()
    {
        try {
            $orders = Order::with('product')->where('user_id', auth()->id())->latest()->get();
            $totalOrders = $orders->count();
            $completedOrders = $orders->where('status', 'completed')->count();
            $pendingOrders = $orders->where('status', 'pending')->count();
            return view('frontend.pages.orders', compact('orders', 'totalOrders', 'completedOrders', 'pendingOrders'));
        } catch (\Throwable $th) {
            Log::error('Error loading orders page: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the orders page.');
        }
    }

    public function proceed(Request $request)
    {
        try {
            $order = Order::find($request->order_id);

            if ($order->user_id != auth()->user()->id) {
                return response()->json(['status' => false, 'message' => 'Unauthorized user']);
            }

            if ($order->status !== 'pending') {
                return response()->json(['status' => false, 'message' => 'Order already processed']);
            }

            $wallet = auth()->user()->wallet;

            if (!$wallet || $wallet->balance < $order->total) {
                return response()->json([
                    'status' => false,
                    'type' => 'insufficient'
                ]);
            }

            DB::transaction(function () use ($order, $wallet, $request) {

                // 1️⃣ Deduct balance
                // $wallet->balance -= $order->total;
                // $wallet->save();

                // Transaction::create([
                //     'transaction_id' => Str::uuid(),
                //     'user_id' => $order->user_id,
                //     'money_flow' => 'out',
                //     'transaction_type' => 'purchase',
                //     'amount' => $order->total,
                //     'description' => 'Order Purchase #' . $order->order_no,
                //     'status' => 'completed',
                // ]);

                // 3️⃣ Update order status
                $order->update([
                    'description_rating' => $request->description_rating,
                    'logistics_rating' => $request->logistics_rating,
                    'service_rating' => $request->service_rating,
                    'status' => 'completed',
                ]);

                // 4️⃣ Add commission
                $wallet->balance += $order->commission;
                $wallet->save();

                // 5️⃣ Create IN transaction (commission)
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
                    [auth()->user()],
                    'Order Completed',
                    'Your order #' . $order->order_no . ' has been completed. Commission added successfully.',
                    'orders',
                    $order->id,
                    'orders'
                );
            });

            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            Log::error('Error processing order: ' . $th->getMessage());
            return response()->json(['status' => false]);
        }
    }

    public function wallet()
    {
        try {
            $user = auth()->user();
            $wallet = $user->wallet;
            $transactions = Transaction::where('user_id', $user->id)->latest()->get();
            if (!$wallet) {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'balance' => 0.00,
                    'wallet_address' => Helper::generateUniqueWalletAddress(),
                ]);
            }
            $withdraws = Withdraw::where('user_id', $user->id)->latest()->get();
            $totalFreeze = $user->wallet->freeze_balance;
            return view('frontend.pages.wallet', compact('wallet', 'transactions', 'totalFreeze'));
        } catch (\Throwable $th) {
            Log::error('Error loading wallet page: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the wallet page.');
        }
    }

    public function withdraw()
    {
        try {
            $user = User::with('bankDetails')->findOrFail(auth()->id());
            $wallet = $user->wallet;
            $withdraws = Withdraw::where('user_id', $user->id)->latest()->get();
            return view('frontend.pages.withdraw', compact('wallet', 'withdraws', 'user'));
        } catch (\Throwable $th) {
            Log::error('Error loading withdraw page: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the withdraw page.');
        }
    }

    public function submitWithdraw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:10|max:100000',
            'user_note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $user = User::where('id', auth()->user()->id)->first();
            $wallet = $user->wallet;

            if (!$wallet || $wallet->balance < $request->amount) {
                return redirect()->back()->with('error', 'Insufficient balance!');
            }
            if ($user->credit_score < 100) {
                return redirect()->back()->with('error', 'Withdrawal not allowed. Your credit score is currently too low to process this request.');
            }
            // Check if any pending order exists
            $hasPendingOrder = Order::where('user_id', $user->id)->where('status', 'pending')->exists();

            if ($hasPendingOrder) {
                return redirect()->back()->with(
                    'error',
                    'Withdrawal not allowed. You have a pending order. Please complete it first.'
                );
            }

            $wallet->balance -= $request->amount;
            $wallet->save();

            $transaction = Transaction::create([
                'transaction_id' => Str::uuid(),
                'user_id' => $user->id,
                'money_flow' => 'out',
                'transaction_type' => 'withdrawal',
                'amount' => $request->amount,
                'description' => 'Withdrawal Request',
                'status' => 'pending',
            ]);

            Withdraw::create([
                'user_id' => $user->id,
                'transaction_id' => $transaction->id,
                'amount' => $request->amount,
                'wallet_address' => $wallet->wallet_address,
                'user_note' => $request->user_note,
                'status' => 'pending',
            ]);

            app('notificationService')->notifyUsers(
                [$user],
                'Withdrawal Requested',
                'Your withdrawal request of ' . Helper::formatCurrency($request->amount) . ' is submitted and pending approval.',
                'withdraws',
                $transaction->id,
                'withdraw-history'
            );

            DB::commit();
            return redirect()->back()->with('success', 'Withdrawal requested successfully!');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            Log::error('Withdrawal Request Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function profile()
    {
        try {
            $user = User::with('bankDetails')->findOrFail(auth()->id());
            return view('frontend.pages.profile', compact('user'));
        } catch (\Throwable $th) {
            Log::error('Error loading profile page: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the profile page.');
        }
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max_size',
            'phone' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            Log::error('Profile Updated Failed Validation', ['errors' => $validator->errors()]);
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $user = User::where('id', auth()->user()->id)->first();
            $user->name = $request->name;
            $user->phone = $request->phone;
            if ($request->hasFile('image')) {
                if (isset($user->image) && File::exists(public_path($user->image))) {
                    File::delete(public_path($user->image));
                }

                $profileImage = $request->file('image');
                $profileImage_ext = $profileImage->getClientOriginalExtension();
                $profileImage_name = time() . '_image.' . $profileImage_ext;

                $profileImage_path = 'uploads/profile-images';
                $profileImage->move(public_path($profileImage_path), $profileImage_name);
                $user->image = $profileImage_path . "/" . $profileImage_name;
            }
            $user->save();

            app('notificationService')->notifyUsers(
                [$user],
                'Profile Updated',
                'Your profile has been updated successfully.',
                'users',
                $user->id,
                'profile'
            );

            DB::commit();
            return redirect()->back()->with('success', 'Profile Updated Successfully');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            Log::error('Profile Updated Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function updateBankDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'method' => 'required|in:bank,crypto',
            'bank_name' => 'nullable|string|max:255',
            'beneficiary_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_type' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'crypto_type' => 'nullable|string|max:255',
            'crypto_address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $user = User::where('id', auth()->user()->id)->first();

            $userBankDetails = UserBankDetail::where('user_id', auth()->user()->id)->first();
            if (!$userBankDetails) {
                UserBankDetail::create([
                    'user_id' => $user->id,
                    'method' => $request->method,
                    'bank_name' => $request->bank_name,
                    'beneficiary_name' => $request->beneficiary_name,
                    'account_number' => $request->account_number,
                    'account_type' => $request->account_type,
                    'ifsc_code' => $request->ifsc_code,
                    'branch' => $request->branch,
                    'crypto_type' => $request->crypto_type,
                    'crypto_address' => $request->crypto_address,
                ]);
            } else {
                $userBankDetails->update([
                    'method' => $request->method,
                    'bank_name' => $request->bank_name,
                    'beneficiary_name' => $request->beneficiary_name,
                    'account_number' => $request->account_number,
                    'account_type' => $request->account_type,
                    'ifsc_code' => $request->ifsc_code,
                    'branch' => $request->branch,
                    'crypto_type' => $request->crypto_type,
                    'crypto_address' => $request->crypto_address,
                ]);
            }

            app('notificationService')->notifyUsers(
                [$user],
                'Bank Details Updated',
                'Your bank details have been updated successfully.',
                'user_bank_details',
                $user->id,
                'bank-details'
            );

            DB::commit();
            return redirect()->back()->with('success', 'Bank Details Updated Successfully');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            Log::error('Bank Details Updated Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $user = User::where('id', auth()->user()->id)->first();

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->with('error', 'Current password is incorrect!');
            }

            $user->password = Hash::make($request->password);
            $user->save();

            app('notificationService')->notifyUsers(
                [$user],
                'Password Changed',
                'Your account password has been updated successfully.',
                'users',
                $user->id,
                'profile'
            );

            DB::commit();
            return redirect()->back()->with('success', 'Password Updated Successfully');
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
            Log::error('Password Updated Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function products()
    {
        try {
            $products = Product::where('is_active', 'active')->latest()->paginate(10);
            return view('frontend.pages.products', compact('products'));
        } catch (\Throwable $th) {
            Log::error('Error loading products page: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the products page.');
        }
    }

    public function productDetails($sku)
    {
        try {
            $product = Product::where('is_active', 'active')->where('sku', $sku)->firstOrFail();
            return view('frontend.pages.product-details', compact('product'));
        } catch (\Throwable $th) {
            Log::error('Error loading product details page: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the product details page.');
        }
    }

    public function notifications()
    {
        try {
            $notifications = Notification::where('user_id', auth()->id())->orderByRaw('read_at IS NULL DESC')
                ->orderBy('created_at', 'desc')
                ->get();
            return view('frontend.pages.notifications', compact('notifications'));
        } catch (\Throwable $th) {
            Log::error('Error loading notifications page: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the notifications page.');
        }
    }

    public function markAllReadNoti()
    {
        try {
            Notification::where('user_id', auth()->id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            return redirect()->route('frontend.notifications')->with('success', 'All notifications marked as read successfully!');
        } catch (\Throwable $th) {
            Log::error('Error markAllReadNoti: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Try again.');
        }
    }

    public function deleteAllNoti()
    {
        try {
            Notification::where('user_id', auth()->id())->delete();
            return redirect()->route('frontend.notifications')->with('success', 'All notifications has been deleted!');
        } catch (\Throwable $th) {
            Log::error('Error deleteAllNoti: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Try again.');
        }
    }

    public function markReadNoti($id)
    {
        try {
            Notification::where('id', $id)
                ->where('user_id', auth()->id())
                ->update(['read_at' => now()]);
            return redirect()->route('frontend.notifications')->with('success', 'Notification marked as read successfully!');
        } catch (\Throwable $th) {
            Log::error('Error markReadNoti: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Try again.');
        }
    }

    public function deleteNoti($id)
    {
        try {
            Notification::where('id', $id)
                ->where('user_id', auth()->id())
                ->delete();
            return redirect()->route('frontend.notifications')->with('success', 'Notification deleted successfully!');
        } catch (\Throwable $th) {
            Log::error('Error deleteNoti: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Try again.');
        }
    }
}
