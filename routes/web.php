<?php

// Importing necessary classes for route definition
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleInfoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Group 1: Vehicle Management Routes

// Displaying the welcome view
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    // Routes for VehicleInfoController
    Route::get('/parked-fleet-overview', [VehicleInfoController::class, 'index']);
    Route::post('/add-vehicle', [VehicleInfoController::class, 'store']);
    Route::get('/list-vehicles', [VehicleInfoController::class, 'list']);
    Route::get('vehicle-details/{id}', [VehicleInfoController::class, 'show']);
    Route::delete('delete-vehicle/{id}', [VehicleInfoController::class, 'destroy']);
    Route::resource('vehicles', VehicleInfoController::class);
    Route::post('/checkout-vehicle/{vehicleId}', [VehicleInfoController::class, 'checkoutVehicle']);
    Route::get('/departure-record-datatable', [VehicleInfoController::class, 'departureRecord'])->name('departure.record.datatable');
    // Route::get('/checked-out-vehicles', [VehicleInfoController::class, 'getCheckedOutVehicles']);
    Route::get('/receipt/{id}', [VehicleInfoController::class, 'showReceipt']);
    Route::get('/checked-out-vehicles', [VehicleInfoController::class, 'getCheckedOutVehicles'])->name('getCheckedOutVehicles');


    // Routes for ReportController
    Route::get('/report/generator', [ReportController::class, 'showReportGenerator'])->name('showReportGenerator');
    Route::get('/report/get-periodic-report', [ReportController::class, 'getPeriodicReport'])->name('getPeriodicReport');
});

// Group 2: Authentication and User Management Routes

// Redirecting to dashboard if authenticated
Route::get('/', function () {return redirect('/dashboard');})->middleware('auth');

// Registration routes
Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');

// Login routes
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');

// Password reset routes
Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');

// Change password routes
Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');

// Dashboard route
Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Group 3: User Profile and Static Page Routes

// Routes within this group are protected by 'auth' middleware
Route::group(['middleware' => 'auth'], function () {
    // Route::get('/virtual-reality', [PageController::class, 'vr'])->name('virtual-reality');
    // Route::get('/rtl', [PageController::class, 'rtl'])->name('rtl');
    // Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
    // Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    // Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
    // Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
    // Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
    // Route::get('/{page}', [PageController::class, 'index'])->name('page');
    // Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/income-report', [ReportController::class, 'incomeReport'])->name('income-report');



Route::get('/generate-report', [ReportController::class, 'generateReport'])->name('generateReport');