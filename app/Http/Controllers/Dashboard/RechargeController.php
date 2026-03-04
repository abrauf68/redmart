<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RechargeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view recharge');

        try {

            $authUser = User::where('id', auth()->id())->first();

            $recharges = Transaction::with('user')
                ->where('transaction_type', 'recharge')
                ->when($authUser->hasRole('agent'), function ($query) use ($authUser) {
                    $query->whereHas('user', function ($q) use ($authUser) {
                        $q->where('inviter_id', $authUser->id);
                    });
                })
                ->latest()
                ->get();

            return view('dashboard.recharges.index', compact('recharges'));
        } catch (\Throwable $th) {
            Log::error("Recharge Index Failed: " . $th->getMessage());
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
        $this->authorize('view recharge');

        try {
            $recharge = Transaction::with('user')->findOrFail($id);
            return view('dashboard.recharges.show', compact('recharge'));
        } catch (\Throwable $th) {
            Log::error("Recharge Show Failed:" . $th->getMessage());
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
        $this->authorize('update recharge');
        $validator = Validator::make($request->all(), [
            'recharge_id' => 'required|exists:transactions,id',
            'status' => 'required|in:pending,completed,failed,cancelled',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();

            $recharge = Transaction::findOrFail($request->recharge_id);

            $user = User::where('id', $recharge->user_id)->first();

            $wallet = Wallet::where('user_id', $user->id)->first();

            if ($request->status == 'completed') {
                $wallet->balance += $recharge->amount;
                $wallet->save();

                app('notificationService')->notifyUsers(
                    [$user],
                    'Recharge Approved',
                    'Your request of recharge amount ' . Helper::formatCurrency($recharge->amount) . ' has been approved and added to your wallet.',
                    'transactions',
                    $recharge->id,
                    'recharges'
                );

                $message = 'Recharge request has been approved successfully.';
            } elseif ($request->status == 'failed') {
                app('notificationService')->notifyUsers(
                    [$user],
                    'Recharge Failed',
                    'Your request of recharge amount ' . Helper::formatCurrency($recharge->amount) . ' has been failed. Please try again.',
                    'transactions',
                    $recharge->id,
                    'recharges'
                );

                $message = 'Recharge request has been marked as failed.';
            } elseif ($request->status == 'cancelled') {
                app('notificationService')->notifyUsers(
                    [$user],
                    'Recharge Cancelled',
                    'Your request of recharge amount ' . Helper::formatCurrency($recharge->amount) . ' has been cancelled. Please contact support for more details.',
                    'transactions',
                    $recharge->id,
                    'recharges'
                );

                $message = 'Recharge request has been cancelled.';
            } else {
                $message = 'Recharge request status updated successfully.';
            }

            $recharge->status = $request->status;
            $recharge->description = $request->description;
            $recharge->save();

            DB::commit();
            return redirect()->route('dashboard.recharges.index')->with('success', $message);
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            Log::error('Recharge status update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete recharge');
        try {
            $recharge = Transaction::findOrFail($id);
            $recharge->delete();
            return redirect()->back()->with('success', 'Recharge Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Recharge Deletion Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
