<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\IrmaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

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

// Change the language of the page
Route::get('ChangeLanguage/{locale}', function ($locale) {
    if (in_array($locale, Config::get('app.locales'))) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('changelang');

Route::middleware(['auth'])->group(function () {
    Route::get('/irma-disclosure', [IrmaController::class, 'disclosures']);
    Route::post('/disclose', [IrmaController::class, 'disclose']);
    Route::post('/irma/start', [IrmaController::class, 'start']);
    Route::get('/irma/result', [IrmaController::class, 'result']);

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::redirect('/', '/irma-disclosure');

Route::get('/login', [AuthController::class, 'login'])
    ->middleware(['guest'])
    ->name('login');
