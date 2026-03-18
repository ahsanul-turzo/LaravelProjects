<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('components.todo');
})->name('home');

Route::get('/greeting2', function () {
    return 'Hello World';
})->name('greeting');
