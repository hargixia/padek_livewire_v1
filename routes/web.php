<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::livewire('/masuk', 'pages::auth.⚡login')->name('login');
Route::livewire('/daftar', 'pages::auth.⚡register')->name('register');

Route::get('/test/{data}', function ($data) {
    $api_support = new \App\Http\Controllers\api_support();
    $result = $api_support->my_encrypt($data);
    return response()->json(['result' => $result]);
});

Route::group(['middleware' => ['auth']], function () {
    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');

    Route::livewire('/permainan','pages::permainan.index')->name('permainan');
    Route::livewire('/skor','pages::skors.index')->name('skor');
    Route::livewire('/users','pages::users.index')->name('users');

    Route::livewire('/profile', 'pages::users.profile')->name('profile');

    Route::post('/logout', function () {
        auth()->logout();
        return redirect()->route('login');
    })->name('logout');
});
