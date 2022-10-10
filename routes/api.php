<?php

use Illuminate\Http\Request;
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

Route::middleware('auth.basic')->group(function () {
    Route::get('/books', 'BooksController@index')->name('books.index');
    Route::post('/books/{id}/reviews', 'BooksReviewController@store');
    Route::delete('/books/{bookId}/reviews/{reviewId}', 'BooksReviewController@destroy');
    Route::middleware('auth.admin')->group(function () {
        Route::post('/books', 'BooksController@store');
    });
});
