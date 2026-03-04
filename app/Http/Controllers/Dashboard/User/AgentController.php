<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view agent');
        try {
            $agents = User::withCount('referrals')->role('agent')->latest()->get();
            return view('dashboard.agents.index', compact('agents'));
        } catch (\Throwable $th) {
            Log::error("Agent Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create agent');
        try {
            return view('dashboard.agents.create');
        } catch (\Throwable $th) {
            Log::error("Agent Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create agent');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
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
            $user->is_approved = '1';
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);


            $username = $this->generateUsername($request->name);

            while (User::where('username', $username)->exists()) {
                $username = $this->generateUsername($request->name);
            }
            $user->username = $username;
            $user->save();

            $user->syncRoles('agent');

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
            return redirect()->route('dashboard.agents.index')->with('success', 'Agent Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Agent Store Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view agent');

        try {
            $agent = User::with('wallet', 'referrals.wallet')->findOrFail($id);

            $pendingReferralsCount = $agent->referrals()
                ->where('is_approved', '0')
                ->count();

            return view('dashboard.agents.show', compact('agent', 'pendingReferralsCount'));
        } catch (\Throwable $th) {
            Log::error("Agent Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong!");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update agent');
        try {
            $agent = User::findOrFail($id);
            return view('dashboard.agents.edit', compact('agent'));
        } catch (\Throwable $th) {
            Log::error("Agent Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('create agent');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'username' => 'required|string|unique:users,username,' . $id,
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
            $user->username = $request->username;
            if($request->password){
                $user->password = Hash::make($request->password);
            }
            $user->save();

            DB::commit();
            return redirect()->route('dashboard.agents.index')->with('success', 'Agent Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Agent Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete agent');
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->back()->with('success', 'Agent Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Agent Deletion Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update agent');
        try {
            $user = User::findOrFail($id);
            $message = $user->is_active == 'active' ? 'Agent Deactivated Successfully' : 'Agent Activated Successfully';
            if ($user->is_active == 'active') {
                $user->is_active = 'inactive';
                $user->save();
            } else {
                $user->is_active = 'active';
                $user->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Agent Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function generateUsername($name)
    {
        $name = strtolower(str_replace(' ', '', $name));
        $username = $name . rand(1000, 9999);
        return $username;
    }
}
