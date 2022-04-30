<?php

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
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/import', [App\Http\Controllers\ImportController::class, 'index'])->name('import');
Route::post('/import', [App\Http\Controllers\ImportController::class, 'upload'])->name('upload');

Route::post('/sectors', [App\Http\Controllers\HomeController::class, 'getSectors'])->name('sectors');
Route::post('/industries', [App\Http\Controllers\HomeController::class, 'getIndustries'])->name('industries');

Route::post('/scrips', [App\Http\Controllers\HomeController::class, 'getCompanies'])->name('scrips');

Route::get('/sync', [App\Http\Controllers\HomeController::class, 'syncSolr'])->name('sync');

Route::get('logs', [Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return '<h1>Cache facade value cleared</h1>';
});

