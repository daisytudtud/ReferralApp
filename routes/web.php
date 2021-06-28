<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReferralController;

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

Route::get('/', function () {
    if(Auth::check()) {
        if(Auth::user()->hasRole('user')) {
            return redirect()->route('referrals');
        }

        if(Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.referrals');
        }
    }
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals');
Route::post('/referrals', [ReferralController::class, 'store']);
Route::get('/admin/referrals', [ReferralController::class, 'referral_list'])->name('admin.referrals');
Route::get('/referrals/successful_referrals', [ReferralController::class, 'successful_referrals'])->name('successful_referrals');

require __DIR__.'/auth.php';
