<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\YiviController;
use App\Http\Controllers\PrivacyStatementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('privacy-statement', PrivacyStatementController::class)->name('privacy-statement');

Route::middleware(['guest'])->group(function () {
    Route::get('/', HomeController::class)
        ->name('home');
    Route::get('/login', [AuthController::class, 'login'])
        ->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/yivi-disclosure', [YiviController::class, 'disclosures'])
        ->name('yivi-disclosure');
    Route::post('/yivi-session/start', [YiviController::class, 'start']);

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
