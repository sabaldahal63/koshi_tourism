<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TourismController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\UserAuthController;

// Public Website Routes
Route::get('/',         [TourismController::class, 'index'])->name('home');
Route::get('/explore',  [TourismController::class, 'explore'])->name('explore');
Route::get('/stay',     [TourismController::class, 'stay'])->name('stay');
Route::get('/planner',  [TourismController::class, 'planner'])->name('planner');
Route::get('/guides',   [TourismController::class, 'guides'])->name('guides');
Route::get('/advisor',  [TourismController::class, 'advisor'])->name('advisor');
Route::get('/bookings', [TourismController::class, 'bookings'])->name('bookings');

// Customer / Traveler Authentication Routes
Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
Route::post('/register',[UserAuthController::class, 'register'])->name('register.submit');
Route::get('/login',    [UserAuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [UserAuthController::class, 'login'])->name('login.submit');
Route::post('/logout',  [UserAuthController::class, 'logout'])->name('logout');
Route::get('/dashboard',[UserAuthController::class, 'dashboard'])->name('user.dashboard');

// Public API Endpoints
Route::get('/api/destinations',    [TourismController::class, 'getDestinationsApi'])->name('api.destinations');
Route::get('/api/hotels',          [TourismController::class, 'getHotelsApi'])->name('api.hotels');
Route::get('/api/bookings',        [TourismController::class, 'getBookingsApi'])->name('api.bookings.get');
Route::post('/api/bookings',       [TourismController::class, 'storeBookingApi'])->name('api.bookings.store');
Route::delete('/api/bookings/{id}',[TourismController::class, 'cancelBookingApi'])->name('api.bookings.cancel');

// Staff / Admin Authentication Routes
Route::get('/admin/login',         [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login',        [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout',       [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Protected Dashboard Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                        [AdminController::class, 'index'])->name('dashboard');
    Route::post('/bookings/{id}/status',   [AdminController::class, 'updateStatus'])->name('bookings.status');
    Route::delete('/bookings/{id}',        [AdminController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/destinations',            [AdminController::class, 'destinations'])->name('destinations');
    Route::get('/hotels',                  [AdminController::class, 'hotels'])->name('hotels');
    Route::get('/guides',                  [AdminController::class, 'guides'])->name('guides');
});
