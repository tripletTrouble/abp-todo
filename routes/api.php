<?php

use App\Http\Controllers\ActivityGroupController;
use App\Http\Controllers\TodoController;
use \Illuminate\Support\Facades\Route;

Route::prefix('activity-groups')
    ->controller(ActivityGroupController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::post('', 'store');
        Route::get('/{id}', 'show');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

Route::prefix('todo-items')
    ->controller(TodoController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::post('', 'store');
        Route::get('/{id}', 'show');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });