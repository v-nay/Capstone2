<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MotelController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
    // return view('auth.login');
});
Route::middleware(['auth', 'verified'])->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'filter'])->name('dashboard');
    Route::post('/export-hotels', [DashboardController::class, 'export'])->name('hotels.export');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name(
        'profile.edit'
    );
    Route::patch('/profile', [ProfileController::class, 'update'])->name(
        'profile.update'
    );
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name(
        'profile.destroy'
    );
    // Route::resource('/profile', [ProfileController::class, 'destroy'])->name(
    //     'profile.destroy'
    // );
    Route::get('/motels', [MotelController::class, 'getMotels']);
    Route::get('/search-all-motels', [MotelController::class, 'searchAllMotels']);
    Route::get('/index', [MotelController::class, 'index'])->name('motel');
    Route::get('/motels-chart', [MotelController::class, 'showMotelScores']);
});
Route::get('/motels-store', [MotelController::class, 'store']);


require __DIR__ . '/auth.php';
