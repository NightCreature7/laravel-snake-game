<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'showGame']);
Route::post('/store-score', [GameController::class, 'storeScore'])->name('storeScore');
Route::get('/leaderboard', [GameController::class, 'showLeaderboard'])->name('leaderboard');
