<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksController;
use App\Http\Middleware\CheckAdmin;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', AuthController::class . '@getLogin')->name('login');


Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'getLogin')->name('auth.getLogin');
    Route::post('login', 'postLogin')->name('auth.postLogin');

    Route::get('register', 'getRegister')->name('auth.getRegister');
    Route::post('register', 'postRegister')->name('auth.postRegister');

    Route::post('/logout', 'logout')->name('auth.logout');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::middleware([CheckAdmin::class])->group(function () {
        Route::controller(BooksController::class)->group(function() {
            Route::get('books', 'index')->name('books.index');
            Route::delete('book/{id}', 'deleteBook')->name('books.delete');
            Route::post('book/', 'store')->name('books.store');
        });
    });
});
