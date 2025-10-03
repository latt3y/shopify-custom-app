<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::get('/products', function () {
    return Inertia::render('products');
})->name('products');

Route::get('/help', function () {
    return Inertia::render('help');
})->name('help');
