<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register()
    {
        if (Auth::check()) {
            $user = User::findOrFail(auth()->user()->id);

            if ($user->hasRole('user')) {
                return redirect()->route('frontend.home');
            }

            return redirect()->route('dashboard');
        } else {
            return view('auth.register');
        }
    }

    public function register_attempt(Request $request)
    {

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'username' => [
            //     'required',
            //     'string',
            //     'max:255',
            //     'min:3',
            //     'unique:users',
            //     'regex:/^[a-z0-9]+$/',
            // ],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
            'invitation_code' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            // 'confirm-password' => 'required|same:password',
            'withdraw_password' => ['required', 'string', 'min:6'],
        ];

        // Make 'g-recaptcha-response' nullable if CAPTCHA is not enabled
        if (config('captcha.version') !== 'no_captcha') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        } else {
            $rules['g-recaptcha-response'] = 'nullable';
        }

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return Redirect::back()->withErrors($validate)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {
            // Begin a transaction
            $inviter = User::where('username', $request->invitation_code)->first();
            if (!$inviter) {
                return Redirect::back()
                    ->withInput($request->all())
                    ->withErrors(['invitation_code' => 'Invalid invitation code!']);
            }

            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            if ($inviter->hasRole('user')) {
                $user->inviter_id = $inviter->inviter_id;
                $user->referral_user_id = $inviter->id;
            } else{
                $user->inviter_id = $inviter->id;
                $user->referral_user_id = null;
            }
            // $user->email = $request->email;
            $user->email_verified_at = now();
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->withdraw_password = Hash::make($request->withdraw_password);

            $username = $this->generateUsername();

            while (User::where('username', $username)->exists()) {
                $username = $this->generateUsername();
            }
            $user->username = $username;

            $user->is_approved = '1';
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

            // Attempt to authenticate
            Auth::attempt(['phone' => $request->phone, 'password' => $request->password]);
            // Auth::attempt(['email' => $request->email, 'password' => $request->password]);

            // if (Auth::check()) {

            //     VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            //         return (new MailMessage)
            //             ->subject('Verify Email Address')
            //             ->line('Click the button below to verify your email address.')
            //             ->action('Verify Email Address', $url);
            //     });
            // }
            // app('notificationService')->notifyUsers([$user], 'Welcome to ' . Helper::getCompanyName());
            // $user->sendEmailVerificationNotification();

            app('notificationService')->notifyUsers([$user], 'Registered Successfully', 'Welcome to ' . Helper::getCompanyName());

            DB::commit();

            return redirect()->route('login')->with('success', 'Your account has been created successfully.');
        } catch (\Throwable $th) {
            DB::rollback();
            // Log the error for debugging
            Log::error('User registration failed', ['error' => $th->getMessage()]);
            return redirect()->back()->withInput($request->all())->with('error', $th->getMessage());
            // throw $th;
        }
    }

    public function generateUsername()
    {
        return rand(10000, 99999);
    }

    // public function generateUsername($name)
    // {
    //     $name = strtolower(str_replace(' ', '', $name));
    //     $username = $name . rand(1000, 9999);
    //     return $username;
    // }

    // protected function generateUsername($name)
    // {
    //     $firstThreeLetters = strtoupper(substr($name, 0, 3));
    //     $randomNumber = rand(1000, 999999);
    //     return $firstThreeLetters . $randomNumber;
    // }
}
