<?php

use App\Http\Controllers\BooksController;
use App\Http\Middleware\CheckAdmin;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::middleware([CheckAdmin::class])->group(function () {
        Route::controller(BooksController::class)->group(function() {
            Route::get('books', 'index')->name('books.index');
        });
    });
});

require __DIR__.'/auth.php';
