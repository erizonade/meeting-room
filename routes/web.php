<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsumptionController;
use App\Http\Controllers\MeetingRoomController;
use App\Http\Controllers\OrderMeetingRoomController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login.index');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});


Route::middleware('auth')->prefix('meeting')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Meeting/Dashboard');
    })->name('dashboard');

    Route::middleware('auth.check:super-admin')->group(function () {
        Route::resource('user', UserController::class)->names('user');
        Route::resource('unit', UnitController::class)->names('unit');
        Route::resource('meeting-room', MeetingRoomController::class)->names('meeting-room');
    });
    Route::resource('order-meeting', OrderMeetingRoomController::class)->names('order-meeting');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
