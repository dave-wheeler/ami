<?php

use App\Http\Controllers\DailyUsageComparisonController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');

Route::get('/stats', [StatsController::class, 'index'])->name('stats.form');
Route::post('/stats', [StatsController::class, 'show'])->name('stats.show');

Route::get('/compare', [DailyUsageComparisonController::class, 'index'])->name('compare.form');
Route::post('/compare', [DailyUsageComparisonController::class, 'show'])->name('compare.show');
