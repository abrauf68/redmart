<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GithubController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\RechargeController;
use App\Http\Controllers\Dashboard\RolePermission\PermissionController;
use App\Http\Controllers\Dashboard\RolePermission\RoleController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\TransactionController;
use App\Http\Controllers\Dashboard\User\AgentController;
use App\Http\Controllers\Dashboard\User\ArchivedUserController;
use App\Http\Controllers\Dashboard\User\CustomerController;
use App\Http\Controllers\Dashboard\User\UserController;
use App\Http\Controllers\Dashboard\WithdrawController;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Middleware\CheckAccountActivation;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/lang/{lang}', function ($lang) {
    // dd($lang);
    if (! in_array($lang, ['en', 'fr', 'ar', 'de'])) {
        abort(404);
    } else {
        session(['locale' => $lang]);
        App::setLocale($lang);
        Log::info("Locale set to: " . $lang);
        return redirect()->back();
    }
})->name('lang');

Route::get('/current-time', function () {
    return response()->json([
        'time' => Carbon::now()->format('h:iA') // Returns time in 12-hour format with AM/PM
    ]);
});

Auth::routes();
// Guest Routes
Route::group(['middleware' => ['guest']], function () {

    //User Login Authentication Routes
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login-attempt', [LoginController::class, 'login_attempt'])->name('login.attempt');

    //User Register Authentication Routes
    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('registration-attempt', [RegisterController::class, 'register_attempt'])->name('register.attempt');

    // Google Authentication Routes
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google.login');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.login.callback');
    // Github Authentication Routes
    Route::get('auth/github', [GithubController::class, 'redirectToGithub'])->name('auth.github.login');
    Route::get('auth/github/callback', [GithubController::class, 'handleGithubCallback'])->name('auth.github.login.callback');
    // Facebook Authentication Routes
    // Route::controller(FacebookController::class)->group(function () {
    //     Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    //     Route::get('auth/facebook/callback', 'handleFacebookCallback');
    // });

});

// Authentication Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('login-verification', [AuthController::class, 'login_verification'])->name('login.verification');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('verify-account', [AuthController::class, 'verify_account'])->name('verify.account');
    Route::post('resend-code', [AuthController::class, 'resend_code'])->name('resend.code');

    // Verified notification
    Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verification_verify'])->middleware(['signed'])->name('verification.verify');
    Route::get('email/verify', [AuthController::class, 'verification_notice'])->name('verification.notice');
    Route::post('email/verification-notification', [AuthController::class, 'verification_send'])->middleware(['throttle:2,1'])->name('verification.send');
    // Verified notification
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/deactivated', function () {
        return view('errors.deactivated');
    })->name('deactivated');
    Route::get('/approval-pending', function () {
        return view('errors.pending');
    })->name('pending');
    Route::middleware(['check.activation', 'not.user'])->group(function () {
        Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

        // Admin Dashboard Authentication Routes
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::resource('profile', ProfileController::class);
            Route::post('profile/setting/account/{id}', [ProfileController::class, 'accountDeactivation'])->name('account.deactivate');
            Route::post('profile/security/password/{id}', [ProfileController::class, 'passwordUpdate'])->name('update.password');

            Route::get('/notifications', [NotificationController::class, 'index']);
            Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
            Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
            Route::post('/notifications/{id}/delete', [NotificationController::class, 'deleteNotification']);
            Route::get('/notifications/send-test-noti/{id}', [NotificationController::class, 'testNotification']);

            Route::resource('user', UserController::class);
            Route::get('user/status/{id}', [UserController::class, 'updateStatus'])->name('user.status.update');

            Route::resource('agents', AgentController::class);
            Route::get('agents/status/{id}', [AgentController::class, 'updateStatus'])->name('agents.status.update');

            Route::resource('customers', CustomerController::class);
            Route::get('customers/status/{id}', [CustomerController::class, 'updateStatus'])->name('customers.status.update');
            Route::post('customers/wallet/update/{id}', [CustomerController::class, 'updateCustomerWallet'])->name('customers.wallet.update');
            Route::post('customers/score/update/{id}', [CustomerController::class, 'updateCustomerScore'])->name('customers.score.update');
            Route::post('customers/special-order/update/{id}', [CustomerController::class, 'updateCustomerSpecialOrder'])->name('customers.special-order.update');
            Route::post('customers/bank-details/update/{id}', [CustomerController::class, 'updateBankDetails'])->name('customers.bank-details.update');
            Route::post('approve/customers/{id}', [CustomerController::class, 'approveCustomer'])->name('customers.approve');

            Route::resource('archived-user', ArchivedUserController::class);
            Route::get('user/restore/{id}', [ArchivedUserController::class, 'restoreUser'])->name('archived-user.restore');

            // Role & Permission Start
            Route::resource('permissions', PermissionController::class);

            Route::resource('roles', RoleController::class);
            //Role & Permission End

            // Setting Routes
            Route::resource('setting', SettingController::class);
            Route::put('company/setting/{id}', [SettingController::class, 'updateCompanySettings'])->name('setting.company.update');
            Route::put('recaptcha/setting/{id}', [SettingController::class, 'updateRecaptchaSettings'])->name('setting.recaptcha.update');
            Route::put('system/setting/{id}', [SettingController::class, 'updateSystemSettings'])->name('setting.system.update');
            Route::put('email/setting/{id}', [SettingController::class, 'updateEmailSettings'])->name('setting.email.update');
            Route::post('send-mail/setting', [SettingController::class, 'sendTestMail'])->name('setting.send_test_mail');

            // User Dashboard Authentication Routes
            Route::resource('products', ProductController::class);
            Route::get('products/status/{id}', [ProductController::class, 'updateStatus'])->name('products.status.update');

            Route::resource('orders', OrderController::class);

            Route::resource('withdraws', WithdrawController::class);

            Route::resource('recharges', RechargeController::class);
            
            Route::get('transactions/receipt/{id}', [TransactionController::class, 'receipt'])->name('transactions.receipt');

        });
    });
});

// Frontend Pages Routes
Route::name('frontend.')->group(function () {
    Route::middleware(['auth', 'verified', 'check.activation', 'check.approval'])->group(function () {
        Route::get('/', [FrontendHomeController::class, 'home'])->name('home');
        Route::get('/support', [FrontendHomeController::class, 'support'])->name('support');
        Route::get('/recharge', [FrontendHomeController::class, 'recharge'])->name('recharge');
        Route::post('/submit/recharge', [FrontendHomeController::class, 'submitRecharge'])->name('recharge.submit');
        Route::get('/start', [FrontendHomeController::class, 'start'])->name('start');
        Route::post('/grab-order', [FrontendHomeController::class, 'grabOrder'])->name('grab.order');
        Route::get('/orders', [FrontendHomeController::class, 'orders'])->name('orders');
        Route::post('/order/proceed', [FrontendHomeController::class, 'proceed'])->name('order.proceed');
        Route::get('/wallet', [FrontendHomeController::class, 'wallet'])->name('wallet');
        Route::get('/withdraw', [FrontendHomeController::class, 'withdraw'])->name('withdraw');
        Route::post('/submit/withdraw', [FrontendHomeController::class, 'submitWithdraw'])->name('withdraw.submit');
        Route::get('/profile', [FrontendHomeController::class, 'profile'])->name('profile');
        Route::put('update/profile', [FrontendHomeController::class, 'updateProfile'])->name('profile.update');
        Route::post('bank-details/update', [FrontendHomeController::class, 'updateBankDetails'])->name('bank-details.update');
        Route::put('password/update', [FrontendHomeController::class, 'updatePassword'])->name('password.update');
        Route::get('/products', [FrontendHomeController::class, 'products'])->name('products');
        Route::get('/products/{sku}', [FrontendHomeController::class, 'productDetails'])->name('products.details');
        Route::get('/notifications', [FrontendHomeController::class, 'notifications'])->name('notifications');
        Route::post('/notifications/mark-all-read', [FrontendHomeController::class, 'markAllReadNoti'])->name('notifications.markAllRead');
        Route::delete('/notifications/delete-all', [FrontendHomeController::class, 'deleteAllNoti'])->name('notifications.deleteAll');
        Route::post('/notifications/{id}/mark-read', [FrontendHomeController::class, 'markReadNoti'])->name('notifications.markRead');
        Route::delete('/notifications/{id}', [FrontendHomeController::class, 'deleteNoti'])->name('notifications.delete');
    });
});


//Artisan Routes
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('optimize:clear');
    return "Application cache cleared!";
})->name('clear.cache');
