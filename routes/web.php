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

Route::get('/qualifying', function () {
    return view('qualifying');
})->middleware(['auth'])->name('qualifying');

Route::get('/qualifying-leaderboard', function () {
    return view('qualifying-leaderboard');
})->name('qualifying-leaderboard');

Route::get('/export/qualifying', [\App\Http\Controllers\ExportController::class, 'qualifyingCsv'])
    ->middleware(['auth'])
    ->name('export.qualifying');

Route::get('/print/qualifying', [\App\Http\Controllers\ExportController::class, 'qualifyingPrint'])
    ->middleware(['auth'])
    ->name('print.qualifying');

Route::get('/export/qualifying-all', [\App\Http\Controllers\ExportController::class, 'qualifyingAllCsv'])
    ->middleware(['auth'])
    ->name('export.qualifying.all');

Route::get('/print/qualifying-all', [\App\Http\Controllers\ExportController::class, 'qualifyingAllPrint'])
    ->middleware(['auth'])
    ->name('print.qualifying.all');

Route::get('/export/qualifying-all-days', [\App\Http\Controllers\ExportController::class, 'qualifyingAllDaysCsv'])
    ->middleware(['auth'])
    ->name('export.qualifying.all.days');

Route::get('/print/qualifying-all-days', [\App\Http\Controllers\ExportController::class, 'qualifyingAllDaysPrint'])
    ->middleware(['auth'])
    ->name('print.qualifying.all.days');

Route::get('/print/crossing-day', [\App\Http\Controllers\ExportController::class, 'crossingDayPrint'])
    ->middleware(['auth'])
    ->name('print.crossing.day');

Route::get('/export/crossing-day', [\App\Http\Controllers\ExportController::class, 'crossingDayCsv'])
    ->middleware(['auth'])
    ->name('export.crossing.day');

Route::get('/print/all-crossings', [\App\Http\Controllers\ExportController::class, 'allCrossingsPrint'])
    ->middleware(['auth'])
    ->name('print.all.crossings');

Route::get('/export/all-crossings', [\App\Http\Controllers\ExportController::class, 'allCrossingsCsv'])
    ->middleware(['auth'])
    ->name('export.all.crossings');

Route::get('/export/mrp-lineup', [\App\Http\Controllers\ExportController::class, 'mrpLineup'])
    ->middleware(['auth'])
    ->name('export.mrp.lineup');

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
