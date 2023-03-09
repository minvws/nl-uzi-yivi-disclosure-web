<?php

declare(strict_types=1);

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

// FIXME: configure timeout auth session
Route::middleware(['auth.uzi'])->group(function () {
    Route::get('/irma-disclosure', [\App\Http\Controllers\IrmaController::class, 'disclosures']);
    Route::post('/disclose', [\App\Http\Controllers\IrmaController::class, 'disclose']);
    Route::post('/irma/start', [\App\Http\Controllers\IrmaController::class, 'start']);
    Route::get('/irma/result', [\App\Http\Controllers\IrmaController::class, 'result']);
});

Route::get('/uzi-login', [\App\Http\Controllers\Auth\UziAuthController::class, 'login'])
                ->middleware(['guest'])
                ->name('uzi.login');

Route::get('/', function () {
    return redirect('/irma-disclosure');
});
