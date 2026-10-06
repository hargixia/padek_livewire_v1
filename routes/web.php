<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
    Route::livewire('/profile', 'pages::profile')->name('profile');
});
