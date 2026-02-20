<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('admin');
});

Route::any('', function () {
    return null;
})->name('login');
