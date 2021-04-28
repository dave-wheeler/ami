<?php

use App\Http\Controllers\DailyUsageComparisonController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('/stats', [StatsController::class, 'show'])->name('stats.api');

Route::post('/compare', [DailyUsageComparisonController::class, 'show'])->name('compare.api');
