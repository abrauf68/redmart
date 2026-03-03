<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view withdraw');

        try {

            $authUser = User::where('id', auth()->id())->first();

            $withdraws = Withdraw::with('user')
                ->when($authUser->hasRole('agent'), function ($query) use ($authUser) {
                    $query->whereHas('user', function ($q) use ($authUser) {
                        $q->where('inviter_id', $authUser->id);
                    });
                })
                ->latest()
                ->get();

            return view('dashboard.withdraws.index', compact('withdraws'));
        } catch (\Throwable $th) {
            Log::error("Withdraw Index Failed: " . $th->getMessage());
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
        $this->authorize('view withdraw');

        try {
            $withdraw = Withdraw::with('transaction', 'user')->findOrFail($id);
            return view('dashboard.withdraws.show', compact('withdraw'));
        } catch (\Throwable $th) {
            Log::error("Withdraw Show Failed:" . $th->getMessage());
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
        $this->authorize('update withdraw');
        $validator = Validator::make($request->all(), [
            'withdraw_id' => 'required|exists:withdraws,id',
            'status' => 'required|in:pending,approved,rejected',
            'admin_note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();

            $withdraw = Withdraw::findOrFail($request->withdraw_id);

            $user = User::where('id', $withdraw->user_id)->first();

            $transaction = Transaction::where('id', $withdraw->transaction_id)->first();

            $wallet = Wallet::where('user_id', $user->id)->first();

            if ($request->status == 'rejected') {

                $wallet->balance += $transaction->amount;
                $wallet->save();

                $transaction->status = 'cancelled';
                $transaction->save();

                Transaction::create([
                    'transaction_id' => Str::uuid(),
                    'user_id' => $user->id,
                    'money_flow' => 'in',
                    'transaction_type' => 'refund',
                    'amount' => $withdraw->amount,
                    'description' => 'Withdraw Cancelled Amount Refunded.',
                    'status' => 'completed',
                ]);

                app('notificationService')->notifyUsers(
                    [$user],
                    'Withdraw Rejected',
                    'Your request of withdraw amount ' . Helper::formatCurrency($withdraw->amount) . ' has been cancelled and refunded to your wallet.',
                    'withdraws',
                    $withdraw->id,
                    'withdraws'
                );

                $message = 'Withdraw request has been rejected successfully.';
            } elseif ($request->status == 'approved') {
                $transaction->status = 'completed';
                $transaction->save();

                app('notificationService')->notifyUsers(
                    [$user],
                    'Withdraw Approved',
                    'Your request of withdraw amount ' . Helper::formatCurrency($withdraw->amount) . ' has been approved and deducted from your wallet.',
                    'withdraws',
                    $withdraw->id,
                    'withdraws'
                );

                $message = 'Withdraw request has been approved successfully.';
            } else {
                $message = 'Something went wrong!';
            }

            $withdraw->status = $request->status;
            $withdraw->admin_note = $request->admin_note;
            $withdraw->save();

            DB::commit();
            return redirect()->route('dashboard.withdraws.index')->with('success', $message);
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            Log::error('Withdraw status update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete withdraw');
        try {
            $withdraw = Withdraw::findOrFail($id);
            $withdraw->delete();
            return redirect()->back()->with('success', 'Withdraw Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Withdraw Deletion Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
