<?php

// Imports
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
                    DashboardController,
                    VoteController
                };

// Auth Routes
Auth::routes();

// Routes
Route::resource('dashboard', DashboardController::class);
Route::resource('votes', VoteController::class) -> middleware('auth');
