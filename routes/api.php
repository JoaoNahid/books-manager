<?php

use App\Http\Controllers\AuthorsController;
use App\Http\Middleware\ApiAccess;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware([ApiAccess::class])->group(function () {
    Route::controller(AuthorsController::class)->group(function () {
        Route::get('/authors/{id?}', 'getAuthors');
        Route::post('/authors', 'store');
        Route::put('/author/{id}', 'update');
        Route::delete('/author/{id}', 'destroy');
    });
});
