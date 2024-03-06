<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BodyTypeController;
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


    // Admin routes
    Route::prefix('admin')->group(function () {

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
                'make-models' => MakeModelController::class,
            ]);
        });
    });
});
