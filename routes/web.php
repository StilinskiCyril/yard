<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BodyTypeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CountyController;
use App\Http\Controllers\DriveSetupController;
use App\Http\Controllers\DriveTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MakeController;
use App\Http\Controllers\MakeModelController;
use App\Http\Controllers\TransmissionTypeController;
use App\Http\Controllers\VehicleConditionController;
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

// Load app metadata
Route::post('counties/load', [CountyController::class, 'load'])->name('counties.load');
Route::post('body-types/load', [BodyTypeController::class, 'load'])->name('body-types.load');
Route::post('drive-setups/load', [DriveSetupController::class, 'load'])->name('drive-setups.load');
Route::post('drive-types/load', [DriveTypeController::class, 'load'])->name('drive-types.load');
Route::post('transmission-types/load', [TransmissionTypeController::class, 'load'])->name('transmission-types.load');
Route::post('vehicle-conditions/load', [VehicleConditionController::class, 'load'])->name('vehicle-conditions.load');
Route::post('makes/load', [MakeController::class, 'load'])->name('makes.load');
Route::post('make-models/{make}/load', [MakeModelController::class, 'load'])->name('make-models.load');

// Register company step one
Route::post('register/company', [HomeController::class, 'registerStepOne'])->name('home.register-step-one');
// Register company step two
Route::get('register2/company', [HomeController::class, 'showRegistrationStepTwo'])->name('home.show-registration-form-step-two');
Route::post('register2/company', [HomeController::class, 'registerStepTwo'])->name('home.register-step-two');

Route::middleware(['auth:web'])->group(function () {
    // Dashboard
    Route::get('dashboard', [HomeController::class, 'showDashboard'])->name('home.show-dashboard');
    // Logout
    Route::get('logout', [LoginController::class, 'logout'])->name('home.logout');

    // Company admin routes
    Route::prefix('companies')->middleware(['role:admin|company-admin'])->group(function () {
        // Show profile
        Route::get('profile', [CompanyController::class, 'showProfile'])->name('companies.show-profile');
        // Load profile
        Route::post('profile/{company}', [CompanyController::class, 'loadProfile'])->name('companies.load-profile');
        // Update profile
        Route::put('profile/{company}/update', [CompanyController::class, 'updateProfile'])->name('companies.update-profile');
        // Show users
        Route::get('users', [CompanyController::class, 'showUsers'])->name('companies.show-users');
        // Load users
        Route::post('users/{company}', [CompanyController::class, 'loadUsers'])->name('companies.load-users');
        // Create user
        Route::post('users/{company}/create', [CompanyController::class, 'createUser'])->name('companies.create-user');
        // Update user
        Route::put('users/{user}/update', [CompanyController::class, 'updateUser'])->name('companies.update-user');
        // Remove
        Route::delete('users/{company}/{user}/remove', [CompanyController::class, 'removeUser'])->name('companies.remove-user');
        // Show wallet
        Route::get('wallet', [CompanyController::class, 'showWallet'])->name('companies.show-wallet');
        // Load wallet transactions
        Route::post('wallet/{company}/transactions', [CompanyController::class, 'loadWalletTransactions'])->name('companies.load-wallet-transactions');
    });

    // Customer support routes
    Route::prefix('customer-support')->middleware(['role:admin|customer-support'])->group(function () {
        Route::post('companies/load', [CompanyController::class, 'load'])->name('companies.load');
    });

    // Admin routes
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {

        // App metadata
        Route::prefix('app-metadata')->group(function () {
            Route::resources([
                'counties' => CountyController::class,
                'body-types' => BodyTypeController::class,
                'drive-setups' => DriveSetupController::class,
                'drive-types' => DriveTypeController::class,
                'transmission-types' => TransmissionTypeController::class,
                'vehicle-conditions' => VehicleConditionController::class,
                'makes' => MakeController::class,
                'make-models' => MakeModelController::class
            ]);
        });
    });
});
