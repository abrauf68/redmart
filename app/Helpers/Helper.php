<?php

namespace App\Helpers;

use App\Models\BusinessSetting;
use App\Models\CompanySetting;
use App\Models\Order;
use App\Models\SystemSetting;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Withdraw;
use Carbon\Carbon;

class Helper
{
    public static function dashboard_route()
    {
        $user = User::find(Auth::user()->id);
        $route = $user->role->role.'.dashboard';
        return $route;
    }
    public static function getLogoLight()
    {
        return CompanySetting::first()->light_logo ?? asset('assets/img/logo/logo.png');
    }
    public static function getLogoDark()
    {
        return CompanySetting::first()->dark_logo ?? asset('assets/img/logo/logo.png');
    }
    public static function getFavicon()
    {
        return CompanySetting::first()->favicon ?? asset('assets/img/favicon/fav.png');
    }
    public static function getCompanyName()
    {
        return CompanySetting::first()->company_name ?? env('APP_NAME');
    }
    public static function getTimezone()
    {
        $systemSetting = SystemSetting::with('timezone')->first();
        return $systemSetting->timezone->name ?? env('APP_TIMEZONE', 'UTC');
    }
    public static function getDefaultLanguage()
    {
        $systemSetting = SystemSetting::with('language')->first();
        return $systemSetting->language->iso_code ?? 'en';
    }
    public static function getfooterText()
    {
        return SystemSetting::first()->footer_text ?? 'All Copyrights Reserved';
    }
    public static function getMaxUploadSize()
    {
        $sizeInKB = SystemSetting::first()->max_upload_size ?? 2048; // Stored in KB

        return self::humanReadableSize($sizeInKB * 1024); // Convert KB to Bytes
    }

    // Helper function to format size into KB, MB, GB, etc.
    public static function humanReadableSize($bytes, $decimals = 2)
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $sizes[$factor];
    }


    // example use of currency format {{ \App\Helpers\Helper::formatCurrency($price) }}
    public static function formatCurrency($amount)
    {
        $currencySetting = SystemSetting::first();

        if (!$currencySetting) {
            return $amount; // Return the amount as is if settings are not found
        }

        $symbol = $currencySetting->currency_symbol;
        $position = $currencySetting->currency_symbol_position; // 'prefix' or 'postfix'

        if ($position === 'prefix') {
            return $symbol . number_format($amount, 2);
        }

        return number_format($amount, 2) . $symbol;
    }

    public static function renderRecaptcha($formId, $action = 'register')
    {
        if (config('captcha.version') === 'v3') {
            return self::renderRecaptchaV3($formId, $action);
        }
    }

    private static function renderRecaptchaV3($formId, $action)
    {
        $siteKey = config('captcha.sitekey');
        return <<<HTML
            <script src="https://www.google.com/recaptcha/enterprise.js?render={$siteKey}"></script>
            <script>
                function handleRecaptcha(formId, action) {
                    document.getElementById(formId).addEventListener('submit', function(e) {
                        e.preventDefault();
                        grecaptcha.enterprise.ready(async () => {
                            try {
                                const token = await grecaptcha.enterprise.execute('{$siteKey}', { action: action });
                                const form = document.getElementById(formId);
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'g-recaptcha-response';
                                input.value = token;
                                form.appendChild(input);
                                form.submit();
                            } catch (error) {
                                console.error('Error executing reCAPTCHA:', error);
                            }
                        });
                    });
                }

                document.addEventListener('DOMContentLoaded', function() {
                    handleRecaptcha('{$formId}', '{$action}');
                });
            </script>
        HTML;
    }

    public static function generateUniqueWalletAddress()
    {
        do {
            $address = 'WALLET-' . strtoupper(bin2hex(random_bytes(8)));
        } while (\App\Models\Wallet::where('wallet_address', $address)->exists());

        return $address;
    }

    public static function pendingCount($type)
    {
        $user = User::find(auth()->user()->id);

        if (!$user) return 0;

        // ADMIN
        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {

            return match ($type) {
                'orders' => Order::where('status', 'pending')->count(),
                'withdraws' => Withdraw::where('status', 'pending')->count(),
                'recharges' => Transaction::where('transaction_type', 'recharge')
                                ->where('status', 'pending')
                                ->count(),
                'customers' => User::role('user')->where('is_approved', '0')->count(),
                default => 0
            };
        }

        // AGENT
        $customerIds = User::where('inviter_id', $user->id)->pluck('id');

        return match ($type) {
            'orders' => Order::whereIn('user_id', $customerIds)
                            ->where('status', 'pending')
                            ->count(),

            'withdraws' => Withdraw::whereIn('user_id', $customerIds)
                            ->where('status', 'pending')
                            ->count(),

            'recharges' => Transaction::whereIn('user_id', $customerIds)
                            ->where('transaction_type', 'recharge')
                            ->where('status', 'pending')
                            ->count(),

            'customers' => User::role('user')->whereIn('id', $customerIds)->where('is_approved', '0')->count(),

            default => 0
        };
    }
}
