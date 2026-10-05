<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::livewire('/login', 'pages::auth.⚡login')->name('login');

Route::group(['middleware' => ['auth']], function () {
    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');
    Route::livewire('/profile', 'pages::profile')->name('profile');
});
