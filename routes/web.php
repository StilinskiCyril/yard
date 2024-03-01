<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Register company step one
Route::post('/register/company', [HomeController::class, 'registerStepOne'])->name('home.register-step-one');
// Register company step two
Route::get('/register2/company', [HomeController::class, 'showRegistrationStepTwo'])->name('home.show-registration-form-step-two');
Route::post('/register2/company', [HomeController::class, 'registerStepTwo'])->name('home.register-step-two');

Route::middleware(['auth:web'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [HomeController::class, 'showDashboard'])->name('home.show-dashboard');
    // Logout
    Route::get('logout', [LoginController::class, 'logout'])->name('home.logout');
});
