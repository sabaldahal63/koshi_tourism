<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TourismController;

Route::get('/',         [TourismController::class, 'index'])->name('home');
Route::get('/explore',  [TourismController::class, 'explore'])->name('explore');
Route::get('/stay',     [TourismController::class, 'stay'])->name('stay');
Route::get('/planner',  [TourismController::class, 'planner'])->name('planner');
Route::get('/guides',   [TourismController::class, 'guides'])->name('guides');
Route::get('/advisor',  [TourismController::class, 'advisor'])->name('advisor');
Route::get('/bookings', [TourismController::class, 'bookings'])->name('bookings');

Route::get('/api/destinations', [TourismController::class, 'getDestinationsApi'])->name('api.destinations');
Route::get('/api/hotels',       [TourismController::class, 'getHotelsApi'])->name('api.hotels');
