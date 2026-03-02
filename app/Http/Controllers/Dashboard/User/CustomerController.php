<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view customer');

        try {
            $user = User::findOrFail(auth()->user()->id);

            $customers = User::with('inviter')
                ->role('user');

            if ($user->hasRole('agent')) {
                $customers->where('inviter_id', $user->id);
            }

            $customers = $customers->latest()->get();

            return view('dashboard.customers.index', compact('customers'));
        } catch (\Throwable $th) {
            Log::error("Customer Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong!");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create customer');
        try {
            $agents = User::role('agent')->latest()->get();
            return view('dashboard.customers.create', compact('agents'));
        } catch (\Throwable $th) {
            Log::error("Customer Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create customer');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'inviter_id' => 'required|exists:users,id',
            'phone' => 'nullable|string|max:255',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->email_verified_at = now();
            $user->inviter_id = $request->inviter_id;
            $user->is_approved = '1';
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);


            $username = $this->generateUsername($request->name);

            while (User::where('username', $username)->exists()) {
                $username = $this->generateUsername($request->name);
            }
            $user->username = $username;
            $user->save();

            $user->syncRoles('user');

            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->first_name = $request->name;
            $profile->save();

            $wallet = new Wallet();
            $wallet->user_id = $user->id;
            $wallet->wallet_address = Helper::generateUniqueWalletAddress();
            $wallet->balance = 0.00;
            $wallet->status = 'active';
            $wallet->save();

            DB::commit();
            return redirect()->route('dashboard.customers.index')->with('success', 'Customer Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Customer Store Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view customer');

        try {
            $customer = User::with('wallet', 'inviter', 'bankDetails')->findOrFail($id);

            $orders = Order::with('product')->where('user_id', $id)->latest()->get();

            $transactions = $customer->transactions()->latest()->limit(10)->get();

            $withdraws = $customer->withdraws()->latest()->limit(10)->get();

            return view('dashboard.customers.show', compact('customer', 'orders', 'transactions', 'withdraws'));
        } catch (\Throwable $th) {
            Log::error("Customer Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong!");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update customer');
        try {
            $customer = User::findOrFail($id);
            $agents = User::role('agent')->latest()->get();
            return view('dashboard.customers.edit', compact('customer', 'agents'));
        } catch (\Throwable $th) {
            Log::error("Customer Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('create customer');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'inviter_id' => 'required|exists:users,id',
            'phone' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->inviter_id = $request->inviter_id;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            DB::commit();
            return redirect()->route('dashboard.customers.index')->with('success', 'Customer Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Customer Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete customer');
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->back()->with('success', 'Customer Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Customer Deletion Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update customer');
        try {
            $user = User::findOrFail($id);
            $message = $user->is_active == 'active' ? 'Customer Deactivated Successfully' : 'Customer Activated Successfully';
            if ($user->is_active == 'active') {
                $user->is_active = 'inactive';
                $user->save();
            } else {
                $user->is_active = 'active';
                $user->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Customer Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function updateCustomerWallet(Request $request, $id)
    {
        $this->authorize('update customer');
        $validator = Validator::make($request->all(), [
            'balance' => 'required|numeric|min:0',
            'freeze_balance' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {
            $customer = User::findOrFail($id);
            $wallet = $customer->wallet;
            $wallet->balance = $request->balance;
            $wallet->freeze_balance = $request->freeze_balance;
            $wallet->save();

            return redirect()->back()->with('success', 'Customer Wallet Updated Successfully!');
        } catch (\Throwable $th) {
            Log::error("Customer Wallet Update Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong!");
        }
    }

    public function updateCustomerScore(Request $request, $id)
    {
        $this->authorize('update customer');
        $validator = Validator::make($request->all(), [
            'credit_score' => 'required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {
            $customer = User::findOrFail($id);
            $customer->credit_score = $request->credit_score;
            $customer->save();

            return redirect()->back()->with('success', 'Customer Credit Score Updated Successfully!');
        } catch (\Throwable $th) {
            Log::error("Customer Credit Score Update Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong!");
        }
    }

    public function approveCustomer(Request $request, $id)
    {
        $this->authorize('update customer');

        try {
            $customer = User::findOrFail($id);
            $customer->is_approved = '1';
            $customer->save();

            return redirect()->back()->with('success', 'Customer Approved Successfully!');
        } catch (\Throwable $th) {
            Log::error("Customer Approve Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong!");
        }
    }

    public function generateUsername($name)
    {
        $name = strtolower(str_replace(' ', '', $name));
        $username = $name . rand(1000, 9999);
        return $username;
    }
}
