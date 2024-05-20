<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('roles', \App\Http\Controllers\RoleController::class);
    Route::resource('permissions', \App\Http\Controllers\PermissionController::class);
    Route::resource('lang', \App\Http\Controllers\LangController::class);
    Route::resource('circle', \App\Http\Controllers\CircleController::class);
    Route::resource('card', \App\Http\Controllers\CardController::class);
    Route::resource('ccard', \App\Http\Controllers\CircleCardController::class);
    Route::resource('bilet', \App\Http\Controllers\BiletController::class);
});
