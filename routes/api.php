<?php

use App\Http\Controllers\MpesaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    /**
     * M-pesa Api
     */
    Route::prefix('c2b')->group(function () {
        // Generate access token
//        Route::post('generate-access-token', [MpesaController::class, 'generateC2bAccessToken'])->name('mpesa.generate-c2b-access-token');
        // Register validation and confirmation url
        Route::post('952c-4bf2-a3ek-74f9/register/url', [MpesaController::class, 'registerValidationAndConfirmationUrls'])->name('mpesa.register-url');
        // Validation url
        Route::post('855c-9edf-a5dd-2990/validation', [MpesaController::class, 'validationUrl'])->name('mpesa.validation');
        // C2B callback url
        Route::post('473c-5af2-a5cd-5458/confirmation', [MpesaController::class, 'confirmationUrl'])->name('mpesa.confirmation-url');
        // Initiate stk push
        Route::post('stk/push', [MpesaController::class, 'stKPush'])->name('mpesa.stk-push');
        // Stk callback url
        Route::post('stk/transaction/callback', [MpesaController::class, 'stkCallback'])->name('mpesa.stk-callback');
    });
});
