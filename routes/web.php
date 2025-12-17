<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/race-management', function () {
    return view('race-management');
})->middleware(['auth'])->name('race-management');

Route::get('/export/standings/{day?}', [\App\Http\Controllers\ExportController::class, 'standings'])
    ->middleware(['auth'])
    ->name('export.standings');

Route::get('/print/standings/{day?}', [\App\Http\Controllers\ExportController::class, 'print'])
    ->middleware(['auth'])
    ->name('print.standings');

Route::get('/leaderboard', function () {
    return view('leaderboard');
})->name('leaderboard');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
