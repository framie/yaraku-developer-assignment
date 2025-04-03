<?php

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

use \App\Http\Controllers\Books\BookController;

// Book Routes.
Route::prefix('books')->name('books.')->group(function () {
    Route::get('/export/{format}/{type}', [BookController::class, 'export'])->name('export');
    Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
    Route::put('/{book}', [BookController::class, 'update'])->name('update');
    Route::post('/', [BookController::class, 'store'])->name('store');
    Route::get('/', [BookController::class, 'index'])->name('index');
});

Route::get('/', function () {
    return view('welcome');
});
